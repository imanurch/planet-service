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

$layanan = mysqli_query($conn, "SELECT * FROM layanan");
$sparepart = mysqli_query($conn, "SELECT DISTINCT nama FROM sparepart");
$teknisi = mysqli_query($conn, "SELECT * FROM teknisi");
$transaksi = mysqli_query($conn, "SELECT * FROM transaksi");

$jml_layanan = mysqli_num_rows($layanan);
$jml_sparepart = mysqli_num_rows($sparepart);
$jml_teknisi = mysqli_num_rows($teknisi);
$jml_transaksi = mysqli_num_rows($transaksi);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>
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
          <a href="dashboard.php" class="list-group-item list-group-item-action" aria-current="true">Dashboard</a>
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
              <li class="breadcrumb-item text-sm text-dark active">Dashboard</li>
            </ol>
          </nav>
        </div>
      </nav>
      <div class="text-center mb-5 mx-3">
        <h3>Halo! Selamat Datang di <span style="color: #76a4ea">PLANET SERVICE</span></h3>
      </div>
      <!-- ISI KONTEN -->
      <div class="row my-16 mx-2 px-0">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header py-2 px-4">
              <div>
                <img src="../pic/ava.png" class="py-1 my-0" style="width: 4rem; float: left" alt="" />
              </div>
              <div class="text-end py-2">
                <p class="text-sm mb-0 text-capitalize">Total Transaksi</p>
                <h4 class="mb-0 pb-0"><?php echo $jml_transaksi;?></h4>
              </div>
            </div>
            <hr class="horizontal my-0" />
            <div class="card-footer pt-2">
              <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+25% </span>dari minggu lalu</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header py-2 px-4">
              <div>
                <img src="../pic/ava.png" class="py-1 my-0" style="width: 4rem; float: left" alt="" />
              </div>
              <div class="text-end py-2">
                <p class="text-sm mb-0 text-capitalize">Total Jenis Sparepart</p>
                <h4 class="mb-0 pb-0"><?php echo $jml_sparepart;?></h4>
              </div>
            </div>
            <hr class="horizontal my-0" />
            <div class="card-footer pt-2">
              <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+25% </span>dari minggu lalu</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header py-2 px-4">
              <div>
                <img src="../pic/ava.png" class="py-1 my-0" style="width: 4rem; float: left" alt="" />
              </div>
              <div class="text-end py-2">
                <p class="text-sm mb-0 text-capitalize">Total Jenis Layanan</p>
                <h4 class="mb-0 pb-0"><?php echo $jml_layanan;?></h4>
              </div>
            </div>
            <hr class="horizontal my-0" />
            <div class="card-footer pt-2">
              <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+25% </span>dari minggu lalu</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header py-2 px-4">
              <div>
                <img src="../pic/ava.png" class="py-1 my-0" style="width: 4rem; float: left" alt="" />
              </div>
              <div class="text-end py-2">
                <p class="text-sm mb-0 text-capitalize">Jumlah Teknisi</p>
                <h4 class="mb-0 pb-0"><?php echo $jml_teknisi;?></h4>
              </div>
            </div>
            <hr class="horizontal my-0" />
            <div class="card-footer pt-2">
              <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+25% </span>dari minggu lalu</p>
            </div>
          </div>
        </div>
        <!-- GRAFIK -->
        <div class="grafik my-5">
          <div class="col-lg-6">
            <div class="card z-index-2">
              <div class="card-header pb-0">
                <h5>Grafik Penjualan</h5>
                <p class="text-sm">
                  <i class="fa fa-arrow-up text-success"></i>
                  <span class="font-weight-bold">Meningkat 4%</span> dari bulan lalu
                </p>
              </div>
              <div class="card-body p-3">
                <div class="chart">
                  <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- </div> -->
  </body>
</html>
