<?php
session_start();
$nombreUser = $_SESSION['nombreUser'];
// veo si usuario no tiene sesion activa
if (!isset($_SESSION['nombreUser'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="jquery-3.3.1.min.js"></script>

    <title>Alta de empleados</title>

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
}

    </style>

<script>
        function validarCorreo() {
            var campo3 = document.forma01.correo.value.trim();
            var correoRepetido = document.getElementById("correoRepetido");

            if (campo3) {

                $.ajax({
                    url: 'validaCorreo.php',
                    type: 'POST',
                    data: {
                        correo: campo3
                    },
                    success: function(response) {
                        if (response > 0) {
                            correoRepetido.innerText = 'El correo "' + campo3 + '" ya existe';
                            correoRepetido.style.display = 'block'; 
                            document.forma01.correo.value = '';
                            setTimeout(function() {
                                correoRepetido.style.display = 'none';
                            }, 5000);
                        }
                    },
                });
            } else {
                correoRepetido.style.display = 'none'; 
            }
        }

        function validarFormulario(event) {
    event.preventDefault(); // Para que no se envíe el formulario

    var campo1 = document.forma01.nombre.value.trim();
    var campo2 = document.forma01.apellidos.value.trim();
    var campo3 = document.forma01.correo.value.trim();
    var campo4 = document.forma01.pass.value.trim();
    var campo5 = document.forma01.rol.value;
    var archivoInput = document.getElementById("archivo");
    var mensajeError = document.getElementById("mensajeError");

    // Verifica que todos los campos estén llenos y que haya un archivo seleccionado
    if (campo1 && campo2 && campo3 && campo4 && campo5 !== "0" && archivoInput.files.length > 0) {
        var formData = new FormData(); // Crea un nuevo objeto FormData

        // Agrega los campos al objeto FormData
        formData.append('nombre', campo1);
        formData.append('apellidos', campo2);
        formData.append('correo', campo3);
        formData.append('pass', campo4);
        formData.append('rol', campo5);
        formData.append('archivo', archivoInput.files[0]); // Agrega el archivo

        $.ajax({
            url: 'empleados_salva.php',
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
                    window.location.href = "empleados_lista.php"; 
                }
            },
        });
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
<form name="forma01" method="post" action="empleados_salva.php" onsubmit="validarFormulario(event);" enctype="multipart/form-data">
    <p>Alta de empleados</p>
    <button type="button" onclick="regresar();">Regresar al listado</button>
    <input type="text" name="nombre" id="nombre" placeholder="Escribe tu nombre" /> <br>
    <input type="text" name="apellidos" id="apellidos" placeholder="Escribe tus apellidos" /> <br>
    <input onblur="validarCorreo()" name="correo" id="correo" placeholder="Escribe tu correo" /> <br>
    <div id="correoRepetido" style="color: red; display: none; margin-top: 10px;"></div>
    <input type="password" name="pass" id="pass" placeholder="Escribe tu password" /> <br>
    <select name="rol" id="rol">
        <option value="0">Selecciona</option>
        <option value="1">Gerente</option>
        <option value="2">Ejecutivo</option>            
    </select>
    <br>
    <input type="file" id="archivo" name="archivo"><br><br>
    <input type="submit" value="Enviar" />
    <div id="mensajeError" style="color: red; display: none; margin-top: 10px;"></div>
</form>

</div>
</body>
</html>