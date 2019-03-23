<?php
/**
 * Created by PhpStorm.
 * User: Ivan HERVE
 * Date: 16-03-19
 * Time: 13:38
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Accomodation extends Model
{
    protected $table = 'accomodation';
    protected $primaryKey = "accomodation_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'accomodation_id',
        'Title',
        'nbRoom',
        'priceRent',
        'priceCharges',
        'BeginingTime',
        'EndTime',
        'Description',
        'HasWifi',
        'HasCarPark',
        'HasFurnitures',
        'isStillFree',
        'nbVisit',
        'PublicationDate',
        'Surface',
    ];
}