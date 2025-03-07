<?php
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}
?>
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>

    <div class="sidebar">
        <h4 class="text-center">Panel</h4>
        <a href="dashboard.php">Inicio</a>
        <a href="estudios.php">Estudios</a>
        <a href="maquinas.php">Máquinas</a>
        <a href="logout.php">Cerrar sesión</a>
    </div>
