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
        <title>Carrio - Tienda</title>
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
    <?php include('menu_usuario.php') ?>
    <div class="bienvenido">El carrito esta vacio</div>
    <?php include('pie_pagina.php') ?>
    </body>
</html>