<div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Categoría</th>
                <th scope="col">Estado</th>
                <th scope="col">Disponibles</th>
                <th scope="col" style="width: 6%"></th>
                <th scope="col" style="width: 15%"></th>
            </tr>
        </thead>
        <tbody class="align-center">
            @if(!$productos->isEmpty())
            @foreach ($productos as $producto)
            <tr>
                <th scope="row">{{$producto->id}}</th>
                <td>{{$producto->name}}</td>
                <td>
                    {{$producto->category_info->name??"Sin categoría"}}
                </td>
                <td>
                    @if($producto->available)
                    <span style="color:green">Activo</span>
                    @else
                    <span style="color:red">Oculto</span>
                    @endif
                </td>
                <td>{{$producto->inventory->stock_available}}</td>
                <td>
                    @if($producto->inventory->stock_available>=$producto->inventory->stock_min)
                    <i class="fa-solid fa-circle-check" style="color:green"></i>
                    @elseif($producto->inventory->stock_available>0)
                    <i class="fa-solid fa-circle-exclamation" style="color:orange"></i>
                    @else
                    <i class="fa-solid fa-circle-xmark" style="color:red"></i>
                    @endif
                </td>
                <td>
                    <a type="button" class="btn btn-outline-secondary btn-sm">Ver detalles</a>
                </td>
            </tr>
            @endforeach

            @else
            <tr class="text-center">
                <td colspan="7">Sin productos registrados aún</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
