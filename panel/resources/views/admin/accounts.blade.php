@extends('paneltemplate')
@section('title','Administración de cuentas')

</style>
@section("contenido")
@foreach($accounts as $user)
    <p>Usuario: {{ $user->name }} - Rango: {{ $user->rank->name }}</p>
@endforeach
@livewire("admin.accounts")

@endsection
