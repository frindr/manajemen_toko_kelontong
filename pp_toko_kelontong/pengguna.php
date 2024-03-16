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
    <title>Data Pengguna</title>
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
                    <h1 class="mt-4">Data Pengguna</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                Tambah
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr align="center">
                                            <th>Username</th>
                                            <th>Password</th>
                                            <th>Role</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $listpengguna = mysqli_query($koneksi, "select * from login");
                                        while ($data = mysqli_fetch_array($listpengguna)) {
                                            $username = $data['username'];
                                            $password = $data['password'];
                                            $role = $data['role'];
                                        ?>
                                            <tr align="center">
                                                <td><?= $username; ?></td>
                                                <td><?= $password; ?></td>
                                                <td><?= $role; ?></td>
                                                <td>
                                                    <?php if ($username === 'Admin') : ?>
                                                        <!-- Jika username adalah Admin -->
                                                        <span>Admin Utama</span>
                                                    <?php else : ?>
                                                        <!-- Jika bukan Admin, tampilkan tombol Edit dan Hapus -->
                                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#update<?= $username; ?>">
                                                            Edit
                                                        </button>
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $username; ?>">
                                                            Hapus
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="update<?= $username; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Pengguna</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <label>Username</label>
                                                                <input type="text" name="username" value="<?= $username; ?>" class="form-control" readonly>
                                                                <br>
                                                                <label>Password</label>
                                                                <input type="text" name="password" value="<?= $password; ?>" class="form-control" required>
                                                                <br>
                                                                <label>Role</label>
                                                                <select name="role" class="form-control">
                                                                    <?php
                                                                    $roles = array("Admin", "Kasir");

                                                                    foreach ($roles as $role) {
                                                                    ?>
                                                                        <option value="<?= $role; ?>"><?= $role; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <br>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary" name="updatepengguna">Edit</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete<?= $username; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Delete Pengguna</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                Yakin ingin menghapus pengguna <?= $username; ?>?
                                                                <br> <br>
                                                                <input type="hidden" name="username" value="<?= $username; ?>">
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-danger" name="hapuspengguna">Hapus</button>
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
                <h4 class="modal-title">Tambah Pengguna</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form method="post">
                <div class="modal-body">
                    <input type="text" name="username" placeholder="Username" class="form-control" required>
                    <br>
                    <input type="text" name="password" placeholder="Password" class="form-control" required>
                    <br>
                    <select name="role" class="form-control">
                        <?php
                        $roles = array("Admin", "Kasir");

                        foreach ($roles as $role) {
                        ?>
                            <option value="<?= $role; ?>"><?= $role; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <br>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="submitpengguna">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</html>