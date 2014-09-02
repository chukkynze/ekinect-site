<?php
 /**
  * Class MemberControls
  *
  * filename:   MemberControls.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/23/14 10:21 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

trait MemberControls
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
            '900'   =>  'employees',
        );

        return $currentMemberTypes[(isset($memberTypeIdentifier) && is_numeric($memberTypeIdentifier) ? $memberTypeIdentifier : 0)];
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
}