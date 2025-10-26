<?php
session_start();    // Inicia una sesión o reanuda una existente.
session_destroy();  // Destruye la sesión actual, eliminando todas las variables de sesión y cerrando la sesión del usuario.
header("Location: login.php");  // Redirige al usuario a la página de inicio de sesión (login.php) después de cerrar la sesión.
?>
  