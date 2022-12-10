<?php
session_start();

if(isset($_SESSION["Login"])){
  header('refresh:0; url = dashboard.php');
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home</title>
    <link href="../scss/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body class="bg-dark text-center text-secondary" style="background-image: url(../pic/bg.home.png); background-size: cover; background-repeat: no-repeat">
    <div class="pt-5">
      <h1 class="pt-5" style="font-size: 80px">PLANET SERVICE</h1>
      <p style="font-size: 20px">Kemudahan dalam mengelola jasa layanan toko anda</p>
    </div>

    <div>
      <button type="submit" class="btn bg-secondary px-4 text-primary" style="border-radius: 6rem"><a href="../src/login.php">LOGIN</a></button>
    </div>
  </body>
</html>
