<?php
session_start();
require "Administrador/funciones/conecta_productos.php";
$con = conecta_productos();

$id_cliente = $_SESSION['idUser'];
$id_producto = $_POST['id_producto'];

$sql = "SELECT id FROM pedidos WHERE id_cliente = $id_cliente AND status = 0 LIMIT 1";
$res = mysqli_query($con, $sql);
$pedido = mysqli_fetch_assoc($res);

if ($pedido) {
    $id_pedido = $pedido['id'];

    // Eliminar el producto del pedido
    $sql = "DELETE FROM pedidos_productos WHERE id_pedido = $id_pedido AND id_producto = $id_producto";
    $res = mysqli_query($con, $sql);

    if ($res) {
        $sql_total = "SELECT SUM(precio * cantidad) AS total FROM pedidos_productos WHERE id_pedido = $id_pedido";
        $res_total = mysqli_query($con, $sql_total);
        $total = mysqli_fetch_assoc($res_total)['total'];

        echo json_encode(array('total' => number_format($total, 2)));
    } else {
        echo json_encode(array('error' => 'Hubo un problema al eliminar el producto.'));
    }
} else {
    echo json_encode(array('error' => 'No se encontró el pedido.'));
}
?>
