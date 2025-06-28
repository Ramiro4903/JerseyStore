<?php // se conecta con la base de datos para eliminar
$id = isset($_REQUEST['numero']) ? intval($_REQUEST['numero']) : 0; 
$ban = 0; 
if ($id > 0) {
    // Conectar a la base de datos
    require "funciones/conecta_productos.php"; 
    $con = conecta_productos();

    $sql = "UPDATE productos SET eliminado = 1 WHERE id = $id";

    if ($con->query($sql) === TRUE) {
        $ban = 1; // eliminar
    } else {
        $ban = 0; // no elimnar
    }
} else {
    $ban = 0; // no eliminar si id no es valido
}

echo $ban;
?>
