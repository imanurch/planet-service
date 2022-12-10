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

function tambah_transaksi($data){
    global $conn;
    // $id_transaksi = getLastID($conn, 'transaksi', 'id_transaksi');
    $tgl_transaksi = $data["tgl_transaksi"];
    $id_teknisi = $data["id_teknisi"];
    $id_pelanggan = $data["id_pelanggan"];
    // $id_device = $data["id_device"];
    // $id_layanan = $data["id_layanan"];
    // $id_sparepart = $data["id_sparepart"];
    // $jml_sparepart = $data["jml_sparepart"];

    $query = "INSERT INTO transaksi
                VALUES
                ('','$tgl_transaksi','$id_teknisi', '$id_pelanggan', '','')";
    
    // $query = "INSERT INTO detail_servis
    //             VALUES
    //             ('$id_servis','$id_device','$id_layanan','$id_sparepart','$jml_sparepart','$biaya_sparepart')";

    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}

function getTotalBiaya($id_transaksi, $id_sparepart, $id_layanan){
    return query("SELECT (ly.biaya+(spr.harga*dt.jumlah_sparepart)) AS tb
    FROM detail_transaksi dt 
    JOIN sparepart spr USING (id_sparepart) 
    JOIN layanan ly USING (id_layanan)
    WHERE dt.id_transaksi= '$id_transaksi' 
    AND ly.id_layanan='$id_layanan' 
    AND spr.id_sparepart = '$id_sparepart'");

    // return $biaya_tambahan;
  }

function tambah_detail_transaksi($data){
    global $conn;
    $id_transaksi = $data["id_transaksi"];
    $id_device = $data["id_device"];
    $id_layanan = $data["id_layanan"];
    $id_sparepart = $data["id_sparepart"];
    $jml_sparepart = $data["jml_sparepart"];
    $biaya_awal = query("SELECT total_biaya FROM transaksi WHERE id_transaksi='$id_transaksi'");
    $biaya_tambahan = getTotalBiaya($id_transaksi, $id_sparepart, $id_layanan);
    $total_biaya = $biaya_awal + $biaya_tambahan;
//     foreach ($biaya_awal AS $ba) :
//         foreach ($biaya_tambahan AS $bt) :
//     $total_biaya = $ba['total_biaya'] + $bt['tb'];
//     endforeach;
// endforeach;
    // $query = "INSERT INTO detail_transaksi
    //             VALUES
    //             ('$id_transaksi','$id_device','$id_layanan', '$id_sparepart', '$jml_sparepart')";

    // $query_tr = "UPDATE transaksi
    //             SET 
    //             total_biaya='$total_biaya'
    //             WHERE id_transaksi = '$id_transaksi'";
                
    
    // mysqli_query($conn,$query);
    mysqli_query($conn,"INSERT INTO detail_transaksi
    VALUES
    ('$id_transaksi','$id_device','$id_layanan', '$id_sparepart', '$jml_sparepart')");
    // mysqli_query($conn,$query_tr);
    mysqli_query($conn,"UPDATE transaksi
    SET 
    total_biaya='$total_biaya'
    WHERE id_transaksi = '$id_transaksi'");
}

// FUNCTION SPAREPART
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

function edit_sparepart($data){
    global $conn;

    $id_sparepart = $data["id_sparepart"];
    $nama = $data["nama"];
    $tipe = $data["tipe"];
    $stok = $data["stok"];
    $harga = $data["harga"];

    $query = "UPDATE sparepart
                SET
                nama='$nama',tipe='$tipe',stok='$stok',harga='$harga'
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

    $query = "INSERT INTO layanan
                VALUES
                ('', '$tipe','$keluhan','$biaya')";

    mysqli_query($conn,$query);

    return mysqli_affected_rows($conn);
}

function edit_layanan($data){
    global $conn;

    $id_layanan = $data["id_layanan"];
    $tipe = $data["tipe"];
    $keluhan = $data["keluhan"];
    $biaya = $data["biaya"];

    $query = "UPDATE layanan
                SET
                tipe_layanan='$tipe', keluhan='$keluhan', biaya='$biaya'
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