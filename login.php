<?php
session_start(); // Inicia una sesión o reanuda una existente.
include 'db.php';   // Incluye el archivo de conexión a la base de datos.

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verifica si la solicitud HTTP es un POST, indicando que el formulario fue enviado.
    $username = $_POST['username']; // Recoge el nombre de usuario del formulario.
    $password = md5($_POST['password']);    // Recoge la contraseña del formulario y la encripta.

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";   // Construye una consulta SQL para buscar el usuario en la base de datos.
    $result = $conn->query($sql);   // Ejecuta la consulta SQL.

    if ($result->num_rows === 1) {  // Verifica si se encontró exactamente un usuario con las credenciales proporcionadas.
        $_SESSION['user'] = $username;  // Almacena el nombre de usuario en la variable de sesión para indicar que el usuario ha iniciado sesión.
        header("Location: index.php");  // Redirige al usuario a la página principal (index.php) después de iniciar sesión exitosamente.
        exit(); // Termina la ejecución del script para evitar que se envíen más datos al navegador.
    } else {    // Si no se encontró un usuario con las credenciales proporcionadas, se establece un mensaje de error.
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Portafolio</title>
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
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, var(--glass) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
            z-index: -1;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .login-header p {
            color: var(--gray);
            font-size: 15px;
        }
        
        .login-header i {
            font-size: 48px;
            margin-bottom: 20px;
            color: var(--primary);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .login-form {
            width: 100%;
        }
        
        .form-group {
            margin-bottom: 25px;
            width: 100%;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 15px 20px 15px 45px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 15px;
            background-color: var(--white);
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        .form-group i {
            position: absolute;
            left: 15px;
            top: 40px;
            color: var(--gray);
            font-size: 18px;
        }
        
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
            margin-top: 10px;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }
        
        .error-message {
            color: var(--error);
            font-size: 14px;
            text-align: center;
            margin-bottom: 25px;
            padding: 15px;
            background-color: #fde8e8;
            border-radius: 10px;
            border-left: 4px solid var(--error);
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        .public-button {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
        }
        
        .public-button a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary);
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .public-button a:hover {
            background: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .remember-me input {
            accent-color: var(--primary);
        }
        
        .forgot-password a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .forgot-password a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 30px;
            color: var(--gray);
            font-size: 13px;
        }
        
        .footer-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        @media (max-width: 576px) {
            .login-container {
                padding: 30px 20px;
            }
            
            .public-button {
                top: 15px;
                left: 15px;
            }
            
            .public-button a {
                padding: 10px 15px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="public-button">
        <a href="public.view.php">
            <i class="fas fa-arrow-left"></i> Volver al Portafolio
        </a>
    </div>
    
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-user-shield"></i>
            <h1>Iniciar Sesión</h1>
            <p>Accede a tu panel de administración</p>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" class="login-form">
            <div class="form-group">
                <label for="username">Usuario</label>
                <i class="fas fa-user"></i>
                <input type="text" 
                       id="username" 
                       name="username" 
                       class="form-control" 
                       placeholder="Ingresa tu usuario" 
                       required
                       autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <i class="fas fa-lock"></i>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control" 
                       placeholder="Ingresa tu contraseña" 
                       required>
            </div>
            
            <div class="remember-forgot">
                <div class="remember-me">
                    <input type="checkbox" id="remember">
                    <label for="remember">Recordarme</label>
                </div>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Ingresar
            </button>
            
            <p class="footer-text">
                ¿No tienes una cuenta? <a href="https://mail.google.com/mail/u/1/#inbox?compose=new">Contacta al administrador</a>
            </p>
        </form>
    </div>
</body>
</html>