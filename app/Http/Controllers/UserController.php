<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Ambil data user beserta field (bidang) dan role
        $users = User::with(['field', 'role'])->get();
        return view('users', compact('users'));
    }
}
