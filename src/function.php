<?php
$conn = mysqli_connect("localhost", "root", "", "planet_servis");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function query($query){
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

function hapus($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM sparepart WHERE id_sparepart = $id");
    return mysqli_affected_rows($conn);
}

// FUNCTION TRANSAKSI
function tambah_transaksi($data){
    global $conn;
    $tgl_transaksi = $data["tgl_transaksi"];
    $id_teknisi = $data["id_teknisi"];
    $id_pelanggan = $data["id_pelanggan"];

    $query = "INSERT INTO transaksi
                VALUES
                ('','$tgl_transaksi','$id_teknisi', '$id_pelanggan', '','')";
    
    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}

function edit_transaksi($data){
    global $conn;

    $id_transaksi = $data["id_transaksi"];
    $id_teknisi = $data["id_teknisi"];
    $id_pelanggan = $data["id_pelanggan"];
    $total_biaya = $data["total_biaya"];
    $status = $data["status"];

    $query = "UPDATE transaksi
                SET
                id_teknisi='$id_teknisi',id_pelanggan='$id_pelanggan',status='$status'
                WHERE id_transaksi = '$id_transaksi'";

    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}

function hapus_transaksi($data){
    global $conn;
    $id_transaksi = $data["id_transaksi"];
    mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi = $id_transaksi");
    return mysqli_affected_rows($conn);
}

function getTotalBiaya($id_transaksi, $id_sparepart, $id_layanan){
    return query("SELECT (ly.biaya+(spr.harga*dt.jumlah_sparepart)) AS tb
    FROM detail_transaksi dt 
    JOIN sparepart spr  
    JOIN layanan ly
    WHERE dt.id_transaksi= '$id_transaksi'
    AND ly.id_layanan='$id_layanan' 
    AND spr.id_sparepart = '$id_sparepart'");
  }

// FUNCTION DETAIL TRANSAKSI
function tambah_detail_transaksi($data){
    global $conn;
    $id_transaksi = $data["id_transaksi"];
    $id_device = $data["id_device"];
    $id_layanan = $data["id_layanan"];
    $id_sparepart = $data["id_sparepart"];
    $jml_sparepart = $data["jml_sparepart"];
    $biaya_awal = query("SELECT total_biaya FROM transaksi WHERE id_transaksi='$id_transaksi'");
    $biaya_layanan = query("SELECT biaya FROM layanan WHERE id_layanan='$id_layanan'");
    $harga_sparepart = query("SELECT harga FROM sparepart WHERE id_sparepart='$id_sparepart'");
    // print_r($biaya_layanan);
    $total_biaya=0;
    if ($harga_sparepart) {
        $total_biaya = $biaya_layanan[0]['biaya']+$harga_sparepart[0]['harga']*$jml_sparepart;
    }else{
        $total_biaya = $biaya_layanan[0]['biaya'];
    }
    $total_biaya = $biaya_awal[0]['total_biaya']+$total_biaya;

    if ($id_sparepart) {
        $query = "INSERT INTO detail_transaksi
                VALUES
                ('$id_transaksi','$id_device','$id_layanan', '$id_sparepart', '$jml_sparepart')";
    }else{
        $query = "INSERT INTO detail_transaksi
                VALUES
                ('$id_transaksi','$id_device','$id_layanan', '', '')";
    }

    $query = "INSERT INTO detail_transaksi
                VALUES
                ('','$id_transaksi','$id_device','$id_layanan', '$id_sparepart', '$jml_sparepart')";

    $query_tr = "UPDATE transaksi
                SET 
                total_biaya='$total_biaya'
                WHERE id_transaksi = '$id_transaksi'";
                
    
    mysqli_query($conn,$query);
    mysqli_query($conn,$query_tr);
    return mysqli_affected_rows($conn);
}

function edit_detail_transaksi($data){
    global $conn;

    $id_detail_transaksi = $data["id_detail_transaksi"];
    $id_device = $data["id_device"];
    $id_layanan = $data["id_layanan"];
    $id_sparepart = $data["id_sparepart"];
    $jumlah_sparepart = $data["jumlah_sparepart"];

    var_dump($id_detail_transaksi);
    $query = "UPDATE detail_transaksi
                SET
                id_device='$id_device',
                id_layanan='$id_layanan',
                id_sparepart='$id_sparepart',
                jumlah_sparepart='$jumlah_sparepart'
                WHERE id_detail_transaksi = '$id_detail_transaksi'";

    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}


// FUNCTION SPAREPART
function tambah_sparepart($data){
    global $conn;

    $nama = $data["nama"];
    $tipe = $data["tipe"];
    $stok = $data["stok"];
    $harga = $data["harga"];
    $laba = $data["laba"];

    $query = "INSERT INTO sparepart
                VALUES
                ('', '$nama','$tipe','$stok','$harga','$laba')";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function edit_sparepart($data){
    global $conn;

    $id_sparepart = $data["id_sparepart"];
    $nama = $data["nama"];
    $tipe = $data["tipe"];
    $stok = $data["stok"];
    $harga = $data["harga"];
    $laba = $data["laba"];

    $query = "UPDATE sparepart
                SET
                nama='$nama',tipe='$tipe',stok='$stok',harga='$harga', laba='$laba'
                WHERE id_sparepart = '$id_sparepart'";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function hapus_sparepart($data){
    global $conn;
    $id_sparepart = $data["id_sparepart"];
    mysqli_query($conn, "DELETE FROM sparepart WHERE id_sparepart = $id_sparepart");
    return mysqli_affected_rows($conn);
}

// FUNCTION TEKNISI
function tambah_teknisi($data){
    global $conn;

    $nama = $data["nama"];
    $ttl = $data["ttl"];
    $telp = $data["telp"];
    $alamat = $data["alamat"];

    $query = "INSERT INTO teknisi
                VALUES
                ('', '$nama','$ttl','$telp','$alamat')";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function edit_teknisi($data){
    global $conn;

    $id_teknisi = $data["id_teknisi"];
    $nama = $data["nama"];
    $ttl = $data["ttl"];
    $telp = $data["telp"];
    $alamat = $data["alamat"];

    $query = "UPDATE teknisi
                SET
                nama='$nama', tgl_lahir='$ttl', no_telp='$telp', alamat='$alamat'
                WHERE id_teknisi = '$id_teknisi'";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function hapus_teknisi($data){
    global $conn;
    $id_teknisi = $data["id_teknisi"];
    mysqli_query($conn, "DELETE FROM teknisi WHERE id_teknisi = $id_teknisi");
    return mysqli_affected_rows($conn);
}

// FUNCTION PELANGGAN
function tambah_pelanggan($data){
    global $conn;

    $nama = $data["nama"];
    $no_telp = $data["no_telp"];
    $alamat = $data["alamat"];

    $query = "INSERT INTO pelanggan
                VALUES
                ('', '$nama','$no_telp','$alamat')";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}
                
function edit_pelanggan($data){
    global $conn;

    $id_pelanggan = $data["id_pelanggan"];
    $nama = $data["nama"];
    $no_telp = $data["no_telp"];
    $alamat = $data["alamat"];

    $query = "UPDATE pelanggan
                SET nama='$nama', no_telp='$no_telp', alamat='$alamat' 
                WHERE id_pelanggan ='$id_pelanggan'";


    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function hapus_pelanggan($data){
    global $conn;
    $id_pelanggan = $data["id_pelanggan"];
    mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan = $id_pelanggan");
    return mysqli_affected_rows($conn);
}

// FUNCTION DEVICE
function tambah_device($data){
    global $conn;

    $nama = $data["nama"];

    $query = "INSERT INTO device
                VALUES
                ('', '$nama')";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function edit_device($data){
    global $conn;

    $id_device = $data["id_device"];
    $nama = $data["nama"];

    $query = "UPDATE device
                SET nama='$nama' 
                WHERE id_device = '$id_device'";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function hapus_device($data){
    global $conn;
    $id_device = $data["id_device"];
    mysqli_query($conn, "DELETE FROM device WHERE id_device = $id_device");
    return mysqli_affected_rows($conn);
}

// FUNCTION LAYANAN
function tambah_layanan($data){
    global $conn;

    $tipe = $data["tipe"];
    $keluhan = $data["keluhan"];
    $biaya = $data["biaya"];
    $laba = $data["laba"];

    $query = "INSERT INTO layanan
                VALUES
                ('', '$tipe','$keluhan','$biaya','$laba')";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function edit_layanan($data){
    global $conn;

    $id_layanan = $data["id_layanan"];
    $tipe = $data["tipe"];
    $keluhan = $data["keluhan"];
    $biaya = $data["biaya"];
    $laba = $data["laba"];

    $query = "UPDATE layanan
                SET
                tipe_layanan='$tipe', keluhan='$keluhan', biaya='$biaya', laba='$laba'
                WHERE id_layanan='$id_layanan'";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function hapus_layanan($data){
    global $conn;
    $id_layanan = $data["id_layanan"];
    mysqli_query($conn, "DELETE FROM layanan WHERE id_layanan = $id_layanan");
    return mysqli_affected_rows($conn);
}
?>