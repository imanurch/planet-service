<?php
require 'function.php';

$id = $_GET["id"];

if(hapus($id)>0){
    header('refresh:0; url=data_produk.php');
    echo "<script>alert('data berhasil dihapus')</script>";
} else{
    echo "<script>alert('data gagal dihapus')</script>";
}

?>