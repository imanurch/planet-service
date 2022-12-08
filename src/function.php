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

function tambah_servis($data){
    global $conn;
    $id_servis = getLastID($conn, 'servis', 'id_servis');
    $tgl_masuk = $data["tgl_masuk"];
    $id_teknisi = $data["id_teknisi"];
    $id_pelanggan = $data["id_pelanggan"];
    $id_device = $data["id_device"];
    $id_layanan = $data["id_layanan"];
    $id_sparepart = $data["id_sparepart"];
    $jml_sparepart = $data["jml_sparepart"];
    // $total_biaya =;


    $query = "INSERT INTO servis
                VALUES
                ('$id_servis','$kode_servis','$tgl_masuk','$id_teknisi', $id_pelanggan)";
    
    $query = "INSERT INTO detail_servis
                VALUES
                ('$id_servis','$id_device','$id_layanan','$id_sparepart','$jml_sparepart','$biaya_sparepart')";
    
    $query = "INSERT INTO transaksi
                VALUES 
                ('','$id_servis','$total_biaya','');

    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}

function tambah_sparepart($data){
    global $conn;

    $nama = $data["nama"];
    $tipe = $data["tipe"];
    $stok = $data["stok"];
    $harga = $data["harga"];

    $query = "INSERT INTO sparepart
                VALUES
                ('', '$nama','$tipe','$stok','$harga')";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

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

function tambah_device($data){
    global $conn;

    $nama = $data["nama"];

    $query = "INSERT INTO device
                VALUES
                ('', '$nama')";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function tambah_layanan($data){
    global $conn;

    $tipe = $data["tipe"];
    $keluhan = $data["keluhan"];
    $biaya = $data["biaya"];

    $query = "INSERT INTO layanan
                VALUES
                ('', '$tipe','$keluhan','$biaya')";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}
?>