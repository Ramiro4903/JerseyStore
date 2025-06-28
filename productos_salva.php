<?php
require "funciones/conecta_productos.php";
$con = conecta_productos();

$nombre = $_REQUEST['nombre'] ?? '';
$codigo = $_REQUEST['codigo'] ?? '';
$descripcion = $_REQUEST['descripcion'] ?? '';
$costo = $_REQUEST['costo'] ?? '';
$stock = $_REQUEST['stock'] ?? '';
$archivo_n = null; 
$archivo = null; 

if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    $file_name = $_FILES['archivo']['name']; // nombre real del archivo
    $file_tmp = $_FILES['archivo']['tmp_name']; // nombre/ruta temporal de archivo
    $arreglo = explode(".", $file_name); // separa el nombre para obtener la extensión
    $len = count($arreglo); // numero de elementos
    $pos = $len - 1; // posicion a buscar
    $ext = $arreglo[$pos]; // extension
    $dir = "archivos/"; // carpeta donde se guardan los archivos
    $file_enc = md5_file($file_tmp); // nombre del archivo encriptado
    $archivo_n = "$file_enc.$ext"; // nombre del archivo encriptado con extensión

    if (!move_uploaded_file($file_tmp, $dir . $archivo_n)) {
        echo json_encode(["status" => "error", "message" => "Error al mover el archivo."]);
        exit; 
    }

    $archivo = $archivo_n; 
}

$sql = "INSERT INTO productos (nombre, codigo, descripcion, costo, stock, archivo_n, archivo) 
VALUES ('$nombre', '$codigo', '$descripcion', '$costo', '$stock', '$archivo_n', '$archivo')";

ob_clean(); 
if ($con->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Empleado agregado exitosamente."]);
} else {
    echo json_encode(["status" => "error", "message" => "Error en la inserción: " . $con->error]);
}
?>
