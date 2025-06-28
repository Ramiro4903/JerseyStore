<?php
session_start();
$nombreUser = $_SESSION['nombreUser'];
// veo si usuario no tiene sesion activa
if (!isset($_SESSION['nombreUser'])) {
    header("Location: index.php");
    exit();
}
require "funciones/conecta.php";
$con = conecta();

$id = $_GET['id'];
$sql = "SELECT * FROM empleados WHERE id = $id AND eliminado = 0";
$result = $con->query($sql);
$empleado = $result->fetch_assoc();

if (!$empleado) {
    echo "Empleado no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Empleado</title>
    <style>
        body {
            background-color: rgb(109, 146, 180); 
            display: flex;
            
            align-items: center;
            height: 100vh; 
            font-family: 'Arial', sans-serif;
            margin: 0;
        }

        .container {
            background-color: white; 
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); 
            width: 350px;
            text-align: center;
        }

        h1 {
            color: rgb(77, 134, 187); 
            font-size: 28px;
            margin-bottom: 20px;
        }

        .detail {
            margin: 8px 0; 
            padding: 8px;
            border-radius: 8px;
            background-color: rgb(230, 230, 230); 
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); 
            flex-direction: column;
            border: 2px solid rgb(77, 134, 187);
        }

        .detail span {
            font-weight: bold; 
            color: rgb(109, 146, 180);
        }

        .detail:hover {
            background-color: rgb(77, 134, 187); 
            color: white; 
        }

        .detail:hover span {
            color: white; 
        }

        a {
            text-decoration: none;
            color: rgb(77, 134, 187);
            font-size: 16px;
            margin-top: 20px; 
            display: inline-block; 
            padding: 10px 15px; 
            border: 2px solid rgb(77, 134, 187); 
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s; 
        }

        a:hover {
            background-color: green; 
            color: white; 
            border: 2px solid green;
            transform: scale(1.10); 
        }

        .photo {
    width: 50%; 
    height: auto; 
    border-radius: 10px;
    margin-top: 15px;
}


    </style>
</head>
<body>
<?php include('menu.php') ?>
    <div class="container">
        <h1>Detalles del Empleado</h1>
        <div class="detail">
            <span>Nombre:</span><br> 
            <?php echo $empleado['nombre'] . ' ' . $empleado['apellidos']; ?>
        </div>
        <div class="detail">
            <span>Correo:</span><br> 
            <?php echo $empleado['correo']; ?>
        </div>
        <div class="detail">
            <span>Rol:</span><br> 
            <?php echo $empleado['rol'] == 1 ? 'Gerente' : 'Ejecutivo'; ?>
        </div>
        <?php if (!empty($empleado['archivo'])): ?>
            <div class="detail">
                <span>Foto:</span><br> 
                <img src="archivos/<?php echo $empleado['archivo']; ?>" alt="Foto del Empleado" class="photo">
            </div>
        <?php endif; ?>
        <a href="empleados_lista.php">Volver a la lista</a>
    </div>
</body>
</html>
