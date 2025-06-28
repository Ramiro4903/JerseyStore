<?php
// Inicia la sesión
session_start();
require "Administrador/funciones/conecta_productos.php"; 

// Verifica si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $pass = $_POST['pass'];

    // Conectar a la base de datos
    $con = conecta_productos();

    // Consulta para verificar las credenciales del usuario
    $sql = "SELECT id, correo, pass FROM clientes WHERE correo = '$correo' AND pass = '$pass'";
    $res = $con->query($sql);

    // Verifica si se encontró el usuario
    if ($res->num_rows > 0) {
        $cliente = $res->fetch_assoc();
        
        // Guarda el ID del cliente en la sesión
        $_SESSION['id_cliente'] = $cliente['id'];

    } else {
        $mensajeError = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="jquery-3.3.1.min.js"></script>
    <title>Inicio de Sesion - Tienda</title>

    <style>
        body {
            background-color: rgb(109, 146, 180);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; 
            font-family: 'Arial', sans-serif;
            margin: 0;
        }

        form {
            background-color: white; 
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            width: 300px;
            text-align: center;
        }

        form input {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .password-container {
            position: relative;
            width: 100%;
        }

        .password-container input {
            width: 80%;
            padding-right: 40px;
        }

        .password-container .toggle-password {
            position: absolute;
            top: 40%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            background: none;
            border: none;
            color: #007bff;
            font-size: 18px;
        }

        form input[type="submit"] {
            background-color: rgb(77, 134, 187); 
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: green; 
            transform: scale(1.05);
        }

        h1 {
            color: rgb(77, 134, 187);
            font-size: 24px;
            margin-bottom: 20px;
        }

        .link a {
            text-decoration: none;
            color: rgb(77, 134, 187);
            font-size: 16px;
        }

        .link a:hover {
            text-decoration: underline;
        }

        form p {
            font-size: x-large;
            color: rgb(77, 134, 187); 
            margin-bottom: 20px;
        }

        .toggle-password i {
            color: #007bff;
        }

        .toggle-password i:hover {
            color: #0056b3;
            transform: scale(1.10);
        }
    </style>

    <script>
    function validarFormulario(event) {
        event.preventDefault(); 
        var correo = $('#correo').val().trim();
        var pass = $('#pass').val().trim();
        var mensajeError = $('#mensajeError');

        if (correo && pass) {
            $.ajax({
                url: 'valida_cliente.php',
                type: 'POST',
                data: {
                    correo: correo,
                    pass: pass
                },
                success: function(data) {
                    if (data === "1") { 
                        window.location.href = "index.php";
                    } else {
                        mensajeError.text("Usuario o contraseña incorrectos.").show();
                        setTimeout(function() { mensajeError.hide(); }, 5000); 
                    }
                },
                error: function() {
                    mensajeError.text("Error en la conexión, intenta nuevamente.").show();
                    setTimeout(function() { mensajeError.hide(); }, 5000);
                }
            });
        } else {
            mensajeError.text("Faltan campos por llenar.").show();
            setTimeout(function() { mensajeError.hide(); }, 5000);
        }
    }

    function togglePassword() {
        const passField = document.getElementById('pass');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (passField.type === 'password') {
            passField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    </script>

    <!-- Link de FontAwesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
    <div align="center">
        <form name="forma01" method="post" action="" onsubmit="validarFormulario(event);">
            <p>INICIO DE SESION</p>
            <input name="correo" id="correo" placeholder="Escribe tu correo" /> <br>
            <div id="correoRepetido" style="color: red; display: none; margin-top: 10px;"></div>
            <div class="password-container">
                <input type="password" name="pass" id="pass" placeholder="Escribe tu password" />
                <button type="button" class="toggle-password" onclick="togglePassword()">
                    <i id="togglePasswordIcon" class="fa fa-eye"></i>
                </button>
            </div>
            <br>
            <input type="submit" value="Iniciar sesion"/>
            <div id="mensajeError" style="color: red; display: none; margin-top: 10px;">
                <?php echo isset($mensajeError) ? $mensajeError : ''; ?>
            </div>
        </form>
    </div>
</body>
</html>
