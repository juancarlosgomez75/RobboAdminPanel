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
        <h2>Registrar estudio</h2>
        <p>Por favor completa el siguiente formulario para registrar el estudio</p>

        <form method="post" action="" autocomplete="off">
            <div class="mb-3">
                <label for="studyName" class="form-label">Nombre de estudio</label>
                <input type="text" class="form-control" id="studyName" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="username" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Locación</label>
                <input type="text" class="form-control" id="location" required>
            </div>
            <div class="mb-3">
                <label for="manager" class="form-label">Encargado</label>
                <input type="text" class="form-control" id="manager" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="address" required>
            </div>
            <div class="mb-3">
                <label for="contactNumber" class="form-label">Número de contacto</label>
                <input type="tel" class="form-control" id="contactNumber" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>
</body>
</html>
