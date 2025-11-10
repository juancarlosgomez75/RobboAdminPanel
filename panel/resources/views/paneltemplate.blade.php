<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/bb_fondo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #fcfcfcde;
        font-family: 'Lato', sans-serif;
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow-x: hidden;
    }

    .content {
        display: flex;
        width: 100%;
    }

    /* --- SIDEBAR --- */
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
        z-index: 1000;
    }

    .sidebar-container.closed {
        transform: translateX(-100%);
    }

    .content-container {
        margin-left: 350px;
        padding: 30px;
        flex-grow: 1;
        width: calc(100% - 350px);
        transition: margin-left 0.3s ease-in-out;
    }

    .sidebar {
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow-y: auto;
    }

    .sidebar ul {
        list-style: none;
        display: flex;
        flex-direction: column;
        margin: 0;
        padding: 0;
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
        align-items: center;
        text-decoration: none;
        color: #ffffff;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
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

    .submenu.active {
        max-height: 300px;
    }

    .submenu li a {
        font-weight: 500 !important;
    }

    .submenu li a:hover {
        color: #D2665A;
    }

    .dropdown.rotate {
        transform: rotate(180deg);
    }

    .sticky-bottom {
        margin-top: auto;
        display: flex;
        align-items: center;
        background-color: #8a3127;
        gap: 0.7rem;
        padding: 0.8rem 1rem;
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
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 1.15rem;
        color: #fff;
    }

    .user-actions i:hover {
        color: #e49f97;
    }

    /* --- BOTÓN HAMBURGUESA --- */
    .menu-toggle {
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        background-color: #D2665A;
        color: white;
        border: none;
        font-size: 1.5rem;
        z-index: 1100;
        padding: 8px 12px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }

    /* --- RESPONSIVE --- */
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

        /* Capa semitransparente detrás del menú */
        body.menu-open::after {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 900;
        }
    }

    @media (max-width: 576px) {
        .sidebar-container {
            width: 250px;
        }

        .user-name {
            font-size: 0.8rem;
        }

        .user-role {
            font-size: 0.7rem;
        }

        .content-container {
            padding: 15px;
        }
    }
</style>
</head>
<body>

    @livewire('alerts')

    <!-- Botón Hamburguesa -->
    <button class="menu-toggle" id="menuToggle"><i class="fa-solid fa-bars"></i></button>

    <div class="content">
        <div class="sidebar-container" id="sidebar">
            <img src="{{ asset('img/LogoBlanco.png') }}" class="img-fluid d-block mx-auto" alt="Logo" width="200" style="padding: 20px 10px">

            <nav class="sidebar">
                <ul>
                    @if(auth()->check() && auth()->user()->rank >= 2)
                    <li>
                        <a href="#" class="toggle-submenu">
                            <i class="fa-solid fa-box"></i><span>Inventario</span>
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
                            <i class="fa-solid fa-truck"></i><span>Producción</span>
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
                            <i class="fa-solid fa-book"></i><span>Administración</span>
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
                            <a href="{{route("panel.perfil.view")}}"><i class="fa-solid fa-gear"></i></a>
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
        // Menú hamburguesa
        document.getElementById('menuToggle').addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Submenús
        document.addEventListener("DOMContentLoaded", function() {
            const toggleLinks = document.querySelectorAll(".toggle-submenu");
            toggleLinks.forEach(link => {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
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
                });
            });
        });

        Livewire.on('refreshPage', () => {
            setTimeout(() => window.location.reload(), 3000);
        });
    </script>

    @livewireScripts
    @stack('scripts')
</body>
</html>
