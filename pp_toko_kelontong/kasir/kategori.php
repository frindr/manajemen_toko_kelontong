<?php
    require '../function.php';
    require '../ceklogin.php';
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
        <title>Kategori Barang</title>
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
                        <a class="nav-link" href="../logout.php">
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
                        <h1 class="mt-4">Daftar Kategori Barang</h1>
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
                                            <tr align = "center">
                                                <th>No</th>
                                                <th>Kategori Barang</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $listkategori = mysqli_query($koneksi, "select * from kategori");
                                                $i = 1;
                                                while($data = mysqli_fetch_array($listkategori)){
                                                    $idkat = $data['id_kat'];
                                                    $kategori = $data['kategori'];
                                            ?>
                                            <tr align = "center">
                                                <td><?=$i++;?></td>
                                                <td><?=$kategori;?></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#update<?=$idkat;?>">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$idkat;?>">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="update<?=$idkat;?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Kategori</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <label>Kategori</label>
                                                                <input type="text" name="kategori" value="<?=$kategori;?>" class="form-control" required>
                                                                <input type="hidden" name="idkat" value="<?=$idkat;?>">
                                                                <br>      
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary" name="updatekategori">Edit</button>
                                                                </div>                  
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete<?=$idkat;?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Delete Kategori</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                Yakin ingin menghapus kategori <?=$kategori;?>?
                                                                <br> <br>
                                                                <input type="hidden" name="idkat" value="<?=$idkat;?>">  
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-danger" name="hapuskategori">Hapus</button>
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
                    <h4 class="modal-title">Tambah Kategori Barang</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <form method="post">
                    <div class="modal-body">
                        <input type="text" name="kategori" placeholder="Kategori" class="form-control" required>
                        <br>      
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="submitkategori">Submit</button>
                        </div>                  
                    </div>
                </form> 
             </div>
        </div>
    </div>

</html>
