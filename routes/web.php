<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// AUTHENTICATION
$router->post('/signin', 'AuthController@signIn');
$router->get('/signout', 'AuthController@signOut');
$router->post('/signup', 'AuthController@signUp');
$router->get('/activateaccount/{uid}', 'AuthController@activateAccount');

// USER
$router->post('/resetpwd', 'UserController@updatePassword');
$router->post('/resetinfo', 'UserController@updateInfo');

// ACCOMODATIONS
$router->get('/accomodations/{qty}', 'AccomodationController@getAccomodations');
$router->get('/advertisments', 'AccomodationController@getAdvertisments');
$router->get('/types', 'AccomodationController@getTypes');
$router->get('/cities', 'AccomodationController@getCities');
$router->get('/advertisments/{id}', 'AccomodationController@getOneAdvertisment');
$router->post('/addadvertisments', 'AccomodationController@addOneAdvertisment');
$router->post('/updateaccomodation', 'AccomodationController@editAd');
$router->post('/rentaccomodation', 'AccomodationController@rentAccomodation');
$router->post('/freeaccomodation', 'AccomodationController@freeAccomodation');

// VISITS
$router->post('/visitaccomodation', 'AccomodationController@visitAccomodation');
$router->get('/getvisits', 'AccomodationController@getVisits');
$router->get('/getvisitors/{id}', 'AccomodationController@getVisitors');
$router->get('/getvisitdates/{id}', 'AccomodationController@getVisitDatesOfOneAccomodation');
$router->post('/updatedatevisit', 'AccomodationController@updateVisits');
$router->post('/adddatevisit', 'AccomodationController@addVisitDate');
$router->post('/updatemyvisit', 'AccomodationController@updateMyVisit');
$router->post('/deletevisit', 'AccomodationController@deleteVisit');
$router->post('/confvisit', 'AccomodationController@confVisit');
$router->post('/refusevisit', 'AccomodationController@refuseVisit');

// ADMINISTRATION
$router->group(['middleware' => 'admin'], function () use ($router) {
    $router->get('/users','AdminController@getUsers');
    $router->get('/profiles','AdminController@getProfiles');
    $router->post('/removeuser','AdminController@removeAccess');
    $router->post('/signoutauser','AdminController@signOut');
});

// TEST
$router->post('/uploadimages', 'AccomodationController@uploadImage');
$router->get('/getimage', 'AccomodationController@getImage');
$router->get('/mail', 'AccomodationController@sendMail');
$router->get('/visitisconf', 'AccomodationController@visitIsConf');
$router->get('/visitiscoming', 'AccomodationController@visitIsComing');
$router->get('/visitisedited', 'AccomodationController@visitIsEdited');
$router->get('/confsignup', 'AuthController@confSignUp');
$router->post('/pwdlost', 'AuthController@pwdLost');
$router->get('/feedbackvisit', 'AccomodationController@feedbackVisit');
