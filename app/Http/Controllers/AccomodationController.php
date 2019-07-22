<?php

namespace App\Http\Controllers;

use App\Accomodation;
use App\City;
use App\DateVisit;
use App\Furniture;
use App\Picture;
use App\Types;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Moment\Moment;
use function Sodium\add;

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

    public function getTypes()
    {
        $types = Types::all();
        if(!$types) return $this->errorRes(['No types'],404);
        if(empty($types)) return $this->errorRes(['No types found'],404);

        return $this->successRes($types);
    }

    public function getCities()
    {
        $city = City::all();
        if(!$city) return $this->errorRes([],404);
        if(empty($city)) return $this->errorRes(['No types found'],404);

        return $this->successRes($city);
    }

    public function getAccomodations ($qty) {
        $accomodations = DB::select("call get_accomodations($qty)");

        if(empty($accomodations)){
            return $this->errorRes(["There's no accomodations here",$accomodations],404);
        }

        foreach ($accomodations as $one) {
            $pictures = DB::select("call get_pictures($one->accomodation_id)");
            if(!$pictures) $one->pictures = null;
            else $one->pictures = $pictures;
        }

        return $this->successRes($accomodations);
    }

    public function getAdvertisments()
    {
        $user = Auth::user();
        //return $this->errorRes($user,404);
        /**/
        $advertisment = DB::select('call get_advertisments('.$user->user_id.');');

        if(empty($advertisment)){
            return $this->errorRes(["There's no advertisment here",$advertisment],404);
        }

        return $this->successRes($advertisment);

    }

    public function getOneAdvertisment($id)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes(["Unauthorized."],401);
        $accomod = Accomodation::all()->where('accomodation_id', '=', $id)->first();

        $advertisment = DB::select('call get_one_advertisment('.$accomod->accomodation_id.');');

        if(empty($advertisment)){
            return $this->errorRes(["There's no advertisment here",$advertisment],404);
        }

        return $this->successRes($advertisment);
    }

    public function addOneAdvertisment(Request $request)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes(["Unauthorized."],404);

        $title = $request->input('title'); if(!$title) return $this->errorRes(['entrez un titre',$title],404);
        $nbRoom = $request->input('nbRoom'); if(empty($nbRoom)) return $this->errorRes(['combien de chambre y a t-il ?',$nbRoom],404);
        $priceRent = $request->input('priceRent'); if(empty($priceRent)) return $this->errorRes(['Combien le loyer ?',$priceRent],404);
        $priceCharges = $request->input('priceCharges'); if(empty($priceCharges)) $priceCharges = 0;
        $beginingTime = $request->input('BeginingTime');if(empty($beginingTime)) return $this->errorRes(['Location à partir de ?',$beginingTime],404);
        $endTime = $request->input('EndTime'); if(empty($endTime)) return $this->errorRes(['Location jusque ?',$endTime],404);
        $description = $request->input('Description'); if(empty($description)) return $this->errorRes(['Pouvez-vous donner une description ?',$description],404);
        $hasWifi = $request->input('HasWifi'); if(empty($hasWifi)) $hasWifi = 0;
        $hasCarPark = $request->input('HasCarPark'); if(empty($hasCarPark)) $hasCarPark = 0;
        $hasFurnitures = $request->input('HasFurnitures'); if(empty($hasFurnitures)) $hasFurnitures = 0;
        $surface = $request->input('Surface'); if(empty($surface)) return $this->errorRes(['Combien de m2 ?',$surface],404);

        $cityName = $request->input('cityName'); if(empty($cityName)) return $this->errorRes(['C\'est dans quelle ville ?',$cityName],404);
        $city = City::all()->where('cityName','=', $cityName)->first();
        if(!$city) {
            $city = City::create([
                'cityName' => "$cityName"
            ]);
        }

