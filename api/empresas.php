<?php
require_once "../conexion/conexion.php";
$conexion = new Conexion();

header("Content-Type: application/json");
if ($_SERVER["REQUEST_METHOD"] === "GET") {

    // Realizar la consulta SQL para obtener los datos de las empresas
    $sql = "SELECT id, nombre, ruc, timbrado FROM empresas";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $empresas = [];
        while ($row = $resultado->fetch_assoc()) {
            $empresas[] = $row;
        }
        echo json_encode(["success" => true, "datos" => $empresas]);
    } else {
        echo json_encode(["success" => true, "datos" => []]);
    }

    $conexion->close();
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);

    if (!$data || !isset($data['id']) || !isset($data['nombre']) || !isset($data['ruc']) || !isset($data['timbrado']) || !isset($data['api_key'])) {
        http_response_code(400);
        echo json_encode(["error" => "Datos JSON incompletos", "success" => false]);
        exit;
    }

    // Obtener los valores de los campos del JSON
    $id = $conexion->real_escape_string($data['id']);
    $nombre = $conexion->real_escape_string($data['nombre']);
    $ruc = $conexion->real_escape_string($data['ruc']);
    $timbrado = $conexion->real_escape_string($data['timbrado']);
    $api_key = $conexion->real_escape_string($data['api_key']);

    // Preparar la consulta SQL según si se debe insertar o actualizar
    if ($id > 0) {
        // Modificar empresa existente
        $sql = "UPDATE empresas SET nombre = '$nombre', ruc = '$ruc', timbrado = '$timbrado', api_key = '$api_key' WHERE id = $id";
    } else {
        // Insertar nueva empresa
        $sql = "INSERT INTO empresas (nombre, ruc, timbrado, api_key) VALUES ('$nombre', '$ruc', '$timbrado', '$api_key')";
    }

    // Ejecutar la consulta SQL
    $conexion->query($sql);

    // Verificar si se afectó alguna fila
    if ($conexion->affected_rows > 0) {
        // Si se insertó una nueva fila, obtener el ID máximo de la tabla sucursales
        if ($id == 0) {
            $sql = "SELECT MAX(id) AS max_id FROM empresas";
            $result = $conexion->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $sucursalId = $row['max_id'];
            }
        } else {
            // Si se actualizó una fila existente, utilizar el ID existente
            $sucursalId = $id;
        }

        // Éxito: La consulta afectó al menos una fila
        echo json_encode(["id" => $sucursalId, "message" => "Operación realizada correctamente", "success" => true]);
    } else {
        if ($id == 0) {
            echo json_encode(["error" => "La operación no tuvo ningún efecto", "success" => false]);
        } else {
            echo json_encode(["id" => $id, "message" => "Operación realizada correctamente", "success" => true]);
        }
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
} else if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    // Leer los datos JSON del cuerpo de la solicitud
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);

    // Verificar si se proporcionó el id
    if (!isset($data['id'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "ID no proporcionado", "success" => false]);
        exit;
    }

    // Obtener el id de la empresa a eliminar
    $id = $conexion->real_escape_string($data['id']);

    // Consulta SQL para eliminar la empresa
    $sql = "DELETE FROM empresas WHERE id = $id";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql)) {
        // Éxito: la operación se realizó correctamente
        echo json_encode(["message" => "Empresa eliminada correctamente", "success" => true]);
    } else {
        // Error: la operación no se realizó correctamente
        echo json_encode(["error" => "Error al eliminar la empresa", "success" => false]);
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
} else {
    // Método no permitido
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Método no permitido", "success" => false]);
}
?>