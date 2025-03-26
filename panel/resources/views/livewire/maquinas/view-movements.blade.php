<div class="row">
    <div class="col-md-12" id="Filtros">

        <div class="row">
            <div class="col-md-3">
                <input type="text" class="custom-input" placeholder="Filtrar por fecha" wire:model.change="filtroFecha">
            </div>
            <div class="col-md-3">
                <input type="text" class="custom-input" placeholder="Filtrar por acción" wire:model.change="filtroAccion">
            </div>
            <div class="col-md-3">
                <input type="text" class="custom-input" placeholder="Filtrar por autor" wire:model.change="filtroAutor">
            </div>
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Fecha y hora</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Autor</th>
                            <th scope="col" style="width: 15%;"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                
            </div>
            {{-- <div class="col-md-12">
                {{ $logs->links() }}
            </div> --}}
        </div>
    </div>
</div>
