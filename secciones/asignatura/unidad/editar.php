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
        $semanas = $_POST['semanas']; // Agregado

        // Consulta SQL para actualizar los datos en la tabla unidad_tema
        $queryActualizar = "UPDATE unidad_tema 
                            SET nombre_unidad_tema = '$nombreUnidadTema', tema = '$tema', fecha = '$fecha', semanas = '$semanas' 
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
    <link rel="stylesheet" href="../../../css/estilo_unidad1.css">
</head>
<body>

    <h2 class="Title">Formulario Edición Unidad Tema</h2>
    <form action="editar.php?id=<?php echo $idUnidad; ?>&codigo=<?php echo $codigoAsignatura; ?>" method="post">

    <select name="nombre_unidad_tema" class="styled-select" required>
    <option value="Unidad 1" <?php if(isset($unidadEditar['nombre_unidad_tema']) && $unidadEditar['nombre_unidad_tema'] == 'Unidad 1') echo 'selected'; ?>>Unidad 1</option>
    <option value="Unidad 2" <?php if(isset($unidadEditar['nombre_unidad_tema']) && $unidadEditar['nombre_unidad_tema'] == 'Unidad 2') echo 'selected'; ?>>Unidad 2</option>
    <option value="Unidad 3" <?php if(isset($unidadEditar['nombre_unidad_tema']) && $unidadEditar['nombre_unidad_tema'] == 'Unidad 3') echo 'selected'; ?>>Unidad 3</option>
    <option value="Unidad 4" <?php if(isset($unidadEditar['nombre_unidad_tema']) && $unidadEditar['nombre_unidad_tema'] == 'Unidad 4') echo 'selected'; ?>>Unidad 4</option>
    <option value="Unidad 5" <?php if(isset($unidadEditar['nombre_unidad_tema']) && $unidadEditar['nombre_unidad_tema'] == 'Unidad 5') echo 'selected'; ?>>Unidad 5</option>
</select><br>

<select name="tema" class="styled-select" required>
    <option value="Aritmética Básica" <?php if(isset($unidadEditar['tema']) && $unidadEditar['tema'] == 'Aritmética Básica') echo 'selected'; ?>>Aritmética Básica</option>
    <option value="Álgebra Elemental" <?php if(isset($unidadEditar['tema']) && $unidadEditar['tema'] == 'Álgebra Elemental') echo 'selected'; ?>>Álgebra Elemental</option>
    <option value="Geometría Básica" <?php if(isset($unidadEditar['tema']) && $unidadEditar['tema'] == 'Geometría Básica') echo 'selected'; ?>>Geometría Básica</option>
    <option value="Probabilidades y Estadísticas" <?php if(isset($unidadEditar['tema']) && $unidadEditar['tema'] == 'Probabilidades y Estadísticas') echo 'selected'; ?>>Probabilidades y Estadísticas</option>
    <option value="Números y Operaciones" <?php if(isset($unidadEditar['tema']) && $unidadEditar['tema'] == 'Números y Operaciones') echo 'selected'; ?>>Números y Operaciones</option>
    <!-- Puedes agregar más opciones según sea necesario -->
</select><br>


        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" value="<?php echo $unidadEditar['fecha']; ?>" required><br>

        <label for="semanas">Semanas:</label>
        <select name="semanas" class="styled-select" required>
            <?php
            // Genera las opciones del select para las semanas
            $options = '';
            for ($semana = 1; $semana <= 16; $semana++) {
                $valor = 'Semana ' . $semana;
                $nombre = 'Semana ' . $semana;
                $selected = ($unidadEditar['semanas'] == $valor) ? 'selected' : '';
                $options .= "<option value=\"$valor\" $selected>$nombre</option>";
            }
            echo $options;
            ?>
        </select><br>

        <input type="submit" value="Guardar cambios">
    </form>

</body>
</html>
