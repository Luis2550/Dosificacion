<?php
include("templates/header.php");

include("bd.php");

// Supongamos que tienes el ID del usuario que quieres mostrar (puedes ajustar esto según tus necesidades)
$id_usuario = $_SESSION['id'];

// Consulta para obtener la información de todas las asignaturas del usuario
$consulta = $conexion->prepare("SELECT codigo_asignatura, nombre_asignatura, descripcion_asignatura, modalidad, periodo_academico, fecha_elaboracion, duracion_asignatura, metodologia_ensenanza FROM asignatura WHERE id_profesor = ?");
$consulta->bind_param('i', $id_usuario); // 'i' indica que el parámetro es de tipo entero
$consulta->execute();

$resultado = $consulta->get_result();
$asignaturas = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<section>
    <?php foreach ($asignaturas as $asignatura): ?>
        <div class="curso">
            <h2><?php echo $asignatura['nombre_asignatura']; ?></h2>
            <p><?php echo $asignatura['descripcion_asignatura']; ?></p>

            <div class="botones">
                <button class="btn-planificacion" onclick="window.location.href='<?php echo $urlBase; ?>secciones/asignatura/index.php?codigo=<?php echo $asignatura['codigo_asignatura']; ?>'">Planificar el Curso</button>
                <button class="btn-planificacion" onclick="window.location.href='<?php echo $urlBase; ?>secciones/asignatura/ver_planificacion.php?codigo=<?php echo $asignatura['codigo_asignatura']; ?>'">Ver Planificación</button>
            </div>
            
        </div>
    <?php endforeach; ?>
</section>

<?php include("templates/footer.php"); ?>
