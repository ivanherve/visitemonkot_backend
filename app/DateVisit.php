<?php
/**
 * Created by PhpStorm.
 * User: Ivan HERVE
 * Date: 30-04-19
 * Time: 00:42
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class DateVisit extends Model
{
    protected $table = 'date_visit';
    protected $primaryKey = 'iddate_visit';

    protected $fillable = [
        'accomodation_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
    ];
}