<?php

/**
 * CORS preflight
 * Es un mecanismo de seguridad donde el navegador pide permiso antes de enviar ciertas solicitudes HTTP entre dominios diferentes 
 */
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { 
    http_response_code(200);
    exit();
}

// Configuración de la base de datos (considera usar variables de entorno)
$host = "localhost";
$db = "leandro_diaz_db1";
$user = "leandro_diaz";
$pass = "leandro_diaz2025";

// Establecer conexión con manejo de errores mejorado
try {
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    // Configurar charset
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    http_response_code(500);
    die(json_encode([
        "error" => "Error de base de datos",
        "message" => $e->getMessage(),
        "code" => $e->getCode()
    ]));
}
?>