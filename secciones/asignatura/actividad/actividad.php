<?php
include("../../../bd.php");

$fechaActual = date("Y-m-d");

// Inicializar variables
$codigoAsignatura = '';

// Verifica si se ha proporcionado el parámetro 'codigo' en la URL
if (isset($_GET['codigo'])) {
    // Obtiene el valor del parámetro 'codigo'
    $codigoAsignatura = $_GET['codigo'];

    // Consulta para obtener los nombres de las unidades y temas específicos de la asignatura
    $queryUnidades = "SELECT DISTINCT nombre_unidad_tema FROM unidad_tema WHERE codigo_asignatura = '$codigoAsignatura'";
    $resultUnidades = mysqli_query($conexion, $queryUnidades);

    // Consulta para obtener los nombres de los componentes
    $queryComponentes = "SELECT id_componente, componente FROM componente_aprendizaje";
    $resultComponentes = mysqli_query($conexion, $queryComponentes);
}

// Verifica si se ha proporcionado el parámetro 'codigo' en la URL
if (isset($_GET['codigo'])) {
    // Obtiene el valor del parámetro 'codigo'
    $codigoAsignatura = $_GET['codigo'];

    // Verifica si se ha enviado la acción de borrar y el ID de la actividad
    if (isset($_GET['accion']) && $_GET['accion'] === 'borrar' && isset($_GET['id'])) {
        $idActividadBorrar = $_GET['id'];

        // Consulta SQL para borrar la actividad
        $queryBorrar = "DELETE FROM actividad WHERE id_actividad = '$idActividadBorrar'";
        $resultBorrar = mysqli_query($conexion, $queryBorrar);

        // Verifica si el borrado fue exitoso
        if ($resultBorrar) {
            echo "Actividad borrada correctamente.";
            // Redirige para evitar reenvío del formulario al recargar la página
            header("Location: actividad.php?codigo=$codigoAsignatura");
            exit();
        } else {
            echo "Error al borrar la actividad: " . mysqli_error($conexion);
        }
    }
}

// Verifica si se ha proporcionado el parámetro 'codigo' en la URL
if (isset($_GET['codigo'])) {
    // Obtiene el valor del parámetro 'codigo'
    $codigoAsignatura = $_GET['codigo'];

    // Consulta para obtener las actividades de la asignatura
    $queryActividades = "SELECT actividad.id_actividad, actividad.actividad, actividad.duracion_actividad, actividad.descripcion_actividad, unidad_tema.nombre_unidad_tema, unidad_tema.tema, componente_aprendizaje.componente, actividad.recurso, actividad.fecha_inicio_realizacion, actividad.fecha_fin_realizacion
        FROM actividad
        INNER JOIN unidad_tema ON actividad.id_unidad_tema = unidad_tema.id_unidad_tema
        INNER JOIN componente_aprendizaje ON actividad.id_componente = componente_aprendizaje.id_componente
        WHERE unidad_tema.codigo_asignatura = '$codigoAsignatura'
        ORDER BY unidad_tema.nombre_unidad_tema";
    $resultActividades = mysqli_query($conexion, $queryActividades);
}

