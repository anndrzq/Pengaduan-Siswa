<?= $this->extend('templates/index'); ?>

<?= $this->section('content'); ?>
<section class="content">
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-900"><?= $title; ?></h1>

        <div class="row">
            <div class="col-12">

                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-main" id="tbl-unverified">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Profile</th>
                                        <th>Nama</th>
                                        <th>File KTP</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
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
                        <input type="hidden" name="id" id="userid">
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
</section>
<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<script>
    $(document).ready(function() {
        $("#tbl-unverified").DataTable({
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            "columnDefs": [{
                "targets": 3,
                "orderable": false
            }],
            "processing": true,
            "serverside": true,
            "order": [],
            "ajax": {
                "url": '<?= base_url('/admin/ManageUser/dt_users_unverified') ?>',
                "type": "post"
            },
            "searchDelay": 350,
            "scrollY": 300,
            "scrollCollapse": true,
            "language": {
                "processing": "Loading data..",
                "infoEmpty": "0 entri",
                "info": "<span class='text-gray-900'>Menampilkan _TOTAL_ data user belum terverifikasi.</span>",
                "infoFiltered": "(difilter dari _MAX_ total entri)",
                "emptyTable": "Belum ada data",
                "lengthMenu": "Menampilkan _MENU_ entri",
                "search": "Pencarian:",
                "zeroRecords": "Data tidak ditemukan",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
            }
        })

        $(document).on("click", ".btn-approve", function() {
            $(".modal-body #userid").val($(this).data('id'))
        })
    })
</script>
<?= $this->endSection(); ?>