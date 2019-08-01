<?php
/**
 * Created by PhpStorm.
 * User: Ivan HERVE
 * Date: 22-07-19
 * Time: 16:26
 */

namespace App\Console\Commands;

use App\Accomodation;
use \Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class HideAccomodation extends Command
{
    protected $signature = 'command:hide';

    protected $description = 'Hide accomodations that are no longer free, those that are rented';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        date_default_timezone_set("Europe/Brussels");
        $accomodations = Accomodation::all();
        foreach ($accomodations as $a) {
            if($a->isStillFree == 0 && date("Y-m-d H:i", strtotime("+1 day",strtotime($a->updated_at))) === date("Y-m-d H:i")){
                DB::update("call hide_accomodation($a->accomodation_id)");
            }
        };
    }
}