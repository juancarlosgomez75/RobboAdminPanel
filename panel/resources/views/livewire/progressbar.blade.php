<div wire:poll.500ms>
    {{-- {{$progreso}} --}}
    <br>
    {{-- {{json_encode($resultados)}} --}}

    <div class="progress" role="progressbar" aria-valuenow="{{$progreso}}" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar" style="width: {{$progreso}}%">{{$progreso}}%</div>
      </div>

</div>


