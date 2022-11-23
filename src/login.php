<?php
$email = $_POST['email'];
$name = $_POST['password'];

if(isset($_POST['submit'])){
    if(empty($_POST['email'])|| ($_POST["email"] != 'admin')) {
        $errEmail = 'Masukkan email yang sesuai';
    }

    else if(empty($_POST['password']) || ($_POST["password"] != 'admin')){
        $errPassword = 'Masukkan password yang sesuai';
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
        <form class="my-5 px-5 mx-3" action="../dashboard.html" method="post">
          <div class="mb-3">
            <label for="InputEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" name="email" />
          </div>
          <div class="mb-3">
            <label for="InputPassword" class="form-label">Kata Sandi</label>
            <input type="password" class="form-control" id="InputPassword" name="password" />
          </div>
          <button type="submit" class="btn btn-secondary">Login</button>
        </form>
      </div>
    </div>
  </body>
</html>
