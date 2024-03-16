<?php
require 'function.php';
require 'ceklogin.php';
?>

<html>

<head>
    <title>Laporan Barang Keluar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
    <div class="container">
        <h2>Data Barang Keluar</h2>
        <div class="data-tables datatable-dark">
            <div class="row mt-4">
                <div class="col">
                    <form method="post" class="form-inline">
                        <input type="date" name="tgl_mulai" class="form-control">
                        <input type="date" name="tgl_selesai" class="form-control ml-3">
                        <button type="submit" name="filter_tgl" class="btn btn-info ml-3">Filter</button>
                    </form>
                </div>
            </div>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr align="center">
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($_POST['filter_tgl'])) {
                            $mulai = $_POST['tgl_mulai'];
                            $akhir = $_POST['tgl_selesai'];

                            if ($mulai != null || $akhir != null) {
                                $listbarkel = mysqli_query($koneksi, "select * from detail_barangkeluar where tanggal_transaksi BETWEEN '$mulai' and DATE_ADD('$akhir', INTERVAL 1 DAY)");
                            } else {
                                $listbarkel = mysqli_query($koneksi, "select * from detail_barangkeluar");
                            }
                        } else {
                            $listbarkel = mysqli_query($koneksi, "select * from detail_barangkeluar");
                        }
                        while ($data = mysqli_fetch_array($listbarkel)) {
                            $idbarkel = $data['id_barkel'];
                            $tanggal = $data['tanggal_transaksi'];
                            $fkidbar = $data['fk_id_bar'];
                            $jumlah = $data['jumlah'];
                            $harga = $data['harga'];
                            $totharga = $data['total_harga'];
                            $barang = $data['nama'];
                            $kodetransaksi = $data['kode_transaksi'];
                            $kategori = $data['kategori'];
                    ?>
                        <tr align="center">
                            <td><?= $kodetransaksi; ?></td>
                            <td><?= $tanggal; ?></td>
                            <td><?= $barang; ?></td>
                            <td><?= $kategori; ?></td>
                            <td><?= $jumlah; ?></td>
                            <td><?= $totharga; ?></td>
                        </tr>
                    <?php
                        };
                    ?>
                </tbody>
            </table>
            <a href="transaksikeluar.php" class="btn btn-primary"> Kembali </a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'print'
                ]
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
</body>

</html>