<?php
require_once "./conexion/conexion.php";
$conexion = new Conexion();

// Obtener los parámetros de la URL
$clave1 = $_GET['clave1'] ?? '';
$clave2 = $_GET['clave2'] ?? '';

// Validar que se hayan proporcionado ambos parámetros
if (empty($clave1) || empty($clave2)) {
    // Redireccionar a una página de error si falta alguno de los parámetros
    header("Location: error.php");
    exit;
}

// Consultar la tabla dte para obtener el QR
$sql = "SELECT qr FROM dte WHERE clave1 = ? AND clave2 = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $clave1, $clave2);
$stmt->execute();
$stmt->bind_result($qr);
$stmt->fetch();
$stmt->close();

// Redireccionar al QR si se encontró uno
if (!empty($qr)) {
    header("Location: $qr");
    exit;
} else {
    // Redireccionar a una página de error si no se encontró un QR
    header("Location: error.php");
    exit;
}
?>
