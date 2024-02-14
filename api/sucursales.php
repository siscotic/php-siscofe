<?php
require_once "../conexion/conexion.php";
$conexion = new Conexion();

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Realizar la consulta SQL para obtener los datos de las sucursales
    $sql = "SELECT * FROM sucursales";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $sucursales = [];
        while ($row = $resultado->fetch_assoc()) {
            $sucursales[] = $row;
        }
        echo json_encode(["success" => true, "datos" => $sucursales]);
    } else {
        echo json_encode(["success" => true, "datos" => []]);
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos del cuerpo de la solicitud
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);

    // Verificar si los datos JSON son válidos
    if (!$data || !isset($data['id']) || !isset($data['nombre']) || !isset($data['id_empresa']) || !isset($data['factura'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Datos JSON incompletos", "success" => false]);
        exit;
    }

    // Obtener los valores de los campos del JSON
    $id = $conexion->real_escape_string($data['id']);
    $nombre = $conexion->real_escape_string($data['nombre']);
    $id_empresa = $conexion->real_escape_string($data['id_empresa']);
    $factura = $conexion->real_escape_string($data['factura']);

    // Preparar la consulta SQL según si se debe insertar o actualizar
    if ($id > 0) {
        // Modificar sucursal existente
        $sql = "UPDATE sucursales SET nombre = '$nombre', id_empresa = '$id_empresa', factura = '$factura' WHERE id = $id";
    } else {
        // Insertar nueva sucursal
        $sql = "INSERT INTO sucursales (nombre, id_empresa, factura) VALUES ('$nombre', '$id_empresa', '$factura')";
    }

    // Ejecutar la consulta SQL
    $conexion->query($sql);

    // Verificar si se afectó alguna fila
    if ($conexion->affected_rows > 0) {
        // Si se insertó una nueva fila, obtener el ID máximo de la tabla sucursales
        if ($id == 0) {
            $sql = "SELECT MAX(id) AS max_id FROM sucursales";
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
        // Error: La consulta no afectó ninguna fila
        echo json_encode(["error" => "La operación no tuvo ningún efecto", "success" => false]);
    }
    
    // Cerrar la conexión a la base de datos
    $conexion->close();
} else {
    // Método no permitido
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Método no permitido", "success" => false]);
}
?>