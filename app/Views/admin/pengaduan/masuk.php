<?= $this->extend('templates/index'); ?>

<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900"><?= $title; ?></h1>

    <?php if (session()->getFlashdata('msg')) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('msg'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">

            <div class="card shadow">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-main" id="pengaduan-masuk">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Tentang</th>
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

</div>
<!-- /.container-fluid -->
<?= $this->endSection(); ?>

<?= $this->section('additional-js') ?>
<script>
    $(document).ready(function() {
        $("#pengaduan-masuk").DataTable({
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            "processing": true,
            "serverside": true,
            "ajax": {
                "url": "<?= base_url('admin/pengaduan/dt_pengaduan_masuk') ?>",
                "type": "post"
            },
            "columnDefs": [{
                "render": function(data) {
                    return moment(data).format('DD MMMM YYYY');
                },
                "type": "date",
                "targets": 1,
            }, {
                "targets": [3],
                "orderable": false
            }],
            "searchDelay": 350,
            "scrollY": 300,
            "scrollCollapse": false,
            "language": {
                "processing": "Loading data..",
                "infoEmpty": "0 entri",
                "info": "Menampilkan _TOTAL_ data pengaduan.",
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
    })
</script>
<?= $this->endSection(); ?>