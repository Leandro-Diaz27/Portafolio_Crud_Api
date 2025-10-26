<?php
include 'auth.php';
// Asegura que el usuario está autenticado antes de permitir cualquier operación. Sin este archivo, cualquier persona podría acceder y manipular datos.
include 'db.php';
// Establece la conexión con la base de datos MySQL.
$id = $_GET['id'];
// Obtiene el valor del parámetro id de la URL. Por ejemplo, si la URL es delete.php?id=5, entonces $id tendrá el valor 5.
$conn->query("DELETE FROM proyectos WHERE id=$id");
// Ejecuta una consulta SQL para eliminar el registro con el identificador especificado en la tabla proyectos.
header("Location: index.php");
// Redirige al usuario a la página principal (index.php) después de eliminar el registro.
exit
?>
  