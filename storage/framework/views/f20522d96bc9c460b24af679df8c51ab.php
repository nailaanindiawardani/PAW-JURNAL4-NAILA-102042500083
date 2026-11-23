<?php $__env->startSection('title', Auth::user()->isDosen() ? 'Mata Kuliah Diampu' : 'Katalog Mata Kuliah'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 class="card-title">
                <?php echo e(Auth::user()->isDosen() ? 'Mata Kuliah yang Diampu' : 'Katalog Mata Kuliah'); ?>

            </h1>
            <p class="card-subtitle">
                <?php if(Auth::user()->isDosen()): ?>
                    Kelola materi, tugas, dan persetujuan mahasiswa pada mata kuliah yang Anda ampu.
                <?php else: ?>
                    Jelajahi mata kuliah yang tersedia di CeLOE LMS dan ajukan pendaftaran untuk bergabung ke kelas.
                <?php endif; ?>
            </p>
        </div>

        <?php if(Auth::user()->isDosen()): ?>
            <a href="<?php echo e(route('courses.create')); ?>" class="btn btn-primary">
                + Buat Mata Kuliah Baru
            </a>
        <?php endif; ?>
    </div>

    <div class="card-body" style="padding-bottom: 0;">
        <form action="<?php echo e(route('courses.index')); ?>" method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
            <div style="flex: 1; min-width: 240px;">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control" 
                    placeholder="Cari berdasarkan kode mata kuliah, nama, atau semester..." 
                    value="<?php echo e(request('search')); ?>"
                >
            </div>
            
            <div style="min-width: 190px;">
                <select name="semester" class="form-control">
                    <option value="">Semua Semester</option>
                    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sem); ?>" <?php echo e(request('semester') === $sem ? 'selected' : ''); ?>>
                            <?php echo e($sem); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div style="min-width: 190px;">
                <select name="sort" class="form-control">
                    <option value="nama_asc" <?php echo e(request('sort', 'nama_asc') === 'nama_asc' ? 'selected' : ''); ?>>
                        Urutkan: Nama (A - Z)
                    </option>
                    <option value="nama_desc" <?php echo e(request('sort') === 'nama_desc' ? 'selected' : ''); ?>>
                        Urutkan: Nama (Z - A)
                    </option>
                    <option value="sks_desc" <?php echo e(request('sort') === 'sks_desc' ? 'selected' : ''); ?>>
                        Urutkan: SKS Terbesar
                    </option>
                    <option value="sks_asc" <?php echo e(request('sort') === 'sks_asc' ? 'selected' : ''); ?>>
                        Urutkan: SKS Terkecil
                    </option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-secondary">
                Filter &amp; Cari
            </button>
            <?php if(request('search') || request('semester') || (request('sort') && request('sort') !== 'nama_asc')): ?>
                <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-secondary" style="color: var(--slate-500);">
                    Reset
                </a>
            <?php endif; ?>
        </form>
    </div>

    <div style="padding: 0 1.5rem 1.5rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.25rem;">
            <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: space-between;">
                    <div class="card-body" style="padding: 1.25rem; flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <span style="font-weight: 700; color: var(--slate-800); font-size: 0.875rem;"><?php echo e($course->kode_mk); ?></span>
                            <span style="color: var(--slate-500); font-size: 0.8125rem;"><?php echo e($course->sks); ?> SKS</span>
                        </div>

                        <h2 style="font-size: 1.0625rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.5rem; line-height: 1.35;">
                            <a href="<?php echo e(route('courses.show', $course->id)); ?>" style="color: inherit; text-decoration: none;">
                                <?php echo e($course->nama_mk); ?>

                            </a>
                        </h2>

                        <p style="font-size: 0.8125rem; color: var(--slate-600); margin-bottom: 0.75rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <?php echo e($course->deskripsi ?: 'Tidak ada deskripsi mata kuliah.'); ?>

                        </p>

                        <div style="font-size: 0.8125rem; color: var(--slate-500); display: flex; flex-direction: column; gap: 0.25rem; margin-top: auto;">
                            <div><strong>Dosen Pengampu:</strong> <?php echo e($course->dosen->name ?? '-'); ?></div>
                            <div><strong>Semester:</strong> <?php echo e($course->semester); ?></div>
                        </div>
                    </div>

                    <div style="padding: 0.875rem 1.25rem; background-color: var(--slate-50); border-top: 1px solid var(--slate-200); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                        <span style="font-size: 0.75rem; color: var(--slate-500);">
                            <?php echo e($course->students_count ?? 0); ?> Mahasiswa Aktif
                        </span>

                        <div style="display: flex; align-items: center; gap: 0.375rem; flex-wrap: wrap;">
                            <?php if(Auth::user()->isDosen()): ?>
                                <a href="<?php echo e(route('courses.show', $course->id)); ?>" class="btn btn-primary btn-sm">
                                    Kelola Kelas
                                </a>
                                <a href="<?php echo e(route('courses.edit', $course->id)); ?>" class="btn btn-secondary btn-sm" title="Edit Mata Kuliah">
                                    Edit
                                </a>
                                <form action="<?php echo e(route('courses.destroy', $course->id)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah <?php echo e($course->kode_mk); ?>?');" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus Mata Kuliah">
                                        Hapus
                                    </button>
                                </form>
                            <?php else: ?>
                                <?php
                                    $enrollStatus = $course->getStudentEnrollmentStatus(Auth::id());
                                ?>

                                <?php if($enrollStatus === 'approved'): ?>
                                    <span style="background-color: #d1fae5; color: #065f46; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 600;">
                                        ✓ Terdaftar
                                    </span>
                                    <a href="<?php echo e(route('courses.show', $course->id)); ?>" class="btn btn-primary btn-sm">
                                        Masuk Kelas
                                    </a>
                                <?php elseif($enrollStatus === 'pending'): ?>
                                    <span style="background-color: #fef3c7; color: #92400e; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 600;">
                                        ⏳ Menunggu Persetujuan
                                    </span>
                                    <a href="<?php echo e(route('courses.show', $course->id)); ?>" class="btn btn-secondary btn-sm">
                                        Ringkasan
                                    </a>
                                <?php elseif($enrollStatus === 'rejected'): ?>
                                    <span style="background-color: #fee2e2; color: #991b1b; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 600;">
                                        ✕ Ditolak
                                    </span>
                                    <form action="<?php echo e(route('courses.enroll-request', $course->id)); ?>" method="POST" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-secondary btn-sm">
                                            Ajukan Ulang
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?php echo e(route('courses.enroll-request', $course->id)); ?>" method="POST" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            + Ajukan Pendaftaran
                                        </button>
                                    </form>
                                    <a href="<?php echo e(route('courses.show', $course->id)); ?>" class="btn btn-secondary btn-sm">
                                        Ringkasan
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="grid-column: 1 / -1; padding: 3rem 1.5rem; text-align: center; color: var(--slate-500);">
                    <div style="font-weight: 700; font-size: 1rem; color: var(--slate-800); margin-bottom: 0.5rem;">Tidak Ada Mata Kuliah Ditemukan</div>
                    <p style="font-size: 0.875rem; margin: 0;">
                        <?php if(Auth::user()->isDosen()): ?>
                            Anda belum membuat mata kuliah. Klik tombol <strong>"+ Buat Mata Kuliah Baru"</strong> di atas.
                        <?php else: ?>
                            Belum ada mata kuliah yang cocok dengan kriteria pencarian Anda.
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <div style="margin-top: 1.5rem;">
            <?php echo e($courses->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PAW_JURNAL4_NAILA_102042500083\resources\views/courses/index.blade.php ENDPATH**/ ?>