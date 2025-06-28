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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Empleados</title>
    <style>
        body {
            background-color: rgb(109, 146, 180);
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }

        .empleados {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            background-color: rgb(255, 255, 255);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 900px;
            padding: 20px;
        }

        .empleados h1 {
            color: rgb(77, 134, 187);
            font-family: Arial, Helvetica, sans-serif;
        }

        .empleados a {
            color: rgb(77, 134, 187);
            font-size: 18px;
            
        }

        .tabla {
            width: 90%;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }

        .fila {
            display: flex;
            border-bottom: 1px solid #ccc;
        }

        .fila.cabecera {
            background-color: rgb(77, 134, 187);
            color: white;
            font-weight: bold;
            
        }

        .fila:nth-child(even) {
            background-color: rgb(230, 230, 230);
        }

        .fila:hover {
            background-color: rgb(200, 200, 255);
        }

        .celda {
            flex: 0.5;
            padding: 10px;
            text-align: center;
        }

        .fila .celda:nth-child(1) {
             flex: 0.5; /* ID */
        }

        .fila .celda:nth-child(2) {
            flex: 4; /* nombre */
        }

        .fila .celda:nth-child(3) {
            flex: 4; /* correo */
        }

        .fila .celda:nth-child(4) {
            flex: 1; /* rol */
            padding: 8px, 8px;
            width: 1px;
        }

        .fila .celda:nth-child(5) {
            flex: 2.0; /* detalles */
        }

        .fila .celda:nth-child(6) {
            flex: 2.0; /* editar */
        }

        .fila .celda:nth-child(7) {
            flex: 1.5; /* eliminar */
        }


        .celda a, .celda button {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 5px;
            color: rgb(77, 134, 187);
            text-decoration: none;
            flex: 1.5;
            width: 5px;
            font-size: 12px
        }

        .celda button {
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
            flex: 1.5;
            padding: 4px 8px; 
            font-size: 12px;  
            width: 20px;
        }

        .celda button:hover {
            background-color: darkred;
        }

        .nuevo_registro a {
            display: inline-block; 
            background-color: rgb(77, 134, 187); 
            color: white; 
            padding: 10px 20px; 
            border-radius: 5px; 
            text-decoration: none; 
            font-size: 16px; 
            font-weight: bold; 
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .nuevo_registro a:hover {
            background-color: rgb(50, 100, 150);
            transform: scale(1.10); 
        }
        .celda :hover{
            transform: scale(1.05);
        }
    </style>
    <script src="jquery-3.3.1.min.js"></script>
    <script>
    function eliminarEmpleado(id) {
        if (confirm("¿Estas seguro de que deseas eliminar este empleado?")) {

            $.ajax({
                url: 'respuesta.php',  // envio a respuesta.php para que ahi se elimine
                type: 'POST',
                data: { numero: id },
                dataType: 'text',
                success: function(response) {
                    if (response == 1) { //si la respuesta es 1, ocultar la fila
                        $('#empleado_' + id).hide(); // ocultar la fila de la tabla
                    } else {
                        alert("No se pudo eliminar el empleado. Intentalo de nuevo.");
                    }
                },
                error: function() {
                    alert("Hubo un error en la comunicación con el servidor.");
                }
            });
        }
    }

    function verDetalles(id){
        window.location.href = 'ver_detalles.php?id=' + id;
    }
    
    </script>
</head>
<body>
    <?php
    include('menu.php');
    ?>
    <div class="empleados">
        <h1>Listado de Empleados</h1>
        <div class="nuevo_registro">
            <a href="empleados_alta.php">Crear un nuevo registro</a>
        </div>
        <?php
        require "funciones/conecta.php";
        $con = conecta();

        $sql = "SELECT * FROM empleados WHERE eliminado = 0";
        $res = $con->query($sql);
        $num = $res->num_rows;

        echo "<div class='tabla'>";
        echo "<div class='fila cabecera'>";
        echo "<div class='celda'>ID</div>";
        echo "<div class='celda'>Nombre Completo</div>";
        echo "<div class='celda'>Correo</div>";
        echo "<div class='celda'>Rol</div>";
        echo "<div class='celda'>Ver detalles</div>";
        echo "<div class='celda'>Editar</div>";
        echo "<div class='celda'>Eliminar</div>";
        echo "</div>";

        while ($row = $res->fetch_array()) {
            $id = $row["id"];
            $nombre = $row["nombre"];
            $apellidos = $row["apellidos"];
            $correo = $row["correo"];
            $rol = $row["rol"] == 1 ? 'Gerente' : 'Ejecutivo';

            echo "<div class='fila' id='empleado_$id'>";
            echo "<div class='celda'>$id</div>";
            echo "<div class='celda'>$nombre $apellidos</div>";
            echo "<div class='celda'>$correo</div>";
            echo "<div class='celda'>$rol</div>";
            echo "<div class='celda'><input type='button' value='Detalles' onclick='verDetalles($id)' 
            style='background-color: green; color: white; border: none; padding: 4px 8px; cursor: pointer; border-radius: 5px;'></div>";
            echo "<div class='celda'><input type='button' value='Editar' onclick='Editar($id)' 
            style='background-color: green; color: white; border: none; padding: 4px 8px; cursor: pointer; border-radius: 5px;'></div>";
            echo "<div class='celda'><input type='button' value='Eliminar' onclick='eliminarEmpleado($id)' 
            style='background-color: red; color: white; border: none; padding: 4px 8px; cursor: pointer; border-radius: 5px;'></div>";
            echo "</div>";
        }

        echo "</div>";
        ?>
    </div>
</body>
</html>