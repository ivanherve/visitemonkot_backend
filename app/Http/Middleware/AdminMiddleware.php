<?php

namespace App\Http\Middleware;

use App\Token;
use App\User;
use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = Token::all()->where('api_token','=',$request->header('Authorization'))->first();
        $user = User::all()->where('user_id','=',$token->user_id)->first();
        if($user->profil_id != 3){
            return response(['status'=>'error','response' => ['Unauthorized.']], 401);
        }
        return $next($request);
    }
}
