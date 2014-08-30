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

    public function getEmployeeDetailsFromMemberID($memberID)
    {
        try
        {
            return EmployeeDetails::where('member_id', '=', $memberID)->first();
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not get Employee details for Member ID [" . $memberID . "] - " . $e);
            return FALSE;
        }
    }

    public function isEmployeeEmailVerified($email)
    {
        try
        {
            $EmployeeEmails   =   new EmployeeEmails();
            return $EmployeeEmails->isEmployeeEmailVerified($email);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not verify this Employee email address [" . $email . "]. " . $e);
            return FALSE;
        }
    }

    public function wasEmployeeVerificationLinkSent($emailAddress)
    {
        // todo: not necessary for employees
        // we set up there email address and give them a password
        // remove this check
        try
        {
            $MemberEmails               =   new MemberEmails();
            return $MemberEmails->wasVerificationLinkSent($emailAddress);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not determine if Employee verification link was sent for [" . $emailAddress . "] - " . $e);
            return FALSE;
        }
    }

    public function authCheckAfterAccess()
    {
        if (!Auth::check())
        {
            return $this->makeResponseView("admin/employees/employee-logout", array());
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
            $memberEmail    =   $this->getEmployeeEmailAddressFromMemberID($memberID);

            if($memberID >= 1)
            {
                switch($memberType)
                {
                    case 'employees'         :   $returnToRoute  =   array
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

    public function getEmployeeEmailAddressFromMemberID($memberID=0)
    {
        $memberID       =   (isset($this->memberID) ? $this->memberID : $memberID);
        $EmployeeEmails       =   new EmployeeEmails();
        return $EmployeeEmails->getEmailAddressFromMemberID($memberID);
    }


    public function addEmployeeStatus($status, $memberID)
    {
        try
        {
            $NewEmployeeStatus    =   new EmployeeStatus();
            return $NewEmployeeStatus->addEmployeeStatus($status, $memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add the new Employee Status [" . $status . "] for MemberID [" . $memberID . "]. " . $e);
            return FALSE;
        }
    }
}