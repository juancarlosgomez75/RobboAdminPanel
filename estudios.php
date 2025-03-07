<?php include "API.php"; ?>
<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}
?>

<?php


// Decodifica la respuesta JSON
$dataResponse = obtenerListadoEstudios();

// Verifica si `Status` y `ListUserData` están en la respuesta
if (isset($dataResponse['Status']) && isset($dataResponse['ListUserData'])) {
    // echo "Status: " . $dataResponse['Status'] . "\n";
    
    // if ($dataResponse['Status'] === true) { // Ajusta según la API
    //     echo "Solicitud exitosa\n";
        
    //     // Analiza `ListUserData`
    //     if (isset($dataResponse['ListUserData']) && is_array($dataResponse['ListUserData'])) {
    //         echo "Usuarios recibidos: \n";
    //         foreach ($dataResponse['ListUserData'] as $user) {
    //             echo "- Nombre: " . ($user['name'] ?? 'Desconocido') . "\n";
    //             echo "- Email: " . ($user['email'] ?? 'No disponible') . "\n";
    //             echo "------------------\n";
    //         }
    //     } else {
    //         echo "No se encontraron datos de usuarios.\n";
    //     }
    // } else {
    //     echo "Error en la solicitud: " . ($dataResponse['Message'] ?? 'Sin detalles') . "\n";
    // }
} else {
    echo "Respuesta inesperada de la API.\n";
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
        <h2>Administración de estudios</h2>
        <p>Hola, desde aquí podrás administrar los estudios que están registrados en el sistema</p>
        <a href="nuevoestudio.php" class="btn btn-primary btn-lg">Registrar nuevo estudio</a><br><br>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Base de datos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Ciudad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <!-- <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Age</th>
                                <th>Start date</th>
                                <th>Salary</th>
                            </tr>
                        </tfoot> -->
                        <tbody>
                            <?php 
                            foreach ($dataResponse['ListUserData'] as $user) {if($user["Activo"]==true){
                            ?>
                            <tr>
                                <td><?php echo $user["Id"];?></td>
                                <td><?php echo $user["WcStudy"]["StudyName"];?></td>
                                <td>Edinburgh</td>
                                <td>
                                    <a type="button" class="btn btn-primary btn-sm" href="editarestudio.php?id=<?php echo $user["Id"];?>">Editar</a>
                                    <a type="button" class="btn btn-secondary btn-sm" href="modelos.php?id=<?php echo $user["Id"];?>">Modelos</a>
                                    <a type="button" class="btn btn-secondary btn-sm" href="maquinas.php?id=<?php echo $user["Id"];?>">Máquinas</a>
                                    <button type="button" class="btn btn-danger btn-sm">Eliminar</button>
                                </td>
                            </tr>
                            <?php
                            }}
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
