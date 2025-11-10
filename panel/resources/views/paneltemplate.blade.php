<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/bb_fondo.png') }}">
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
    height: 100vh;
}

.content {
    display: flex;
    width: 100%;
}

/* === SIDEBAR === */
.sidebar-container {
    width: 350px;
    background-color: #D2665A;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease-in-out;
    z-index: 200;
}

.content-container {
    margin-left: 350px;
    padding: 30px;
    flex-grow: 1;
    width: calc(100% - 350px);
}

.sidebar {
    display: flex;
    flex-direction: column;
    height: 100vh;
}

.sidebar ul {
    list-style: none;
    display: flex;
    flex-direction: column;
    margin: 0;
    padding: 0;
    width: 100%;
    flex-grow: 1;
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
    max-height: 200px;
}

/* Rotación del icono */
.dropdown.rotate {
    transform: rotate(180deg);
}

/* Footer lateral */
.sticky-bottom {
    margin-top: auto;
    display: flex;
    align-items: center;
    background-color: #8a3127;
    gap: 0.7rem;
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
}

.user-details {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.user-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: #fff;
}

.user-role {
    font-size: 0.75rem;
    color: #ccc;
}

.user-actions {
    align-items: center;
    justify-content: center;
    display: flex;
    gap: 0.6rem;
    font-size: 1.15rem;
    color: #fff;
    cursor: pointer;
}

.user-actions i:hover {
    color: #e49f97;
}

/* === BOTÓN HAMBURGUESA === */
.menu-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    background-color: #D2665A;
    color: white;
    border: none;
    font-size: 1.5rem;
    z-index: 200;
    padding: 8px 12px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
}

/* === RESPONSIVE === */
@media (max-width: 992px) {
    .sidebar-container {
        transform: translateX(-100%);
        width: 280px;
    }

    .sidebar-container.open {
        transform: translateX(0);
    }

    .content-container {
        margin-left: 0;
        width: 100%;
        padding: 20px;
    }

    .menu-toggle {
        display: block;
    }

    body.menu-open::after {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 100;
    }
}

@media (max-width: 576px) {
    .sidebar-container {
        width: 240px;
    }
}
    </style>
</head>
<body>

@livewire('alerts')

<!-- Botón hamburguesa -->
<button class="menu-toggle" id="menuToggle"><i class="fa-solid fa-bars"></i></button>

<div class="content">
    <div class="sidebar-container">
        <img src="{{ asset('img/LogoBlanco.png') }}" class="img-fluid d-block mx-auto" alt="Imagen" width="200" style="padding: 20px 10px">

        <nav class="sidebar">
            <ul>
                @if(auth()->check() && auth()->user()->rank >= 2)
                <li>
                    <a href="#" class="toggle-submenu">
                        <i class="fa-solid fa-box"></i>
                        <span>Inventario</span>
                        <i class="fa-solid fa-chevron-down dropdown"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('inventario.couriers') }}">Empresas mensajería</a></li>
                        <li><a href="{{ route('inventario.index') }}">Productos</a></li>
                        <li><a href="{{ route('ordenes') }}">Órdenes</a></li>
                        <li><a href="{{ route('pedidos') }}">Pedidos</a></li>
                    </ul>
                </li>
                @endif

                @if(auth()->check() && auth()->user()->rank >= 1)
                <li>
                    <a href="#" class="toggle-submenu">
                        <i class="fa-solid fa-truck"></i>
                        <span>Producción</span>
                        <i class="fa-solid fa-chevron-down dropdown"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('estudios.index') }}">Estudios</a></li>
                        <li><a href="{{ route('maquinas.index') }}">Máquinas</a></li>
                        @if(auth()->check() && auth()->user()->rank >= 4)<li><a href="{{ route('reportes') }}">Reportes</a></li>@endif
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    </ul>
                </li>
                @endif

                @if(auth()->check() && auth()->user()->rank >= 4)
                <li>
                    <a href="#" class="toggle-submenu">
                        <i class="fa-solid fa-book"></i>
                        <span>Administración</span>
                        <i class="fa-solid fa-chevron-down dropdown"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('admin.accounts') }}">Cuentas de usuario</a></li>
                        <li><a href="{{ route('admin.logs') }}">Logs</a></li>
                    </ul>
                </li>
                @endif

                <li class="sticky-bottom user-info">
                    <img src="{{ asset('img/bb.jpg') }}" alt="Usuario" class="user-avatar">
                    <div class="user-details">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-role">{{ auth()->user()->rank_info->name ?? 'Sin rango' }}</span>
                    </div>

                    <div class="user-actions">
                        <a href="{{route('panel.perfil.view')}}"><i class="fa-solid fa-gear"></i></a>
                        <a href="{{ route('logout') }}" class="ps-1"><i class="fa-solid fa-right-from-bracket"></i></a>
                    </div>

                    @if(auth()->user()->can_developt)
                        @livewire("api-select")
                    @endif
                </li>
            </ul>
        </nav>
    </div>

    <div class="content-container" id="mainContent">
        @yield("contenido")

        <div class="text-center mt-2" style="color: #999; font-size: 0.7rem;">
            Panel RobboAdmin v1.0.0 © {{ date('Y') }}
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleLinks = document.querySelectorAll(".toggle-submenu");
    const sidebar = document.querySelector(".sidebar-container");
    const menuToggle = document.getElementById("menuToggle");

    // Submenús
    toggleLinks.forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault();
            const parentUl = this.closest("li");
            const submenu = this.nextElementSibling;
            const icon = this.querySelector(".dropdown");

            document.querySelectorAll(".submenu").forEach(menu => {
                if (menu !== submenu) menu.classList.remove("active");
            });
            document.querySelectorAll(".dropdown").forEach(drop => {
                if (drop !== icon) drop.classList.remove("rotate");
            });

            submenu.classList.toggle("active");
            icon.classList.toggle("rotate");
            parentUl.classList.toggle("active");
        });
    });

    // Toggle sidebar
    menuToggle.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        document.body.classList.toggle("menu-open");
    });

    // Cerrar al hacer clic fuera
    document.addEventListener("click", (e) => {
        if (window.innerWidth <= 992 && sidebar.classList.contains("open")) {
            if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                sidebar.classList.remove("open");
                document.body.classList.remove("menu-open");
            }
        }
    });
});
</script>

@livewireScripts
<script>
Livewire.on('refreshPage', () => {
    setTimeout(() => window.location.reload(), 3000);
});
</script>
@stack('scripts')
</body>
</html>
