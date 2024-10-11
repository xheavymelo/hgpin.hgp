<?php
session_start();

// Conexión a la base de datos
$servername = "localhost";
$db_username = "root"; // El usuario por defecto de MySQL en XAMPP es "root"
$db_password = ""; // La contraseña por defecto es vacía
$dbname = "soporte_tecnico";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Registrar el cierre de sesión si el usuario está autenticado
if (isset($_SESSION['username'])) {
    $usuario = $_SESSION['username'];

    // Registrar la acción de cerrar sesión
    $accion = 'Cerrar sesión';
    $detalles = 'Usuario ' . $usuario . ' ha cerrado sesión.';
    $audit_sql = "INSERT INTO auditoria (usuario, accion, detalles) VALUES (?, ?, ?)";
    $audit_stmt = $conn->prepare($audit_sql);

    if ($audit_stmt) {
        $audit_stmt->bind_param("sss", $usuario, $accion, $detalles);
        $audit_stmt->execute();
        $audit_stmt->close();
    }

    // Finalizar sesión
    session_destroy();
    header("Location: login.php");
    exit();
}

// Procesar el inicio de sesión si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar las credenciales
    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifica la contraseña con password_verify()
        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;

            // Registrar el inicio de sesión en la auditoría
            $accion = 'Inicio de sesión';
            $detalles = 'Usuario ' . $username . ' ha iniciado sesión exitosamente.';
            $audit_sql = "INSERT INTO auditoria (usuario, accion, detalles) VALUES (?, ?, ?)";
            $audit_stmt = $conn->prepare($audit_sql);

            if ($audit_stmt) {
                $audit_stmt->bind_param("sss", $username, $accion, $detalles);
                $audit_stmt->execute();
                $audit_stmt->close();
            }

            header("Location: admin.php");
            exit();
        } else {
            $error = "Credenciales inválidas.";
        }
    } else {
        $error = "Credenciales inválidas.";
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
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .form-section, .admin-section {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .button {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
    <header>
        <h1>Iniciar Sesión</h1>
    </header>
    <section class="form-section">
        <form action="login.php" method="POST">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="login">Iniciar Sesión</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <!-- Nuevo botón para verificar administrador -->
        <form action="verificar_administrador.php" method="GET">
            <button type="submit" class="button">Verificar Administrador</button>
        </form>
    </section>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
