<?php
include("../../../bd.php");

// Inicializar variables
$codigoAsignatura = '';
$idActividadEditar = '';

$fechaActual = date("Y-m-d");

// Verifica si se ha proporcionado el parámetro 'codigo' en la URL
if (isset($_GET['codigo'])) {
    // Obtiene el valor del parámetro 'codigo'
    $codigoAsignatura = $_GET['codigo'];

    // Verifica si se ha proporcionado el parámetro 'id' en la URL
    if (isset($_GET['id'])) {
        // Obtiene el valor del parámetro 'id'
        $idActividadEditar = $_GET['id'];

        // Consulta para obtener los datos de la actividad a editar
        $queryEditar = "SELECT actividad.*, unidad_tema.nombre_unidad_tema, componente_aprendizaje.componente
                        FROM actividad
                        INNER JOIN unidad_tema ON actividad.id_unidad_tema = unidad_tema.id_unidad_tema
                        INNER JOIN componente_aprendizaje ON actividad.id_componente = componente_aprendizaje.id_componente
                        WHERE actividad.id_actividad = '$idActividadEditar'";
        $resultEditar = mysqli_query($conexion, $queryEditar);

        // Verifica si la consulta fue exitosa
        if ($resultEditar && mysqli_num_rows($resultEditar) > 0) {
            $actividadEditar = mysqli_fetch_assoc($resultEditar);
        } else {
            echo "Error al obtener los datos de la actividad a editar: " . mysqli_error($conexion);
            exit();
        }
    } else {
        // Si no se proporciona 'id', redirige a la página principal
        header("Location: actividad.php?codigo=$codigoAsignatura");
        exit();
    }
}

// Consulta para obtener los nombres de las unidades específicas de la asignatura
$queryUnidades = "SELECT id_unidad_tema, nombre_unidad_tema FROM unidad_tema WHERE codigo_asignatura = '$codigoAsignatura'";
$resultUnidades = mysqli_query($conexion, $queryUnidades);

// Consulta para obtener los nombres de los componentes
$queryComponentes = "SELECT id_componente, componente FROM componente_aprendizaje";
$resultComponentes = mysqli_query($conexion, $queryComponentes);

// Verifica si se ha enviado el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $actividad = $_POST['actividad'];
    $duracion_actividad = $_POST['duracion_actividad'];
    $descripcion_actividad = $_POST['descripcion_actividad'];
    $fecha_inicio = $_POST['fecha_inicio_realizacion'];
    $fecha_fin = $_POST['fecha_fin_realizacion'];
    $id_unidad_tema = $_POST['id_unidad_tema'];
    $id_componente = $_POST['id_componente'];
    $recurso = $_POST['descripcion_recurso']; // Nuevo campo

    // Consulta SQL para actualizar los datos en la tabla actividad
    $queryActualizar = "UPDATE actividad 
                        SET actividad = '$actividad', duracion_actividad = '$duracion_actividad', 
                        descripcion_actividad = '$descripcion_actividad', id_unidad_tema = '$id_unidad_tema', 
                        id_componente = '$id_componente', recurso = '$recurso', fecha_inicio_realizacion = '$fecha_inicio',
                        fecha_fin_realizacion = '$fecha_fin'
                        WHERE id_actividad = '$idActividadEditar'";
    
    // Ejecutar la consulta de actualización
    $resultActualizar = mysqli_query($conexion, $queryActualizar);

    // Verificar si la actualización fue exitosa
    if ($resultActualizar) {
        echo "Datos actualizados correctamente.";
        // Redirigir con el código de asignatura en la URL
        header("Location: actividad.php?codigo=$codigoAsignatura");
        exit();
    } else {
        echo "Error al actualizar datos: " . mysqli_error($conexion);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Actividad</title>
    <link rel="stylesheet" href="../../../css/estilo_actividad3.css">
</head>
<body>

<h2 class="Title">Editar Actividad</h2>
<form action="editar.php?codigo=<?php echo $codigoAsignatura; ?>&id=<?php echo $idActividadEditar; ?>" method="post">
    <!-- Campos adicionales según tus necesidades -->
    <label for="actividad">Actividad:</label>
    <input type="text" name="actividad" value="<?php echo $actividadEditar['actividad']; ?>" required><br>

    <label for="fecha_inicio_realizacion">Fecha Inicio Realización:</label>
    <input type="date" name="fecha_inicio_realizacion" min="<?php echo $fechaActual; ?>" value="<?php echo $actividadEditar['fecha_inicio_realizacion']; ?>" required><br>

    <label for="fecha_fin_realizacion">Fecha Fin Realización:</label>
    <input type="date" name="fecha_fin_realizacion" min="<?php echo $fechaActual; ?>" value="<?php echo $actividadEditar['fecha_fin_realizacion']; ?>" required><br>

    <label for="duracion_actividad">Duración de la Actividad:</label>
    <input type="time" name="duracion_actividad" value="<?php echo $actividadEditar['duracion_actividad']; ?>" required><br>
    <br>

    <label for="descripcion_actividad">Descripción de la Actividad:</label>
    <textarea name="descripcion_actividad" rows="4" required><?php echo $actividadEditar['descripcion_actividad']; ?></textarea><br>

    <div class="select-container">
        <label for="id_unidad_tema">Unidad/Tema:</label>
        <select name="id_unidad_tema" required>
            <?php while ($unidad = mysqli_fetch_assoc($resultUnidades)): ?>
                <option value="<?php echo $unidad['id_unidad_tema']; ?>" <?php echo ($unidad['id_unidad_tema'] == $actividadEditar['id_unidad_tema']) ? 'selected' : ''; ?>>
                    <?php echo $unidad['nombre_unidad_tema']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="select-container">
        <label for="id_componente">Componente:</label>
        <select name="id_componente" required>
            <?php while ($componente = mysqli_fetch_assoc($resultComponentes)): ?>
                <option value="<?php echo $componente['id_componente']; ?>" <?php echo ($componente['id_componente'] == $actividadEditar['id_componente']) ? 'selected' : ''; ?>>
                    <?php echo $componente['componente']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <label for="descripcion_recurso">Tipo de Recurso:</label>
    <select name="descripcion_recurso" required>
        <option value="Videos educativos" <?php echo ($actividadEditar['recurso'] == 'Videos educativos') ? 'selected' : ''; ?>>Videos educativos</option>
        <option value="PDFs" <?php echo ($actividadEditar['recurso'] == 'PDFs') ? 'selected' : ''; ?>>PDFs</option>
        <option value="Youtube" <?php echo ($actividadEditar['recurso'] == 'Youtube') ? 'selected' : ''; ?>>Youtube</option>
        <option value="Documentos Word" <?php echo ($actividadEditar['recurso'] == 'Documentos Word') ? 'selected' : ''; ?>>Documentos Word</option>
        <option value="Presentaciones en PowerPoint" <?php echo ($actividadEditar['recurso'] == 'Presentaciones en PowerPoint') ? 'selected' : ''; ?>>Presentaciones en PowerPoint</option>
        <option value="Teams/Zoom" <?php echo ($actividadEditar['recurso'] == 'Teams/Zoom') ? 'selected' : ''; ?>>Teams/Zoom</option>
    </select><br>

    <br>
    <input type="submit" value="Guardar Cambios">
</form>

</body>
</html>

