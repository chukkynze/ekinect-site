<?php
 /**
  * Class CustomerControls
  *
  * filename:   CustomerControls.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/23/14 10:21 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

trait CustomerControls
{

    public function getCustomerDetailsFromMemberID($memberID)
    {
        try
        {
            return CustomerDetails::where('member_id', '=', $memberID)->first();
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not get Customer details for Member ID [" . $memberID . "] - " . $e);
            return FALSE;
        }
    }

    public function isCustomerEmailVerified($email)
    {
        try
        {
            $MemberEmails   =   new CustomerEmails();
            return $MemberEmails->isCustomerEmailVerified($email);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not verify this Customer email address [" . $email . "]. " . $e);
            return FALSE;
        }
    }

    public function wasCustomerVerificationLinkSent($emailAddress)
    {
        try
        {
            $CustomerEmails               =   new CustomerEmails();
            return $CustomerEmails->wasCustomerVerificationLinkSent($emailAddress);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not determine if customer verification link was sent for [" . $emailAddress . "] - " . $e);
            return FALSE;
        }
    }

    public function authCheckAfterCustomerAccess()
    {
        if (!Auth::check())
        {
            return $this->makeResponseView("application/customers/customer-logout", array());
        }
    }

    public function authCheckOnCustomerAccess()
    {
        if (Auth::check())
        {
            $memberID       =   Auth::id();
            $memberType     =   Auth::user()->member_type;
            $memberEmail    =   $this->getCustomerPrimaryEmailAddressFromMemberID($memberID);

            if($memberID >= 1)
            {
                switch($memberType)
                {
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

                    default :   $verifyEmailLink    =   $this->generateVerifyEmailLink($memberEmail, $memberID, 'verify-new-member');
                                Session::put('customerLogoutMessage', 'We did not recognize your member type. Please ensure your verification process is complete by <a href="' . $verifyEmailLink . '">completing your verification details.</a>.');
                                Auth::logout();
                                $returnToRoute  =   array
                                                    (
                                                        'name'  =>  'customerLogout',
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

    public function getCustomerPrimaryEmailAddressFromMemberID($memberID=0)
    {
        $memberID       =   (isset($this->customerID) ? $this->customerID : $memberID);
        $CustomerEmails =   new CustomerEmails();
        return $CustomerEmails->getCustomerPrimaryEmailAddressFromMemberID($memberID);
    }


    public function addCustomerStatus($status, $memberID)
    {
        try
        {
            $NewCustomerStatus    =   new CustomerStatus();
            return $NewCustomerStatus->addCustomerStatus($status, $memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add the new Customer Status [" . $status . "] for MemberID [" . $memberID . "]. " . $e);
            return FALSE;
        }
    }


    public function addCustomerSiteStatus($status, $memberID)
    {
        try
        {
            $NewCustomerSiteStatus    =   new CustomerSiteStatus();
            return $NewCustomerSiteStatus->addCustomerSiteStatus($status, $memberID);
        }
        catch(\Whoops\Example\Exception $e)
        {
            Log::error("Could not add the new Customer Site Status [" . $status . "] for MemberID [" . $memberID . "]. " . $e);
            return FALSE;
        }
    }
}