<?php
require "funciones/conecta_productos.php";
$con = conecta_productos();

// Recibe los datos del formulario
$id = $_REQUEST['id'] ?? 0;
$nombre = $_REQUEST['nombre'] ?? '';

if ($id == 0) {
    echo "ID inválido.";
    exit;
}

// Verifica que la promoción exista
$sql_check = "SELECT id FROM promociones WHERE id = ? AND eliminado = 0";
$stmt_check = $con->prepare($sql_check);
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows === 0) {
    echo "Promoción no encontrada o ya eliminada.";
    exit;
}
$stmt_check->close();

$archivo_subido = false;
$archivo_nuevo = "";

// Verifica si se subió un archivo
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
    $file_name = $_FILES['archivo']['name'];
    $file_tmp = $_FILES['archivo']['tmp_name'];
    $arreglo = explode(".", $file_name);
    $ext = strtolower(end($arreglo)); // Obtiene la extensión del archivo
    $dir = "archivos/"; // Carpeta donde se guardarán los archivos
    $file_enc = md5_file($file_tmp); // Nombre del archivo encriptado
    $archivo_nuevo = "$file_enc.$ext"; // Nombre final del archivo

    // Mueve el archivo al directorio destino
    if (move_uploaded_file($file_tmp, $dir . $archivo_nuevo)) {
        $archivo_subido = true; 
    } else {
        echo "Error al mover el archivo.";
        exit;
    }
}

// Construye la consulta SQL según si hay archivo subido o no
if ($archivo_subido) {
    $sql = "UPDATE promociones SET nombre = ?, archivo = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssi", $nombre, $archivo_nuevo, $id);
} else {
    $sql = "UPDATE promociones SET nombre = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $nombre, $id);
}

// Ejecuta la consulta y redirige al listado
if ($stmt->execute()) {
    header("Location: promociones_lista.php");
    exit;
} else {
    echo "Error al actualizar la promoción: " . $stmt->error;
}

// Cierra la conexión
$stmt->close();
$con->close();
?>
