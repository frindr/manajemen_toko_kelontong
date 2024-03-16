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
    <title>Data Barang</title>
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
                    <h1 class="mt-4">Daftar Barang</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                Tambah
                            </button>
                            <a href="exportbarang.php" class="btn btn-info"> Export Data </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr align="center">
                                            <th>Kode</th>
                                            <th>Barang</th>
                                            <th>Kategori</th>
                                            <th>Stok</th>
                                            <th>Satuan</th>
                                            <th>Harga</th>
                                            <th>EXP Date</th>
                                            <th>Status Stok</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $listbarang = mysqli_query($koneksi, "select * from data_barang");
                                        $i = 1;
                                        while ($data = mysqli_fetch_array($listbarang)) {
                                            $idbar = $data['id_bar'];
                                            $barang = $data['nama'];
                                            $kategori = $data['kategori'];
                                            $stok = $data['jumlah'];
                                            $satuan = $data['satuan'];
                                            $harga = $data['harga'];
                                            $expdate = $data['exp_date'];
                                            $kode = $data['kode'];

                                            $leadTime = 4;
                                            $start_date = date('Y-m-d', strtotime("-$leadTime days"));
                                            $end_date = date('Y-m-d');

                                            // Menghitung rata-rata permintaan per hari
                                            $queryPermintaan = mysqli_query($koneksi, "SELECT SUM(jumlah) as sum_permintaan FROM detail_barangkeluar WHERE fk_id_bar = '$idbar' AND tanggal_transaksi BETWEEN '$start_date' AND NOW()");
                                            $resultPermintaan = mysqli_fetch_assoc($queryPermintaan);
                                            $rataPermintaanPerHari = $resultPermintaan['sum_permintaan'] / $leadTime;

                                            // Menghitung safety stock
                                            $queryMaxPemakaian = mysqli_query($koneksi, "SELECT MAX(jumlah) as max_pemakaian FROM detail_barangkeluar WHERE fk_id_bar = '$idbar'");
                                            $resultMaxPemakaian = mysqli_fetch_assoc($queryMaxPemakaian);
                                            $pemakaianMaksimum = $resultMaxPemakaian['max_pemakaian'] ?? 0;
                                            $safetyStock = ($pemakaianMaksimum - $rataPermintaanPerHari) * $leadTime;

                                            // Menghitung reorder point
                                            $reorderPoint = ($rataPermintaanPerHari * $leadTime) + $safetyStock;

                                            // Menentukan status reorder
                                            $status = ($stok <= $reorderPoint) ? "REORDER" : "-";

                                        ?>
                                            <tr align="center">
                                                <td><?= $kode; ?></td>
                                                <td><?= $barang; ?></td>
                                                <td><?= $kategori; ?></td>
                                                <td><?= $stok; ?></td>
                                                <td><?= $satuan; ?></td>
                                                <td><?= $harga; ?></td>
                                                <td><?= $expdate; ?></td>
                                                <td><?= $status; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#update<?= $idbar; ?>">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $idbar; ?>">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="update<?= $idbar; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Barang</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <label>Kode Barang</label>
                                                                <input type="text" name="kode" value="<?= $kode; ?>" class="form-control" readonly>
                                                                <br>
                                                                <label>Nama Barang</label>
                                                                <input type="text" name="barang" value="<?= $barang; ?>" class="form-control" required>
                                                                <br>
                                                                <label>Kategori Barang</label>
                                                                <select name="listkategori" class="form-control">
                                                                    <?php
                                                                    $ambildata = mysqli_query($koneksi, "select * from kategori");
                                                                    while ($fetcharray = mysqli_fetch_array($ambildata)) {
                                                                        $idkat = $fetcharray['id_kat'];
                                                                        $kategoriedit = $fetcharray['kategori'];
                                                                    ?>
                                                                        <option value="<?= $idkat; ?>"><?= $kategoriedit; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <br>
                                                                <label>Stok Barang</label>
                                                                <input type="number" name="stok" value="<?= $stok; ?>" class="form-control" required>
                                                                <br>
                                                                <label>Satuan Barang</label>
                                                                <input type="text" name="satuan" value="<?= $satuan; ?>" class="form-control" readonly>
                                                                <br>
                                                                <label>Harga Barang</label>
                                                                <input type="number" name="harga" value="<?= $harga; ?>" class="form-control" required>
                                                                <br>
                                                                <label>EXP Date Barang</label>
                                                                <input type="date" name="exp" value="<?= $expdate; ?>" class="form-control" required>
                                                                <br>
                                                                <input type="hidden" name="idbar" value="<?= $idbar; ?>">
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary" name="updatebarang">Edit</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete<?= $idbar; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Delete Barang</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                Yakin ingin menghapus barang <?= $barang; ?>?
                                                                <br> <br>
                                                                <input type="hidden" name="idbar" value="<?= $idbar; ?>">
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-danger" name="hapusbarang">Hapus</button>
                                                                </div>
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

<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form method="post">
                <div class="modal-body">
                    <input type="text" name="barang" placeholder="Nama Barang" class="form-control" required>
                    <br>
                    <select name="listkategori" class="form-control">
                        <?php
                        $ambildata = mysqli_query($koneksi, "select * from kategori");
                        while ($fetcharray = mysqli_fetch_array($ambildata)) {
                            $idkat = $fetcharray['id_kat'];
                            $kategori = $fetcharray['kategori'];
                        ?>
                            <option value="<?= $idkat; ?>"><?= $kategori; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <br>
                    <select name="satuan" class="form-control">
                        <?php
                        $satuans = array("lusin", "buah", "bungkus", "botol", "dus", "kotak", "sachet", "pak", "kaleng");
                        foreach ($satuans as $satuan) {
                        ?>
                            <option value="<?= $satuan; ?>"><?= $satuan; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <br>
                    <input type="number" name="harga" placeholder="Harga" class="form-control" required>
                    <br>
                    <label>EXP Date</label>
                    <input type="date" name="exp" class="form-control" required>
                    <br>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="submitbarang">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</html>