function obtenerConteoUnidad($nombreUnidad) {
    global $conexion, $codigoAsignatura;

    $queryConteo = "SELECT COUNT(*) AS conteo FROM actividad
                    INNER JOIN unidad_tema ON actividad.id_unidad_tema = unidad_tema.id_unidad_tema
                    WHERE unidad_tema.codigo_asignatura = '$codigoAsignatura'
                    AND unidad_tema.nombre_unidad_tema = '$nombreUnidad'";

    $resultConteo = mysqli_query($conexion, $queryConteo);

    if ($resultConteo) {
        $row = mysqli_fetch_assoc($resultConteo);
        return $row['conteo'];
    } else {
        return 1; // Devolver 1 por defecto si hay un error en la consulta
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $actividad = $_POST['actividad'];
    $duracion_actividad = $_POST['duracion_actividad'];
    $descripcion_actividad = $_POST['descripcion_actividad'];
    $id_unidad_tema = $_POST['id_unidad_tema'];
    $id_componente = $_POST['id_componente'];
    $recurso = $_POST['descripcion_recurso'];
    $fecha_inicio_realizacion = $_POST['fecha_inicio_realizacion'];
    $fecha_fin_realizacion = $_POST['fecha_fin_realizacion'];

    // Consulta SQL para insertar los datos en la tabla actividad
    $query = "INSERT INTO actividad (actividad, duracion_actividad, descripcion_actividad, id_unidad_tema, id_componente, recurso, fecha_inicio_realizacion, fecha_fin_realizacion) 
              VALUES ('$actividad', '$duracion_actividad', '$descripcion_actividad', '$id_unidad_tema', '$id_componente', '$recurso', '$fecha_inicio_realizacion', '$fecha_fin_realizacion')";
              
    // Ejecutar la consulta
    $result = mysqli_query($conexion, $query);

    // Verificar si la inserción fue exitosa
    if ($result) {
        echo "Datos insertados correctamente.";
        // Redirigir con el código de asignatura en la URL
        header("Location: actividad.php?codigo=$codigoAsignatura");
        exit();
    } else {
        echo "Error al insertar datos: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Actividad</title>
    <link rel="stylesheet" href="../../../css/estilo_actividad3.css">
</head>
<body>

<a href="../index.php?codigo=<?php echo $codigoAsignatura;?>" class="regresar">Regresar</a>

<h2 class="Title">Formulario de Actividad</h2>
<form action="actividad.php?codigo=<?php echo $codigoAsignatura; ?>" method="post">
    <!-- Agrega aquí campos adicionales según tus necesidades -->
    <label for="actividad">Actividad:</label>
    <input type="text" name="actividad" required><br>

    <label for="fecha_inicio_realizacion">Fecha Inicio Realización:</label>
    <input type="date" name="fecha_inicio_realizacion" min="<?php echo $fechaActual; ?>" required><br>

    <label for="fecha_fin_realizacion">Fecha Fin Realización:</label>
    <input type="date" name="fecha_fin_realizacion" min="<?php echo $fechaActual; ?>"  required><br>

    <label for="duracion_actividad">Duración de la Actividad:</label>
    <input type="time" name="duracion_actividad" required><br>
    <br>

    <label for="descripcion_actividad">Descripción de la Actividad:</label>
    <textarea name="descripcion_actividad" rows="4" required></textarea><br>

    <div class="select-container">
    <label for="id_unidad_tema">Unidad/Tema:</label>
    <select name="id_unidad_tema" required>
        <?php 
        while ($unidad = mysqli_fetch_assoc($resultUnidades)): 
        ?>
            <option disabled><?php echo $unidad['nombre_unidad_tema']; ?></option>
            <?php
            // Consulta para obtener los temas asociados a la unidad actual
            $queryTemas = "SELECT id_unidad_tema, tema FROM unidad_tema WHERE nombre_unidad_tema = '{$unidad['nombre_unidad_tema']}' AND codigo_asignatura = '$codigoAsignatura'";
            $resultTemas = mysqli_query($conexion, $queryTemas);

            while ($tema = mysqli_fetch_assoc($resultTemas)): ?>
                <option value="<?php echo $tema['id_unidad_tema']; ?>"><?php echo $tema['tema']; ?></option>
            <?php endwhile; ?>
        <?php endwhile; ?>
    </select>
</div>


<div class="select-container">
    <label for="id_componente">Componente:</label>
    <select name="id_componente" required>
        <?php while ($componente = mysqli_fetch_assoc($resultComponentes)): ?>
            <option value="<?php echo $componente['id_componente']; ?>"><?php echo $componente['componente']; ?></option>
        <?php endwhile; ?>
    </select>
</div>

<label for="descripcion_recurso">Tipo de Recurso:</label>
    <select name="descripcion_recurso" required>
        <option value="Videos educativos">Videos educativos</option>
        <option value="PDFs">PDFs</option>
        <option value="Youtube">Youtube</option>
        <option value="Documentos Word">Documentos Word</option>
        <option value="Presentaciones en PowerPoint">Presentaciones en PowerPoint</option>
        <option value="Teams/Zoom">Teams/Zoom</option>
    </select><br>
    
    <br>
    <input type="submit" value="Guardar">
</form>

<h2 class="Title">Tabla de Actividades</h2>

<?php if (isset($resultActividades) && mysqli_num_rows($resultActividades) > 0): ?>
    <table border="1">
    <thead>
        <tr>
            <th>Unidad</th>
            <th>Tema</th>
            <th>Actividad</th>
            <th>Duración</th>
            <th>Descripción</th>
            <th>Componente</th>
            <th>Recurso</th>
            <th>Fecha Inicio Realización</th>
            <th>Fecha Fin Realización</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $prevUnidad = null; // Variable para almacenar la unidad anterior
        while ($actividad = mysqli_fetch_assoc($resultActividades)):
        ?>
            <tr>
                <?php
                // Comprobar si la unidad es diferente a la anterior
                if ($actividad['nombre_unidad_tema'] != $prevUnidad):
                ?>
                    <td class="unit-cell" rowspan="<?php echo obtenerConteoUnidad($actividad['nombre_unidad_tema']); ?>">
                        <?php echo $actividad['nombre_unidad_tema']; ?>
                    </td>
                <?php
                endif;
                ?>
                <td><?php echo $actividad['tema']; ?></td>
                <td><?php echo $actividad['actividad']; ?></td>
                <td><?php echo $actividad['duracion_actividad']; ?></td>
                <td><?php echo $actividad['descripcion_actividad']; ?></td>
                <td><?php echo $actividad['componente']; ?></td>
                <td><?php echo $actividad['recurso']; ?></td>
                <td><?php echo $actividad['fecha_inicio_realizacion']; ?></td>
                <td><?php echo $actividad['fecha_fin_realizacion']; ?></td>
                <td class="actions-column">
                    <a href="editar.php?id=<?php echo $actividad['id_actividad']; ?>&codigo=<?php echo $codigoAsignatura; ?>" class="btn-editar">Editar</a>
                    <a href="?accion=borrar&id=<?php echo $actividad['id_actividad']; ?>&codigo=<?php echo $codigoAsignatura; ?>" class="btn-borrar">Borrar</a>
                </td>
            </tr>
            <?php
            // Almacenar la unidad actual para comparar con la siguiente iteración
            $prevUnidad = $actividad['nombre_unidad_tema'];
        endwhile;
        ?>
    </tbody>
</table>


<?php else: ?>
    <p>No hay actividades disponibles para esta asignatura.</p>
<?php endif; ?>


</body>
</html>
