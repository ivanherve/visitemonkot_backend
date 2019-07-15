<?php

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Http\Request;
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
        $users = DB::select("call users_management();"); if(!$users) return $this->errorRes(["Il n'y a pas d'utilisateur existant"],404);

        return $this->successRes($users);
    }

    public function getProfiles()
    {
        $profil = Profile::all(); if(!$profil) return $this->errorRes(["Il n'y a pas de profile existant"],404);

        return $this->successRes($profil);
    }

    public function removeAccess(Request $request)
    {
        $uid = $request->input('uid'); if(!$uid) return $this->errorRes(["De quelle utilisateur s'agit-il ?", $uid],404);

        $user = User::all()->where('user_id','=',$uid)->first(); if(!$user) return $this->errorRes(["Cette utilisateur n'existe pas"],404);
        if($user->profil_id == 3) return $this->errorRes(["Unauthorized."],401);
        $signout = DB::update("call signout_a_user($uid);");
        $remove = DB::update("call remove_access($uid);");

        return $this->successRes("Les droits de $user->Firstname $user->Surname ont été retiré");
    }

    public function signOut(Request $request)
    {
        $uid = $request->input('uid'); if(!$uid) return $this->errorRes(["De quelle utilisateur s'agit-il ?", $uid],404);
        $user = User::all()->where('user_id','=',$uid)->first(); if(!$user) return $this->errorRes(["Cette utilisateur n'existe pas", $uid],404);
        if($user->profil_id == 3) return $this->errorRes(["Unauthorized."],401);

        $signout = DB::update("call signout_a_user($uid);");

        return $this->successRes("$user->Firstname $user->Surname est déconnecté");
    }
}
