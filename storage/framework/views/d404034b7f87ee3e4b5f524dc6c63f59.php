<?php $__env->startSection('title', 'Edit Profil Akun'); ?>

<?php $__env->startSection('content'); ?>
<div style="margin-bottom: 1.25rem;">
    <a href="<?php echo e(route('dashboard')); ?>" style="display: inline-flex; align-items: center; gap: 0.375rem; color: var(--slate-600); text-decoration: none; font-size: 0.875rem; font-weight: 500;">
        <span>&larr;</span> Kembali ke Dashboard
    </a>
</div>

<div class="card" style="max-width: 720px; margin: 0 auto 2rem;">
    <div class="card-header">
        <div>
            <h1 class="card-title" style="margin: 0; font-size: 1.25rem;">
                Edit Profil Akun
            </h1>
            <p class="card-subtitle">
                Perbarui data akun pengguna portal pembelajaran CeLOE LMS Telkom University.
            </p>
        </div>
        <span style="color: var(--slate-600); font-size: 0.875rem; font-weight: 600; text-transform: capitalize;">
            <?php echo e($user->role); ?>

        </span>
    </div>

    <div class="card-body" style="padding: 1.75rem;">
        <form action="<?php echo e(route('profile.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Informasi Profil Dasar -->
            <div style="margin-bottom: 1.25rem;">
                <label for="name" style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--slate-700); margin-bottom: 0.375rem;">
                    Nama Lengkap <span style="color: var(--tel-red);">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?php echo e(old('name', $user->name)); ?>"
                    required
                    style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid var(--slate-300); border-radius: var(--radius-md); font-size: 0.875rem; outline: none; font-family: inherit;"
                >
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color: #dc2626; font-size: 0.8125rem; margin-top: 0.25rem;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="email" style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--slate-700); margin-bottom: 0.375rem;">
                    Alamat Email <span style="color: var(--tel-red);">*</span>
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?php echo e(old('email', $user->email)); ?>"
                    required
                    style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid var(--slate-300); border-radius: var(--radius-md); font-size: 0.875rem; outline: none; font-family: inherit;"
                >
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color: #dc2626; font-size: 0.8125rem; margin-top: 0.25rem;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="margin-bottom: 1.75rem;">
                <label for="nomor_induk" style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--slate-700); margin-bottom: 0.375rem;">
                    Nomor Induk (NIM / NIP / ID)
                </label>
                <input
                    type="text"
                    id="nomor_induk"
                    name="nomor_induk"
                    value="<?php echo e(old('nomor_induk', $user->nomor_induk)); ?>"
                    placeholder="Contoh: 1301210001 atau 19850101..."
                    style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid var(--slate-300); border-radius: var(--radius-md); font-size: 0.875rem; outline: none; font-family: inherit;"
                >
                <small style="color: var(--slate-500); font-size: 0.75rem; margin-top: 0.25rem; display: block;">
                    Nomor identitas akademik Anda yang tercatat di portal.
                </small>
                <?php $__errorArgs = ['nomor_induk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color: #dc2626; font-size: 0.8125rem; margin-top: 0.25rem;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Bagian Ganti Kata Sandi -->
            <div style="border-top: 1px solid var(--slate-200); padding-top: 1.5rem; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1rem; font-weight: 600; color: var(--slate-800); margin: 0 0 0.25rem 0;">
                    Ubah Kata Sandi
                </h3>
                <p style="font-size: 0.8125rem; color: var(--slate-500); margin: 0 0 1.25rem 0;">
                    Kosongkan kolom di bawah jika Anda tidak ingin mengganti kata sandi.
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
                    <div>
                        <label for="password" style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--slate-700); margin-bottom: 0.375rem;">
                            Kata Sandi Baru
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            placeholder="Minimal 6 karakter"
                            style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid var(--slate-300); border-radius: var(--radius-md); font-size: 0.875rem; outline: none; font-family: inherit;"
                        >
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div style="color: #dc2626; font-size: 0.8125rem; margin-top: 0.25rem;"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="password_confirmation" style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--slate-700); margin-bottom: 0.375rem;">
                            Konfirmasi Kata Sandi Baru
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            placeholder="Ulangi kata sandi baru"
                            style="width: 100%; padding: 0.625rem 0.875rem; border: 1px solid var(--slate-300); border-radius: var(--radius-md); font-size: 0.875rem; outline: none; font-family: inherit;"
                        >
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--slate-200); padding-top: 1.25rem;">
                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PAW_JURNAL4_NAILA_102042500083\resources\views/profile/edit.blade.php ENDPATH**/ ?>