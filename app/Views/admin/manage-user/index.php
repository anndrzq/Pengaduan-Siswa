<?= $this->extend('templates/index'); ?>

<?= $this->section('content'); ?>
<section class="content">
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <?php if (session()->getFlashdata('msg')) : ?>
            <input type="hidden" name="flash-msg" id="flash-msg" data-flash="<?= session()->getFlashdata('msg'); ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-12">

                <!-- Page Heading -->
                <h1 class="h3 mb-4 text-gray-900"><?= $title; ?></h1>

                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-main" id="tbl-users">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Profile</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables Server Side -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="modal-hapus" tabindex="-1" role="dialog" aria-labelledby="modal-hapusLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <?= form_open('admin/ManageUser/soft_delete', ['class' => 'd-inline', 'id' => 'formHapusUser']); ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-hapusLabel">Yakin ingin menghapus data?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span class="text-gray-900">Klik "Yakin" untuk konfirmasi hapus data berikut.</span>
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id" id="user_id">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-yakin">Yakin</button>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- /. container-fluid -->

</section>
<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<script>
    $(document).ready(function() {

        tbl_users()

        function tbl_users() {
            $("#tbl-users").DataTable({
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
                "processing": true,
                "serverside": true,
                "order": [],
                "ajax": {
                    "url": '<?= base_url('/admin/ManageUser/dt_users') ?>',
                    "type": "post"
                },
                "columnDefs": [{
                    "targets": [1, 4],
                    "orderable": false
                }],
                "searchDelay": 350,
                "scrollY": 300,
                "scrollCollapse": true,
                "language": {
                    "processing": "Loading data..",
                    "infoEmpty": "0 entri",
                    "info": "<span class='text-gray-900'>Menampilkan _TOTAL_ data user.</span>",
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
        }

        // Hapus User (Single Data)
        $(document).on('click', '.hapus-user', function() {
            $(".modal-body #user_id").val($(this).data('userid'))
        })

        $('#formHapusUser').submit(function(e) {
            e.preventDefault()

            var userid = $("#user_id").val()

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                dataType: 'json',
                data: {
                    id: userid
                },
                beforeSend: function() {
                    $('.btn-yakin').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>');
                },
                success: function(res) {
                    $.toast({
                        text: res.msg,
                        position: "top-right",
                        hideAfter: 2500,
                    })
                    $("#modal-hapus").modal('toggle')
                    setTimeout(function() {
                        location.reload()
                    }, 3000)
                }
            })
        })

        // Pesan berhasil di verifikasi (approved)
        var msg = $("#flash-msg").data('flash')
        if (msg) {
            $.toast({
                text: msg,
                position: "top-right",
                hideAfter: 3000,
            })
        }

    })
</script>
<?= $this->endSection(); ?>