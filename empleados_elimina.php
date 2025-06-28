<?php
// empleados elimina
require "funciones/conecta.php";
$con = conecta();

$id = $_REQUEST['id'];

$sql = "UPDATE empleados SET eliminado = 1 WHERE id = $id";
$res = $con->query($sql);

// Responder en formato JSON
$response = array();
if ($res) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

header('Content-Type: application/json'); 
echo json_encode($response); 
?>
