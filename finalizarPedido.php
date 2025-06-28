<?php
require "Administrador/funciones/conecta_productos.php";
$con = conecta_productos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'];

    $sql = "UPDATE pedidos SET status = 1 WHERE id = $id_pedido";
    if (mysqli_query($con, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
