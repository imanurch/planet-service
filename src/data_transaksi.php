<?php
include 'function.php';

$field = "kode_transaksi";
  if(isset($_GET['sort'])){
    $field = $_GET['sort'];
  }

if(isset($_GET["search"])){
  $search = $_GET["search"];
  $transaksi = query("SELECT * FROM transaksi 
                      WHERE kode_transaksi LIKE '%$search%' 
                      OR tgl_transaksi LIKE '%$search%'
                      OR id_servis LIKE '%$search%'
                      OR total_biaya LIKE '%$search%' 
                      ORDER BY $field ASC");
} else{
  $transaksi = query("SELECT * FROM transaksi ORDER BY $field ASC "); 
}

if(isset($_POST["reset"])){
  $reset = $_POST["reset"];
  $transaksi = query("SELECT * FROM transaksi ORDER BY kode_transaksi ASC");
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Transaksi</title>
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
              <li class="breadcrumb-item text-sm text-dark active">Data Transaksi</li>
            </ol>
          </nav>
        </div>
      </nav>
      <div class="mx-2 px-1">
        <div>
          <h3>Data Transaksi</h3>
          <p>Daftar Transaksi yang sudah berlalu dan yang sedang berjalan</p>
        </div>
        
        <!-- SEARCH BAR -->
        <div style="width:200px;">
          <div class="input-group">
            <form action="" method="get">
              <input type="search" id="form1" class="form-control" placeholder="Cari..." name="search" />
            </form>
            <!-- <button type="button" class="btn btn-primary" style="float:right;">
              <img src="../pic/search.png" alt="" style="width: 15px;">
              <i class="fas fa-search"></i>
            </button> -->
          </div>            
        </div>
      
        <!-- SORT -->
        <form action="" method="get">
          <div class="row" style="width:160px">
          <div class="col-12 my-3">
            <div class="input-group">
              <select name="sort" id="sort" class="form-control">
                <option selected disabled>--pilih--</option>
                <!-- kode_transaksi LIKE '%$search%' 
                      OR tgl_transaksi LIKE '%$search%'
                      OR id_servis LIKE '%$search%'
                      OR total_biaya  -->
                <option value="kode_transaksi" <?php if(isset($_GET['sort']) && $_GET['sort'] == "kode_transaksi"){echo "selected";}?>>Kode Transaksi</option>
                <option value="tgl_transaksi" <?php if(isset($_GET['sort']) && $_GET['sort'] == "tgl_transaksi"){echo "selected";}?>>Tanggal Transaksi</option>
                <option value="id_teknisi" <?php if(isset($_GET['sort']) && $_GET['sort'] == "id_teknisi"){echo "selected";}?>>ID Teknisi</option>
                <option value="id_pelanggan" <?php if(isset($_GET['sort']) && $_GET['sort'] == "id_pelanggan"){echo "selected";}?>>ID Pelanggan</option>
                <option value="id_servis" <?php if(isset($_GET['sort']) && $_GET['sort'] == "id_servis"){echo "selected";}?>>ID Servis</option>
                <option value="total_biaya" <?php if(isset($_GET['sort']) && $_GET['sort'] == "total_biaya"){echo "selected";}?>>Total Biaya</option>
                <option value="status" <?php if(isset($_GET['sort']) && $_GET['sort'] == "status"){echo "selected";}?>>Status</option>
              </select>
              <button type="submit" class="input-group-text btn btn-secondary" name="submit" >Urut</button>
            </div>
          </div>
          </div>
        </form>

        <!-- RESET FILTER -->
        <form action="" method="post">
          <button class="input-group-text btn btn-secondary" style="" id="reset" name="reset">
            Reset filter
          </button>
        </form>

        <!-- TABEL DATA TRANSAKSI -->
        <div class="col-12 col-md-12 my-3">
          <table class="table border">
            <thead class="text-center">
              <tr>
                <th class="text-secondary">NO</th>
                <th class="text-secondary">TANGGAL SERVIS</th>
                <th class="text-secondary">KODE TRANSAKSI</th>
                <th class="text-secondary">ID TEKNISI</th>
                <th class="text-secondary">ID PELANGGAN</th>
                <th class="text-secondary">ID SERVIS</th>
                <th class="text-secondary">TOTAL BIAYA</th>
                <th class="text-secondary">STATUS</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              <?php foreach($transaksi as $trs) : ?>
              <tr>
                <td class="text-center mb-0"><?= $no; ?></td>
                <td class="mb-0"><?= $tr[""];?></td>
                <td class="mb-0"><?= $tr[""];?></td>
              </tr>
              <?php $no++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>