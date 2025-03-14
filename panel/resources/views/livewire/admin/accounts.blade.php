<div>
    @if($alerta)
        @if($alerta_sucess!="")
        <div class="alert alert-success" role="alert">
            {{$alerta_sucess}}
        </div>
        @elseif($alerta_error!="")
        <div class="alert alert-danger" role="alert">
            {{$alerta_error}}
        </div>
        @elseif($alerta_warning!="")
        <div class="alert alert-warning" role="alert">
            {{$alerta_warning}}
        </div>
        @endif
    @endif
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de cuentas</h5>
                    {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    <a href="#" class="btn btn-sm action-btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <i class="fa-solid fa-plus"></i> Crear cuenta
                    </a>
                    
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 7%;">#</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Rango</th>
                                <th scope="col" style="width: 15%;"></th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts as $account)
                                <tr>
                                    <th scope="row">{{ $account->id }}</th>
                                    <td>{{$account->username}}</td>
                                    <td>{{$account->name}}</td>
                                    <td>{{$account->rank_info->name}}</td>
                                    <td>
                                        @if($account->activo )
                                        <i class="fa-solid fa-circle-check" style="color: green;"></i>
                                        @else
                                        <i class="fa-solid fa-circle-xmark" style="color: red;"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if(auth()->user()->rank < $account->rank)
                                        Sin acciones
                                        @elseif(auth()->user()->id == $account->id)
                                        Tu cuenta
                                        @else
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="{{route('admin.account.view',$account->id)}}">Visualizar</a>
                                        @endif
                                    </td>
                                    {{-- <td>{{ $dato["StudyName"] ?? 'Sin Nombre' }}</td>
                                    <td>{{ $dato['City'] ?? 'No especificada' }}</td>
                                    <td>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="estudio/{{ $dato['Id'] }}">Visualizar</a>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
  
  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Registrar nuevo usuario de plataforma</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>Al presiojar en <b>Crear cuenta</b>, autorizas al usuario a ingresar a la platafoma.</p>
            <p>Por seguridad, no podrás crear usuarios con rango superior al tuyo.</p>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Usuario</label>
                    <input type="text" class="form-control" placeholder="Ejemplo: admin" wire:model="username" required>
                    <div class="form-text">Con el que se loguea.</div>
                    <br>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" placeholder="Ejemplo: Pedro" wire:model="name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" placeholder="Ejemplo: admin@admin.com" wire:model="email" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rango</label>
                    <select class="form-select" wire:model="rank" required>
                        <option selected disabled value="0">Selecciona un rango</option>
                        @foreach($rangos as $rank)
                        <option value="{{$rank["id"]}}">{{$rank["name"]}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="registrar()">Crear cuenta</button>
        </div>
      </div>
    </div>
  </div>

    <br>
</div>