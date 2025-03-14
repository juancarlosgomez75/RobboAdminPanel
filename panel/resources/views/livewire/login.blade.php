<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-md-6 col-lg-6">
            <div class="card border p-5 shadow-lg text-center mx-auto custom-card">
                <div class="card-body">
                    <img src="{{ asset('img/Logo.png') }}" class="img-fluid d-block mx-auto" alt="Imagen" width="300" style="padding: 8px; margin-bottom: 2rem; margin-top: 1rem;">

                    <!-- ALERTA DE ERROR -->
                    @if($error)
                        <div class="alert alert-danger text-center">
                            <strong>¡Error!</strong> Usuario o contraseña incorrectos, o la cuenta fue desactivada.
                        </div>
                    @endif

                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">Usuario:</label>
                        <input type="text" class="form-control form-control-lg" name="username" required wire:model="username" autocomplete="off">
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">Contraseña:</label>
                        <input type="password" class="form-control form-control-lg" name="password" required wire:model="password" autocomplete="off">
                    </div>

                    <button type="submit" class="btn btn-danger w-100" wire:click="tryLogin()">Ingresar</button>

                    <!-- Mensajes de respuesta -->
                    <div class="mt-3">
                        <strong>{{$response}}</strong>
                        <br>
                        <small>{{$prueba}}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>