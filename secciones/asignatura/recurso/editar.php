<?php
include("../../../bd.php");

// Inicializar variables
$codigoAsignatura = '';
$idRecursoEditar = '';

// Verifica si se ha proporcionado el parámetro 'codigo' y 'id' en la URL
if (isset($_GET['codigo']) && isset($_GET['id'])) {
    // Obtiene el valor del parámetro 'codigo'
    $codigoAsignatura = $_GET['codigo'];
    // Obtiene el valor del parámetro 'id'
    $idRecursoEditar = $_GET['id'];

    // Consulta para obtener los nombres de las unidades específicas de la asignatura
    $queryUnidades = "SELECT id_unidad_tema, nombre_unidad_tema FROM unidad_tema WHERE codigo_asignatura = '$codigoAsignatura'";
    $resultUnidades = mysqli_query($conexion, $queryUnidades);

    // Consulta para obtener los nombres de las actividades específicas de la asignatura
    $queryActividades = "SELECT id_actividad, actividad FROM actividad INNER JOIN unidad_tema ON actividad.id_unidad_tema = unidad_tema.id_unidad_tema WHERE unidad_tema.codigo_asignatura = '$codigoAsignatura'";
    $resultActividades = mysqli_query($conexion, $queryActividades);

    // Consulta para obtener los detalles del recurso a editar
    $queryRecursoEditar = "SELECT recurso.id_recurso, recurso.descripcion_recurso, recurso.duracion_recurso, actividad.id_actividad, actividad.actividad, unidad_tema.nombre_unidad_tema
                            FROM recurso
                            INNER JOIN actividad ON recurso.id_actividad = actividad.id_actividad
                            INNER JOIN unidad_tema ON actividad.id_unidad_tema = unidad_tema.id_unidad_tema
                            WHERE recurso.id_recurso = '$idRecursoEditar'";
    $resultRecursoEditar = mysqli_query($conexion, $queryRecursoEditar);

    // Verifica si se encontró el recurso a editar
    if ($resultRecursoEditar && mysqli_num_rows($resultRecursoEditar) > 0) {
        $recursoEditar = mysqli_fetch_assoc($resultRecursoEditar);
    } else {
        echo "No se encontró el recurso a editar.";
        exit();
    }
}

// ... (Resto del código)

// Verifica si se ha enviado el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario para recursos (ajusta según tu estructura)
    $descripcion_recurso = $_POST['descripcion_recurso'];
    $duracion_recurso = $_POST['duracion_recurso'];
    $id_actividad = $_POST['id_actividad'];

    // Consulta SQL para actualizar los datos en la tabla recurso
    $queryActualizar = "UPDATE recurso SET 
                        descripcion_recurso = '$descripcion_recurso', 
                        duracion_recurso = '$duracion_recurso', 
                        id_actividad = '$id_actividad' 
                        WHERE id_recurso = '$idRecursoEditar'";

    // Ejecutar la consulta de actualización
    $resultActualizar = mysqli_query($conexion, $queryActualizar);

    // Verificar si la actualización fue exitosa
    if ($resultActualizar) {
        echo "Datos de recurso actualizados correctamente.";
        // Redirigir con el código de asignatura en la URL
        header("Location: recurso.php?codigo=$codigoAsignatura");
        exit();
    } else {
        echo "Error al actualizar datos de recurso: " . mysqli_error($conexion);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Recurso</title>
    <link rel="stylesheet" href="../../../css/estilo_recurso.css">
</head>
<body>

<h2 class="Title">Editar Recurso</h2>
<form action="editar.php?id=<?php echo $idRecursoEditar; ?>&codigo=<?php echo $codigoAsignatura; ?>" method="post">
    <!-- Agrega aquí campos adicionales según tus necesidades -->
    <label for="descripcion_recurso">Tipo de Recurso:</label>
    <select name="descripcion_recurso" required>
        <option value="Videos educativos" <?php echo ($recursoEditar['descripcion_recurso'] == 'Videos educativos') ? 'selected' : ''; ?>>Videos educativos</option>
        <option value="PDFs" <?php echo ($recursoEditar['descripcion_recurso'] == 'PDFs') ? 'selected' : ''; ?>>PDFs</option>
        <option value="Youtube" <?php echo ($recursoEditar['descripcion_recurso'] == 'Youtube') ? 'selected' : ''; ?>>Youtube</option>
        <option value="Documentos Word" <?php echo ($recursoEditar['descripcion_recurso'] == 'Documentos Word') ? 'selected' : ''; ?>>Documentos Word</option>
        <option value="Presentaciones en PowerPoint" <?php echo ($recursoEditar['descripcion_recurso'] == 'Presentaciones en PowerPoint') ? 'selected' : ''; ?>>Presentaciones en PowerPoint</option>
        <option value="Teams/Zoom" <?php echo ($recursoEditar['descripcion_recurso'] == 'Teams/Zoom') ? 'selected' : ''; ?>>Teams/Zoom</option>
    </select>

    <br>
    <br>



    <label for="duracion_recurso">Duración del Recurso:</label>
    <input type="time" name="duracion_recurso" value="<?php echo $recursoEditar['duracion_recurso']; ?>" required><br>
    <br>

    <div class="select-container">
        <label for="id_actividad">Actividad:</label>
        <select name="id_actividad" required>
            <?php while ($actividad = mysqli_fetch_assoc($resultActividades)): ?>
                <option value="<?php echo $actividad['id_actividad']; ?>" <?php echo ($actividad['id_actividad'] == $recursoEditar['id_actividad']) ? 'selected' : ''; ?>>
                    <?php echo $actividad['actividad']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <br>
    <input type="submit" value="Guardar Cambios">
</form>

</body>
</html>
