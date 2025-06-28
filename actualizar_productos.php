<?php
require "funciones/conecta_productos.php";
$con = conecta_productos();

$nombre = $_REQUEST['nombre'] ?? '';
$codigo = $_REQUEST['codigo'] ?? '';
$descripcion = $_REQUEST['descripcion'] ?? '';
$costo = $_REQUEST['costo'] ?? '';
$stock = $_REQUEST['stock'] ?? '';
$id = $_REQUEST['id'] ?? 0;

$archivo_subido = false;
$archivo_nuevo = "";

// Verifica si se subió un archivo
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
    $file_name = $_FILES['archivo']['name'];
    $file_tmp = $_FILES['archivo']['tmp_name'];
    $arreglo = explode(".", $file_name);
    $ext = end($arreglo); // obtiene la extensión del archivo
    $dir = "archivos/"; // carpeta donde se guardan los archivos
    $file_enc = md5_file($file_tmp); // nombre del archivo encriptado
    $archivo_nuevo = "$file_enc.$ext"; // nombre final del archivo

    if (move_uploaded_file($file_tmp, $dir . $archivo_nuevo)) {
        $archivo_subido = true; 
    }
}

// Construye la consulta SQL según si hay archivo subido o no
if ($archivo_subido) {
    $sql = "UPDATE productos SET nombre = ?, codigo = ?, descripcion = ?, costo = ?, stock = ?, archivo = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssdsi", $nombre, $codigo, $descripcion, $costo, $stock, $archivo_nuevo, $id);
} else {
    $sql = "UPDATE productos SET nombre = ?, codigo = ?, descripcion = ?, costo = ?, stock = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssdsi", $nombre, $codigo, $descripcion, $costo, $stock, $id);
}

// Ejecuta la consulta y redirige al listado
if ($stmt->execute()) {
    header("Location: productos_lista.php");
    exit;
} else {
    echo "Error al actualizar el producto.";
}

$stmt->close();
$con->close();
?>
