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

    public function authCheckAfterEmployeeAccess()
    {
        if (!Auth::check())
        {
            return $this->makeResponseView("admin/employees/employee-logout", array());
        }
    }


	/**
	 * @return array|bool
	 */
	public function authCheckOnEmployeeAccess()
    {
        if (Auth::check())
        {
            $memberID           =   Auth::id();
            $memberType         =   Auth::user()->member_type;
            $employeeDetails    =   $this->getEmployeeDetailsFromMemberID($memberID);


            if($memberID >= 1)
            {
	            if($employeeDetails->department != '' && $memberType == 'employee')
	            {
		            switch($employeeDetails->department)
	                {
	                    case 'SuperUser'    :   $returnToRoute  =   array('name'  =>  'showSuperUserDashboard');    break;
	                    case 'Executive'    :   $returnToRoute  =   array('name'  =>  'showExecutiveDashboard');    break;
	                    case 'Financial'    :   $returnToRoute  =   array('name'  =>  'showFinancialDashboard');    break;
	                    case 'Tech'         :   $returnToRoute  =   array('name'  =>  'showTechDashboard');         break;

	                    default :   Session::put('employeeLogoutMessage', 'We do not recognize you.');
	                                Auth::logout();
	                                $returnToRoute  =   array
	                                                    (
	                                                        'name'  =>  'employeeLogout',
	                                                        'data'  =>  array(),
	                                                    );
	                }
	            }
	            else
	            {
		            Session::put('employeeLogoutMessage', 'Incorrect access point.');
                    Auth::logout();
                    $returnToRoute  =   array
                                        (
                                            'name'  =>  'employeeLogout',
                                            'data'  =>  array(),
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
        $memberID           =   (isset($this->employeeID) ? $this->employeeID : $memberID);
        $EmployeeEmails     =   new EmployeeEmails();
        return $EmployeeEmails->getEmployeeEmailAddressFromMemberID($memberID);
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


    public function addEmployeeSiteStatus($status, $memberID)
    {
        try
        {
            $NewEmployeeSiteStatus    =   new EmployeeSiteStatus();
            return $NewEmployeeSiteStatus->addEmployeeSiteStatus($status, $memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add the new Employee Site Status [" . $status . "] for MemberID [" . $memberID . "]. " . $e);
            return FALSE;
        }
    }
}