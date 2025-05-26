@extends('paneltemplate')
@section('title','Visualización de modelo')

</style>
@section("contenido")
@livewire("modelos.viewedit",compact("ModelInformation","ManagerInformation","StudyInformation"))

@endsection
