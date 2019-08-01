<?php
/**
 * Created by PhpStorm.
 * User: Ivan HERVE
 * Date: 22-07-19
 * Time: 16:26
 */

namespace App\Console\Commands;

use \Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FeedbackVisit extends Command
{
    protected $signature = 'command:feedback';

    protected $description = 'Send an email to remind the user who had visited an accomodation to give a feedback';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // for loop on all visits, check date
        date_default_timezone_set("Europe/Brussels");
        $visits = DB::select("call get_all_approved_visit();");

        foreach ($visits as $v){
            if(date("Y-m-d H:i", strtotime("+2 Hours",strtotime($v->visitDate))) === date("Y-m-d H:i")){
//                array_push($tab,['Yes' => $v]);
                $subject = 'Feedback de la visite';

                $email = 'he201342@students.ephec.be'; //$v->email;

                $data = [
                    'headTitle' => $subject,
                    'title' => 'Feedback de la visite',
                    'visiter' => $v->visiter,
                    'accomodation' => $v->accomodation
                ];

                Mail::send('email.feedbackVisit', $data, function ($msg) use ($subject, $email) {
                    $msg->to($email)->subject($subject);
                });
                //Log::info("");
            }
        }

        //return $this->successRes($tab);

        //return view('email.feedbackVisit',$data);
    }
}