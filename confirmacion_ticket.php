<?php
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

// Obtener el ID del ticket desde la URL
$ticket_id = $_GET['ticket_id'];

// Consultar los detalles del ticket
$sql = "SELECT * FROM tickets WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Mostrar los detalles del ticket
    $ticket = $result->fetch_assoc();
} else {
    echo "No se encontró el ticket.";
    exit();
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Ticket</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Ticket Creado Exitosamente</h1>
    </header>

    <section class="ticket-details">
        <h2>Detalles del Ticket</h2>
        <p><strong>ID del Ticket:</strong> <?php echo $ticket['id']; ?></p>
        <p><strong>Nombre:</strong> <?php echo $ticket['nombre']; ?></p>
        <p><strong>Email:</strong> <?php echo $ticket['email']; ?></p>
        <p><strong>Asunto:</strong> <?php echo $ticket['asunto']; ?></p>
        <p><strong>Descripción:</strong> <?php echo $ticket['descripcion']; ?></p>
        <p><strong>Fecha de creación:</strong> <?php echo $ticket['fecha_creacion']; ?></p>
    </section>

    <!-- Botón para regresar a la página principal -->
    <section class="actions">
        <a href="index.html" class="button">Volver a la Página Principal</a>
        
        <!-- Botón para ver el estado del ticket -->
        <a href="estado_ticket.php?ticket_id=<?php echo $ticket['id']; ?>" class="button">Ver Estado del Ticket</a>
    </section>

</body>
</html>
