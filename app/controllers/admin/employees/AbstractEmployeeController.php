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
 

class AbstractEmployeeController extends BaseController
{
    use EmployeeControls;

    public $employeeID;
        public $employeeType;

    public $employeeDetailsID;
        public $employeeNamePrefix;
        public $employeeFirstName;
        public $employeeMidName1;
        public $employeeMidName2;
        public $employeeLastName;
        public $employeeFullName;
        public $employeeDisplayName;
        public $employeeNameSuffix;

        public $employeeGender;
        public $employeeGenderRaw;
        public $employeeBirthDate;

        public $employeePersonalSummary;

        public $employeeLargeProfilePicUrl;
        public $employeeMediumProfilePicUrl;
        public $employeeSmallProfilePicUrl;
        public $employeeXSmallProfilePicUrl;

        public $employeePersonalWebsiteLink;
        public $employeeSocialLinkLinkedIn;
        public $employeeSocialLinkGooglePlus;
        public $employeeSocialLinkTwitter;
        public $employeeSocialLinkFacebook;

        public $employeeHomeLink;
        public $employeeProfileLink;

    public $employeePrimaryEmail;



    public function __construct()
    {
        $this->authCheckAfterAccess();

        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit

        $this->employeeID             =   Auth::id();
        $this->employeeDetailsID      =   $this->getPrimaryKeyUsingEmployeeID("EmployeeDetails");
        $this->employeePrimaryEmail   =   $this->getPrimaryEmailAddressFromEmployeeID();
    }

    public function getPrimaryKeyUsingEmployeeID($modelName)
    {
        if($modelName == 'EmployeeDetails')
        {
            $Model      =   new $modelName();
            $primaryKey =   $Model->getPrimaryKeyUsingEmployeeID($this->employeeID);
        }
        else
        {
            $primaryKey =   0;
        }

        return $primaryKey;
    }

    public function getEmployeeDetailsObject($primaryKey)
    {
        return (isset($primaryKey) && is_numeric($primaryKey) && $primaryKey >= 1 ? EmployeeDetails::find($primaryKey) : FALSE);
    }

    public function employeeLogout()
    {
        // perform generic employee activities before logging out
        $this->addEmployeeSiteStatus("Successfully logged out.", $this->employeeID);

        // Actual Logout
        Auth::logout();
    }
}