<?php
/**
 * Created by PhpStorm.
 * User: Ivan HERVE
 * Date: 10-03-19
 * Time: 15:32
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $table = 'Pictures';
    protected $primaryKey = 'picture_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'picture_id', 'picPath'
    ];

    public function accomodation() {
        $this->belongsToMany(Accomodation::class, 'accomodation_has_pictures' ,'accomodation_id');
    }
}