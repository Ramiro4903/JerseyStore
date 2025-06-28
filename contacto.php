<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$logueado = isset($_SESSION['nombreUser']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Tienda</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Incluir jQuery -->
    <style>
         body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        body {
            background-color: rgb(109, 146, 180);
            display: flex;
            align-items: center;
            justify-content: center; 
            height: 100%;
        }

        form {
            background-color: #c9cccf;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
            border: 2px solid #black;
        }

        form input, form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #c9cccf;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s ease-in-out;;
            font-family:Georgia, 'Times New Roman', Times, serif;
        }

        form input[type="text"]:focus, form input[type="email"]:focus, form textarea:focus {
            border-color: #3065a2;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.5);
            outline: none;
        }

        form input[type="submit"], form button {
            background-color: #3065a2;
            color: white;
            border: none;
            cursor: pointer;
            padding: 12px 20px;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        form input[type="submit"]:hover, form button:hover {
            background-color: #1d4f88;
            transform: scale(1.05);
        }

        #mensajeError, #mensajeResultado {
            padding: 10px;
            border-radius: 8px;
            margin-top: 20px;
            display: none;
        }

        #mensajeError {
            background-color: #f8d7da;
            color: #721c24;
        }

        #mensajeResultado {
            background-color: #d4edda;
            color: #155724;
        }

        h2 {
            margin-top: 30px;
            color: rgb(77, 134, 187);
            font-size: 28px;
            margin-bottom: 20px;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        textarea {
            resize: vertical;
            font-family:Georgia, 'Times New Roman', Times, serif;
            font-size: 16px;
        }
        label{
            color: rgb(77, 134, 187);
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
    </style>
    <script>
        function validarFormulario(event) {
            event.preventDefault(); 

            var nombre = $("#nombre").val().trim();
            var correo = $("#correo").val().trim();
            var comentarios = $("#comentarios").val().trim();

            if (nombre === "" || correo === "" || comentarios === "") {
                $("#mensajeError").show();
                $("#mensajeError").text("Por favor, llena todos los campos.");

                setTimeout(function() {
                    $("#mensajeError").hide();
                }, 5000); 
            } else {
                enviarFormulario(nombre, correo, comentarios);
            }
        }

        function enviarFormulario(nombre, correo, comentarios) {
            $.ajax({
    url: 'recibe_contacto.php',
    type: 'POST',
    data: {
        nombre: $('#nombre').val(),
        correo: $('#correo').val(),
        comentarios: $('#comentarios').val()
    },
    success: function(response) {
        var mensaje = document.getElementById('mensajeResultado');
        
        if (response === 'success') {
            mensaje.style.display = 'block';
            mensaje.style.color = 'green';
            mensaje.style.backgroundColor = '#dff0d8';
            mensaje.innerText = "El mensaje ha sido enviado correctamente.";
            setTimeout(function() {
                window.location.href = 'index.php';
        }, 3000);
        } else if (response === 'error_email') {
            mensaje.style.display = 'block';
            mensaje.style.color = 'red';
            mensaje.style.backgroundColor = '#f2dede';
            mensaje.innerText = "El correo electrónico ingresado no tiene un formato válido.";
        } else {
            mensaje.style.display = 'block';
            mensaje.style.color = 'red';
            mensaje.style.backgroundColor = '#f2dede';
            mensaje.innerText = "Hubo un problema al enviar el mensaje. Inténtalo de nuevo.";
        }

        // Ocultar el mensaje después de 5 segundos
        setTimeout(function() {
            mensaje.style.display = 'none';
        }, 5000);
    },
    error: function() {
        var mensaje = document.getElementById('mensajeResultado');
        mensaje.style.display = 'block';
        mensaje.style.color = 'red';
        mensaje.style.backgroundColor = '#f2dede';
        mensaje.innerText = "Hubo un error en la comunicación con el servidor.";

        setTimeout(function() {
            mensaje.style.display = 'none';
        }, 5000);
    }
});


        }
    </script>
</head>
<body>
<?php include('menu_usuario.php'); ?>
<br>
<br>
<br>
    <form onsubmit="validarFormulario(event)" method="post">
    <h2>CONTACTENOS</h2>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre"><br><br>

        <label for="correo">Correo:</label>
        <input type="text" id="correo" name="correo" placeholder="ejemplo@gmail.com"><br><br>

        <label for="comentarios">Comentarios:</label><br>
        <textarea id="comentarios" name="comentarios" rows="5" cols="30"></textarea><br><br>

        <button type="submit">Enviar</button>
    </form>
    <div id="mensajeError" style="color: red; display: none; margin-top: 10px;"></div>
    <div id="mensajeResultado" style="display: none; color: green; padding: 10px; background-color: #dff0d8; border-radius: 5px; margin-top: 20px;"></div>
    <?php include('pie_pagina.php'); ?>
</body>
</html>
