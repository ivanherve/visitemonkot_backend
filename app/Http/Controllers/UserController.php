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

        if (hash('sha256',$password) != $user->password){
            return $this->jsonRes('error', 'Ancien mot de passe incorrect',401);
        }

        $newPassword = $request->input('newPassword');
        $confirmPassword = $request->input('confPassword');
        if ($newPassword != $confirmPassword){
            return $this->jsonRes('error','Les deux mots de passe ne sont pas identiques !',403);
        }
        $user->update(['password' => hash('sha256',$newPassword)]);
        if ($user->password != hash('sha256',$newPassword)){
            return $this->jsonRes('error','password problem',400);
        }
        return $this->jsonRes('success',$user->Firstname.', votre mot de passe vient d\'être modifié.',200);
    }
}
