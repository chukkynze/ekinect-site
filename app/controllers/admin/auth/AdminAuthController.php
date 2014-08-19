<?php
 /**
  * Class AdminAuthController
  *
  * filename:   AdminAuthController.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/9/14 8:58 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */

class AdminAuthController extends BaseController
{
    const POLICY_AllowedVerificationSeconds_Signup				=   43200;
	const POLICY_AllowedVerificationSeconds_ChangePassword		=   10800;

	const POLICY_AllowedLoginAttempts       					=   3;
    const POLICY_AllowedLoginCaptchaAttempts    				=   3;
    const POLICY_AllowedSignupAttempts       					=   3;
    const POLICY_AllowedForgotAttempts       					=   3;
    const POLICY_AllowedChangeVerifiedMemberPasswordAttempts 	=   3;
    const POLICY_AllowedChangeOldMemberPasswordAttempts 		=   3;
    const POLICY_AllowedLostSignupVerificationAttempts 			=   3;
    const POLICY_AllowedAttemptsLookBackDuration  				=   'Last1Hour';


    private $activity;
    private $reason;

    public function __construct()
    {
        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit
    }


    public function showAccess()
	{
        $activity   =   ( isset($this->activity)    ?   $this->activity :   'login');
        $reason     =   ( isset($this->reason)      ?   $this->reason   :   '');

        $LoginFormMessages          =   '';
        $LoginCaptchaFormMessages   =   '';
        $SignupFormMessages         =   '';
        $ForgotFormMessages         =   '';

        $LoginAttemptMessages        =   '';
        $LoginCaptchaAttemptMessages =   '';
        $SignupAttemptMessages       =   '';
        $ForgotAttemptMessages       =   '';

        if($activity == 'login')
		{
			switch($reason)
			{
				case 'expired-session' 		:	$LoginHeaderMessage 	=	1; break;
				case 'intentional-logout' 	:	$LoginHeaderMessage 	=	2; break;
				case 'changed-password' 	:	$LoginHeaderMessage 	=	3; break;

				default : $LoginHeaderMessage 	=	0;
			}
		}
		else
		{
			$LoginHeaderMessage     =   '';
		}

        $LoginCaptchaHeaderMessage 	=   '';
        $SignupHeaderMessage       	=   '';
        $ForgotHeaderMessage       	=   '';

        $viewData   =   array
                        (
                            'activity'                  =>  (isset($activeForm) ? $activeForm : $activity),

                            'LoginFormMessages'         =>  $LoginFormMessages,
                            'LoginAttemptMessages'      =>  $LoginAttemptMessages,

                            'SignupFormMessages'        =>  $SignupFormMessages,
                            'ForgotFormMessages'        =>  $ForgotFormMessages,

                            'LoginHeaderMessage'        =>  $LoginHeaderMessage
                        );

		return is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
            ?   Response::make(View::make('admin/auth/login', $viewData))
            :   Response::make(View::make('admin/auth/login', $viewData))->withCookie($this->SiteUserCookie);
	}


    public function logout()
    {
		if ($this->getAuthService()->hasIdentity())
        {
            $this->getAuthService()->clearIdentity();
        }
    }

    public function loginAgain()
    {
        $this->activity     =   "login";
        $this->reason       =   "expired-session";
        return $this->showAccess();
    }

    public function successfulLogout()
    {
        $this->activity     =   "login";
        $this->reason       =   "intentional-logout";
        return $this->showAccess();
    }

    public function successfulAccessCredentialChange()
    {
        $this->activity     =   "login";
        $this->reason       =   "changed-password";
        return $this->showAccess();
    }

    public function loginCaptcha()
    {
        $this->activity     =   "login-captcha";
        $this->reason       =   "";
        return $this->showAccess();
    }

    public function memberLogout()
    {
        $this->logout();

		// return $this->redirect()->toRoute('member-login-after-intentional-logout');
    }

    public function memberLogoutExpiredSession()
    {
        $this->logout();

		// return $this->redirect()->toRoute('member-login-after-expired-session');
    }

    public function signup()
    {
        $this->activity     =   "signup";
        $this->reason       =   "";
        return $this->showAccess();
    }

