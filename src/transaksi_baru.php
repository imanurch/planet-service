<?php
session_start();

if(!isset($_SESSION["Login"])){
  header('refresh:0; url = login.php');
  exit;
}

if(isset($_POST["logout"])){
  session_unset();
  session_destroy();
  header('refresh:0; url=home.php');
  exit();
}

include 'function.php';

date_default_timezone_set('Asia/Jakarta');
// $device = query("SELECT tipe FROM sparepart"); 
$pelanggan = query("SELECT id_pelanggan, no_telp, nama FROM pelanggan ORDER BY nama ASC"); 
$device = query("SELECT id_device, nama FROM device ORDER BY nama ASC"); 
$keluhan = query("SELECT id_layanan, keluhan FROM layanan ORDER BY keluhan ASC"); 
$sparepart = query("SELECT id_sparepart, nama FROM sparepart ORDER BY nama ASC"); 
$teknisi = query("SELECT id_teknisi, nama FROM teknisi ORDER BY nama ASC"); 

// $kode_transaksi = mysqli_query($conn, "SELECT MAX(id_transaksi) as max_kode from transaksi");


// if(isset($_POST["nama"])){
  // if(tambah_servis($_POST) > 0){
  //   header('refresh:0; url=transaksi_baru.php');
  //   echo "<script>alert('data berhasil ditambahkan')</script>";
  // } else{
  //   echo "<script>alert('data gagal ditambahkan')</script>";
  // }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
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
          <a href="dashboard.php" class="list-group-item list-group-item-action" aria-current="true">Dashboard</a>
          <a href="transaksi_baru.php" class="list-group-item list-group-item-action">Servis Baru</a>
          <a href="data_transaksi.php" class="list-group-item list-group-item-action">Data Transaksi</a>
          <a href="data_layanan.php" class="list-group-item list-group-item-action">Data Layanan</a>
          <a href="data_produk.php" class="list-group-item list-group-item-action">Data Sparepart</a>
          <a href="data_device.php" class="list-group-item list-group-item-action">Data Device</a>
          <a href="data_pelanggan.php" class="list-group-item list-group-item-action">Data Pelanggan</a>
          <a href="data_teknisi.php" class="list-group-item list-group-item-action">Data Teknisi</a>
        </div>
      </div>
      <div class="position-absolute bottom-0 p-5 text-second text-center">
        <form action="" method="post" >
        <button class="btn text-secondary" type="submit" name="logout"><img class="me-1" src="../pic/logout.svg" alt=""> Logout</button>
        </form>
      </div>
    </div>

    <!-- konten -->
    <div class="px-2" style="margin-left: 200px">
      <nav class="navbar navbar-main navbar-expand-lg pt-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
        <div class="container-fluid">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item text-sm">Pages</li>
              <li class="breadcrumb-item text-sm text-dark active">Servis Baru</li>
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
                <input type="text" class="form-control" id="tgl_masuk" name="tgl_masuk" disabled value="<?php echo date('l, d-m-Y H:i:s a')?>"/>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inputPelanggan" class="col-1 col-form-label text-wrap" style="width: 10rem">Nama Pelanggan</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <select class="selectpicker form-control" data-live-search="true" name="id_pelanggan" id="floatingSelect" aria-label="Floating label select example" >
                  <option selected disabled>-</option>
                  <?php foreach($pelanggan as $pl) : ?>
                  <option value="<?= $pl["id_pelanggan"];?>"><?= $pl["no_telp"];?> | <?= $pl["nama"];?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <!-- <div class="row mb-3">
              <label for="inputDevice" class="col-1 col-form-label text-wrap" style="width: 10rem">Device</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <select class="selectpicker form-control" data-live-search="true" name="id_device" id="floatingSelect" aria-label="Floating label select example">
                  <option selected disabled>-</option>
                  <?php foreach($device as $dc) : ?>
                  <option value="<?= $dc["id_device"];?>"><?= $dc["nama"];?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inputKeluhan" class="col-1 col-form-label text-wrap" style="width: 10rem">Keluhan</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <select class="selectpicker form-control" data-live-search="true" name="id_layanan" id="floatingSelect" aria-label="Floating label select example">
                  <option selected disabled>-</option>
                  <?php foreach($keluhan as $kl) : ?>
                  <option value="<?= $kl["id_layanan"];?>"><?= $kl["keluhan"];?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div> -->
            <!-- <div class="row mb-3">
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
            </div> -->
            <!-- <div class="row mb-3">
              <label for="inputSparepart" class="col-1 col-form-label text-wrap" style="width: 10rem">Sparepart</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <select class="selectpicker form-control" data-live-search="true" name="id_sparepart" id="floatingSelect" aria-label="Floating label select example">
                  <option selected>-</option>
                  <?php foreach($sparepart as $spr) : ?>
                  <option value="<?= $spr["id_sparepart"];?>"><?= $spr["nama"];?></option>
                  <?php endforeach; ?>
                </select> 
              </div>
            </div>
            <div class="row mb-3">
              <label for="inputJumlahSparepart" class="col-1 col-form-label text-wrap" style="width: 10rem">Jumlah</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <input type="text" class="form-control" id="jml_sparepart" name="jml_sparepart">
              </div>
           </div> -->
            <div class="row mb-3">
              <label for="inputTeknisi" class="col-1 col-form-label text-wrap" style="width: 10rem">Teknisi</label>
              <div class="col-12 col-sm-6 col-lg-5">
                <select class="selectpicker form-control" data-live-search="true" name="id_teknisi" id="floatingSelect" aria-label="Floating label select example">
                  <option selected>-</option>
                  <?php foreach($teknisi as $tk) : ?>
                  <option value="<?= $tk["id_teknisi"];?>"><?= $tk["id_teknisi"];?> | <?= $tk["nama"];?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-secondary">Simpan</button>
          </form>
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
  </body>
</html>
