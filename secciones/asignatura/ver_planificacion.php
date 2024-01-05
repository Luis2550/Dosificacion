<?php
include("../../bd.php");

if (isset($_GET['codigo'])) {
    $codigoAsignatura = $_GET['codigo'];

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
        ORDER BY ut.nombre_unidad_tema, a.actividad;
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
        $totalHoras = 0;
        $totalHorasPorComponente = array();

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

            // Calcular total de horas
            $totalHoras += strtotime($row['duracion_actividad']);
            
            // Calcular total de horas por componente
            if (!isset($totalHorasPorComponente[$row['componente']])) {
                $totalHorasPorComponente[$row['componente']] = 0;
            }
            $totalHorasPorComponente[$row['componente']] += strtotime($row['duracion_actividad']);
        endforeach;
        ?>
        </tbody>
    </table>

    <h3>Total de horas por componente:</h3>
    <table>
        <thead>
        <tr>
            <th>Componente</th>
            <th>Total de Horas</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($totalHorasPorComponente as $componente => $totalHorasComponente):
            echo '<tr>';
            echo '<td>' . $componente . '</td>';
            echo '<td>' . date('H:i', $totalHorasComponente) . '</td>';
            echo '</tr>';
        endforeach;
        ?>
        </tbody>
    </table>

    <p>Total de horas: <?php echo date('H:i', $totalHoras); ?></p>

<?php else: ?>
    <p>No hay datos disponibles para mostrar.</p>
<?php endif; ?>

</body>
</html>
