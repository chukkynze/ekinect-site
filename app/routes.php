<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
// System Routes
App::missing(function($exception){
    return Response::make("Page not found", 404);
});
App::missing(function($exception){
    return Response::make("Page not found", 500);
});


// Custom Error Pages
Route::get('/there-was-a-problem/{errorNumber}',         array('as' =>  'custom-error',     'uses'  =>  'HomeController@processErrors',));


// Outside Paywall Routes - Core Landing Pages
Route::group(array('prefix' => '/'), function()
{
    Route::get('/',             'HomeController@showHome');
    Route::get('/terms',        'HomeController@showTerms');
    Route::get('/privacy',      'HomeController@showPrivacy');
});



// Entering Paywall Routes - Access Authorization
Route::group(array('prefix' => '/'), function()
{
    Route::get('/login',                                                    array('as' =>  'login',                                 'uses'  =>  'AuthController@showAccess',                        ));
    Route::post('/login',                                                   array('as' =>  'processLogin',                          'uses'  =>  'AuthController@processLogin',                      ));
    Route::get('/login-again',                                              array('as' =>  'loginAgain',                            'uses'  =>  'AuthController@loginAgain',                        ));
    Route::get('/you-have-successfully-logged-out',                         array('as' =>  'successfulLogout',                      'uses'  =>  'AuthController@successfulLogout',                  ));
    Route::get('/you-have-successfully-changed-your-access-credentials',    array('as' =>  'successfulAccessCredentialChange',      'uses'  =>  'AuthController@successfulAccessCredentialChange',  ));
    Route::get('/login-captcha',                                            array('as' =>  'loginCaptcha',                          'uses'  =>  'AuthController@loginCaptcha',                      ));
    Route::get('/member-logout',                                            array('as' =>  'memberLogout',                          'uses'  =>  'AuthController@memberLogout',                      ));
    Route::get('/member-logout-expired-session',                            array('as' =>  'memberLogoutExpiredSession',            'uses'  =>  'AuthController@memberLogoutExpiredSession',        ));
    Route::get('/signup',                                                   array('as' =>  'signup',                                'uses'  =>  'AuthController@signup',                            ));
    Route::post('/signup',                                                  array('as' =>  'processSignup',                         'uses'  =>  'AuthController@processSignup',                     'before' => 'csrf',));
    Route::get('/vendor-signup',                                            array('as' =>  'vendorSignup',                          'uses'  =>  'AuthController@vendorSignup',                      ));
    Route::get('/freelancer-signup',                                        array('as' =>  'freelancerSignup',                      'uses'  =>  'AuthController@freelancerSignup',                  ));
    Route::get('/forgot',                                                   array('as' =>  'forgot',                                'uses'  =>  'AuthController@forgot',                            ));
    Route::post('/forgot',                                                  array('as' =>  'processForgotPassword',                 'uses'  =>  'AuthController@processForgotPassword',             'before' => 'csrf',));
    Route::get('/reset-password',                                           array('as' =>  'resetPassword',                         'uses'  =>  'AuthController@resetPassword',                     ));
    Route::get('/password-change',                                          array('as' =>  'changePasswordWithOldPassword',         'uses'  =>  'AuthController@changePasswordWithOldPassword',     ));
    Route::post('/verification-details',                                    array('as' =>  'processVerificationDetails',            'uses'  =>  'AuthController@processVerificationDetails',        ));
    Route::get('/resend-signup-confirmation',                               array('as' =>  'resendSignupConfirmation',              'uses'  =>  'AuthController@resendSignupConfirmation',          ));
    Route::post('/resend-signup-confirmation',                              array('as' =>  'processResendSignupConfirmation',       'uses'  =>  'AuthController@processResendSignupConfirmation',   ));
    Route::get('/email-verification/{vcode}',                               array('as' =>  'verifyEmail',                           'uses'  =>  'AuthController@verifyEmail',                       ));
    Route::get('/change-password-verification/{vcode}',                     array('as' =>  'changePasswordWithVerifyEmailLink',     'uses'  =>  'AuthController@changePasswordWithVerifyEmailLink', ));
});




// Admin
Route::group(array('prefix' => 'admin'), function()
{
    // Outside Paywall Routes
    Route::get('/login',                array('as' =>  'adminlogin',                    'uses'  =>  'AdminAuthController@showAccess',           ));

    // Behind Paywall Routes
    Route::get('dashboard',             array('as' =>  'showEmployeeDashboard',         'uses'  =>  'AdminController@showDashboard',            'before' => 'auth', ));

});

// Vendor
Route::group(array('prefix' => 'vendor', 'before' => 'auth',), function()
{
    // Behind Paywall Routes
    Route::get('dashboard',             array('as' =>  'showVendorDashboard',           'uses'  =>  'VendorController@showDashboard',            ));
});

// Vendor Clients
Route::group(array('prefix' => 'vendor-client'), function()
{
    // Behind Paywall Routes
    Route::get('dashboard',             array('as' =>  'showVendorClientDashboard',     'uses'  =>  'VendorClientController@showDashboard',     'before' => 'auth', ));
});

// Freelancer Dashboard
Route::group(array('prefix' => 'freelancer'), function()
{
    // Behind Paywall Routes
    Route::get('dashboard',             array('as' =>  'showFreelancerDashboard',       'uses'  =>  'FreelancerController@showDashboard',       'before' => 'auth', ));
});











// API Routes - No Auth
Route::group(array('prefix' => 'api'), function()
{
    Route::group(array('prefix' => 'vendor'), function()
    {
        Route::get('user', function()
        {
            //
        });

    });

    Route::group(array('prefix' => 'vendors'), function()
    {
        Route::get('user', function()
        {
            //
        });

    });

    Route::group(array('prefix' => 'freelancer'), function()
    {
        Route::get('user', function()
        {
            //
        });

    });

    Route::group(array('prefix' => 'freelancers'), function()
    {
        Route::get('user', function()
        {
            //
        });

    });

});


// API Routes - With Auth
Route::group(array('prefix' => 'api'), function()
{
    Route::group(array('prefix' => 'admin'), function()
    {
        Route::get('user', function()
        {
            //
        });

    });

    Route::group(array('prefix' => 'admin'), function()
    {
        Route::get('user', function()
        {
            //
        });

    });

});