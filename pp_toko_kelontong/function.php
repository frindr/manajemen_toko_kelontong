<?php

    session_start();

    //Koneksi ke database
    $koneksi = mysqli_connect("localhost", "root", "", "pp_toko_kelontong");

    //CUD Kategori
        //Add
        if(isset($_POST['submitkategori'])){
            $kategori = $_POST['kategori'];

            try {
                $insertkategori = mysqli_query($koneksi, "insert into kategori (kategori) values ('$kategori')");
                if($insertkategori){
                    echo"
                        <script>
                            alert('Data Berhasil Ditambahkan!');
                            window.location.replace('kategori.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Data Sudah Ada!');
                        window.location.replace('kategori.php');
                    </script>
                ";
                exit();
            }
        }

        //Update
        if(isset($_POST['updatekategori'])){
            $idkat = $_POST['idkat'];
            $kategori = $_POST['kategori'];

            try {
                $updatekategori = mysqli_query($koneksi, "update kategori set kategori = '$kategori' where id_kat = '$idkat'");
                if($updatekategori){
                echo"
                    <script>
                        alert('Data Berhasil Diperbarui!');
                        window.location.replace('kategori.php');
                    </script>
                ";
                exit();
            }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Data Sudah Ada!');
                        window.location.replace('kategori.php');
                    </script>
                ";
                exit();
            }
        }

        //Delete
        if(isset($_POST['hapuskategori'])){
            $idkat = $_POST['idkat'];

            try {
                $deletekategori = mysqli_query($koneksi, "delete from kategori where id_kat = '$idkat'");
                if($deletekategori){
                    echo"
                        <script>
                            alert('Data Berhasil Dihapus!');
                            window.location.replace('kategori.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Gagal Menghapus Data!');
                        window.location.replace('kategori.php');
                    </script>
                ";
                exit();
            }
        }
    //End CUD Kategori

    //CUD Distributor
        //Add
        if(isset($_POST['submitdistributor'])){
            $distributor = $_POST['distributor'];
            $notelp = $_POST['notelp'];
            $email = $_POST['email'];
            $alamat = $_POST['alamat'];

            try {
                $insertdistributor = mysqli_query($koneksi, "insert into distributor (distributor, no_telp, email, alamat) values ('$distributor', '$notelp', '$email', '$alamat')");
                if($insertdistributor){
                    echo"
                        <script>
                            alert('Data Berhasil Ditambahkan!');
                            window.location.replace('distributor.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Data Sudah Ada!');
                        window.location.replace('distributor.php');
                    </script>
                ";
                exit();
            }
        }

        //Update
        if(isset($_POST['updatedistributor'])){
            $iddist = $_POST['iddist'];
            $distributor = $_POST['distributor'];
            $notelp = $_POST['notelp'];
            $email = $_POST['email'];
            $alamat = $_POST['alamat'];
            
            try {
                $updatedistributor = mysqli_query($koneksi, "update distributor set distributor = '$distributor', no_telp = '$notelp', email = '$email', alamat = '$alamat' where id_dist = '$iddist'");
                if($updatedistributor){
                    echo"
                        <script>
                            alert('Data Berhasil Diperbarui!');
                            window.location.replace('distributor.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Data Sudah Ada!');
                        window.location.replace('distributor.php');
                    </script>
                ";
                exit();
            }
        }

        //Delete
        if(isset($_POST['hapusdistributor'])){
            $iddist = $_POST['iddist'];

            try {
                $deletedistributor = mysqli_query($koneksi, "delete from distributor where id_dist = '$iddist'");
                if($deletedistributor){
                    echo"
                        <script>
                            alert('Data Berhasil Dihapus!');
                            window.location.replace('distributor.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Gagal Menghapus Data!');
                        window.location.replace('distributor.php');
                    </script>
                ";
                exit();
            }
        }
    //End CUD Distributor

    //Transaksi Masuk
        //Add
        if(isset($_POST['transaksimasuk'])){
            $tanggalWaktuSekarang = date("YmdHis");
            $kode = "BM" . $tanggalWaktuSekarang;
            $inserttransaksimasuk = mysqli_query($koneksi, "insert into transaksi (kode_transaksi, status) values ('$kode', 'in')");

            if ($inserttransaksimasuk) {
                header("Location: detailtransaksimasuk.php");
                exit();
            }
        }
    
    //Transaksi Keluar
        //Add
        if(isset($_POST['transaksikeluar'])){
            $tanggalWaktuSekarang = date("YmdHis");
            $kode = "BK" . $tanggalWaktuSekarang;
            $inserttransaksikeluar = mysqli_query($koneksi, "insert into transaksi (kode_transaksi, status) values ('$kode', 'out')");

            if ($inserttransaksikeluar) {
                header("Location: detailtransaksikeluar.php");
                exit();
            }
        }

    //CUD Barang
        //Add
        if(isset($_POST['submitbarang'])){
            $barang = $_POST['barang'];
            $kategori = $_POST['listkategori'];
            $satuan = $_POST['satuan'];
            $harga = $_POST['harga'];
            $exp = $_POST['exp'];

            $resultkategori = mysqli_query($koneksi, "SELECT kategori FROM kategori where id_kat = '$kategori'");
            $ambilkategori = mysqli_fetch_assoc($resultkategori);
            $kategorinya = $ambilkategori['kategori'];
            // Mengonversi huruf pertama setiap kata menjadi huruf besar
            $kategoriArray = explode(" ", ucwords($kategorinya));
            // Mengambil huruf pertama dari setiap kata
            $modifkategori = '';
            foreach ($kategoriArray as $word) {
                $modifkategori .= $word[0];
            }

            $resultPrimaryKey = mysqli_query($koneksi, "SELECT MAX(id_bar) AS max_id FROM barang");
            $rowPrimaryKey = mysqli_fetch_assoc($resultPrimaryKey);
            $lastPrimaryKey = $rowPrimaryKey['max_id'];
            $formattedPrimaryKey = sprintf("%03d", $lastPrimaryKey + 1);
            $productCode = $modifkategori . $formattedPrimaryKey;

            try {
                $insertbarang = mysqli_query($koneksi, "insert into barang (nama, fk_id_kat, jumlah, satuan, harga, exp_date, kode) values ('$barang', '$kategori', '0', '$satuan', '$harga', '$exp', '$productCode')");
                if($insertbarang){
                    echo"
                        <script>
                            alert('Data Berhasil Ditambahkan!');
                            window.location.replace('index.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Data Sudah Ada!');
                        window.location.replace('index.php');
                    </script>
                ";
                exit();
            }
        }

        //Update
        if(isset($_POST['updatebarang'])){
            $idbar = $_POST['idbar'];
            $barang = $_POST['barang'];
            $listkategori = $_POST['listkategori'];
            $stok = $_POST['stok'];
            $satuan = $_POST['satuan'];
            $harga = $_POST['harga'];
            $exp = $_POST['exp'];

            $resultkategori = mysqli_query($koneksi, "SELECT kategori FROM kategori where id_kat = '$listkategori'");
            $ambilkategori = mysqli_fetch_assoc($resultkategori);
            $kategorinya = $ambilkategori['kategori'];
            // Mengonversi huruf pertama setiap kata menjadi huruf besar
            $kategoriArray = explode(" ", ucwords($kategorinya));
            // Mengambil huruf pertama dari setiap kata
            $modifkategori = '';
            foreach ($kategoriArray as $word) {
                $modifkategori .= $word[0];
            }

            $formattedPrimaryKey = sprintf("%03d", $idbar);
            $productCode = $modifkategori . $formattedPrimaryKey;
            
            try {
                $updatebarang = mysqli_query($koneksi, "update barang set kode = '$productCode', nama = '$barang', fk_id_kat = '$listkategori', harga = '$harga', exp_date = '$exp', jumlah = '$stok' where id_bar = '$idbar'");
                if($updatebarang){
                    echo"
                        <script>
                            alert('Data Berhasil Diperbarui!');
                            window.location.replace('index.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Data Sudah Ada!');
                        window.location.replace('index.php');
                    </script>
                ";
                exit();
            }
        }

        //Delete
        if(isset($_POST['hapusbarang'])){
            $idbar = $_POST['idbar'];

            try {
                $deletebarang = mysqli_query($koneksi, "delete from barang where id_bar = '$idbar'");
                if($deletebarang){
                    echo"
                        <script>
                            alert('Data Berhasil Dihapus!');
                            window.location.replace('index.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Gagal Menghapus Data!');
                        window.location.replace('index.php');
                    </script>
                ";
                exit();
            }
        }
    //End CUD Barang

    //CUD Barang Masuk
        //Add
        if(isset($_POST['barangmasuk'])){
            $idtransaksi = $_POST['idtransaksi'];
            $barang = $_POST['listbarang'];
            $distributor = $_POST['listdist'];
            $jumlah = $_POST['jumlah'];
            $totharga = $_POST['harga'];
            
            try {
                $insertbarangmasuk = mysqli_query($koneksi, "insert into barang_masuk (fk_id_transaksi, fk_id_bar, jumlah, total_harga, fk_id_dist) values ('$idtransaksi', '$barang', '$jumlah', '$totharga', '$distributor')");
                if($insertbarangmasuk){
                    echo"
                        <script>
                            alert('Data Berhasil Ditambahkan!');
                            window.location.replace('detailtransaksimasuk.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Gagal Menambahkan Data!');
                        window.location.replace('detailtransaksimasuk.php');
                    </script>
                ";
                exit();
            }
        }

        //Update
        if(isset($_POST['updatebarangmasuk'])){
            $idbarmas = $_POST['idbarmas'];
            $jumlah = $_POST['jumlahbeli'];
            $totharga = $_POST['totharga'];

            try {
                $updatebarangmasuk = mysqli_query($koneksi, "update barang_masuk set jumlah = '$jumlah', total_harga = '$totharga' where id_barmas = '$idbarmas'");
                if($updatebarangmasuk){
                    echo"
                        <script>
                            alert('Data Berhasil Diperbarui!');
                            window.location.replace('detailtransaksimasuk.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Gagal Mengubah Data Karena Akan Menyebabkan Stok Barang Minus!');
                        window.location.replace('detailtransaksimasuk.php');
                    </script>
                ";
                exit();
            }
        }
    
        //Delete
        if(isset($_POST['hapusbarangmasuk'])){
            $idbarmas = $_POST['idbarmas'];

            try {
                $deletebarangmasuk = mysqli_query($koneksi, "delete from barang_masuk where id_barmas = '$idbarmas'");
                if($deletebarangmasuk){
                    echo"
                        <script>
                            alert('Data Berhasil Dihapus!');
                            window.location.replace('detailtransaksimasuk.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Gagal Menghapus Data Karena Akan Menyebabkan Stok Barang Minus!');
                        window.location.replace('detailtransaksimasuk.php');
                    </script>
                ";
                exit();
            }
        }
    //END CUD Barang Masuk

    //CUD Barang Keluar
        //Add
        if(isset($_POST['barangkeluar'])){
            $idtransaksi = $_POST['idtransaksi'];
            $barang = $_POST['listbarang'];
            $jumlah = $_POST['jumlah'];

            $resultharga = mysqli_query($koneksi, "SELECT harga FROM barang where id_bar = '$barang'");
            $ambilharga = mysqli_fetch_assoc($resultharga);
            $harga = $ambilharga['harga'];

            $totharga = $jumlah * $harga;
            
            try {
                $insertbarangkeluar = mysqli_query($koneksi, "insert into barang_keluar (fk_id_transaksi, fk_id_bar, jumlah, total_harga) values ('$idtransaksi', '$barang', '$jumlah', '$totharga')");
                if($insertbarangkeluar){
                    echo"
                        <script>
                            alert('Data Berhasil Ditambahkan!');
                            window.location.replace('detailtransaksikeluar.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Gagal Menambahkan Data Karena Stok Barang Tidak Cukup!');
                        window.location.replace('detailtransaksikeluar.php');
                    </script>
                ";
                exit();
            }
        }

        //Update
        if(isset($_POST['updatebarangkeluar'])){
            $idbarkel = $_POST['idbarkel'];
            $jumlah = $_POST['jumlahbeli'];
            $harga = $_POST['harga'];
            $totharga = $jumlah * $harga;

            try {
                $updatebarangkeluar = mysqli_query($koneksi, "update barang_keluar set jumlah = '$jumlah', total_harga = '$totharga' where id_barkel = '$idbarkel'");
                if($updatebarangkeluar){
                    echo"
                        <script>
                            alert('Data Berhasil Diperbarui!');
                            window.location.replace('detailtransaksikeluar.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Gagal Mengubah Data Karena Stok Barang Tidak Cukup!');
                        window.location.replace('detailtransaksikeluar.php');
                    </script>
                ";
                exit();
            }
        }
    
        //Delete
        if(isset($_POST['hapusbarangkeluar'])){
            $idbarkel = $_POST['idbarkel'];
            
            $deletebarangkeluar = mysqli_query($koneksi, "delete from barang_keluar where id_barkel = '$idbarkel'");
    
            if($deletebarangkeluar){
                echo"
                    <script>
                        alert('Data Berhasil Dihapus!');
                        window.location.replace('detailtransaksikeluar.php');
                    </script>
                ";
                exit();
            }
        }
    //END CUD Barang Keluar

    //CUD Admin
        //Add
        if(isset($_POST['submitpengguna'])){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $role = $_POST['role'];

            try {
                $insertpengguna = mysqli_query($koneksi, "insert into login (username, password, role) values ('$username', '$password', '$role')");
                if($insertpengguna){
                    echo"
                        <script>
                            alert('Data Berhasil Ditambahkan!');
                            window.location.replace('pengguna.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Gagal Menambahkan Data!');
                        window.location.replace('pengguna.php');
                    </script>
                ";
                exit();
            }
        }

        //Update
        if(isset($_POST['updatepengguna'])){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $role = $_POST['role'];

            try {
                $updatepengguna = mysqli_query($koneksi, "update login set password = '$password', role = '$role' where username = '$username'");
                if($updatepengguna){
                    echo"
                        <script>
                            alert('Data Berhasil Diperbarui!');
                            window.location.replace('pengguna.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Gagal Mengubah Data!');
                        window.location.replace('pengguna.php');
                    </script>
                ";
                exit();
            }
        }

        //Delete
        if(isset($_POST['hapuspengguna'])){
            $username = $_POST['username'];

            try {
                $deletepengguna = mysqli_query($koneksi, "delete from login where username = '$username'");
                if($deletepengguna){
                    echo"
                        <script>
                            alert('Data Berhasil Dihapus!');
                            window.location.replace('pengguna.php');
                        </script>
                    ";
                    exit();
                }
            } catch (Exception $e) {
                echo"
                    <script>
                        alert('Data Ini Tidak Dapat Dihapus!');
                        window.location.replace('pengguna.php');
                    </script>
                ";
                exit();
            }
        }
    //END CUD Pengguna

?>