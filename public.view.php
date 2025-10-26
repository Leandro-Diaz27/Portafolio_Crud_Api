<?php
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>leandro diaz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            background: linear-gradient(135deg, #f5f7fa, #e4e8f0);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            position: relative;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        /* Header Styles */
        header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1rem 0;
            box-shadow: var(--shadow);
            position: relative;
            z-index: 10;
        }
        
        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, var(--glass), transparent);
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            position: relative;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            text-decoration: none;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .logo i {
            font-size: 1.5rem;
        }
        
        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .login-btn {
            background-color: var(--white);
            color: var(--primary);
            border: none;
            padding: 0.7rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .login-btn:hover {
            background-color: rgba(255, 255, 255, 0.95);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        
        /* Main Content */
        h1 {
            text-align: center;
            margin: 3rem 0;
            color: var(--dark);
            font-size: 2.8rem;
            position: relative;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        h1::after {
            content: '';
            display: block;
            width: 100px;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            margin: 1rem auto;
            border-radius: 5px;
        }
        
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2.5rem;
            margin: 3rem 0;
        }
        
        .project-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.4s ease;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .project-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(247, 37, 133, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(31, 38, 135, 0.2);
        }
        
        .project-card:hover::before {
            opacity: 1;
        }
        
        .project-image {
            height: 220px;
            overflow: hidden;
            position: relative;
            z-index: 0;
        }
        
        .project-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .project-card:hover .project-image img {
            transform: scale(1.1);
        }
        
        .project-content {
            padding: 1.8rem;
            position: relative;
        }
        
        .project-content h2 {
            margin: 0 0 1.2rem;
            color: var(--primary);
            font-size: 1.6rem;
            font-weight: 700;
        }
        
        .project-content p {
            color: var(--gray);
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }
        
        .project-tech {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .tech-tag {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .project-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
            justify-content: center;
            align-items: center;
        }


        .project-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .project-btn i {
            font-size: 1rem;
        }

        .github-btn {
            background-color: #333;
            color: white;
            border: 2px solid #333;
        }

        .github-btn:hover {
            background-color: #24292e;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(36, 41, 46, 0.2);
        }

        .demo-btn {
            background-color: var(--primary);
            color: white;
            border: 2px solid var(--primary);
        }

        .demo-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(58, 12, 163, 0.2);
        }

        .presentation {
            background: var(--white);
            border-radius: 15px;
            padding: 2.5rem;
            margin: 3rem 0;
            box-shadow: var(--shadow);
        }
        
        .profile-section {
            display: flex;
            gap: 3rem;
            align-items: center;
        }
        
        .profile-image {
            flex: 0 0 250px;
        }
        
        .profile-img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .profile-content {
            flex: 1;
        }
        
        .profile-content h2 {
            color: var(--primary-dark);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .profile-title {
            color: var(--secondary);
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        
        .profile-description p {
            margin-bottom: 1rem;
            color: var(--dark);
            line-height: 1.7;
        }
        
        .skills h3 {
            margin: 1.5rem 0 1rem;
            color: var(--primary);
        }
        
        .skills-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
        }
        
        .skill-tag {
            background: linear-gradient(135deg, var(--primary-light), var(--success));
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .profile-section {
                flex-direction: column;
                gap: 2rem;
            }
            
            .profile-image {
                flex: 0 0 auto;
                width: 200px;
                margin: 0 auto;
            }
        }

            /* Para pantallas pequeñas */
            @media (max-width: 480px) {
                .project-buttons {
                    flex-direction: column;
                }
                
                .project-btn {
                    width: 100%;
                }
            }
            
            /* Footer */
            footer {
                background: linear-gradient(135deg, var(--primary-dark), var(--dark));
                color: white;
                text-align: center;
                padding: 3rem 0;
                margin-top: 5rem;
                position: relative;
            }
            
            footer::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, var(--glass), transparent);
            }
            
            footer p {
                position: relative;
                font-size: 1rem;
            }
            
            .social-links {
                display: flex;
                justify-content: center;
                gap: 1.5rem;
                margin: 1.5rem 0;
            }
            
            .social-links a {
                color: white;
                font-size: 1.5rem;
                transition: transform 0.3s ease;
            }
            
            .social-links a:hover {
                transform: translateY(-5px) scale(1.1);
            }
            
            @media (max-width: 768px) {
                .projects-grid {
                    grid-template-columns: 1fr;
                    gap: 2rem;
                }
                
                h1 {
                    font-size: 2.2rem;
                    margin: 2rem 0;
                }
                
                .header-container {
                    flex-direction: column;
                    gap: 1rem;
                }
                
                .logo {
                    font-size: 1.5rem;
                }
            }
    </style>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
    <div class="header-container">
        <a class="logo">
           
            <span>Leandro Diaz</span>
        </a>
        <div class="auth-buttons">
            <button class="login-btn" onclick="window.location.href='login.php'">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </div>
    </div>
</header>
    <main class="container">
        
<section class="presentation">
    <div class="profile-section">
        <div class="profile-image">
            <img src="assets/imgs/aaaaa.png" alt="Leandro Diaz" class="profile-img">
        </div>
        <div class="profile-content">
            <h2>Hola, soy Leandro Diaz</h2>
            <p class="profile-title">Desarrollador Full Stack</p>
            <div class="profile-description">
                <p>Apasionado por crear soluciones innovadoras.</p>

                <p>Mi enfoque esta en el desarrollo de aplicaciones web escalables y centradas en el usuario.</p>
            </div>
            <div class="skills">
                <h3>Tecnologías principales:</h3>
                <div class="skills-tags">
                    <span class="skill-tag">HTML 5</span>
                    <span class="skill-tag">CSS 3</span>
                    <span class="skill-tag">JavaScript</span>
                    <span class="skill-tag">PHP</span>
                    <span class="skill-tag">MySQL</span>
                    <span class="skill-tag">Zypher</span>
                    <span class="skill-tag">Github</span>
                    <span class="skill-tag">Jira</span>
                    <span class="skill-tag">Postman</span>
                    <span class="skill-tag">Figma</span>
                    <span class="skill-tag">Filezilla</span>

                </div>
            </div>
        </div>
    </div>
</section>
        <h1>Mis Proyectos Destacados</h1>
        <div class="projects-grid">
            <?php
            $stmt = $conn->prepare("SELECT id, titulo, descripcion, imagen, url_github, url_produccion FROM proyectos ORDER BY created_at DESC");   // Prepara una consulta SQL para seleccionar los proyectos de la base de datos, ordenados por fecha de creación.       
            $stmt->execute();   // Ejecuta la consulta preparada.   
            $proyectos = $stmt->get_result();   // Obtiene el resultado de la consulta ejecutada.       
            
            while ($proyecto = $proyectos->fetch_assoc()):  // Recorre cada proyecto obtenido de la base de datos y lo almacena en la variable $proyecto.
            ?>
                <div class="project-card">
                    <?php if ($proyecto['imagen']): ?>  
                    <div class="project-image">
                        <img src="uploads/<?= htmlspecialchars($proyecto['imagen']) ?>" alt="<?= htmlspecialchars($proyecto['titulo']) ?>">
                    </div>
                    <?php endif; ?>
                    
                    <!-- Añade esta sección para los enlaces -->
                    <div class="project-buttons">
                        <?php if ($proyecto['url_github']): ?>
                        <a href="<?= htmlspecialchars($proyecto['url_github']) ?>" target="_blank" class="project-btn github-btn">
                            <i class="fab fa-github"></i> Código
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($proyecto['url_produccion']): ?>
                        <a href="<?= htmlspecialchars($proyecto['url_produccion']) ?>" target="_blank" class="project-btn demo-btn">
                            <i class="fas fa-external-link-alt"></i> Demo
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="project-content">
                        <h2><?= htmlspecialchars($proyecto['titulo']) ?></h2>
                        <p><?= nl2br(htmlspecialchars($proyecto['descripcion'])) ?></p>
                    </div>
                    
                </div>
                
            <?php endwhile; ?>
        </div>
    </main>
    
    <footer>
        <p>&copy; <?= date('Y') ?></p>
    </footer>
</body>
</html>