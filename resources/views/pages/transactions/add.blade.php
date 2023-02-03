@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Create Item</h1>
@stop

@section('content')
<form action="" method="POST">
    @csrf
    @if(auth()->user()->customer == null)
    <div class="card">
        <div class="card-header">
            <p>Create as Customer</p>
        </div>
        <div class="card-body">
            <div class="form-group">
                <select name="customer_id" class="form-control @error('description') is-invalid @enderror">
                    @foreach($customers as $customer)
                    <option value="{{$customer->id}}">{{$customer->id}} - {{$customer->user->name}}</option>
                    @endforeach
                </select>

                @error('customer_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
    </div>
    @else
    <input type="hidden" name="customer_id" value="{{auth()->user()->customer->id}}"></input>
    @endif

    <div class="card">
        <div class="card-header">
            <p>Items</p>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Item Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Total Price</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="items">
                    <tr>
                        <td>
                            <select class="form-control item-id" name="item_id[]">
                                <option value="0" selected disabled>Select Item</option>
                                @foreach($items as $item)
                                <option value="{{$item->id}}" x-price="{{$item->selling_price}}" x-max-stock="{{$item->stock}}">{{$item->id}} - {{$item->name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td></td>
                        <td><input value="0" class="form-control qty" name="qty[]" type="number"></input></td>
                        <td></td>
                        <td><a href="#" class="btn btn-primary btn-sm btn-add">+</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <button type="submit" class="btn btn-primary pull-right">Submit</button>
</form>

@stop

@section('css')
@stop

@section('js')
<script>
    const items = $("#items");

    function addItem() {
        const template = items.children()[0].cloneNode(true);
        $(template).children()[4].innerHTML += `<a href="#" class="btn btn-danger btn-sm btn-delete ml-2">-</button>`
        $($($(template).children()[2]).children()[0]).val(0);
        $($(template).children()[1]).text('');
        $($(template).children()[3]).text('');
        items.append(template);
    }

    function deleteItem(elem) {
        $(elem).parent().parent().remove();
    }

    function calculateAll() {
        for (let i = 0; i < items.children().length; i++) {
            const __ = $(items.children()[i]);
            const __item = $($(__.children()[0]).children()[0]);
            const __peritem = $(__.children()[1]);
            const __qty = $($(__.children()[2]).children()[0]);
            const __total = $(__.children()[3]);

            if(parseInt(__qty.val()) >= parseInt(__qty.prop('max'))) {
                __qty.val(__qty.prop('max'));
            }

            $(__item).find('option').each((idx, e) => {
                const curentOption = $(e);
                if (curentOption.prop('selected') && curentOption.val() != 0) {
                    __peritem.text(curentOption.attr('x-price'));
                    const total = curentOption.attr('x-price') * parseInt(__qty.val());
                    __total.text(total);
                }
            })
        }
    }

    function refreshItemRow(elem) {
        const current = $(elem).parentsUntil('tbody');
        const item = $(current[1]);
        item.find('.item-id').find('option').each((idx, e) => {
            const curentOption = $(e);
            if (curentOption.prop('selected') && curentOption.val() != 0) { 
                const qty = item.find('.qty')
                qty.val(0);
                qty.prop('min', 0);
                qty.prop('max', curentOption.attr('x-max-stock'));
            }
        })
        calculateAll();
    }

    $(document).ready(() => {
        $(document).on('click', '.btn-add', function() {
            addItem();
        });
        $(document).on('click', '.btn-delete', function(elem) {
            deleteItem(elem.target);
        });
        $(document).on('change', '.qty', function(elem) {
            calculateAll();
        });
        $(document).on('change', '.item-id', function(elem) {
            refreshItemRow(elem.target);
        })
    })
</script>
@stop