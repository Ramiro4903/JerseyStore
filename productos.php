<?php
session_start();
$logueado = isset($_SESSION['nombreUser']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Tienda</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        /* Retícula de productos */
        .productos {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* Cuatro columnas */
            gap: 20px;
            margin: 20px auto;
            width: 90%;
        }

        .producto {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }

        .producto img {
            width: 150px;
            height: auto;
            margin-bottom: 10px;
        }

        .producto h3 {
            font-size: 14px;
            margin: 5px 0;
            color: #000;
        }

        .producto p {
            font-size: 12px;
            color: gray;
        }

        .producto a {
            color: inherit;
            text-decoration: none;
        }

        .producto a:hover {
            text-decoration: underline;
        }

        .producto button {
            display: inline-block;
            margin-top: 10px;
            color: white;
            background-color: blue;
            padding: 5px 10px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .producto button:hover {
            background-color: #526fc6;
        }

        .cantidad-input {
            margin-top: 5px;
            width: 60px;
            text-align: center;
            margin-right: 5px;
        }

        .mensaje {
            color: red;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }
    </style>
    <script>
    function agregarAlCarrito(codigo, cantidad) {
        $.ajax({
            url: 'insertarProductos.php',
            type: 'post',
            dataType: 'text',
            data: {
                codigo: codigo,
                cantidad: cantidad
            },
            success: function(res) {
                $("#mensaje-" + codigo).text("Agregado al carrito").fadeIn().delay(5000).fadeOut();
                $("#cantidad-" + codigo).val(1);  // Resetea la cantidad a 1 después de agregar al carrito
            }
        });
    }
    </script>
</head>
<body>
<?php
include('menu_usuario.php');
?>
<div class="productos">
    <?php
    include('Administrador/funciones/conecta_productos.php');
    $conexion = conecta_productos();

    // Consulta de productos
    $productosQuery = "SELECT id, nombre, codigo, costo, archivo, stock FROM productos WHERE status = 1 AND eliminado = 0";
    $resultadoProductos = $conexion->query($productosQuery);

    // Mostrar productos
    if ($resultadoProductos && $resultadoProductos->num_rows > 0) {
        while ($producto = $resultadoProductos->fetch_assoc()) {
            echo '<div class="producto" data-codigo="' . $producto['codigo'] . '">';

            // Redirigir a detalles según si está logueado o no
            $link = $logueado ? "productos_detalles.php?id=" . $producto['id'] : "detalles_sinlogin.php?id=" . $producto['id'];
            echo '<a href="' . $link . '">';
            echo '<img src="Administrador/archivos/' . $producto['archivo'] . '" alt="' . $producto['nombre'] . '">';
            echo '</a>';

            echo '<a href="' . $link . '">';
            echo '<h3>' . $producto['nombre'] . '</h3>';
            echo '</a>';

            echo '<p>Código: ' . $producto['codigo'] . '</p>';
            echo '<p>$' . number_format($producto['costo'], 2) . '</p>';
            echo '<p>Stock: ' . number_format($producto['stock']) . '</p>';

            if ($logueado) {
                echo '<label for="cantidad-' . $producto['codigo'] . '" input="cantidad"></label>';
                echo '<input type="number" id="cantidad-' . $producto['codigo'] . '" value="1" min="1" max="' . $producto['stock'] . '" />';
                echo '<button onclick="agregarAlCarrito(\'' . $producto['codigo'] . '\', document.getElementById(\'cantidad-' . $producto['codigo'] . '\').value)">Agregar al carrito</button>';
                echo '<div id="mensaje-' . $producto['codigo'] . '" style="display: none; padding: 10px; background-color: #dff0d8; color: #3c763d; margin-top: 10px; border-radius: 5px;"></div>';
            }

            echo '</div>';
        }
    } else {
        echo '<p>No hay productos disponibles en este momento.</p>';
    }
    ?>
</div>

<div class="mensaje" id="mensaje"></div>
<?php
include('pie_pagina.php');
?>
</body>
</html>
