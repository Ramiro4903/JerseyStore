<?php
session_start();
$nombreUser = $_SESSION['nombreUser'];
// Verifica si el usuario no tiene sesión activa
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
    <title>Lista de Productos</title>
    <style>
        body {
            background-color: rgb(109, 146, 180);
            display: flex;
            margin-top: 50px;
        }

        .productos {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            background-color: rgb(255, 255, 255);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 900px;
            padding: 20px;
        }

        .productos h1 {
            color: rgb(77, 134, 187);
            font-family: Arial, Helvetica, sans-serif;
        }

        .productos a {
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

        .celda a, .celda button {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 5px;
            color: rgb(77, 134, 187);
            text-decoration: none;
            font-size: 12px;
        }

        .celda button {
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
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
        }

        .nuevo_registro a:hover {
            background-color: rgb(50, 100, 150);
            transform: scale(1.10);
        }
    </style>
    <script src="jquery-3.3.1.min.js"></script>
    <script>
    function eliminarProducto(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
            $.ajax({
                url: 'respuesta_productos.php',
                type: 'POST',
                data: { numero: id },
                dataType: 'text',
                success: function(response) {
                    if (response == 1) {
                        $('#producto_' + id).hide();
                    } else {
                        alert("No se pudo eliminar el producto. Inténtalo de nuevo.");
                    }
                },
                error: function() {
                    alert("Hubo un error en la comunicación con el servidor.");
                }
            });
        }
    }

    function verDetalles(id) {
        window.location.href = 'detalles_productos.php?id=' + id;
    }

    function Editar(id) {
        window.location.href = 'editar_productos.php?id=' + id;
    }
    </script>
</head>
<body>
<?php include('menu.php') ?>
    <div class="productos">
        <h1>Lista de Productos</h1>
        <div class="nuevo_registro">
            <a href="productos_alta.php">Dar de Alta un Producto</a>
        </div>
        <?php
        require "funciones/conecta_productos.php";
        $con = conecta_productos();

        $sql = "SELECT * FROM productos WHERE eliminado = 0";
        $res = $con->query($sql);
        $num = $res->num_rows;

        echo "<div class='tabla'>";
        echo "<div class='fila cabecera'>";
        echo "<div class='celda'>ID</div>";
        echo "<div class='celda'>Producto</div>";
        echo "<div class='celda'>Código</div>";
        echo "<div class='celda'>Costo</div>";
        echo "<div class='celda'>Stock</div>";
        echo "<div class='celda'>Detalles</div>";
        echo "<div class='celda'>Editar</div>";
        echo "<div class='celda'>Eliminar</div>";
        echo "</div>";

        while ($row = $res->fetch_array()) {
            $id = $row["id"];
            $nombre = $row["nombre"];
            $codigo = $row["codigo"];
            $costo = $row["costo"];
            $stock = $row["stock"];

            echo "<div class='fila' id='producto_$id'>";
            echo "<div class='celda'>$id</div>";
            echo "<div class='celda'>$nombre</div>";
            echo "<div class='celda'>$codigo</div>";
            echo "<div class='celda'>$ $costo</div>";
            echo "<div class='celda'>$stock</div>";
            echo "<div class='celda'><button onclick='verDetalles($id)' style='background-color: green; color: white;'>Detalles</button></div>";
            echo "<div class='celda'><button onclick='Editar($id)' style='background-color: blue; color: white;'>Editar</button></div>";
            echo "<div class='celda'><button onclick='eliminarProducto($id)'>Eliminar</button></div>";
            echo "</div>";
        }

        echo "</div>";
        ?>
    </div>
</body>
</html>
