<?php
/**
 * Created by PhpStorm.
 * User: Ivan HERVE
 * Date: 10-03-19
 * Time: 15:32
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'City';
    protected $primaryKey = 'city_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_id', 'cityName'
    ];
}