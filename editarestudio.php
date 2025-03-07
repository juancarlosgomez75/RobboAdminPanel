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
        <h2>Editar estudio</h2>
        <p>Por favor completa el siguiente formulario para editar el estudio</p>

        <form method="post" action="" autocomplete="off">
            <b>Información del estudio:</b>
            <div class="mb-3">
                <label for="studyName" class="form-label">Nombre de estudio</label>
                <input type="text" class="form-control" id="studyName" required value="<?php echo $user["WcStudy"]["StudyName"];?>">
            </div>
            <div class="mb-3">
                <label for="manager" class="form-label">Nombre de manager</label>
                <input type="text" class="form-control" id="manager" required value="<?php echo $user["WcStudy"]["Contact"];?>">
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Ciudad</label>
                <input type="text" class="form-control" id="location" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="address" required value="<?php echo $user["WcStudy"]["Address"];?>">
            </div>

            <hr class="my-4">

            <b>Información del monitor/encargado:</b>

            <div class="mb-3">
                <label for="monitor" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="monitor" required value="<?php echo $user["Name"];?>">
            </div>
            <div class="mb-3">
                <label for="contactNumber" class="form-label">Número de contacto</label>
                <input type="tel" class="form-control" id="contactNumber" required value="<?php echo $user["Phone"];?>">
            </div>
            <div class="mb-3">
                <label for="contactNumber" class="form-label">Email</label>
                <input type="email" class="form-control" id="contactmail" required value="<?php echo $user["Email"];?>">
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>
</body>
</html>
