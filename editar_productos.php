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
$productos = $result->fetch_assoc();

if (!$productos) {
    echo "Producto no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edición de Productos</title>
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
function validarCodigo() {
            var codigo = document.forma01.codigo.value.trim();
            var codigoRepetido = document.getElementById("codigoRepetido");

            if (codigo) {
                console.log('1');
                $.ajax({
                    url: 'validaCodigo.php',
                    type: 'POST',
                    data: {
                        codigo: codigo,
                        id: <?php echo $productos['id']; ?> 
                    },
                    success: function(response) {
                        if (response > 0) {
                            codigoRepetido.innerText = 'El codigo "' + codigo + '" ya existe';
                            codigoRepetido.style.display = 'block';
                            document.forma01.codigo.value = '';
                            setTimeout(function() {
                                codigoRepetido.style.display = 'none';
                            }, 5000);
                        }
                    },
                });
            }
        }

        function validarFormulario(event) {
            event.preventDefault();

            var nombre = document.forma01.nombre.value.trim();
            var codigo = document.forma01.codigo.value.trim();
            var descripcion = document.forma01.descripcion.value.trim();
            var costo = document.forma01.costo.value;
            var stock = document.forma01.stock.value;
            var mensajeError = document.getElementById("mensajeError");

            if (nombre && codigo && descripcion && costo !== "0" && stock > "0") {
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
            window.location.href = "productos_lista.php";
        }
    </script>
</head>
<body>
<?php include('menu.php') ?>
    <div align="center">
        <form name="forma01" method="post" action="actualizar_productos.php" onsubmit="validarFormulario(event);" enctype="multipart/form-data">
        <h1>Edición de Productos</h1> 
            <input type="hidden" name="id" value="<?php echo $productos['id']; ?>">
            <button type="button" onclick="regresar();">Regresar al listado</button><br><br>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $productos['nombre']; ?>"><br>

            <label for="codigo">Codigo:</label>
            <input type="text" id="codigo" name="codigo" value="<?php echo $productos['codigo']; ?>" onblur="validarCodigo()"><br>
            <div id="codigoRepetido" style="color: red; display: none;"></div>

            <label for="descripcion">Descripcion:</label>
            <input type="text" id="descripcion" name="descripcion" value="<?php echo $productos['descripcion']; ?>"><br>

            <label for="costo">Costo:</label>
            <input type="text" id="costo" name="costo" value="<?php echo $productos['costo']; ?>"><br>

            <label for="stock">Stock:</label>
            <input type="text" id="stock" name="stock" value="<?php echo $productos['stock']; ?>"><br>

            <label for="archivo">Subir nueva foto (opcional):</label>
            <input type="file" id="archivo" name="archivo" accept="image/*"><br>

            <input type="submit" value="Actualizar">
            <div id="mensajeError" style="color: red; display: none; margin-top: 10px;"></div>
        </form>
    </div>
</body>
</html>
