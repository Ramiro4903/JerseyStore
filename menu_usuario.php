<?php
// Verificar si la sesión ya está iniciada antes de llamarla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verifica si el usuario ha iniciado sesión
$nombreUser = isset($_SESSION['nombreUser']) ? $_SESSION['nombreUser'] : "";
?>
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
        <a href="index.php" class="menu-item"><i class="fas fa-home"></i> Home</a>
        <a href="productos.php" class="menu-item"><i class="fas fa-box"></i> Productos</a>
        <a href="contacto.php" class="menu-item"><i class="fas fa-envelope"></i> Contacto</a>

        <?php if ($nombreUser): ?>
            <!-- Si el usuario está logueado, muestra estas opciones -->
            <a href="carrito01.php" class="menu-item"><i class="fas fa-shopping-cart"></i> Ver carrito</a>
            <a href="salir_cliente.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Salir</a>
            <a class="menu-nombre">Bienvenido: <br><?php echo $nombreUser; ?></a>
        <?php else: ?>
            <!-- Si el usuario no está logueado, muestra solo el login -->
            <a href="login.php" class="menu-item"><i class="fas fa-user"></i> Login</a>
        <?php endif; ?>
    </div>
</body>
</html>
