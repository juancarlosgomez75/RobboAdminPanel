@extends('paneltemplate')
@section('title','Visualización de estudio')

</style>
@section("contenido")
@livewire("estudios.viewedit",["Managers"=>$Managers,"Ciudades"=>$Ciudades,"CiudadActual"=>$CiudadActual])

@endsection
