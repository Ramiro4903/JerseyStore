<?php
    
$file_name = $_FILES['archivo']['name']; // nombre real del archivo
$file_tmp = $_FILES['archivo']['tmp_name']; //nombre/ruta temporal de archivo
$arreglo = explode(".",$file_name); // separa el nombre para obtener la extension
$len = count($arreglo); //numero de elementos
$pos = $len-1; //posicion a buscar
$ext = $arreglo[$pos]; //extension
$dir = "archivos/"; //carpeta donde se guardan los archivos
$file_enc = md5_file($file_tmp); //nombre del archivo encriptado

echo "file_name : $file_name  <br>";
echo "file_tmp : $file_tmp  <br>";
echo "ext : $ext  <br>";
echo "file_enc : $file_enc  <br>";

if($file_name != ''){
    $fileName1 = "$file_enc.$ext";
    copy($file_tmp, $dir.$fileName1);
    echo "fileName1:  $fileName1  <br>";
}

?>