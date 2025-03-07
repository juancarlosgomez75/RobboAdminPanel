<?php include "API.php"; ?>
<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}
?>

<?php
if (isset($_GET['id'])) {
    // Obtener el valor de 'id'
    $id = $_GET['id'];

    //Filtro que sea un entero positivo
    if (ctype_digit($id)){

        //Consulto si es un estudio valido
        $user=consultarEstudioValido($id);
        if($user!=false){
            //Ahora consulto las maquinas
            $machines=obtenerListadoMaquinas($id);
        }
        else{
            header("Location: estudios.php");
            exit();
        }

    }else{
        header("Location: estudios.php");
        exit();
    }
}else{
    header("Location: estudios.php");
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
        <h2>Administración de máquinas de ESTUDIO</h2>
        <p>Hola, desde aquí podrás administrar las máquinas que están vinculadas en el sistema a este estudio</p>
        <a href="nuevomodelo.php" class="btn btn-primary btn-lg">Vincular máquina</a><br><br>

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
                            foreach ($machines as $machine) {
                            ?>
                            <tr>
                                <td><?php echo $machine["FirmwareID"]?></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm">Desvincular</button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
