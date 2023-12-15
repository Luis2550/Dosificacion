<?php
include("../../../bd.php");

// Inicializar variables
$codigoAsignatura = '';

// Verifica si se ha proporcionado el parámetro 'codigo' en la URL
if (isset($_GET['codigo'])) {
    // Obtiene el valor del parámetro 'codigo'
    $codigoAsignatura = $_GET['codigo'];

    // Consulta para obtener las unidades de temas de la asignatura
    $query = "SELECT * FROM unidad_tema WHERE codigo_asignatura = '$codigoAsignatura'";
    $result = mysqli_query($conexion, $query);

    // Verifica si se obtuvieron resultados
    if ($result) {
        // Obtiene todas las filas de resultados como un array asociativo
        $unidades = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombreUnidadTema = $_POST['nombre_unidad_tema'];
    $tema = $_POST['tema'];
    $fecha = $_POST['fecha'];
    $codigoAsignatura = $_POST['codigo_asignatura'];

    // Consulta SQL para insertar los datos en la tabla unidad_tema
    $query = "INSERT INTO unidad_tema (nombre_unidad_tema, tema, fecha, codigo_asignatura) 
              VALUES ('$nombreUnidadTema', '$tema', '$fecha', '$codigoAsignatura')";

    // Ejecutar la consulta
    $result = mysqli_query($conexion, $query);

    // Verificar si la inserción fue exitosa
    if ($result) {
        echo "Datos insertados correctamente.";

        // Redirigir con el código de asignatura en la URL
        header("Location: unidad.php?codigo=$codigoAsignatura");
        exit();
    } else {
        echo "Error al insertar datos: " . mysqli_error($conexion);
    }
}

// Verifica si se ha proporcionado el parámetro 'codigo' en la URL
if (isset($_GET['codigo'])) {
    // Obtiene el valor del parámetro 'codigo'
    $codigoAsignatura = $_GET['codigo'];

    // Consulta para obtener las unidades de temas de la asignatura
    $query = "SELECT * FROM unidad_tema WHERE codigo_asignatura = '$codigoAsignatura'";
    $result = mysqli_query($conexion, $query);

    // Verifica si se obtuvieron resultados
    if ($result) {
        // Obtiene todas las filas de resultados como un array asociativo
        $unidades = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Procesar acción de borrar si se ha proporcionado 'accion' y 'id' en la URL
    if (isset($_GET['accion']) && $_GET['accion'] == 'borrar' && isset($_GET['id'])) {
        $idUnidad = $_GET['id'];

        // Consulta SQL para borrar la unidad
        $queryBorrar = "DELETE FROM unidad_tema WHERE id_unidad_tema = '$idUnidad'";
        $resultBorrar = mysqli_query($conexion, $queryBorrar);

        // Verificar si la eliminación fue exitosa
        if ($resultBorrar) {
            echo "Unidad borrada correctamente.";

            // Redirigir de nuevo a la página de unidades con el código de asignatura
            header("Location: unidad.php?codigo=$codigoAsignatura");
            exit();
        } else {
            echo "Error al borrar la unidad: " . mysqli_error($conexion);
        }
    }
} else {
    echo "Parámetros incorrectos.";
}

// Cierra la conexión a la base de datos
mysqli_close($conexion);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Unidad Tema</title>
    <link rel="stylesheet" href="../../../css/estilo_unidad.css">
</head>
<body>

<a href="../index.php?codigo=<?php echo $codigoAsignatura;?>" class="regresar">Regresar</a>

    <h2 class="Title">Formulario Unidad Tema</h2>
    <form action="unidad.php" method="post">
        <label for="nombre_unidad_tema">Nombre de la Unidad/Tema:</label>
        <input type="text" name="nombre_unidad_tema" required><br>

        <label for="tema">Tema:</label>
        <textarea name="tema" rows="4" required></textarea><br>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" required><br>

        <label for="codigo_asignatura">Código de Asignatura:</label>
        <input type="text" name="codigo_asignatura" required value="<?php echo $codigoAsignatura; ?>" readonly><br>

        <input type="submit" value="Guardar">
    </form>

    <?php if (isset($unidades) && !empty($unidades)): ?>
    <h2 class="subtitulo">Unidades de Temas de la Asignatura</h2>
    <table border="1">
        <thead>
            <tr>
                
                <th>Nombre</th>
                <th>Tema</th>
                <th>Fecha</th>
                <th class="actions-column">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($unidades as $unidad): ?>
                <tr>
                    
                    <td><?php echo $unidad['nombre_unidad_tema']; ?></td>
                    <td><?php echo $unidad['tema']; ?></td>
                    <td><?php echo $unidad['fecha']; ?></td>
                    <td class="actions-column">
                        <a href="editar.php?id=<?php echo $unidad['id_unidad_tema']; ?>&codigo=<?php echo $codigoAsignatura; ?>" class="btn-editar">Editar</a>
                        <a href="?accion=borrar&id=<?php echo $unidad['id_unidad_tema']; ?>&codigo=<?php echo $codigoAsignatura; ?>" class="btn-borrar">Borrar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay unidades de temas disponibles para esta asignatura.</p>
<?php endif; ?>

</body>
</html>
