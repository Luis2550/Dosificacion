<?php
    session_start();
    $urlBase = "http://localhost/plantilla/";

    if(!isset($_SESSION['usuario'])){
        header("location:".$urlBase."login.php");
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aula Virtual</title>
     <!-- Enlace a estilos generales -->
     <link rel="stylesheet" href="css/estilo_inicio.css">
     <link rel="stylesheet" href="../../css/estilo_inicio.css">
     <link rel="stylesheet" href="../../css/estilo_asignatura.css">

    <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body>

    <header>
        <h1>Aula Virtual</h1>
        <div class="cabecera-derecha">
            <div class="info-profesor">
                <p>Profesor: <?php echo $_SESSION['nombre']." ".$_SESSION['apellido'];?></p>
            </div>
            <div class="opciones">
                <label for="menu-opciones">Opciones:</label>
                <select id="menu-opciones" onchange="redirectOption(this)">
                    <option value="inicio">Inicio</option>
                    <option value="configuracion">Configuración</option>
                    <option value="revisar-cursos">Revisar Cursos</option>
                    <option value="cerrar-sesion">Cerrar Sesión</option>
                </select>
            </div>
        </div>
    </header>