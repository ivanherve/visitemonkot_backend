<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes('Unauthorized',401);

        $firstname = $request->input("firstname"); if(!$firstname) return $this->errorRes(["Veuillez insérer un prénom"],404);
        $surname = $request->input("surname"); if(!$surname) return $this->errorRes(["Veuillez insérer un nom de famille"],404);
        $email = $request->input("email"); if(!$email) return $this->errorRes(["Veuillez insérer une adresse email"],404);

        $update = DB::update("call update_info('$firstname','$surname','$email', '$user->user_id')");

        return $this->successRes("Vos informations ont été modifié");
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $password = $request->input('password');

        $check = DB::select("call check_user('$password','$user->email')"); if(!$check) return $this->errorRes(["Ancien mot de passe incorrect"],401);

        /**/
        $newPassword = $request->input('newPassword'); if(!$newPassword) return $this->errorRes(["Veuillez entrer votre nouveau mot de passe"],404);
        $confirmPassword = $request->input('confPassword'); if(!$confirmPassword) return $this->errorRes(["Veuillez confirmer votre nouveau mot de passe"],404);
        if ($newPassword != $confirmPassword){
            return $this->jsonRes('error','Les deux mots de passe ne sont pas identiques !',403);
        }

        DB::update("call update_pwd('$newPassword',$user->user_id)");

        return $this->jsonRes('success',$user->Firstname.', votre mot de passe vient d\'être modifié.',200);

    }
}
