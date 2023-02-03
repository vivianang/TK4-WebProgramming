@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Create Item</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" value="{{ old('name', $data->name) }}" class="form-control @error('name') is-invalid @enderror" name="name">
            
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $data->description) }}</textarea>
            
            @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" value="{{ old('type', $data->type) }}" class="form-control @error('type') is-invalid @enderror" name="type">
            
            @error('type')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" value="{{ old('stock', $data->stock) }}" class="form-control @error('stock') is-invalid @enderror" name="stock">
            
            @error('stock')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>

            <div class="form-group">
                <label for="buying_price">Buying Price</label>
                <input type="number" value="{{ old('buying_price', $data->buying_price) }}" class="form-control @error('buying_price') is-invalid @enderror" name="buying_price">
            
            @error('buying_price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>

            <div class="form-group">
                <label for="selling_price">Selling Price</label>
                <input type="number" value="{{ old('selling_price', $data->selling_price) }}" class="form-control @error('selling_price') is-invalid @enderror" name="selling_price">
            
            @error('selling_price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>

            <div class="form-group">
                <label for="image_url">Image URL</label>
                <input type="text" value="{{ old('image_url', $data->image_url) }}" class="form-control @error('image_url') is-invalid @enderror" name="image_url">
                <small>Please upload picture manually and paste uploaded URL here</small>
                
            @error('image_url')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
@stop

@section('css')
@stop

@section('js')

@stop