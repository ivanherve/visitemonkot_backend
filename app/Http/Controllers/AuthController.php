<?php

namespace App\Http\Controllers;

use App\Token;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        $pswd = hash('sha256', $request->input('password'));
        $user = User::all()->where("email", "=", $request->input('email'))
            ->where("password", "=", $pswd)->first();

        if (!$user) {
            return $this->jsonRes('error', [$pswd,$user,$request->input('email'),$request->input('password')], 401);
        }

        $userId = $user->user_id;
        $hasNotToken = Token::all()->where('user_id','=', $userId)->where('api_token','=',null)->first();
        $tokenId = Token::all()->where('user_id','=', $userId)->where('api_token','=',null)->pluck('token_id')->first();
        if ($hasNotToken){
            $token = str_random(60);
            $newToken = DB::select('UPDATE token SET api_token = \''.$token.'\' WHERE token_id = '.$tokenId.';');
            $newToken = Token::all()->where('user_id','=', $userId)->where('api_token','=',$token)->first();
            return $this->jsonRes('success',['user' => $user, 'token' => $newToken],200);
        }
        else {
            $newToken = Token::create([
                'user_id' => $userId,
                'api_token' => str_random(60)
            ]);
        }
        return $this->jsonRes('success',['user' => $user, 'token' => $newToken],200);

    }

    /**
     * Log out an authenticated user.
     * It get the token in use and update the database to set this token to null.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signOut(Request $request)
    {
        $api_token = $request->header('api_token');
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
        /**/
        $createUser = User::create([
            'Firstname' => $request->input('firstname'),
            'Surname' => $request->input('surname'),
            'email' => $request->input('email'),
            'password' => hash('sha256', $request->input('password')),
            'isAccountActive' => 0,
            'Profil_profil_id' => 1,
        ]);

        if(!$createUser){
            return $this->errorRes('Une erreur s\'est produite durant votre inscription',500);
        }

        // Server Mail
        Mail::send('email.contact', ['name' => $createUser->Firstname, 'surname' => $createUser->Surname, 'email' => $createUser->email], function ($msg) use ($createUser) {
            $msg->to($createUser->email)->subject('Validation de votre inscription sur VisiteMonKot !');
        });


        return $this->jsonRes('success', $createUser, 200);
    }

    public function activateAccount(Request $request)
    {
        //
    }
}
