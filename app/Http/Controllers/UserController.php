<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function welcome(){
        return view('welcome');
    }

    public function about(){
        // $records = [];
        return view('about');
    }

    public function listUsers(){
        // dd(DB::connection()->getDatabaseName()); //to get current database name--checking connected or not
        $users = User::all();
        return view('listUsers', compact('users'));
    }

    //create user
    public function create(){
        return view('create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:4',
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->password = $request->input('password');
        $user->save();

        return redirect('/users')->with('success', 'User created successfully');
    }

    //Edit user
    public function edit($id){
        $user = User::find($id);
        return view('editUser', compact('user'));
    }

    //Update user
    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:4',
        ]);

        $user = User::find($id);
        $user->name = $request->input('name');
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();

        return redirect('/users')->with('success', 'User updated successfully');    
    }

    //delete user
    public function delete($id){
        $user = User::find($id);
        $user->delete();

        return redirect('/users')->with('success', 'User deleted successfully');
    }
}
