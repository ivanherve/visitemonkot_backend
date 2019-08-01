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

class RemindVisit extends Command
{
    protected $signature = 'command:remind';

    protected $description = 'Send an email to users who have an appointment for a visit';

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
            if(date("Y-m-d H:i", strtotime("-6 Hours",strtotime($v->visitDate))) === date("Y-m-d H:i")){
                //
                $subject = 'Rappel de visite';
                $emailVisiter = 'he201342@students.ephec.be'; // $v->email;
                $emailOwner = 'he201342@students.ephec.be'; // $v->emailOwner;

                $dataVisiter = [
                    'case' => 'Annonceur',
                    'accomodation' => $v->accomodation,
                    'recipient' => $v->visiter,
                    'title' => $subject,
                    'headTitle' => $subject,
                    'otherPart' => $v->owner,
                    'mobile' => $v->mobileOwner,
                    'date' => $v->visitDate,
                    'address' => $v->address,
                ];

                $dataOwner = [
                    'case' => 'Visiteur',
                    'accomodation' => $v->accomodation,
                    'recipient' => $v->owner,
                    'title' => $subject,
                    'headTitle' => $subject,
                    'otherPart' => $v->visiter,
                    'mobile' => $v->mobileVisiter,
                    'date' => $v->visitDate,
                    'address' => $v->address,
                ];

                Mail::send('email.remindVisit', $dataVisiter, function ($msg) use ($emailVisiter, $subject) {
                    $msg->to($emailVisiter)->subject($subject);
                });

                Mail::send('email.remindVisit', $dataOwner, function ($msg) use ($emailOwner, $subject) {
                    $msg->to($emailOwner)->subject($subject);
                });
            }
        }
    }
}