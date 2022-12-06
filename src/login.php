<?php
session_start();

if(isset($_SESSION["Login"])){
  header('refresh:0; url = dashboard.php');
  exit;
}

require 'function.php';
if(isset($_POST['login'])){
  $email = $_POST['email'];
  $password = $_POST['password'];

  $result = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' AND password = '$password'");

    if(mysqli_num_rows($result) > 0){
      $_SESSION["Login"] = true;
      header('refresh:0; url=dashboard.php');
    }
    else{
      header('refresh:0; url=login.php');
      echo "<script>alert('email atau password yang anda masukkan salah')</script>";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link href="../scss/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="row align-items-center text-center">
      <div class="col-12 col-sm-12 col-md-6 bg-dark text-light" style="padding-top: 6rem; padding-bottom: 6rem">
        <img src="../pic/roket.png" class="img-fluid mb-4" style="width: 50%" alt="" />
        <h2 class="pt-3">PLANET SERVICE</h2>
      </div>
      <div class="col-12 col-sm-12 col-md-6 py-4">
        <h3>LOGIN</h3>
        <p>Lengkapi Email dan Kata Sandi yang terdaftar pada aplikasi</p>
        <form class="my-5 px-5 mx-3" action="" method="post">
          <div class="mb-3">
            <label for="InputEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" name="email" />
          </div>
          <div class="mb-3">
            <label for="InputPassword" class="form-label">Kata Sandi</label>
            <input type="password" class="form-control" id="InputPassword" name="password" />
          </div>
          <button type="submit" class="btn btn-secondary" name="login">Login</button>
        </form>
      </div>
    </div>
  </body>
</html>