<?php
 /**
  * Class AbstractEmployeeController
  *
  * filename:   AbstractEmployeeController.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/16/14 12:31 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class AbstractEmployeeController extends AbstractMemberController
{
    use EmployeeControls;

    public $employeeID;
    public $employeeType;
    public $employeeDetails;
    public $employeeDetailsID;
    public $employeePrimaryEmail;


    public function __construct()
    {
        parent::__construct();

        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit

        $this->employeeID               =   Auth::id();
        $this->employeeType             =   Auth::user()->member_type;
        $this->employeeDetailsID        =   $this->getPrimaryKeyUsingMemberID("EmployeeDetails");
        $this->employeeDetails          =   $this->getEmployeeDetailsObject();
        $this->employeePrimaryEmail     =   $this->getEmployeeEmailAddressFromMemberID();
    }

    public function getEmployeeDetailsObject()
    {
        return (isset($this->employeeDetailsID) && is_numeric($this->employeeDetailsID) && $this->employeeDetailsID >= 1
	        ?   EmployeeDetails::find($this->employeeDetailsID)
	        :   FALSE);
    }

    public function employeeLogout()
    {
        // perform generic employee activities before logging out


        // Actual Logout
        Auth::logout();
    }
}