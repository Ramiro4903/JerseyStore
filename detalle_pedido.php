<?php
session_start();
$nombreUser = $_SESSION['nombreUser'];
if (!isset($_SESSION['nombreUser'])) {
    header("Location: index.php");
    exit();
}
require "funciones/conecta_productos.php";
$con = conecta_productos();

$id_cliente = $_SESSION['idUser'];
$id_pedido = $_GET['id_pedido'];

$sql = "SELECT * FROM pedidos WHERE id_cliente = $id_cliente AND id = $id_pedido AND status = 1";
$res = mysqli_query($con, $sql);
$pedido = mysqli_fetch_assoc($res);

if (!$pedido) {
    echo "<p>Pedido no encontrado.</p>";
    exit;
}

// Obtener los productos del pedido
$sql_productos = "SELECT productos.nombre, pedidos_productos.precio, 
                          pedidos_productos.cantidad, 
                          (pedidos_productos.precio * pedidos_productos.cantidad) AS subtotal
                   FROM pedidos_productos
                   JOIN productos ON pedidos_productos.id_producto = productos.id
                   WHERE pedidos_productos.id_pedido = $id_pedido";
$res_productos = mysqli_query($con, $sql_productos);

$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detalle del Pedido</title>
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
            flex: 2;  
            padding: 15px; 
            text-align: center;
        }

        .total {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
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
            transform: scale(1.05);
            background-color: rgb(50, 100, 150);
            border: 2px solid rgb(50, 100, 150);
        }
    </style>
    <script>
         function regresar() {
            window.location.href = "pedidos.php";
        }
    </script>
</head>
<body>
<?php include('menu.php'); ?>
    <div class="pedidos">
        <h1>Detalles del Pedido #<?php echo $pedido['id']; ?></h1>
        <button type="button" onclick="regresar();">Regresar</button>
        <div class="tabla">
            <div class="fila cabecera">
                <div class="celda">Producto</div>
                <div class="celda">Precio</div>
                <div class="celda">Cantidad</div>
                <div class="celda">Subtotal</div>
            </div>
            <?php while ($row = mysqli_fetch_assoc($res_productos)): 
                $total += $row['subtotal']; ?>
                <div class="fila">
                    <div class="celda"><?php echo $row['nombre']; ?></div>
                    <div class="celda"><?php echo number_format($row['precio'], 2); ?></div>
                    <div class="celda"><?php echo $row['cantidad']; ?></div>
                    <div class="celda"><?php echo number_format($row['subtotal'], 2); ?></div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="total">
            Total: $<span id="total"><?php echo number_format($total, 2); ?></span>
        </div>
    </div>
</body>
</html>
