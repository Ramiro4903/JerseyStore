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

$id = $_GET['id'];
$sql = "SELECT * FROM productos WHERE id = $id AND eliminado = 0";
$result = $con->query($sql);
$producto = $result->fetch_assoc();

if (!$producto) {
    echo "Producto no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <style>
        body {
            background-color: rgb(109, 146, 180);
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;

        }

        .container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 1200px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .product-card {
            background-color: rgb(230, 230, 230);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s, background-color 0.3s;
        }

        .photo {
            width: 400px; /* Tamaño fijo en píxeles */
            height: 500px;
            max-height: 500px; /* Opcional: Limita la altura máxima */
            margin-left: 40px;
        }

        .product-card:hover {
            transform: scale(1.05);
            background-color: rgb(77, 134, 187);
            color: white;
        }

        .product-card img {
            width: 80%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product-card span {
            font-weight: bold;
            color: rgb(77, 134, 187);
        }

        .product-card:hover span {
            color: white;
        }

        .product-card p {
            margin: 5px 0;
        }

        .back-link {
            text-decoration: none;
            color: rgb(77, 134, 187);
            font-size: 16px;
            display: block;
            text-align: center;
            margin: 20px auto;
            padding: 10px 20px;
            border: 2px solid rgb(77, 134, 187);
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
            width: fit-content;
        }
        .detail{
            font-size: 20px;
        }

        .detail.nombre{
            font-size: 100px;
        }

        .back-link:hover {
            background-color: green;
            color: white;
            border-color: green;
            transform: scale(1.0);
        }

        a {
            text-decoration: none;
            color: white; 
            font-size: 16px;
            display: inline-block; 
            text-align: center;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: rgb(77, 134, 187); 
            border: 2px solid rgb(77, 134, 187); 
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s, border-color 0.3s; 
            cursor: pointer; 
            width: fit-content;
        }

        a:hover {
            background-color: green; 
            color: white; 
            border-color: green; 
            transform: scale(1.10);
        }
        h1{
            margin-top: 0px;
            color: rgb(77, 134, 187);
        }
        span{
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            color: rgb(77, 134, 187);
            font-size: 25px;
        }
        .detail-nombre {
            font-size: 20px;
            margin-bottom: 15px; 
            margin-top: 180px;
            margin-left: -800px;
            margin-right: 800px;
        }
        .detail-descripcion {
            font-size: 20px;
            margin-bottom: 15px; 
        }
        .detail-costo {
            font-size: 20px;
            margin-top: -170px;
            margin-left: -10px;
        }
        .detail-stock {
            font-size: 20px;
            margin-top: -190px;
            margin-left: -10px;
        }
    

        
    </style>
</head>
<body>
<?php include('menu.php') ?>
    <div class="container">
        <h1>Detalles del Producto:</h1>
        <?php if (!empty($producto['archivo'])): ?>
            <div class="detail">
                <img src="archivos/<?php echo $producto['archivo']; ?>" alt="Foto del producto" class="photo">
            </div>
        <?php endif; ?>
        <div class="detail-nombre">
            <span>Nombre:</span><br> 
            <?php echo $producto['nombre']; ?>
        </div>
        <div class="detail-costo">    
            <span>Costo:</span><br> 
            <?php echo $producto['costo']; ?>
        </div>
        <div class="detail-descripcion">
            <span>Descripcion:</span><br> 
            <?php echo $producto['descripcion']; ?>
        </div>
        <div class="detail">
            <span>Codigo:</span><br> 
            <?php echo $producto['codigo']; ?>
        </div>
        <div class="detail-stock">
            <span>Stock:</span><br> 
            <?php echo $producto['stock']; ?>
        </div>
        <a href="productos_lista.php">Volver a la lista</a>
    </div>
</body>
</html>
