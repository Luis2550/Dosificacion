<?php
include("../../../bd.php");

// Verifica si se ha proporcionado el parámetro 'id' en la URL
if (isset($_GET['id']) && isset($_GET['codigo'])) {
    // Obtiene el valor del parámetro 'id'
    $idUnidad = $_GET['id'];
    // Obtiene el valor del parámetro 'codigo'
    $codigoAsignatura = $_GET['codigo'];

    // Consulta para obtener los datos de la unidad a editar
    $queryEditar = "SELECT * FROM unidad_tema WHERE id_unidad_tema = '$idUnidad'";
    $resultEditar = mysqli_query($conexion, $queryEditar);

    // Verifica si se obtuvieron resultados
    if ($resultEditar) {
        // Obtiene los datos de la unidad a editar como un array asociativo
        $unidadEditar = mysqli_fetch_assoc($resultEditar);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recibir datos del formulario de edición
        $nombreUnidadTema = $_POST['nombre_unidad_tema'];
        $tema = $_POST['tema'];
        $fecha = $_POST['fecha'];

        // Consulta SQL para actualizar los datos en la tabla unidad_tema
        $queryActualizar = "UPDATE unidad_tema 
                            SET nombre_unidad_tema = '$nombreUnidadTema', tema = '$tema', fecha = '$fecha' 
                            WHERE id_unidad_tema = '$idUnidad'";
        $resultActualizar = mysqli_query($conexion, $queryActualizar);

        // Verificar si la actualización fue exitosa
        if ($resultActualizar) {
            echo "Datos actualizados correctamente.";

            // Redirigir de nuevo a la página de unidades con el código de asignatura
            header("Location: unidad.php?codigo=$codigoAsignatura");
            exit();
        } else {
            echo "Error al actualizar datos: " . mysqli_error($conexion);
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
    <title>Editar Unidad Tema</title>
    <link rel="stylesheet" href="../../../css/estilo_unidad.css">
</head>
<body>

    <h2 class="Title">Formulario Edición Unidad Tema</h2>
    <form action="editar.php?id=<?php echo $idUnidad; ?>&codigo=<?php echo $codigoAsignatura; ?>" method="post">
        <label for="nombre_unidad_tema">Nombre de la Unidad/Tema:</label>
        <input type="text" name="nombre_unidad_tema" value="<?php echo $unidadEditar['nombre_unidad_tema']; ?>" required><br>

        <label for="tema">Tema:</label>
        <textarea name="tema" rows="4" required><?php echo $unidadEditar['tema']; ?></textarea><br>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" value="<?php echo $unidadEditar['fecha']; ?>" required><br>

        <input type="submit" value="Guardar cambios">
    </form>

</body>
</html>