//        return response()->json($city->city_id);

        $typeNom = $request->input('Type'); if(empty($typeNom)) return $this->errorRes(['De quelle type s\'agit-il ?',$typeNom],404);
        $type = Types::all()->where('type', '=', $typeNom)->first(); if(!$type) return $this->errorRes(['Ce type n\'existe pas',$type],404);
        $typeId = $type->typeAcc_id;

        //return $this->errorRes(["regarder console",$type, $typeId],404);

        $datesVisit = $request->input('datesVisit'); if(!$datesVisit) return $this->errorRes(['A partir de quand organisez-vous les visites ?',$datesVisit],404);
        $datesVisit = json_decode($datesVisit);

        $address = $request->input('address'); if(!$address) return $this->errorRes(['Veuillez indiquer l\'adresse du logement svp ?',$address],404);
//        $endVisit = $request->input('endVisit'); if(!$endVisit) return $this->errorRes(['Jusqu\'à quand organisez-vous les visites ?',$endVisit],404);
        $addressVisible = $request->input('addressVisible'); if(!$addressVisible) $addressVisible = 0;

        $advertisment = Accomodation::create([
            'Title' => $title,
            'Address' => $address,
            'nbRoom' => $nbRoom,
            'priceRent' => $priceRent,
            'priceCharges' => $priceCharges,
            'BeginingTime' => $beginingTime,
            'EndTime' => $endTime,
            'Description' => $description,
            'HasWifi' => $hasWifi,
            'HasCarPark' => $hasCarPark,
            'HasFurnitures' => $hasFurnitures,
            'isStillFree' => 1,
            'Owner_user_id' => $user->user_id,
            'City_city_id' => $city->city_id,
            'TypeAccomodation_typeAcc_id' => $typeId,
            'Surface' => $surface,
            //'BeginingVisit' => $beginingVisit,
            //'EndVisit' => $endVisit,
            'addressVisible' => $addressVisible,
        ]);

        if(!$advertisment){
            return $this->errorRes(['L\'annonce n\'a pu être publié',$advertisment],404);
        }

        $images = $request->input('image'); if(!$images) return $this->errorRes(["Insérez des images",$images],404);

        $images = json_decode($images);

        for ($i = 0; $i < sizeof($images); $i++){
            $insert = DB::insert("call insert_pictures('$images[$i]','$advertisment->accomodation_id')"); if(!$insert) return $this->errorRes(['Il y\'a eu un problème pour importer vos photos'],500);
        }

        $dates = [];

        $typeDate = $request->input('typeDate');

        //return $this->errorRes(['dateVisit' => $datesVisit,'typeDate' =>  $typeDate],401);

        if($typeDate == 2){
            $dV = $datesVisit[0];
            $dates = DateVisit::create([
                'accomodation_id' => $advertisment->accomodation_id,
                'start_date' => $dV->firstDate,
                'end_date' => null,
                'start_time' => $dV->startTimeVisits,
                'end_time' => $dV->endTimeVisits,
            ]);
        } else {
            foreach ($datesVisit as $dV){
                $dates = DateVisit::create([
                    'accomodation_id' => $advertisment->accomodation_id,
                    'start_date' => $dV->firstDate,
                    'end_date' => $dV->lastDate,
                    'start_time' => $dV->startTimeVisits,
                    'end_time' => $dV->endTimeVisits,
                ]);
            }
        }

        if(!$dates) return $this->errorRes(["Une erreur s'est produite aux niveau des dates de visite", $dates],500);

        return $this->successRes([$advertisment,$dates]);

    }

    public function uploadImage(Request $request)
    {
        $user = Auth::user();
        if(!$user) return $this->errorRes('Unauthorized',401);

        $aid = $request->input('aid'); if(!$aid) return $this->errorRes(["De quel logement s'agit-il ?", $aid],404);
        $image = $request->input('image'); if(!$image) return $this->errorRes(["Insérez des images",$image],404);

        $image = json_decode($image);

        for ($i = 0; $i < sizeof($image); $i++){
            DB::insert("call insert_pictures('$image[$i]','$aid')");
        }

//        $send = DB::insert("call insert_pictures('$image','$aid')");
//
        return $this->successRes("Les images sont en bdd");
    }

    public function getImage()
    {
        $user = Auth::user();
        if(!$user) return $this->errorRes('Unauthorized',401);

        $pic = Picture::all(); if(!$pic) return $this->errorRes(["Il n'y a pas de photos",$pic],404);

        return $this->successRes($pic);
    }

    public function editAd(Request $request)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes(['Unauthorized.'],401);
        $accId = $request->input('accId');
        $title = $request->input('title');
        $rent = $request->input('rent');
        $charge = $request->input('charge');
        $wifi = $request->input('hasWifi');
        $furnitures = $request->input('hasFurniture');
        $parking = $request->input('hasParking');
        $description = $request->input('description');
        $cityName = $request->input('cityName'); if(!$cityName) return $this->errorRes(["Il n'y a pas de ville ici"],404);
        $address = $request->input('address'); if(!$address) return $this->errorRes(["Il n'y a pas d'adresse ici"],404);

        $accomodation = DB::select('call get_one_advertisment('.$accId.')');
        if(!$accomodation) return $this->errorRes(['Il n\'a pas de logement ici', $accomodation],404);

        $cityId = City::all()->where('cityName','=',$cityName)->first()->city_id;

        $date = date('Y-m-d H:i:s');

        //$title = str_replace("'","''", $title);
