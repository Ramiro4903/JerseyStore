<?php
session_start();
$nombreUser = $_SESSION['nombreUser'];
// veo si usuario no tiene sesion activa
if (!isset($_SESSION['nombreUser'])) {
    header("Location: index.php");
    exit();
}
?> 

<html>
    <head>
    <style>
        .bienvenido{
            color : rgb(77, 134, 187); 
            padding: 40px;
            font-size : 25px;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            text-align: center;
        }

    </style>
    </head>

    <body>
    <?php include('menu.php') ?>
    <div class="bienvenido">Bienvenido: <?php echo $nombreUser; ?>, al sistema de administracion</div>
    </body>
</html>