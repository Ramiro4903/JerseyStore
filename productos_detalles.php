<?php
session_start();
if (!isset($_SESSION['idUser'])) {
    header("Location: detalles_sinlogin.php");
    exit();
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
$nombreUser = $_SESSION['nombreUser']; 

require "Administrador/funciones/conecta_productos.php";
$con = conecta_productos();

$id = $_GET['id'];
$sql = "SELECT * FROM productos WHERE id = $id AND eliminado = 0";
$result = $con->query($sql);
$producto = $result->fetch_assoc();

if (!$producto) {
    echo "Producto no encontrado.";
    exit;
}

$sql_similares = "SELECT codigo, nombre, costo, archivo, stock FROM productos WHERE eliminado = 0 AND id != $id ORDER BY RAND() LIMIT 3";
$result_similares = $con->query($sql_similares);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <style>
        body {
            background-color: rgb(109, 146, 180);
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
        }

        .product-detail {
            display: flex;
            gap: 20px;
        }

        .photo {
            width: 50%;
            height: 50%;
        }

        .photo img {
            width: 100%;
            height: 100%;
            border-radius: 10px;
        }

        .details {
            flex: 1;
            font-size: 16px;
        }

        .details h1 {
            color: rgb(77, 134, 187);
            margin-bottom: 10px;
        }

        .details span {
            font-weight: bold;
            color: rgb(77, 134, 187);
        }

        .actions {
            margin-top: 20px;
        }

        .cantidad-input {
            width: 60px;
            height: 40px;
            text-align: center;
            margin-right: 10px;
        }

        .cantidad {
            width: 80px;
            height: 80px;
            text-align: center;
            margin-right: 10px;
        }

        .btn {
            background-color: rgb(77, 134, 187);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #3065a2;
            transform: scale(1.01);
        }

        .back-link {
            text-decoration: none;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border: 2px solid rgb(77, 134, 187);
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
            background-color: rgb(77, 134, 187);
            margin-left: 600px;
            top: -350px;
        }

        .back-link:hover {
            border: 2px solid #3065a2;
            background-color: #3065a2;
            color: white;
            transform: scale(1.01);
        }

        .productos-similares {
            margin-top: 50px;
        }

        .productos-similares h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .productos {
            display: flex;
            justify-content: space-around;
            gap: 20px;
        }

        .producto {
            text-align: center;
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 10px;
            width: 250px;
        }

        .producto img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
        }

        .producto h4 {
            font-size: 16px;
            margin: 10px 0;
        }

        .producto p {
            font-size: 14px;
            font-weight: bold;
        }

        .producto .actions {
            margin-top: 10px;
        }

        .producto input {
            width: 50px;
            height: 25px;
            text-align: center;
        }

        .producto .btn {
            margin-top: 5px;
            width: 100%;
        }


    </style>
</head>
<body>
<?php include('menu_usuario.php'); ?>

<div class="container">
    <div class="product-detail">
        <div class="photo">
            <?php if (!empty($producto['archivo'])): ?>
                <img src="Administrador/archivos/<?php echo $producto['archivo']; ?>" alt="Foto del producto">
            <?php endif; ?>
        </div>

        <div class="details">
            <h1><?php echo $producto['nombre']; ?></h1>
            <p><span>Código:</span> <?php echo $producto['codigo']; ?></p> <br>
            <p><span>Descripción:</span> <?php echo $producto['descripcion']; ?></p> <br>
            <p><span>Costo:</span> $<?php echo number_format($producto['costo'], 2); ?></p> <br>
            <p><span>Stock disponible:</span> <?php echo $producto['stock']; ?></p> <br>

            <div class="actions">
            <?php
                echo '<div class="cantidad-container">';
                echo '<input type="number" id="cantidad-' . $producto['codigo'] . '" min="1" max="' . $producto['stock'] . '" value="1">';
                echo '<br>';
                echo '<button class="btn" onclick="agregarAlCarrito(\'' . $producto['codigo'] . '\', document.getElementById(\'cantidad-' . $producto['codigo'] . '\').value)">Agregar al carrito</button>';
                echo '<div id="mensaje-' . $producto['codigo'] . '" class="mensaje" style="display: none; ">Agregado al carrito</div>';
                echo '</div>';
              ?>
            </div>
        </div>
    </div>
    <a href="productos.php" class="back-link">Regresar</a>

    <div class="productos-similares">
    <h3>Otros productos similares</h3>
    <div class="productos">
    <?php while ($producto_similar = $result_similares->fetch_assoc()): ?>
        <div class="producto">
            <img src="Administrador/archivos/<?php echo $producto_similar['archivo']; ?>" alt="<?php echo $producto_similar['nombre']; ?>">
            <h4><?php echo $producto_similar['nombre']; ?></h4>
            <p>$<?php echo number_format($producto_similar['costo'], 2); ?></p>
            <div class="actions">
                <div class="cantidad-container">
                    <input type="number" id="cantidad-<?php echo $producto_similar['codigo']; ?>" min="1" max="<?php echo $producto_similar['stock']; ?>" value="1">
                    <button class="btn" onclick="agregarAlCarrito('<?php echo $producto_similar['codigo']; ?>', document.getElementById('cantidad-<?php echo $producto_similar['codigo']; ?>').value)">Agregar al carrito</button>
                    <div id="mensaje-<?php echo $producto_similar['codigo']; ?>" class="mensaje" style="display: none; color: green;">Agregado al carrito</div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</div>


</div>



<?php include('pie_pagina.php'); ?>
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
                $("#cantidad-" + codigo).val(1);  
            }
        });
   
    }
</script>

</body>
</html>
