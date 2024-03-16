<?php
    require '../function.php';
    require '../ceklogin.php';
?>

<?php
    $ambilkode = mysqli_query($koneksi, "select kode_transaksi from transaksi where status = 'in' order by id_transaksi desc limit 1");
    while ($fetcharray = mysqli_fetch_array($ambilkode)) {
        $kodetransaksi = $fetcharray['kode_transaksi'];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Transaksi Barang Masuk</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-4">Kode Transaksi: <?= $kodetransaksi ?></h1>
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
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Jumlah</th>
                                        <th>Total Harga</th>
                                        <th>Distributor</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $listbarmas = mysqli_query($koneksi, "select * from detail_barangmasuk where fk_id_transaksi = (select id_transaksi from transaksi where status = 'in' order by id_transaksi desc limit 1)");
                                    while ($data = mysqli_fetch_array($listbarmas)) {
                                        $idbarmas = $data['id_barmas'];
                                        $tanggal = $data['tanggal_transaksi'];
                                        $fkidbar = $data['fk_id_bar'];
                                        $jumlah = $data['jumlah'];
                                        $totharga = $data['total_harga'];
                                        $fkiddist = $data['fk_id_dist'];
                                        $barang = $data['nama'];
                                        $distributor = $data['distributor'];
                                        $idtransaksi = $data['fk_id_transaksi'];
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
                                            <td><?= $distributor; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $idbarmas; ?>">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $idbarmas; ?>">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="edit<?= $idbarmas; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang Masuk</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            <label>Tanggal Barang Masuk</label>
                                                            <input type="text" name="tanggal" class="form-control" value="<?= $tanggal ?>" readonly>
                                                            <br>
                                                            <label>Nama Barang</label>
                                                            <input type="text" name="barang" class="form-control" value="<?= $barang ?>" readonly>
                                                            <br>
                                                            <label>Jumlah Pembelian</label>
                                                            <input type="number" name="jumlahbeli" value="<?= $jumlah; ?>" class="form-control" required min="1">
                                                            <br>
                                                            <label>Total Harga</label>
                                                            <input type="number" name="totharga" value="<?= $totharga; ?>" class="form-control" required min="1">
                                                            <br>
                                                            <label>Distributor</label>
                                                            <input type="text" name="distributor" class="form-control" value="<?= $distributor ?>" readonly>
                                                            <br>
                                                            <input type="hidden" name="idbarmas" value="<?= $idbarmas; ?>">
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary" name="updatebarangmasuk">Edit</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="delete<?= $idbarmas; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Delete Barang Masuk</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            Yakin ingin menghapus barang masuk <?= $barang; ?>?
                                                            <br> <br>
                                                            <input type="hidden" name="idbarmas" value="<?= $idbarmas; ?>">
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-danger" name="hapusbarangmasuk">Hapus</button>
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
                            <?php
                                $resulttotbayar = mysqli_query($koneksi, "SELECT SUM(total_harga) AS totalBayar FROM barang_masuk WHERE fk_id_transaksi = (select id_transaksi from transaksi where status = 'in' order by id_transaksi desc limit 1)");
                                $ambiltotbayar = mysqli_fetch_assoc($resulttotbayar);
                                $totbayar = $ambiltotbayar['totalBayar'];
                            ?>
                            <div class="total-bayar text-right">
                                <strong>Total Bayar: <?= $totbayar; ?></strong>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="selesai()">Selesai</button>
                            <script>
                                function selesai() {
                                    window.location.replace('transaksimasuk.php');
                                }
                            </script>
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
                <h4 class="modal-title">Tambah Barang Masuk</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form method="post">
                <div class="modal-body">
                    <select name="idtransaksi" class="form-control">
                        <?php
                        $ambilkode = mysqli_query($koneksi, "select id_transaksi, kode_transaksi from transaksi where status = 'in' order by id_transaksi desc limit 1");
                        while ($fetcharray = mysqli_fetch_array($ambilkode)) {
                            $idtransaksi = $fetcharray['id_transaksi'];
                            $kodetransaksi = $fetcharray['kode_transaksi'];
                        ?>
                            <option value="<?= $idtransaksi; ?>"><?= $kodetransaksi; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <br>
                    <select name="listbarang" class="form-control">
                        <?php
                        $ambilbarang = mysqli_query($koneksi, "select * from barang");
                        while ($fetcharray = mysqli_fetch_array($ambilbarang)) {
                            $namabarang = $fetcharray['nama'];
                            $idbarang = $fetcharray['id_bar'];
                            $satuan = $fetcharray['satuan'];
                        ?>
                            <option value="<?= $idbarang; ?>"><?= $namabarang; ?> (<?= $satuan; ?>)</option>
                        <?php
                        }
                        ?>
                    </select>
                    <br>
                    <input type="number" name="jumlah" placeholder="Jumlah" class="form-control" required min="1">
                    <br>
                    <input type="number" name="harga" placeholder="Total Harga" class="form-control" required min="1">
                    <br>
                    <label>Distributor</label>
                    <select name="listdist" class="form-control">
                        <?php
                        $ambildist = mysqli_query($koneksi, "select * from distributor");
                        while ($fetcharray = mysqli_fetch_array($ambildist)) {
                            $iddist = $fetcharray['id_dist'];
                            $distributor = $fetcharray['distributor'];
                        ?>
                            <option value="<?= $iddist; ?>"><?= $distributor; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="barangmasuk">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</html>