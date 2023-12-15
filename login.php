<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluir el archivo de conexión a la base de datos
    include('bd.php');

    // Obtener los valores del formulario
    $correo = $_POST['email'];
    $contrasenia = $_POST['password'];

    // Preparar la consulta SQL con marcadores de posición "?"
    $sentencia = $conexion->prepare("SELECT *, count(*) as n_usuarios 
        FROM profesores 
        WHERE correo=? AND contrasenia=?");

    // Verificar si la preparación de la consulta fue exitosa
    if (!$sentencia) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    // Vincular los parámetros y ejecutar la consulta
    $sentencia->bind_param("ss", $correo, $contrasenia);
    $sentencia->execute();

    // Obtener resultados
    $resultado = $sentencia->get_result();
    $registro = $resultado->fetch_assoc();

    // Verificar la existencia de usuarios
    if ($registro['n_usuarios'] > 0) {
        $_SESSION['usuario'] = $registro['correo'];  
        $_SESSION['nombre'] = $registro['nombres'];  
        $_SESSION['apellido'] = $registro['apellidos']; 
        $_SESSION['id'] = $registro['id'];  
        $_SESSION['logueado'] = true;

        header('location:./index.php');
    } else {
        $mensaje = "Error: correo o contraseña incorrectos";
    }

    // Cerrar la sentencia y la conexión
    $sentencia->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/sesion.css">
    <title>Iniciar Sesión</title>
</head>
<body>
    <div class="login-container">
        <form class="login-form" method="POST" action="">
            <h2>Iniciar Sesión</h2>
            <?php if (isset($mensaje)): ?>
                <p style="color: red;"><?php echo $mensaje; ?></p>
            <?php endif; ?>
            <div class="form-group">
                <label for="email">Correo Institucional:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
