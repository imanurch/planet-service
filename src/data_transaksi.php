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

$total_layanan = mysqli_query($conn, "SELECT * FROM transaksi");
$total_row = mysqli_num_rows($total_layanan);

$field = "kode_transaksi";
  if(isset($_GET['sort'])){
    $field = $_GET['sort'];
  }

if(isset($_GET["search"])){
  $search = $_GET["search"];
  // $transaksi = query("SELECT * FROM transaksi 
  //                     WHERE kode_transaksi LIKE '%$search%' 
  //                     OR tgl_transaksi LIKE '%$search%'
  //                     OR id_servis LIKE '%$search%'
  //                     OR total_biaya LIKE '%$search%' 
  //                     ORDER BY $field ASC");
} else{
  // $transaksi = query("SELECT * FROM transaksi ORDER BY $field ASC "); 
}

if(isset($_POST["reset"])){
  $reset = $_POST["reset"];
  // $transaksi = query("SELECT * FROM transaksi ORDER BY kode_transaksi ASC");
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
      <div class="text-second text-start ps-1 py-2">
        <form action="" method="post" >
          <button class="btn text-secondary" type="submit" name="logout"><img class="me-1" src="../pic/logout.svg" alt=""> Logout</button>
        </form>
      </div>
      <!-- <div class="position-absolute bottom-0 p-4 text-second text-center">
        <p>by <a href="">Ciao</a></p>
      </div> -->
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
        
        <!-- FILTER DATA DALAM TABEL -->
        <nav class="navbar bg-light mt-5">
          <div class="container-fluid">
            <!-- RESET FILTER -->
            <form class="d-flex m-1" action="" method="post">
              <button class="btn btn-outline-success ms-1" style="" id="reset" name="reset">
                Reset filter
              </button>
            </form>
            <!-- SEARCH BAR -->
            <form class="d-flex m-1" role="search" action="" method="post">
              <input class="form-control me-1" type="search" placeholder="Search" aria-label="Search" name="search">
              <button class="btn btn-outline-success" type="submit" name="submitsearch">Search</button>
            </form>
            <!-- SORTING -->
            <form class="d-flex m-1" action="" method="post">
              <select name="sort" id="sort" class="form-select ">
                <!-- <option selected disabled>--pilih--</option> -->
                <option selected value="tgl_masuk" <?php if(isset($_GET['sort']) && $_GET['sort'] == "tgl_masuk"){echo "selected";}?>>Tanggal Masuk</option>
                <option value="kode_transaksi" <?php if(isset($_GET['sort']) && $_GET['sort'] == "kode_transaksi"){echo "selected";}?>>Kode Transaksi</option>
                <option value="id_teknisi" <?php if(isset($_GET['sort']) && $_GET['sort'] == "id_teknisi"){echo "selected";}?>>ID Teknisi</option>
                <option value="id_pelanggan" <?php if(isset($_GET['sort']) && $_GET['sort'] == "id_pelanggan"){echo "selected";}?>>ID Pelanggan</option>
                <option value="total_biaya" <?php if(isset($_GET['sort']) && $_GET['sort'] == "total_biaya"){echo "selected";}?>>Total Biaya</option>
                <option value="status" <?php if(isset($_GET['sort']) && $_GET['sort'] == "status"){echo "selected";}?>>Status</option>
              </select>
              <select name="flow" id="flow" class="form-select">
                <!-- <option selected disabled>--pilih--</option> -->
                <option selected value="ASC" <?php if(isset($_GET['flow']) && $_GET['flow'] == "ASC"){echo "selected";}?>>ASC</option>
                <option value="DESC" <?php if(isset($_GET['flow']) && $_GET['flow'] == "DESC"){echo "selected";}?>>DESC</option>
              </select>
              <button class="btn btn-outline-success ms-1" type="submit" name="submitsort">Sort</button>
            </form>
            <!-- MAKSIMUM ROW DATA -->
            <form class="d-flex m-1" action="" method="post">
              <select name="max" id="max" class="form-select ">
                <option selected value="10" <?php if(isset($_GET['max']) && $_GET['max'] == "10"){echo "selected";}?>>10</option>
                <option value="25" <?php if(isset($_GET['max']) && $_GET['max'] == "25"){echo "selected";}?>>25</option>
                <option value="50" <?php if(isset($_GET['max']) && $_GET['max'] == "50"){echo "selected";}?>>50</option>
              </select>
              <button class="btn btn-outline-success ms-1" type="submit" name="submitmax">Sort</button>
            </form>
          </div>
        </nav>

        <!-- TABEL DATA TRANSAKSI -->
        <p class="mt-3 mb-0 text-secondary">Total Data : <?php echo $total_row; ?></p>
        <div class="col-12 col-md-12 my-3">
          <table class="table border">
            <thead class="text-center">
              <tr>
                <th class="text-secondary">NO</th>
                <th class="text-secondary">TANGGAL MASUK</th>
                <th class="text-secondary">KODE TRANSAKSI</th>
                <th class="text-secondary">ID TEKNISI</th>
                <th class="text-secondary">ID PELANGGAN</th>
                <th class="text-secondary">TOTAL BIAYA</th>
                <th class="text-secondary">STATUS</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              <!-- <?php foreach($transaksi as $tr) : ?> -->
              <tr>
                <td class="text-center mb-0"><?= $no; ?></td>
                <!-- <td class="mb-0"><?= $tr[""];?></td>
                <td class="mb-0"><?= $tr[""];?></td>
                <td class="mb-0"><?= $tr[""];?></td>
                <td class="mb-0"><?= $tr[""];?></td>
                <td class="mb-0"><?= $tr[""];?></td>
                <td class="mb-0"><?= $tr[""];?></td> -->
                <td><a class="btn btn-outline-secondary mt-3" href="#edit<?= $ly["id_layanan"];?>" data-bs-toggle="modal" data-bs-target="#edit<?= $ly["id_layanan"];?>"><img src="../pic/edit.svg" alt=""></a></td>
                <td><a class="btn btn-outline-secondary mt-3" href="hapus.php?id=<?= $ly["id_layanan"];?>"><img src="../pic/trash.svg" alt=""></a></td>
              </tr>
              <?php $no++; ?>
              <!-- <?php endforeach; ?> -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>

        <!-- SEARCH BAR -->
        <!-- <div style="width:200px;">
          <div class="input-group">
            <form action="" method="get">
              <input type="search" id="form1" class="form-control" placeholder="Cari..." name="search" />
            </form> -->
            <!-- <button type="button" class="btn btn-primary" style="float:right;">
              <img src="../pic/search.png" alt="" style="width: 15px;">
              <i class="fas fa-search"></i>
            </button> -->
          <!-- </div>            
        </div> -->
      
        <!-- SORT -->
        <!-- <form action="" method="get">
          <div class="row" style="width:160px">
          <div class="col-12 my-3">
            <div class="input-group">
              <select name="sort" id="sort" class="form-control">
                <option selected disabled>--pilih--</option> -->
                <!-- kode_transaksi LIKE '%$search%' 
                      OR tgl_transaksi LIKE '%$search%'
                      OR id_servis LIKE '%$search%'
                      OR total_biaya  -->
                <!-- <option value="kode_transaksi" <?php if(isset($_GET['sort']) && $_GET['sort'] == "kode_transaksi"){echo "selected";}?>>Kode Transaksi</option>
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
        </form> -->

        <!-- RESET FILTER -->
        <!-- <form action="" method="post">
          <button class="input-group-text btn btn-secondary" style="" id="reset" name="reset">
            Reset filter
          </button>
        </form> -->