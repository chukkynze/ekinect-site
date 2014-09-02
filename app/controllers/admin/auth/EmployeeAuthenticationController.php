<?php
 /**
  * Class EmployeeAuthenticationController
  *
  * filename:   EmployeeAuthenticationController.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/9/14 8:58 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */

class EmployeeAuthenticationController extends BaseController
{
    use MemberControls;
    use EmployeeControls;
    use SecurityControls;

    /**
     * This is the maximum amount of time it can take for a employee to verify their email address.
     */
    const POLICY_AllowedVerificationSeconds_Signup				=   43200;
    /**
     * This is the maximum amount of time it can take for a employee to verify & completely change their password.
     */
    const POLICY_AllowedVerificationSeconds_ChangePassword		=   10800;


    /**
     * How many attempts are allowed per access activity
     */
    const POLICY_AllowedEmployeeLoginAttempts       		    =   300;
    const POLICY_AllowedLoginAttempts       					=   300;
    const POLICY_AllowedLoginCaptchaAttempts    				=   3;
    const POLICY_AllowedSignupAttempts       					=   3;
    const POLICY_AllowedForgotAttempts       					=   3;
    const POLICY_AllowedChangeVerifiedMemberPasswordAttempts 	=   300;
    const POLICY_AllowedChangeOldMemberPasswordAttempts 		=   3;
    const POLICY_AllowedLostSignupVerificationAttempts 			=   3;


    /**
     * How far back to compare access attempts
     */
    const POLICY_AllowedAttemptsLookBackDuration  				=   'Last1Hour';


    private $activity;
    private $reason;

    public function __construct()
    {
        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit
    }


	/**
	 * Show the access page: login
	 * 
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
	 */
	public function showLogin()
	{
        $authCheck  =   $this->authCheckOnEmployeeAccess();
        if(FALSE != $authCheck){return Redirect::route($authCheck['name']);}

        $FormMessages          =   '';

        $viewData   =   array
                        (
                            'FormMessages'         =>  $FormMessages,
                        );
        return $this->makeResponseView('admin/auth/login', $viewData);
	}


