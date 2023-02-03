<?php

namespace App\Http\Controllers;

use App\DataTables\ItemsDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Item;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class ItemsController extends Controller
{
    public function index(Request $request, ItemsDataTable $dataTable)
    {
        if($request->user()->cannot('item:browse')) {
            return redirect()->route('dashboard');
        }

        return $dataTable->render('pages.items.index');
    }

    public function add(Request $request) {
        if($request->user()->cannot('item:add')) {
            return redirect()->route('dashboard');
        }

        return view('pages.items.add');
    }

    public function submitAdd(Request $request) {
        if($request->user()->cannot('item:add')) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'type' => 'required',
            'stock' => 'required|numeric',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'image_url' => 'required',
        ]);

        Item::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'type' => $request->get('type'),
            'stock' => $request->get('stock'),
            'buying_price' => $request->get('buying_price'),
            'selling_price' => $request->get('selling_price'),
            'image_url' => $request->get('image_url'),
        ]);

        return redirect()->route('items.index');
    }

    public function edit(Request $request, string $id) {
        if($request->user()->cannot('item:edit')) {
            return redirect()->route('dashboard');
        }

        $items = Item::where('id', '=', $id)->first();
        if ($items == null) {
            return back();
        }

        return view('pages.items.edit', ['data' => $items]);
    }

    public function submitEdit(Request $request, string $id) {
        if($request->user()->cannot('item:edit')) {
            return redirect()->route('dashboard');
        }

        $items = Item::where('id', '=', $id)->first();
        if ($items == null) {
            return back();
        }

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'type' => 'required',
            'stock' => 'required|numeric',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'image_url' => 'required',
        ]);

        $items->name = $request->get('name');
        $items->description = $request->get('description');
        $items->type = $request->get('type');
        $items->stock = $request->get('stock');
        $items->buying_price = $request->get('buying_price');
        $items->selling_price = $request->get('selling_price');
        $items->image_url = $request->get('image_url');
        $items->save();

        return redirect()->route('items.index');
    }

    public function delete(Request $request, string $id) {
        if($request->user()->cannot('item:delete')) {
            return back();
        }

        $users = Item::where('id', '=', $id)->first();
        if ($users == null) {
            return back();
        }

        $users->delete();
        return redirect()->route('items.index');
    }
}
