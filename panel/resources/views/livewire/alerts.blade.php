<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050; width: auto;">
    <div id="toast-container">
        @foreach($toasts as $index => $toast)
            <div class="toast show fade mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                <div class="toast-header">
                    <img src="{{ asset('img/bb.jpg') }}" class="rounded me-2" alt="Icono" width="20">
                    <strong class="me-auto">{{ $toast['titulo'] ?? 'Notificación' }}</strong>
                    <small>Hace un momento</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"
                        wire:click="eliminarToast"></button>
                </div>
                <div class="toast-body">
                    {{ $toast['mensaje'] }}
                </div>
            </div>
        @endforeach
    </div>
</div>