//        $title = str_replace('"','""', $title);
//        $description = str_replace("'","''", $description);
        $description = str_replace('"','\"', $description);

        //return $this->errorRes([$title,"$description"],404);

        $accomodation = DB::update("call update_accomodation($user->user_id, $accId, $cityId, ".'"'.$title.'"'.", $rent, $charge, $wifi, $furnitures, $parking, '$date', ".'"'.$description.'"'.", '$address');");
        if(!$accomodation) return $this->errorRes(['Aucune information n\'a été modifié'],404);
        return $this->successRes($accomodation);
    }

    public function rentAccomodation(Request $request)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes(["Unauthorized."],401);
        $status = $request->input('status');
        $accId = $request->input('accId');

        $accomodation = DB::update('call rent_accomodation('.$accId.','.$user->user_id.');');
        $accomodation = DB::select('call get_one_advertisment('.$accId.');'); if(!$accomodation) return $this->errorRes(['Erreur de mise à jour', $accomodation],500);

        return $this->successRes($accomodation);
    }

    public function freeAccomodation(Request $request)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes(["Unauthorized."],401);
        $status = $request->input('status');
        $accId = $request->input('accId');

        $accomodation = DB::update('call free_accomodation('.$status.','.$accId.','.$user->user_id.');'); if(!$accomodation) return $this->errorRes(['Erreur de mise à jour'],500);
        $accomodation = DB::select('call get_one_advertisment('.$accId.');');

        return $this->successRes($accomodation);
    }

    public function visitAccomodation(Request $request)
    {
        $user = Auth::user();
        $aid = $request->input('aid'); if(!$aid) return $this->errorRes(['De quelle logement il s\'agit ? '],404);
        $date = $request->input('date'); if(!$date) return $this->errorRes(["Veulliez choisir une date pour la visite svp"], 404);
        $time = $request->input('time'); if(!$time) return $this->errorRes(["A quelle heure la visite ?"], 404);
        $accomodation = DateVisit::all()->where('iddate_visit','=',$aid)->first(); if(!$accomodation) return $this->errorRes(['Ce logement n\'existe pas', $accomodation],404);
        $acc = Accomodation::all()->where('accomodation_id','=',$accomodation->accomodation_id)->first(); if(!$acc) return $this->errorRes(['Ce logement n\'existe pas', $aid],404);
        if($accomodation->start_date > $date || ($accomodation->end_date < $date && $accomodation->end_date != null)) return $this->errorRes(["Veuillez choisir une date convenable svp",$date,$accomodation->start_date,$accomodation->end_date],401);
        if($accomodation->start_time > $time || ($accomodation->end_time <= $time && $accomodation->end_time != null)) {
            if(substr($accomodation->start_time,0,-3) != $time) return $this->errorRes(["Veuillez choisir une heure convenable svp",$time,substr($accomodation->start_time,0,-3),$accomodation->end_time, $accomodation->start_time == $time ? 'oui':'non', $aid],401);
        }

        $date = $date.' '.$time.':00';/**/
        $check = DB::select("call check_visit('$user->user_id','$accomodation->accomodation_id')");

        if($check){
            return $this->errorRes(["Vous visitez déjà ce logement", $check],401);
        }

        $owner = User::all()->where('user_id','=',$acc->Owner_user_id)->first();

        Moment::setLocale('fr_FR');

        $ownerName = $owner->Firstname;
        $ownerSurname = $owner->Surname;

        $moment = new Moment($date,'CET');
        $email = 'he201342@students.ephec.be'; // $owner->email ;
        $data = [
            'ownerName' => $ownerName,
            'ownerSurname' => $ownerSurname,
            'visiterName' => $user->Firstname,
            'visiterSurname' => $user->Surname,
            'accomodation' => $acc->Title, // Request
            'dateVisit' => $moment->format('l dS F Y à H:i'),
        ];

        $visit = DB::insert("call visit_accomodation('$user->user_id','$accomodation->accomodation_id','$date');"); if(!$visit) return $this->errorRes(['Une erreur s\'est produite'],500);

        Mail::send('email.confVisit', $data, function ($msg) use ($email) {
            $msg->to($email)->subject('Demande de visite');
        });

        return $this->successRes($date);
//        return view('email.confVisit',$data);
    }

    public function confVisit(Request $request)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes(['Unauthorized.'],404);

        Moment::setLocale('fr_FR');

        $visiter_id = $request->input('visiter_id'); if(!$visiter_id) return $this->errorRes(['De quelle demande s\'agit-il ?'],404);
        $visiter = User::all()->where('user_id','=',$visiter_id)->first(); if(!$visiter) return $this->errorRes(['Cet utilisateur n\'existe pas'],404);

        $aid = $request->input('aid'); if(!$aid) return $this->errorRes(["De quelle logement s'agit-il ?"],404);
        $accommodation = Accomodation::all()->where('accomodation_id','=',$aid)->first(); if(!$accommodation) return $this->errorRes(['Ce logement n\'existe pas'],404);

        $visit = DB::select("call get_one_visit($visiter_id,$aid)"); if(!$visit) return $this->errorRes(["Aucune demande de visite n'a été faite"],404);
        $visit = $visit[0];
        $date = $visit->visitDate;
        $moment = new Moment($date,'CET');

        $city = City::all()->where('city_id','=',$accommodation->City_city_id)->first();

        DB::update("call approve_visit($visiter_id, $aid)");

        $subject = 'Visite accepté';

        $data = [
            'headTitle' => $subject,
            'title' => 'Votre demande de visite a été accepté',
            'accomodation' => $accommodation->Title,
            'visiter' => $visiter->Firstname.' '.$visiter->Surname,
            'date' => $moment->format('l dS F Y à H:i'),
            'address' => $accommodation->Address.', '.$city->cityName
        ];

        $email = 'he201342@students.ephec.be';//$visiter->email;

        Mail::send('email.visitApproved', $data, function ($msg) use ($email, $subject) {
            $msg->to($email)->subject($subject);
        });

        return $this->successRes('Visite Confirmé');
    }

    public function refuseVisit(Request $request)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes(['Unauthorized.'],404);

        $visiter_id = $request->input('visiter_id');
        $aid = $request->input('aid');

        DB::delete("call refuse_visit($visiter_id, $aid)");

        return $this->successRes('Visite rejeté');
    }

    public function getVisits()
    {
        $user = Auth::user();
        $visits = DB::select("call get_visits('$user->user_id')"); if(!$visits) return $this->errorRes(["Vous n'avez prévu aucune visite", $visits],404);
        return $this->successRes($visits);
    }

    public function getVisitors($id)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes('Unauthorized',401);

        $visitors = DB::select("call get_visitors($id)"); if(!$visitors) return $this->errorRes(['Il n\'y a pas de visiteur pour ce logement',$visitors],404);

        return $this->successRes($visitors);
    }

    public function getVisitDatesOfOneAccomodation($id)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes('Unauthorized',401);

        $visitDates = DateVisit::all()->where('accomodation_id','=',$id); if(!$visitDates) return $this->errorRes("Il n'y a pas de visite organisé pour ce logement",404);

        return $this->successRes($visitDates);
    }

    public function getFurnitures()
    {
        $furnitures = Furniture::all();
    }

    public function updateVisits(Request $request)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes('Unauthorized',401);

        $strd = $request->input('start_date'); if(!$strd || $strd == 'undefined') return $this->errorRes(["A partir de quand démarre la visite ?",$strd],404);
        $endd = $request->input('end_date'); if(!$endd || $endd == 'undefined') return $this->errorRes(["Quand se termine la visite ?",$endd],404);

        $strt = $request->input('start_time'); if(!$strt || $strt == 'undefined') return $this->errorRes(["A quelle heure commence les visites ?",$strt],404);
        $endt = $request->input('end_time'); if(!$endt || $endt == 'undefined') return $this->errorRes(["A quelle heure se terminent les visites ?",$endt],404);

        $id = $request->input('id_visit'); if(!$id) return $this->errorRes(['De quelle visite s\'agit-il ?',$id],404);

        $dateVisit = DateVisit::all()->where('iddate_visit','=',$id)->first(); if(!$dateVisit) return $this->errorRes(['Cette visite n\'existe pas', $dateVisit],404);
        if($strd > $endd) return $this->errorRes(["Veuillez chosir une date convenable svp ?",$strd,$endd],401);
        if($strt > $endt) return $this->errorRes(["Veuillez chosir une heure convenable svp ?",$strt,$endt],401);

