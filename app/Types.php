<?php
/**
 * Created by PhpStorm.
 * User: Ivan HERVE
 * Date: 16-03-19
 * Time: 13:38
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    protected $table = 'typeaccomodation';
    protected $primaryKey = "typeAcc_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'typeAcc_id',
        'type'
    ];
}