<?php

namespace App\Http\Controllers;

use App\DataTables\CustomersDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Customer;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class CustomersController extends Controller
{
    public function index(Request $request, CustomersDataTable $dataTable)
    {
        if($request->user()->cannot('customer:browse')) {
            return redirect()->route('dashboard');
        }

        return $dataTable->render('pages.customers.index');
    }

    public function add(Request $request) {
        if($request->user()->cannot('customer:add')) {
            return redirect()->route('dashboard');
        }

        return view('pages.customers.add');
    }

    public function submitAdd(Request $request) {
        if($request->user()->cannot('customer:add')) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'name' => 'required',
            'place_of_birth' => 'required',
            'date_of_birth' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'identity_photo_url' => 'required|url',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:password',
        ]);

        // Check for Existing User
        $existingUser = User::where('email', '=', $request->get('email'))->count();
        if($existingUser > 0) {
            throw ValidationException::withMessages(['email' => 'Email already used!']);
        }

        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        // Find newly created user, assign to staff role
        $userCustomer = User::where('email', $request->get('email'))->first();
        $userCustomer->assignRole(Role::where('name', '=', 'customer')->first());

        // Create staff object
        $newCustomer = Customer::create([
            'user_id' => $userCustomer->id,
            'place_of_birth' => $request->get('place_of_birth'),
            'date_of_birth' => $request->get('date_of_birth'),
            'address' => $request->get('address'),
            'identity_photo_url' => $request->get('identity_photo_url'),
            'gender' => $request->get('gender')
        ]);
        
        // Update user with new staff_id
        $userCustomer->customer_id = $newCustomer->id;
        $userCustomer->save();

        return redirect()->route('customers.index');
    }

    public function edit(Request $request, string $id) {
        if($request->user()->cannot('customer:edit')) {
            return redirect()->route('dashboard');
        }

        $users = User::where('id', '=', $id)->first();
        if ($users == null) {
            return back();
        }

        return view('pages.customers.edit', ['data' => $users]);
    }

    public function submitEdit(Request $request, string $id) {
        if($request->user()->cannot('staff:edit')) {
            return redirect()->route('dashboard');
        }

        $users = User::where('id', '=', $id)->first();
        if ($users == null) {
            return back();
        }

        $request->validate([
            'name' => 'required',
            'gender' => 'required'
        ]);

        if($request->get('password') != "") {
            $request->validate([
                'password' => 'required|min:8',
                'confirm_password' => 'required|min:8|same:password',
            ]);

            $users->password = Hash::make($request->get('password'));
        }

        $users->name = $request->get('name');
        $users->customer->place_of_birth = $request->get('place_of_birth');
        $users->customer->date_of_birth = $request->get('date_of_birth');
        $users->customer->address = $request->get('address');
        $users->customer->identity_photo_url = $request->get('identity_photo_url');
        $users->customer->gender = $request->get('gender');
        $users->save();
        $users->customer->save();

        return redirect()->route('customers.index');
    }

    public function delete(Request $request, string $id) {
        if($request->user()->cannot('customer:delete')) {
            return back();
        }

        $users = User::where('id', '=', $id)->first();
        if ($users == null) {
            return back();
        }

        $users->delete();
        return redirect()->route('customers.index');
    }
}