//        return $this->errorRes(["Rien n'a été modifié ici",[$strd,$endd,$strt, $endt,$id]],404);
        if($endd != 'null') $endd = "'".$endd."'";
        $edit = DB::update("call update_date_visit('$strd',$endd,'$strt','$endt',$id);"); if(!$edit) return $this->errorRes(["Rien n'a été modifié ici",[$strd,$endd,$strt, $endt,$id]],304);

        return $this->successRes("Vos dates de visite ont bien été mis à jours");
    }

    public function addVisitDate(Request $request)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes('Unauthorized',401);

        $strd = $request->input('start_date'); if(!$strd || $strd == 'undefined') return $this->errorRes(["A partir de quand démarre la visite ?",$strd],404);
        $endd = $request->input('end_date'); if(!$endd || $endd == 'undefined') return $this->errorRes(["Quand se termine la visite ?",$endd],404);
        $aid = $request->input('accomodation_id'); if(!$aid) return $this->errorRes(["De quel logement il s'agit ?", $aid],404);

        if($endd == 'noe') {
            $endd = null;
            $deleteVisits = DB::delete("call delete_visits($aid)");
        }

        if($endd != null){
            $endd = "'".$endd."'";
        }

        $strt = $request->input('start_time'); if(!$strt || $strt == 'undefined') return $this->errorRes(["A quelle heure commence les visites ?",$strt],404);
        $endt = $request->input('end_time'); if(!$endt || $endt == 'undefined') return $this->errorRes(["A quelle heure se terminent les visites ?",$endt],404);

