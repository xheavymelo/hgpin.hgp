<?php
session_start();

// Verificar si el usuario está logueado
//if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//    header("Location: login.php");
//    exit();
//}

// Conexión a la base de datos
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "soporte_tecnico";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario de agregar usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $rol = $_POST['rol'];
    $username = $_POST['username'];

    // Crear contraseña igual al nombre de usuario
    $password = password_hash($username, PASSWORD_DEFAULT);

    // Insertar nuevo usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre, apellido, fecha_nacimiento, rol, username, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $apellido, $fecha_nacimiento, $rol, $username, $password);
    
    if ($stmt->execute()) {
        $success = "Usuario agregado exitosamente.";
    } else {
        $error = "Error al agregar usuario.";
    }

    // Cerrar la declaración
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Gestionar Usuarios</h1>
        <a href="login.php" class="button" style="float: right;">Cerrar Sesión</a>
    </header>
    <section class="form-section">
        <h2>Agregar Usuario</h2>
        <form action="manage_users.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

            <label for="rol">Rol:</label>
            <input type="text" id="rol" name="rol" required>

            <label for="username">Nombre de Usuario (ID):</label>
            <input type="text" id="username" name="username" required>

            <button type="submit" name="add_user">Agregar Usuario</button>
        </form>
        <?php if (isset($success)) echo "<p>$success</p>"; ?>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </section>
</body>
</html>
