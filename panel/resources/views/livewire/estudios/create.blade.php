<div>
    <div class="alert alert-success" role="alert">
        A simple success alert—check it out!
    </div>
    <div class="alert alert-danger" role="alert">
        A simple danger alert—check it out!
    </div>
    <div class="alert alert-warning" role="alert">
        A simple warning alert—check it out!
    </div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title">Crear estudio</h5>
                    <p class="card-text">Por favor completa toda la información para registrar el estudio</p><br>
                </div>
                <div class="col-md-12">
                    
                    <div class="row">
                        <div class="col-md-7">
                            <label for="studyname" class="form-label">Nombre del estudio</label>
                            <input type="text" class="form-control" id="studyname" aria-describedby="studynameHelp">
                            <div id="studynameHelp" class="form-text">Si es una cadena de estudios, por favor indica la ciudad en el nombre. Ej: JB Medellin</div>
                        </div>
                        <div class="col-md-5">
                            <label for="cityname" class="form-label">Ciudad</label>
                            <select class="form-select" id="cityname" aria-label="cityHelp">
                                <option selected disabled value="0">Selecciona la ciudad y pais del estudio</option>
                                @foreach($datosUsar as $index => $dato)
                                <tr>
                                    <th scope="row">{{ $dato['Id'] }}</th>
                                    <td>{{ $dato["StudyName"] ?? 'Sin Nombre' }}</td>
                                    <td>{{ $dato['City'] ?? 'No especificada' }}</td>
                                    <td>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="estudio/{{ $dato['Id'] }}">Visualizar</a>
                                    </td>
                                </tr>
                            @endforeach
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <br>
</div>