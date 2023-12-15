<?php
include("../../../bd.php");

// Inicializar variables
$codigoAsignatura = '';

// Verifica si se ha proporcionado el parámetro 'codigo' en la URL
if (isset($_GET['codigo'])) {
    // Obtiene el valor del parámetro 'codigo'
    $codigoAsignatura = $_GET['codigo'];

    // Consulta para obtener los nombres de las unidades específicas de la asignatura
    $queryUnidades = "SELECT id_unidad_tema, nombre_unidad_tema FROM unidad_tema WHERE codigo_asignatura = '$codigoAsignatura'";
    $resultUnidades = mysqli_query($conexion, $queryUnidades);

    // Consulta para obtener los nombres de las actividades específicas de la asignatura
    $queryActividades = "SELECT id_actividad, actividad FROM actividad INNER JOIN unidad_tema ON actividad.id_unidad_tema = unidad_tema.id_unidad_tema WHERE unidad_tema.codigo_asignatura = '$codigoAsignatura'";
    $resultActividades = mysqli_query($conexion, $queryActividades);
}

// Verifica si se ha proporcionado el parámetro 'codigo' en la URL
if (isset($_GET['codigo'])) {
    // Obtiene el valor del parámetro 'codigo'
    $codigoAsignatura = $_GET['codigo'];

    // Verifica si se ha enviado la acción de borrar y el ID del recurso
    if (isset($_GET['accion']) && $_GET['accion'] === 'borrar' && isset($_GET['id'])) {
        $idRecursoBorrar = $_GET['id'];

        // Consulta SQL para borrar el recurso
        $queryBorrar = "DELETE FROM recurso WHERE id_recurso = '$idRecursoBorrar'";
        $resultBorrar = mysqli_query($conexion, $queryBorrar);

        // Verifica si el borrado fue exitoso
        if ($resultBorrar) {
            echo "Recurso borrado correctamente.";
            // Redirige para evitar reenvío del formulario al recargar la página
            header("Location: recurso.php?codigo=$codigoAsignatura");
            exit();
        } else {
            echo "Error al borrar el recurso: " . mysqli_error($conexion);
        }
    }
}

// Verifica si se ha proporcionado el parámetro 'codigo' en la URL
if (isset($_GET['codigo'])) {
    // Obtiene el valor del parámetro 'codigo'
    $codigoAsignatura = $_GET['codigo'];

    // Consulta para obtener los recursos relacionados con las actividades
    $queryRecursos = "SELECT recurso.id_recurso, recurso.descripcion_recurso, recurso.duracion_recurso, actividad.actividad, unidad_tema.nombre_unidad_tema
                      FROM recurso
                      INNER JOIN actividad ON recurso.id_actividad = actividad.id_actividad
                      INNER JOIN unidad_tema ON actividad.id_unidad_tema = unidad_tema.id_unidad_tema
                      WHERE unidad_tema.codigo_asignatura = '$codigoAsignatura'";
    $resultRecursos = mysqli_query($conexion, $queryRecursos);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario para recursos (ajusta según tu estructura)
    $descripcion_recurso = $_POST['descripcion_recurso'];
    $duracion_recurso = $_POST['duracion_recurso'];
    $id_actividad = $_POST['id_actividad'];

    // Consulta SQL para insertar los datos en la tabla recurso
    $query = "INSERT INTO recurso (descripcion_recurso, duracion_recurso, id_actividad) 
              VALUES ('$descripcion_recurso', '$duracion_recurso', '$id_actividad')";
              
    // Ejecutar la consulta
    $result = mysqli_query($conexion, $query);

    // Verificar si la inserción fue exitosa
    if ($result) {
        echo "Datos de recurso insertados correctamente.";
        // Redirigir con el código de asignatura en la URL
        header("Location: recurso.php?codigo=$codigoAsignatura");
        exit();
    } else {
        echo "Error al insertar datos de recurso: " . mysqli_error($conexion);
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Recurso</title> 
    <link rel="stylesheet" href="../../../css/estilo_recurso.css">
</head>
<body>

<a href="../index.php?codigo=<?php echo $codigoAsignatura;?>" class="regresar">Regresar</a>

<h2 class="Title">Formulario de Recurso</h2>
<form action="recurso.php?codigo=<?php echo $codigoAsignatura; ?>" method="post">
    <!-- Agrega aquí campos adicionales según tus necesidades -->
    <label for="descripcion_recurso">Tipo de Recurso:</label>
    <select name="descripcion_recurso" required>
        <option value="Videos educativos">Videos educativos</option>
        <option value="PDFs">PDFs</option>
        <option value="Youtube">Youtube</option>
        <option value="Documentos Word">Documentos Word</option>
        <option value="Presentaciones en PowerPoint">Presentaciones en PowerPoint</option>
        <option value="Teams/Zoom">Teams/Zoom</option>
    </select>
    <br>
    <br>


    <label for="duracion_recurso">Duración del Recurso:</label>
    <input type="time" name="duracion_recurso" required><br>
    <br>

    <div class="select-container">
        <label for="id_actividad">Actividad:</label>
        <select name="id_actividad" required>
            <?php while ($actividad = mysqli_fetch_assoc($resultActividades)): ?>
                <option value="<?php echo $actividad['id_actividad']; ?>"><?php echo $actividad['actividad']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <br>
    <input type="submit" value="Guardar Recurso">
</form>

<h2 class="Title">Tabla de Recursos</h2>

<?php if (isset($resultRecursos) && mysqli_num_rows($resultRecursos) > 0): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Unidad</th>
                <th>Actividad</th>
                <th>Recurso</th>
                <th>Duración del Recurso</th>
                <th>Opciones</th> <!-- Nueva columna -->
            </tr>
        </thead>
        <tbody>
            <?php while ($recurso = mysqli_fetch_assoc($resultRecursos)): ?>
                <tr>
                    <td><?php echo $recurso['nombre_unidad_tema']; ?></td>
                    <td><?php echo $recurso['actividad']; ?></td>
                    <td><?php echo $recurso['descripcion_recurso']; ?></td>
                    <td><?php echo $recurso['duracion_recurso']; ?></td>
                    <td class="actions-column">
                        <a href="editar.php?id=<?php echo $recurso['id_recurso']; ?>&codigo=<?php echo $codigoAsignatura; ?>" class="btn-editar">Editar</a>
                        <a href="?accion=borrar&id=<?php echo $recurso['id_recurso']; ?>&codigo=<?php echo $codigoAsignatura; ?>" class="btn-borrar">Borrar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay recursos disponibles para esta asignatura.</p>
<?php endif; ?>

</body>
</html>

