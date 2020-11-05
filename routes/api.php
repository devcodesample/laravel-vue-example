<?php


use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

//Route::middleware(['auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::group(['prefix' => 'v1',['middleware'=>['cors','checkHeader']]], function() {
    /**
     * Therapist Routes
     */
        Route::group(['namespace' => 'Therapist'], function() {

        Route::post('user/forgot/password', 'TherapistApiController@forgot_password');
        Route::post('user/reset/password', 'TherapistApiController@resetPassword');
        Route::post('/user/login/', 'TherapistApiController@login');
        Route::post('user/register/', 'TherapistApiController@register');
        Route::post('user/update/profile/pic/', 'TherapistApiController@updateProfilePic');
        Route::post('user/update/licence/pic/', 'TherapistApiController@updateLicencePic');
        Route::post('user/update/video/message/', 'TherapistApiController@uploadVideoMessage');
        Route::post('/user/create/profile/', 'TherapistApiController@create');
        Route::get('/users', 'TherapistApiController@index');
        Route::get('/users/random', 'TherapistApiController@randomTherapistProfiles');
        Route::get('/users/for/map/', 'TherapistApiController@googleMapTherapiests');
        Route::post('/user/', 'TherapistApiController@show');
    
        Route::post('/user/by/id/', 'TherapistApiController@showbyUid');
        Route::get('/user/search', 'TherapistApiController@customUserSearch');
        Route::get('/user/search/v2', 'TherapistApiController@customUserSearchv2');
        Route::get('/user/clientside/search', 'TherapistApiController@clientsideSearch');
        Route::get('therapy/type', 'TherapistApiController@getTherapyType'); 
        Route::get('therapy/type/full', 'TherapistApiController@getTherapyTypeFull');
  Route::post('/user/by/slug/', 'TherapistApiController@showbySlug');
    Route::get('user/therapist/locations','TherapistApiController@getTherapiestLocation');
        
 Route::post('/user/purge/tech/issues', 'TherapistApiController@purgeTherapistTechniquesAndIssues');

	
    });
   /**
     * Provider Type
     */
Route::group(['namespace' => 'ProviderType'], function() {
        Route::get('/providers', 'ProviderTypeApiController@index');
});
    /**
     * Insurance Routes
     */
    Route::group(['namespace' => 'Insurance'], function() {
        Route::get('/insurance', 'InsuranceApiController@index');
    });
/**
*Contact Us
*/
Route::group(['namespace' => 'Contact'], function() {
Route::post('/contact/send/mail', 'ContactUsApiController@sendContactUsMail');
Route::post('/rebekka/goodheart/send/mail','RebekkahGoodheartApiController@sendRebekkaGoodHeartMail');
});

/**
 *Version 2 API ROUTES
 */
Route::group(['prefix' => 'v2','middleware'=>'cors'], function() {
Route::group(['namespace' => 'Therapist'], function() {
Route::post('/user/login', 'TherapistApiController@loginv2');
Route::group(['middleware'=>'jwt.auth'], function() {
Route::get('/user/profile','TherapistApiController@profilev2');
});
});
});
