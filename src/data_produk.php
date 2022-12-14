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

$device = query("SELECT id_device, nama FROM device ORDER BY nama ASC"); 
$total_layanan = mysqli_query($conn, "SELECT * FROM sparepart");
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
  $sparepart = query("SELECT * FROM sparepart ORDER BY nama ASC LIMIT 10");
}

if(isset($_POST["submitsearch"])){
  $search = $_POST["search"];
  $sparepart = query("SELECT * FROM sparepart 
                      WHERE nama LIKE '%$search%' 
                      OR tipe LIKE '%$search%'
                      OR stok LIKE '%$search%'
                      OR harga LIKE '%$search%' 
                      OR laba LIKE '%$search%' 
                      ORDER BY $field $flow LIMIT $max");
} else{
  $sparepart = query("SELECT * FROM sparepart ORDER BY $field $flow LIMIT $max"); 
}

if(isset($_POST["submit"])){
  if(tambah_sparepart($_POST) > 0){
    header('refresh:0; url=data_produk.php');
    echo "<script>alert('data berhasil ditambahkan')</script>";
  } else{
    echo "<script>alert('data gagal ditambahkan')</script>";
  }
}

if(isset($_POST["submitedit"])){
  if(edit_sparepart($_POST) > 0){
    header('refresh:0; url=data_produk.php');
    echo "<script>alert('data berhasil diedit')</script>";
  } else{
    echo "<script>alert('data gagal diedit')</script>";
  }
}

if(isset($_POST["submithapus"])){
  if(hapus_sparepart($_POST) > 0){
    header('refresh:0; url=data_produk.php');
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
    <title>Data Produk</title>
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
          <a href="data_transaksi.php" class="list-group-item list-group-item-action">Data Transaksi</a>
          <a href="data_layanan.php" class="list-group-item list-group-item-action">Data Layanan</a>
          <a href="data_produk.php" class="list-group-item list-group-item-action">Data Sparepart</a>
          <a href="data_device.php" class="list-group-item list-group-item-action">Data Device</a>
          <a href="data_pelanggan.php" class="list-group-item list-group-item-action">Data Pelanggan</a>
          <a href="data_teknisi.php" class="list-group-item list-group-item-action">Data Teknisi</a>
        </div>
      </div>
      <div class="position-absolute bottom-0 p-5 text-second text-center">
        <div>
        <button class="btn text-secondary" type="submit" name="logout"><img class="me-1" src="../pic/logout.svg" alt=""> Logout</button>
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
              <li class="breadcrumb-item text-sm text-dark active">Data Sparepart</li>
            </ol>
          </nav>
        </div>
      </nav>
      <div class="mx-2 px-1">
        <div>
          <h3>Data Sparepart</h3>
          <p>Daftar sparepart yang tersedia</p>
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
                <option value="tipe" <?php if(isset($_GET['sort']) && $_GET['sort'] == "tipe"){echo "selected";}?>>tipe</option>
                <option value="stok" <?php if(isset($_GET['sort']) && $_GET['sort'] == "stok"){echo "selected";}?>>stok</option>
                <option value="harga" <?php if(isset($_GET['sort']) && $_GET['sort'] == "harga"){echo "selected";}?>>harga</option>
                <option value="laba" <?php if(isset($_GET['sort']) && $_GET['sort'] == "laba"){echo "selected";}?>>laba</option>
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

        <!-- MODAL INSERT -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Sparepart</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="" method="post">
                      <label for="nama" class="form-label">Nama</label>
                      <input type="text" class="form-control" id="nama" name="nama" />
                    
                      <label for="tipe" class="form-label">Tipe</label>
                      <!-- <input type="text" class="form-control" id="tipe" name="tipe" /> -->
                      <select class="selectpicker form-control" data-live-search="true" name="tipe" id="floatingSelect" aria-label="Floating label select example">
                        <option selected disabled>-</option>
                        <?php foreach($device as $dc) : ?>
                        <option value="<?= $dc["nama"];?>"><?= $dc["nama"];?></option>
                        <?php endforeach; ?>
                      </select>
                    
                      <label for="stok" class="form-label">Stok</label>
                      <input type="text" class="form-control" id="stok" name="stok" />
                    
                      <label for="harga" class="form-label">Harga</label>
                      <input type="text" class="form-control" id="harga" name="harga" />

                      <label for="laba" class="form-label">Laba(%)</label>
                      <input type="text" class="form-control" id="laba" name="laba" />
                      
                      <button type="submit" class="btn btn-secondary mt-3" style="float:right" name="submit" >Submit</button>
                </form>
              </div>
              <div class="modal-footer">
              </div>
            </div>
          </div>
        </div>      

        <!-- TABEL DATA -->
        <p class="mt-3 mb-0 text-secondary">Total Data : <?php echo $total_row; ?></p>
        <!-- <div class="col-9 col-sm-8 col-md-7 my-3"> -->
        <div class="col-12 my-3">
          <table class="table border">
            <thead class="text-center">
              <tr>
                <th class="text-secondary">NO</th>
                <th class="text-secondary">ID</th>
                <th class="text-secondary">NAMA</th>
                <th class="text-secondary">TIPE</th>
                <th class="text-secondary">STOK</th>
                <th class="text-secondary">HARGA</th>
                <th class="text-secondary">LABA(%)</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              <?php foreach($sparepart as $spr) : ?>
                
              <tr>
                <td class="text-center mb-0"><?= $no; ?></td>
                <td class="text-center mb-0"><?= $spr["id_sparepart"]; ?></td>
                <td class="mb-0"><?= $spr["nama"];?></td>
                <td class="mb-0"><?= $spr["tipe"];?></td>
                <td class="text-center mb-0"><?= $spr["stok"];?></td>
                <td class="text-center mb-0"><?= $spr["harga"];?></td>            
                <td class="text-center mb-0"><?= $spr["laba"];?></td>            
                <td><a class="btn btn-outline-secondary mt-3" href="#edit<?= $spr["id_sparepart"];?>" data-bs-toggle="modal" data-bs-target="#edit<?= $spr["id_sparepart"];?>"><img src="../pic/edit.svg" alt=""></a></td>
                
                <!-- Modal EDIT TABEL-->
                <div class="modal fade" id="edit<?= $spr["id_sparepart"];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Data Sparepart</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="" method="post">
                          <?php
                          $id = $spr["id_sparepart"];
                          $update = query("SELECT * FROM sparepart WHERE id_sparepart = '$id'"); 
                          ?>
                              <input type="hidden" class="form-control" id="id_sparepart" name="id_sparepart" value="<?= $id?>"/>

                              <label for="nama" class="form-label">Nama</label>
                              <input type="text" class="form-control" id="nama" name="nama" value="<?= $spr["nama"]?>"/>
                            
                              <label for="tipe" class="form-label">Tipe</label>
                              <!-- <input type="text" class="form-control" id="tipe" name="tipe" value="<?= $spr["tipe"]?>" /> -->
                              <select class="selectpicker form-control" data-live-search="true" name="tipe" id="floatingSelect" aria-label="Floating label select example">
                                <option value="<?= $spr["tipe"]?>" selected disabled><?= $spr["tipe"]?></option>
                                <?php foreach($device as $dc) : ?>
                                <option value="<?= $dc["nama"];?>"><?= $dc["nama"];?></option>
                                <?php endforeach; ?>
                              </select>

                              <label for="stok" class="form-label">Stok</label>
                              <input type="text" class="form-control" id="stok" name="stok" value="<?= $spr["stok"]?>"/>
                            
                              <label for="harga" class="form-label">Harga</label>
                              <input type="text" class="form-control" id="harga" name="harga" value="<?= $spr["harga"]?>"/>

                              <label for="laba" class="form-label">Laba(%)</label>
                              <input type="text" class="form-control" id="laba" name="laba" value="<?= $spr["laba"]?>"/>

                              <button type="submit" class="btn btn-secondary mt-3" style="float:right" name="submitedit" >Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer">
                      </div>
                    </div>
                  </div>
                </div>
                
                <td><a class="btn btn-outline-secondary mt-3" href="#hapus<?= $spr["id_sparepart"];?>" data-bs-toggle="modal" data-bs-target="#hapus<?= $spr["id_sparepart"];?>"><img src="../pic/trash.svg" alt=""></a></td>
                <!-- MODAL HAPUS -->
                <div class="modal fade" id="hapus<?= $spr["id_sparepart"];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Yakin untuk menghapus data?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      <form action="" method="post">
                        <input type="hidden" class="form-control" id="id_sparepart" name="id_sparepart" value="<?= $spr["id_sparepart"];?>"/>
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
    </div>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
  </body>
</html>