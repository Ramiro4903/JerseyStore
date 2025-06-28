<?php
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

$sql_similares = "SELECT id, nombre, costo, archivo, stock FROM productos WHERE eliminado = 0 AND id != $id ORDER BY RAND() LIMIT 3";
$result_similares = $con->query($sql_similares);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
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

        .similar-products {
            margin-top: 40px;
            text-align: center;
            margin-left: 10px;
        }

        .productos-similares {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-left: 10px;
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
            height: auto;
            margin-bottom: 10px;
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
        </div>
    </div>

    <a href="productos.php" class="back-link">Regresar</a>

    <div class="similar-products">
        <h3>Productos Similares</h3>
        <div class="productos-similares">
        <?php while ($producto_similar = $result_similares->fetch_assoc()): ?>
            <div class="producto">
                <img src="Administrador/archivos/<?php echo $producto_similar['archivo']; ?>" alt="<?php echo $producto_similar['nombre']; ?>">
                <h4><?php echo $producto_similar['nombre']; ?></h4>
                <p>$<?php echo number_format($producto_similar['costo'], 2); ?></p>
            </div>
        <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include('pie_pagina.php'); ?>
</body>
</html>
