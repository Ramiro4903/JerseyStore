<?php
require_once 'conecta_clientes.php';

$con = conecta_clientes();
if (!$con) {
    die("Error en la conexión a la base de datos");
}

$id_cliente = intval($_POST['id_cliente']); // Asegúrate de recibir este dato desde el formulario

// Verificar si ya existe un pedido abierto para este cliente
$sql = "SELECT id FROM pedidos WHERE id_cliente = $id_cliente AND status = 0";
$res = $con->query($sql);

if ($res->num_rows > 0) {
    // Pedido existente
    $pedido = $res->fetch_assoc();
    $id_pedido = $pedido['id'];
} else {
    // Crear un nuevo pedido
    $sql = "INSERT INTO pedidos (id_cliente, status) VALUES ($id_cliente, 0)";
    if ($con->query($sql)) {
        $id_pedido = $con->insert_id;
    } else {
        die("Error al crear el pedido: " . $con->error);
    }
}

// Retornar el id del pedido al cliente o redirigir según tu lógica
echo json_encode(['id_pedido' => $id_pedido]);
?>
