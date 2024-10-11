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

// Obtener todos los usuarios
$sql = "SELECT id, username FROM usuarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuarios = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $usuarios = [];
}

// Cerrar la conexión
$conn->close();

// Función para restablecer la contraseña
function restablecer_contrasena($usuario_id) {
    global $conn; // Asegúrate de usar la conexión correcta
    $nueva_contrasena = password_hash("nueva_contrasena", PASSWORD_DEFAULT); // Cambia esto a la nueva contraseña que desees
    $sql = "UPDATE usuarios SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nueva_contrasena, $usuario_id);
    $stmt->execute();
    $stmt->close();
    return $stmt->affected_rows > 0; // Devuelve verdadero si se actualizó
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Usuarios</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Lista de Usuarios</h1>
        <a href="admin.php" class="button">Volver al Panel de Administración</a>
    </header>

    <section class="admin-section">
        <h2>Usuarios Registrados</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['username']); ?></td>
                    <td>
                        <form action="" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas restablecer la contraseña?');">
                            <input type="hidden" name="usuario_id" value="<?php echo htmlspecialchars($usuario['id']); ?>">
                            <button type="submit" name="restablecer">Restablecer Contraseña</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php
        // Procesar el restablecimiento de la contraseña
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['restablecer'])) {
            $usuario_id = $_POST['usuario_id'];
            if (restablecer_contrasena($usuario_id)) {
                echo "<p>Contraseña restablecida para el usuario con ID $usuario_id.</p>";
            } else {
                echo "<p>Error al restablecer la contraseña.</p>";
            }
        }
        ?>
    </section>
</body>
</html>
