<?php
session_start();
$nombreUser = $_SESSION['nombreUser'];
// veo si usuario no tiene sesion activa
if (!isset($_SESSION['nombreUser'])) {
    header("Location: index.php");
    exit();
}
require "funciones/conecta_productos.php";
$con = conecta_productos();

$id_cliente = $_SESSION['idUser'];

$sql = "SELECT * FROM pedidos WHERE id_cliente = $id_cliente AND status = 1";
$res = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pedidos Cerrados</title>
    <style>
        body {
            background-color: rgb(109, 146, 180);
            display: flex;
            margin-top: 50px;
        }

        .pedidos {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            background-color: rgb(255, 255, 255);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 900px;
            padding: 20px;
        }

        .pedidos h1 {
            color: rgb(77, 134, 187);
        }

        .tabla {
            width: 100%;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }

        .fila {
            display: flex;
            border-bottom: 1px solid #ccc;
        }

        .fila.cabecera {
            background-color: rgb(77, 134, 187);
            color: white;
            font-weight: bold;
        }

        .fila:nth-child(even) {
            background-color: rgb(230, 230, 230);
        }

        .fila:hover {
            background-color: rgb(200, 200, 255);
        }

        .celda {
            flex: 1;
            padding: 10px;
            text-align: center;
        }

        button {
            display: inline-block;
            background-color: rgb(77, 134, 187);
            color: white;
            padding: 10px 20px;
            border-radius: 5px ;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            border: 2px solid rgb(77, 134, 187);
            cursor: pointer;
        }

        button:hover {
            transform: scale(1.03);
            background-color: rgb(50, 100, 150);
            border: 2px solid rgb(50, 100, 150);
        }
    </style>
</head>
<body>
<?php include('menu.php'); ?>
    <div class="pedidos">
        <h1>Pedidos Cerrados</h1>
        <div class="tabla">
            <div class="fila cabecera">
                <div class="celda">ID Pedido</div>
                <div class="celda">Fecha</div>
                <div class="celda">Total</div>
                <div class="celda">Acción</div>
            </div>
            <?php while ($pedido = mysqli_fetch_assoc($res)): 
                $id_pedido = $pedido['id'];
                $sql_detalle = "SELECT SUM(pedidos_productos.precio * pedidos_productos.cantidad) AS total 
                                FROM pedidos_productos 
                                WHERE pedidos_productos.id_pedido = $id_pedido";
                $res_detalle = mysqli_query($con, $sql_detalle);
                $detalle = mysqli_fetch_assoc($res_detalle);
                $total_pedido = $detalle['total'];
            ?>
                <div class="fila">
                    <div class="celda"><?php echo $pedido['id']; ?></div>
                    <div class="celda"><?php echo date("d/m/Y", strtotime($pedido['fecha'])); ?></div>
                    <div class="celda"><?php echo number_format($total_pedido, 2); ?></div>
                    <div class="celda">
                        <button onclick="verDetalle(<?php echo $pedido['id']; ?>)">Ver Detalle</button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script>
        function verDetalle(id_pedido) {
            window.location.href = "detalle_pedido.php?id_pedido=" + id_pedido;
        }
    </script>
</body>
</html>
