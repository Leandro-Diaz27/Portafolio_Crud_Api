<?php
session_start();
  // Inicia una sesión PHP o reanuda una existente.
if (!isset($_SESSION['user'])) {
  // Verifica si la variable de sesión $_SESSION['user'] está definida. Si no está definida, significa que el usuario no ha iniciado sesión.
  header("Location: login.php");
  // Redirige al usuario a la página login.php para iniciar sesión.
  exit;
  // Detiene la ejecución del script después de la redirección.
}
?>