<?php
include 'function.php';

$field = "tipe_layanan";
  if(isset($_GET['sort'])){
    $field = $_GET['sort'];
  }
// $layanan = query("SELECT tipe_layanan, keluhan, biaya FROM layanan ORDER BY $field ASC");

$urut = "ASC";
if(isset($_POST['DESC'])){
  $urut = $_POST['DESC'];
}

$max = "10";
if(isset($_GET['10'])){
  $max = 10;
} else if(isset($_GET['25'])){
  $max = 25;
}

// $range = 20;
// if(isset($_GET['pg1'])){
//   $range = "0,3";
// } else if(isset($_GET['pg2'])){
//   $range = "10,4";
// }

if(isset($_GET["search"])){
  $search = $_GET["search"];
  $layanan = query("SELECT * FROM layanan 
                      WHERE tipe_layanan LIKE '%$search%' 
                      OR keluhan LIKE '%$search%'
                      OR biaya LIKE '%$search%'
                      ORDER BY $field $urut LIMIT $max");
} else{
  $layanan = query("SELECT * FROM layanan ORDER BY $field $urut LIMIT $max"); 
}

if(isset($_POST["reset"])){
  $reset = $_POST["reset"];
  $layanan = query("SELECT * FROM layanan ORDER BY tipe_layanan ASC LIMIT 20");
}

if(isset($_POST["submit"])){
  if(tambah_layanan($_POST) > 0){
    header('refresh:0; url=data_layanan.php');
    echo "<script>alert('data berhasil ditambahkan')</script>";
  } else{
    echo "<script>alert('data gagal ditambahkan')</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Layanan</title>
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

    <!-- KONTEN -->
    <div class="px-2" style="margin-left: 200px">
      <nav class="navbar navbar-main navbar-expand-lg pt-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
        <div class="container-fluid">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item text-sm">Pages</li>
              <li class="breadcrumb-item text-sm text-dark active">Data Layanan</li>
            </ol>
          </nav>
        </div>
      </nav>
      <div class="mx-2 px-1">
        <div>
          <h3>Data Layanan</h3>
          <p>Daftar jasa layanan yang tersedia</p>
        </div>

        <!-- SEARCH BAR -->
        <div style="width:200px;">
          <div class="input-group">
            <form action="" method="get">
              <input type="search" id="form1" class="form-control " placeholder="Cari..." name="search" />
              <!-- <img src="../pic/search.png" alt="" style="width: 15px;"> -->
            </form>
            <!-- <button type="button" class="btn btn-primary" style="float:right;">
              <img src="../pic/search.png" alt="" style="width: 15px;">
              <i class="fas fa-search"></i>
            </button> -->
          </div>            
        </div>
        
        <!-- BUTTON MODAL INSERT -->
        <div class="col-12">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="float:right">
            Tambah Data
          </button>
        </div>  
        <!-- MODAL INSERT -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Layanan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="" method="post">
                      <label for="tipe" class="form-label">Tipe</label>
                      <!-- <input type="text" class="form-control" id="tipe" name="tipe" /> -->
                      <select class="form-select" id="tipe" name="tipe">
                        <option value="1">Jasa</option>
                        <option value="2">Ganti Sparepart</option>
                      </select>
                    
                      <label for="keluhan" class="form-label">Keluhan</label>
                      <input type="text" class="form-control" id="keluhan" name="keluhan" />
                    
                      <label for="biaya" class="form-label">Biaya</label>
                      <input type="text" class="form-control" id="biaya" name="biaya" />
                
                      <button type="submit" class="btn btn-secondary mt-3" style="float:right" name="submit" >Submit</button>
                </form>
              </div>
              <div class="modal-footer">
              </div>
            </div>
          </div>
        </div>

        <!-- SORT -->
        <form action="" method="get">
          <div class="row" style="width:170px">
            <div class="col-12 my-3">
              <div class="input-group">
                <select name="sort" id="sort" class="autosubmit form-control">
                  <option selected disabled>--pilih--</option>
                  <option value="tipe_layanan" <?php if(isset($_GET['sort']) && $_GET['sort'] == "tipe_layanan"){echo "selected";}?>>tipe_layanan</option>
                  <option value="keluhan" <?php if(isset($_GET['sort']) && $_GET['sort'] == "keluhan"){echo "selected";}?>>keluhan</option>
                  <option value="biaya" <?php if(isset($_GET['sort']) && $_GET['sort'] == "biaya"){echo "selected";}?>>biaya</option>
                </select>
                <button type="submit" class="input-group-text btn btn-secondary" name="submit" >Urut</button>
              </div>
              <form action="" method="post">
                <input class="form-check-input" type="checkbox" value="" name="DESC" id="DESC">
                <label class="form-check-label" for="flexCheckDefault">
                  DESC
                </label>
              </form>
            </div>
          </div>
        </form>

        <!-- switch -->
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
          <label class="form-check-label" for="flexSwitchCheckChecked">Descending</label>
        </div>

        <!-- RESET FILTER -->
        <form action="" method="post">
          <button class="input-group-text btn btn-secondary" style="" id="reset" name="reset">
            Reset filter
          </button>
        </form>

        <!-- TABEL DATA LAYANAN -->
        <div class="col-12 my-3">
          <table class="table border">
            <thead class="text-center">
              <tr>
                <th class="text-secondary">NO</th>
                <th class="text-secondary">TIPE LAYANAN</th>
                <th class="text-secondary">KELUHAN</th>
                <th class="text-secondary">BIAYA</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <?php $no = 1; ?>
              <?php foreach($layanan as $ly) : ?>
              <tr>
                <td class="text-center mb-0"><?= $no; ?></td>
                <td class="mb-0"><?= $ly["tipe_layanan"];?></td>
                <td class="mb-0"><?= $ly["keluhan"];?></td>
                <td class="text-center mb-0"><?= $ly["biaya"];?></td>
                <td class="align-middle">
                  <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user"> Edit </a>
                </td>
              </tr>
              <?php $no++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- RANGE DATA -->
        <!-- <form action="" method="get" class="px-0">
          <div class="input-group">
            <button type="submit" class="input-group-text btn btn-secondary" name = "pg1" >1</button>
            <button type="submit" class="input-group-text btn btn-secondary" name = "pg2" >2</button>
            <button type="submit" class="input-group-text btn btn-secondary" name = "pg3" >3</button>
          </div>
        </form> -->

        <!-- MAX DATA -->
        <form action="" method="get" class="px-0">
          <div class="input-group">
            <button type="submit" class="input-group-text btn btn-secondary" name = "10" >10</button>
            <button type="submit" class="input-group-text btn btn-secondary" name = "25" >25</button>
          </div>
        </form>
      </div>
    </div>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
  </body>
</html>
