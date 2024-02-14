<?php
require_once "../conexion/conexion.php";
$conexion = new Conexion();

header("Content-Type: application/json");
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        // Realizar una consulta SQL para obtener los datos solicitados
        $sql = "SELECT E.id AS id_empresa, E.nombre AS empresa_nombre, S.id AS id_sucursal, S.nombre AS sucursal_nombre, ult_fechahora as ultimaconexion
                FROM empresas AS E
                INNER JOIN sucursales AS S ON E.id = S.id_empresa
                INNER JOIN estado AS ES on ES.id_sucursal  = S.id_empresa";

        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        http_response_code(200); // OK
        echo json_encode($data);
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Error en el servidor"]);
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {


    // Verifica si se recibió un JSON válido en la solicitud POST
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);

    if (!$data) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "JSON no válido"]);
        exit;
    }

    // Conecta a la base de datos y realiza la operación de registro o actualización según sea necesario
    try {
        // Verifica si la sucursal ya existe en la tabla estado
        $stmt = $conexion->prepare("SELECT id FROM estado WHERE id_sucursal = ?");
        $stmt->bind_param("i", $data->id_sucursal);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // La sucursal ya existe, realiza una actualización
            $stmt = $conexion->prepare("UPDATE estado SET ult_fechahora = CURRENT_TIMESTAMP WHERE id_sucursal = ?");
            $stmt->bind_param("i", $data->id_sucursal);
            $stmt->execute();
        } else {
            // La sucursal no existe, realiza un registro
            $stmt = $conexion->prepare("INSERT INTO estado (id_sucursal, ult_fechahora) VALUES (?, CURRENT_TIMESTAMP)");
            $stmt->bind_param("i", $data->id_sucursal);
            $stmt->execute();
        }

        $stmt = $conexion->prepare("SELECT value FROM datos WHERE clave = 'PL'");
        $stmt->execute();
        $stmt->bind_result($valor_retornado);
        $stmt->fetch();

        // Cambia el mensaje de respuesta final
        http_response_code(200); // OK
        echo json_encode(["PL" => ($valor_retornado === "true"), "message" => "Operación exitosa"]);

    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Error en el servidor"]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Método no permitido"]);
}
?>