<?php
include 'auth.php';
include 'db.php';

  // auth.php: Podría validar si el usuario está autenticado.
  // db.php: Contiene la conexión a la base de datos.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Verifica si la solicitud HTTP es un POST, indicando que el formulario fue enviado.
  $titulo = $_POST['titulo'];
  $descripcion = $_POST['descripcion'];
  $url_github = $_POST['url_github'];
  $url_produccion = $_POST['url_produccion'];
  // Recoge los datos enviados desde el formulario ($_POST).
  $imagen = $_FILES['imagen']['name'];
  $tmp = $_FILES['imagen']['tmp_name'];
  move_uploaded_file($tmp, "uploads/$imagen");
  //   $_FILES gestiona la subida de archivos.
  // move_uploaded_file(): Mueve la imagen subida a la carpeta uploads/.

  $sql = "INSERT INTO proyectos (titulo, descripcion, url_github, url_produccion, imagen) 
          VALUES ('$titulo', '$descripcion', '$url_github', '$url_produccion', '$imagen')";

  $conn->query($sql);
  header("Location: index.php");
}
      // Construye y ejecuta una consulta SQL para insertar los datos en la tabla proyectos.
      // Redirige al usuario a index.php después de guardar.
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Especifica que el documento está en HTML5 y el idioma es español.
  Carga los íconos de Font Awesome para mejorar la interfaz.
  Define el título y las configuraciones para hacer la página responsiva. -->
  <title>Agregar Proyecto | Panel de Administración</title>
  <!-- iconos de Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
    
    .file-input-container {
      position: relative;
      overflow: hidden;
      display: inline-block;
      width: 100%;
    }
    
    .file-input-container i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray);
    }
    
    .file-input-label {
      display: block;
      padding: 0.8rem 1rem 0.8rem 2.5rem;
      border: 2px dashed #e9ecef;
      border-radius: 10px;
      background-color: var(--white);
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .file-input-label:hover {
      border-color: var(--primary);
      background-color: rgba(67, 97, 238, 0.05);
    }
    
    .file-input {
      position: absolute;
      left: 0;
      top: 0;
      opacity: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }
    
    .file-name {
      margin-top: 0.5rem;
      font-size: 0.8rem;
      color: var(--gray);
      display: none;
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
      <h2><i class="fas fa-plus-circle"></i> Nuevo Proyecto</h2>
      <p>Completa los detalles de tu proyecto</p>
    </div>
    
    <form method="post" enctype="multipart/form-data"> 
      <!-- method="post": Indica que se enviará un formulario.
      enctype="multipart/form-data": Necesario para enviar archivos. -->

      <div class="form-group">
        <label for="titulo">Título del Proyecto</label>
        
        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ej: Sitio Web Corporativo" required>
      </div>
      
      <div class="form-group">
        <label for="descripcion">Descripción</label>
        
        <textarea class="form-control" id="descripcion" name="descripcion" 
                  placeholder="Describe tu proyecto en detalle (máximo 200 palabras)" 
                  maxlength="200" required></textarea>
      </div>
      
      <div class="form-group">
        <label for="url_github">URL de GitHub</label>
        <i class="fab fa-github"></i>
        <input type="url" class="form-control" id="url_github" name="url_github" 
               placeholder="https://github.com/usuario/proyecto">
      </div>
      
      <div class="form-group">
        <label for="url_produccion">URL de Producción</label>
        <i class="fas fa-globe"></i>
        <input type="url" class="form-control" id="url_produccion" name="url_produccion" 
               placeholder="https://tusitio.com">
      </div>
      
      <div class="form-group">
        <label for="imagen">Imagen del Proyecto</label>
        <div class="file-input-container">
          <i class="fas fa-image"></i>
          <label class="file-input-label" for="imagen">Selecciona una imagen...</label>
          <input type="file" class="file-input" id="imagen" name="imagen" required>
          <div class="file-name" id="file-name"></div>
        </div>
      </div>
      
      <button type="submit" class="btn-submit">
        <i class="fas fa-save"></i> Guardar Proyecto
      </button>
    </form>
  </div>

  <script>
    // Muestra dinámicamente el nombre del archivo seleccionado debajo del campo de carga.
    document.getElementById('imagen').addEventListener('change', function(e) {
      const fileName = e.target.files[0] ? e.target.files[0].name : 'Ningún archivo seleccionado';
      const fileDisplay = document.getElementById('file-name');
      fileDisplay.textContent = 'Archivo seleccionado: ' + fileName;
      fileDisplay.style.display = 'block';
    });
  </script>
</body>
</html>