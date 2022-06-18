<?= $this->extend('templates/index'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-900"><?= $title; ?></h1>

            <div class="card shadow px-5 py-4">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <img class="card-img-top p-2" src="/img/profile/<?= $data['user_image']; ?>" alt="Image profile" height="290">
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><span class="badge badge-info"> <?= ($data['user_level'] == 1 ? 'Admin' : ($data['user_level'] == 2 ? 'Petugas' : 'Member')); ?></span></li>
                            <li class="list-group-item"><i class="fa fa-user"></i> <?= $data['nama']; ?> | username : <?= $data['username']; ?> </li>
                            <li class="list-group-item"><i class="fa fa-envelope"></i> <?= $data['email']; ?></li>
                            <li class="list-group-item"><i class="fa fa-calendar"></i> Aktif sejak. <?= date('d M Y', strtotime($data['created_at'])); ?></li>
                            <li class="list-group-item"><i class="fa fa-chart-bar"></i> Jumlah pengaduan : <?= $jml; ?></li>
                        </ul>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <button data-toggle="modal" data-target="#edit-profile" type="button" class="mb-1 btn btn-success btn-block edit-profile" data-id="<?= $data['id']; ?>"><i class="fas fa-pencil-alt"></i> Ubah Profil</button>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <button data-toggle="modal" data-target="#edit-password" type="button" class="d-inline btn btn-primary btn-block edit-password" data-id="<?= $data['id']; ?>"><i class="fas fa-key"></i> Ubah Password</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - Edit Profile -->
    <div class="modal fade" id="edit-profile" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Update Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?= form_open_multipart('user/ubah_profile', ['id' => 'formUbahProfile']); ?>
                <div class="modal-body">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="id" id="userid">
                    <div class="form-group">
                        <label for="user_image">Foto Profil</label>
                        <input type="file" name="user_image" id="user_image" class="form-control p-1">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password untuk konfirmasi perubahan" autocomplete="false">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-simpan">Simpan data</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-password" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Update Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?= form_open_multipart('user/ubah_password', ['id' => 'formUbahPassword']); ?>
                <div class="modal-body">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="id" id="user_id">
                    <div class="form-group">
                        <label for="password">Password Lama</label>
                        <input type="password" name="current-password" id="current-password" class="form-control" placeholder="Masukkan password saat ini" autocomplete="false">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" name="new-password" id="new-password" class="form-control" placeholder="Masukkan password baru" autocomplete="false">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Konfirmasi Password</label>
                        <input type="password" name="confirm-password" id="confirm-password" class="form-control" placeholder="Konfirmasi password baru" autocomplete="false">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-simpan">Simpan data</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<script>
    $("#formUbahPassword").submit(function(e) {
        e.preventDefault()

        $.ajax({
            url: $(this).attr("action"),
            type: $(this).attr("method"),
            dataType: "json",
            data: $(this).serialize(),
            beforeSend: function() {
                $('.btn-simpan').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>');
            },
            complete: function() {
                $('.btn-simpan').html('Simpan data');
            },
            success: function(res) {
                if (res.status) {
                    $.toast({
                        heading: res.msg,
                        position: 'top-right',
                        icon: 'success'
                    })

                    $("#edit-password").modal("toggle")
                } else {
                    if (res.type == 'verify') {
                        $.toast({
                            heading: res.msg,
                            position: 'top-right',
                            icon: 'error'
                        })
                    } else {
                        $.each(res.errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid');
                            $('[name="' + key + '"]').next().text(value);
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid');
                                $('[name="' + key + '"]').addClass('is-valid');
                            }
                        });
                    }
                }
            },
        })

        $('#formUbahPassword input').on('keyup', function() {
            $(this).removeClass('is-valid is-invalid');
        });
    })

    $(document).on("click", ".edit-profile", function() {
        var userid = $(this).data('id')

        $.ajax({
            url: "<?= base_url('/user/getProfile') ?>",
            method: "post",
            dataType: "json",
            data: {
                id: userid
            },
            success: function(res) {
                $(".modal-body #userid").val(res.id)
                $(".modal-body #nama").val(res.nama)
                $(".modal-body #username").val(res.username)
                $(".modal-body #email").val(res.email)
            }
        })
    })

    // callback function
    function errorOccured() {
        $.toast({
            heading: 'An error occured!',
            position: 'top-right',
            icon: 'error'
        })
    }

    $("#formUbahProfile").submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this)

        $.ajax({
            url: $(this).attr("action"),
            type: $(this).attr("method"),
            dataType: "json",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $('.btn-simpan').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>');
            },
            complete: function() {
                $('.btn-simpan').html('Simpan data');
            },
            success: function(res) {
                if (res.status) { // jika status = true
                    $.toast({
                        heading: res.msg,
                        position: 'top-right',
                        icon: 'success'
                        // text: '<a href="/auth/logout">Logout</a> untuk memperbarui sesi dengan profil baru',
                        // hideAfter: false,
                    })

                    $("#edit-profile").modal('toggle')

                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else {
                    $.each(res.errors, function(key, value) {
                        $('[name="' + key + '"]').addClass('is-invalid');
                        $('[name="' + key + '"]').next().text(value);
                        if (value == "") {
                            $('[name="' + key + '"]').removeClass('is-invalid');
                            $('[name="' + key + '"]').addClass('is-valid');
                        }
                    });
                }
            },
            error: function() {
                errorOccured()
            },
        });

        $('#formUbahProfile input').on('keyup', function() {
            $(this).removeClass('is-valid is-invalid');
        });
    })

    $('.modal').on('show.bs.modal', function(e) {
        $("#formUbahProfile")[0].reset()
        $("#formUbahPassword")[0].reset()
        $(".is-valid").removeClass("is-valid");
        $(".is-invalid").removeClass("is-invalid");
        $(".invalid-feedback").empty();
    });
</script>
<?= $this->endSection(); ?>