<div class="row">
    <div class="col-md-12" id="Filtros">

        <div class="row">
            <div class="col-md-3">
                <input type="text" class="custom-input" placeholder="Filtrar por fecha" wire:model.change="filtroFecha">
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
                        @if(!$history->isEmpty())
                        @foreach ($history as $reporte)
                        <tr>
                            <td>
                                {{ $reporte->created_at }}
                            </td>
                            <td>
                                {{ $reporte->description }}
                            </td>
                            <td>
                                {{ $reporte->author_info->username }}
                            </td>
                            <td>
                                <a type="button" class="btn btn-outline-secondary btn-sm" wire:click="showInfo({{ $reporte->id }})">Ver detalles</a>
                            </td>
                        </tr>
                        
                        @endforeach
                        @else
                        <tr class="text-center">
                            <td colspan="4">No se han encontrado registros aún para esta máquina</td>
                        </tr>
                        @endif

                    </tbody>
                </table>
                
            </div>
            <div class="col-md-12">
                {{ $history->links() }}
            </div>
        </div>
    </div>


    <!-- Modal Bootstrap -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Detalles de movimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Esta es la información almacenada para este movimiento
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="text" class="form-control" placeholder="Ejemplo: Juguetes" wire:model="Fecha" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <input type="text" class="form-control" placeholder="Ejemplo: Juguetes" wire:model="Description" disabled>
                    </div>
                    <div class="mb-3">
                        <labelclass="form-label">Detalles</label>
                        <textarea class="form-control" rows="3" wire:model="Details" disabled></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Livewire.on('abrirModalInfo', () => {
                let modal = new bootstrap.Modal(document.getElementById('infoModal'));
                modal.show();
            });
    
            Livewire.on('cerrarModalInfo', () => {
                let modalEl = document.getElementById('infoModal');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            });
        });
    </script>

</div>
