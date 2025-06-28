<?php
session_start();
require "funciones/conecta.php";
$con = conecta();


$correo = $_REQUEST['correo'];
$passNoEncr = $_REQUEST['pass'];
$passSiEncr= md5($passNoEncr); 

    $sql = "SELECT * FROM empleados WHERE eliminado = 0 AND correo = '$correo' AND  pass = '$passSiEncr' ";
    $res = $con->query($sql);
    $num = $res->num_rows;

    if($num == 1){
        $row = $res->fetch_array();
        $id = $row["id"];
        $nombre = $row["nombre"].' '.$row["apellidos"];
        $correo = $row["correo"];

        $_SESSION['idUser']  = $id;
        $_SESSION['nombreUser'] = $nombre;
        $_SESSION['correoUser'] = $correo;
    }

    echo $num; // retorna el num de registros encontrados


?>