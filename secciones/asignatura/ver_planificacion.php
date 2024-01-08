<?php
include("../../bd.php");

if (isset($_GET['codigo'])) {
    $codigoAsignatura = $_GET['codigo'];

    // Asegúrate de escapar las variables correctamente para prevenir SQL injection
    $codigoAsignatura = mysqli_real_escape_string($conexion, $codigoAsignatura);

    // Obtén las semanas disponibles para la asignatura
    $semanasQuery = "SELECT DISTINCT semanas FROM unidad_tema WHERE codigo_asignatura = '$codigoAsignatura';";
    $semanasResult = mysqli_query($conexion, $semanasQuery);
    $semanas = mysqli_fetch_all($semanasResult, MYSQLI_ASSOC);

    // Verifica si se seleccionó una semana
    $semanaSeleccionada = isset($_POST['semana']) ? $_POST['semana'] : (isset($semanas[0]['semanas']) ? $semanas[0]['semanas'] : null);

    // Consulta principal
    $query = "
        SELECT 
            ut.nombre_unidad_tema AS nombre_unidad_tema,
            ut.tema,
            a.actividad,
            a.descripcion_actividad,
            a.duracion_actividad,
            a.recurso,
            c.componente
        FROM unidad_tema ut
        LEFT JOIN actividad a ON ut.id_unidad_tema = a.id_unidad_tema
        LEFT JOIN componente_aprendizaje c ON a.id_componente = c.id_componente
        WHERE ut.codigo_asignatura = '$codigoAsignatura'
        " . ($semanaSeleccionada ? "AND ut.semanas = '$semanaSeleccionada'" : "") . "
        ORDER BY ut.semanas, ut.nombre_unidad_tema, a.actividad;
    ";

    $result = mysqli_query($conexion, $query);

    if ($result) {
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
} else {
    echo "Parámetro 'codigo' no proporcionado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte</title>
    <link rel="stylesheet" href="../../css/estilo_reporte.css">
</head>
<body>

<h2>Reporte de Unidades, Actividades y Recursos</h2>

<?php if (isset($data) && !empty($data)): ?>
    <!-- Formulario para seleccionar la semana -->
    <form method="post" action="">
        <label for="semana">Seleccionar Semana:</label>
        <select name="semana">
            <?php foreach ($semanas as $semana): ?>
                <option value="<?php echo $semana['semanas']; ?>" <?php echo ($semanaSeleccionada == $semana['semanas']) ? 'selected' : ''; ?>>
                    <?php echo $semana['semanas']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="submit" value="Actualizar">
    </form>

    <!-- Tabla de resultados -->
    <table>
        <thead>
        <tr>
            <th>Unidad/Tema</th>
            <th>Tema</th>
            <th>Actividad</th>
            <th>Descripción de la Actividad</th>
            <th>Duración de la Actividad</th>
            <th>Recurso</th>
            <th>Componente</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $prevUnidad = null;
        foreach ($data as $row):
            echo '<tr>';
            if ($prevUnidad !== $row['nombre_unidad_tema']) {
                $rowspan = count(array_filter($data, function ($item) use ($row) {
                    return $item['nombre_unidad_tema'] === $row['nombre_unidad_tema'];
                }));
                echo '<td rowspan="' . $rowspan . '">' . $row['nombre_unidad_tema'] . '</td>';
            }
            echo '<td>' . $row['tema'] . '</td>';
            echo '<td>' . $row['actividad'] . '</td>';
            echo '<td>' . $row['descripcion_actividad'] . '</td>';
            echo '<td>' . $row['duracion_actividad'] . '</td>';
            echo '<td>' . $row['recurso'] . '</td>';
            echo '<td>' . $row['componente'] . '</td>';
            echo '</tr>';
            $prevUnidad = $row['nombre_unidad_tema'];
        endforeach;
        ?>
        </tbody>
    </table>

<?php else: ?>
    <p>No hay datos disponibles para mostrar.</p>
<?php endif; ?>

</body>
</html>
