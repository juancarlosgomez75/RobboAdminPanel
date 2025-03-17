<div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre de categoría</th>
                <th scope="col" style="width: 15%"></th>
            </tr>
        </thead>
        <tbody class="align-center">
            @if(!empty($categorias))
            <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td><a type="button" class="btn btn-outline-secondary btn-sm">Eliminar</a></td>
            </tr>
            @else
            @endif
        </tbody>
      </table>
</div>