//        $add = DB::insert("call new_visit_date($aid,'$strd',".null.",'$strt','$endt');");

        $add = DateVisit::create([
            'accomodation_id' => $aid,
            'start_date' => $strd,
            'end_date' => $endd,
            'start_time' => $strt,
            'end_time' => $endt,
        ]);

        return $this->successRes('Nouvelle date de viste ajouté');
    }

    public function updateMyVisit(Request $request)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes('Unauthorized',401);

        $aid = $request->input('aid'); if(!$aid) return $this->errorRes(["De quel logement s'agit-il ?"],401);

        $accomo = Accomodation::all()->where('accomodation_id','=',$aid)->first(); if(!$accomo) return $this->errorRes(["Ce logement n'existe pas"],404);

        $date = $request->input('date'); if(!$date) return $this->errorRes(["Veulliez insérer une date"],404);

        $update = DB::update("call update_my_visit('$user->user_id','$accomo->accomodation_id', '$date')");

        return $this->successRes("Vous avez modifié votre rendez-vous de visite");

    }

    public function deleteVisit(Request $request)
    {
        $user = Auth::user(); if(!$user) return $this->errorRes('Unauthorized',401);

        $aid = $request->input('aid'); if(!$aid) return $this->errorRes(["De quel logement s'agit-il ?"],401);

        $accomo = Accomodation::all()->where('accomodation_id','=',$aid)->first(); if(!$accomo) return $this->errorRes(["Ce logement n'existe pas"],404);

        $del = DB::delete("call delete_one_vist($user->user_id, $accomo->accomodation_id)");

        return $this->successRes("La visite a été annulé");
    }

    public function sendMail()
    {
        $name = 'Test';
        $surname = 'tst';
        $data = [
            'name' => $name,
            'surname' => $surname,
            'email' => 'tst@test.ts',
            'msg' => 'This is a message. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'logo' => 'http://localhost:3000/static/media/vmk_v3_1.64902ba3.png'
        ];
/**/
        Mail::send('email.contact', $data, function ($msg) use ($name, $surname) {
            $msg->to('he201342@students.ephec.be')->subject('Message de '.$name.' '.$surname);
        });

        return $this->successRes('e-mail envoyé');

//        return view('email.contact',$data);
    }

    public function visitIsConf()
    {
        $subject = 'Demande confirmé';
        $data = [
            'headTitle' => $subject,
            'title' => 'Votre demande de visite a été approuvé',
            'dateVisit' => '2019-07-04 12:00:00',
            'visiter' => 'Alice Visiteur',
            'owner' => 'Bob Annonceur',
            'address' => '12/101 Rue René Magritte, Louvain-La-Neuve'
        ];

        return view('email.visitIsConf', $data);
    }

    public function visitIsComing()
    {
        $subject = 'Visite en approche';
        $data = [
            'headTitle' => $subject,
            'title' => 'Une visite approche',
            'names' => 'xxx yyy',
            'dateVisit' => '2019-07-04 12:00:00',
            'address' => '12/101 Rue René Magritte, Louvain-La-Neuve'
        ];

        return view('email.visitIsComing', $data);
    }

    public function visitIsEdited()
    {
        $subject = 'Modification de date de visite';
        $data = [
            'headTitle' => $subject,
            'title' => 'Modification de date de visite',
            'visiter' => 'Bob Visiteur',
            'owner' => 'Alice Bailleur',
            'accomodation' => 'Studio avec piscine',
        ];

        return view('email.visitIsEdited', $data);
    }

    public function feedbackVisit()
    {
        $subject = 'Feedback de la visite';

        $data = [
            'headTitle' => $subject,
            'title' => 'Feedback de la visite',
            'visiter' => 'Bob Visiteur',
            'accomodation' => 'Studio avec piscine'
        ];

        return view('email.feedbackVisit',$data);
    }

    public function like_accomodation(Request $request)
    {

    }

}