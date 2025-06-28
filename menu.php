<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: 'Arial', sans-serif;
            margin: 0;
            min-height: 100vh;
        }

        .menu {
            display: flex;
            justify-content: center;
            background-color: rgb(77, 134, 187);
            padding: 15px 30px;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 0;
        }

        .menu-item {
            color: white;
            text-decoration: none;
            padding: 20px 30px;
            font-size: 20px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s;
            border-radius: 5px;
            margin: 0 5px;
            display: flex;
            align-items: center;
        }

        .menu-item i {
            margin-right: 8px; /* Espacio entre el icono y el texto */
        }

        .menu-item:hover {
            background-color: #3065a2;
        }

        .menu-nombre {
            color: white;
            padding: 20px 30px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 5px;
            margin: 0 5px;
        }

        .logo {
            height: 100px;
            max-width: 150px;
            width: auto;
            margin-right: 100px;
            margin-top: 0px;
        }

        img {
            height: 100px;
            width: 100px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="menu">
        <a class="logo"><img src="Logo_Final (png).png" alt="Logo"/></a>
        <a class="menu-nombre">Bienvenido:<br><?php echo $nombreUser; ?></a>
        <a href="bienvenido.php" class="menu-item"><i class="fas fa-home"></i> Inicio</a>
        <a href="empleados_lista.php" class="menu-item"><i class="fas fa-users"></i> Empleados</a>
        <a href="productos_lista.php" class="menu-item"><i class="fas fa-box"></i> Productos</a>
        <a href="promociones_lista.php" class="menu-item"><i class="fas fa-tags"></i> Promociones</a>
        <a href="pedidos.php" class="menu-item"><i class="fas fa-clipboard-list"></i> Pedidos</a>
        <a href="cerrar_sesion.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>
</body>
</html>
