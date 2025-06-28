<?php
require "funciones/conecta.php";
$con = conecta();

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$correo = $_POST['correo'];
$rol = $_POST['rol'];
$pass = $_POST['pass'];

$archivo_subido = false;
$archivo_nuevo = "";

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

$passENC = !empty($pass) ? md5($pass) : null;

if (!empty($pass) && $archivo_subido) {
    $sql = "UPDATE empleados SET nombre = ?, apellidos = ?, correo = ?, rol = ?, pass = ?, archivo = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssssi", $nombre, $apellidos, $correo, $rol, $passENC, $archivo_nuevo, $id);
} elseif (!empty($pass) && !$archivo_subido) {
    
    $sql = "UPDATE empleados SET nombre = ?, apellidos = ?, correo = ?, rol = ?, pass = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $apellidos, $correo, $rol, $passENC, $id); 
} elseif (empty($pass) && $archivo_subido) {
    
    $sql = "UPDATE empleados SET nombre = ?, apellidos = ?, correo = ?, rol = ?, archivo = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $apellidos, $correo, $rol, $archivo_nuevo, $id); 
} else {
   
    $sql = "UPDATE empleados SET nombre = ?, apellidos = ?, correo = ?, rol = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $apellidos, $correo, $rol, $id); 
}

if ($stmt->execute()) {
    header("Location: empleados_lista.php"); 
    exit;
} else {
    echo "Error al actualizar el empleado.";
}

$stmt->close();
$con->close();
?>
