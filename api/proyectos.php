<?php
include 'config.php';
header("Access-Control-Allow-Origin: *"); // Permite solicitudes desde cualquier origen. En producción, deberías restringir esto a dominios específicos.
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS"); // Permite los métodos HTTP especificados.
header("Access-Control-Allow-Headers: Content-Type, Authorization");    // Permite los encabezados especificados.

$method = $_SERVER['REQUEST_METHOD'];   // Obtiene el método HTTP de la solicitud actual (GET, POST, PUT, PATCH, DELETE, OPTIONS).
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));  // Divide la URL en partes, eliminando los espacios en blanco al principio y al final, y separando por '/'. Por ejemplo, si la URL es /api/proyectos/1, $request será ['proyectos', '1'].
$id = isset($request[0]) ? intval($request[0]) : null;  // Si hay un segundo elemento en $request, lo convierte a entero; de lo contrario, lo establece como null. Esto se usa para identificar un recurso específico (por ejemplo, un proyecto con ID 1).

function getInput() {   // Esta función obtiene los datos de entrada de la solicitud HTTP.
    return json_decode(file_get_contents("php://input"), true); // Lee el cuerpo de la solicitud y lo decodifica como un array asociativo. Esto es útil para solicitudes PUT y PATCH donde los datos se envían en formato JSON.
}

header("Content-Type: application/json; charset=UTF-8");    // Establece el tipo de contenido de la respuesta como JSON y especifica la codificación de caracteres UTF-8.

