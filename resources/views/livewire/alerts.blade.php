<div style="position: fixed; top: 0; right: 20px; z-index: 1050;">
    
    @foreach ($toasts as $index => $toast)
    {{-- class="toast show bg-success text-white" para añadir colores --}}
        <div class="toast show mt-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="{{ asset('img/bb.jpg') }}" class="rounded me-2" alt="Icono" width="20">
                <strong class="me-auto">{{ $toast['Title'] ?? 'Notificación' }}</strong>
                <small>Hace un momento</small>
                <button type="button" class="btn-close" wire:click="removeToast({{ $index }})" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ $toast["Message"] }}
            </div>
        </div>
    @endforeach
    {{-- <button wire:click="addTest()">aaaaaa</button> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('closeToast', event => {
                setTimeout(() => {
                    @this.removeToast(0);
                }, event.detail);
            });
        });
    </script>
</div>
