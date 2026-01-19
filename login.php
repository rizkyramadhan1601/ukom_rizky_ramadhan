  <?php
session_start();
include 'config/app.php';
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
  ?>
  <!DOCTYPE html>
  <html lang="id">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>EZParking Login</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets-img/logo.webp" rel="icon">
    <link href="assets-img/logo.webp" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
    
    <!-- Css Project -->
    <link rel="stylesheet" href="style.css">
    <style>
      body {
        margin: 0;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
        }

        body::before {
        content: "";
        position: absolute;
        inset: 0;
        background: url('assets-img/bg_login.webp') center / cover no-repeat;
        filter: blur(5px);
        transform: scale(1.1);
        z-index: 1;
        }

        /* card login tetap jelas */
        .login-card {
        position: relative;
        z-index: 2;
        background: #ffffff7e;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        padding: 40px;
        width: 100%;
        max-width: 400px;
        }

        .form-control {
        border-radius: 10px;
        }

        .btn-primary {
        border-radius: 10px;
        font-weight: 600;
        }
    </style>
  </head>
  <body>

  <div class="login-card">
    <h4 class="text-center mb-4 fw-bold">LOGIN EZParking</h4>

      <?php if (!empty($error)) : ?>
          <div class="alert alert-danger"><?= $error; ?></div>
      <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>

      <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
    </form>


    <p class="text-center text-muted mt-3 mb-0" style="font-size: 14px;">
      © <?= date('Y') ?> M RIZKY RAMADHAN - All rights reserved
    </p>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
  </html>