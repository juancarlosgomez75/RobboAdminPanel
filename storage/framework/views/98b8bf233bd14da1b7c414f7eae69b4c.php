<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('img/bb_fondo.png')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
body {
    background-color: #fcfcfcde;
    font-family: 'Lato', sans-serif;
    margin: 0;
    padding: 0;
    height: 100vh; /* Para que la página tome toda la altura */
}

.content{
    display: flex;
    width: 100%;
}
.sidebar-container {
    width: 350px;
    background-color: #D2665A;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    flex-direction: column;
}

.content-container {
    margin-left: 350px;
    padding: 30px;
    flex-grow: 1;
    width: calc(100% - 300px);
}

.sidebar {
    display: flex;
    flex-direction: column;
    height: 100vh; /* Hace que la barra lateral ocupe toda la altura */
}

.sidebar ul {
    list-style: none;
    display: flex;
    flex-direction: column;
    margin: 0;
    padding: 0;
    width: 100%;
    flex-grow: 1; /* Permite que los elementos crezcan y dejen espacio abajo */
}

.sidebar ul li {
    padding: 0.5rem 1.5rem 0.5rem 2rem;
    transition: all .2s;
}

.sidebar > ul > li.active {
    background-color: #e49f97;
}

.sidebar ul li a {
    display: flex;
    gap: 0.5rem;
    padding: 0.3rem 0;
    align-items: center;
    text-decoration: none;
    color: #ffffff;
    transition: all .2s;
    cursor: pointer;
    font-weight: 600;
}

.sidebar ul>li:not(.user-info):hover {
    background-color: #e49f97;
}

.sidebar ul li > a .dropdown {
    margin-left: auto;
    transition: transform 0.4s ease-in-out;
}

/* Submenú */
.submenu {
    list-style: none;
    padding-left: 2.5rem;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.5s ease-in-out;
}

.submenu li {
    padding-left: 1.55rem;
}

.submenu li a {
    font-weight: 500 !important;
}

.submenu li a:hover {
    color: #D2665A;
}

.submenu.active {
    max-height: 200px; /* Ajusta según el contenido */
}

/* Rotación del icono */
.dropdown.rotate {
    transform: rotate(180deg);
}

.shadow-custom {
    box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.15);
    border: 0;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Hace que el último elemento se mantenga abajo */
.sticky-bottom {
    margin-top: auto;
    display: flex;
    align-items: center;
    background-color: #8a3127;
    /* border-top: 1px solid rgba(255, 255, 255, 0.2); */
    gap: 0.7rem;
}

/* Imagen circular */
.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
}

/* Contenedor de los textos */
.user-details {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

/* Nombre del usuario */
.user-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: #fff;
}

/* Rol del usuario */
.user-role {
    font-size: 0.75rem;
    color: #ccc;
}

/* Contenedor de los iconos */
.user-actions {
    align-items: center;
    justify-content: center; /* Centra horizontalmente */
    display: flex;
    gap: 0.6rem;
    font-size: 1.15rem;
    color: #fff;
    cursor: pointer;
}

.user-actions i:hover {
    color: #e49f97;
}



    </style>
</head>
<body>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('alerts');

$__html = app('livewire')->mount($__name, $__params, 'lw-919799811-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

    <div class="content">
        <div class="sidebar-container">
            <img src="<?php echo e(asset('img/LogoBlanco.png')); ?>" class="img-fluid d-block mx-auto" alt="Imagen" width="200" style="padding: 20px 10px">

            <nav class="sidebar">
                <ul>
                    <?php if(auth()->check() && auth()->user()->rank >= 2): ?>
                    <li>
                        <a href="#" class="toggle-submenu">
                            <i class="fa-solid fa-box"></i>
                            <span>Inventario</span>
                            <i class="fa-solid fa-chevron-down dropdown"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="<?php echo e(route('inventario.couriers')); ?>">Empresas mensajería</a></li>
                            <li><a href="<?php echo e(route('inventario.index')); ?>">Productos</a></li>
                            <li><a href="<?php echo e(route('ordenes')); ?>">Órdenes</a></li>
                            <li><a href="<?php echo e(route('pedidos')); ?>">Pedidos</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if(auth()->check() && auth()->user()->rank >= 1): ?>
                    <li>
                        <a href="#" class="toggle-submenu">
                            <i class="fa-solid fa-truck"></i>
                            <span>Producción</span>
                            <i class="fa-solid fa-chevron-down dropdown"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="<?php echo e(route('estudios.index')); ?>">Estudios</a></li>
                            <li><a href="<?php echo e(route('maquinas.index')); ?>">Máquinas</a></li>
                            <?php if(auth()->check() && auth()->user()->rank >= 4): ?><li><a href="<?php echo e(route('reportes')); ?>">Reportes</a></li><?php endif; ?>
                            <li><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                

                    <?php if(auth()->check() && auth()->user()->rank >= 4): ?>
                    <li>
                        <a href="#" class="toggle-submenu">
                            <i class="fa-solid fa-book"></i>
                            <span>Administración</span>
                            <i class="fa-solid fa-chevron-down dropdown"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="<?php echo e(route('admin.accounts')); ?>">Cuentas de usuario</a></li>
                            <li><a href="<?php echo e(route('admin.logs')); ?>">Logs</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>


                    <li class="sticky-bottom user-info">
                        <img src="<?php echo e(asset('img/bb.jpg')); ?>" alt="Usuario" class="user-avatar">

                        
                        <div class="user-details">
                            <span class="user-name"><?php echo e(auth()->user()->name); ?></span>
                            <span class="user-role">
                                <?php echo e(auth()->user()->rank_info->name ?? 'Sin rango'); ?>

                            </span>
                        </div>

                        <div class="user-actions">

                            <a href="<?php echo e(route("panel.perfil.view")); ?>"><i class="fa-solid fa-gear"></i></a>
                            <a href="<?php echo e(route('logout')); ?>" class="ps-1"><i class="fa-solid fa-right-from-bracket"></i></a>

                        </div>
                        <?php if(auth()->user()->can_developt): ?>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("api-select");

$__html = app('livewire')->mount($__name, $__params, 'lw-919799811-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        <?php endif; ?>

                    </li>
                    
                </ul>
            </nav>
        </div>
        <div class="content-container">
            <?php echo $__env->yieldContent("contenido"); ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleLinks = document.querySelectorAll(".toggle-submenu");

            toggleLinks.forEach(link => {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
                    const parentUl = this.closest("li");
                    const submenu = this.nextElementSibling;
                    const icon = this.querySelector(".dropdown");

                    // Cierra los otros submenús
                    document.querySelectorAll(".submenu").forEach(menu => {
                        if (menu !== submenu) {
                            menu.classList.remove("active");
                            menu.parentElement.closest("li").classList.remove("active");
                        }
                    });

                    document.querySelectorAll(".dropdown").forEach(drop => {
                        if (drop !== icon) {
                            drop.classList.remove("rotate");
                        }
                    });

                    // Alterna el actual
                    submenu.classList.toggle("active");
                    icon.classList.toggle("rotate");
                    parentUl.classList.toggle("active");
                });
            });
        });
    </script>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


    <script>
        Livewire.on('refreshPage', () => {
            setTimeout(() => {
                window.location.reload();
            }, 3000); // Espera 4 segundos antes de recargar
        });
    </script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/paneltemplate.blade.php ENDPATH**/ ?>