@extends('paneltemplate')
@section('title','Visualización de estudio')

</style>
@section("contenido")
@livewire("estudios.viewedit",["Managers"=>$Managers])

@endsection
