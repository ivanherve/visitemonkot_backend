<?php

namespace App\Http\Controllers;

use App\Token;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
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

    /**
     * Check the email and the password hashed to the database.
     * If these elements do not exist, the function shows an error message.
     * If they exist, the function will create a new token for the authenticated user if this one does not have a token yet.
     * If the user already has a token, the function will create another token to him/her or
     * it will update the database to insert a token id, the user id and a new token.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn (Request $request) {
        $pswd = $request->input('password');
        $email = $request->input('email');
        $user = DB::select("call log_in('$email','$pswd');"); if (!$user) return $this->jsonRes('error', [$user,$email,$request->input('password')], 401);

        $userId = $user[0]->user_id;
        $hasNotToken = Token::all()->where('user_id','=', $userId)->where('api_token','=',null)->first();
        $tokenId = Token::all()->where('user_id','=', $userId)->where('api_token','=',null)->pluck('token_id')->first();
        if ($hasNotToken){
            $token = str_random(60);
            $newToken = DB::update("call update_token('$token','$tokenId')");
            $newToken = Token::all()->where('user_id','=', $userId)->where('api_token','=',$token)->first();
            return $this->jsonRes('success',['user' => $user[0], 'token' => $newToken],200);
        }
        else {
            $newToken = Token::create([
                'user_id' => $userId,
                'api_token' => str_random(60)
            ]);
        }
        return $this->jsonRes('success',['user' => $user[0], 'token' => $newToken],200);

    }

    /**
     * Log out an authenticated user.
     * It get the token in use and update the database to set this token to null.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signOut(Request $request)
    {
        $api_token = $request->header('Authorization');
        $token = Token::all()->where('api_token', '=', $api_token)->first();
        $tokenId = Token::all()->where('api_token', '=', $api_token)->pluck('token_id')->first();
        if (!$token) {
            return $this->jsonRes('error', 'Vous n\'êtes pas connecté', 401);
        }
        $token = DB::select('UPDATE token SET api_token = null WHERE token_id = '.$tokenId.';');
        $emptyTok = Token::all()->where('token_id','=',$tokenId)->pluck('api_token')->first();
//        return $this->jsonRes('s',$emptyTok,200);
        if($emptyTok == null){
            return $this->jsonRes('success', 'Vous êtes maintenant déconnécté', 200);
        }
        return $this->jsonRes('error', 'Vous n\'êtes pas connecté', 401);
    }

    /**
     * Enable users to sign up to the platform.
     * It checks first if the email address entered already exist in the database, if that is the case it returns a 401 error message.
     * Otherwise, it update the database by inserting new values in User's table, create a new token, a new OCR and log in the new user.
     * @param Request $request
     * @return mixed
     */
    public function signUp(Request $request)
    {
        $users = User::all();
        foreach ($users as $one) {
            if ($one->email == $request->input('email')) {
                return $this->jsonRes('error', 'This e-mail address already exist', 401);
            }
        }
        $firstname = $request->input('firstname'); if(!$firstname) $this->errorRes(['Quelle est votre prénom ?',$firstname],404);
        $surname = $request->input('surname'); if(!$surname) $this->errorRes(['Quelle est votre Nom ?',$surname],404);
        $email = $request->input('email'); if(!$email) $this->errorRes(['Quelle est votre adresse e-mail ?',$email],404);
        $password = $request->input('password'); if(!$password) $this->errorRes(['Insérer un mot de passe svp ?',$password],404);

        $createUser = DB::insert("call create_user('$firstname', '$surname', '$email', '$password');"); if(!$createUser) return $this->errorRes('Une erreur s\'est produite durant votre inscription',500);

        $user = DB::select("call log_in('$email','$password');");

        $user = $user[0];

        $subject = 'Confirmation d\'inscription';
        $data = [
            'headTitle' => $subject,
            'title' => 'Validation de votre inscription sur VisiteMonKot !',
            'user' => $user->Firstname.' '.$user->Surname,
            'link' => '/activateaccount/'.$user->user_id,
        ];

        //return view('email.confSignUp', $data);
        /**/
        // Server Mail
        Mail::send('email.confSignUp', $data, function ($msg) use ($user, $subject) {
            $msg->to('he201342@students.ephec.be')->subject($subject);
        });

        return $this->jsonRes('success', $user, 200);
    }

    public function activateAccount($uid)
    {
        $user = User::all()->where('user_id','=', $uid)->first(); if(!$user) return $this->errorRes(['Cet utilisateur n\'existe pas'],404);
        DB::update("call activate_account($uid)");

        $data = [
            'headTitle' => 'Compte Actif',
            'title' => 'Votre compte est activé',
            'user' => $user->Firstname.' '.$user->Surname
        ];

        return view('email.accountActive', $data);
    }

    public function confSignUp()
    {
        $subject = 'Confirmation d\'inscription';
        $data = [
            'headTitle' => $subject,
            'title' => 'Confirmation de votre inscription',
            'user' => 'Bob Alice-User',
            'link' => '/activateaccount/{uid}',
        ];

        return view('email.confSignUp', $data);
    }

    public function pwdLost(Request $request)
    {
        $subject = 'Mot de passe perdu';

        $email = $request->input('email'); if(!$email) return $this->errorRes(["Veuillez entrer votre adresse e-mail svp !"],404);

        $user = User::all()->where('email','=',$email)->first();

        if(!$user) return $this->errorRes(["Votre adresse est introuvable, êtes-vous d'être bien inscrit ?"],404);

        $newPwd = Str::random(10);

        DB::update("call update_pwd('$newPwd', $user->user_id)");

        $data = [
            'headTitle' => $subject,
            'title' => 'Nouveau Mot de passe',
            'user' => $user->Firstname.' '.$user->Surname,
            'newPwd' => $newPwd
        ];

        $email = 'he201342@students.ephec.be';//$user->email;

//        return view('email.pwdLost',$data);
        Mail::send('email.pwdLost', $data, function ($msg) use ($email, $subject) {
            $msg->to($email)->subject($subject);
        });

        return $this->successRes('Votre mot de passe a été modifié');
    }
}
