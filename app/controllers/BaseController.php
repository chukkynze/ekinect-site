<?php

class BaseController extends Controller
{
    const POLICY_CompanyURL_protocol            =   'http://';
    const POLICY_CompanyURL_loc                 =   'local.freelife.com/';
    const POLICY_CompanyURL_dev                 =   'dev.freelife.com/';
    const POLICY_CompanyURL_stg                 =   'stg.freelife.com/';
    const POLICY_CompanyURL_prd                 =   'www.ekinect.me/';

    const POLICY_CookiePrefix                   =   'ekinect_';
    const POLICY_EncryptedURLDelimiter          =   ':ekt:';
    const POLICY_UserIDCookieDurationMinutes    =   525600;

    const POLICY_LinkTechnicalSupport           =   "<a href='mailto:technicalsupport@ekinect.com?subject=Error:[errorNumber]'>Technical Support</a>";
    const POLICY_LinkCustomerService            =   "<a href='mailto:customersupport@ekinect.com?subject=Error:[errorNumber]'>Customer Support</a>";

    public $SiteUser;
    public $SiteUserCookie;
    public $SiteHit;


    /**
     *
     */
    public function setSiteHit()
    {
        $allCookies   =   array();
        foreach($_COOKIE as $cKey => $cValue)
        {
            if(strpos($cKey,self::POLICY_CookiePrefix, 0) >= 0)
            {
                $allCookies[$cKey]    =   $cValue;
            }
        }

        $newSiteHit    =   SiteHit::create(
                                        array
                                        (
                                            'user_id'       =>  $this->getSiteUser()->getId(),
                                            'cookies'       =>  json_encode($allCookies),
                                            'url_location'  =>  Request::path(),
                                            'client_time'   =>  0,
                                            'server_time'   =>  strtotime('now'),
                                        ));
        $newSiteHit->save();

        $this->SiteHit = $newSiteHit;
    }

    /**
     * @return mixed
     */
    public function getSiteHit()
    {
        return $this->SiteHit;
    }



    /**
     * @return bool|\Illuminate\Database\Eloquent\Model|static
     */
    public function setSiteUser()
    {
        $newSiteUser    =   SiteUser::create(
                                        array
                                        (
                                            'member_id'     =>  0,
                                            'agent'         =>  $_SERVER['HTTP_USER_AGENT'],
                                            'ip_address'    =>  sprintf('%u', ip2long($_SERVER['REMOTE_ADDR'])),
                                            'user_status'   =>  'Open',
                                        ));
        $newSiteUser->save();

        $this->SiteUser         =   $newSiteUser;
        $this->SiteUserCookie   =   Cookie::make('ekinect_uid', urlencode($this->SiteUser->getId()), self::POLICY_UserIDCookieDurationMinutes, '/', $_SERVER['SERVER_NAME'], 0, 0);

        return ( is_object($this->SiteUser) ? $this->SiteUser : FALSE );
    }

    /**
     * @return mixed
     */
    public function getSiteUser()
    {
        $siteUserID   =   (int) Cookie::get('ekinect_uid');
        if(isset($siteUserID) && $siteUserID > 0)
        {
            $siteUser   =   SiteUser::find($siteUserID);
            $this->SiteUserCookie = $siteUserID;

            if(FALSE != $siteUser && is_object($siteUser))
            {
                $this->SiteUser =   $siteUser;
                return $this->SiteUser;
            }
        }

        return $this->setSiteUser();
    }



    public function addAdminAlert()
    {
        // todo: Add an Admin Alert for certain issues
    }





	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    public function makeResponseView($viewName, $viewData)
    {
        return  is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
                    ?   Response::make(View::make($viewName, $viewData))
                    :   Response::make(View::make($viewName, $viewData))->withCookie($this->SiteUserCookie);
    }

	/**
	 * Checks if form is clean
     *
     * This could mean:
     * 1. form has been populated by robots
     * 2.
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

                case 'EmployeeLoginForm'            	        :   $dummyInput     =   array
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

                case 'VerificationDetailsForm'     	            :   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'LostSignupVerificationForm'     	        :   $dummyInput     =   array
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

																						);
																	break;


                default  :   $dummyInput     =	array
												(
													'false'     =>  'FALSE',
												);
            }

            if(count($dummyInput) >= 1)
            {
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
                $returnValue    =   TRUE;
            }
        }
        else
        {
            Log::info("Variable formValues is not an array.");
            $returnValue    =   FALSE;
        }

        return $returnValue;
    }

    public function changeArrayFormat($inputArray, $outputFormat)
    {
        switch($outputFormat)
        {
            case 'array'    :   $output =   $inputArray; break;
            case 'json'     :   $output =   json_encode($inputArray); break;

            /**
             * Uses Array2XML
             * app/controllers/library/Array2XML.php
             */
            case 'xml'      :   $xml    =   Array2XML::createXML('restaurant', $inputArray);
                                $output =   $xml->saveXML();
                                break;

            case 'string'   :   $output =  serialize($inputArray); break;
            case 'text'     :   $output =  serialize($inputArray); break;

            default : $output = FALSE;
        }

        return $output;
    }

}
