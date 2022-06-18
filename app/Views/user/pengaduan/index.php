<?= $this->extend('templates/index'); ?>

<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900"><?= $title; ?></h1>

    <?php if (session()->getFlashdata('error-msg')) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    <?= session()->getFlashdata('error-msg'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

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
                    <a href="/pengaduan/tambah" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Pengaduan Baru</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-main" id="tbl-pengaduan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tentang</th>
                                    <th>Status</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($data) : ?>
                                    <?php foreach ($data as $num => $row) : ?>
                                        <tr>
                                            <td><?= $num + 1; ?></td>
                                            <td><?= $row['judul_pengaduan']; ?></td>
                                            <td>
                                                <?= $row['status_pengaduan'] == 1 ? '<span class="badge-warning p-1 rounded-sm">menunggu</span>' : ($row['status_pengaduan'] == 2 ? '<span class="badge-success p-1 rounded-sm">di proses</span>' : '<span class="badge-info p-1 rounded-sm">selesai</span>') ?>
                                            </td>
                                            <td>
                                                <div class="dropdown show">
                                                    <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </a>

                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                        <a href="/pengaduan/<?= $row['id']; ?>" class="dropdown-item">Detail</a>

                                                        <?php if ($row['status_pengaduan'] == 1) : ?>

                                                            <a href="/pengaduan/ubah/<?= $row['id']; ?>" class="dropdown-item">Ubah</a>

                                                            <?= form_open('pengaduan/' . $row['id'], ['class' => 'd-inline']); ?>
                                                            <?= csrf_field(); ?>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button onclick="return confirm('yakin?')" type="submit" class="dropdown-item">Delete</button>
                                                            <?= form_close(); ?>

                                                        <?php endif; ?>

                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="4">
                                            <h3 class="text-gray-900 text-center">Data belum ada.</h3>
                                        </td>
                                    </tr>
                                <?php endif; ?>
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
        $("#tbl-pengaduan").DataTable({
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            "columnDefs": [{
                "targets": 3,
                "orderable": false
            }],
            "responsive": true,
            "searchDelay": 350,
            "scrollY": 300,
            // "scrollCollapse": true,
            "language": {
                "infoEmpty": "0 entri",
                "info": "Menampilkan _END_ data pengaduan.",
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