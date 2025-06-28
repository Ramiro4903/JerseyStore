<?php
session_start();
if (!isset($_SESSION['idUser'])) {
    header("Location: index.php");
    exit();
}

require "Administrador/funciones/conecta_productos.php";
$con = conecta_productos();

$id_cliente = $_SESSION['idUser'];

// Verificar si hay un pedido abierto
$sql = "SELECT * FROM pedidos WHERE id_cliente = $id_cliente AND status = 0 LIMIT 1";
$res = mysqli_query($con, $sql);
$pedido = mysqli_fetch_assoc($res);


if (!$pedido) {
    header("Location: carritoVacio.php");
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        .celda input {
            padding: 5px 10px;
        }

        .celda button {
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 6px;
        }

        .celda button:hover {
            background-color: darkred;
        }

        .total {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .continuar a {
            display: inline-block;
            background-color: rgb(77, 134, 187);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .continuar a:hover {
            background-color: rgb(50, 100, 150);
            transform: scale(1.10);
        }
    </style>
        <script>
       function eliminarProducto(id_producto) {
    if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
        $.ajax({
            url: 'eliminarProducto.php',
            type: 'POST',
            dataType: 'json',
            data: {
                id_producto: id_producto
            },
            success: function(res) {
                if (res.error) {
                    console.log("error");
                } else {
                    // Eliminar el producto de la lista
                    $("#producto-" + id_producto).remove();

                    // Actualizar el total
                    $("#total").text(res.total);
                }
            },
        });
    }
}

        function actualizarCantidad(id_producto, cantidad, inputElement) {
    if (cantidad <= 0) {
        inputElement.value = 1; 
        cantidad = 1; 
    }

    $.ajax({
        url: 'actualizarCantidad.php',
        type: 'POST',
        dataType: 'json',
        data: {
            id_producto: id_producto,
            cantidad: cantidad
        },
        success: function(response) {
            if (response.error) {
                console.log(response.error); 
            } else {
                // Convertir los valores a números antes de mostrarlos
                var subtotal = parseFloat(response.subtotal);
                var total = parseFloat(response.total);

                // Actualizar el subtotal del producto en la tabla
                $("#subtotal-" + id_producto).text(subtotal.toFixed(2));

                // Actualizar el total en la parte inferior
                $("#total").text(total.toFixed(2));
            }
        },
    });
}

    </script>
</head>
<body>
<?php
include('menu_usuario.php');
?>
    <div class="carrito">
        <h1>Carrito de Compras</h1>
        <div class="tabla">
            <div class="fila cabecera">
                <div class="celda">Producto</div>
                <div class="celda">Precio</div>
                <div class="celda">Cantidad</div>
                <div class="celda">Subtotal</div>
                <div class="celda">Acciones</div>
            </div>
            <?php while ($row = mysqli_fetch_assoc($res)): 
                $total += $row['subtotal']; ?>
                <div class="fila" id="producto-<?php echo $row['id_producto']; ?>">
    <div class="celda"><?php echo $row['nombre']; ?></div>
    <div class="celda"><?php echo number_format($row['precio'], 2); ?></div>
    <div class="celda">
    <input type="number" value="<?php echo $row['cantidad']; ?>" 
       onblur="actualizarCantidad(<?php echo $row['id_producto']; ?>, this.value, this)">

    </div>
    <div class="celda" id="subtotal-<?php echo $row['id_producto']; ?>">
        <?php echo number_format($row['subtotal'], 2); ?>
    </div>
    <div class="celda">
        <button onclick="eliminarProducto(<?php echo $row['id_producto']; ?>)">Eliminar</button>
    </div>
</div>
            <?php endwhile; ?>
        </div>
        <div class="total">
            Total: $<span id="total"><?php echo number_format($total, 2); ?></span>
        </div>
        <div class="continuar">
            <br>
            <a href="carrito02.php">Continuar</a>
        </div>
    </div>
    <?php
include('pie_pagina.php');
?>
</body>
</html>
