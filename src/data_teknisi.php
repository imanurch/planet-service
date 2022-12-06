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

$field = "nama";
  if(isset($_GET['sort'])){
    $field = $_GET['sort'];
  }
// $teknisi = query("SELECT nama, tgl_lahir, no_telp, alamat FROM teknisi ORDER BY $field ASC");

if(isset($_GET["search"])){
  $search = $_GET["search"];
  $teknisi = query("SELECT * FROM teknisi 
                      WHERE nama LIKE '%$search%' 
                      OR tgl_lahir LIKE '%$search%'
                      OR no_telp LIKE '%$search%'
                      OR alamat LIKE '%$search%'
                      ORDER BY $field ASC");
} else{
  $teknisi = query("SELECT * FROM teknisi ORDER BY $field ASC "); 
}

if(isset($_POST["submit"])){
  if(tambah_teknisi($_POST) > 0){
    header('refresh:0; url=data_teknisi.php');
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
    <title>Data Teknisi</title>
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
        <form action="" method="post" >
          <button type="submit" name="logout">Logout</button>
          <!-- <a href="../src/home.html" class="">Logout</a> -->
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
              <li class="breadcrumb-item text-sm text-dark active">Data Teknisi</li>
            </ol>
          </nav>
        </div>
      </nav>
      <div class="mx-2 px-1">
        <div>
          <h3>Data Teknisi</h3>
          <p>Daftar teknisi yang bekerja</p>
        </div>
        <!-- Button trigger modal -->
        <div class="col-md-7">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="float:right">
            Tambah Data
          </button>
        </div>  
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Teknisi</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="" method="post">
                      <label for="nama" class="form-label">Nama</label>
                      <input type="text" class="form-control" id="nama" name="nama" />
                    
                      <label for="ttl" class="form-label">Tanggal Lahir</label>
                      <input type="date" class="form-control" id="ttl" name="ttl" />
                    
                      <label for="telp" class="form-label">Nomor Telepon</label>
                      <input type="text" class="form-control" id="telp" name="telp" />
                    
                      <label for="alamat" class="form-label">Alamat</label>
                      <input type="text" class="form-control" id="alamat" name="alamat" />

                      <button type="submit" class="btn btn-secondary mt-3" style="float:right" name="submit" >Submit</button>
                </form>
              </div>
              <div class="modal-footer">
              </div>
            </div>
          </div>
        </div>

        <!-- SEARCH
        <div class="" style="width:200px;">
          <div class="input-group">
            <div class="form-outline" action="" method="post">
              <input type="search" id="form1" class="form-control" placeholder="Search..." name="search" />
            </div>
          </div>            
        </div> -->

        <!-- SEARCH BAR -->
        <div style="width:200px;">
          <div class="input-group">
            <form action="" method="get">
              <input type="search" id="form1" class="form-control" placeholder="Cari..." name="search" />
            </form>
          </div>            
        </div>

        <!-- SORT -->
        <form action="" method="get">
          <div class="row" style="width:160px">
          <div class="col-12 my-3">
            <div class="input-group">
              <select name="sort" id="sort" class="form-control">
                <option selected disabled>--pilih--</option>
                <option value="nama" <?php if(isset($_GET['sort']) && $_GET['sort'] == "nama"){echo "selected";}?>>nama</option>
                <option value="tgl_lahir" <?php if(isset($_GET['sort']) && $_GET['sort'] == "tgl_lahir"){echo "selected";}?>>tanggal lahir</option>
                <option value="alamat" <?php if(isset($_GET['sort']) && $_GET['sort'] == "alamat"){echo "selected";}?>>alamat</option>
              </select>
              <button type="submit" class="input-group-text btn btn-secondary" name="submit" >Urut</button>
            </div>
          </div>
          </div>
        </form>
        
        <!-- TABEL TEKNISI -->
        <div class="col-12 my-3">
          <table class="table border">
            <thead class="text-center">
              <tr>
              <th class="text-secondary">NO</th>
                <th class="text-secondary">NAMA</th>
                <th class="text-secondary">TANGGAL LAHIR</th>
                <th class="text-secondary">NO TELEPON</th>
                <th class="text-secondary">ALAMAT</th>
                <th class="text-secondary">STATUS</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <?php $no = 1; ?>
              <?php foreach($teknisi as $tk) : ?>
              <tr>
                <td class="text-center mb-0"><?= $no; ?></td>
                <td class="mb-0"><?= $tk["nama"];?></td>
                <td class="mb-0"><?= $tk["tgl_lahir"];?></td>
                <td class="mb-0"><?= $tk["no_telp"];?></td>
                <td class="mb-0"><?= $tk["alamat"];?></td>
                <td class="mb-0"></td>
                <td class="align-middle">
                  <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user"> Edit </a>
                </td>
              </tr>
              <?php $no++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
  </body>
</html>
