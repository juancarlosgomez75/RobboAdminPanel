<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        body {
            background-color: #faf0ed;
        }

        /* Sidebar */
        .sidebar ul {
            list-style: none;
            border: 2px solid red;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s ease-in-out;
        }

        .sidebar ul.active {
            background-color: rgba(255, 0, 0, 0.3); /* Color rojo cuando activo */
        }

        .sidebar ul li > a {
            border: 1px solid blue;
            display: flex;
            gap: 0.5rem;
            padding: 0.2rem 0.3rem;
            align-items: center;
            text-decoration: none;
            color: #6d6d6d;
            transition: all .2s;
            cursor: pointer;
        }

        .sidebar ul li > a:hover {
            color: #D2665A;
        }

        .sidebar ul li > a .dropdown {
            margin-left: auto;
            transition: transform 0.4s ease-in-out;
        }

        /* Submenú con animación */
        .submenu {
            list-style: none;
            padding-left: 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-in-out;
        }

        .submenu.active {
            max-height: 200px; /* Ajusta según el contenido */
        }

        /* Rotación del icono */
        .dropdown.rotate {
            transform: rotate(180deg);
        }
    </style>
</head>
<body>
    <div class="container" style="padding-top: 1em">
        <div class="row" style="margin-bottom: 1em">
            <div class="col-md-3">
                <img src="{{ asset('img/Logo.png') }}" class="img-fluid" alt="Imagen" width="100">
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <nav class="sidebar">
                    <ul>
                        <li>
                            <a href="#" class="toggle-submenu">
                                <i class="fa-solid fa-heart"></i>
                                <span>Holi</span>
                                <i class="fa-solid fa-chevron-down dropdown"></i>
                            </a>
                            <ul class="submenu">
                                <li><a href="#">Hola</a></li>
                                <li><a href="#">Hola</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul>
                        <li>
                            <a href="#" class="toggle-submenu">
                                <i class="fa-solid fa-heart"></i>
                                <span>Holi 2</span>
                                <i class="fa-solid fa-chevron-down dropdown"></i>
                            </a>
                            <ul class="submenu">
                                <li><a href="#">Adiós</a></li>
                                <li><a href="#">Chao</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul>
                        <li>
                            <a href="#">
                                <i class="fa-solid fa-heart"></i>
                                <span>Holi 3</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleLinks = document.querySelectorAll(".toggle-submenu");

            toggleLinks.forEach(link => {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
                    const parentUl = this.closest("ul");
                    const submenu = this.nextElementSibling;
                    const icon = this.querySelector(".dropdown");

                    // Cierra los otros submenús
                    document.querySelectorAll(".submenu").forEach(menu => {
                        if (menu !== submenu) {
                            menu.classList.remove("active");
                            menu.parentElement.closest("ul").classList.remove("active");
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
</body>
</html>
