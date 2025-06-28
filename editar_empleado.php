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
    <title>Edición de Empleados</title>
    <script src="jquery-3.3.1.min.js"></script>
    <style>
        body {
            background-color: rgb(109, 146, 180);
            display: flex;
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

        form input, form select {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        form input[type="text"], 
        form input[type="password"], 
        form select {
            margin-top: 8px;
            padding: 14px; 
            width: 100%; 
            margin-bottom: 14px;
            border-radius: 5px; 
            border: 2px solid #ccc; 
            box-sizing: border-box;
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

        form select {
            background-color: #fff;
            cursor: pointer;
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

        form button {
            background-color: rgb(77, 134, 187);
            color: white; 
            border: none; 
            cursor: pointer; 
            padding: 7px 10px; 
            border-radius: 5px; 
            font-size: 14px; 
            transition: background-color 0.3s ease; 
            margin-bottom: 3px;
        }

        form button:hover {
            background-color: green; 
            transform: scale(1.05);
        }
    </style>

    <script>
        function validarCorreo() {
            var correo = document.forma01.correo.value.trim();
            var correoRepetido = document.getElementById("correoRepetido");

            if (correo) {
                console.log('1');
                $.ajax({
                    url: 'validaCorreo.php',
                    type: 'POST',
                    data: {
                        correo: correo,
                        id: <?php echo $empleado['id']; ?> 
                    },
                    success: function(response) {
                        if (response > 0) {
                            correoRepetido.innerText = 'El correo "' + correo + '" ya existe';
                            correoRepetido.style.display = 'block';
                            document.forma01.correo.value = '';
                            setTimeout(function() {
                                correoRepetido.style.display = 'none';
                            }, 5000);
                        }
                    },
                });
            }
        }

        function validarFormulario(event) {
            event.preventDefault();

            var nombre = document.forma01.nombre.value.trim();
            var apellidos = document.forma01.apellidos.value.trim();
            var correo = document.forma01.correo.value.trim();
            var rol = document.forma01.rol.value;
            var mensajeError = document.getElementById("mensajeError");

            if (nombre && apellidos && correo && rol !== "0") {
                document.forma01.submit();
            } else {
                mensajeError.innerText = 'Faltan campos por llenar.';
                mensajeError.style.display = 'block';
                setTimeout(function() {
                    mensajeError.style.display = 'none';
                }, 5000);
            }
        }

        function regresar() {
            window.location.href = "empleados_lista.php";
        }
    </script>
</head>
<body>
<?php include('menu.php') ?>
    <div align="center">
        <form name="forma01" method="post" action="actualizar_empleado.php" onsubmit="validarFormulario(event);" enctype="multipart/form-data">
        <h1>Edición de Empleados</h1> 
            <input type="hidden" name="id" value="<?php echo $empleado['id']; ?>">
            <button type="button" onclick="regresar();">Regresar al listado</button><br><br>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $empleado['nombre']; ?>"><br>

            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" value="<?php echo $empleado['apellidos']; ?>"><br>

            <label for="correo">Correo:</label>
            <input type="text" id="correo" name="correo" value="<?php echo $empleado['correo']; ?>" onblur="validarCorreo()"><br>
            <div id="correoRepetido" style="color: red; display: none;"></div>

            <label for="pass">Contraseña:</label>
            <input type="password" id="pass" name="pass"><br>

            <label for="rol">Rol:</label>
            <select id="rol" name="rol">
                <option value="1" <?php if ($empleado['rol'] == 1) echo 'selected'; ?>>Gerente</option>
                <option value="2" <?php if ($empleado['rol'] == 2) echo 'selected'; ?>>Ejecutivo</option>
            </select><br><br>

            <label for="archivo">Subir nueva foto (opcional):</label>
            <input type="file" id="archivo" name="archivo" accept="image/*"><br>

            <input type="submit" value="Actualizar">
            <div id="mensajeError" style="color: red; display: none; margin-top: 10px;"></div>
        </form>
    </div>
</body>
</html>
