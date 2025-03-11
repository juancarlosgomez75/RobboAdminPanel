@extends('paneltemplate')
@section('title','Registro de máquinas')

</style>
@section("contenido")
@livewire("maquinas.create",["information"=>$information])

@endsection
