<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "conexion/conexion.php";

// Crear una instancia de la clase Conexion
$conexion = new Conexion();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = file_get_contents("php://input");
    var_dump($data);

    // Escapar los datos del JSON si es necesario
    $escapedData = $conexion->real_escape_string($data);

    $query = "INSERT INTO webhooks (webhook_data) VALUES ('$escapedData')";

    if ($conexion->executeUpdate($query) > 0) {
        echo "Datos JSON guardados correctamente en la base de datos";
    } else {
        echo "Error al guardar los datos JSON en la base de datos";
    }
} else {
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $mensaje = "METODO GET DE PRUEBA";
        $ipCliente = $_SERVER['REMOTE_ADDR'];
        $errorQuery = "INSERT INTO webhooks (webhook_data) VALUES ('$mensaje IP: $ipCliente')";
        $filasAfectadas = $conexion->executeUpdate($errorQuery);
        echo "FUNCIONANDO MI REY";
    } else {
        echo "METODO NO PERMITIDO";
    }
}
?>