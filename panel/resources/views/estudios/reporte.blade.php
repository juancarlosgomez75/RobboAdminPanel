@extends('paneltemplate')
@section('title','Reporte de estudio')

</style>
@section("contenido")
@livewire("estudios.reporte",compact("Informacion","Managers","Maquinas","Ciudades"))

@endsection
