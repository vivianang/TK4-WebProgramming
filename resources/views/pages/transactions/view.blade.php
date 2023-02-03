@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Transaction Details</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <p>Customer Information</p>
    </div>
    <div class="card-body">
        <p style="font-weight: bold">{{$data->customer->user->name}}</p>
        <p>{{$data->customer->user->email}}</p>
        <p>{{$data->customer->address}}</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <p>Items (Price shown during purchase)</p>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Item Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Total Price</th>
                </tr>
            </thead>
            <tbody id="items">
                @foreach($data->details as $detail)
                <tr>
                    <td>{{$detail->item->name}}</td>
                    <td>{{$detail->price}}</td>
                    <td>{{$detail->qty}}</td>
                    <td>{{$detail->total_price}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@section('css')
@stop

@section('js')
@stop