    public function processSignup()
    {
        $returnToRoute          =   array(
            'name'  =>  FALSE,
            'data'  =>  FALSE,
        );
        $SignupFormMessages     =   array();

        if(Request::isMethod('post'))
        {
            $AccessAttempt      =   new AccessAttempt();
            $AttemptedSignups   =   $AccessAttempt->getAccessAttemptByUserIDs
                                                    (
                                                        'SignupForm',
                                                        array($this->getSiteUser()->id),
                                                        self::POLICY_AllowedAttemptsLookBackDuration
                                                    );

            if($AttemptedSignups['total'] < self::POLICY_AllowedSignupAttempts)
            {
                $SubmittedPostValues    =   Input::all();
                $SubmittedFormName      =   'SignupForm';

                /**
                 * Check for robot entries against dummy variables
                 */
                if($this->isFormClean($SubmittedFormName, $SubmittedPostValues))
                {
                    $formFields     =   array
                                        (
                                            'new_member'                =>  Input::get('new_member'),
                                            'password'                  =>  Input::get('password'),
                                            'password_confirmation '    =>  Input::get('password_confirmation'),
                                            'acceptTerms'               =>  Input::get('acceptTerms'),
                                        );
                    $formRules      =   array
                                        (
                                            'new_member'                =>  array
                                                                            (
                                                                                'required',
                                                                                'email',
                                                                                'unique:member_emails,email_address',
                                                                                'between:5,120',
                                                                            ),
                                            'password'                  =>  array
                                                                            (
                                                                                'required',
                                                                                'between:10,256',
                                                                            ),
                                            'password_confirmation '    =>  array
                                                                            (
                                                                                'same:password',
                                                                            ),
                                            'acceptTerms'               =>  array
                                                                            (
                                                                                'required',
                                                                                'boolean',
                                                                                'accepted',
                                                                            ),
                                        );
                    $formMessages   =   array
                                        (
                                            'new_member.required'   =>  "An email address is required and can not be empty.",
                                            'new_member.email'      =>  "Your email address format is invalid.",
                                            'new_member.unique'     =>  "Please, check your inbox for previous sign up instructions.",
                                            'new_member.between'    =>  "Please, re-check your email address' size.",

                                            'password.required'     =>  "Please enter your password.",
                                            'password.confirmed'    =>  "A password confirmation is required.",
                                            'password.between'      =>  "Passwords must be more than 10 digits. Valid characters only.",

                                            'password_confirmation.same'    =>  "A password confirmation is required.",

                                            'acceptTerms.required'  =>  "Please indicate that you read our Terms & Privacy Policy.",
                                            'acceptTerms.boolean'   =>  "Please, indicate that you read our Terms & Privacy Policy.",
                                            'acceptTerms.accepted'  =>  "Please indicate that you read our Terms & Privacy Policy",
                                        );

                    $validator      =   Validator::make($formFields, $formRules, $formMessages);
                    $passwordCheck  =   $this->checkPasswordStrength($formFields['password']);

                    if ($validator->passes() && $passwordCheck['status'])
                    {
                        // Add the emailAddress
                        $this->addEmailStatus($formFields['new_member'], 'AddedUnverified');

                        // Get the Site User so you can associate this user behaviour with this new member
                        $this->SiteUser =   $this->getSiteUser();

                        // Create a Member Object
                        $NewMemberID    =   $this->addMember($formFields['new_member'], $formFields['password']);

                        if($NewMemberID > 0)
                        {
                            // Update User with Member ID
                            $this->setSiteUserMemberID($this->getSiteUser()->getID(), $NewMemberID);

                            // Create & Save a Member Status Object for the new Member
                            $this->addMemberStatus($NewMemberID, 'Successful-Signup');

                            // Create & Save a Member Emails Object
                            $NewMemberEmailID   =   $this->addMemberEmail($formFields['new_member'], $NewMemberID);

                            if($NewMemberEmailID > 0)
                            {
                                // Prepare an Email for Validation
                                // setup SMTP options
                                $verifyEmailLink    =   $this->generateVerifyEmailLink($formFields['new_member'], $NewMemberID, 'verify-new-member');
                                $sendEmailStatus    =   $this->sendEmail
                                                        (
                                                            'verify-new-member',
                                                            array
                                                            (
                                                                'verifyEmailLink' => $verifyEmailLink
                                                            ),
                                                            array
                                                            (
                                                                'fromTag'       =>  'General',
                                                                'sendToEmail'   =>  $formFields['new_member'],
                                                                'sendToName'    =>  'Welcome to Ekinect',
                                                                'subject'       =>  'Welcome to Ekinect',
                                                                'ccArray'       =>  FALSE,
                                                                'attachArray'   =>  FALSE,
                                                            )
                                                        );

                                if($sendEmailStatus)
                                {
                                    // Update Member emails that verification was sent and at what time for this member
                                    $this->updateMemberEmail($NewMemberEmailID, array
                                    (
                                        'verification_sent'     =>  1,
                                        'verification_sent_on'  =>  strtotime('now'),
                                    ));

                                    // Add the emailAddress status
                                    $this->addEmailStatus($formFields['new_member'], 'VerificationSent');

                                    // Redirect to Successful Signup Page that informs them of the need to validate the email before they can enjoy the free 90 day Premium membership
                                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 1);
                                    $viewData   =   array
                                                    (
                                                        'emailAddress'        =>  $formFields['new_member'],
                                                    );

                                    return  is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
                                        ?   Response::make(View::make('auth/member-signup-success', $viewData))
                                        :   Response::make(View::make('auth/member-signup-success', $viewData))->withCookie($this->SiteUserCookie);
                                }
                                else
                                {
                                    $this->addAdminAlert();
                                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 0);
                                    Log::info($SubmittedFormName . " - Could not send the new member email to [" . $formFields['new_member'] . "] for member id [" . $NewMemberID . "].");
                                    $customerService        =   str_replace("[errorNumber]", "Could not send the new member email.", self::POLICY_LinkCustomerService );
                                    $SignupFormMessages[]   =   "Sorry, we cannot complete the signup process at this time.
                                                                Please refresh, and if the issue continues, contact " . $customerService . ".";
                                }
                            }
                            else
                            {
                                $this->addAdminAlert();
                                $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 0);
                                Log::info($SubmittedFormName . " - Could not create a new member email.");
                                $customerService        =   str_replace("[errorNumber]", "Could not create a new member email.", self::POLICY_LinkCustomerService );
                                $SignupFormMessages[]   =   "Sorry, we cannot complete the signup process at this time.
                                                            Please refresh, and if the issue continues, contact " . $customerService . ".";
                            }
                        }
                        else
                        {
                            $this->addAdminAlert();
                            $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 0);
                            Log::info($SubmittedFormName . " - Could not create a new member.");
                            $customerService        =   str_replace("[errorNumber]", "Could not create a new member.", self::POLICY_LinkCustomerService );
                            $SignupFormMessages[]   =   "Sorry, we cannot complete the signup process at this time.
                                                        Please refresh, and if the issue continues, contact " . $customerService . ".";
                        }
                    }
                    else
                    {
                        $SignupFormErrors   =   $validator->messages()->toArray();
                        $SignupFormMessages =   array();
                        foreach($SignupFormErrors as $errors)
                        {
                            $SignupFormMessages[]   =   $errors[0];
                        }

                        if(array_key_exists('errors', $passwordCheck))
                        {
                            foreach($passwordCheck['errors'] as $errors)
                            {
                                $SignupFormMessages[]   =   $errors;
                            }
                        }

                        $this->registerAccessAttempt($this->getSiteUser()->getID(),$SubmittedFormName, 0);
                        Log::info($SubmittedFormName . " - form values did not pass.");
                    }
                }
                else
                {
                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 0);
                    $this->addAdminAlert();
                    Log::warning($SubmittedFormName . " has invalid dummy variables passed.");
                    $returnToRoute  =   array
                                        (
                                            'name'  =>  'custom-error',
                                            'data'  =>  array('errorNumber' => 23),
                                        );
                }
            }
            else
            {
                $this->applyLock('Locked:Excessive-Signup-Attempts', 'cjunze@gmail.com','excessive-signups', []);
                $returnToRoute  =   array
                                    (
                                        'name'  =>  'custom-error',
                                        'data'  =>  array('errorNumber' => 18),
                                    );
            }
        }
        else
        {
            $returnToRoute  =   array
                                (
                                    'name'  =>  'custom-error',
                                    'data'  =>  array('errorNumber' => 23),
                                );
        }

        if(FALSE != $returnToRoute['name'])
        {
            return Redirect::route($returnToRoute['name'],$returnToRoute['data']);
        }
        else
        {
            $viewData   =   array(
                'activity'                  =>  "signup",

                'LoginAttemptMessages'      =>  '',

                'LoginFormMessages'         =>  '',
                'SignupFormMessages'        =>  (count($SignupFormMessages) >= 1 ? $SignupFormMessages : ''),
                'ForgotFormMessages'        =>  '',

                'LoginHeaderMessage'        =>  ''
            );

            return  is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
                ?   Response::make(View::make('auth/login', $viewData))
                :   Response::make(View::make('auth/login', $viewData))->withCookie($this->SiteUserCookie);
        }
    }

    public function vendorSignup()
    {
        $this->activity     =   "signup";
        $this->reason       =   "";
        return $this->showAccess();
    }

    public function freelancerSignup()
    {
        $this->activity     =   "signup";
        $this->reason       =   "";
        return $this->showAccess();
    }

    public function forgot()
    {
        $this->activity     =   "forgot";
        $this->reason       =   "";
        return $this->showAccess();
    }

    public function resetPassword()
    {
        $this->activity     =   "forgot";
        $this->reason       =   "";
        return $this->showAccess();
    }

    public function changePasswordWithOldPassword()
    {

    }

    public function processVerificationDetails()
    {
        // Please use your info to login to your free trial
        // success needs to be on the landing pages so the login button is right on top
        $returnToRoute                      =   array
                                                (
                                                    'name'  =>  FALSE,
                                                    'data'  =>  FALSE,
                                                );
        $VerificationDetailsFormMessages    =   array();

        if(Request::isMethod('post'))
        {
            // Validate vcode
            $verifiedMemberIDArray  =   $this->verifyEmailByLinkAndGetMemberIDArray(Input::get('vcode'), 'VerificationDetailsForm');

            if (!isset($verifiedMemberIDArray['errorNbr']) && !isset($verifiedMemberIDArray['errorMsg']))
            {
                if (isset($verifiedMemberIDArray) && is_array($verifiedMemberIDArray))
                {
                    // Validate Form
                    $formFields     =   array
                                        (
                                            'first_name'    =>  Input::get('first_name'),
                                            'last_name'     =>  Input::get('last_name'),
                                            'gender'        =>  Input::get('gender'),
                                            'member_type'   =>  Input::get('member_type'),
                                            'zipcode'       =>  Input::get('zipcode'),
                                        );
                    $formRules      =   array
                                        (
                                            'first_name'    =>  array
                                                                (
                                                                    'required',
                                                                    'alpha',
                                                                    'between:2,60',
                                                                ),
                                            'last_name'     =>  array
                                                                (
                                                                    'required',
                                                                    'alpha',
                                                                    'between:2,60',
                                                                ),
                                            'gender'        =>  array
                                                                (
                                                                    'required',
                                                                    'numeric',
                                                                    'digits:1',
                                                                    'min:1',
                                                                    'max:2',
                                                                ),
                                            'member_type'   =>  array
                                                                (
                                                                    'required',
                                                                    'numeric',
                                                                    'digits:1',
                                                                    'min:1',
                                                                    'max:3',
                                                                ),
                                            'zipcode'       =>  array
                                                                (
                                                                    'required',
                                                                    'numeric',
                                                                    'digits:5',
                                                                    #'exists:freelife_utils.location_data,postal_code',
                                                                ),
                                        );
                    $formMessages   =   array
                                        (
                                            'first_name.required'   =>  "Please, enter your first name.",
                                            'first_name.alpha'      =>  "Please, use only the alphabet for your first name.",
                                            'first_name.between'    =>  "Please, re-check the length of your first name.",

                                            'last_name.required'    =>  "Please, enter your last name.",
                                            'last_name.alpha'       =>  "Please, use only the alphabet for your last name.",
                                            'last_name.between'     =>  "Please, re-check the length of your last name.",

                                            'gender.required'       =>  "Please, select your gender.",
                                            'gender.numeric'        =>  "Please, choose a gender.",
                                            'gender.digits'         =>  "Please, choose a gender.",
                                            'gender.min'            =>  "Please, choose a gender.",
                                            'gender.max'            =>  "Please, choose a gender.",

                                            'member_type.required'  =>  "Please, select your Membership Type.",
                                            'member_type.numeric'   =>  "Please, choose a Membership Type.",
                                            'member_type.digits'    =>  "Please, choose a Membership Type.",
                                            'member_type.min'       =>  "Please, choose a Membership Type.",
                                            'member_type.max'       =>  "Please, choose a Membership Type.",

                                            'zipcode.required'      =>  "Please, enter your zipcode.",
                                            'zipcode.numeric'       =>  "Please, use only numbers for your zipcode.",
                                            'zipcode.digits'        =>  "Please, enter a zipcode.",
                                        );

                    $validator      =   Validator::make($formFields, $formRules, $formMessages);

                    if ($validator->passes())
                    {
                        $memberDetailsExist     =   $this->doMemberDetailsExist($verifiedMemberIDArray['memberID']);

                        // Add Member Details
                        $detailsFillableArray   =   array
                                                    (
                                                        'member_id'             =>  $verifiedMemberIDArray['memberID'],
                                                        'first_name'            =>  $formFields['first_name'],
                                                        'last_name'             =>  $formFields['last_name'],
                                                        'gender'                =>  $formFields['gender'],
                                                        'zipcode'               =>  $formFields['zipcode'],
                                                        'personal_summary'      =>  '',
                                                        'profile_pic_url'       =>  '',
                                                        'personal_website_url'  =>  '',
                                                        'linkedin_url'          =>  '',
                                                        'google_plus_url'       =>  '',
                                                        'twitter_url'           =>  '',
                                                        'facebook_url'          =>  '',
                                                    );
                        if($memberDetailsExist)
                        {
                            $this->updateMemberDetails($verifiedMemberIDArray['memberID'], $detailsFillableArray);
                        }
                        else
                        {
                            $this->addMemberDetails($verifiedMemberIDArray['memberID'], $detailsFillableArray);
                        }

                        // Update Member Object with Member Type
                        $memberFillableArray    =   array
                                                    (
                                                        'member_type'   =>  strtolower($formFields['member_type']),
                                                    );
                        $this->updateMember($verifiedMemberIDArray['memberID'], $memberFillableArray);
                        $this->addMemberStatus('VerifiedStartupDetails', $verifiedMemberIDArray['memberID']);
                        $this->addMemberStatus('ValidMember', $verifiedMemberIDArray['memberID']);

                        // Successful Verification Notification Email
                        $this->sendEmail
                        (
                            'genericProfileInformationChange',
                            array
                            (
                                'first_name'    =>  $formFields['first_name'],
                                'last_name'     =>  $formFields['last_name'],
                            ),
                            array
                            (
                                'fromTag'       =>  'General',
                                'sendToEmail'   =>  $verifiedMemberIDArray['email'],
                                'sendToName'    =>  $formFields['first_name'] . ' ' . $formFields['last_name'],
                                'subject'       =>  'Profile Change Notification',
                                'ccArray'       =>  FALSE,
                                'attachArray'   =>  FALSE,
                            )
                        );


                        $viewData   =   array
                                        (
                                            'firstName'     =>  $formFields['first_name'],
                                            'emailAddress'  =>  $verifiedMemberIDArray['email'],
                                        );

                        return  $this->makeResponseView('auth/verification-details-success', $viewData);
                    }
                    else
                    {
                        $VerificationDetailsFormErrors   =   $validator->messages()->toArray();
                        $VerificationDetailsFormMessages =   array();
                        foreach($VerificationDetailsFormErrors as $errors)
                        {
                            $VerificationDetailsFormMessages[]   =   $errors[0];
                        }

                        Log::info("VerificationDetails form values did not pass.");
                    }
                }
                else
                {
                    Log::info("Error #3 - returned value from verifiedMemberIDArray is not an array.");
                    $returnToRoute  =   array
                    (
                        'name'  =>  'custom-error',
                        'data'  =>  array('errorNumber' => 3),
                    );
                }
            }
            else
            {
                Log::info("Error #" . $verifiedMemberIDArray['errorNbr'] . " - " . $verifiedMemberIDArray['errorMsg'] . ".");
                $returnToRoute  =   array
                (
                    'name'  =>  'custom-error',
                    'data'  =>  array('errorNumber' => $verifiedMemberIDArray['errorNbr']),
                );
            }
        }
        else
        {
            $returnToRoute  =   array
                                (
                                    'name'  =>  'custom-error',
                                    'data'  =>  array('errorNumber' => 23),
                                );
        }

        if(FALSE != $returnToRoute['name'])
        {
            return Redirect::route($returnToRoute['name'],$returnToRoute['data']);
        }
        else
        {
            $viewData   =   array
                            (
                                'vcode'         =>  Input::get('vcode'),
                                'firstName'     =>  Input::get('first_name'),
                                'lastName'      =>  Input::get('last_name'),
                                'gender'        =>  Input::get('gender') ?: 0,
                                'memberType'    =>  Input::get('member_type') ?: 0,
                                'zipCode'       =>  Input::get('zipcode'),
                                'VerificationDetailsFormMessages'   => $VerificationDetailsFormMessages,
                            );
            return  $this->makeResponseView('auth/verified_email_success', $viewData);
        }
    }

    public function resendSignupConfirmation()
    {
        // lostSignupVerificationAction
    }

    /**
     * Processes the Verification Details form
     *
     * @param $vCode
     *
     * @return ViewModel
     */
    public function verifyEmail($vCode)
    {
        $returnToRoute          =   array
                                    (
                                        'name'  =>  FALSE,
                                        'data'  =>  FALSE,
                                    );

        /**
         * Must return both email and member id bc a member can have more than one email address
         */
        $verifiedMemberIDArray  =   $this->verifyEmailByLinkAndGetMemberIDArray($vCode, 'VerificationDetailsForm');
        $vCodeCreateTime		=	(is_numeric($verifiedMemberIDArray['vCodeCreateTime'])
                                        ?   (int) $verifiedMemberIDArray['vCodeCreateTime']
                                        :   0);
        $verificationDuration	=	( (strtotime("now") - $vCodeCreateTime) <= self::POLICY_AllowedVerificationSeconds_Signup
                                        ?   TRUE
                                        :   FALSE );

        if($verificationDuration)
        {
            if (!isset($verifiedMemberIDArray['errorNbr']) && !isset($verifiedMemberIDArray['errorMsg']))
            {
                if (isset($verifiedMemberIDArray) && is_array($verifiedMemberIDArray))
                {
                    if ($verifiedMemberIDArray['alreadyVerified'] === 0)
                    {
                        // Create New Member Status for this member identifying as verified and starting trial
                        $this->addMemberStatus('VerifiedEmail', $verifiedMemberIDArray['memberID']);

                        $this->updateMemberEmail($verifiedMemberIDArray['memberID'], array
                        (
                            'verified'     =>  1,
                            'verified_on'  =>  strtotime('now'),
                        ));
                    }

                    $this->addEmailStatus($verifiedMemberIDArray['email'], 'Verified');
                }
                else
                {
                    Log::info("Error #3 - returned value from verifiedMemberIDArray is not an array.");
                    $returnToRoute  =   array
                    (
                        'name'  =>  'custom-error',
                        'data'  =>  array('errorNumber' => 3),
                    );
                }
            }
            else
            {
                Log::info("Error #" . $verifiedMemberIDArray['errorNbr'] . " - " . $verifiedMemberIDArray['errorMsg'] . ".");
                $returnToRoute  =   array
                (
                    'name'  =>  'custom-error',
                    'data'  =>  array('errorNumber' => $verifiedMemberIDArray['errorNbr']),
                );
            }
        }
        else
        {
            Log::info("Error #22 - verification link has expired.");
            $returnToRoute  =   array
            (
                'name'  =>  'custom-error',
                'data'  =>  array('errorNumber' => 22),
            );
        }



        if(FALSE != $returnToRoute['name'])
        {
            return Redirect::route($returnToRoute['name'],$returnToRoute['data']);
        }
        else
        {
            // Create Member Details Form - also force to add name, gender, customer type and zip code and time zone in form
            $viewData   =   array
                            (
                                'vcode'         =>  $vCode,
                                'firstName'     =>  '',
                                'lastName'      =>  '',
                                'gender'        =>  0,
                                'memberType'    =>  0,
                                'zipCode'       =>  '',
                                'VerificationDetailsFormMessages'   => (isset($VerificationDetailsFormMessages) && $VerificationDetailsFormMessages != '' ?: ''),
                            );

            return  is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
                ?   Response::make(View::make('auth/verified_email_success', $viewData))
                :   Response::make(View::make('auth/verified_email_success', $viewData))->withCookie($this->SiteUserCookie);
        }
    }

    public function changePasswordWithVerifyEmailLink($vCode)
    {

    }

	/**
	 * Applies an appropriate lock to a user, ip, and or member and sends an email if necessary and possible
	 *
	 * @param        $lockStatus
	 * @param string $contactEmail
	 * @param string $emailTemplateName
	 * @param array  $emailTemplateOptionsArray
	 */
	public function applyLock($lockStatus, $contactEmail='', $emailTemplateName='', $emailTemplateOptionsArray=array())
	{
	    // Lock user status
		$this->getSiteUser()->setUserStatus($lockStatus, $this->getSiteUser()->getID());

		// Create an IP Block
		$ipBin  =	new IPBin();
        $ipBin->blockIPAddress($this->getSiteUser()->getID(), $lockStatus, $this->getSiteUser()->getMemberID());

		// Lock the user member status by adding a more current member status of $lockStatus
		$this->addMemberStatus($lockStatus, $this->getSiteUser()->getMemberID());

        /**
         * If an email address is passed we want to use it to inform the user/member that they were locked
         */
        $validator  =   Validator::make
                        (
                            array
                            (
                                'email' => $contactEmail
                            ),
                            array
                            (
                                'email' => 'email'
                            )
                        );

        if ($validator->passes())
        {
            // if email is in our database
			if($this->isEmailVerified($contactEmail))
			{
                $MemberEmails       =   new MemberEmails();
                $memberID           =   $MemberEmails->getMemberIDFromEmailAddress($contactEmail);
                $memberPriEmail     =   $MemberEmails->getPrimaryEmailAddressFromMemberID($memberID);
                $MemberDetailsModel =   MemberDetails::where('member_id', '=', $memberID)->first();
                $sendToName         =   ($MemberDetailsModel->first_name != "" && $MemberDetailsModel->last_name != ""
                                            ?   $MemberDetailsModel->first_name . " " . $MemberDetailsModel->last_name
                                            :   "Ekinect Member");

				// Lock the member
				$this->updateMemberStatus($memberID, ['status' => $lockStatus]);

				// Email Options for a Member
                $messageOptionsArray    =   $this->getLockMessageOptions($lockStatus) + ['sendToEmail'   =>  $memberPriEmail, 'sendToName' => $sendToName,];
			}
			else
			{
                // Email Options for a Site User
                $messageOptionsArray    =   $this->getLockMessageOptions($lockStatus) + ['sendToEmail'   =>  $contactEmail, 'sendToName' => 'Ekinect User',];
			}

            $this->sendEmail($emailTemplateName, $emailTemplateOptionsArray, $messageOptionsArray);
        }
	}

    public function getLockMessageOptions($lockStatus)
    {
        switch($lockStatus)
        {
            case 'Locked:Excessive-Login-Attempts'                      :   $messageOptionsArray    =   [
                                                                                'fromTag'       =>  'Customer Service',
                                                                                'subject'       =>  'Profile Change Notification',
                                                                                'ccArray'       =>  FALSE,
                                                                                'attachArray'   =>  FALSE,
                                                                            ];
                                                                            break;

            case 'Locked:Excessive-Signup-Attempts'                     :   $messageOptionsArray    =   [
                                                                                'fromTag'       =>  'Customer Service',
                                                                                'subject'       =>  'Profile Change Notification',
                                                                                'ccArray'       =>  FALSE,
                                                                                'attachArray'   =>  FALSE,
                                                                            ];
                                                                            break;

            case 'Locked:Excessive-ForgotLogin-Attempts'                :   $messageOptionsArray    =   [
                                                                                'fromTag'       =>  'Customer Service',
                                                                                'subject'       =>  'Profile Change Notification',
                                                                                'ccArray'       =>  FALSE,
                                                                                'attachArray'   =>  FALSE,
                                                                            ];
                                                                            break;

            case 'Locked:Excessive-ChangeVerifiedLinkPassword-Attempts' :   $messageOptionsArray    =   [
                                                                                'fromTag'       =>  'Customer Service',
                                                                                'subject'       =>  'Profile Change Notification',
                                                                                'ccArray'       =>  FALSE,
                                                                                'attachArray'   =>  FALSE,
                                                                            ];
                                                                            break;

            case 'Locked:Excessive-ChangeOldPassword-Attempts'          :   $messageOptionsArray    =   [
                                                                                'fromTag'       =>  'Customer Service',
                                                                                'subject'       =>  'Profile Change Notification',
                                                                                'ccArray'       =>  FALSE,
                                                                                'attachArray'   =>  FALSE,
                                                                            ];
                                                                            break;

            case 'Locked:Excessive-LostSignupVerification-Attempts'     :   $messageOptionsArray    =   [
                                                                                'fromTag'       =>  'Customer Service',
                                                                                'subject'       =>  'Profile Change Notification',
                                                                                'ccArray'       =>  FALSE,
                                                                                'attachArray'   =>  FALSE,
                                                                            ];
                                                                            break;


            default : throw new \Exception("Invalid Lock Status during message options retrieval.");
        }

        return $messageOptionsArray;
    }



	/**
	 * Checks if form has been populated by robots
	 *
	 * @param $formName
	 * @param $formValues
	 *
	 * @return bool
	 */
	public function isFormClean($formName, $formValues)
    {
        $returnValue    =   FALSE;

        if(is_array($formValues))
        {
            switch($formName)
            {
                case 'LoginForm'            					:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'SignupForm'     							:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'ForgotForm'     							:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'LoginCaptchaForm'     					:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'ChangePasswordWithVerifyLinkForm'     	:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
                                                					break;

                case 'ChangePasswordWithOldPasswordForm'     	:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;


                default  :   $dummyInput     =	array
												(
													'false'     =>  'FALSE',
												);
            }

            foreach ($dummyInput as $dumbKey => $dumbValue)
            {
                if(array_key_exists($dumbKey, $formValues))
                {
                    if($dummyInput[$dumbKey] != 'FALSE')
                    {
                        if($formValues[$dumbKey] == $dummyInput[$dumbKey])
                        {
                            $returnValue    =   TRUE;
                        }
                        else
                        {
                            Log::info("Form value for dummy input has incorrect value of [" . $formValues[$dumbKey]. "]. It should be [" . $dummyInput[$dumbKey]. "].");
                            $returnValue    =   FALSE;
                        }
                    }
                    else
                    {
                        Log::info("Invalid formName. => dummyInput[" . $dumbValue . "]");
                        $returnValue    =   FALSE;
                    }
                }
                else
                {
                    Log::info("Array key from variable dumbKey (" . $dumbKey . ") does not exist in variable array formValues.");
                    $returnValue    =   FALSE;
                }
            }
        }
        else
        {
            Log::info("Variable formValues is not an array.");
            $returnValue    =   FALSE;
        }

        return $returnValue;
    }

    public function makeResponseView($viewName, $viewData)
    {
        return  is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
                    ?   Response::make(View::make($viewName, $viewData))
                    :   Response::make(View::make($viewName, $viewData))->withCookie($this->SiteUserCookie);
    }


	/**
	 * Stores an access attempt
	 *
	 * @param $userID
	 * @param $accessFormName
	 * @param $attemptBoolean
	 */
	public function registerAccessAttempt($userID, $accessFormName, $attemptBoolean)
    {
        try
        {
             $AccessAttempt  =   new AccessAttempt();
            $AccessAttempt->registerAccessAttempt($userID, $accessFormName, $attemptBoolean);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add a new Access Attempt. " . $e);
        }
    }

    public function addEmailStatus($emailAddress, $status)
    {
        try
        {
            $EmailStatus    =   new EmailStatus();
            $EmailStatus->addEmailStatus($emailAddress, $status);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add a new Email Status. " . $e);
        }
    }

    public function generateLoginCredentials($newMemberEmail, $newMemberPassword)
    {
        $siteSalt           =   $_ENV['ENCRYPTION_KEY_SITE_default_salt'];
        $memberSalt1        =   uniqid(mt_rand(0, 61), true);
        $memberSalt2        =   uniqid(mt_rand(0, 61), true);
        $memberSalt3        =   uniqid(mt_rand(0, 61), true);
        $loginCredentials   =   $this->createHash
                                (
                                    $memberSalt1 . $newMemberEmail . $siteSalt . $newMemberPassword . $memberSalt2,
                                    $siteSalt . $memberSalt3
                                );
        return  array
                (
                    $loginCredentials,
                    $memberSalt1,
                    $memberSalt2,
                    $memberSalt3,
                );
    }

    public function generateMemberLoginCredentials($newMemberEmail, $newMemberPassword, $memberSalt1, $memberSalt2, $memberSalt3)
    {
        $siteSalt           =   $_ENV['ENCRYPTION_KEY_SITE_default_salt'];
        $loginCredentials   =   $this->createHash
                                (
                                     $memberSalt1 . $newMemberEmail . $siteSalt . $newMemberPassword . $memberSalt2,
                                     $siteSalt . $memberSalt3
                                );

        return $loginCredentials;
    }

    public function generateVerifyEmailLink($memberEmail, $memberID, $emailTemplateName )
    {
        $siteSalt           =   $_ENV['ENCRYPTION_KEY_SITE_default_salt'];

        $a                  =   base64_encode($this->twoWayCrypt('e',$memberEmail,$siteSalt));      // email address
        $b                  =   base64_encode($this->createHash($memberID,$siteSalt));              // one-way hashed mid
        $c                  =   base64_encode($this->twoWayCrypt('e',strtotime("now"),$siteSalt));  // vCode creation time
        $addOn              =   str_replace("/", "--::--", $a . self::POLICY_EncryptedURLDelimiter . $b . self::POLICY_EncryptedURLDelimiter . $c);
        $addOn              =   str_replace("+", "--:::--", $addOn);

		switch($emailTemplateName)
		{
			case 'verify-new-member'		:	$router	=	'email-verification';
												break;

			case 'forgot-logins-success'	:	$router	=	'change-password-verification';
												break;

			default : throw new \Exception('Invalid Email route passed (' . $emailTemplateName . '.');
		}
        $verifyEmailLink    =   self::POLICY_CompanyURL_protocol . self::POLICY_CompanyURL_prd . $router . "/" . $addOn;

        return $verifyEmailLink;
    }

    public function verifyEmailByLinkAndGetMemberIDArray($passedVCode, $verificationFormName='')
    {
        $siteSalt           =   $_ENV['ENCRYPTION_KEY_SITE_default_salt'];

        $vCode              =   str_replace("--::--", "/", $passedVCode);
        $vCode              =   str_replace("--:::--", "+", $vCode);
        $getTokens          =   explode(self::POLICY_EncryptedURLDelimiter, $vCode);
        $emailFromVcode     =   $this->twoWayCrypt('d',base64_decode($getTokens[0]),$siteSalt);
        $vCodeCreateTime    =   $this->twoWayCrypt('d',base64_decode($getTokens[2]),$siteSalt);
        $memberIDHash       =   base64_decode($getTokens[1]);

        $memberID           =   $this->getMemberIDFromVerifyLink($emailFromVcode, $memberIDHash);

        if(isset($memberID) && !is_bool($memberID) && $memberID >= 1)
        {
            switch($verificationFormName)
            {
                case 'VerificationDetailsForm'				:	// Check if email from vCode has already been validated and verified (user that clicks the link twice+)
                    $emailIsAlreadyVerified     =   ($this->isEmailVerified($emailFromVcode) ? 1 : 0);
                    break;

                case 'ChangePasswordWithVerifyLinkForm'		:	// Check ... something
                    $emailIsAlreadyVerified     =   1;
                    break;


                default :	throw new \Exception('Invalid verification link form.');
            }


            return  array
            (
                'statusMsg'         =>  '',
                'memberID'          =>  $memberID,
                'email'             =>  $emailFromVcode,
                'vCodeCreateTime'   =>  $vCodeCreateTime,
                'alreadyVerified'   =>  (int) $emailIsAlreadyVerified,
            );
        }
        else
        {
            // custom error
            $errorMsg   =   "Error #1 - MemberEmailsTable->isVerifyLinkValid returned an invalid member id.";
            Log::info($errorMsg);
            return  array
            (
                'errorNbr'  =>  '1',
                'errorMsg'  =>  $errorMsg,
            );
        }
    }

    public function isEmailVerified($email)
    {
        try
        {
            $MemberEmails   =   new MemberEmails();
            return $MemberEmails->isEmailVerified($email);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not verify this email address [" . $email . "]. " . $e);
            return FALSE;
        }
    }

    public function getMemberIDFromVerifyLink($emailAddress, $memberIDHash)
    {
        $MemberEmails               =   new MemberEmails();
        $wasVerificationLinkSent    =   $MemberEmails->wasVerificationLinkSent($emailAddress);

        if($wasVerificationLinkSent)
        {
            $memberID   =   $MemberEmails->getMemberIDFromEmailAddress($emailAddress);
            if($memberID >= 1)
            {
                return ($this->isVerifyLinkValid($memberID, $memberIDHash)
                        ?   $memberID
                        :   FALSE);
            }
            else
            {
                Log::error("Retrieved an invalid member id from this email address.");
                return FALSE;
            }
        }
        else
        {
            Log::error("Verification link was not sent for this email address.");
            return FALSE;
        }
    }

    public function isVerifyLinkValid($memberID, $memberIDHash)
    {
        return  ($memberIDHash === $this->createHash($memberID, $_ENV['ENCRYPTION_KEY_SITE_default_salt'])
                    ?   TRUE
                    :   FALSE);
    }

    public function addMember($newMemberEmail, $newMemberPassword)
    {
        try
        {
            $LoginCredentials   =   $this->generateLoginCredentials($newMemberEmail, $newMemberPassword);
            $NewMember          =   new Member();
            return $NewMember->addMember($LoginCredentials);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add a new Member identified by this email address [" . $newMemberEmail . "]. " . $e);
            return FALSE;
        }
    }

    public function updateMember($memberID, $fillableArray)
    {
        try
        {
            $Member    =   new Member();
            return $Member->updateMember($memberID, $fillableArray);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not update Member ID [" . $memberID . "] - " . $e);
            return FALSE;
        }
    }

    public function addMemberStatus($status, $memberID)
    {
        try
        {
            $NewMemberStatus    =   new MemberStatus();
            return $NewMemberStatus->addMemberStatus($status, $memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add the new Member Status [" . $status . "] for Member [" . $memberID . "]. " . $e);
            return FALSE;
        }
    }

    public function updateMemberStatus($memberID, $fillableArray)
    {
        try
        {
            $MemberStatus    =   new MemberStatus();
            return $MemberStatus->updateMemberStatus($memberID, $fillableArray);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not update MemberStatus ID [" . $memberID . "] - " . $e);
            return FALSE;
        }
    }

    public function doMemberDetailsExist($memberID)
    {
        try
        {
            $MemberDetails    =   new MemberDetails();
            return $MemberDetails->doMemberDetailsExist($memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add details for Member ID [" . $memberID . "] - " . $e);
            return FALSE;
        }
    }

    public function addMemberEmail($memberEmail, $memberID)
    {
        try
        {
            $NewMemberEmail    =   new MemberEmails();
            return $NewMemberEmail->addMemberEmail($memberEmail, $memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add email address [ " . $memberEmail . "] for Member ID [" . $memberID . "] - " . $e);
            return FALSE;
        }
    }

    public function updateMemberEmail($memberEmailsID, $fillableArray)
    {
        try
        {
            $MemberEmail    =   new MemberEmails();
            return $MemberEmail->updateMemberEmail($memberEmailsID, $fillableArray);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not update MemberEmails ID [" . $memberEmailsID . "] - " . $e);
            return FALSE;
        }
    }

    public function addMemberDetails($memberID, $fillableArray)
    {
        try
        {
            $NewMemberDetail    =   new MemberDetails();
            return $NewMemberDetail->addMemberDetails($memberID, $fillableArray);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add details for Member Detail ID [" . $memberID . "] - " . $e);
            return FALSE;
        }
    }

    public function updateMemberDetails($memberID, $fillableArray)
    {
        try
        {
            $MemberDetails    =   new MemberDetails();
            return $MemberDetails->updateMemberDetails($memberID, $fillableArray);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not update Member Details ID [" . $memberID . "] - " . $e);
            return FALSE;
        }
    }

    public function setSiteUserMemberID($userID, $memberID)
    {
        try
        {
            $SiteUser    =   new SiteUser();
            $SiteUser->setSiteUserMemberID($userID, $memberID);
            return TRUE;
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not set the User [" . $userID . "] with the new  Member ID [" . $memberID . "]. " . $e);
            return FALSE;
        }
    }

    public function checkPasswordStrength($password)
    {
        if( !preg_match("#[0-9]+#", $password) )
        {
            $error[]    =   "Password must include at least one number!";
        }

        if( !preg_match("#[a-z]+#", $password) )
        {
            $error[]    =   "Password must include at least one letter!";
        }

        if( !preg_match("#[A-Z]+#", $password) )
        {
            $error[]    =   "Password must include at least one CAPS!";
        }

        if( !preg_match("#\W+#", $password) )
        {
            $error[]    =   "Password must include at least one symbol!";
        }

        if(isset($error) && count($error) >= 1)
        {
            $output     =   array
                            (
                                'status' =>   FALSE,
                                'errors' =>   $error,
                            );
        }
        else
        {
            $output     =   array
                            (
                                'status' =>   TRUE,
                            );
        }

        return $output;
    }

	/**
	 * Sends an email
	 *
	 * @param $emailTemplateName
     * @param $emailTemplateDataVariables
     * @param $emailMessageVariables
     *
     * @return bool
     */
    public function sendEmail($emailTemplateName, $emailTemplateDataVariables, $emailMessageVariables)
	{
        $EmailTemplate      =   new EmailUtility();
		$emailContent       =   $EmailTemplate->getEmailTemplate($emailTemplateName, $emailTemplateDataVariables);

        try
        {
            Mail::send(
            array
            (
                $emailContent['htmlView'],
                $emailContent['textView']
            ),
            $emailContent['templateVariables'],
            function($message) use ($emailMessageVariables, $emailContent){
                $message->from
                            (
                                $_ENV['EMAIL_OPTIONS_FromEmailAddresses_' . str_replace(" ", "_", $emailMessageVariables['fromTag']) . '_email'],
                                $_ENV['EMAIL_OPTIONS_FromEmailAddresses_' . str_replace(" ", "_", $emailMessageVariables['fromTag']) . '_senderName']
                            );
                $message->to($emailMessageVariables['sendToEmail'],$emailMessageVariables['sendToName']);
                $message->subject($emailContent['subject']);

                if($emailMessageVariables['ccArray'])
                {
                    foreach($emailMessageVariables['ccArray'] as $ccArray)
                    {
                        $message->cc($ccArray['cc_email']);
                    }
                }

                if($emailMessageVariables['attachArray'])
                {
                    foreach($emailMessageVariables['attachArray'] as $attachArray)
                    {
                        $message->attach
                                    (
                                        $attachArray['pathToFile'],
                                        array
                                        (
                                            'as'    =>  $attachArray['display'],
                                            'mime'  =>  $attachArray['mime']
                                        )
                                    );
                    }
                }
            });

            return TRUE;
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not send the " . $emailTemplateName . " email to " . $emailTemplateDataVariables['sendToEmail'] . ". " . $e);
            return FALSE;
        }
    }

    public function addAdminAlert()
    {
        // todo: Add an Admin Alert for certain issues
    }
}