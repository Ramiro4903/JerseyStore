<?php
require "funciones/conecta.php";
$con = conecta();

// Capturar la variable
$correo = $_REQUEST['correo'];
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

$sql = "SELECT * FROM empleados WHERE correo = ? AND id != ? AND eliminado = 0";
$stmt = $con->prepare($sql);
$stmt->bind_param("si", $correo, $id);
$stmt->execute();
$result = $stmt->get_result();
echo $result->num_rows;
?>
