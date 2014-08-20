<?php
 /**
  * Class AbstractMemberController
  *
  * filename:   AbstractMemberController.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/16/14 12:31 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class AbstractMemberController extends BaseController
{
    public $memberID;

    public function __construct()
    {
        $this->authCheckAfterAccess();
    }

    public function getMemberDetailsFromMemberID($memberID)
    {
        try
        {
            // Use Member ID to get pri key
            $MemberDetails    =   new MemberDetails();
            return $MemberDetails->getMemberDetailsFromMemberID($memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not get member details for Member ID [" . $memberID . "] - " . $e);
            return FALSE;
        }
    }

    public function getPrimaryKeyFromMemberID($modelName)
    {
        if($modelName == 'MemberDetails')
        {
            $Model      =   new $modelName();
            $primaryKey =   $Model->getPrimaryKeyByMemberID($this->memberID);
        }
        else
        {
            $primaryKey =   0;
        }

        return $primaryKey;
    }

    public function getMemberDetailsObject($primaryKey)
    {
        return (isset($primaryKey) && is_numeric($primaryKey) && $primaryKey >= 1 ? MemberDetails::find($primaryKey) : FALSE);
    }

    public function memberLogout()
    {
        // perform generic member activities before logging out


        // Actual Logout
        Auth::logout();
    }
}