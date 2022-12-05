<?php
include 'function.php';
date_default_timezone_set('Asia/Jakarta');
// $device = query("SELECT tipe FROM sparepart"); 
$keluhan = query("SELECT keluhan FROM layanan ORDER BY keluhan ASC"); 
$sparepart = query("SELECT DISTINCT nama FROM sparepart ORDER BY nama ASC"); 
$teknisi = query("SELECT nama FROM teknisi ORDER BY nama ASC"); 

// if(isset($_POST["submit"])){
//   if(tambah_servis($_POST) > 0){
//     header('refresh:0; url=data_produk.php');
//     echo "<script>alert('data berhasil ditambahkan')</script>";
//   } else{
//     echo "<script>alert('data gagal ditambahkan')</script>";
//   }
// }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Transaksi Baru</title>
    <link href="../scss/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <!-- SIDEBARR -->
    <div class="bg-dark text-secondary position-fixed" style="width: 200px; height: 700px">
      <div class="profil text-center">
        <img src="../pic/ava.png" class="mx-auto pt-4 pb-2 d-block" style="width: 30%" alt="" />
        Admin
      </div>

      <div class="menu">
        <div class="list-group mt-5 mx-3">
          <a href="/src/dashboard.html" class="list-group-item list-group-item-action" aria-current="true">Dashboard</a>
          <a href="/src/transaksi_baru.html" class="list-group-item list-group-item-action">Servis Baru</a>
          <a href="/src/data_transaksi.html" class="list-group-item list-group-item-action">Data Transaksi</a>
          <a href="/src/data_layanan.html" class="list-group-item list-group-item-action">Data Layanan</a>
          <a href="/src/data_produk.html" class="list-group-item list-group-item-action">Data Produk</a>
          <a href="/src/data_teknisi.html" class="list-group-item list-group-item-action">Data Teknisi</a>
        </div>
      </div>
      <div class="position-absolute bottom-0 p-5 text-second text-center">
        <div>
          <img src="../pic/" style="width: 25px" alt="" />
          <a href="../src/home.html" class="">Logout</a>
        </div>
      </div>
    </div>

    <!-- konten -->
    <div class="px-2" style="margin-left: 200px">
      <nav class="navbar navbar-main navbar-expand-lg pt-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
        <div class="container-fluid">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item text-sm">Pages</li>
              <li class="breadcrumb-item text-sm text-dark active">Transaksi Baru</li>
            </ol>
          </nav>
        </div>
      </nav>

      <div class="mx-2 px-1">
        <div>
          <h3>Servis Baru</h3>
          <p>Lengkapi data untuk membuat transaksi baru</p>
        </div>
        <!-- FORM INPUT -->
        <div class="my-5">
          <form action="" method="post">
            <div class="row mb-3">
            <label for="inputDate" class="col-1 col-form-label text-wrap" style="width: 10rem">Waktu</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <input type="text" class="form-control" id="date" name="date" disabled value="<?php echo date('l, d-m-Y H:i:s a')?>"/>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inputPelanggan" class="col-1 col-form-label text-wrap" style="width: 10rem">Nama Pelanggan</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" />
              </div>
            </div>
            <div class="row mb-3">
              <label for="inputTelp" class="col-1 col-form-label text-wrap" style="width: 10rem">Nomor Telepon</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <input type="text" class="form-control" id="no_telp" name ="no_telp"/>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inputAlamat" class="col-1 col-form-label text-wrap" style="width: 10rem">Alamat</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <input type="text" class="form-control" id="alamat" name="alamat" />
              </div>
            </div>
            <div class="row mb-3">
              <label for="inputDevice" class="col-1 col-form-label text-wrap" style="width: 10rem">Device</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <input type="text" class="form-control" id="nama_device" name="nama_device" />
              </div>
            </div>
            <div class="row mb-3">
              <label for="inputKeluhan" class="col-1 col-form-label text-wrap" style="width: 10rem">Keluhan</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <!-- <input type="text" class="form-control" id="keluhan" name="keluhan" /> -->
                <div class="form-floating">
                  <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                    <option selected disabled>-</option>
                    <?php foreach($keluhan as $kl) : ?>
                    <option value=""><?= $kl["keluhan"];?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inputTipe" class="col-1 col-form-label text-wrap" style="width: 10rem">Tipe Layanan</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="jasa" id="jasa" value="option1" checked />
                  <label class="form-check-label" for="gridRadios1"> Jasa </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="ganti_sparepart" id="ganti_sparepart" value="option2" />
                  <label class="form-check-label" for="gridRadios2"> Ganti Sparepart </label>
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inputSparepart" class="col-1 col-form-label text-wrap" style="width: 10rem">Sparepart</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <div class="form-floating">
                  <select class="form-select select-initialized" id="floatingSelect" aria-label="Floating label select example">
                    <option selected>-</option>
                    <?php foreach($sparepart as $spr) : ?>
                    <option value=""><?= $spr["nama"];?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inputTeknisi" class="col-1 col-form-label text-wrap" style="width: 10rem">Teknisi</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <div class="form-floating">
                  <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                    <option selected>-</option>
                    <?php foreach($teknisi as $tk) : ?>
                    <option value=""><?= $tk["nama"];?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-secondary">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
