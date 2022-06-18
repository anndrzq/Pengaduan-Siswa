<?= $this->extend('auth/template'); ?>

<?= $this->section('content'); ?>
<!-- Outer Row -->
<div class="row justify-content-center">

    <div class="col-xl-6 col-lg-6 col-md-9 col-sm-12">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <!-- <div class="col-lg-6 d-none d-lg-block bg-login-image"></div> -->
                    <div class="col-lg">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Silahkan login.</h1>
                            </div>

                            <?php if (session()->getFlashdata('msg-auth')) : ?>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-success" role="alert">
                                            <?= session()->getFlashdata('msg-auth'); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('msg-failed')) : ?>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-danger" role="alert">
                                            <?= session()->getFlashdata('msg-failed'); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?= form_open('/auth/validasi_login'); ?>
                            <?= csrf_field(); ?>
                            <div class="form-group">
                                <input type="text" class="form-control <?= $validation->hasError('email') ? 'is-invalid' : ''; ?>" name="email" placeholder="Email atau username.." value="<?= old('email'); ?>">
                                <div class="invalid-feedback pl-1">
                                    <?= $validation->getError('email'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control <?= $validation->hasError('password') ? 'is-invalid' : ''; ?>" name="password" placeholder="Password.." value="<?= old('password'); ?>">
                                <div class="invalid-feedback pl-1">
                                    <?= $validation->getError('password'); ?>
                                </div>
                            </div>
                            <button type="submit" name="btn-submit" class="btn btn-primary btn-block">
                                Login
                            </button>

                            <?= form_close(); ?>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="/auth/forgot-password">Lupa Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="/auth/register">Belum punya akun? Daftar.</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<?= $this->endSection(); ?>