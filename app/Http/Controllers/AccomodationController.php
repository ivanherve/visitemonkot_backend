<?php

namespace App\Http\Controllers;

use App\Accomodation;
use App\User;
use Illuminate\Support\Facades\DB;

class AccomodationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getAccomodations () {
        $accomodations = DB::select('call get_accomodations');

        if(empty($accomodations)){
            return $this->errorRes(["There's no accomodations here",$accomodations],404);
        }

        return $this->successRes($accomodations);
    }
}
