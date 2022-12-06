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
// $sparepart = query("SELECT * FROM sparepart ORDER BY $field ASC "); 
if(isset($_GET["search"])){
  $search = $_GET["search"];
  $sparepart = query("SELECT * FROM sparepart 
                      WHERE nama LIKE '%$search%' 
                      OR tipe LIKE '%$search%'
                      OR stok LIKE '%$search%'
                      OR harga LIKE '%$search%' 
                      ORDER BY $field ASC");
} else{
  $sparepart = query("SELECT * FROM sparepart ORDER BY $field ASC "); 
}

if(isset($_POST["reset"])){
  $reset = $_POST["reset"];
  $sparepart = query("SELECT * FROM sparepart ORDER BY nama ASC");
}

if(isset($_POST["submit"])){
  if(tambah_sparepart($_POST) > 0){
    header('refresh:0; url=data_produk.php');
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
    <title>Data Produk</title>
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
              <li class="breadcrumb-item text-sm text-dark active">Data Produk</li>
            </ol>
          </nav>
        </div>
      </nav>
      <div class="mx-2 px-1">
        <div>
          <h3>Data Produk</h3>
          <p>Daftar produk sparepart yang tersedia</p>
        </div>
        <!-- BUTTON MODAL INSERT -->
        <div class="col-md-7">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="float:right">
            Tambah Data
          </button>
        </div>  
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
                      <input type="text" class="form-control" id="tipe" name="tipe" />
                    
                      <label for="stok" class="form-label">Stok</label>
                      <input type="text" class="form-control" id="stok" name="stok" />
                    
                      <label for="harga" class="form-label">Harga</label>
                      <input type="text" class="form-control" id="harga" name="harga" />

                      
                      <button type="submit" class="btn btn-secondary mt-3" style="float:right" name="submit" >Submit</button>
                </form>
              </div>
              <div class="modal-footer">
              </div>
            </div>
          </div>
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
                <option value="nama" <?php if(isset($_GET['sort']) && $_GET['sort'] == "nama"){echo "selected";}?>>nama</option>
                <option value="tipe" <?php if(isset($_GET['sort']) && $_GET['sort'] == "tipe"){echo "selected";}?>>tipe</option>
                <option value="stok" <?php if(isset($_GET['sort']) && $_GET['sort'] == "stok"){echo "selected";}?>>stok</option>
                <option value="harga" <?php if(isset($_GET['sort']) && $_GET['sort'] == "harga"){echo "selected";}?>>harga</option>
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
        

        <!-- TABEL DATA -->
        <!-- <div class="col-9 col-sm-8 col-md-7 my-3"> -->
        <div class="col-12 my-3">
          <table class="table border">
            <thead class="text-center">
              <tr>
                <th class="text-secondary">NO</th>
                <th class="text-secondary">NAMA</th>
                <th class="text-secondary">TIPE</th>
                <th class="text-secondary">STOK</th>
                <th class="text-secondary">HARGA</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              <?php foreach($sparepart as $spr) : ?>
              <tr>
                <td class="text-center mb-0"><?= $no; ?></td>
                <td class="mb-0"><?= $spr["nama"];?></td>
                <td class="mb-0"><?= $spr["tipe"];?></td>
                <td class="text-center mb-0"><?= $spr["stok"];?></td>
                <td class="text-center mb-0"><?= $spr["harga"];?></td>
                <td>
                <!-- <a href="data_produk.php?id=<?= $spr["id_sparepart"];?>">Edit</a> -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit<?php echo $spr["id_sparepart"];?>"> Edit </button>
                </td>
                <!-- <td>
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <a href="data_produk.php?id=<?= $spr["id_sparepart"];?>">ubah</a>  
                </button>
                <ul class="dropdown-menu">
                  <li><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit">Edit</button></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                </ul>
                </td> -->
                <!-- <td class="align-middle">
                  <div class="col-md-7">
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit">
                    <a href="data_produk.php?id=<?= $spr["id_sparepart"];?>">Edit</a>
                  </button>
                </td> -->
                <!-- <td><a href="hapus.php?id=<?= $spr["id_sparepart"];?>">delete</a></td> -->
              </tr>
              <?php $no++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
          </div>  
          <!-- Modal EDIT TABEL-->
          <div class="modal fade" id="edit<?php echo $spr["id_sparepart"];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Data Sparepart</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="" method="get">
                    <?php
                    $id = $spr["id_sparepart"];
                    $update = query("SELECT * FROM sparepart WHERE id_sparepart = '$id'"); 
                    ?>
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="<?= $nama?>"/>
                      
                        <label for="tipe" class="form-label">Tipe</label>
                        <input type="text" class="form-control" id="tipe" name="tipe" />
                      
                        <label for="stok" class="form-label">Stok</label>
                        <input type="text" class="form-control" id="stok" name="stok" />
                      
                        <label for="harga" class="form-label">Harga</label>
                        <input type="text" class="form-control" id="harga" name="harga" />

                        <button type="submit" class="btn btn-secondary mt-3" style="float:right" name="submit" >Submit</button>
                  </form>
                </div>
                <div class="modal-footer">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
  </body>
</html>

<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
  Launch static backdrop modal
</button> -->

<!-- Modal -->
<!-- <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div> -->