try {   
    switch ($method) {  // Dependiendo del método HTTP, se ejecutará un bloque de código diferente.
        case 'GET': 
            if ($id) {  // Si se proporciona un ID, se busca un proyecto específico.
                $stmt = $conn->prepare("SELECT * FROM proyectos WHERE id = ?"); 
                $stmt->bind_param("i", $id);    // Vincula el parámetro ID a la consulta preparada para evitar inyecciones SQL.
                $stmt->execute();   // Ejecuta la consulta preparada.
                $result = $stmt->get_result();  // Obtiene el resultado de la consulta ejecutada.
                echo json_encode($result->fetch_assoc());   // Devuelve el proyecto encontrado como JSON.
            } else {    // Si no se proporciona un ID, se obtienen todos los proyectos.
                $result = $conn->query("SELECT * FROM proyectos ORDER BY created_at DESC"); 
                echo json_encode($result->fetch_all(MYSQLI_ASSOC));   // Devuelve todos los proyectos como JSON.

            }
            break;            

        case 'POST': // Este método se utiliza para crear un nuevo proyecto.                 
            $data = getInput(); // Obtiene los datos enviados en el cuerpo de la solicitud.
            $stmt = $conn->prepare("INSERT INTO proyectos (titulo, descripcion, url_github, url_produccion, imagen) VALUES (?, ?, ?, ?, ?)");   // Prepara una consulta SQL para insertar un nuevo proyecto en la base de datos.
            $stmt->bind_param( // Vincula los parámetros a la consulta preparada.     
                "sssss",    // Especifica los tipos de datos de los parámetros: 's' para string.
                $data['titulo'],    
                $data['descripcion'],
                $data['url_github'],
                $data['url_produccion'],
                $data['imagen']
            );
            $stmt->execute();   // Ejecuta la consulta preparada para insertar el nuevo proyecto.
            echo json_encode(["success" => true, "id" => $stmt->insert_id]);    // Devuelve el ID del nuevo proyecto como JSON.
            break;  // Este caso maneja las solicitudes POST, que se utilizan para crear nuevos recursos (en este caso, proyectos).

        case 'PUT': // Este método se utiliza para actualizar un proyecto existente.
            if (!$id) { // Si no se proporciona un ID, se devuelve un error.
                http_response_code(400);    // Establece el código de estado HTTP a 400 (Bad Request).
                echo json_encode(["error" => "Se requiere ID para PUT"]);   // Devuelve un mensaje de error en formato JSON.
                break; // Termina el caso PUT si no se proporciona un ID. 
            }
            
            $data = getInput(); // Obtiene los datos enviados en el cuerpo de la solicitud.
            if (empty($data)) { // Si no se proporcionan datos, se devuelve un error.
                http_response_code(400);    // Establece el código de estado HTTP a 400 (Bad Request).
                echo json_encode(["error" => "Datos no proporcionados"]);   // Devuelve un mensaje de error en formato JSON.
                break;  // Termina el caso PUT si no se proporcionan datos.
            }
            
            // Verificar que todos los campos requeridos estén presentes
            $required = ['titulo', 'descripcion', 'url_github', 'url_produccion', 'imagen'];    // Define los campos requeridos para la actualización del proyecto.
            foreach ($required as $field) { // Recorre cada campo requerido.
                    if (!isset($data[$field])) {    // Si un campo requerido no está presente en los datos proporcionados, se devuelve un error.
                        http_response_code(400);    // Establece el código de estado HTTP a 400 (Bad Request).
                    echo json_encode(["error" => "Campo requerido faltante: $field"]);  // Devuelve un mensaje de error en formato JSON.
                    break 2;    // Termina el bucle foreach y el caso PUT.
                }
            }
            
            $stmt = $conn->prepare("UPDATE proyectos SET titulo = ?, descripcion = ?, url_github = ?, url_produccion = ?, imagen = ? WHERE id = ?");    // Prepara una consulta SQL para actualizar un proyecto existente en la base de datos.
            $stmt->bind_param(  // Vincula los parámetros a la consulta preparada.
                "sssssi",
                $data['titulo'],
                $data['descripcion'],
                $data['url_github'],
                $data['url_produccion'],
                $data['imagen'],
                $id
            );
            
            if (!$stmt->execute()) {    // Si la ejecución de la consulta falla, se devuelve un error.
                http_response_code(500);    // Establece el código de estado HTTP a 500 (Internal Server Error).
                echo json_encode(["error" => $stmt->error]);    // Devuelve un mensaje de error en formato JSON.
                break;  // Termina el caso PUT si la ejecución de la consulta falla.
            }
            
            if ($stmt->affected_rows === 0) {   // Si no se actualizó ningún registro, se verifica si el proyecto existe.
                
                $check = $conn->query("SELECT id FROM proyectos WHERE id = $id");   // Verifica si el proyecto con el ID proporcionado existe en la base de datos.
                if ($check->num_rows === 0) {   // Si no se encuentra el proyecto, se devuelve un error 404.
                    http_response_code(404);    // Establece el código de estado HTTP a 404 (Not Found).
                    echo json_encode(["error" => "Proyecto no encontrado"]);    // Devuelve un mensaje de error en formato JSON.
                } else {    // Si el proyecto existe pero no se realizaron cambios, se devuelve un mensaje de éxito.
                    http_response_code(200);    // Establece el código de estado HTTP a 200 (OK).
                    echo json_encode(["success" => true, "message" => "No se realizaron cambios"]);   // Devuelve un mensaje de éxito en formato JSON.
                }
            } else {    // Si se actualizó al menos un registro, se devuelve un mensaje de éxito con el número de filas afectadas.
                echo json_encode(["success" => true, "affected_rows" => $stmt->affected_rows]);   // Devuelve un mensaje de éxito en formato JSON, incluyendo el número de filas afectadas por la actualización.
            }
            break;  // Este caso maneja las solicitudes PUT, que se utilizan para actualizar recursos existentes (en este caso, proyectos).

        case 'PATCH':   // Este método se utiliza para actualizar parcialmente un proyecto existente.
            file_put_contents('api_debug.log', date('Y-m-d H:i:s')." - PATCH Request for ID: $id\n", FILE_APPEND);  // Registra la solicitud PATCH en un archivo de depuración.
            $data = getInput(); // Obtiene los datos enviados en el cuerpo de la solicitud.
            file_put_contents('api_debug.log', "Data received: ".print_r($data, true)."\n", FILE_APPEND);   // Registra los datos recibidos en el archivo de depuración.
            
            if (empty($data)) { // Si no se proporcionan datos, se devuelve un error.
                http_response_code(400);    // Establece el código de estado HTTP a 400 (Bad Request).
                echo json_encode(["error" => "Datos no proporcionados"]);   // Devuelve un mensaje de error en formato JSON.
                break;  // Termina el caso PATCH si no se proporcionan datos.
            }
            
            if ($conn->connect_error) { // Verifica si hay un error de conexión a la base de datos.
                http_response_code(500);        // Establece el código de estado HTTP a 500 (Internal Server Error).
                echo json_encode(["error" => "Error de conexión: ".$conn->connect_error]);  // Devuelve un mensaje de error en formato JSON.
                break;  // Termina el caso PATCH si hay un error de conexión a la base de datos.
            }
            
            $sets = []; // Inicializa un array para almacenar las partes de la consulta SQL que se actualizarán.
            $types = '';    // Inicializa una cadena para almacenar los tipos de datos de los parámetros que se vincularán a la consulta SQL.
            $values = [];   // Inicializa un array para almacenar los valores que se vincularán a la consulta SQL.
            
            foreach ($data as $key => $value) { // Recorre cada par clave-valor en los datos proporcionados.
                if (in_array($key, ['titulo', 'descripcion', 'url_github', 'url_produccion', 'imagen'])) {  // Verifica si la clave es uno de los campos permitidos para actualizar.
                    $sets[] = "`$key` = ?";     // Agrega la parte de la consulta SQL que se actualizará al array $sets.
                    $types .= 's';      // Agrega el tipo de dato 's' (string) a la cadena $types.
                    $values[] = $value; // Agrega el valor correspondiente al array $values.
                }
            }
            
            if (empty($sets)) { // Si no hay campos válidos para actualizar, se devuelve un error.
                http_response_code(400);    // Establece el código de estado HTTP a 400 (Bad Request).
                echo json_encode(["error" => "No hay campos válidos para actualizar"]);  // Devuelve un mensaje de error en formato JSON.
                break;  // Termina el caso PATCH si no hay campos válidos para actualizar.
            }
            
            $query = "UPDATE proyectos SET ".implode(", ", $sets)." WHERE id = ?";  // Construye la consulta SQL para actualizar los campos especificados en $sets, uniendo las partes con comas.
            $types .= 'i';      // Agrega el tipo de dato 'i' (integer) a la cadena $types.
            $values[] = $id;    // Agrega el ID del proyecto a actualizar al array $values.
            
            file_put_contents('api_debug.log', "Query: $query\n", FILE_APPEND); // Registra la consulta SQL en el archivo de depuración.
            file_put_contents('api_debug.log', "Types: $types\n", FILE_APPEND); // Registra los tipos de datos de los parámetros en el archivo de depuración.
            file_put_contents('api_debug.log', "Values: ".print_r($values, true)."\n", FILE_APPEND);    // Registra los valores que se vincularán a la consulta SQL en el archivo de depuración.
            
            $stmt = $conn->prepare($query); // Prepara la consulta SQL para evitar inyecciones SQL.
            if (!$stmt) {   // Si la preparación de la consulta falla, se devuelve un error.
                $error = $conn->error;  // Obtiene el error de la conexión a la base de datos.
                file_put_contents('api_debug.log', "Prepare error: $error\n", FILE_APPEND); // Registra el error de preparación en el archivo de depuración.
                http_response_code(500);    // Establece el código de estado HTTP a 500 (Internal Server Error).
                echo json_encode(["error" => "Error al preparar la consulta: $error"]); // Devuelve un mensaje de error en formato JSON.
                break;  // Termina el caso PATCH si la preparación de la consulta falla.
            }
            
            if (!$stmt->bind_param($types, ...$values)) {   // Vincula los parámetros a la consulta preparada. El operador de propagación (...) se utiliza para pasar los valores del array $values como argumentos individuales.
                $error = $stmt->error;  // Si la vinculación de parámetros falla, se devuelve un error.
                file_put_contents('api_debug.log', "Bind error: $error\n", FILE_APPEND);    // Registra el error de vinculación en el archivo de depuración.
                http_response_code(500);    // Establece el código de estado HTTP a 500 (Internal Server Error).
                echo json_encode(["error" => "Error al vincular parámetros: $error"]);  // Devuelve un mensaje de error en formato JSON.
                break;  // Termina el caso PATCH si la vinculación de parámetros falla.
            }
            
            if (!$stmt->execute()) {    // Ejecuta la consulta preparada. Si la ejecución falla, se devuelve un error.
                $error = $stmt->error;  // Obtiene el error de la ejecución de la consulta.
                file_put_contents('api_debug.log', "Execute error: $error\n", FILE_APPEND); // Registra el error de ejecución en el archivo de depuración.
                http_response_code(500);    // Establece el código de estado HTTP a 500 (Internal Server Error).
                echo json_encode(["error" => "Error al ejecutar: $error"]); // Devuelve un mensaje de error en formato JSON.
                break;  // Termina el caso PATCH si la ejecución de la consulta falla.
            }
            
            $affected = $stmt->affected_rows;   // Obtiene el número de filas afectadas por la consulta ejecutada.
            file_put_contents('api_debug.log', "Affected rows: $affected\n", FILE_APPEND);  // Registra el número de filas afectadas en el archivo de depuración.
            
            if ($affected === 0) {  // Si no se actualizó ningún registro, se verifica si el proyecto existe.
                $check = $conn->query("SELECT id FROM proyectos WHERE id = $id");   // Verifica si el proyecto con el ID proporcionado existe en la base de datos.
                if ($check->num_rows === 0) {   // Si no se encuentra el proyecto, se devuelve un error 404.
                    http_response_code(404);    // Establece el código de estado HTTP a 404 (Not Found).
                    echo json_encode(["error" => "Proyecto no encontrado"]);    // Devuelve un mensaje de error en formato JSON.
                } else {    // Si el proyecto existe pero no se realizaron cambios, se devuelve un mensaje de éxito.
                    http_response_code(200);    // Establece el código de estado HTTP a 200 (OK).
                    echo json_encode(["success" => true, "message" => "No se realizaron cambios"]);  // Devuelve un mensaje de éxito en formato JSON, indicando que no se realizaron cambios.
                }
            } else {    // Si se actualizó al menos un registro, se devuelve un mensaje de éxito con el número de filas afectadas.
                echo json_encode(["success" => true, "affected_rows" => $affected]);    // Devuelve un mensaje de éxito en formato JSON con el número de filas afectadas.
            }
            break;  // Este caso maneja las solicitudes PATCH, que se utilizan para actualizar parcialmente recursos existentes (en este caso, proyectos).

        case 'DELETE':  // Este método se utiliza para eliminar un proyecto existente.
            if (!$id) { // Si no se proporciona un ID, se devuelve un error.
                http_response_code(400);    // Establece el código de estado HTTP a 400 (Bad Request).
                echo json_encode(["error" => "Se requiere ID para DELETE"]);   // Devuelve un mensaje de error en formato JSON.
                break;  // Termina el caso DELETE si no se proporciona un ID.
            }
    file_put_contents('delete.log', date('Y-m-d H:i:s')." - ID: $id\n", FILE_APPEND);   // Registra la solicitud DELETE en un archivo de depuración.
    
    // Verifica si el registro existe primero
    $check = $conn->prepare("SELECT id FROM proyectos WHERE id = ?");   // Prepara una consulta SQL para verificar si el proyecto con el ID proporcionado existe en la base de datos.
    $check->bind_param("i", $id);   // Vincula el parámetro ID a la consulta preparada para evitar inyecciones SQL.
    $check->execute();  // Ejecuta la consulta preparada.
    
    if ($check->get_result()->num_rows === 0) { // Si no se encuentra el proyecto, se devuelve un error 404.
        http_response_code(404);    // Establece el código de estado HTTP a 404 (Not Found).
        echo json_encode(["error" => "Proyecto no encontrado"]);    // Devuelve un mensaje de error en formato JSON.
        break;  // Termina el caso DELETE si el proyecto no existe.
    }

    $stmt = $conn->prepare("DELETE FROM proyectos WHERE id = ?");   // Prepara una consulta SQL para eliminar el proyecto con el ID proporcionado de la base de datos.
    $stmt->bind_param("i", $id);    // Vincula el parámetro ID a la consulta preparada para evitar inyecciones SQL.
    
    if (!$stmt->execute()) {    // Si la ejecución de la consulta falla, se devuelve un error.
        file_put_contents('delete.log', "Error: ".$stmt->error."\n", FILE_APPEND);  // Registra el error de ejecución en el archivo de depuración.
        http_response_code(500);    // Establece el código de estado HTTP a 500 (Internal Server Error).
        echo json_encode(["error" => "Error al eliminar: ".$stmt->error]);  // Devuelve un mensaje de error en formato JSON.
        break;  // Termina el caso DELETE si la ejecución de la consulta falla.
    }

    echo json_encode([  // Devuelve un mensaje de éxito en formato JSON, indicando que el proyecto se eliminó correctamente.
        "success" => true, // Indica que la operación se realizó con éxito.     
        "affected_rows" => $stmt->affected_rows 
    ]);
    break;  // Termina el caso DELETE si la ejecución de la consulta es exitosa.
    }
} catch (Exception $e) {    // Captura cualquier excepción que ocurra durante el procesamiento de la solicitud.
    http_response_code(500);    // Establece el código de estado HTTP a 500 (Internal Server Error).
    echo json_encode(["error" => $e->getMessage()]);    // Devuelve un mensaje de error en formato JSON.
}
?>