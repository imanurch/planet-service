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

// function tambah_servis($data){
//     global $conn;
//     $date = $data["date"];
//     $nama_pelanggan = $data["nama_pelanggan"];
//     $no_telp = $data["no_telp"];
//     $alamat = $data["alamat"];
//     $tipe_layanan = $data["tipe_layanan"];
//     $nama_device = $data["nama_device"];
//     $keluhan = $data["keluhan"];
//     $alamat = $data["alamat"];
//     $nama_sparepart = $data["nama_sparepart"];
//     $tipe_hp = $data["tipe_hp"];
//     $nama_teknisi = $data["nama_teknisi"];

//     $query = "INSERT INTO pelanggan
//                 VALUES
//                 ('', '$nama_pelanggan','$no_telp','$alamat')";
    
//     $query = "INSERT INTO detail_servis
//                 VALUES
//                 ('','','','','$nama_device','','','','','')";
    
//     $query = "INSERT INTO servis
//                 VALUES
//                 ('', '','')";

//     mysqli_query($conn,$query);

//     return mysqli_affected_rows($conn);
// }

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