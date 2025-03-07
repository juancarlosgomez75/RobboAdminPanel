<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
<body>
    <?php include "menu.php"; ?>

    <div class="content">
        <h2>Hola, <?php echo htmlspecialchars($_SESSION["usuario"]); ?>!</h2>
        <p>Este es tu panel de administración.</p>
    </div>
</body>
</html>
