<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('index');
});



Route::post('update/privacy', function() {
    return var_dump($_POST);
});

    Route::get('/kontakt', function () {
        return view('contact');
    });


// Test Env for frontend jquery
/*Route::get('ct', function() {
    return view('test.frontend');
});*/

/*
|--------------------------------------------------------------------------
| Contact Details
|--------------------------------------------------------------------------
|
| All the functionality of contact section
*/
// user input
Route::post('update/input', [
    'before'  => 'csrf',
    'as'      => 'userinput',
    'uses'    => 'InputController@update'
]);

// Contact Certificate Upload
Route::post('/contact/certificate', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'uploadcertificate',
    'uses'    => 'InputController@uploadfilestore'
]);

//Contact Certificate Listout
Route::get('/contact/certificate/file', ['middleware' => 'auth', 'uses' => 'InputController@loadcertificatefile']);

// Contact Certificate Delete
Route::post('/certificate/file/delete', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'deletecertificate',
    'uses'    => 'InputController@deletecertificatefile'
]);

// Contact Certificate download
Route::get('/certificate/download/', [
    'middleware' => 'auth',
    'uses'    => 'InputController@downloadcertificate'
]);

/*
|--------------------------------------------------------------------------
| Authentication routes
|--------------------------------------------------------------------------
|
| All the functionality of auth
*/
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@showResetForm');
Route::post('password/reset', 'Auth\PasswordController@postReset');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
|
| Remainder message for publish profile for public after registration
*/
Route::group(['middleware' => 'auth'], function () {
    Route::get('/edit', 'PublishprofileController@index');
    Route::get('{alias}/edit', 'PublishprofileController@show');
});


/*
|--------------------------------------------------------------------------
| Tag Interest
|--------------------------------------------------------------------------
|
| All the functionality of tag interest
*/
// Tag Interest Listout
Route::get('/tag/interest', ['middleware' => 'auth', 'uses' => 'TagController@index']);

// Tag Interest Insert
Route::post('/interest/update', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'interestupdate',
    'uses'    => 'TagController@store'
]);

// Tag Interest Delete
Route::post('/interest/delete', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'interestdelete',
    'uses'    => 'TagController@destroy'
]);


/*
|--------------------------------------------------------------------------
| Tag Language
|--------------------------------------------------------------------------
|
| All the functionality of tag language
*/
// Tag Languages Listout
Route::get('/tag/language', ['middleware' => 'auth', 'uses' => 'TaglanguageController@index']);

// Tag Languages Insert
Route::post('/language/insert', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'taglanguageinsert',
    'uses'    => 'TaglanguageController@store'
]);

// Tag Languages Update
Route::post('/language/update', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'taglanguageupdate',
    'uses'    => 'TaglanguageController@update'
]);

// Tag Languages Delete
Route::post('/language/delete', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'taglanguagedelete',
    'uses'    => 'TaglanguageController@destroy'
]);

// Tag Languages Rankings Listout
Route::get('/language/ranking/get', [
    'middleware' => 'auth',
    'uses'    => 'TaglanguageController@show'
]);

/*
|--------------------------------------------------------------------------
| Profile Image
|--------------------------------------------------------------------------
|
| All the functionality of profile image
*/
// Upload profile image
Route::post('/uploadProfile/image',[
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'uploadprofileimage',
    'uses'    => 'ProfileimageController@store']);

// View profile image
Route::get('/viewProfile/image', [
    'middleware' => 'auth',
    'as' => 'viewprofileimage',
    'uses' => 'ProfileimageController@show']);

// Delete profile image
Route::post('/deleteProfile/image',[
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'deleteprofileimage',
    'uses'    => 'ProfileimageController@destroy']);

// Get profile image details
Route::get('/getDetails', [
    'middleware' => 'auth',
    'as' => 'getimagedetails',
    'uses' => 'ProfileimageController@index']);


/*
|--------------------------------------------------------------------------
| Tag Profession
|--------------------------------------------------------------------------
|
| All the functionality of tag Profession
*/
// Tag Profession Listout
Route::get('/tag/ambition', ['middleware' => 'auth', 'uses' => 'TagambitionController@index']);

// Tag Profession Insert
Route::post('/ambition/insert', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'tagambitioninsert',
    'uses'    => 'TagambitionController@store'
]);

// Tag Profession Delete
Route::post('/ambition/delete', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'tagambitiondelete',
    'uses'    => 'TagambitionController@destroy'
]);

// Tag Profession Update
Route::post('/ambition/update', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'tagambitionupdate',
    'uses'    => 'TagambitionController@update'
]);

// Tag Profession Files Listout name
Route::get('/profession/file', [
    'middleware' => 'auth',
    'uses'    => 'TagambitionController@show'
]);

// Tag Profession Files download
Route::get('/profession/download/{id}', [
    'uses'    => 'TagambitionController@download'
]);

//Tag profession Files Delete
Route::get('/profession/remove/file', [
    'uses'    => 'TagambitionController@destroyFileOnly'
]);


/*
|--------------------------------------------------------------------------
| Convert profile page to PDF
|--------------------------------------------------------------------------
|
| Using barryvdh/laravel-snappy
*/

Route::post('/{alias}/laravelsnappy/{id}/{idd}', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'profiletopdf',
    'uses'    => 'ProfiletopdfController@index'
]);

/*
|--------------------------------------------------------------------------
| Export
|--------------------------------------------------------------------------
|
| This page is used to PDF export
*/
Route::get('/{alias}/export/{id}/{idd}', ['uses' => 'ExportuserprofileController@show']);

