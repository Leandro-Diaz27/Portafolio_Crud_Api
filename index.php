<?php
include 'auth.php'; // Asegura que el usuario está autenticado antes de permitir cualquier operación.
include 'db.php'; // Establece la conexión con la base de datos MySQL.

$result = $conn->query("SELECT * FROM proyectos ORDER BY created_at DESC");  // Obtener todos los proyectos ordenados por fecha de creación descendente
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración | Mis Proyectos</title>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet"> 
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
      background: linear-gradient(135deg, #f5f7fa, #e4e8f0);
      min-height: 100vh;
      color: var(--dark);
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem 1rem;
    }
    
    .header {
      text-align: center;
      margin-bottom: 3rem;
      position: relative;
    }
    
    .header h2 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    
    .header::after {
      content: '';
      display: block;
      width: 100px;
      height: 5px;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      margin: 0 auto;
      border-radius: 5px;
    }
    
    .action-buttons {
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      margin-bottom: 3rem;
      flex-wrap: wrap;
    }
    
    .btn {
      padding: 0.8rem 1.8rem;
      border-radius: 50px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.8rem;
      text-decoration: none;
      border: none;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .btn-success {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: var(--white);
    }
    
    .btn-success:hover {
      background: linear-gradient(135deg, var(--primary-dark), var(--primary));
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(67, 97, 238, 0.3);
    }
    
    .btn-secondary {
      background: var(--white);
      color: var(--gray);
      border: 1px solid var(--gray);
    }
    
    .btn-secondary:hover {
      background: var(--light);
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }
    
    .project-card {
      background: var(--white);
      border-radius: 15px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: all 0.4s ease;
      margin-bottom: 2.5rem;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .project-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(31, 38, 135, 0.2);
    }
    
    .project-row {
      display: flex;
      flex-direction: row;
    }
    
    .project-image {
      flex: 0 0 40%;
      max-height: 250px;
      overflow: hidden;
    }
    
    .project-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
    
    .project-card:hover .project-image img {
      transform: scale(1.05);
    }
    
    .project-content {
      flex: 0 0 60%;
      padding: 2rem;
      position: relative;
    }
    
    .project-title {
      color: var(--primary);
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }
    
    .project-description {
      color: var(--gray);
      margin-bottom: 1.5rem;
      line-height: 1.7;
    }
    
    .project-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.8rem;
    }
    
    .btn-sm {
      padding: 0.5rem 1.2rem;
      font-size: 0.9rem;
      border-radius: 50px;
    }
    
    .btn-dark {
      background: var(--dark);
      color: var(--white);
    }
    
    .btn-dark:hover {
      background: #000;
      transform: translateY(-2px);
    }
    
    .btn-primary {
      background: var(--primary);
      color: var(--white);
    }
    
    .btn-primary:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
    }
    
    .btn-warning {
      background: #ffc107;
      color: var(--dark);
    }
    
    .btn-warning:hover {
      background: #e0a800;
      transform: translateY(-2px);
    }
    
    .btn-danger {
      background: var(--error);
      color: var(--white);
    }
    
    .btn-danger:hover {
      background: #d32f2f;
      transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
      .project-row {
        flex-direction: column;
      }
      
      .project-image, .project-content {
        flex: 0 0 100%;
      }
      
      .header h2 {
        font-size: 2rem;
      }
      
      .action-buttons {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
      }
      
      .btn {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>
<div class="container"> 
  <div class="header">
    <h2>Panel de Administración</h2>
    <p>Gestiona tus proyectos profesionales</p>
  </div>
  
  <div class="action-buttons">
    <a href="add.php" class="btn btn-success">
      <i class="fa fa-plus"></i> Agregar Proyecto
    </a>
    <a href="logout.php" class="btn btn-secondary">
      <i class="fas fa-sign-out-alt"></i> Cerrar sesión
    </a>
  </div>

  <?php while($row = $result->fetch_assoc()): ?>   <!-- Recorre los resultados de una consulta a la base de datos ($result) que contiene los proyectos. -->
    <div class="project-card">
      <div class="project-row">
        <div class="project-image">
          <img src="uploads/<?= htmlspecialchars($row['imagen']) ?>" alt="<?= htmlspecialchars($row['titulo']) ?>">
        </div>

        <div class="project-content">
          <h3 class="project-title"><?= htmlspecialchars($row['titulo']) ?></h3>
          <p class="project-description"><?= nl2br(htmlspecialchars($row['descripcion'])) ?></p>
        </div>
        <div class="project-actions">

            <?php if(!empty($row['url_github'])): ?>
              <a href="<?= htmlspecialchars($row['url_github']) ?>" class="btn btn-dark btn-sm" target="_blank">
                <i class="fab fa-github"></i> GitHub
              </a>
            <?php endif; ?>

            <?php if(!empty($row['url_produccion'])): ?>
              <a href="<?= htmlspecialchars($row['url_produccion']) ?>" class="btn btn-primary btn-sm" target="_blank">
                <i class="fa fa-link"></i> Enlace
              </a>
            <?php endif; ?>

            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
              <i class="fa fa-edit"></i> Editar
            </a>
            
            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['id'] ?>">
            <i class="fa fa-trash"></i> Eliminar
            </a>


          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    const id = this.getAttribute('data-id');
    const url = this.getAttribute('href');
    
    Swal.fire({
      title: '¿Estás seguro?',
      text: "¡No podrás revertir esta acción!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',                           
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      background: '#ffffff',
      backdrop: `
        rgba(0,0,0,0.5)
        url("/images/trash.gif")
        center top
        no-repeat
      `
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = url;
      }
    });
  });
});
 </script>
  <!--Este script mejora la experiencia del usuario al confirmar la eliminación de un proyecto utilizando SweetAlert2, una librería de JavaScript para mostrar alertas personalizadas. A continuación, explico cómo funciona y las partes clave: -->

</body>

</html>