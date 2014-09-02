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
    use MemberControls;
    use EmployeeControls;
    use CustomerControls;

    public $memberID;
    public $memberType;



    public function __construct()
    {
        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit

        $this->memberID             =   Auth::id();
        $this->memberType           =   Auth::user()->member_type;

	    if($this->memberType == 'employee')
	    {
		    $this->authCheckAfterEmployeeAccess();
	    }
	    else
	    {
		    $this->authCheckAfterCustomerAccess();
	    }
    }

    public function getPrimaryKeyUsingMemberID($modelName)
    {
        $Model      =   new $modelName();
        return $Model->getPrimaryKeyUsingMemberID($this->memberID, $modelName);
    }
}