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
    $semanas = $_POST['semanas']; // Nuevo campo semanas
    $fecha = $_POST['fecha'];
    $codigoAsignatura = $_POST['codigo_asignatura'];

    // Consulta SQL para insertar los datos en la tabla unidad_tema
    $query = "INSERT INTO unidad_tema (nombre_unidad_tema, tema, semanas, fecha, codigo_asignatura) 
              VALUES ('$nombreUnidadTema', '$tema', '$semanas', '$fecha', '$codigoAsignatura')";

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
    // Consulta para obtener las unidades de temas de la asignatura con orden por nombre
        $query = "SELECT * FROM unidad_tema WHERE codigo_asignatura = '$codigoAsignatura' ORDER BY nombre_unidad_tema";
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
    <link rel="stylesheet" href="../../../css/estilo_unidad1.css">
</head>
<body>

<a href="../index.php?codigo=<?php echo $codigoAsignatura;?>" class="regresar">Regresar</a>

    <h2 class="Title">Formulario Unidad Tema</h2>
    <form action="unidad.php" method="post">

    <select name="nombre_unidad_tema" class="styled-select" required>
        <option value="Unidad 1">Unidad 1</option>
        <option value="Unidad 2">Unidad 2</option>
        <option value="Unidad 3">Unidad 3</option>
        <option value="Unidad 4">Unidad 4</option>
        <option value="Unidad 5">Unidad 5</option>
    </select><br>

    <select name="tema" class="styled-select" required>
        <option value="Aritmética Básica">Aritmética Básica</option>
        <option value="Álgebra Elemental">Álgebra Elemental</option>
        <option value="Geometría Básica">Geometría Básica</option>
        <option value="Probabilidades y Estadísticas">Probabilidades y Estadísticas</option>
        <option value="Números y Operaciones">Números y Operaciones</option>
        <!-- Puedes agregar más opciones según sea necesario -->
    </select><br>

    <?php
// Genera las opciones del select para las semanas
$options = '';
for ($semana = 1; $semana <= 16; $semana++) {
    $valor = 'Semana ' . $semana;
    $nombre = 'Semana ' . $semana;
    $options .= "<option value=\"$valor\">$nombre</option>";
}
?>

<!-- HTML para el select de semanas -->
<select name="semanas" class="styled-select" required>
    <?php echo $options; ?>
</select><br>


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
                <th>Semana</th>
                <th>Fecha</th>
                <th class="actions-column">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Establecer el idioma a español
            setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');

            $prevUnidad = null; // Variable para almacenar la unidad anterior

            foreach ($unidades as $unidad):
                ?>
                <tr>
                    <?php if ($prevUnidad !== $unidad['nombre_unidad_tema']): ?>
                        <!-- Si la unidad es diferente a la anterior, mostrarla y combinar las celdas -->
                        <td rowspan="<?php echo count(array_filter($unidades, function($item) use ($unidad) {
                            return $item['nombre_unidad_tema'] === $unidad['nombre_unidad_tema'];
                        })); ?>">
                            <?php echo $unidad['nombre_unidad_tema']; ?>
                        </td>
                    <?php endif; ?>
                    <td><?php echo $unidad['tema']; ?></td>
                    <td><?php echo $unidad['semanas']; ?></td>
                    <td><?php echo $unidad['fecha']; ?></td>
    
                    <td class="actions-column">
                        <a href="editar.php?id=<?php echo $unidad['id_unidad_tema']; ?>&codigo=<?php echo $codigoAsignatura; ?>" class="btn-editar">Editar</a>
                        <a href="?accion=borrar&id=<?php echo $unidad['id_unidad_tema']; ?>&codigo=<?php echo $codigoAsignatura; ?>" class="btn-borrar">Borrar</a>
                    </td>
                </tr>
                <?php
                $prevUnidad = $unidad['nombre_unidad_tema']; // Actualizar la unidad anterior
            endforeach;
            ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay unidades de temas disponibles para esta asignatura.</p>
<?php endif; ?>

</body>
</html>
