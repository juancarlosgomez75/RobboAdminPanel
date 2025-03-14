<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
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
    display: flex;
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
}

/* Contenedor del usuario */
.user-info {
    display: flex;
    align-items: center;
    padding: 0.8rem 1rem;
    background-color: #8a3127;
    /* border-top: 1px solid rgba(255, 255, 255, 0.2); */
    gap: 0.8rem;
}

/* Imagen circular */
.user-avatar {
    width: 40px;
    height: 40px;
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
    display: flex;
    gap: 0.8rem;
    font-size: 1.2rem;
    color: #fff;
    cursor: pointer;
}

.user-actions i:hover {
    color: #e49f97;
}



    </style>
</head>
<body>
    <div class="container" style="padding-top: 1em">
        <div class="row" style="margin-bottom: 1em">
            <div class="col-md-2" style="position: fixed; height: 100%; background-color: #D2665A; margin: 0; padding: 0; top: 0; left: 0;">
                <img src="{{ asset('img/LogoBlanco.png') }}" class="img-fluid d-block mx-auto" alt="Imagen" width="200" style="padding: 8px; margin-bottom: 2rem; margin-top: 1rem;">


                    <nav class="sidebar">
                        <ul>
                            @if(auth()->check() && auth()->user()->rank >= 4)
                            <li>
                                <a href="#" class="toggle-submenu">
                                    <i class="fa-solid fa-truck"></i>
                                    <span>Producción</span>
                                    <i class="fa-solid fa-chevron-down dropdown"></i>
                                </a>
                                <ul class="submenu">
                                    <li><a href="{{ route('estudios.index') }}">Estudios</a></li>
                                    <li><a href="{{ url('/modelos') }}">Modelos</a></li>
                                    <li><a href="{{ route('maquinas.index') }}">Máquinas</a></li>
                                </ul>
                            </li>
                            @endif
                        
                        

 
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


                            {{-- <li>
                                <a href="/logout">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>Salir del panel</span>
                                </a>
                            </li> --}}


                            <li class="sticky-bottom user-info">
                                <img src="{{ asset('img/bb.jpg') }}" alt="Usuario" class="user-avatar">
                                <div class="user-details">
                                    <span class="user-name">{{ auth()->user()->name }}</span>
                                    <span class="user-role">
                                        {{ auth()->user()->rank_info->name ?? 'Sin rango' }}
                                    </span>
                                </div>
                                <div class="user-actions">

                                    <a href="/profile"><i class="fa-solid fa-gear"></i></a>
                                    <a href="{{ route('logout') }}" class="ps-1"><i class="fa-solid fa-right-from-bracket"></i></a>
                                </div>
                            </li>
                            
                        </ul>
                    </nav>
 

            </div>

            <div class="col offset-md-1 md-10">
                <br>

                <div class="row">
                   {{-- <div class="col md-9">
                        <h2><b>@yield('title')</b></h2>
                    </div> 
                    <div class="col md-3">
                        Holis
                    </div> --}}
                    <div class="col md-12">
                        @yield("contenido")
                    </div>
                </div>
                
            </div>

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
@stack('scripts')
</body>
</html>
