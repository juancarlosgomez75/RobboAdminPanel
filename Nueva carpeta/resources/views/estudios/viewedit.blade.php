@extends('paneltemplate')
@section('title','Visualización de estudio')

</style>
@section("contenido")
@livewire("estudios.viewedit",["Informacion"=>$Information,"Managers"=>$Managers,"Maquinas"=>$Machines,"Ciudades"=>$Ciudades])

@endsection
