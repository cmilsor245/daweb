<?php
  if (!isset($_SESSION)) {
    session_start();
  }

  if (!isset($_COOKIE["webserver-target-group-cookie"]) || (time() - $_SESSION["last_activity"]) > 2) {
    $_SESSION["username"] = "test";
    setcookie("webserver-target-group-cookie", session_id(), time() + 3600, "/");
    $_SESSION["last_activity"] = time();
  }
?>

<!DOCTYPE html>
<html lang = "en">
  <head>
    <meta charset = "utf-8" />
    <meta name = "viewport" content = "width = device-width, initial-scale = 1.0" />
    <title>login</title>
    <link rel = "stylesheet" href = "css/style.css" />
  </head>
  <body>
    <div class = "container">
      <form action = "php/login.php" method = "post">
        <label for = "username">usuario (server original):</label>
        <input type = "text" id = "username" name = "username" autofocus required onfocus = "this.select()" placeholder = "e.g. test">

        <label for = "password">contraseña (server original):</label>
        <input type = "password" id = "password" name = "password" required onfocus = "this.select()" placeholder = "e.g. test">

        <label for = "local">login local</label>
        <input type = "radio" id = "local" name = "login_type" value = "local" checked>

        <label for = "database">login con base de datos</label>
        <input type = "radio" id = "database" name = "login_type" value = "database">

        <button type = "submit">iniciar sesión</button>
      </form>
    </div>
  </body>
</html>
