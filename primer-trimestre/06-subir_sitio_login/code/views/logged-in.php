<?php
  session_start();
?>

<!DOCTYPE html>
<html lang = "en">
  <head>
    <meta charset = "utf-8" />
    <meta name = "viewport" content = "width = device-width, initial-scale = 1.0" />
    <title>welcome</title>
    <link rel = "stylesheet" href = "../css/style.css" />
  </head>
  <body>
    <div class = "container logged-in-container">
      <h2>bienvenido, <?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "usuario"; ?>!</h2>
      <a href = "../php/sign-out.php"><button>cerrar sesión</button></a>
    </div>
  </body>
</html>
