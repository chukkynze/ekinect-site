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
Route::get('/there-was-a-problem/{errorNumber}',    array('as' =>  'custom-error',             'uses'  =>  'HomeController@processErrors',));
Route::get('/there-was-a-problem/24',               array('as' =>  'access-temp-disabled',     'uses'  =>  'HomeController@processErrors',));


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
	Route::get('/admin/login',                                              array('as' =>  'showAdminLogin',                        'uses'  =>  'EmployeeAuthenticationController@showLogin',                            ));
	Route::post('/admin/login',                                             array('as' =>  'postAdminLogin',                        'uses'  =>  'EmployeeAuthenticationController@postLogin',                             'before' => 'csrf',));
    Route::get('/employee-logout',                                          array('as' =>  'employeeLogout',                        'uses'  =>  'EmployeeAuthenticationController@employeeLogout',                              ));


    Route::get('/login',                                                    array('as' =>  'login',                                 'uses'  =>  'CustomerAuthenticationController@showAccess',                                ));
    Route::post('/login',                                                   array('as' =>  'postLogin',                             'uses'  =>  'CustomerAuthenticationController@postLogin',                             'before' => 'csrf',));
    Route::get('/login-again',                                              array('as' =>  'loginAgain',                            'uses'  =>  'CustomerAuthenticationController@loginAgain',                                ));
    Route::get('/you-have-successfully-logged-out',                         array('as' =>  'successfulLogout',                      'uses'  =>  'CustomerAuthenticationController@successfulLogout',                          ));
    Route::get('/you-have-successfully-changed-your-access-credentials',    array('as' =>  'successfulAccessCredentialChange',      'uses'  =>  'CustomerAuthenticationController@successfulAccessCredentialChange',          ));
    Route::get('/login-captcha',                                            array('as' =>  'loginCaptcha',                          'uses'  =>  'CustomerAuthenticationController@loginCaptcha',                              ));
    Route::get('/customer-logout',                                          array('as' =>  'customerLogout',                        'uses'  =>  'CustomerAuthenticationController@customerLogout',                              ));
    Route::get('/member-logout-expired-session',                            array('as' =>  'memberLogoutExpiredSession',            'uses'  =>  'CustomerAuthenticationController@memberLogoutExpiredSession',                ));
    Route::get('/signup',                                                   array('as' =>  'signup',                                'uses'  =>  'CustomerAuthenticationController@signup',                                    ));
    Route::post('/signup',                                                  array('as' =>  'postSignup',                            'uses'  =>  'CustomerAuthenticationController@postSignup',                             'before' => 'csrf',));
    Route::get('/vendor-signup',                                            array('as' =>  'vendorSignup',                          'uses'  =>  'CustomerAuthenticationController@vendorSignup',                              ));
    Route::get('/freelancer-signup',                                        array('as' =>  'freelancerSignup',                      'uses'  =>  'CustomerAuthenticationController@freelancerSignup',                          ));
    Route::get('/forgot',                                                   array('as' =>  'forgot',                                'uses'  =>  'CustomerAuthenticationController@forgot',                                    ));
    Route::post('/forgot',                                                  array('as' =>  'processForgotPassword',                 'uses'  =>  'CustomerAuthenticationController@processForgotPassword',                     'before' => 'csrf',));
    Route::get('/reset-password',                                           array('as' =>  'resetPassword',                         'uses'  =>  'CustomerAuthenticationController@resetPassword',                             ));
    Route::get('/password-change',                                          array('as' =>  'changePasswordWithOldPassword',         'uses'  =>  'CustomerAuthenticationController@changePasswordWithOldPassword',             ));
    Route::post('/verification-details',                                    array('as' =>  'processVerificationDetails',            'uses'  =>  'CustomerAuthenticationController@processVerificationDetails',                'before' => 'csrf',));
    Route::get('/resend-signup-confirmation',                               array('as' =>  'resendSignupConfirmation',              'uses'  =>  'CustomerAuthenticationController@resendSignupConfirmation',                  ));
    Route::post('/resend-signup-confirmation',                              array('as' =>  'processResendSignupConfirmation',       'uses'  =>  'CustomerAuthenticationController@processResendSignupConfirmation',           'before' => 'csrf',));
    Route::get('/email-verification/{vcode}',                               array('as' =>  'verifyEmail',                           'uses'  =>  'CustomerAuthenticationController@verifyEmail',                               ));
    Route::get('/change-password-verification/{vcode}',                     array('as' =>  'showChangePasswordWithVerifyEmailLink', 'uses'  =>  'CustomerAuthenticationController@showChangePasswordWithVerifyEmailLink',     ));
    Route::post('/change-password-verification/{vcode}',                    array('as' =>  'postChangePasswordWithVerifyEmailLink', 'uses'  =>  'CustomerAuthenticationController@postChangePasswordWithVerifyEmailLink',     'before' => 'csrf',));
});




