<!DOCTYPE html>
<html>
<head>
    <title>ABC Personas y Cuentas Bancarias</title>
</head>
<body>
    <h1>ABC Personas y Cuentas Bancarias</h1>

    <?php
    $conexion = new mysqli('localhost', 'root', '', 'bdjhonatan');

    if ($conexion->connect_error) {
        die("Error en la conexión a la base de datos: " . $conexion->connect_error);
    }

    function obtenerPersonas($conexion) {
        $query = "SELECT * FROM Persona";
        $result = $conexion->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function obtenerCuentasBancarias($conexion, $persona_id) {
        $query = "SELECT * FROM CuentaBancaria WHERE persona_id = $persona_id";
        $result = $conexion->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    if (isset($_POST['agregar_persona'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $departamento = $_POST['departamento'];
        $email = $_POST['email'];

        $query = "INSERT INTO Persona (nombre, apellido, departamento, email) VALUES ('$nombre', '$apellido', '$departamento', '$email')";
        if ($conexion->query($query) === TRUE) {
            echo "Persona agregada correctamente.";
        } else {
            echo "Error al agregar persona: " . $conexion->error;
        }
    }

    echo "<h2>Agregar Persona:</h2>";
    echo "<form method='post'>";
    echo "Nombre: <input type='text' name='nombre'><br>";
    echo "Apellido: <input type='text' name='apellido'><br>";
    echo "Departamento: <input type='text' name='departamento'><br>";
    echo "Email: <input type='text' name='email'><br>";
    echo "<input type='submit' name='agregar_persona' value='Agregar'>";
    echo "</form>";

    $personas = obtenerPersonas($conexion);

    echo "<h2>Seleccione una persona:</h2>";
    echo "<form method='post'>";
    echo "<select name='persona_id'>";
    foreach ($personas as $persona) {
        echo "<option value='" . $persona['id'] . "'>" . $persona['nombre'] . " " . $persona['apellido'] . "</option>";
    }
    echo "</select>";
    echo "<input type='submit' name='seleccionar_persona' value='Seleccionar'>";
    echo "</form>";

    if (isset($_POST['seleccionar_persona'])) {
        $persona_id = $_POST['persona_id'];
        $cuentas_bancarias = obtenerCuentasBancarias($conexion, $persona_id);

        echo "<h2>Cuentas Bancarias de la Persona:</h2>";
        echo "<ul>";
        foreach ($cuentas_bancarias as $cuenta) {
            echo "<li>" . $cuenta['tipo'] . " - Saldo: $" . $cuenta['saldo'] . "</li>";
        }
        echo "</ul>";

        echo "<h2>Editar/Eliminar Persona:</h2>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='persona_id' value='$persona_id'>";
        echo "Nombre: <input type='text' name='nombre' value='" . $personas[0]['nombre'] . "'><br>";
        echo "Apellido: <input type='text' name='apellido' value='" . $personas[0]['apellido'] . "'><br>";
        echo "Departamento: <input type='text' name='departamento' value='" . $personas[0]['departamento'] . "'><br>";
        echo "Email: <input type='text' name='email' value='" . $personas[0]['email'] . "'><br>";
        echo "<input type='submit' name='editar_persona' value='Editar'>";
        echo "<input type='submit' name='eliminar_persona' value='Eliminar'>";
        echo "</form>";
    }

    if (isset($_POST['editar_persona'])) {
        $persona_id = $_POST['persona_id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $departamento = $_POST['departamento'];
        $email = $_POST['email'];

        $query = "UPDATE Persona SET nombre='$nombre', apellido='$apellido', departamento='$departamento', email='$email' WHERE id=$persona_id";
        if ($conexion->query($query) === TRUE) {
            echo "Persona editada correctamente.";
        } else {
            echo "Error al editar persona: " . $conexion->error;
        }
    }

    if (isset($_POST['eliminar_persona'])) {
        $persona_id = $_POST['persona_id'];

        $query = "DELETE FROM Persona WHERE id=$persona_id";
        if ($conexion->query($query) === TRUE) {
            echo "Persona eliminada correctamente.";
        } else {
            echo "Error al eliminar persona: " . $conexion->error;
        }
    }

    $conexion->close();
    ?>
</body>
</html>
