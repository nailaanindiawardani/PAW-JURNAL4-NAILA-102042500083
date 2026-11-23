<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionController extends Controller
{
    public function store(Request $request, Assignment $assignment): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->isMahasiswa()) {
            abort(403, 'Akses ditolak. Pengumpulan tugas hanya dapat dilakukan oleh Mahasiswa.');
        }

        $user = Auth::user();

        $enrollment = $assignment->course->students()->where('users.id', $user->id)->first();
        if (!$enrollment || $enrollment->pivot->status !== 'approved') {
            abort(403, 'Akses ditolak. Anda belum disetujui oleh Dosen untuk mengikuti mata kuliah tugas ini.');
        }

        if ($assignment->isOverdue()) {
            return back()->with('error', 'Gagal mengumpulkan: Batas waktu pengumpulan (deadline) tugas telah berakhir.');
        }

        $validated = $request->validate([
            'jawaban' => ['required', 'string', 'min:10', 'max:20000'],
        ], [
            'jawaban.required' => 'Teks jawaban tugas wajib diisi.',
            'jawaban.min' => 'Jawaban terlalu pendek. Tuliskan jawaban minimal 10 karakter.',
            'jawaban.max' => 'Jawaban melebihi batas maksimal 20.000 karakter.',
        ]);

        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'user_id' => $user->id,
            ],
            [
                'jawaban' => $validated['jawaban'],
                'submitted_at' => now(),
            ]
        );

        return back()->with('success', 'Jawaban tugas Anda berhasil disimpan ke sistem.');
    }

    public function grade(Request $request, Submission $submission): RedirectResponse
    {
        if (!Auth::check() || $submission->assignment->course->dosen_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda bukan dosen pengampu mata kuliah tugas ini.');
        }

        $validated = $request->validate([
            'nilai' => ['required', 'integer', 'min:0', 'max:100'],
            'catatan_dosen' => ['nullable', 'string', 'max:1000'],
        ], [
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.integer' => 'Nilai harus berupa bilangan bulat.',
            'nilai.min' => 'Nilai minimal adalah 0.',
            'nilai.max' => 'Nilai maksimal adalah 100.',
            'catatan_dosen.max' => 'Catatan dosen maksimal 1.000 karakter.',
        ]);

        $submission->update([
            'nilai' => $validated['nilai'],
            'catatan_dosen' => $validated['catatan_dosen'] ?? null,
        ]);

        return back()->with('success', "Nilai untuk {$submission->user->name} berhasil disimpan.");
    }

    public function exportCsv(Assignment $assignment): StreamedResponse
    {
        if (!Auth::check() || $assignment->course->dosen_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda bukan dosen pengampu mata kuliah tugas ini.');
        }

        $assignment->load([
            'course.approvedStudents' => fn($q) => $q->orderBy('nomor_induk'),
            'submissions',
        ]);

        $students = $assignment->course->approvedStudents;
        $submissions = $assignment->submissions->keyBy('user_id');

        $kodeMk = preg_replace('/[^A-Za-z0-9_-]/', '_', $assignment->course->kode_mk);
        $fileName = "rekap-nilai-{$kodeMk}-tugas-{$assignment->id}.csv";

        return response()->streamDownload(function () use ($students, $submissions) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'NIM',
                'Nama Mahasiswa',
                'Status Pengumpulan',
                'Waktu Pengumpulan',
                'Nilai',
                'Status Kelulusan',
                'Catatan Dosen',
            ]);

            foreach ($students as $student) {
                $sub = $submissions->get($student->id);

                if ($sub) {
                    $statusPengumpulan = 'Sudah Mengumpulkan';
                    $waktuPengumpulan = $sub->submitted_at
                        ? $sub->submitted_at->format('Y-m-d H:i:s')
                        : ($sub->updated_at ? $sub->updated_at->format('Y-m-d H:i:s') : '-');
                    $nilai = $sub->nilai !== null ? $sub->nilai : '-';
                    $statusKelulusan = $sub->status_nilai;
                    $catatanDosen = $sub->catatan_dosen ?? '-';
                } else {
                    $statusPengumpulan = 'Belum Mengumpulkan';
                    $waktuPengumpulan = '-';
                    $nilai = '-';
                    $statusKelulusan = 'Belum Dinilai';
                    $catatanDosen = '-';
                }

                fputcsv($handle, [
                    $student->nomor_induk ?? '-',
                    $student->name,
                    $statusPengumpulan,
                    $waktuPengumpulan,
                    $nilai,
                    $statusKelulusan,
                    $catatanDosen,
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}