<?php
 /**
  * Class EmployeeControls
  *
  * filename:   EmployeeControls.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/23/14 10:21 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

trait EmployeeControls
{

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

    public function getMemberTypeFromFromValue($memberTypeIdentifier)
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

    public function getMemberDetailsFromMemberID($memberID)
    {
        try
        {
            return MemberDetails::where('member_id', '=', $memberID)->first();
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not get member details for Member ID [" . $memberID . "] - " . $e);
            return FALSE;
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

    public function getMemberSaltFromID($memberID)
    {
        try
        {
            $Member   =   new Member();
            return $Member->getMemberSaltFromID($memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not get member salts from id [" . $memberID . "]. " . $e);
            return FALSE;
        }
    }

    public function wasVerificationLinkSent($emailAddress)
    {
        try
        {
            $MemberEmails               =   new MemberEmails();
            return $MemberEmails->wasVerificationLinkSent($emailAddress);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not determine if verification link was sent for [" . $emailAddress . "] - " . $e);
            return FALSE;
        }
    }

    public function makeResponseView($viewName, $viewData)
    {
        return  Response::make(View::make($viewName, $viewData));
    }

    public function authCheckAfterAccess()
    {
        if (!Auth::check())
        {
            return $this->makeResponseView("application/members/member-logout", array());
        }
    }


	/**
	 * @return array|bool
	 */
	public function authCheckOnAccess()
    {
        if (Auth::check())
        {
            $memberID       =   Auth::id();
            $memberType     =   Auth::user()->member_type;
            $memberEmail    =   $this->getPrimaryEmailAddressFromMemberID($memberID);

            if($memberID >= 1)
            {
                switch($memberType)
                {
                    case 'employee'         :   $returnToRoute  =   array
                                                                (
                                                                    'name'  =>  'showEmployeeDashboard',
                                                                );
                                                break;

                    default :   Session::put('memberLogoutMessage', 'We did not recognize your member type.');
                                Auth::logout();
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

    public function getPrimaryEmailAddressFromMemberID($memberID=0)
    {
        $memberID       =   (isset($this->memberID) ? $this->memberID : $memberID);
        $MemberEmails       =   new MemberEmails();
        return $MemberEmails->getPrimaryEmailAddressFromMemberID($memberID);
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
}