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

    public $memberID;
        public $memberType;

    public $memberDetailsID;
        public $memberNamePrefix;
        public $memberFirstName;
        public $memberMidName1;
        public $memberMidName2;
        public $memberLastName;
        public $memberFullName;
        public $memberDisplayName;
        public $memberNameSuffix;

        public $memberGender;
        public $memberGenderRaw;
        public $memberBirthDate;

        public $memberPersonalSummary;

        public $memberLargeProfilePicUrl;
        public $memberMediumProfilePicUrl;
        public $memberSmallProfilePicUrl;
        public $memberXSmallProfilePicUrl;

        public $memberPersonalWebsiteLink;
        public $memberSocialLinkLinkedIn;
        public $memberSocialLinkGooglePlus;
        public $memberSocialLinkTwitter;
        public $memberSocialLinkFacebook;

        public $memberHomeLink;
        public $memberProfileLink;

    public $memberPrimaryEmail;



    public function __construct()
    {
        $this->authCheckAfterAccess();

        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit

        $this->memberID             =   Auth::id();
        $this->memberDetailsID      =   $this->getPrimaryKeyUsingMemberID("MemberDetails");
        $this->memberPrimaryEmail   =   $this->getPrimaryEmailAddressFromMemberID();
    }

    public function getPrimaryKeyUsingMemberID($modelName)
    {
        if($modelName == 'MemberDetails')
        {
            $Model      =   new $modelName();
            $primaryKey =   $Model->getPrimaryKeyUsingMemberID($this->memberID);
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
        $this->addMemberSiteStatus("Successfully logged out.", $this->memberID);

        // Actual Logout
        Auth::logout();
    }
}