    public function postLogin()
    {
        $FormName           =   'EmployeeLoginForm';
        $FormMessages       =   '';
        $AttemptMessages    =   '';
        $returnToRoute      =   array
	                            (
	                                'name'  =>  FALSE,
	                                'data'  =>  FALSE,
	                            );


        if(Request::isMethod('post'))
        {
            $authCheck  =   $this->authCheckOnEmployeeAccess();
            if(FALSE != $authCheck){return Redirect::route($authCheck['name']);}

            // Check if Access is allowed
            if(!$this->isAccessAllowed())
            {
                return Redirect::route('access-temp-disabled', FALSE);
            }


            $Attempts   =   $this->getAccessAttemptByUserIDs
                                    (
                                        $FormName,
                                        array($this->getSiteUser()->id),
                                        self::POLICY_AllowedAttemptsLookBackDuration
                                    );

            if($Attempts['total'] < self::POLICY_AllowedEmployeeLoginAttempts)
            {
                if($this->isFormClean($FormName, Input::all()))
                {
                    $formFields     =   array
                                        (
                                            'returning_employee'            =>  Input::get('returning_employee'),
                                            'employee_password'             =>  Input::get('employee_password'),
                                        );
                    $formRules      =   array
                                        (
                                            'returning_employee'            =>  array
                                                                                (
                                                                                    'required',
                                                                                    'email',
                                                                                    'exists:employee_emails,email_address',
                                                                                    'between:5,120',
                                                                                ),
                                            'employee_password'             =>  array
                                                                                (
                                                                                    'required',
                                                                                    'between:10,256',
                                                                                ),
                                        );
                    $formMessages   =   array
                                        (
                                            'returning_employee.required'   =>  "Your email address is required and can not be empty.",
                                            'returning_employee.email'      =>  "Your email address format is invalid.",
                                            'returning_employee.exists'     =>  "Your email address does not exist in our records.",
                                            'returning_employee.between'    =>  "Your email address is too long.",

                                            'employee_password.required'    =>  "Please enter your password.",
                                            'employee_password.between'     =>  "Passwords must be more than 10 digits.",
                                        );

                    $validator      =   Validator::make($formFields, $formRules, $formMessages);

                    if ($validator->passes())
                    {
                        // Get the member id from the submitted email
                        $memberID               =   $this->getEmployeeMemberIDFromEmailAddress($formFields['returning_employee']);
	                    $isMemberTypeAllowed    =   $this->isMemberTypeAllowedHere($memberID);

	                    if($isMemberTypeAllowed)
	                    {
		                    $salts              =   $this->getMemberSaltFromID($memberID);
	                        $loginCredentials   =   $this->generateMemberLoginCredentials($formFields['returning_employee'], $formFields['employee_password'], $salts['salt1'], $salts['salt2'], $salts['salt3']);

	                        $this->addEmployeeSiteStatus("Attempting log in.", $memberID);

	                        // Check if Employee Status is valid
                            $isEmployeeStatusLocked      =   $this->isEmployeeStatusLocked($memberID);

                            if(!$isEmployeeStatusLocked)
                            {
                                // Ensure member is not required to perform a forced behaviour
                                $employeeHasNoForce         =   $this->checkEmployeeHasNoForce($memberID);

                                if($employeeHasNoForce['AttemptStatus'])
                                {
                                    // Check Employment Status
                                    $employeeCanWork		=	$this->checkEmploymentStatus();

                                    if($employeeCanWork['AttemptStatus'])
                                    {
                                        // create our user data for the authentication
                                        $authData           =   array
                                                                (
                                                                    'id' 	   => $memberID,
                                                                    'password' => $loginCredentials,
                                                                );

                                        if (Auth::attempt($authData, true))
                                        {
                                            $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 1);
                                            $authCheck  =   $this->authCheckOnEmployeeAccess();
                                            if(FALSE != $authCheck)
                                            {
                                                $this->addEmployeeSiteStatus("Successfully logged in.", $memberID);
                                                // todo: Send email stating you have logged in
                                                return Redirect::route($authCheck['name']);
                                            }
                                        }
                                        else
                                        {
                                            $FormMessages   =   array();
                                            $FormMessages[] =   "Unfortunately, we do not recognize your login credentials. Please retry.";

                                            $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 0);
                                            Log::info($FormName . " - incorrect login credentials.");
                                        }
                                    }
                                    else
                                    {
                                        $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                                        $this->addAdminAlert();
                                        Log::warning($FormName . " member employment is not in order.");
                                        $returnToRoute  =   array
                                                            (
                                                                'name'  =>  'custom-error',
                                                                'data'  =>  array('errorNumber' => 28),
                                                            );
                                    }
                                }
                                else
                                {
                                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                                    $this->addAdminAlert();
                                    Log::warning($FormName . " member is under force.");
                                    $returnToRoute  =   array
                                                        (
                                                            'name'  =>  'custom-error',
                                                            'data'  =>  array('errorNumber' => 25),
                                                        );
                                }
                            }
                            else
                            {
                                $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                                $this->addAdminAlert();
                                Log::warning($FormName . " member status is under lock.");
                                $returnToRoute  =   array
                                                    (
                                                        'name'  =>  'custom-error',
                                                        'data'  =>  array('errorNumber' => 24),
                                                    );
                            }
	                    }
	                    else
	                    {
		                    $FormMessages   =   array();
                            $FormMessages[] =   "Your membership is valid, yet this is not your correct access point. Please check your emailed login instructions.";
                            Log::info($FormName . " - member type not allowed here.");
	                    }
                    }
                    else
                    {
                        $FormErrors   =   $validator->messages()->toArray();
                        $FormMessages =   array();
                        foreach($FormErrors as $errors)
                        {
                            $FormMessages[]   =   $errors[0];
                        }

                        $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 0);
                        Log::info($FormName . " - form values did not pass.");
                    }
                }
                else
                {
                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                    $this->addAdminAlert();
                    Log::warning($FormName . " has invalid dummy variables passed.");
                    $returnToRoute  =   array
                                        (
                                            'name'  =>  'custom-error',
                                            'data'  =>  array('errorNumber' => 23),
                                        );
                }
            }
            else
            {
                $this->applyLock('Locked:Excessive-EmployeeLogin-Attempts', '','excessive-logins', []);
                $returnToRoute  =   array
                                    (
                                        'name'  =>  'custom-error',
                                        'data'  =>  array('errorNumber' => 27),
                                    );
            }
        }
        else
        {
            Log::warning($FormName . " is not being correctly posted to.");
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
                'FormMessages'         =>  $FormMessages,
            );
            return $this->makeResponseView('admin/auth/login', $viewData);
        }
    }


	public function isMemberTypeAllowedHere($memberID)
	{
        try
        {
            $Member     =   Member::where("id", "=", $memberID)->first();
            switch($Member->getMemberType())
            {
	            case 'employee'       :
	                return TRUE;
	                break;

	            default : return FALSE;
            }
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not find if member id [" . $memberID . "] is allowed here. " . $e);
            return FALSE;
        }
	}

	/**
	 * This is the catch all method for the policies affecting whether a user/member is allowed access.
	 * It also takes into consideration reasons to lock the site that may go beyond just a single user
	 *
	 * @return bool
	 */
	public function isAccessAllowed()
	{
		$returnValue 	=	FALSE;

		if($this->isUserIPAddressAllowedAccess())
		{
			$returnValue	=	TRUE;
		}

		return $returnValue;
	}


    public function isMemberAccessAllowed()
    {
		$returnValue 	=	FALSE;

		#if($this->isUserAllowedAccess())
		#{
		#	$returnValue	=	TRUE;
		#}

		if($this->isUserIPAddressAllowedAccess())
		{
			$returnValue	=	TRUE;
		}

		if($this->isUserMemberAllowedAccess())
		{
			$returnValue	=	TRUE;
		}

		return $returnValue;
    }

	/**
	 * The "Force:" keyword is used to denote that the user, having passed basic identification (NOT Authentication)
	 * needs to perform certain actions or have certain actions performed upon them
	 *
	 * @param $memberID
     *
     * @return array|bool
     */
    public function checkEmployeeHasNoForce($memberID)
	{
		try
        {
            $EmployeeStatus    =   new EmployeeStatus();
            return $EmployeeStatus->checkEmployeeHasNoForce($memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not check if member [ " . $memberID . " ] has a forced requirement. " . $e);
            return FALSE;
        }





	}


	public function checkEmploymentStatus()
	{
		$AttemptStatus 			=	TRUE;
		$AttemptStatusRoute 	=	'';

		return 	array
				(
					'AttemptStatus' 		=>	$AttemptStatus,
					'AttemptStatusRoute' 	=>	$AttemptStatusRoute,
				);
	}

	/**
	 * This method determines if the employee id is allowed access
     * and is only checked upon validating that the login creds are valid and correct
	 *
	 * @return bool
	 */

    /**
	 * This method determines if the member id is allowed access
     * and is only checked upon validating that the login creds are valid and correct
	 *
     * @param $memberID
     *
     * @return bool
     */
    public function isEmployeeStatusLocked($memberID)
	{
		try
        {
            $EmployeeStatus    =   new EmployeeStatus();
            return $EmployeeStatus->isEmployeeStatusLocked($memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not check if employee member id [ " . $memberID . " ] status is locked. " . $e);
            return FALSE;
        }
	}

	/**
	 * This determines if the ip address provided by the user is allowed access
	 *
	 * @return bool
	 */
	public function isUserIPAddressAllowedAccess()
	{
		try
        {
            $IpBin   =   new IpBin();
            return $IpBin->isUserIPAddressAllowedAccess($this->getSiteUser()->getId());
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not check if ip address is allowed access for user [" . $this->getSiteUser()->getId() . "]. " . $e);
            return FALSE;
        }
	}

	/**
	 * Is the member associated with this user allowed access
	 *
	 * @return bool
	 */
	public function isUserMemberAllowedAccess()
	{
		$BlockedEmployeeStatuses 	=	array
									(
										'Locked:Excessive-Login-Attempts',
									);

		return (	$this->getSiteUser()->getUserMemberID()*1 > 0
				&& 	!in_array
					(
						$this->getEmployeeStatusByMemberID($this->getSiteUser()->getUserMemberID()),
						$BlockedEmployeeStatuses
					)
					? 	TRUE
					: 	FALSE);
	}


    public function resendSignupConfirmation()
	{
		$FormMessages       =   "";
        $viewData           =   array
                                (
                                    'FormMessages'         		=>  $FormMessages,
                                );
        return $this->makeResponseView('application/auth/lost-signup-verification', $viewData);
	}

    public function processResendSignupConfirmation()
	{
		$FormName			=	"LostSignupVerificationForm";
        $returnToRoute      =   array
                                (
                                    'name'  =>  FALSE,
                                    'data'  =>  FALSE,
                                );
        $FormMessages       =   "";

        if(Request::isMethod('post'))
        {
            $Attempts       =   $this->getAccessAttemptByUserIDs
                                        (
                                            $FormName,
                                            array($this->getSiteUser()->id),
                                            self::POLICY_AllowedAttemptsLookBackDuration
                                        );

            if($Attempts['total'] < self::POLICY_AllowedLostSignupVerificationAttempts)
            {
                if($this->isFormClean($FormName, Input::all()))
                {

                    $formFields     =   array
                                        (
                                            'lost_signup_email'         =>  Input::get('lost_signup_email'),
                                            'recaptcha_response_field'  =>  Input::get('recaptcha_response_field'),
                                        );
                    $formRules      =   array
                                        (
                                            'lost_signup_email'         =>  array
                                                                            (
                                                                                'required',
                                                                                'email',
                                                                                'exists:employee_email_status,email_address',
                                                                                'between:5,120',
                                                                            ),
                                            'recaptcha_response_field'  =>  array
                                                                            (
                                                                                'required',
                                                                                'recaptcha',
                                                                            ),
                                        );
                    $formMessages   =   array
                                        (
                                            'lost_signup_email.required'            =>  "An email address is required and can not be empty.",
                                            'lost_signup_email.email'               =>  "Your email address format is invalid.",
                                            'lost_signup_email.exists'              =>  "Are you sure you've already <a href='/signup'>signed up</a>?",
                                            'lost_signup_email.between'             =>  "Please, re-check your email address' size.",

                                            'recaptcha_response_field.required'     =>  "Please enter the reCaptcha value.",
                                            'recaptcha_response_field.recaptcha'    =>  "Your reCaptcha entry is incorrect.",
                                        );

                    $validator      =   Validator::make($formFields, $formRules, $formMessages);

                    if ($validator->passes())
                    {
                        $NewMemberID    =   $this->getEmployeeMemberIDFromEmailAddress($formFields['lost_signup_email']);

                        if($NewMemberID > 0)
                        {
                            // Check to see if this email has already received an EmployeeEmailStatus of 'Verified' or 'VerificationSentAgain'
                            $decisions  =   2;
                            ($this->checkForPreviousEmailStatus($formFields['lost_signup_email'],'Verified')                ?   $decisions : $decisions--);
                            ($this->checkForPreviousEmailStatus($formFields['lost_signup_email'],'VerificationSentAgain')   ?   $decisions : $decisions--);

                            // todo: Check how far back email validation was sent (and if member email exists)

                            $isAlreadyVerified  =   ($decisions == 0 ? TRUE : FALSE);
                            if($isAlreadyVerified)
                            {
                                // ReSend an Email for Validation
                                $verifyEmailLink    =   $this->generateVerifyEmailLink($formFields['lost_signup_email'], $NewMemberID, 'verify-new-member');
                                $sendEmailStatus    =   $this->sendEmail
                                                        (
                                                            'verify-new-member-again',
                                                            array
                                                            (
                                                                'verifyEmailLink' => $verifyEmailLink
                                                            ),
                                                            array
                                                            (
                                                                'fromTag'       =>  'General',
                                                                'sendToEmail'   =>  $formFields['lost_signup_email'],
                                                                'sendToName'    =>  'Welcome to Ekinect',
                                                                'subject'       =>  'Welcome to Ekinect',
                                                                'ccArray'       =>  FALSE,
                                                                'attachArray'   =>  FALSE,
                                                            )
                                                        );

                                if($sendEmailStatus)
                                {
                                    // Update Member emails that verification was sent and at what time for this member
                                    $this->updateEmployeeEmail
                                            (
                                                $this->getEmployeeEmailIDFromEmailAddress($formFields['lost_signup_email']),
                                                array
                                                (
                                                    'verification_sent'     =>  1,
                                                    'verification_sent_on'  =>  strtotime('now'),
                                                )
                                            );

                                    // Redirect to Successful Signup Page that informs them of the need to validate the email before they can enjoy the free 90 day Premium membership
                                    // Update status
                                    $this->addEmployeeEmailStatus($formFields['lost_signup_email'], 'VerificationSentAgain');
                                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 1);
                                    $viewData   =   array
                                                    (
                                                        'emailAddress' => $formFields['lost_signup_email'],
                                                    );
                                    return $this->makeResponseView('application/auth/member-signup-success', $viewData);
                                }
                                else
                                {
                                    $this->addAdminAlert();
                                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                                    Log::info($FormName . " - Could not resend the new member email to [" . $formFields['lost_signup_email'] . "] for member id [" . $NewMemberID . "].");
                                    $employeeService    =   str_replace("[errorNumber]", "Could not resend the new member email.", self::POLICY_LinkEmployeeService );
                                    $FormMessages       =   array();
                                    $FormMessages[]     =   "Sorry, we cannot complete the signup process at this time.
                                                             Please refresh, and if the issue continues, contact " . $employeeService . ".";
                                }
                            }
                            else
                            {
                                $this->addAdminAlert();
                                $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                                Log::info($FormName . " - Could not resend verification email to [" . $formFields['lost_signup_email'] . "] for member id [" . $NewMemberID . "]. Member is already verified.");
                                $employeeService    =   str_replace("[errorNumber]", "Could not resend verification email.", self::POLICY_LinkEmployeeService );
                                $FormMessages       =   array();
                                $FormMessages[]     =   "It appears that you have already verified your email address. There is no need to resend a verification.
                                                         Check your inbox for instructions and, if you still require assistance, contact " . $employeeService . ".";
                            }
                        }
                        else
                        {
                            $this->addAdminAlert();
                            $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                            Log::info($FormName . " - Could not get new member id from email provided [" . $formFields['lost_signup_email'] . "].");
                            $employeeService    =   str_replace("[errorNumber]", "Could not resend verification for new member.", self::POLICY_LinkEmployeeService );
                            $FormMessages       =   array();
                            $FormMessages[]     =   "Sorry, we cannot complete the signup and verification process at this time.
                                                    Please refresh, and if the issue continues, contact " . $employeeService . ".";
                        }
                    }
                    else
                    {
                        $FormErrors   =   $validator->messages()->toArray();
                        $FormMessages =   array();
                        foreach($FormErrors as $errors)
                        {
                            $FormMessages[]   =   $errors[0];
                        }

                        $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 0);
                        Log::info($FormName . " - form values did not pass.");
                    }
                }
                else
                {
                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                    $this->addAdminAlert();
                    Log::warning($FormName . " has invalid dummy variables passed.");
                    $returnToRoute  =   array
                                        (
                                            'name'  =>  'custom-error',
                                            'data'  =>  array('errorNumber' => 23),
                                        );
                }
            }
            else
            {
                $this->applyLock('Locked:Excessive-LostSignupVerification-Attempts', Input::get('lost_signup_email'),'excessive-lost-signup-verification', []);
                $returnToRoute  =   array
                                    (
                                        'name'  =>  'custom-error',
                                        'data'  =>  array('errorNumber' => 18),
                                    );
            }
        }
        else
        {
            Log::warning($FormName . " is not being correctly posted to.");
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
            $viewData           =   array
                                (
                                    'FormMessages'  =>  $FormMessages,
                                );
            return $this->makeResponseView('application/auth/lost-signup-verification', $viewData);
        }
	}


    public function logout()
    {
		Auth::logout();
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

    public function employeeLogout()
    {
        $this->logout();
        return $this->makeResponseView('admin/employees/employee-logout', array());
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


	/**
	 * Create a new employee and send a verification email
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
	 */
	public function postSignup()
    {
        $FormName       =   'SignupForm';
        $returnToRoute  =   array
                            (
					            'name'  =>  FALSE,
					            'data'  =>  FALSE,
					        );
        $FormMessages   =   array();

        if(Request::isMethod('post'))
        {
            $Attempts   =   $this->getAccessAttemptByUserIDs
                            (
                                $FormName,
                                array($this->getSiteUser()->id),
                                self::POLICY_AllowedAttemptsLookBackDuration
                            );

            if($Attempts['total'] < self::POLICY_AllowedSignupAttempts)
            {
                if($this->isFormClean($FormName, Input::all()))
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
                                                                                'unique:employee_emails,email_address',
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
                                            'password.between'      =>  "Passwords must be more than 10 digits.",

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
                        $this->addEmployeeEmailStatus($formFields['new_member'], 'AddedUnverified');

                        // Get the Site User so you can associate this user behaviour with this new member
                        $this->SiteUser =   $this->getSiteUser();

                        // Create a Member Object
                        $NewMemberID    =   $this->addEmployee($formFields['new_member'], $formFields['password']);

                        if($NewMemberID > 0)
                        {
                            // Update User with Member ID
                            $this->setSiteUserMemberID($this->getSiteUser()->getID(), $NewMemberID);

                            // Create & Save a Employee Status Object for the new Member
                            $this->addEmployeeStatus('Successful-Signup', $NewMemberID);

                            // Create & Save a Employee Emails Object
                            $NewEmployeeEmailID   =   $this->addEmployeeEmail($formFields['new_member'], $NewMemberID);

                            if($NewEmployeeEmailID > 0)
                            {
                                // Prepare an Email for Validation
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
                                    // Update employee emails that verification was sent and at what time for this member
                                    $this->updateEmployeeEmail($NewEmployeeEmailID, array
                                    (
                                        'verification_sent'     =>  1,
                                        'verification_sent_on'  =>  strtotime('now'),
                                    ));

                                    // Add the emailAddress status
                                    $this->addEmployeeEmailStatus($formFields['new_member'], 'VerificationSent');

                                    // Redirect to Successful Signup Page that informs them of the need to validate the email before they can login
                                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 1);
                                    $viewData   =   array
                                                    (
                                                        'emailAddress'        =>  $formFields['new_member'],
                                                    );
                                    return $this->makeResponseView('application/auth/employee-signup-success', $viewData);
                                }
                                else
                                {
                                    $this->addAdminAlert();
                                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                                    Log::info($FormName . " - Could not send the new employee email to [" . $formFields['new_member'] . "] for member id [" . $NewMemberID . "].");
                                    $employeeService        =   str_replace("[errorNumber]", "Could not send the new employee email.", self::POLICY_LinkEmployeeService );
                                    $SignupFormMessages[]   =   "Sorry, we cannot complete the signup process at this time.
                                                                Please refresh, and if the issue continues, contact " . $employeeService . ".";
                                }
                            }
                            else
                            {
                                $this->addAdminAlert();
                                $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                                Log::info($FormName . " - Could not create a new employee email.");
                                $employeeService        =   str_replace("[errorNumber]", "Could not create a new employee email.", self::POLICY_LinkEmployeeService );
                                $SignupFormMessages[]   =   "Sorry, we cannot complete the signup process at this time.
                                                            Please refresh, and if the issue continues, contact " . $employeeService . ".";
                            }
                        }
                        else
                        {
                            $this->addAdminAlert();
                            $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                            Log::info($FormName . " - Could not create a new member.");
                            $employeeService        =   str_replace("[errorNumber]", "Could not create a new employee.", self::POLICY_LinkEmployeeService );
                            $SignupFormMessages[]   =   "Sorry, we cannot complete the signup process at this time.
                                                        Please refresh, and if the issue continues, contact " . $employeeService . ".";
                        }
                    }
                    else
                    {
                        $FormErrors   =   $validator->messages()->toArray();
                        $FormMessages =   array();
                        foreach($FormErrors as $errors)
                        {
                            $FormMessages[]   =   $errors[0];
                        }

                        if(array_key_exists('errors', $passwordCheck))
                        {
                            foreach($passwordCheck['errors'] as $errors)
                            {
                                $FormMessages[]   =   $errors;
                            }
                        }

                        $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 0);
                        Log::info($FormName . " - form values did not pass.");
                    }
                }
                else
                {
                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                    $this->addAdminAlert();
                    Log::warning($FormName . " has invalid dummy variables passed.");
                    $returnToRoute  =   array
                                        (
                                            'name'  =>  'custom-error',
                                            'data'  =>  array('errorNumber' => 23),
                                        );
                }
            }
            else
            {
                $this->applyLock('Locked:Excessive-Signup-Attempts', '','excessive-signups', []);
                $returnToRoute  =   array
                                    (
                                        'name'  =>  'custom-error',
                                        'data'  =>  array('errorNumber' => 18),
                                    );
            }
        }
        else
        {
            Log::warning($FormName . " is not being correctly posted to.");
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
                'SignupFormMessages'        =>  (count($FormMessages) >= 1 ? $FormMessages : ''),
                'ForgotFormMessages'        =>  '',

                'LoginHeaderMessage'        =>  ''
            );
            return $this->makeResponseView('application/auth/login', $viewData);
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

    public function processForgotPassword()
    {
        $FormName			=	"ForgotForm";
        $returnToRoute      =   array
                                (
                                    'name'  =>  FALSE,
                                    'data'  =>  FALSE,
                                );
        $FormMessages       =   "";

        if(Request::isMethod('post'))
        {
            $Attempts       =   $this->getAccessAttemptByUserIDs
				                (
				                    'ForgotForm',
				                    array($this->getSiteUser()->id),
				                    self::POLICY_AllowedAttemptsLookBackDuration
				                );

            if($Attempts['total'] < self::POLICY_AllowedForgotAttempts)
            {
                if($this->isFormClean($FormName, Input::all()))
                {

                    $formFields     =   array
                                        (
                                            'forgot_email'              =>  Input::get('forgot_email'),
                                            'recaptcha_response_field'  =>  Input::get('recaptcha_response_field'),
                                        );
                    $formRules      =   array
                                        (
                                            'forgot_email'              =>  array
                                                                            (
                                                                                'required',
                                                                                'email',
                                                                                'exists:employee_emails,email_address',
                                                                                'between:5,120',
                                                                            ),
                                            'recaptcha_response_field'  =>  array
                                                                            (
                                                                                'required',
                                                                                'recaptcha',
                                                                            ),
                                        );
                    $formMessages   =   array
                                        (
                                            'forgot_email.required'            =>  "Your email address is required and can not be empty.",
                                            'forgot_email.email'               =>  "Your email address format is invalid.",
                                            'forgot_email.exists'              =>  "Are you sure you've <a href='/signup'>signed up</a>?",
                                            'forgot_email.between'             =>  "Please, re-check your email address' size.",

                                            'recaptcha_response_field.required'     =>  "Please enter the reCaptcha value.",
                                            'recaptcha_response_field.recaptcha'    =>  "Your reCaptcha entry is incorrect.",
                                        );

                    $validator      =   Validator::make($formFields, $formRules, $formMessages);

                    if ($validator->passes())
                    {
                        $this->addEmployeeEmailStatus($formFields['forgot_email'], 'Forgot');

                        $NewMemberID    =   $this->getEmployeeMemberIDFromEmailAddress($formFields['forgot_email']);

                        // Send an Email for Validation
                        $verifyEmailLink    =   $this->generateVerifyEmailLink($formFields['forgot_email'], $NewMemberID, 'forgot-logins-success');
                        $EmployeeDetails      =   $this->getEmployeeDetailsFromMemberID($NewMemberID);
                        $sendEmailStatus    =   $this->sendEmail
					                            (
					                                'forgot-logins-success',
					                                array
					                                (
					                                    'verifyEmailLink'   => $verifyEmailLink,
					                                    'first_name'	    =>	$EmployeeDetails->first_name,
					                                    'last_name'			=>	$EmployeeDetails->last_name,
					                                ),
					                                array
					                                (
					                                    'fromTag'           =>  'General',
					                                    'sendToEmail'       =>  $formFields['forgot_email'],
					                                    'sendToName'        =>  $EmployeeDetails->first_name . ' ' . $EmployeeDetails->last_name,
					                                    'subject'           =>  'Access Issues',
					                                    'ccArray'           =>  FALSE,
					                                    'attachArray'       =>  FALSE,
					                                )
					                            );
                        $this->registerAccessAttempt($FormName, $FormName, 1);
                        $viewData   =   array
                        (
                            'emailAddress' => $formFields['forgot_email'],
                        );
                        return $this->makeResponseView('application/auth/forgot-success', $viewData);
                    }
                    else
                    {
                        $FormErrors   =   $validator->messages()->toArray();
                        $FormMessages =   array();
                        foreach($FormErrors as $errors)
                        {
                            $FormMessages[]   =   $errors[0];
                        }

                        $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 0);
                        Log::info($FormName . " - form values did not pass.");
                    }
                }
                else
                {
                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                    $this->addAdminAlert();
                    Log::warning($FormName . " has invalid dummy variables passed.");
                    $returnToRoute  =   array
                    (
                        'name'  =>  'custom-error',
                        'data'  =>  array('errorNumber' => 23),
                    );
                }
            }
            else
            {
                $this->applyLock('Locked:Excessive-ForgotLogin-Attempts', Input::get('forgot_email'),'excessive-forgot-logins', []);
                $returnToRoute  =   array
                (
                    'name'  =>  'custom-error',
                    'data'  =>  array('errorNumber' => 20),
                );
            }
        }
        else
        {
            Log::warning($FormName . " is not being correctly posted to.");
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
            $viewData           =   array
                                    (
                                        'FormMessages'  =>  $FormMessages,
                                    );
            return $this->makeResponseView('application/auth/forgot-success', $viewData);
        }
    }

    public function processVerificationDetails()
    {
        // Please use your info to login to your free trial
        // success needs to be on the landing pages so the login button is right on top
        $SubmittedFormName                  =   'VerificationDetailsForm';
        $returnToRoute                      =   array
                                                (
                                                    'name'  =>  FALSE,
                                                    'data'  =>  FALSE,
                                                );
        $VerificationDetailsFormMessages    =   array();

        if(Request::isMethod('post'))
        {
            if($this->isFormClean($SubmittedFormName, Input::all()))
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
                            $memberDetailsExist     =   $this->doEmployeeDetailsExist($verifiedMemberIDArray['memberID']);

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
                                $this->updateEmployeeDetails($verifiedMemberIDArray['memberID'], $detailsFillableArray);
                            }
                            else
                            {
                                $this->addEmployeeDetails($verifiedMemberIDArray['memberID'], $detailsFillableArray);
                            }

                            // Update Member Object with Member Type
                            $memberFillableArray    =   array
                                                        (
                                                            'member_type'   =>  $this->getMemberTypeFromFromValue(strtolower($formFields['member_type'])),
                                                        );
                            $this->updateMember($verifiedMemberIDArray['memberID'], $memberFillableArray);
                            $this->addEmployeeStatus('VerifiedStartupDetails', $verifiedMemberIDArray['memberID']);
                            $this->addEmployeeStatus('ValidMember', $verifiedMemberIDArray['memberID']);
                            $this->addEmployeeSiteStatus('Member startup details complete.', $verifiedMemberIDArray['memberID']);

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


                            $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 1);
                            $viewData   =   array
                                            (
                                                'firstName'     =>  $formFields['first_name'],
                                                'emailAddress'  =>  $verifiedMemberIDArray['email'],
                                            );

                            return  $this->makeResponseView('application/auth/verification-details-success', $viewData);
                        }
                        else
                        {
                            $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 0);
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
                        $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 0);
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
                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 0);
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
            $this->registerAccessAttempt($this->getSiteUser()->getID(), $SubmittedFormName, 0);
            Log::warning($SubmittedFormName . " is not being correctly posted to.");
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
            return  $this->makeResponseView('application/auth/verified_email_success', $viewData);
        }
    }

    /**
     * Processes the Verification Details form
     *
     * @param $vCode
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function verifyEmail($vCode)
    {
        $returnToRoute  =   array
                            (
                                'name'  =>  FALSE,
                                'data'  =>  FALSE,
                            );

        /**
         * Must return both email and member id because a member may have more than one email address
         */
        $verifiedMemberIDArray  =   $this->verifyEmailByLinkAndGetMemberIDArray($vCode, 'VerificationDetailsForm');

	    if (!isset($verifiedMemberIDArray['errorNbr']) && !isset($verifiedMemberIDArray['errorMsg']))
	    {
		    $vCodeCreateTime		=	(is_numeric($verifiedMemberIDArray['vCodeCreateTime'])
	                                        ?   (int) $verifiedMemberIDArray['vCodeCreateTime']
	                                        :   0);
	        $verificationDuration	=	( (strtotime("now") - $vCodeCreateTime) <= self::POLICY_AllowedVerificationSeconds_Signup
	                                        ?   TRUE
	                                        :   FALSE );

	        if($verificationDuration)
	        {
	            if (isset($verifiedMemberIDArray) && is_array($verifiedMemberIDArray))
                {
                    if ($verifiedMemberIDArray['alreadyVerified'] === 0)
                    {
                        // Create New Employee Status for this member identifying as verified
                        $this->addEmployeeStatus('VerifiedEmail', $verifiedMemberIDArray['memberID']);

                        $this->updateEmployeeEmail($verifiedMemberIDArray['memberID'], array
                        (
                            'verified'     =>  1,
                            'verified_on'  =>  strtotime('now'),
                        ));
                    }

                    $this->addEmployeeEmailStatus($verifiedMemberIDArray['email'], 'Verified');
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
	            Log::info("Error #22 - verification link has expired.");
	            $returnToRoute  =   array
	            (
	                'name'  =>  'custom-error',
	                'data'  =>  array('errorNumber' => 22),
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



        if(FALSE != $returnToRoute['name'])
        {
            return Redirect::route($returnToRoute['name'],$returnToRoute['data']);
        }
        else
        {
            // Create Employee Details Form - also force to add name, gender, employee type and zip code and time zone in form
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
            return $this->makeResponseView('application/auth/verified_email_success', $viewData);
        }
    }

    /**
     * Password Resets
     *
     * @param $vCode
     *
     * @return \Illuminate\Http\Response
     */
    public function showChangePasswordWithVerifyEmailLink($vCode)
    {
		$FormMessages       =   "";
        $viewData           =   array
                                (
                                    'vcode'         =>  $vCode,
                                    'FormMessages'  =>  $FormMessages,
                                );
        return $this->makeResponseView('application/auth/change-password-with-verified-email-link', $viewData);
    }

    public function postChangePasswordWithVerifyEmailLink()
    {
        $FormName           =   'ChangePasswordWithVerifyLinkForm';
        $FormMessages       =   '';
        $returnToRoute      =   array
                                (
                                    'name'  =>  FALSE,
                                    'data'  =>  FALSE,
                                );

        if(Request::isMethod('post'))
        {
            $Attempts   =   $this->getAccessAttemptByUserIDs
                            (
                                $FormName,
                                array($this->getSiteUser()->id),
                                self::POLICY_AllowedAttemptsLookBackDuration
                            );

            if($Attempts['total'] < self::POLICY_AllowedChangeVerifiedMemberPasswordAttempts)
            {
                if($this->isFormClean($FormName, Input::all()))
                {
                    // Validate vcode
                    $vcodeDetails           =   $this->verifyEmailByLinkAndGetMemberIDArray(Input::get('vcode'), 'ChangePasswordWithVerifyLinkForm');

	                if (!isset($vcodeDetails['errorNbr']) && !isset($vcodeDetails['errorMsg']))
	                {
		                $vcodeCreateTime		=	(is_numeric($vcodeDetails['vCodeCreateTime']) ? (int) $vcodeDetails['vCodeCreateTime'] : 0);
	                    $verificationDuration	=	( (strtotime("now") - $vcodeCreateTime) <= self::POLICY_AllowedVerificationSeconds_ChangePassword ? TRUE : FALSE );

	                    if($verificationDuration)
	                    {
	                        if (isset($vcodeDetails) && is_array($vcodeDetails))
                            {
                                $formFields     =   array
                                                    (
                                                        'change_verify_member'      =>  Input::get('change_verify_member'),
                                                        'password'                  =>  Input::get('password'),
                                                        'password_confirmation '    =>  Input::get('password_confirmation'),
                                                        'recaptcha_response_field'  =>  Input::get('recaptcha_response_field'),
                                                    );
                                $formRules      =   array
                                                    (
                                                        'change_verify_member'      =>  array
                                                                                        (
                                                                                            'required',
                                                                                            'email',
                                                                                            'exists:employee_emails,email_address',
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
                                                        'recaptcha_response_field'  =>  array
                                                                                        (
                                                                                            'required',
                                                                                            'recaptcha',
                                                                                        ),
                                                    );
                                $formMessages   =   array
                                                    (
                                                        'change_verify_member.required'   =>  "Your email address is required and can not be empty.",
                                                        'change_verify_member.email'      =>  "Your email address format is invalid.",
                                                        'change_verify_member.exists'     =>  "Please, use the primary email associated with your account.",
                                                        'change_verify_member.between'    =>  "Please, re-check your email address' size.",

                                                        'password.required'     =>  "Please enter your password.",
                                                        'password.confirmed'    =>  "A password confirmation is required.",
                                                        'password.between'      =>  "Passwords must be more than 10 digits.",

                                                        'password_confirmation.same'    =>  "A password confirmation is required.",

                                                        'recaptcha_response_field.required'     =>  "Please enter the reCaptcha value.",
                                                        'recaptcha_response_field.recaptcha'    =>  "Your reCaptcha entry is incorrect.",
                                                    );

                                $validator      =   Validator::make($formFields, $formRules, $formMessages);
                                $passwordCheck  =   $this->checkPasswordStrength($formFields['password']);

                                if ($validator->passes() && $passwordCheck['status'])
                                {
	                                if( $vcodeDetails['email'] ==  $formFields['change_verify_member'])
	                                {
		                                // Current Member Email Status Should be Forgot
		                                if($this->getEmployeeEmailStatus($vcodeDetails['email']) == 'Forgot')
		                                {
			                                $this->addEmployeeEmailStatus($vcodeDetails['email'], 'Remembered');

			                                $LoginCredentials       =   $this->generateLoginCredentials($vcodeDetails['email'], $formFields['password']);
					                        $memberFillableArray    =   array
					                                                    (
					                                                        'password'          =>  Hash::make($LoginCredentials[0]),
					                                                        'salt1'             =>  $LoginCredentials[1],
					                                                        'salt2'             =>  $LoginCredentials[2],
					                                                        'salt3'             =>  $LoginCredentials[3],
					                                                    );
                                            $this->updateMember($vcodeDetails['memberID'], $memberFillableArray);
                                            $this->addEmployeeStatus("ChangedPassword", $vcodeDetails['memberID']);
                                            $this->addEmployeeStatus("ValidMember", $vcodeDetails['memberID']);
                                            $this->addEmployeeSiteStatus("Member has changed their password.", $vcodeDetails['memberID']);

			                                $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 1);

			                                $successMessage[]       =   'Congratulations. You have successfully changed your password!';
                                            Session::put('successFlashMessage', $successMessage);

			                                $EmployeeDetailsObject    =   $this->getEmployeeDetailsFromMemberID($vcodeDetails['memberID']);
			                                $sendEmailStatus        =   $this->sendEmail
				                                                        (
				                                                            'genericPasswordChange',
				                                                            array
				                                                            (
				                                                                'first_name' 	=> 	$EmployeeDetailsObject->getEmployeeDetailsFirstName(),
																				'last_name' 	=> 	$EmployeeDetailsObject->getEmployeeDetailsLastName(),
				                                                            ),
				                                                            array
				                                                            (
				                                                                'fromTag'       =>  'General',
				                                                                'sendToEmail'   =>  $vcodeDetails['email'],
				                                                                'sendToName'    =>  $EmployeeDetailsObject->getEmployeeDetailsFullName(),
				                                                                'subject'       =>  'Password Reset',
				                                                                'ccArray'       =>  FALSE,
				                                                                'attachArray'   =>  FALSE,
				                                                            )
				                                                        );

			                                $viewData   =   array
		                                                    (
		                                                        'emailAddress'        =>  $vcodeDetails['email'],
		                                                    );
		                                    return $this->makeResponseView('application/auth/reset-verified-password-success', $viewData);

		                                }
		                                else
		                                {
			                                $FormMessages[]   =   'Your new access credentials can not be updated at this time. Please retry the link or contact Employee Service';
			                                $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 0);
		                                    Log::info($FormName . " - getEmployeeEmailStatusStatus != 'Forgot'");
		                                }
	                                }
	                                else
	                                {
		                                $FormMessages[]   =   'Your new access credentials can not be updated at this time. Please retry the link or contact Employee Service';
		                                $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 0);
	                                    Log::info($FormName . " - vcodeDetails['email'] !=  formFields['change_verify_member'].");
	                                }
                                }
                                else
                                {
                                    $FormErrors   =   $validator->messages()->toArray();
                                    $FormMessages =   array();
                                    foreach($FormErrors as $errors)
                                    {
                                        $FormMessages[]   =   $errors[0];
                                    }

                                    if(array_key_exists('errors', $passwordCheck))
                                    {
                                        foreach($passwordCheck['errors'] as $errors)
                                        {
                                            $FormMessages[]   =   $errors;
                                        }
                                    }

                                    $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 0);
                                    Log::info($FormName . " - form values did not pass.");
                                }
                            }
                            else
                            {
                                $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
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
	                        $this->addAdminAlert();
	                        Log::warning($FormName . " has expired vcode.");
	                        $returnToRoute  =   array
	                                            (
	                                                'name'  =>  'custom-error',
	                                                'data'  =>  array('errorNumber' => 22),
	                                            );
	                    }
	                }
	                else
                    {
                        $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                        Log::info("Error #" . $vcodeDetails['errorNbr'] . " - " . $vcodeDetails['errorMsg'] . ".");
                        $returnToRoute  =   array
                        (
                            'name'  =>  'custom-error',
                            'data'  =>  array('errorNumber' => $vcodeDetails['errorNbr']),
                        );
                    }
                }
                else
                {
                    $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
                    $this->addAdminAlert();
                    Log::warning($FormName . " has invalid dummy variables passed.");
                    $returnToRoute  =   array
                                        (
                                            'name'  =>  'custom-error',
                                            'data'  =>  array('errorNumber' => 23),
                                        );
                }
            }
            else
            {
                $this->applyLock('Locked:Excessive-ChangeVerifiedLinkPassword-Attempts', '','excessive-change-verified-member-password', []);
                Log::warning($FormName . " has attempted to change password via verification link too many times.");
                $returnToRoute  =   array
                                    (
                                        'name'  =>  'custom-error',
                                        'data'  =>  array('errorNumber' => 19),
                                    );
            }
        }
        else
        {
            $this->addAdminAlert();
            Log::warning($FormName . " is not being correctly posted to.");
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
				                'FormMessages'  =>  $FormMessages,
				            );
            return $this->makeResponseView('application/auth/change-password-with-verified-email-link', $viewData);
        }
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
		$this->addEmployeeStatus($lockStatus, $this->getSiteUser()->getMemberID());

        /**
         * If an email address is passed we want to use it to inform the user/member that they were locked
         */
        if($contactEmail != '')
        {
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
                if($this->isEmployeeEmailVerified($contactEmail))
                {
                    $EmployeeEmails         =   new EmployeeEmails();
                    $memberID               =   $EmployeeEmails->getEmployeeMemberIDFromEmailAddress($contactEmail);
                    $memberPriEmail         =   $EmployeeEmails->getEmployeePrimaryEmailAddressFromMemberID($memberID);
                    $EmployeeDetailsModel   =   EmployeeDetails::where('member_id', '=', $memberID)->first();
                    $sendToName             =   ($EmployeeDetailsModel->first_name != "" && $EmployeeDetailsModel->last_name != ""
	                                                ?   $EmployeeDetailsModel->first_name . " " . $EmployeeDetailsModel->last_name
	                                                :   "Ekinect Member");

                    // Lock the member
                    $this->addEmployeeStatus($lockStatus, $memberID);

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
	}

    public function getLockMessageOptions($lockStatus)
    {
        switch($lockStatus)
        {
            case 'Locked:Excessive-Login-Attempts'                      :   $messageOptionsArray    =   [
                                                                                'fromTag'       =>  'Employee Service',
                                                                                'subject'       =>  'Profile Change Notification',
                                                                                'ccArray'       =>  FALSE,
                                                                                'attachArray'   =>  FALSE,
                                                                            ];
                                                                            break;

            case 'Locked:Excessive-Signup-Attempts'                     :   $messageOptionsArray    =   [
                                                                                'fromTag'       =>  'Employee Service',
                                                                                'subject'       =>  'Profile Change Notification',
                                                                                'ccArray'       =>  FALSE,
                                                                                'attachArray'   =>  FALSE,
                                                                            ];
                                                                            break;

            case 'Locked:Excessive-ForgotLogin-Attempts'                :   $messageOptionsArray    =   [
                                                                                'fromTag'       =>  'Employee Service',
                                                                                'subject'       =>  'Profile Change Notification',
                                                                                'ccArray'       =>  FALSE,
                                                                                'attachArray'   =>  FALSE,
                                                                            ];
                                                                            break;

            case 'Locked:Excessive-ChangeVerifiedLinkPassword-Attempts' :   $messageOptionsArray    =   [
                                                                                'fromTag'       =>  'Employee Service',
                                                                                'subject'       =>  'Profile Change Notification',
                                                                                'ccArray'       =>  FALSE,
                                                                                'attachArray'   =>  FALSE,
                                                                            ];
                                                                            break;

            case 'Locked:Excessive-ChangeOldPassword-Attempts'          :   $messageOptionsArray    =   [
                                                                                'fromTag'       =>  'Employee Service',
                                                                                'subject'       =>  'Profile Change Notification',
                                                                                'ccArray'       =>  FALSE,
                                                                                'attachArray'   =>  FALSE,
                                                                            ];
                                                                            break;

            case 'Locked:Excessive-LostSignupVerification-Attempts'     :   $messageOptionsArray    =   [
                                                                                'fromTag'       =>  'Employee Service',
                                                                                'subject'       =>  'Profile Change Notification',
                                                                                'ccArray'       =>  FALSE,
                                                                                'attachArray'   =>  FALSE,
                                                                            ];
                                                                            break;


            default : throw new \Exception("Invalid Lock Status during message options retrieval.");
        }

        return $messageOptionsArray;
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
        #$verifyEmailLink    =   self::POLICY_CompanyURL_protocol . self::POLICY_CompanyURL_prd . $router . "/" . $addOn;
        $verifyEmailLink    =   (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/" . $router . "/" . $addOn;

        return $verifyEmailLink;
    }


    public function getAccessAttemptByUserIDs($accessFormName, $userIDArray, $timeFrame)
    {
        try
        {
            $AccessAttempt      =   new AccessAttempt();
            return $AccessAttempt->getAccessAttemptByUserIDs($accessFormName, $userIDArray, $timeFrame);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not get Access Attempt data. " . $e);
            return FALSE;
        }
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

    public function getEmployeeEmailStatus($emailAddress)
    {
        try
        {
            $EmailStatus    =   new EmployeeEmailStatus();
            return $EmailStatus->getEmployeeEmailStatus($emailAddress);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not get current Email Status. " . $e);
            return FALSE;
        }
    }

    public function addEmployeeEmailStatus($emailAddress, $status)
    {
        try
        {
            $EmployeeEmailStatus    =   new EmployeeEmailStatus();
            $EmployeeEmailStatus->addEmployeeEmailStatus($emailAddress, $status);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add a new Email Status. " . $e);
        }
    }

    public function checkForPreviousEmailStatus($emailAddress, $status)
    {
        try
        {
            $EmployeeEmailStatus    =   new EmployeeEmailStatus();
            return $EmployeeEmailStatus->checkForPreviousEmployeeEmailStatus($emailAddress, $status);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not check for previous Email Status. " . $e);
            return FALSE;
        }
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
                    $emailIsAlreadyVerified     =   ($this->isEmployeeEmailVerified($emailFromVcode) ? 1 : 0);
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
            $errorMsg   =   "Error #1 - EmployeeEmailsTable->isVerifyLinkValid returned an invalid member id.";
            Log::info($errorMsg);
            return  array
            (
                'errorNbr'  =>  '1',
                'errorMsg'  =>  $errorMsg,
            );
        }
    }



    public function getEmployeeMemberIDFromEmailAddress($emailAddress)
    {
        try
        {
            $EmployeeEmails   =   new EmployeeEmails();
            return $EmployeeEmails->getEmployeeMemberIDFromEmailAddress($emailAddress);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not get member id from this email address [" . $emailAddress . "]. " . $e);
            return FALSE;
        }
    }

    public function getEmployeeEmailIDFromEmailAddress($emailAddress)
    {
        try
        {
            $EmployeeEmails   =   new EmployeeEmails();
            return $EmployeeEmails->getEmployeeEmailIDFromEmailAddress($emailAddress);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not get id from this email address [" . $emailAddress . "]. " . $e);
            return FALSE;
        }
    }

    public function getMemberIDFromVerifyLink($emailAddress, $memberIDHash)
    {
        $wasEmployeeVerificationLinkSent    =   $this->wasEmployeeVerificationLinkSent($emailAddress);

        if($wasEmployeeVerificationLinkSent)
        {
            $EmployeeEmails               =   new EmployeeEmails();
            $memberID   =   $EmployeeEmails->getEmployeeMemberIDFromEmailAddress($emailAddress);
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

    public function addEmployee($newEmployeeEmail, $newMemberPassword)
    {
        try
        {
            $LoginCredentials   =   $this->generateLoginCredentials($newEmployeeEmail, $newMemberPassword);
            $NewMember          =   new Member();
            return $NewMember->addEmployee($LoginCredentials);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add a new Member identified by this email address [" . $newEmployeeEmail . "]. " . $e);
            return FALSE;
        }
    }

    public function doEmployeeDetailsExist($memberID)
    {
        try
        {
            $EmployeeDetails    =   new EmployeeDetails();
            return $EmployeeDetails->doEmployeeDetailsExist($memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add details for Member ID [" . $memberID . "] - " . $e);
            return FALSE;
        }
    }

    public function addEmployeeEmail($memberEmail, $memberID)
    {
        try
        {
            $NewEmployeeEmail    =   new EmployeeEmails();
            return $NewEmployeeEmail->addEmployeeEmail($memberEmail, $memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add email address [ " . $memberEmail . "] for Member ID [" . $memberID . "] - " . $e);
            return FALSE;
        }
    }

    public function updateEmployeeEmail($employeeEmailsID, $fillableArray)
    {
        try
        {
            $EmployeeEmail    =   new EmployeeEmails();
            return $EmployeeEmail->updateEmployeeEmail($employeeEmailsID, $fillableArray);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not update EmployeeEmails ID [" . $employeeEmailsID . "] - " . $e);
            return FALSE;
        }
    }

    public function addEmployeeDetails($memberID, $fillableArray)
    {
        try
        {
            $NewEmployeeDetail    =   new EmployeeDetails();
            return $NewEmployeeDetail->addEmployeeDetails($memberID, $fillableArray);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add details for Member Detail ID [" . $memberID . "] - " . $e);
            return FALSE;
        }
    }

    public function updateEmployeeDetails($memberID, $fillableArray)
    {
        try
        {
            $EmployeeDetails    =   new EmployeeDetails();
            return $EmployeeDetails->updateEmployeeDetails($memberID, $fillableArray);
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
}