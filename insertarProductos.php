<?php
session_start();
require "Administrador/funciones/conecta_productos.php";
$codigo = $_REQUEST['codigo'];
$cantidad = $_REQUEST['cantidad'];

$con = conecta_productos();
$id_cliente = $_SESSION['idUser'];

// Verificar si el usuario está logueado
if (!isset($_SESSION['nombreUser'])) {
    header("Location: index.php");
    exit();
}

// Verificar si ya existe un pedido abierto para el cliente
$sql = "SELECT * FROM pedidos WHERE id_cliente = $id_cliente AND status = 0";
$res = $con->query($sql);
$pedido = $res->fetch_assoc();

if (!$pedido) {
    // Si no existe un pedido abierto, crear uno nuevo con la fecha actual
    $sql = "INSERT INTO pedidos (id_cliente, fecha) VALUES ($id_cliente, NOW())";
    $con->query($sql);
    $pedido = $res->fetch_assoc();
}
//Recupera id_ de pedido
$id_pedido = $pedido['id'];

// Buscar el id de producto con el código
$sql = "SELECT * FROM productos WHERE codigo = $codigo";
$res = $con->query($sql);
$producto = $res->fetch_assoc();
$id_producto = $producto['id'];
// Obtener el costo unitario del producto
$costo_unitario = $producto['costo'];


// Verificar si el producto ya está en el pedido
$sql = "SELECT * FROM pedidos_productos WHERE id_pedido = $id_pedido AND id_producto = $id_producto";
$res = $con->query($sql);

// Verificar si se encuentra el producto en el pedido
if ($res && $res->num_rows > 0) {
    $pedidos_productos = $res->fetch_assoc();

    // Obtener la cantidad existente
    $cantidad_n = $pedidos_productos['cantidad']; 

    // Sumar la nueva cantidad al carrito
    $cantidad = $cantidad + $cantidad_n;

    // Calcular el nuevo subtotal (el precio no cambia, solo la cantidad)
    $nuevo_subtotal = $costo_unitario + $cantidad - $cantidad;

    // Actualizar la cantidad y el subtotal en la base de datos
    $sql = "UPDATE pedidos_productos SET cantidad = $cantidad, precio = $nuevo_subtotal WHERE id_pedido = $id_pedido AND id_producto = $id_producto";
    $con->query($sql);

} else {
    // Si no existe el producto en el pedido, insertar el nuevo producto
    $subtotal = $costo_unitario + $cantidad - $cantidad;

    $sql_insert = "INSERT INTO pedidos_productos (id_pedido, id_producto, cantidad, precio) VALUES ($id_pedido, $id_producto, $cantidad, $subtotal)";
    $con->query($sql_insert);
}

$con->close();
echo 1;
?>
