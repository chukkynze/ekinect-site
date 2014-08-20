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

    protected function createHash($val,$key)
    {
        $hash   =   hash_hmac('sha512', $val, $key);
        return $hash;
    }

    /**
     *
     * Encrypt/Decrypt function
     * Note strings should already hashed, salted and md5ed or sha1ed before even thinking of using this
     *
     * @param           $mode 'e'|'d' ==> encrypt|decrypt
     * @param           $string_to_convert
     * @param           $key
     *
     * @return array|bool|string
     */
    public function twoWayCrypt($mode, $string_to_convert, $key)
    {
        $encryptionMethod   =   "AES-256-CBC";
        $raw_output         =   FALSE;

        if($mode === "e")
        {
            // Encrypt
            $iv             =   mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CBC), MCRYPT_RAND);
            return  $iv . self::POLICY_EncryptedURLDelimiter . openssl_encrypt($string_to_convert, $encryptionMethod, $key, $raw_output, $iv);
        }
        elseif($mode === "d")
        {
            // Decrypt
            $expld          =   explode(self::POLICY_EncryptedURLDelimiter, $string_to_convert);
            return  openssl_decrypt($expld[1], $encryptionMethod, $key, $raw_output, $expld[0]);
        }
        else
        {
            return FALSE;
        }
    }

    public function makeResponseView($viewName, $viewData)
    {
        return  is_int($this->SiteUserCookie) && $this->SiteUserCookie > 0
                    ?   Response::make(View::make($viewName, $viewData))
                    :   Response::make(View::make($viewName, $viewData))->withCookie($this->SiteUserCookie);
    }

    public function getMemberTypeFromFormValue($memberTypeIdentifier)
    {
        $currentMemberTypes =   array(
            '0'     =>  'unknown',
            '1'     =>  'vendor',
            '2'     =>  'freelancer',
            '3'     =>  'vendor-client',
            '4'     =>  'report-viewer',
            '900'   =>  'employee',
        );

        return $currentMemberTypes[(isset($memberTypeIdentifier) && is_numeric($memberTypeIdentifier) ? $memberTypeIdentifier : 0)];
    }


    public function failedAuthCheck()
    {

    }


    public function authCheckAfterAccess()
    {
        if (!Auth::check())
        {
            return $this->makeResponseView("application/members/member-logout", array());
        }
    }

    public function authCheckOnAccess()
    {
        if (Auth::check())
        {
            $memberID       =   Auth::id();
            $memberType     =   Auth::user()->member_type;

            if($memberID >= 1)
            {
                switch($memberType)
                {
                    case 'employee'         :   $returnToRoute  =   array
                                                                (
                                                                    'name'  =>  'employeeCheckBeforeAccess',
                                                                );
                                                break;

                    case 'vendor'           :   $returnToRoute  =   array
                                                                (
                                                                    'name'  =>  'showVendorDashboard',
                                                                );
                                                break;

                    case 'vendor-client'    :   $returnToRoute  =   array
                                                                (
                                                                    'name'  =>  'showVendorClientDashboard',
                                                                );
                                                break;

                    case 'freelancer'       :   $returnToRoute  =   array
                                                                (
                                                                    'name'  =>  'showFreelancerDashboard',
                                                                );
                                                break;

                    default :   Auth::logout();
                                $returnToRoute  =   array
                                                    (
                                                        'name'  =>  'memberLogout',
                                                        'data'  =>  array
                                                                    (
                                                                        'memberID'  =>  $memberID
                                                                    ),
                                                    );
                }
            }
            else
            {
                $returnToRoute  =   FALSE;
            }
        }
        else
        {
            $returnToRoute  =   FALSE;
        }

        return $returnToRoute;
    }




}
