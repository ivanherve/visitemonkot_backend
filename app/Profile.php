<?php
/**
 * Created by PhpStorm.
 * User: Ivan HERVE
 * Date: 10-03-19
 * Time: 15:32
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'Profil';
    protected $primaryKey = 'profil_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profil_id', 'profilName'
    ];

    public function user() {
        $this->hasMany(User::class, 'profil_id');
    }
}