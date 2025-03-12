@extends('paneltemplate')
@section('title','Visualización de manager')

</style>
@section("contenido")
@livewire("estudios.manager-viewedit",compact("Information","Models"))

@endsection
