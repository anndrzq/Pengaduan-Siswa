<?= $this->extend('auth/template'); ?>

<?= $this->section('content'); ?>

<div class="card o-hidden border-0 shadow-lg my-5 col-lg-7 mx-auto">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
            <div class="col-lg">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Daftar akun baru!</h1>
                    </div>

                    <?= form_open_multipart('auth/validasi_register'); ?>
                    <?= csrf_field(); ?>

                    <div class="form-group">
                        <input type="text" class="form-control <?= $validation->hasError('nama') ? 'is-invalid' : ''; ?>" name="nama" placeholder="Nama lengkap" value="<?= old('nama'); ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('nama'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control <?= $validation->hasError('username') ? 'is-invalid' : ''; ?>" name="username" placeholder="Username" value="<?= old('username'); ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('username'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control <?= $validation->hasError('email') ? 'is-invalid' : ''; ?>" name="email" placeholder="Email address" value="<?= old('email'); ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('email'); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input type="password" class="form-control <?= $validation->hasError('password') ? 'is-invalid' : ''; ?>" name="password" placeholder="Password" value="<?= old('password'); ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('password'); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <input type="password" class="form-control <?= $validation->hasError('confirm_password') ? 'is-invalid' : ''; ?>" name="confirm_password" placeholder="Konfirmasi password" value="<?= old('confirm_password'); ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('confirm_password'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="file_ktp" class="text-info">Upload Identitas Diri | Maksimal 512 KB.</label>
                        <input type="file" class="p-1 form-control <?= $validation->hasError('user_ktp') ? 'is-invalid' : ''; ?>" name="user_ktp" id="file_ktp">
                        <div class="invalid-feedback">
                            <?= $validation->getError('user_ktp'); ?>
                        </div>
                    </div>
                    <button type="submit" name="btn-submit" class="btn btn-primary btn-block">
                        Daftar Akun
                    </button>

                    <?= form_close(); ?>

                    <hr>
                    <div class="text-center">
                        <a class="small" href="/auth">Sudah punya akun? Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>