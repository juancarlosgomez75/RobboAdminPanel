@extends('paneltemplate')
@section('title','Creación de estudios')

</style>
@section("contenido")
@livewire("estudios.create",['Ciudades' => $Ciudades])

@endsection
