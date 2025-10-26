
<?php
include 'auth.php'; // Asegura que el usuario está autenticado antes de permitir cualquier operación.
include 'db.php'; // Establece la conexión con la base de datos MySQL.

$id = $_GET['id']; // Obtiene el valor del parámetro id de la URL. Por ejemplo, si la URL es edit.php?id=5, entonces $id tendrá el valor 5.
$proyecto = $conn->query("SELECT * FROM proyectos WHERE id=$id")->fetch_assoc(); // Ejecuta una consulta SQL para obtener los detalles del proyecto con el identificador especificado en la tabla proyectos.

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verifica si la solicitud HTTP es un POST, indicando que el formulario fue enviado.
  
  $titulo = $_POST['titulo']; // Recoge los datos enviados desde el formulario ($_POST).
  $descripcion = $_POST['descripcion'];
  $url_github = $_POST['url_github'];
  $url_produccion = $_POST['url_produccion'];

  if ($_FILES['imagen']['name']) { // Verifica si se ha subido una nueva imagen.
    $imagen = $_FILES['imagen']['name']; // Obtiene el nombre del archivo de la imagen subida.
    move_uploaded_file($_FILES['imagen']['tmp_name'], "uploads/$imagen"); // Mueve la imagen subida a la carpeta uploads/.
    $img_sql = ", imagen='$imagen'";  // Prepara la parte de la consulta SQL para actualizar la imagen.
  } else {
    $img_sql = ""; // Si no se ha subido una nueva imagen, no se actualiza el campo imagen en la base de datos.
  }

  $sql = "UPDATE proyectos SET titulo='$titulo', descripcion='$descripcion', url_github='$url_github', url_produccion='$url_produccion' $img_sql WHERE id=$id"; 
  $conn->query($sql); // Construye y ejecuta una consulta SQL para actualizar los datos del proyecto en la tabla proyectos. Si $img_sql no está vacío, se incluirá la actualización de la imagen.
  header("Location: index.php"); // Redirige al usuario a index.php después de actualizar el proyecto.
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Proyecto | Panel de Administración</title>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <style>
    :root {
      --primary: #4361ee;
      --primary-light: #4895ef;
      --primary-dark: #3a0ca3;
      --secondary: #f72585;
      --success: #4cc9f0;
      --error: #f44336;
      --dark: #212529;
      --light: #f8f9fa;
      --gray: #6c757d;
      --white: #ffffff;
      --glass: rgba(255, 255, 255, 0.15);
      --shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif;
    }
    
    body {
      background: linear-gradient(135deg, #f0f4f8, #dfe7f5);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem;
    }
    
    .form-container {
      background: var(--white);
      border-radius: 20px;
      box-shadow: var(--shadow);
      width: 100%;
      max-width: 600px;
      padding: 2.5rem;
      position: relative;
      overflow: hidden;
      transition: transform 0.3s ease;
    }
    
    .form-container:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(31, 38, 135, 0.2);
    }
    
    .form-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
    }
    
    .form-header {
      text-align: center;
      margin-bottom: 2.5rem;
    }
    
    .form-header h2 {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 0.5rem;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    
    .form-header p {
      color: var(--gray);
      font-size: 0.9rem;
    }
    
    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--dark);
      font-size: 0.9rem;
    }
    
    .form-control {
      width: 100%;
      padding: 0.8rem 1rem 0.8rem 2.5rem;
      border: 2px solid #e9ecef;
      border-radius: 10px;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      background-color: var(--white);
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
    }
    
    textarea.form-control {
      min-height: 120px;
      padding: 1rem;
      resize: vertical;
    }
    
    .form-group i {
      position: absolute;
      left: 1rem;
      top: 2.4rem;
      color: var(--gray);
      font-size: 1rem;
    }
    
    .btn-submit {
      width: 100%;
      padding: 1rem;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: var(--white);
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    
    .btn-submit:hover {
      background: linear-gradient(135deg, var(--primary-dark), var(--primary));
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(67, 97, 238, 0.3);
    }
    
    .current-image {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-top: 0.5rem;
    }
    
    .current-image img {
      width: 50px;
      height: 50px;
      border-radius: 5px;
      object-fit: cover;
    }
    
    .current-image span {
      font-size: 0.8rem;
      color: var(--gray);
    }
    
    @media (max-width: 768px) {
      .form-container {
        padding: 1.5rem;
      }
      
      .form-header h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="form-container">
    <div class="form-header">
      <h2><i class="fas fa-edit"></i> Editar Proyecto</h2>
      <p>Actualiza los detalles de tu proyecto</p>
    </div>
    
    <form method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="titulo">Título del Proyecto</label>
        
        <input type="text" class="form-control" id="titulo" name="titulo" value="<?= htmlspecialchars($proyecto['titulo']) ?>" required>
      </div>
      
      <div class="form-group">
        <label for="descripcion">Descripción</label>
        
        <textarea class="form-control" id="descripcion" name="descripcion" required><?= htmlspecialchars($proyecto['descripcion']) ?></textarea>
      </div>
      
      <div class="form-group">
        <label for="url_github">URL de GitHub</label>
        <i class="fab fa-github"></i>
        <input type="url" class="form-control" id="url_github" name="url_github" value="<?= htmlspecialchars($proyecto['url_github']) ?>">
      </div>
      
      <div class="form-group">
        <label for="url_produccion">URL de Producción</label>
        <i class="fas fa-globe"></i>
        <input type="url" class="form-control" id="url_produccion" name="url_produccion" value="<?= htmlspecialchars($proyecto['url_produccion']) ?>">
      </div>
      
      <div class="form-group">
        <label for="imagen">Imagen del Proyecto</label>
        <i class="fas fa-image"></i>
        <input type="file" class="form-control" id="imagen" name="imagen">
        <?php if($proyecto['imagen']): ?>
          <div class="current-image">
            <img src="uploads/<?= htmlspecialchars($proyecto['imagen']) ?>" alt="Imagen actual">
            <span>Imagen actual</span>
          </div>
        <?php endif; ?>
      </div>
      
      <button type="submit" class="btn-submit">
        <i class="fas fa-save"></i> Actualizar Proyecto
      </button>
    </form>
  </div>
</body>
</html>