/*
|--------------------------------------------------------------------------
| Confirm profile is available for public
|--------------------------------------------------------------------------
|
| All the functions of profile publish
*/

// Send email for publish profile
Route::get('email/publishprofile/{userID}', 'PublishprofileController@store');

/*
|--------------------------------------------------------------------------
| Share Profile for other users
|--------------------------------------------------------------------------
|
| All the functions of share profile
*/

// share the details of user
Route::get('/{alias}', 'ShareuserprofileController@show');

/*
|--------------------------------------------------------------------------
| Graph Ranking
|--------------------------------------------------------------------------
|
| All the functions of graph
*/

// Graph ranking insert
Route::post('/graph', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'tagambitionupdate',
    'uses'    => 'GraphController@store'
]);

//Graph ranking select - not in use
Route::get('/graph/ranking', ['middleware' => 'auth', 'uses' => 'GraphController@index']);

/*
|--------------------------------------------------------------------------
| Recording Profile Video
|--------------------------------------------------------------------------
|
| All the functions of recording video
*/

// Saving video files
Route::post('/recording/video', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'recordingvideo',
    'uses'    => 'RecordingvideoController@store'
]);

//View profile video
Route::get('/profile/video', ['middleware' => 'auth', 'uses' => 'RecordingvideoController@index']);

//Delete video file
Route::post('/video/delete', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'recordingvideo',
    'uses'    => 'RecordingvideoController@destroy'
]);

/*
|--------------------------------------------------------------------------
| Ability Test
|--------------------------------------------------------------------------
|
| All the functions of ability test
*/

//View questions of different categories
Route::post('/abilitytest', [
    'middleware' => 'auth',
    'as' => 'abilitytest',
    'uses' => 'AbilitytestController@index'
]);

//Ajax for pagination click
Route::get('/pagination/abilitytest', [
    'middleware' => 'auth',
    'as' => 'ajaxabilitytest',
    'uses' => 'AbilitytestController@paginationindex'
]);

//Save answer of abilitytest
Route::post('/abilityanswer', [
    'middleware' => 'auth',
    'as' => 'abilityanswer',
    'uses' => 'AbilitytestController@store'
]);

//Save answer of paginated abilitytest
Route::post('/pagination/abilityanswer', [
    'middleware' => 'auth',
    'as' => 'paginationabilityanswer',
    'uses' => 'AbilitytestController@paginationstore'
]);

/*
|--------------------------------------------------------------------------
| Backend
|--------------------------------------------------------------------------
|
| All the functions of backend
*/

//View backend page
Route::get('/backend/dashboard', function () {
    return view('backend.index');
});

//List each tags
Route::get('/backend/tags', 'BackendControllers\GettagsController@show');

//Suggestions of each tags update using toggle method
Route::get('/toggle/update', 'BackendControllers\GettagsController@update');










//Privacy settings
Route::post('/profile/privacy', ['middleware' => 'auth', 'uses' => 'PrivacyController@saveSettings']);

//// Experience/Company input
Route::post('/experience/update', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'userexperience',
    'uses'    => 'UserexperienceController@update'
]);

Route::post('experience/delete', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'deleteexperience',
    'uses'    => 'UserexperienceController@delete'
]);


Route::get('/experience/getDetails', [
    'middleware' => 'auth',
    'as' => 'getexperience',
    'uses' => 'UserexperienceController@getExperience'
]);

//experience auto complete
Route::get('/experience/getoptions', ['middleware' => 'auth', 'uses' => 'UserexperienceController@getSuggestion']);
Route::post('/experience/savecity', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'savecity',
    'uses'    => 'UserexperienceController@saveCity'
]);
//contact-city inputs - google places api
Route::post('update/cityinput', [
    'before'  => 'csrf',
    'as'      => 'usercity',
    'uses'    => 'InputController@updateCityInputs'
]);
//#SEARCH - city update
Route::post('/usersearch/savecity', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'savecity',
    'uses'    => 'SearchcityController@saveCity'
]);
//delete #SEARCH CITY
Route::post('/usersearch/delete', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'deletesearchcity',
    'uses'    => 'SearchcityController@delete'
]);
//public share profile image
Route::get('/shareProfile/image/{alias}', [
    'as' => 'showprofileimage',
    'uses' => 'ShareuserprofileController@showProfileImage']);

//profession get graduation, Experience
Route::get('/profession/graduation', ['middleware' => 'auth', 'uses' => 'ProfessionController@listGraduation']);
Route::get('/profession/experience', ['middleware' => 'auth', 'uses' => 'ProfessionController@listExperience']);
Route::get('/profession/getlist', ['middleware' => 'auth', 'uses'    => 'ProfessionController@show']);

Route::post('/profession/insert', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'uses'    => 'ProfessionController@store'
]);

Route::post('/profession/delete', [
    'middleware' => 'auth',
    'before'  => 'csrf',
    'as'      => 'professiondelete',
    'uses'    => 'ProfessionController@destroy'
]);
//delete ability test
Route::post('/abilitytest/delete', [
    'middleware' => 'auth',
    'as' => 'deletetest',
    'uses' => 'AbilitytestController@destroy'
]);
//get graph data
Route::get('/profile/graph', ['middleware' => 'auth', 'uses' => 'GraphController@graphData']);

//Download certificated from exported pdf
Route::get('/profile/certificate/{alias}/{hashstring}', [
    'uses'    => 'ExportuserprofileController@downloadCertificate'
]);
