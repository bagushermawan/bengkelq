<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function index()
    {
        $role=Role::all();
        $daftar_user = User::paginate();
        $count = User::count();
        // dd($daftar_product->categories->name);

        return view("user.index", ["daftar_user" => $daftar_user], compact('count'));
    }

    public function create()
    {
        $role=Role::all();
        return view('user.create', [
        	'role' => $role,
        ]);
    }

    
}
