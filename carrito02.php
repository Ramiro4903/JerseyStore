<?php
session_start();
if (!isset($_SESSION['idUser'])) {
    header("Location: index.php");
    exit();
}
require "Administrador/funciones/conecta_productos.php";
$con = conecta_productos();

$id_cliente = $_SESSION['idUser'];

$sql = "SELECT * FROM pedidos WHERE id_cliente = $id_cliente AND status = 0 LIMIT 1";
$res = mysqli_query($con, $sql);
$pedido = mysqli_fetch_assoc($res);

if (!$pedido) {
    echo "<p>El carrito está vacío.</p>";
    exit;
}

$id_pedido = $pedido['id'];

$sql = "SELECT pedidos_productos.id_producto, productos.nombre, 
               pedidos_productos.precio, pedidos_productos.cantidad, 
               (pedidos_productos.precio * pedidos_productos.cantidad) AS subtotal 
        FROM pedidos_productos 
        JOIN proyecto.productos ON pedidos_productos.id_producto = proyecto.productos.id 
        WHERE pedidos_productos.id_pedido = $id_pedido";
$res = mysqli_query($con, $sql);
$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Carrio - Tienda</title>
    <style>
        body {
            background-color: rgb(109, 146, 180);
            display: flex;
            margin-top: 50px;
        }

        .carrito {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            background-color: rgb(255, 255, 255);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 900px;
            padding: 20px;
        }

        .carrito h1 {
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

        .total {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .continuar a, .boton-finalizar {
            display: inline-block;
            background-color: rgb(77, 134, 187);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            cursor: pointer;
        }

        .continuar a:hover, .boton-finalizar:hover {
            transform: scale(1.10);
            background-color: rgb(50, 100, 150);
        }

        .mensaje-exito {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #4CAF50;
            background-color: #DFF2BF;
            color: #4CAF50;
            font-weight: bold;
            display: none;
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

        button:hover{
            transform: scale(1.05);
            background-color: rgb(50, 100, 150);
            border: 2px solid rgb(50, 100, 150);
        }

    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function regresar() {
            window.location.href = "carrito01.php";
        }

        function finalizarPedido() {
            if (confirm("¿Desea finalizar el pedido?")) {
                $.ajax({
                    url: "finalizarPedido.php",
                    type: "POST",
                    data: { id_pedido: <?php echo $id_pedido; ?> },
                    success: function (response) {
                        if (response == "success") {
                            document.getElementById("mensaje-exito").style.display = "block";
                            setTimeout(function () {
                                window.location.href = "index.php";
                            }, 3000);
                        } else {
                            console.log('error');
                        }
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            }
        }
    </script>
</head>
<body>
<?php
include('menu_usuario.php');
?>
    <div class="carrito">
        <h1>Resumen del Pedido</h1>
        <br>
        <button type="button" onclick="regresar();">Regresar</button>
        <div class="tabla">
            <div class="fila cabecera">
                <div class="celda">Producto</div>
                <div class="celda">Precio</div>
                <div class="celda">Cantidad</div>
                <div class="celda">Subtotal</div>
            </div>
            <?php while ($row = mysqli_fetch_assoc($res)): 
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
        <div class="continuar">
            <br>
            <button class="boton-finalizar" onclick="finalizarPedido();">Finalizar Pedido</button>
        </div>
        <div id="mensaje-exito" class="mensaje-exito">El pedido fue finalizado con éxito.</div>
    </div>
    <?php
include('pie_pagina.php');
?>
</body>
</html>
