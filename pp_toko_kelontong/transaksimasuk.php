<?php
require 'function.php';
require 'ceklogin.php';
$loginas = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Transaksi Masuk</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Toko Serba Ada</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="pengguna.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Kelola Pengguna
                        </a>
                        <a class="nav-link" href="kategori.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Kategori Barang
                        </a>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Data Barang
                        </a>
                        <a class="nav-link" href="transaksimasuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Barang Masuk
                        </a>
                        <a class="nav-link" href="transaksikeluar.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Barang Keluar
                        </a>
                        <a class="nav-link" href="distributor.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Distributor Barang
                        </a>
                        <a class="nav-link" href="logout.php">
                            Logout
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo $loginas; ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Transaksi Masuk</h1>
                    <div class="card mb-4">
                        <form method="post" id="transaksiForm">
                            <div class="card-header">
                                <button type="submit" class="btn btn-primary" name="transaksimasuk">Tambah</button>
                                <a href="laporanbarangmasuk.php" class="btn btn-success">Lihat Laporan</a>
                            </div>
                        </form>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr align="center">
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Kode</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $listtransaksi = mysqli_query($koneksi, "select distinct kode_transaksi, id_transaksi, tanggal_transaksi from liat_transaksi_bm");
                                        $i = 1;
                                        while ($data = mysqli_fetch_array($listtransaksi)) {
                                            $idtransaksi = $data['id_transaksi'];
                                            $tanggal = $data['tanggal_transaksi'];
                                            $kode = $data['kode_transaksi'];
                                        ?>
                                            <tr align="center">
                                                <td><?= $i++; ?></td>
                                                <td><?= $tanggal; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-lighth" data-toggle="modal" data-target="#update<?= $idtransaksi; ?>">
                                                        <?= $kode; ?>
                                                    </button>
                                                </td>
                                            </tr>
                                            <!--Modal -->
                                            <div class="modal fade" id="update<?= $idtransaksi; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Rincian Transaksi</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <label>Daftar Belanja</label>
                                                                <select name="listbelanja" class="form-control">
                                                                    <?php
                                                                        $ambildata = mysqli_query($koneksi, "SELECT nama, jumlah, total_harga FROM liat_transaksi_bm WHERE fk_id_transaksi = '$idtransaksi'");
                                                                        $totalTransaksi = 0;
                                                                        while ($fetcharray = mysqli_fetch_array($ambildata)) {
                                                                            $barang = $fetcharray['nama'];
                                                                            $jumlah = $fetcharray['jumlah'];
                                                                            $kode = $fetcharray['kode_transaksi'];
                                                                            $harga = $fetcharray['total_harga'];
                                                                            $totalTransaksi += $harga;
                                                                    ?>
                                                                        <option value="<?= $barang; ?>"><?= $barang; ?> (<?= $jumlah; ?>)</option>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <br>
                                                                <?php $formattedTotal = "Rp" . number_format($totalTransaksi, 0, ',', '.') . ",-"; ?>
                                                                <label>Total Transaksi</label>
                                                                <input type="text" class="form-control" value="<?= $formattedTotal; ?>" readonly>
                                                                <br>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        };
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; IFR 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>


</body>

</html>