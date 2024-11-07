<!DOCTYPE html>
<html lang="ca">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TopBid</title>
  <link rel="icon" href="views/images/icons/logo.ico">
  <link rel="stylesheet" href="views/css/login.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <div class="box">
    <form action="index.php?controller=auth&action=authenticate" method="POST">
      <h1>TopBid</h1>

      <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
      <?php endif; ?>

      <div class="input-box">
        <label for="username">Usuari:</label>
        <input type="text" name="username" id="username" placeholder="Username" required>
        <i class='bx bxs-user-circle'></i>
      </div>
      <div class="input-box">
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <i class='bx bxs-lock-alt'></i>
      </div>
      <button type="submit" class="btn">Login</button>
    </form>
  </div>
</body>

</html>