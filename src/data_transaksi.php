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
$pelanggan = query("SELECT id_pelanggan, no_telp, nama FROM pelanggan ORDER BY nama ASC"); 
$device = query("SELECT id_device, nama FROM device ORDER BY nama ASC"); 
$layanan = query("SELECT id_layanan, keluhan FROM layanan ORDER BY keluhan ASC"); 
$sparepart = query("SELECT * FROM sparepart ORDER BY nama ASC"); 
$no_sparepart = query("SELECT id_sparepart FROM sparepart WHERE nama = 'Tidak Ada'"); 
$teknisi = query("SELECT id_teknisi, nama FROM teknisi ORDER BY nama ASC"); 

function getDataDetailTransaksi($id_transaksi) {

return query("SELECT dv.id_device, dv.nama AS nama_dv, ly.id_layanan, ly.tipe_layanan, ly.keluhan, spr.id_sparepart, spr.nama AS nama_spr, dt.jumlah_sparepart, dt.id_transaksi, dt.id_detail_transaksi
                              FROM detail_transaksi dt 
                              JOIN transaksi tr USING (id_transaksi)
                              JOIN device dv USING (id_device) 
                              JOIN layanan ly USING (id_layanan)
                              JOIN sparepart spr USING (id_sparepart)
                              WHERE dt.id_transaksi='$id_transaksi'");
}

$total_layanan = mysqli_query($conn, "SELECT * FROM transaksi");
$total_row = mysqli_num_rows($total_layanan);

$field = "tr.id_transaksi";
  if(isset($_GET['sort'])){
    $field = $_GET['sort'];
  }

if(isset($_GET["search"])){
  $search = $_GET["search"];
  $data_transaksi = query("SELECT tr.id_transaksi, tr.tgl_transaksi, tr.total_biaya, tr.status, t.id_teknisi, t.nama as nama_tk, p.id_pelanggan, p.nama as nama_p, p.no_telp
                          FROM transaksi tr JOIN pelanggan p USING (id_pelanggan) JOIN teknisi t USING (id_teknisi) 
                          WHERE tr.id_transaksi LIKE '%$search%' 
                          OR tr.tgl_transaksi LIKE '%$search%'
                          OR t.nama LIKE '%$search%'
                          OR p.nama LIKE '%$search%' 
                          ORDER BY $field ASC");
} else{
  $data_transaksi = query("SELECT tr.id_transaksi, tr.tgl_transaksi, tr.total_biaya, tr.status, t.id_teknisi, t.nama as nama_tk, p.id_pelanggan, p.nama as nama_p, p.no_telp
                     FROM transaksi tr JOIN pelanggan p USING (id_pelanggan) JOIN teknisi t USING (id_teknisi) 
                     ORDER BY $field ASC "); 
}

if(isset($_POST["reset"])){
  $reset = $_POST["reset"];
  $data_transaksi = query("SELECT tr.id_transaksi, tr.tgl_transaksi, tr.total_biaya, tr.status, t.id_teknisi, t.nama as nama_tk, p.id_pelanggan, p.nama as nama_p, p.no_telp
                          FROM transaksi tr JOIN pelanggan p USING (id_pelanggan) JOIN teknisi t USING (id_teknisi) 
                          ORDER BY tr.id_transaksi ASC "); 
}

function getDataEditDetailTransaksi($id_transaksi) {

  return query("SELECT dv.id_device, dv.nama AS nama_dv, ly.id_layanan, ly.tipe_layanan, ly.keluhan, spr.id_sparepart, spr.nama AS nama_spr, dt.jumlah_sparepart, dt.id_transaksi, dt.id_detail_transaksi
                                FROM detail_transaksi dt 
                                JOIN transaksi tr USING (id_transaksi)
                                JOIN device dv USING (id_device) 
                                JOIN layanan ly USING (id_layanan)
                                JOIN sparepart spr USING (id_sparepart)
                                WHERE dt.id_transaksi='$id_transaksi'");
  }

function getIDDetailTrasaksi($id_detail_transaksi){
  return query("SELECT * FROM detail_transaksi");
}

if(isset($_POST["submit"])){
  if(tambah_transaksi($_POST) > 0){
    header('refresh:0; url=data_transaksi.php');
    echo "<script>alert('data berhasil ditambahkan')</script>";
  } else{
    echo "<script>alert('data gagal ditambahkan')</script>";
  }
}

if(isset($_POST["submitedit"])){
  if(edit_transaksi($_POST) > 0){
    header('refresh:0; url=data_transaksi.php');
    echo "<script>alert('data berhasil diedit')</script>";
  } else{
    echo "<script>alert('data gagal diedit')</script>";
  }
}

if(isset($_POST["submit_detail_transaksi"])){
  if(tambah_detail_transaksi($_POST) > 0){
    header('refresh:0; url=data_transaksi.php');
    echo "<script>alert('detail berhasil ditambah')</script>";
  } else{
    echo "<script>alert('detail gagal ditambah')</script>";
  }
}

if(isset($_POST["submithapus"])){
  if(hapus_transaksi($_POST) > 0){
    header('refresh:0; url=data_transaksi.php');
    echo "<script>alert('data berhasil dihapus')</script>";
  } else{
    echo "<script>alert('data gagal dihapus')</script>";
  }
}

if(isset($_POST["submiteditdetail"])){
  if(edit_detail_transaksi($_POST) > 0){
    header('refresh:0; url=data_transaksi.php');
    echo "<script>alert('detail berhasil diedit')</script>";
  } else{
    echo "<script>alert('detail gagal diedit')</script>";
  }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css"> -->

    <!-- Latest compiled and minified JavaScript -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script> -->

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script> -->
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
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Transaksi</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="" method="post">
                  <label for="inputDate" class="col-1 col-form-label text-wrap" style="width: 10rem">Waktu</label>
                  <input type="text" class="form-control" id="tgl_transaksi" name="tgl_transaksi"  value="<?php echo date('Y-m-d H:i:s')?>"/>

                  <label for="inputPelanggan" class="col-1 col-form-label text-wrap" style="width: 10rem">Nama Pelanggan</label>                   
                  <select class="selectpicker form-control" data-live-search="true" name="id_pelanggan" id="floatingSelect" aria-label="Floating label select example" >
                    <option selected disabled>-</option>
                    <?php foreach($pelanggan as $pl) : ?>
                    <option value="<?= $pl["id_pelanggan"];?>"><?= $pl["no_telp"];?> | <?= $pl["nama"];?></option>
                    <?php endforeach; ?>
                  </select>                                
                  
                  <label for="inputTeknisi" class="col-1 col-form-label text-wrap" style="width: 10rem">Teknisi</label>                   
                  <select class="selectpicker form-control" data-live-search="true" name="id_teknisi" id="floatingSelect" aria-label="Floating label select example">
                    <option selected>-</option>
                    <?php foreach($teknisi as $tk) : ?>
                    <option value="<?= $tk["id_teknisi"];?>"><?= $tk["id_teknisi"];?> | <?= $tk["nama"];?></option>
                    <?php endforeach; ?>
                  </select>
                            
                  <button type="submit" class="btn btn-secondary mt-3" style="float:right" name="submit" >Submit</button>
                </form>
              </div>
              <div class="modal-footer">
              </div>
            </div>
          </div>
        </div>  

        <!-- TABEL DATA TRANSAKSI -->
        <p class="mt-3 mb-0 text-secondary">Total Data : <?php echo $total_row; ?></p>
        <div class="col-12 col-md-12 my-3">
          <table class="table border">
            <thead class="text-center">
              <tr>
                <th class="text-secondary">NO</th>
                <th class="text-secondary">ID TRANSAKSI</th>
                <th class="text-secondary">TANGGAL TRANSAKSI</th>                
                <th class="text-secondary">NAMA TEKNISI</th>
                <th class="text-secondary">NAMA PELANGGAN</th>
                <th class="text-secondary">TOTAL BIAYA</th>
                <th class="text-secondary">STATUS</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              <?php foreach($data_transaksi as $tr) : ?>
              <tr>
                <td class="text-center mb-0"><?= $no; ?></td>
                <td class="mb-0"><?= $tr["id_transaksi"];?></td>
                <td class="mb-0"><?= $tr["tgl_transaksi"];?></td>
                <td class="mb-0"><?= $tr["nama_tk"];?></td>
                <td class="mb-0"><?= $tr["nama_p"];?></td>
                <td class="mb-0"><?= $tr["total_biaya"];?></td>
                <td class="mb-0"><?= $tr["status"];?></td>
                <td><a class="btn btn-outline-secondary mt-3" href="#edit<?= $tr["id_transaksi"];?>" data-bs-toggle="modal" data-bs-target="#edit<?= $tr["id_transaksi"];?>"><img src="../pic/edit.svg" alt=""></a></td>
                <td><a class="btn btn-outline-secondary mt-3" href="#hapus<?= $tr["id_transaksi"];?>" data-bs-toggle="modal" data-bs-target="#hapus<?= $tr["id_transaksi"];?>"><img src="../pic/trash.svg" alt=""></a></td>
                <td><a class="btn btn-outline-secondary mt-3" href="" ><img src="../pic/printer.svg" alt=""></a></td>
                
                 <!-- MODAL EDIT TRANSAKSI -->
                 <div class="modal fade" id="edit<?= $tr["id_transaksi"];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Data Transaksi</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="" method="post">
                              <input type="hidden" class="form-control" id="id_transaksi" name="id_transaksi" value="<?= $tr["id_transaksi"];?>"/>

                              <label for="tgl_transaksi" class="form-label">Tanggal Transaksi</label>
                              <input type="text" class="form-control" id="tgl_transaksi" name="tgl_transaksi" placeholder="<?= $tr["tgl_transaksi"];?>" value ="<?= $tr["tgl_transaksi"];?>" disabled/>
                            
                              <label for="id_teknisi" class="form-label">Nama Teknisi</label>
                              <select class="form-select" name="id_teknisi" id="id_teknisi" aria-label="Floating label select example">
                                <option value ="<?= $tr["id_teknisi"]?>" selected><?= $tr["id_teknisi"];?> | <?= $tr["nama_tk"];?></option>
                                <?php foreach($teknisi as $tk) : ?>
                                <option value="<?= $tk["id_teknisi"]?>"><?= $tk["id_teknisi"];?> | <?= $tk["nama"];?></option>
                                <?php endforeach; ?>
                              </select>

                              <label for="id_pelanggan" class="form-label">Nama Pelanggan</label>
                              <select class="form-select" name="id_pelanggan" id="id_pelanggan" aria-label="Floating label select example">
                                <option value ="<?= $tr["id_pelanggan"];?>" selected><?= $tr["no_telp"];?> | <?= $tr["nama_p"];?></option>
                                <?php foreach($pelanggan as $pl) : ?>
                                <option value="<?= $pl["id_pelanggan"];?>"><?= $pl["no_telp"];?> | <?= $pl["nama"];?></option>
                                <?php endforeach; ?>
                              </select>

                              <label for="total_biaya" class="form-label">Total Biaya</label>
                              <input disabled type="text" class="form-select" id="total_biaya" name="total_biaya" placeholder="<?= $tr["total_biaya"];?>" value ="<?= $tr["total_biaya"];?>"/>

                              <label for="status" class="form-label">Status</label>
                              <!-- <input type="text" class="form-control" id="status" name="status" placeholder="<?= $tr["status"];?>" value ="<?= $tr["status"];?>"/> -->
                              <select class="form-select" name="status" id="status" aria-label="Floating label select example"  >
                                <option value ="<?= $tr["status"];?>" selected><?= $tr["status"];?></option>                                
                                <option value="0">Pending</option>                                
                                <option value="1">Selesai</option>                                
                              </select>

                              <button type="submit" class="btn btn-secondary mt-3" style="float:right" name="submitedit" >Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer">
                      </div>
                    </div>
                  </div>
                 </div>

                <!-- MODAL HAPUS TRANSAKSI-->
                <div class="modal fade" id="hapus<?= $tr["id_transaksi"];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Yakin untuk menghapus data?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      <form action="" method="post">
                        <input type="hidden" class="form-control" id="id_transaksi" name="id_transaksi" value="<?= $tr["id_transaksi"];?>"/>
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

                <!-- BAGIAN DETAIL TRANSAKSI -->
                <td><a class="btn btn-outline-secondary mt-3" href="#detail<?= $tr["id_transaksi"];?>" data-bs-toggle="modal" data-bs-target="#detail<?= $tr["id_transaksi"];?>">Detail</a></td>
                <td><a class="btn btn-outline-secondary mt-3" href="#tambah_detail<?= $tr["id_transaksi"];?>" data-bs-toggle="modal" data-bs-target="#tambah_detail<?= $tr["id_transaksi"];?>">Tambah Detail</a></td>

                <!-- MODAL SHOW DETAIL -->
                <div class="modal fade" id="detail<?= $tr["id_transaksi"];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Detail Data Transaksi</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <?php foreach(getDataDetailTransaksi($tr["id_transaksi"]) as $dt) : ?>
                        <li>id : <?= $dt["id_detail_transaksi"];?></li>
                        <?php $id_dt = $dt["id_detail_transaksi"]; ?>
                        <li>Device : <?= $dt["nama_dv"];?></li>
                        <li>Tipe Layanan : <?= $dt["tipe_layanan"];?></li>
                        <li>Keluhan : <?= $dt["keluhan"];?></li>
                        <li>Sparepart : <?= $dt["nama_spr"];?></li>
                        <li>Jumlah Sparepart : <?= $dt["jumlah_sparepart"];?></li>
                        <a class="btn btn-outline-secondary mt-3" href="#edit_detail<?= $id_dt;?>" data-bs-toggle="modal" data-bs-target="#edit_detail<?= $id_dt;?>">Edit Detail</a>
                        <p></p>
                        
                          <!-- MODAL EDIT DETAIL TRANSAKSI -->
                          <div class="modal fade" id="edit_detail<?= $id_dt;?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Detail Data Transaksi</h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  
                                </div>
                                <div class="modal-footer">
                                </div>
                              </div>
                            </div>
                          </div>


                        <?php endforeach; ?>
                      </div>
                      <div class="modal-footer">
                      <a class="btn btn-outline-secondary mt-3" href="#tambah_detail<?= $dt["id_detail_transaksi"];?>" data-bs-toggle="modal" data-bs-target="#tambah_detail<?= $dt["id_detail_transaksi"];?>">Edit Detail</a>                                      
                        <a class="btn btn-outline-secondary mt-3" href="#tambah_detail<?= $tr["id_transaksi"];?>" data-bs-toggle="modal" data-bs-target="#tambah_detail<?= $tr["id_transaksi"];?>">Tambah Detail</a>                                      
                        <a class="btn btn-outline-secondary mt-3" href="#edit_detail<?= $tr["id_transaksi"];?>" data-bs-toggle="modal" data-bs-target="#edit_detail<?= $tr["id_transaksi"];?>">Edit Detail</a>                                      
                      </div>
                    </div>
                  </div>
                </div>



                <!-- MODAL EDIT DETAIL TRANSAKSI -->
                <div class="modal fade" id="edit_detail<?= $tr["id_transaksi"];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Detail Data Transaksi</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="" method="post">
                        <?php foreach(getDataDetailTransaksi($tr["id_transaksi"]) as $dt) : ?>
                              <input type="text" class="form-control" id="id_detail_transaksi" name="id_detail_transaksi" value="<?= $dt["id_detail_transaksi"];?>"/>

                              <label for="id_device" class="form-label">Device</label>
                              <select class="form-select" name="id_device" id="id_device" aria-label="Floating label select example">
                                <option value ="<?= $dt["id_device"]?>" selected><?= $dt["nama_dv"];?></option>
                                <?php foreach($device as $dv) : ?>
                                <option value="<?= $dv["id_device"]?>"><?= $dv["nama"];?></option>
                                <?php endforeach; ?>
                              </select>
                            
                              <label for="id_layanan" class="form-label">Layanan</label>
                              <select class="form-select" name="id_layanan" id="id_layanan" aria-label="Floating label select example">
                                <option value ="<?= $dt["id_layanan"]?>" selected><?= $dt["keluhan"];?></option>
                                <?php foreach($layanan as $ly) : ?>
                                <option value="<?= $ly["id_layanan"]?>"><?= $ly["keluhan"];?></option>
                                <?php endforeach; ?>
                              </select>

                              <label for="id_sparepart" class="form-label">Sparepart</label>                              
                              <select class="form-select" name="id_sparepart" id="id_sparepart" aria-label="Floating label select example"  >
                                <option value ="<?= $dt["id_sparepart"];?>" selected><?= $dt["nama_spr"];?></option>                                
                                <?php foreach($sparepart as $spr) : ?>
                                <option value="<?= $spr["id_sparepart"]?>"><?= $spr["nama"];?></option>
                                <?php endforeach; ?>                                
                              </select>

                              <label for="jumlah_sparepart" class="form-label">Jumlah Sparepart</label>
                              <input type="text" class="form-select" id="jumlah_sparepart" name="jumlah_sparepart" placeholder="<?= $dt["jumlah_sparepart"];?>" value ="<?= $dt["jumlah_sparepart"];?>"/>

                              <br>
                        <?php endforeach; ?>
                        <button type="submit" class="btn btn-secondary mt-3" style="float:right" name="submiteditdetail" >Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- MODAL TAMBAH DETAIL -->
                <div class="modal fade" id="tambah_detail<?= $tr["id_transaksi"];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Detail</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="" method="post">
                          <input type="hidden" class="form-control" id="id_transaksi" name="id_transaksi" value="<?= $tr["id_transaksi"]?>"/>

                          <label for="id_device" class="form-label">device</label>
                          <select class="form-control" data-live-search="true" name="id_device" id="floatingSelect" aria-label="Floating label select example">
                            <option selected>-</option>
                            <?php foreach($device as $dv) : ?>
                            <option value="<?= $dv["id_device"];?>"><?= $dv["nama"];?></option>
                            <?php endforeach; ?>
                          </select>
                            
                          <label for="id_layanan" class="form-label">Keluhan</label>
                          <!-- <input type="text" class="form-control" id="id_layanan" name="id_layanan"/> -->
                          <select class="form-control" data-live-search="true" name="id_layanan" id="id_layanan" aria-label="Floating label select example">
                            <option selected disabled>-</option>
                            <?php foreach($layanan as $kl) : ?>
                            <option value="<?= $kl["id_layanan"];?>"><?= $kl["keluhan"];?></option>
                            <?php endforeach; ?>
                          </select>
                            
                          <label for="id_sparepart" class="form-label">Sparepart</label>
                          <select class="form-control" data-live-search="true" name="id_sparepart" id="floatingSelect" aria-label="Floating label select example">
                            
                            <option value="<?= $no_sparepart[0]["id_sparepart"];?>" selected>-</option>
                            <?php foreach($sparepart as $spr) : ?>
                            <option value="<?= $spr["id_sparepart"];?>"><?= $spr["nama"];?> | <?= $spr["tipe"];?></option>
                            <?php endforeach; ?>
                          </select> 
                          <!-- <input type="text" class="form-control" id="id_sparepart" name="id_sparepart" /> -->

                          <label for="jml_sparepart" class="form-label">Jumlah Sparepart</label>
                          <input type="text" class="form-control" id="jml_sparepart" name="jml_sparepart" value="0"/>

                          <!-- <input type="hidden" class="form-control" id="id_transaksi" name="id_transaksi" value="<?= $tr["id_transaksi"]?>"/> -->
                          <button type="submit" class="btn btn-secondary mt-3" style="float:right" name="submit_detail_transaksi" >Submit</button>
                        </form>
                      </div>
                      <div class="modal-footer">                        
                      </div>
                    </div>
                  </div>
                </div>        

              </tr>
              <?php $no++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>

          <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Data Transaksi</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="" method="post">
                  <label for="inputDate" class="col-1 col-form-label text-wrap" style="width: 10rem">Waktu</label>
                  <input type="text" class="form-control" id="tgl_transaksi" name="tgl_transaksi" disabled value="<?php echo date('Y-m-d H:i:s')?>"/>

                  <label for="inputPelanggan" class="col-1 col-form-label text-wrap" style="width: 10rem">Nama Pelanggan</label>                   
                  <select class="selectpicker form-control" data-live-search="true" name="id_pelanggan" id="floatingSelect" aria-label="Floating label select example" >
                    <option selected disabled>-</option>
                    <?php foreach($pelanggan as $pl) : ?>
                    <option value="<?= $pl["id_pelanggan"];?>"><?= $pl["no_telp"];?> | <?= $pl["nama"];?></option>
                    <?php endforeach; ?>
                  </select>                                
                  
                  <label for="inputTeknisi" class="col-1 col-form-label text-wrap" style="width: 10rem">Teknisi</label>                   
                  <select class="selectpicker form-control" data-live-search="true" name="id_teknisi" id="floatingSelect" aria-label="Floating label select example">
                    <option selected>-</option>
                    <?php foreach($teknisi as $tk) : ?>
                    <option value="<?= $tk["id_teknisi"];?>"><?= $tk["id_teknisi"];?> | <?= $tk["nama"];?></option>
                    <?php endforeach; ?>
                  </select>
                            
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
  </body>
</html>