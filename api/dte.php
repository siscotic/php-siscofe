<?php
require_once "../conexion/conexion.php";
$conexion = new Conexion();

header("Content-Type: application/json");

$headers = getallheaders();

// Verificar si la cabecera Authorization está presente
if (!isset($headers['Authorization']) || empty($headers['Authorization'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "Se requiere una cabecera de autorización", "success" => false]);
    exit;
}

$authorizationHeader = $headers['Authorization'];

if (!preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
    http_response_code(401);
    echo json_encode(["error" => "Formato de token inválido", "success" => false]);
    exit;
}

$apiKey = isset($matches[1]) ? $matches[1] : null;

if (!$apiKey) {
    http_response_code(401); 
    echo json_encode(["error" => "No se encontró un token de autorización válido", "success" => false]);
    exit;
}

$apiKey = $matches[1];

// Consultar la tabla empresas para validar el API key y obtener el id_empresa correspondiente
$sql = "SELECT id FROM empresas WHERE api_key = ?";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo "Error al preparar la consulta: " . $conexion->error;
    exit;
}
$stmt->bind_param("s", $apiKey);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($result->num_rows != 1) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "API key no válida", "success" => false]);
    exit;
}

$idEmpresaFromApiKey = $row['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);

    if (!$data || !isset($data['datos']) || !is_array($data['datos'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "JSON no válido o falta la lista de datos", "success" => false]);
        exit;
    }

    // Verificar si el id_empresa del JSON coincide con el id_empresa obtenido del API key
    if (!isset($data['id_empresa']) || $data['id_empresa'] != $idEmpresaFromApiKey) {
        http_response_code(401); // Unauthorized
        echo json_encode(["error" => "El id_empresa proporcionado no coincide con el API key", "success" => false]);
        exit;
    }

    $id_sucursal = $conexion->real_escape_string($data['id_sucursal']);
    $id_empresa = $conexion->real_escape_string($data['id_empresa']);
    $values = [];

    // Construir la cadena de valores para la inserción múltiple
    foreach ($data['datos'] as $dato) {
        $clave1 = $conexion->real_escape_string($dato['clave1']);
        $clave2 = $conexion->real_escape_string($dato['clave2']);
        $qr = $conexion->real_escape_string($dato['qr']);
        $values[] = "('$id_sucursal', '$clave1', '$clave2', '$qr')";
    }

    // Unir los valores con comas para la consulta SQL
    $valuesString = implode(', ', $values);
    
    // Construir la consulta SQL de inserción múltiple
    $sql = "INSERT INTO dte (id_sucursal, clave1, clave2, qr) VALUES $valuesString 
            ON DUPLICATE KEY UPDATE qr = VALUES(qr)";

    if ($conexion->query($sql) === TRUE) {
        echo json_encode(["message" => "Datos insertados correctamente", "success" => true]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Error al insertar datos: " . $conexion->error, "success" => false]);
    }

    $conexion->close();
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Método no permitido", "success" => false]);
}
?>
