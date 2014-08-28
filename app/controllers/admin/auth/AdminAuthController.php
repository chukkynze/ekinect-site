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
    use EmployeeControls;

    const POLICY_AllowedVerificationSeconds_Signup				=   43200;
	const POLICY_AllowedVerificationSeconds_ChangePassword		=   10800;

	const POLICY_AllowedLoginAttempts       					=   300;
	const POLICY_AllowedEmployeeLoginAttempts       		    =   300;
    const POLICY_AllowedLoginCaptchaAttempts    				=   3;
    const POLICY_AllowedSignupAttempts       					=   3;
    const POLICY_AllowedForgotAttempts       					=   3;
    const POLICY_AllowedChangeVerifiedMemberPasswordAttempts 	=   300;
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


    public function showLogin()
	{
        $authCheck  =   $this->authCheckOnAccess();
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
        $FormName       =   'EmployeeLoginForm';
        $returnToRoute  =   array
                            (
                                'name'  =>  FALSE,
                                'data'  =>  FALSE,
                            );

        $FormMessages       =   '';
        $AttemptMessages    =   '';

        if(Request::isMethod('post'))
        {
            $authCheck  =   $this->authCheckOnAccess();
            if(FALSE != $authCheck){return Redirect::route($authCheck['name']);}

            // Check if Access is allowed
            if(!$this->isAccessAllowed())
            {
                return Redirect::route('access-temp-disabled', FALSE);
            }


            $Attempts   =   $this->getAccessAttemptByUserIDs
                                    (
                                        'EmployeeLoginForm',
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
                                                                                    'exists:member_emails,email_address',
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
                        $memberID               =   $this->getMemberIDFromEmailAddress($formFields['returning_employee']);
	                    $isMemberTypeAllowed    =   $this->isMemberTypeAllowedHere($memberID);

	                    if($isMemberTypeAllowed)
	                    {
		                    $salts              =   $this->getMemberSaltFromID($memberID);
	                        $loginCredentials   =   $this->generateMemberLoginCredentials($formFields['returning_employee'], $formFields['employee_password'], $salts['salt1'], $salts['salt2'], $salts['salt3']);

	                        $this->addMemberSiteStatus("Attempting log in.", $memberID);

	                        $wasVerificationLinkSent    =   $this->wasVerificationLinkSent($formFields['returning_employee']);

	                        if($wasVerificationLinkSent)
	                        {
	                            $memberEmailIsVerified  =   $this->isEmailVerified($formFields['returning_employee']);

	                            if($memberEmailIsVerified)
	                            {
	                                // Check if Member Status is valid
	                                $isMemberStatusLocked      =   $this->isMemberStatusLocked($memberID);

	                                if(!$isMemberStatusLocked)
	                                {
	                                    // Ensure member is not required to perform a forced behaviour
	                                    $memberHasNoForce         =   $this->checkMemberHasNoForce($memberID);

	                                    if($memberHasNoForce['AttemptStatus'])
	                                    {
	                                        // Check Member Financial Status
	                                        $memberIsInGoodFinancialStanding		=	$this->checkMemberEmploymentStatus();

	                                        if($memberIsInGoodFinancialStanding['AttemptStatus'])
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
	                                                $authCheck  =   $this->authCheckOnAccess();
	                                                if(FALSE != $authCheck)
	                                                {
	                                                    $this->addMemberSiteStatus("Successfully logged in.", $memberID);
		                                                // todo: Send email stating you have logged in
	                                                    return Redirect::route($authCheck['name']);
	                                                }
	                                            }
	                                            else
	                                            {
	                                                $FormMessages   =   array();
	                                                $FormMessages[] =   "Unfortunately, we do not recognize your login credentials. Please retry.";

	                                                $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 0);
	                                                Log::info($FormName . " - form values did not pass.");
	                                            }
	                                        }
	                                        else
	                                        {
	                                            $this->registerAccessAttempt($this->getSiteUser()->getID(), $FormName, 0);
	                                            $this->addAdminAlert();
	                                            Log::warning($FormName . " member financials are not in order.");
	                                            $returnToRoute  =   array
	                                                                (
	                                                                    'name'  =>  'custom-error',
	                                                                    'data'  =>  array('errorNumber' => 26),
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
	                                $FormMessages[] =   "You must validate your email address before you can log in. Please, check your inbox.";
	                                Log::info($FormName . " - email address is not valid.");
	                            }
	                        }
	                        else
	                        {
	                            $FormMessages   =   array();
	                            $FormMessages[] =   "Your email address isn't recognized as valid. Signup first or, login with a previous email and validate this one.";
	                            Log::info($FormName . " - email address verification not sent.");
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
	            case 'employees'       :
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
	 * This is the catch all method for the policies affecting whether a employees is allowed access.
	 * It also takes into consideration reasons to lock the site that may go beyond just a single employees/user
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

		if($this->isUserAllowedAccess())
		{
			$returnValue	=	TRUE;
		}

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
    public function checkMemberHasNoForce($memberID)
	{
		try
        {
            $MemberStatus    =   new MemberStatus();
            return $MemberStatus->checkMemberHasNoForce($memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not check if member [ " . $memberID . " ] has a forced requirement. " . $e);
            return FALSE;
        }





	}


	public function checkMemberEmploymentStatus()
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
	 * This method determines if the member id is allowed access
     * and is only checked upon validating that the login creds are valid and correct
	 *
     * @param $memberID
     *
     * @return bool
     */
    public function isMemberStatusLocked($memberID)
	{
		try
        {
            $MemberStatus    =   new MemberStatus();
            return $MemberStatus->isMemberStatusLocked($memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not check if member [ " . $memberID . " ] status is locked. " . $e);
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
		$BlockedMemberStatuses 	=	array
									(
										'Locked:Excessive-EmployeeLogin-Attempts',
									);

		return (	$this->getUser()->getUserMemberID()*1 > 0
				&& 	!in_array
					(
						$this->getMemberStatusTable()->getMemberStatusByMemberID($this->getUser()->getUserMemberID()),
						$BlockedMemberStatuses
					)
					? 	TRUE
					: 	FALSE);
	}


    public function logout()
    {
		Auth::logout();
        #return Redirect::route('memberLogout',array());
    }

    public function loginAgain()
    {
        $this->activity     =   "login";
        $this->reason       =   "expired-session";
        return $this->showLogin();
    }

    public function successfulLogout()
    {
        $this->activity     =   "login";
        $this->reason       =   "intentional-logout";
        return $this->showLogin();
    }

    public function successfulAccessCredentialChange()
    {
        $this->activity     =   "login";
        $this->reason       =   "changed-password";
        return $this->showLogin();
    }

    public function loginCaptcha()
    {
        $this->activity     =   "login-captcha";
        $this->reason       =   "";
        return $this->showLogin();
    }

    public function memberLogout()
    {
        $this->logout();
        return $this->makeResponseView('admin/members/member-logout', array());
    }

    public function memberLogoutExpiredSession()
    {
        $this->logout();

		// return $this->redirect()->toRoute('member-login-after-expired-session');
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
                                                            'member_type'   =>  $this->getMemberTypeFromFromValue(strtolower($formFields['member_type'])),
                                                        );
                            $this->updateMember($verifiedMemberIDArray['memberID'], $memberFillableArray);
                            $this->addMemberStatus('VerifiedStartupDetails', $verifiedMemberIDArray['memberID']);
                            $this->addMemberStatus('ValidMember', $verifiedMemberIDArray['memberID']);
                            $this->addMemberSiteStatus('Member startup details complete.', $verifiedMemberIDArray['memberID']);

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

                            return  $this->makeResponseView('admin/auth/verification-details-success', $viewData);
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
            return  $this->makeResponseView('admin/auth/verified_email_success', $viewData);
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
        $returnToRoute          =   array
                                    (
                                        'name'  =>  FALSE,
                                        'data'  =>  FALSE,
                                    );

        /**
         * Must return both email and member id bc a member can have more than one email address
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
            return $this->makeResponseView('admin/auth/verified_email_success', $viewData);
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
        return $this->makeResponseView('admin/auth/change-password-with-verified-email-link', $viewData);
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
                                                                                            'exists:member_emails,email_address',
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
		                                if($this->getEmailStatus($vcodeDetails['email']) == 'Forgot')
		                                {
			                                $this->addEmailStatus($vcodeDetails['email'], 'Remembered');

			                                $LoginCredentials       =   $this->generateLoginCredentials($vcodeDetails['email'], $formFields['password']);
					                        $memberFillableArray    =   array
					                                                    (
					                                                        'password'          =>  Hash::make($LoginCredentials[0]),
					                                                        'salt1'             =>  $LoginCredentials[1],
					                                                        'salt2'             =>  $LoginCredentials[2],
					                                                        'salt3'             =>  $LoginCredentials[3],
					                                                    );
                                            $this->updateMember($vcodeDetails['memberID'], $memberFillableArray);
                                            $this->addMemberStatus("ChangedPassword", $vcodeDetails['memberID']);
                                            $this->addMemberStatus("ValidMember", $vcodeDetails['memberID']);
                                            $this->addMemberSiteStatus("Member has changed their password.", $vcodeDetails['memberID']);

			                                $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 1);

			                                $successMessage[]       =   'Congratulations. You have successfully changed your password!';
                                            Session::put('successFlashMessage', $successMessage);

			                                $MemberDetailsObject    =   $this->getMemberDetailsFromMemberID($vcodeDetails['memberID']);
			                                $sendEmailStatus        =   $this->sendEmail
				                                                        (
				                                                            'genericPasswordChange',
				                                                            array
				                                                            (
				                                                                'first_name' 	=> 	$MemberDetailsObject->getMemberDetailsFirstName(),
																				'last_name' 	=> 	$MemberDetailsObject->getMemberDetailsLastName(),
				                                                            ),
				                                                            array
				                                                            (
				                                                                'fromTag'       =>  'General',
				                                                                'sendToEmail'   =>  $vcodeDetails['email'],
				                                                                'sendToName'    =>  $MemberDetailsObject->getMemberDetailsFullName(),
				                                                                'subject'       =>  'Password Reset',
				                                                                'ccArray'       =>  FALSE,
				                                                                'attachArray'   =>  FALSE,
				                                                            )
				                                                        );

			                                $viewData   =   array
		                                                    (
		                                                        'emailAddress'        =>  $vcodeDetails['email'],
		                                                    );
		                                    return $this->makeResponseView('admin/auth/reset-verified-password-success', $viewData);

		                                }
		                                else
		                                {
			                                $FormMessages[]   =   'Your new access credentials can not be updated at this time. Please retry the link or contact Customer Service';
			                                $this->registerAccessAttempt($this->getSiteUser()->getID(),$FormName, 0);
		                                    Log::info($FormName . " - getEmailStatusStatus != 'Forgot'");
		                                }
	                                }
	                                else
	                                {
		                                $FormMessages[]   =   'Your new access credentials can not be updated at this time. Please retry the link or contact Customer Service';
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
            $viewData   =   array(
                'vcode'         =>  Input::get('vcode'),
                'FormMessages'  =>  $FormMessages,
            );
            return $this->makeResponseView('admin/auth/change-password-with-verified-email-link', $viewData);
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
		$this->addMemberStatus($lockStatus, $this->getSiteUser()->getMemberID());

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
                    $this->addMemberStatus($lockStatus, $memberID);

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
            case 'Locked:Excessive-EmployeeLogin-Attempts'                      :   $messageOptionsArray    =   [
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

    public function getEmailStatus($emailAddress)
    {
        try
        {
            $EmailStatus    =   new EmailStatus();
            return $EmailStatus->getEmailStatus($emailAddress);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not get current Email Status. " . $e);
            return FALSE;
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

    public function checkForPreviousEmailStatus($emailAddress, $status)
    {
        try
        {
            $EmailStatus    =   new EmailStatus();
            return $EmailStatus->checkForPreviousEmailStatus($emailAddress, $status);
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



    public function getMemberIDFromEmailAddress($emailAddress)
    {
        try
        {
            $MemberEmails   =   new MemberEmails();
            return $MemberEmails->getMemberIDFromEmailAddress($emailAddress);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not get member id from this email address [" . $emailAddress . "]. " . $e);
            return FALSE;
        }
    }

    public function getMemberEmailIDFromEmailAddress($emailAddress)
    {
        try
        {
            $MemberEmails   =   new MemberEmails();
            return $MemberEmails->getMemberEmailIDFromEmailAddress($emailAddress);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not get id from this email address [" . $emailAddress . "]. " . $e);
            return FALSE;
        }
    }

    public function getMemberIDFromVerifyLink($emailAddress, $memberIDHash)
    {
        $wasVerificationLinkSent    =   $this->wasVerificationLinkSent($emailAddress);

        if($wasVerificationLinkSent)
        {
            $MemberEmails               =   new MemberEmails();
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