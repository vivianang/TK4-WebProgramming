@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Manage Item</h1>
@stop

@section('content')
<div class="card">
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
@stop

@section('css')
@stop

@section('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@stop