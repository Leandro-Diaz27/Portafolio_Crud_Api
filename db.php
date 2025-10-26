<?php
$host = "localhost";
// Especifica el servidor donde está alojada la base de datos. localhost se utiliza cuando el servidor y la base de datos están en la misma máquina.
$db = "leandro_diaz_db1";
$user = "consultar al admin";
$pass = "consultar al admin";
// Variables de Configuración

$conn = new mysqli($host, $user, $pass, $db);
  // Crea un nuevo objeto de la clase mysqli para conectarse a la base de datos.
if ($conn->connect_error) {
  // Verifica si hubo un error al intentar conectarse. Si connect_error tiene un valor, significa que algo falló.
  die("Error de conexión: " . $conn->connect_error);
}
  // Detiene la ejecución del script y muestra el mensaje de error. Esto es útil durante el desarrollo para identificar problemas.
?>
