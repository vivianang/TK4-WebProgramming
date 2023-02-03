<?php

namespace App\Http\Controllers;

use App\DataTables\StaffsDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Staff;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class StaffsController extends Controller
{
    public function index(Request $request, StaffsDataTable $dataTable)
    {
        if($request->user()->cannot('staff:browse')) {
            return redirect()->route('dashboard');
        }

        return $dataTable->render('pages.staffs.index');
    }

    public function add(Request $request) {
        if($request->user()->cannot('staff:add')) {
            return redirect()->route('dashboard');
        }

        return view('pages.staffs.add');
    }

    public function submitAdd(Request $request) {
        if($request->user()->cannot('staff:add')) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:password',
            'gender' => 'required'
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
        $userStaff = User::where('email', $request->get('email'))->first();
        $userStaff->assignRole(Role::where('name', '=', 'staff')->first());

        // Create staff object
        $newStaff = Staff::create([
            'user_id' => $userStaff->id,
            'gender' => $request->get('gender')
        ]);
        
        // Update user with new staff_id
        $userStaff->staff_id = $newStaff->id;
        $userStaff->save();

        return redirect()->route('staffs.index');
    }

    public function edit(Request $request, string $id) {
        if($request->user()->cannot('staff:edit')) {
            return redirect()->route('dashboard');
        }

        $users = User::where('id', '=', $id)->first();
        if ($users == null) {
            return back();
        }

        return view('pages.staffs.edit', ['data' => $users]);
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
        $users->staff->gender = $request->get('gender');
        $users->save();
        $users->staff->save();

        return redirect()->route('staffs.index');
    }

    public function delete(Request $request, string $id) {
        if($request->user()->cannot('staff:delete')) {
            return back();
        }

        $users = User::where('id', '=', $id)->first();
        if ($users == null) {
            return back();
        }

        $users->delete();
        return redirect()->route('staffs.index');
    }
}
