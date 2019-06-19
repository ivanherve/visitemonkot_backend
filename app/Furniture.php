<?php
/**
 * Created by PhpStorm.
 * User: Ivan HERVE
 * Date: 16-03-19
 * Time: 13:38
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Furniture extends Model
{
    protected $table = 'Furniture';
    protected $primaryKey = "Furniture_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Furniture_id',
        'Furniture'
    ];
}