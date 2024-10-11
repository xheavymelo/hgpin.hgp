<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$db_username = "root"; // El usuario por defecto de MySQL en XAMPP es "root"
$db_password = ""; // La contraseña por defecto es vacía
$dbname = "soporte_tecnico";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener registros de auditoría
$audit_sql = "SELECT * FROM auditoria ORDER BY fecha DESC"; // Asegúrate de que hay una columna `fecha` en tu tabla
$audit_result = $conn->query($audit_sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eef2f3;
        }

        header {
            background-color: #0056b3;
            color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
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
            font-weight: bold;
        }

        .button:hover {
            background-color: #0056b3;
        }

        /* Estilos para la tabla de auditoría */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden; /* Para redondear las esquinas de la tabla */
        }

        thead {
            background-color: #007bff;
            color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        .audit-box {
            margin-top: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .welcome {
            margin: 10px 0;
            font-size: 1.1em;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #007bff;
            color: white;
            margin-top: 20px;
            border-radius: 0 0 10px 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Panel de Administración</h1>
        <p class="welcome">Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <a href="logout.php" class="button">Cerrar sesión</a>
    </header>

    <div class="container">
        <section class="admin-section">
            <h2>Gestión de Tickets</h2>
            <p><a href="ver_tickets.php" class="button">Ver Tickets Abiertos</a></p>
            <p><a href="gestionar_tickets.php" class="button">Administrar Tickets</a></p>

            <h2>Gestión de Usuarios</h2>
            <p><a href="ver_usuarios.php" class="button">Ver Usuarios</a></p>
            <p><a href="gestionar_usuarios.php" class="button">Administrar Usuarios</a></p>

            <div class="audit-box">
                <h2>Registros de Auditoría</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Detalles</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($audit_result->num_rows > 0): ?>
                            <?php while ($row = $audit_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($row['accion']); ?></td>
                                    <td><?php echo htmlspecialchars($row['detalles']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No hay registros de auditoría disponibles.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Soporte Técnico. Todos los derechos reservados.
    </footer>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
