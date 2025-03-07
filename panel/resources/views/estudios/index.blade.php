@extends('paneltemplate')
@section('title','Listado de estudios')

<style>
.custom-input {
    display: flex;
    align-items: center;
    padding: 4px 10px; /* Reduce la altura */
    border: 1px solid #ddd; /* Borde gris claro */
    border-radius: 6px;
    width: 100%;
    font-size: 14px;
    margin-bottom:0.5rem;
    height: 30px; /* Controla la altura */
}

.custom-input:focus {
    outline: none;
    box-shadow: none; /* Elimina borde azul en focus */
    border-color: #ccc; /* Borde ligeramente más oscuro al enfocar */
}

.hide{
    display: none;
}

</style>
@section("contenido")
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de estudios</h5>
                    {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    <button type="button" class="btn btn-sm action-btn btn-outline-secondary">
                        <i class="fa-solid fa-plus"></i> Crear
                    </button>
                    
                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" id="verFiltros">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>
                    
                </div>
                <div class="col-md-12 hide" id="Filtros">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" class="custom-input" placeholder="Filtrar por nombre">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="custom-input" placeholder="Filtrar por ciudad">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="w-10" scope="col" style="width: 7%;">#</th>
                            <th class="w-40" scope="col">Nombre</th>
                            <th class="w-30" scope="col">Ciudad</th>
                            <th class="w-15" scope="col">Modelos</th>
                            <th scope="col" style="width: 23%;">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>
                                <a type="button" class="btn btn-outline-secondary btn-sm" href="modelos.php?id=">Ver</a>
                                <a type="button" class="btn btn-outline-primary btn-sm" href="editarestudio.php?id=">Editar</a>
                                <button type="button" class="btn btn-outline-danger btn-sm">Eliminar</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td colspan="2">Larry the Bird</td>
                            <td>@twitter</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
<script>
    document.getElementById("verFiltros").addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("Filtros").classList.toggle("hide");
    });
</script>
@endpush