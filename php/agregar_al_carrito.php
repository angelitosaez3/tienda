<?php
require_once("../config/config.php");
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['sesion_personal'])) {
    header("Location: ./iniciar_sesion.php");
    exit();
}

$id_producto = $_GET["id_producto"];
$cantidad_seleccionada = $_GET["cantidad"];
$id_usuario = $_SESSION['sesion_personal']['id'];

// Hacer conexión a la base de datos
$con = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);

// Verificar la conexión con la BD
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Hacer inserción en la base de datos
$result = mysqli_query($con, "INSERT INTO carrito (id_producto, id_usuario, cantidad_seleccionada) VALUES ($id_producto, $id_usuario, $cantidad_seleccionada)");
mysqli_close($con);

// Redirigir después de la inserción
header('Location: ./carrito.php');
exit();
?>
