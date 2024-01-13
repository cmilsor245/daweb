<?php
  session_start();

  $login_type = isset($_POST["login_type"]) ? $_POST["login_type"] : "";

  $host = "webserver-db.czf9igclxddl.us-east-1.rds.amazonaws.com";
  $username = "admin";
  $password = "password1234";
  $database = "webserverdb";

  try {
    if ($login_type === "local") {
      $local_username = "test";
      $local_password_hash = hash("sha256", "test");

      if ($_POST["username"] === $local_username && hash("sha256", $_POST["password"]) === $local_password_hash) {
        $_SESSION["username"] = $local_username;
        header("Location: ../views/logged-in.php");
      } else {
        displayError("usuario y/o contraseña incorrectos");
      }
    } elseif ($login_type === "database") {
      $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);

      $hashed_password = hash("sha256", $_POST["password"]);

      $stmt = $pdo -> prepare("SELECT * FROM users WHERE user_id = :username AND password = :password");
      $stmt -> bindParam(":username", $_POST["username"]);
      $stmt -> bindParam(":password", $hashed_password, PDO::PARAM_STR);
      $stmt -> execute();

      $user = $stmt -> fetch();

      if ($user) {
        $_SESSION["username"] = $_POST["username"];
        header("Location: ../views/logged-in.php");
      } else {
        displayError("usuario y/o contraseña incorrectos");
      }
    } else {
      displayError("error: método de login no válido");
    }
  } catch (PDOException $e) {
    displayError("error de conexión: " . $e -> getMessage());
  }

  function displayError($message) {
    echo "<script>alert(\"$message\");</script>";
    echo "<a href=\"../login.html\"><button>volver</button></a>";
  }
?>

<link rel = "stylesheet" href = "../css/style.css" />