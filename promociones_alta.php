<?php
session_start();
$nombreUser = $_SESSION['nombreUser'];
// veo si usuario no tiene sesión activa
if (!isset($_SESSION['nombreUser'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<script src="jquery-3.3.1.min.js"></script>
    <title>Alta de Promociones</title>

    <style>
body, html {
    margin: 0;
    padding: 0;
    height: 100%; 
}

body {
    background-color: rgb(109, 146, 180);
    display: flex;
    align-items: flex-start; 
    font-family: 'Arial', sans-serif;
    height: 100%;
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
form input[type="text"], form input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
    transition: all 0.3s ease;
}

/* Efecto al enfocar los campos */
form input[type="text"]:focus, form input[type="file"]:focus {
    border-color: #4caf50; /* Verde elegante */
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.4);
    outline: none;
}

/* Campo de archivo personalizado */
form input[type="file"] {
    background-color: #f9f9f9;
    cursor: pointer;
    color: #333;
}

form input[type="file"]::file-selector-button {
    padding: 8px 12px;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Efecto hover en el botón del selector de archivo */
form input[type="file"]::file-selector-button:hover {
    background-color: #0f6e02;
}


form input[type="text"], 
form input[type="number"], 
form textarea {
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
    transform: scale(1.01);
}

form select {
    background-color: #fff;
    cursor: pointer;
}

h1 {
    color: rgb(77, 134, 187);
    font-size: 30px;
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
    font-size: 28px;
    font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
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
    margin-top: 15px;
    margin-bottom: 20px;
    
}

form button:hover {
    background-color: green; 
    transform: scale(1.01);
}

    </style>

<script>

        function validarFormulario(event) {
    event.preventDefault(); // Para que no se envíe el formulario

    var campo1 = document.forma01.nombre.value.trim();
    var archivoInput = document.getElementById("archivo");
    var mensajeError = document.getElementById("mensajeError");

    // Verifica que todos los campos estén llenos y que haya un archivo seleccionado
    if (campo1 !== "" && archivoInput.files.length > 0) {
        var formData = new FormData(); // Crea un nuevo objeto FormData

        // Agrega los campos al objeto FormData
        formData.append('nombre', campo1);
        formData.append('archivo', archivoInput.files[0]); // Agrega el archivo

        $.ajax({
            url: 'promociones_salva.php', 
            type: 'POST',
            data: formData,
            contentType: false, // Para que jQuery no intente establecer el tipo de contenido
            processData: false, // Para que jQuery no procese los datos
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === "error") {
                    mensajeError.innerText = data.message;
                    mensajeError.style.display = 'block';
                    setTimeout(function() {
                        mensajeError.style.display = 'none';
                    }, 5000);
                } else {
                    window.location.href = "promociones_lista.php"; 
                }
            },
        });
    } else {
        mensajeError.innerText = 'Faltan campos por llenar o no se seleccionó una imagen.';
        mensajeError.style.display = 'block';
        setTimeout(function() {
            mensajeError.style.display = 'none'; 
        }, 5000);
    }
}


        function regresar() {
            window.location.href = "promociones_lista.php"; 
        }
    </script>

</head>
<body>
    <?php include('menu.php') ?>
<div align="center">
<form name="forma01" method="post" action="promociones_salva.php" onsubmit="validarFormulario(event);" enctype="multipart/form-data">
    <p>Alta de Promociones</p>
    <button type="button" onclick="regresar();">Regresar al listado</button>
    <input type="text" name="nombre" id="nombre" placeholder="Nombre de la Promocion" /> <br>
    <input type="file" id="archivo" name="archivo"><br><br>
    <input type="submit" value="Enviar" />
    <div id="mensajeError" style="color: red; display: none; margin-top: 10px;"></div>
</form>

</div>
</body>
</html>
