<?php

session_start();

// destuye variables de la sesion
session_unset();

// destuye la sesion
session_destroy();

header("Location: index.php");
exit();
?>