// Admin
Route::group(array('prefix' => 'admin', 'before' => 'auth'), function()
{
	// SuperUser
	Route::group(array('prefix' => 'superuser', 'before' => 'auth'), function()
	{
		Route::get('home',                  array('as' =>  'showSuperUserDashboard',                'uses'  =>  'SuperUserEmployeeController@showDashboard',        ));
	    Route::get('dashboard',             array('as' =>  'showSuperUserDashboard',                'uses'  =>  'SuperUserEmployeeController@showDashboard',        ));
	    Route::get('logout',                array('as' =>  'superUserLogout',                       'uses'  =>  'SuperUserEmployeeController@superUserLogout',      ));
	});

	// Executive
	Route::group(array('prefix' => 'executive', 'before' => 'auth'), function()
	{
		Route::get('home',                  array('as' =>  'showExecutiveDashboard',                'uses'  =>  'ExecutiveEmployeeController@showDashboard',        ));
	    Route::get('dashboard',             array('as' =>  'showExecutiveDashboard',                'uses'  =>  'ExecutiveEmployeeController@showDashboard',        ));
	});

	// Financial
	Route::group(array('prefix' => 'finance', 'before' => 'auth'), function()
	{
		Route::get('home',                  array('as' =>  'showFinancialDashboard',                'uses'  =>  'FinanceEmployeeController@showDashboard',        ));
	    Route::get('dashboard',             array('as' =>  'showFinancialDashboard',                'uses'  =>  'FinanceEmployeeController@showDashboard',        ));
	});

	// Tech
	Route::group(array('prefix' => 'tech', 'before' => 'auth'), function()
	{
		Route::get('home',                  array('as' =>  'showTechDashboard',                     'uses'  =>  'TechEmployeeController@showDashboard',        ));
	    Route::get('dashboard',             array('as' =>  'showTechDashboard',                     'uses'  =>  'TechEmployeeController@showDashboard',        ));
	});
});

// Vendor
Route::group(array('prefix' => 'vendor', 'before' => 'auth',), function()
{
    Route::get('home',                 array('as' =>  'showVendorDashboard',                    'uses'  =>  'VendorController@showDashboard',                           ));
    Route::get('dashboard',            array('as' =>  'showVendorDashboard',                    'uses'  =>  'VendorController@showDashboard',                           ));
    Route::get('logout',               array('as' =>  'vendorLogout',                           'uses'  =>  'VendorController@vendorLogout',                            ));
    Route::get('change-password',      array('as' =>  'showChangePasswordWithOldPassword',      'uses'  =>  'VendorController@showChangePasswordWithOldPassword',       ));
    Route::post('change-password',     array('as' =>  'postChangePasswordWithOldPassword',      'uses'  =>  'VendorController@postChangePasswordWithOldPassword',       ));
});

// Vendor Clients
Route::group(array('prefix' => 'vendor-client', 'before' => 'auth'), function()
{
    Route::get('dashboard',             array('as' =>  'showVendorClientDashboard',             'uses'  =>  'VendorClientController@showDashboard',                     ));
    Route::get('logout',                array('as' =>  'vendorClientLogout',                    'uses'  =>  'VendorClientController@vendorClientLogout',                ));
});

// Freelancer Dashboard
Route::group(array('prefix' => 'freelancer', 'before' => 'auth'), function()
{
    Route::get('home',                  array('as' =>  'showFreelancerDashboard',               'uses'  =>  'FreelancerController@showDashboard',                       ));
    Route::get('dashboard',             array('as' =>  'showFreelancerDashboard',               'uses'  =>  'FreelancerController@showDashboard',                       ));
    Route::get('logout',                array('as' =>  'freelancerLogout',                      'uses'  =>  'FreelancerController@freelancerLogout',                    ));
    Route::get('change-password',       array('as' =>  'showChangePasswordWithOldPassword',     'uses'  =>  'FreelancerController@showChangePasswordWithOldPassword',   ));
    Route::post('change-password',      array('as' =>  'postChangePasswordWithOldPassword',     'uses'  =>  'FreelancerController@postChangePasswordWithOldPassword',   ));
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