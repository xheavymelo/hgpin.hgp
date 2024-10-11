<?php
session_start();

// Contraseña de administrador
$admin_password = "hgpin021589008";

// Verificar si se ha enviado la contraseña
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['admin_password'] === $admin_password) {
        // La contraseña es correcta, proceder a la gestión de usuarios
        header("Location: manage_users.php");
        exit();
    } else {
        $error = "Contraseña incorrecta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Área de Administración</h1>
    </header>
    <section class="form-section">
        <form action="admin_login.php" method="POST">
            <label for="admin_password">Contraseña de Administrador:</label>
            <input type="password" id="admin_password" name="admin_password" required>

            <button type="submit">Acceder</button>
        </form>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </section>
</body>
</html>
