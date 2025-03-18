<div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Categoría</th>
                <th scope="col">Estado</th>
                <th scope="col">Disponibles</th>
                <th scope="col"></th>
                <th scope="col" style="width: 15%"></th>
            </tr>
        </thead>
        {{-- <tbody class="align-center">
            @if(!$categorias->isEmpty())
            @foreach ($categorias as $categoria)
            <tr>
                <th scope="row">{{$categoria->id}}</th>
                <td>{{$categoria->name}}</td>
                <td>
                    <a type="button" class="btn btn-outline-secondary btn-sm" wire:click="editar({{$categoria->id}})">Editar</a>
                </td>
            </tr>
            @endforeach

            @else
            <tr class="text-center">
                <td colspan="3">Sin categorias registradas aún</td>
            </tr>
            @endif
        </tbody> --}}
    </table>
</div>
