<?php
session_start();
require "Administrador/funciones/conecta_productos.php";
$con = conecta_productos();

$id_cliente = $_SESSION['idUser'];
$id_producto = $_POST['id_producto'];
$cantidad = $_POST['cantidad'];

if ($cantidad <= 0) {
    echo json_encode(['error' => 'La cantidad debe ser mayor que cero.']);
    exit;
}

$sql = "SELECT id FROM pedidos WHERE id_cliente = $id_cliente AND status = 0 LIMIT 1";
$res = mysqli_query($con, $sql);
$pedido = mysqli_fetch_assoc($res);
$id_pedido = $pedido['id'];

$sql = "UPDATE pedidos_productos 
        SET cantidad = $cantidad 
        WHERE id_producto = $id_producto 
          AND id_pedido = $id_pedido";
mysqli_query($con, $sql);

$sql = "SELECT pedidos_productos.precio * pedidos_productos.cantidad AS subtotal 
        FROM pedidos_productos 
        WHERE id_producto = $id_producto 
          AND id_pedido = $id_pedido";
$res = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($res);
$subtotal = $row['subtotal'];

$sql = "SELECT SUM(pedidos_productos.precio * pedidos_productos.cantidad) AS total 
        FROM pedidos_productos 
        WHERE id_pedido = $id_pedido";
$res = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($res);
$total = $row['total'];

echo json_encode([
    'subtotal' => $subtotal,
    'total' => $total,
]);
?>
