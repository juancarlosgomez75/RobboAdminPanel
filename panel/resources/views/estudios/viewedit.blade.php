@extends('paneltemplate')
@section('title','Visualización de estudio')

</style>
@section("contenido")
@livewire("estudios.viewedit",["Informacion"=>$Information,"Managers"=>$Managers,"Modelos"=>$Models,"Maquinas"=>$Machines,"Ciudades"=>$Ciudades])

@endsection
