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

$total_layanan = mysqli_query($conn, "SELECT * FROM teknisi");
$total_row = mysqli_num_rows($total_layanan);

$field = "nama";
  if(isset($_POST['sort'])){
    $field = $_POST['sort'];
  }

$flow = "ASC";
if(isset($_POST['flow'])){
  $flow = $_POST['flow'];
}

$max = "10";
if(isset($_POST['max'])){
  $max = $_POST['max'];
}

if(isset($_POST["reset"])){
  $reset = $_POST["reset"];
  $teknisi = query("SELECT * FROM teknisi ORDER BY nama ASC LIMIT 10");
}

if(isset($_POST["submitsearch"])){
  $search = $_POST["search"];
  $teknisi = query("SELECT * FROM teknisi 
                      WHERE nama LIKE '%$search%' 
                      OR tgl_lahir LIKE '%$search%'
                      OR no_telp LIKE '%$search%'
                      OR alamat LIKE '%$search%'
                      ORDER BY $field $flow LIMIT $max");
} else{
  $teknisi = query("SELECT * FROM teknisi ORDER BY $field $flow LIMIT $max"); 
}

if(isset($_POST["submit"])){
  if(tambah_teknisi($_POST) > 0){
    header('refresh:0; url=data_teknisi.php');
    echo "<script>alert('data berhasil ditambahkan')</script>";
  } else{
    echo "<script>alert('data gagal ditambahkan')</script>";
  }
}

if(isset($_POST["submitedit"])){
  if(edit_teknisi($_POST) > 0){
    header('refresh:0; url=data_teknisi.php');
    echo "<script>alert('data berhasil diedit')</script>";
  } else{
    echo "<script>alert('data gagal diedit')</script>";
  }
}

if(isset($_POST["submithapus"])){
  if(hapus_teknisi($_POST) > 0){
    header('refresh:0; url=data_teknisi.php');
    echo "<script>alert('data berhasil dihapus')</script>";
  } else{
    echo "<script>alert('data gagal dihapus')</script>";
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
                <option selected disabled>--pilih--</option>
                <option value="nama" <?php if(isset($_GET['sort']) && $_GET['sort'] == "nama"){echo "selected";}?>>nama</option>
                <option value="tgl_lahir" <?php if(isset($_GET['sort']) && $_GET['sort'] == "tgl_lahir"){echo "selected";}?>>tanggal lahir</option>
                <option value="alamat" <?php if(isset($_GET['sort']) && $_GET['sort'] == "alamat"){echo "selected";}?>>alamat</option>
              </select>
              <select name="flow" id="flow" class="form-select">
                <option selected disabled>--pilih--</option>
                <option value="ASC" <?php if(isset($_GET['flow']) && $_GET['flow'] == "ASC"){echo "selected";}?>>ASC</option>
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
            <!-- BUTTON MODAL INSERT --> 
            <button type="button" class="btn btn btn-outline-success ms-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="float:right">
              Tambah Data
            </button>
          </div>
        </nav>

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
        
        <!-- TABEL TEKNISI -->
        <p class="mt-3 mb-0 text-secondary">Total Data : <?php echo $total_row; ?></p>
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
                <th></th>
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
                <td><a class="btn btn-outline-secondary mt-3" href="#edit<?= $tk["id_teknisi"];?>" data-bs-toggle="modal" data-bs-target="#edit<?= $tk["id_teknisi"];?>"><img src="../pic/edit.svg" alt=""></a></td>
                <!-- <td><a class="btn btn-outline-secondary mt-3" href="hapus.php?id=<?= $tk["id_teknisi"];?>"><img src="../pic/trash.svg" alt=""></a></td> -->
                <td><a class="btn btn-outline-secondary mt-3" href="#hapus<?= $tk["id_teknisi"];?>" data-bs-toggle="modal" data-bs-target="#hapus<?= $tk["id_teknisi"];?>"><img src="../pic/trash.svg" alt=""></a></td>
                <!-- MODAL EDIT -->
                <div class="modal fade" id="edit<?= $tk["id_teknisi"];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Data Teknisi</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="" method="post">
                              <input type="hidden" class="form-control" id="id_teknisi" name="id_teknisi" value="<?= $tk["id_teknisi"];?>"/>

                              <label for="nama" class="form-label">Nama</label>
                              <input type="text" class="form-control" id="nama" name="nama" placeholder="<?= $tk["nama"];?>" value ="<?= $tk["nama"];?>"/>
                            
                              <label for="ttl" class="form-label">Tanggal Lahir</label>
                              <input type="date" class="form-control" id="ttl" name="ttl" placeholder="<?= $tk["tgl_lahir"];?>" value ="<?= $tk["tgl_lahir"];?>"/>
                            
                              <label for="telp" class="form-label">Nomor Telepon</label>
                              <input type="text" class="form-control" id="telp" name="telp" placeholder="<?= $tk["no_telp"];?>" value ="<?= $tk["no_telp"];?>"/>
                            
                              <label for="alamat" class="form-label">Alamat</label>
                              <input type="text" class="form-control" id="alamat" name="alamat" placeholder="<?= $tk["alamat"];?>" value ="<?= $tk["alamat"];?>"/>

                              <button type="submit" class="btn btn-secondary mt-3" style="float:right" name="submitedit" >Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- MODAL HAPUS -->
                <div class="modal fade" id="hapus<?= $tk["id_teknisi"];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Yakin untuk menghapus data?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      <form action="" method="post">
                        <input type="hidden" class="form-control" id="id_teknisi" name="id_teknisi" value="<?= $tk["id_teknisi"];?>"/>
                        Data yang dihapus tidak bisa dikembalikan
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                          <button type="submit" class="btn btn-primary" name="submithapus">Hapus</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

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

        <!-- Button trigger modal -->
        <!-- <div class="col-md-7">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="float:right">
            Tambah Data
          </button>
        </div>   -->

        <!-- SEARCH
        <div class="" style="width:200px;">
          <div class="input-group">
            <div class="form-outline" action="" method="post">
              <input type="search" id="form1" class="form-control" placeholder="Search..." name="search" />
            </div>
          </div>            
        </div> -->

        <!-- SEARCH BAR -->
        <!-- <div style="width:200px;">
          <div class="input-group">
            <form action="" method="get">
              <input type="search" id="form1" class="form-control" placeholder="Cari..." name="search" />
            </form>
          </div>            
        </div> -->

        <!-- SORT -->
        <!-- <form action="" method="get">
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
        </form> -->