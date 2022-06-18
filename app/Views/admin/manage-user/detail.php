<?= $this->extend('templates/index'); ?>

<?= $this->section('content'); ?>
<section class="content">
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-900"><?= $title; ?></h1>

        <div class="row">
            <div class="col-12">


                <a href="/admin/manage-user">&laquo; Kembali ke daftar user</a>

                <div class="card mb-3 text-gray-900 p-2" style="max-width: 540px;">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="/img/profile/<?= $data['user_image']; ?>" class="card-img" height="<?= $data['is_active'] == 1 ? "280px" : ""; ?>" alt="user-profile">

                            <?php if ($data['is_active'] == 0) : ?>
                                <hr class="sidebar-divider">

                                <button type="button" data-toggle="modal" data-target="#user-approval" class="btn btn-primary btn-block">Approve User</button>
                            <?php endif; ?>

                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"><?= $data['username']; ?></h5>
                                <hr class="sidebar-divider">
                                <p class="card-text mb-0"><b>Nama: </b><?= $data['nama']; ?></p>
                                <p class="card-text mb-0"><b>Email: </b><?= $data['email']; ?></p>
                                <hr class="sidebar-divider">
                                <p class="card-text mb-0"><b>KTP: </b><a href="/img/ktp/<?= $data['user_ktp']; ?>" target="_blank">Lihat</a></p>
                                <p class="card-text mb-0"><b>Status: </b><?= $data['is_active'] == 1 ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>'; ?></p>
                                <p class="card-text mb-0"><b>Mendaftar Sejak, </b><?= date('d-M-Y', strtotime($data['created_at'])); ?></p>
                                <hr class="sidebar-divider">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="user-approval" tabindex="-1" role="dialog" aria-labelledby="user-approvalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <?= form_open('admin/manage-user/user_activation', ['class' => 'd-inline']); ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="user-approvalLabel">Yakin ingin mengkorfimasi pengguna?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span class="text-gray-900">Klik "Approve" untuk konfirmasi pengaktifan akun pengguna.</span>
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id" value="<?= $data['id']; ?>">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Approve</button>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
</section>
<?= $this->endSection(); ?>