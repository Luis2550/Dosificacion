<?php
include("../../bd.php");

// Verifica si se ha proporcionado el parámetro 'codigo' en la URL
if (isset($_GET['codigo'])) {
    // Obtiene el valor del parámetro 'codigo'
    $codigoAsignatura = $_GET['codigo'];

    // Consulta para obtener los datos de la asignatura
    $query = "SELECT * FROM asignatura WHERE codigo_asignatura = '$codigoAsignatura'";
    $result = mysqli_query($conexion, $query);

    // Verifica si se obtuvieron resultados
    if ($result) {
        // Obtiene la fila de resultados como un array asociativo
        $asignatura = mysqli_fetch_assoc($result);
    }
}

// Cierra la conexión a la base de datos
mysqli_close($conexion);
?>

<?php include("../../templates/header.php");?>

<main>
    <?php if (isset($asignatura)): ?>
        <h2>Información de la Asignatura</h2>
        <ul>
            <li><b>Modalidad:</b> <?php echo $asignatura['modalidad']; ?></li>
            <li><b>Periodo Académico:</b> <?php echo $asignatura['periodo_academico']; ?></li>
            <li><b>Fecha de Elaboración:</b> <?php echo $asignatura['fecha_elaboracion']; ?></li>
            <li><b>Duración:</b> <?php echo $asignatura['duracion_asignatura']; ?></li>
            <li><b>Metodología de Enseñanza:</b> <?php echo $asignatura['metodologia_ensenanza']; ?></li>
        </ul>
    <?php else: ?>
        <p>No se proporcionó un código de asignatura válido.</p>
    <?php endif; ?>

    <h2>Menú de Registro</h2>
    <div class="registro-menu">
        <ul>
            <li><a href="./unidad/unidad.php?codigo=<?php echo $codigoAsignatura; ?>" class="btn-planificacion2">Registrar Unidad</a></li>
            
            <li><a href="./actividad/actividad.php?codigo=<?php echo $codigoAsignatura; ?>" class="btn-planificacion2">Registrar Actividad</a></li>
            <li><a href="./recurso/recurso.php?codigo=<?php echo $codigoAsignatura; ?>" class="btn-planificacion2">Registrar Recurso</a></li>
        </ul>
    </div>
</main>

<?php include("../../templates/footer.php"); ?>
