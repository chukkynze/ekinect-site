<?php
 /**
  * Class AbstractCustomerController
  *
  * filename:   AbstractCustomerController.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/16/14 12:31 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class AbstractCustomerController extends AbstractMemberController
{
    use CustomerControls;

    public $customerID;
    public $customerType;
    public $customerDetails;
    public $customerDetailsID;
    public $customerPrimaryEmail;


    public function __construct()
    {
        parent::__construct();

        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit

        $this->customerID               =   Auth::id();
        $this->customerType             =   Auth::user()->member_type;
        $this->customerDetailsID        =   $this->getPrimaryKeyUsingMemberID("CustomerDetails");
        $this->customerDetails          =   $this->getCustomerDetailsObject();
        $this->customerPrimaryEmail     =   $this->getCustomerPrimaryEmailAddressFromMemberID();
    }

    public function getCustomerDetailsObject()
    {
        return (isset($this->customerDetailsID) && is_numeric($this->customerDetailsID) && $this->customerDetailsID >= 1
	        ?   CustomerDetails::find($this->customerDetailsID)
	        :   FALSE);
    }

    public function customerLogout()
    {
        // perform generic customer activities before logging out


        // Actual Logout
        Auth::logout();
    }
}