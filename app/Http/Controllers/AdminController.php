<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getUsers()
    {
        //$users = User::all(); if(!$users) return $this->errorRes(["Il n'y a pas d'utilisateur existant"],404);

        $users = DB::select("call users_management();"); if(!$users) return $this->errorRes(["Il n'y a pas d'utilisateur existant"],404);

        return $this->successRes($users);
    }
}
