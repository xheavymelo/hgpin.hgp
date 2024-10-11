<?php
session_start(); // Iniciar la sesión

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "soporte_tecnico";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se envió un ticket_id
if (isset($_GET['ticket_id'])) {
    $ticket_id = $_GET['ticket_id'];

    // Consultar la base de datos para encontrar el ticket
    $sql = "SELECT * FROM tickets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Mostrar la información del ticket
        $ticket = $result->fetch_assoc();
    } else {
        $ticket = null; // No se encontró el ticket
    }
} else {
    $ticket = null; // No se proporcionó un ID de ticket
}

// Cerrar la conexión
$stmt->close();
$conn->close();

// Verificar si el usuario ha desactivado la actualización
$auto_refresh = isset($_SESSION['auto_refresh']) ? $_SESSION['auto_refresh'] : true;

// Manejar el formulario de desactivación de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['disable_refresh'])) {
        $_SESSION['auto_refresh'] = false; // Desactivar la actualización
    } elseif (isset($_POST['enable_refresh'])) {
        $_SESSION['auto_refresh'] = true; // Activar la actualización
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado del Ticket</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            text-align: center;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        .ticket-details, .error {
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .refresh-toggle {
            margin: 20px 0;
        }
    </style>
    <!-- Esta línea actualiza la página cada 10 segundos si la actualización está habilitada -->
    <?php if ($auto_refresh): ?>
        <meta http-equiv="refresh" content="10">
    <?php endif; ?>
</head>
<body>
    <div class="container">
        <header>
            <h1>Estado del Ticket</h1>
        </header>

        <?php if ($ticket): ?>
            <section class="ticket-details">
                <h2>Detalles del Ticket</h2>
                <p><strong>ID del Ticket:</strong> <?php echo htmlspecialchars($ticket['id']); ?></p>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($ticket['nombre']); ?></p>
                <p><strong>Correo electrónico:</strong> <?php echo htmlspecialchars($ticket['email']); ?></p>
                <p><strong>Asunto:</strong> <?php echo htmlspecialchars($ticket['asunto']); ?></p>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($ticket['descripcion']); ?></p>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($ticket['estado']); ?></p>
                <p><strong>Fecha de creación:</strong> <?php echo htmlspecialchars($ticket['fecha_creacion']); ?></p>
            </section>
        <?php else: ?>
            <div class="error">
                <p>No se encontró el ticket con el número proporcionado.</p>
            </div>
        <?php endif; ?>

        <section class="refresh-toggle">
            <form method="post">
                <?php if ($auto_refresh): ?>
                    <button type="submit" name="disable_refresh" class="button">Desactivar Actualización Automática</button>
                <?php else: ?>
                    <button type="submit" name="enable_refresh" class="button">Activar Actualización Automática</button>
                <?php endif; ?>
            </form>
        </section>

        <section class="actions">
            <a href="index.html" class="button">Volver a la Página Principal</a>
        </section>
    </div>
</body>
</html>
