<?php
require "funciones/conecta_productos.php";
$con = conecta_productos();

// Capturar la variable
$codigo = $_REQUEST['codigo'];
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

$sql = "SELECT * FROM productos WHERE codigo = ? AND id != ? AND eliminado = 0";
$stmt = $con->prepare($sql);
$stmt->bind_param("si", $codigo, $id);
$stmt->execute();
$result = $stmt->get_result();
echo $result->num_rows;
?>
