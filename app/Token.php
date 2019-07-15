<?php
/**
 * Created by PhpStorm.
 * User: Ivan HERVE
 * Date: 10-03-19
 * Time: 15:32
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'token';
    protected $primaryKey = 'token_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'api_token', 'token_id'
    ];

    public function user() {
        $this->belongsTo(User::class, 'user_id');
    }
}