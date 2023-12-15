<?php

// Conexión a la base de datos utilizando mysqli

$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "dosificacion2";

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8
$conexion->set_charset("utf8");

?>