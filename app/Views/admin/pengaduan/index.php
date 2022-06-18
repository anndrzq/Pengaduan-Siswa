<?= $this->extend('templates/index'); ?>

<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900"><?= $title; ?></h1>

    <?php if (session()->getFlashdata('msg') or session()->getFlashdata('msg-auth')) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('msg'); ?>
                    <?= session()->getFlashdata('msg-auth'); ?>
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
                        <table class="table table-bordered table-main" id="semua-pengaduan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Tentang</th>
                                    <th>Status</th>
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

<?= $this->section('additional-js'); ?>
<script>
    $(document).ready(function() {
        $("#semua-pengaduan").DataTable({
            "lengthMenu": [
                [8, 15, 30, 50, -1],
                [8, 15, 30, 50, "All"]
            ],
            "responsive": true,
            "processing": true,
            "serverside": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('/admin/pengaduan/dt_pengaduan') ?>",
                "type": "post"
            },
            "columnDefs": [{
                "render": function(data) {
                    return moment(data).format('DD MMMM YYYY');
                },
                "type": "date",
                "targets": 1,
            }, {
                "orderable": false,
                "targets": 4,
            }],
            "searchDelay": 350,
            "scrollY": 300,
            "scrollCollapse": true,
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