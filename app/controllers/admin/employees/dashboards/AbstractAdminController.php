<?php
/**
 * Class AbstractAdminController
 *
 * filename:   AbstractAdminController.php
 *
 * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
 * @since       7/8/14 5:19 AM
 *
 * @copyright   Copyright (c) 2014 www.eKinect.com
 */


class AbstractAdminController extends BaseController
{
	use EmployeeControls;

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

    public $layoutData;
    public $viewRootFolder = 'admin/employees/';

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.employees-cloud';


    public function __construct()
    {

        $this->layoutData         =   $this->getCloudLayoutVariables("array");
    }


    /**
     * @param \Illuminate\View\View $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getLayout()
    {
        return $this->layout;
    }


    public function employeeLogout()
    {
        // perform generic member activities before logging out
        $this->addMemberSiteStatus("Successfully logged out employees.", $this->memberID);

        // Actual Logout
        Auth::logout();

        // Redirect to the logged out page
        return $this->makeResponseView('admin/employees/employees-logout', array());
    }



    public function forceEmployeeLogout()
    {
        // Redirect to the logged out page
        return  array
        (
            'name'  =>  'employeeLogout',
            'data'  =>  array(),
        );
    }


    public function getCloudLayoutVariables($outputFormat="array")
    {
        $MemberDetailsObject	            =	$this->getMemberDetailsObject($this->memberDetailsID);

        /**
         * Update Class Properties
         */
        $this->memberNamePrefix   			=   $MemberDetailsObject->getMemberDetailsPrefix("text");
        $this->memberFirstName   			=   $MemberDetailsObject->getMemberDetailsFirstName();
        $this->memberMidName1   			=   $MemberDetailsObject->getMemberDetailsMidName1();
        $this->memberMidName2  				=   $MemberDetailsObject->getMemberDetailsMidName2();
        $this->memberLastName   			=   $MemberDetailsObject->getMemberDetailsLastName();
        $this->memberFullName				=	$MemberDetailsObject->getMemberDetailsFullName();
        $this->memberDisplayName			=	$MemberDetailsObject->getMemberDetailsDisplayName();
        $this->memberNameSuffix				=	$MemberDetailsObject->getMemberDetailsSuffix("text");

        $this->memberGender					=	$MemberDetailsObject->getMemberDetailsGender('text');
        $this->memberGenderRaw				=	$MemberDetailsObject->getMemberDetailsGender('raw');
        $this->memberBirthDate				=	$MemberDetailsObject->getMemberDetailsBirthDate();

        $this->memberPersonalSummary		=	$MemberDetailsObject->getMemberDetailsPersonalSummary();

        $this->memberLargeProfilePicUrl 	=	$MemberDetailsObject->getMemberDetailsProfilePicUrl();
        $this->memberMediumProfilePicUrl 	=	$MemberDetailsObject->getMemberDetailsProfilePicUrl();
        $this->memberSmallProfilePicUrl 	=	$MemberDetailsObject->getMemberDetailsProfilePicUrl();
        $this->memberXSmallProfilePicUrl 	=	$MemberDetailsObject->getMemberDetailsProfilePicUrl();

        $this->memberPersonalWebsiteLink 	=	$MemberDetailsObject->getMemberDetailsPersonalSiteUrl();
        $this->memberSocialLinkLinkedIn 	=	$MemberDetailsObject->getMemberDetailsLinkedInUrl();
        $this->memberSocialLinkGooglePlus 	=	$MemberDetailsObject->getMemberDetailsGooglePlusUrl();
        $this->memberSocialLinkTwitter 		=	$MemberDetailsObject->getMemberDetailsTwitterUrl();
        $this->memberSocialLinkFacebook		=	$MemberDetailsObject->getMemberDetailsFacebookUrl();

        $this->memberHomeLink				=	'/admin/home';
        $this->memberProfileLink			=	'/admin/profile';

        /**
         * ALERT Dropdown Variables
         */
        $ALERT_listItemsArray	=	array
        (
            array
            (
                'alertLink' 			=>	'/admin/alert/notice/',
                'alertLinkID'			=>	'1',
                'alertLabelClass'		=>	'label label-success',
                'alertIconClass'		=>	'fa fa-user',
                'alertContent'			=>	'5 users online.',
                'alertFuzzyTime'		=>	'Just Now',
                'alertExactTime'		=>	'1234567890',
            ),
            array
            (
                'alertLink' 			=>	'/admin/alert/notice/',
                'alertLinkID'			=>	'1',
                'alertLabelClass'		=>	'label label-primary',
                'alertIconClass'		=>	'fa fa-comment',
                'alertContent'			=>	'5 users online.',
                'alertFuzzyTime'		=>	'Just Now',
                'alertExactTime'		=>	'1234567890',
            ),
            array
            (
                'alertLink' 			=>	'/admin/alert/notice/',
                'alertLinkID'			=>	'1',
                'alertLabelClass'		=>	'label label-warning',
                'alertIconClass'		=>	'fa fa-lock',
                'alertContent'			=>	'5 users online.',
                'alertFuzzyTime'		=>	'Just Now',
                'alertExactTime'		=>	'1234567890',
            ),
        );
        $ALERT_listItemsCount 	=	count($ALERT_listItemsArray) > 0 ? count($ALERT_listItemsArray) : 0;

        /**
         * INBOX Dropdown Variables
         */
        $INBOX_listItemsArray	=	array
        (
            array
            (
                'messageLink' 			=>	'/admin/inbox/message/',
                'messageLinkID'			=>	'1',
                'messageAvatar'			=>	'/app/members/admin/img/avatars/avatar8.jpg',
                'messageAvatarAltText'	=>	'Jane Doe',
                'messageFromMemberType'	=>	'Signing Agency',
                'messageFrom'			=>	'Jane Doe',
                'messageFromShort'		=>	'Jane Doe',
                'messageContent'		=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
                'messageContentShort'	=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
                'messageFuzzyTime'		=>	'Just Now',
                'messageExactTime'		=>	'1234567890',
            ),
            array
            (
                'messageLink' 			=>	'/admin/inbox/message/',
                'messageLinkID'			=>	'2',
                'messageAvatar'			=>	'/app/members/admin/img/avatars/avatar7.jpg',
                'messageAvatarAltText'	=>	'Jane Doe',
                'messageFromMemberType'	=>	'Freelancer',
                'messageFrom'			=>	'Jane Doe',
                'messageFromShort'		=>	'Jane Doe',
                'messageContent'		=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
                'messageContentShort'	=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
                'messageFuzzyTime'		=>	'Just Now',
                'messageExactTime'		=>	'1234567890',
            ),
            array
            (
                'messageLink' 			=>	'/admin/inbox/message/',
                'messageLinkID'			=>	'3',
                'messageAvatar'			=>	'/app/members/admin/img/avatars/avatar6.jpg',
                'messageAvatarAltText'	=>	'Jane Doe',
                'messageFromMemberType'	=>	'Signing Source',
                'messageFrom'			=>	'Jane Doe',
                'messageFromShort'		=>	'Jane Doe',
                'messageContent'		=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
                'messageContentShort'	=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
                'messageFuzzyTime'		=>	'Just Now',
                'messageExactTime'		=>	'1234567890',
            ),
            array
            (
                'messageLink' 			=>	'/admin/inbox/message/',
                'messageLinkID'			=>	'3',
                'messageAvatar'			=>	'/app/members/admin/img/avatars/default-male.jpg',
                'messageAvatarAltText'	=>	'Jane Doe',
                'messageFromMemberType'	=>	'Client',
                'messageFrom'			=>	'Jane Doe',
                'messageFromShort'		=>	'Jane Doe',
                'messageContent'		=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
                'messageContentShort'	=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
                'messageFuzzyTime'		=>	'4 hours ago',
                'messageExactTime'		=>	'1234567890',
            ),
            array
            (
                'messageLink' 			=>	'/admin/inbox/message/',
                'messageLinkID'			=>	'3',
                'messageAvatar'			=>	'/app/members/admin/img/avatars/default-male.jpg',
                'messageAvatarAltText'	=>	'Jane Doe',
                'messageFromMemberType'	=>	'Guest',
                'messageFrom'			=>	'Jane Doe',
                'messageFromShort'		=>	'Jane Doe',
                'messageContent'		=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
                'messageContentShort'	=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
                'messageFuzzyTime'		=>	'4 hours ago',
                'messageExactTime'		=>	'1234567890',
            ),
        );
        $INBOX_listItemsCount 	=	count($INBOX_listItemsArray) > 0 ? count($INBOX_listItemsArray) : 0;


        /**
         * TO-DO Dropdown Variables
         */
        $TODO_listItemsArray 	=	array
        (
            array
            (
                'taskID'							=>	'1',
                'taskHeading'						=>	'Enter Signing Information', // Max 25 Characters
                'taskOverallCompletionLevel'		=>	'60',
                'taskProgressBarIsStriped'			=>	FALSE,
                'taskProgressBarIsStripedActive' 	=>	FALSE,

                'taskProgressBarIsComposite'		=>	FALSE, // The DISPLAY of this one task is broken into bits that add up to taskOverallCompletionLevel
                'taskBits'							=>	array
                (
                    array
                    (
                        'taskBitProgressBarType' 	=>	'success', // success | info | warning | danger
                        'taskBitCompletionLevel'	=>	'60', // Out of 100%. Do not add the percent sign
                    ),
                ),
            ),
            array
            (
                'taskID'							=>	'2',
                'taskHeading'						=>	'Enter Signing Agency Info',
                'taskOverallCompletionLevel'		=>	'60',
                'taskProgressBarIsStriped'			=>	FALSE,
                'taskProgressBarIsStripedActive' 	=>	FALSE,

                'taskProgressBarIsComposite'		=>	FALSE, // The DISPLAY of this one task is broken into bits that add up to taskOverallCompletionLevel
                'taskBits'							=>	array
                (
                    array
                    (
                        'taskBitProgressBarType' 	=>	'success', // Cannot be empty. Choose success | info | warning | danger
                        'taskBitCompletionLevel'	=>	'40', // Out of 100%. Do not add the percent sign
                    ),
                    array
                    (
                        'taskBitProgressBarType' 	=>	'warning', // success | info | warning | danger
                        'taskBitCompletionLevel'	=>	'20', // Out of 100%. Do not add the percent sign
                    ),
                ),
            ),
            array
            (
                'taskID'							=>	'2',
                'taskHeading'						=>	'Order # 12345678901234567',
                'taskOverallCompletionLevel'		=>	'40',
                'taskProgressBarIsStriped'			=>	TRUE,
                'taskProgressBarIsStripedActive' 	=>	TRUE,
                'taskBits'							=>	array
                (
                    array
                    (
                        'taskBitProgressBarType' 	=>	'danger', // Cannot be empty. Choose success | info | warning | danger
                        'taskBitCompletionLevel'	=>	'40', // Out of 100%. Do not add the percent sign
                    ),
                ),
            ),
        );

		$defaultLargeProfilePicUrl  	=	isset($this->memberGender) ? '/app/members/vendor/img/avatars/default-' . strtolower($this->memberGender) . '-large.jpg'    :   '/app/members/vendor/img/avatars/default-female-large.jpg';
		$defaultMediumProfilePicUrl  	=	isset($this->memberGender) ? '/app/members/vendor/img/avatars/default-' . strtolower($this->memberGender) . '.jpg'          :   '/app/members/vendor/img/avatars/default-female.jpg';
		$defaultSmallProfilePicUrl  	=	isset($this->memberGender) ? '/app/members/vendor/img/avatars/default-' . strtolower($this->memberGender) . '.jpg'          :   '/app/members/vendor/img/avatars/default-female.jpg';
		$defaultXSmallProfilePicUrl  	=	isset($this->memberGender) ? '/app/members/vendor/img/avatars/default-' . strtolower($this->memberGender) . '.jpg'          :   '/app/members/vendor/img/avatars/default-female.jpg';

		$memberPicUrlLarge				=	$MemberDetailsObject->getMemberDetailsProfilePicUrl();
        $memberPicUrlMedium				=	$MemberDetailsObject->getMemberDetailsProfilePicUrl();
        $memberPicUrlSmall				=	$MemberDetailsObject->getMemberDetailsProfilePicUrl();
        $memberPicUrlXSmall				=	$MemberDetailsObject->getMemberDetailsProfilePicUrl();


        /**
         * User Menu Dropdown
         *
         * link - The link the option should go to
         * iconClass - the class based icon to display
         * sectionName - The name to display in the menu section
         * labelClass - The label class used to highlight the icon and section. Default is no highlighting.
         *
         * Additional sections and highlighting can be added to the array given any logic you need
         */
        $memberUserMenuArray	=	array
        (
            /**
             * Standard Sections
             */
            array
            (
                'link'			=>	'/admin/profile',
                'iconClass'		=>	'fa fa-user',
                'sectionName'	=>	'My Profile',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/admin/account-ettings',
                'iconClass'		=>	'fa fa-cog',
                'sectionName'	=>	'Account Settings',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/admin/address-book',
                'iconClass'		=>	'fa fa-book',
                'sectionName'	=>	'Address Book',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/admin/privacy-settings',
                'iconClass'		=>	'fa fa-eye',
                'sectionName'	=>	'Privacy Settings',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/admin/change-password',
                'iconClass'		=>	'fa fa-lock',
                'sectionName'	=>	'Change Password',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/admin/logout',
                'iconClass'		=>	'fa fa-power-off',
                'sectionName'	=>	'Log Out',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/admin/terms',
                'iconClass'		=>	'fa fa-lock',
                'sectionName'	=>	'Terms',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/admin/privacy',
                'iconClass'		=>	'fa fa-power-off',
                'sectionName'	=>	'Privacy Policy',
                'labelClass'	=>	'',
            ),
        );


        $output =   array
        (
            'memberID'      =>  $this->memberID,
            'displayName'  =>  $this->memberDisplayName,
            'profileLink'  =>  'admin/profile',


            /**
             *
             */
            'crewsSectionButtonText_xs'  =>  '1 pending | 2 complete',
            'crewsSectionButtonText_sm'  =>  '1 pending | 2 complete',
            'crewsSectionButtonText_md'  =>  '1 pending | 2 complete',
            'crewsSectionButtonText_lg'  =>  '1 pending | 2 complete',

            'jobsSectionButtonText_xs'  =>  '1 pending | 2 complete',
            'jobsSectionButtonText_sm'  =>  '1 pending | 2 complete',
            'jobsSectionButtonText_md'  =>  '1 pending | 2 complete',
            'jobsSectionButtonText_lg'  =>  '1 pending | 2 complete',

            'calendarSectionButtonText_xs'  =>  '1 event | 2 notices',
            'calendarSectionButtonText_sm'  =>  '1 event | 2 notices',
            'calendarSectionButtonText_md'  =>  '1 event | 2 notices',
            'calendarSectionButtonText_lg'  =>  '1 event | 2 notices',

            'analyticsSectionButtonText_xs'  =>  '1 alert | 2 notes',
            'analyticsSectionButtonText_sm'  =>  '1 alert | 2 notes',
            'analyticsSectionButtonText_md'  =>  '1 alert | 2 notes',
            'analyticsSectionButtonText_lg'  =>  '1 alert | 2 notes',

            'reportsSectionButtonText_xs'  =>  '1 new | 2 pending',
            'reportsSectionButtonText_sm'  =>  '1 new | 2 pending',
            'reportsSectionButtonText_md'  =>  '1 new | 2 pending',
            'reportsSectionButtonText_lg'  =>  '1 new | 2 pending',

            'helpSectionButtonText_xs'  =>  '1 request | 2 tips',
            'helpSectionButtonText_sm'  =>  '1 request | 2 tips',
            'helpSectionButtonText_md'  =>  '1 request | 2 tips',
            'helpSectionButtonText_lg'  =>  '1 request | 2 tips',


            /**
             * Custom Ekinect JSS & CSS Files
             */
            'turnOnFlotCharts' 						=> 	FALSE,
            'ModuleDirectoryReference' 				=>	'admin/',
            'cloudLayoutJSPageName'					=>	'adminHome',
            'actionSpecificCSSFilesArray'			=>	array(),
            'actionSpecificJSFilesTopArray'			=>	array(),
            'actionSpecificJSFilesBottomArray'		=>	array(),


            /**
             * NOTIFICATION/Alerts Dropdown Variables
             */
            'ALERT_footerLink'  					=> 	'/admin/alerts',
            'ALERT_totalMessageCount'  				=> 	(string) $ALERT_listItemsCount > 0 ? $ALERT_listItemsCount : '0',
            'ALERT_title'  							=> 	'' . $ALERT_listItemsCount . ' Notification' . ($ALERT_listItemsCount == 1 ? '' : 's'),
            'ALERT_listItemsArray'  				=> 	$ALERT_listItemsArray,


            /**
             * INBOX Dropdown Variables
             */
            'INBOX_sidebarLink'  					=> 	'/admin/inbox',
            'INBOX_sidebarLink_all'  				=> 	'/admin/inbox/all',
            'INBOX_sidebarLink_new'  				=> 	'/admin/inbox/new',
            'INBOX_sidebarLink_favorites'  			=> 	'/admin/inbox/favorites',
            'INBOX_footerLink'  					=> 	'/admin/inbox',
            'INBOX_composeNewLink'  				=> 	'/admin/inbox/compose-new-message',
            'INBOX_totalMessageCount'  				=> 	(string) $INBOX_listItemsCount > 0 ? $INBOX_listItemsCount : '0',
            'INBOX_title'  							=> 	'' . $INBOX_listItemsCount . ' Message' . ($INBOX_listItemsCount == 1 ? '' : 's'),
            'INBOX_listItemsArray'  				=> 	$INBOX_listItemsArray,


            /**
             * TODO_ Dropdown Variables
             */
            'TODO_footerLink'  						=> 	'/admin/tasks',
            'TODO_listTotalNumber'  				=> 	(string) count($TODO_listItemsArray) > 0 ? count($TODO_listItemsArray) : '0',
            'TODO_listItemsArray'  					=> 	$TODO_listItemsArray,


            /**
             * User Login Dropdown Variables
             */
            'memberLoginDropDownDisplayName' 		=> 	$MemberDetailsObject->getMemberDetailsFirstName(),
            'memberFullName' 						=> 	$MemberDetailsObject->getMemberDetailsFullName(),
            'memberHomeLink' 						=> 	'/admin/home',
            'memberUserMenuArray' 					=> 	$memberUserMenuArray,


            /**
             * Picture & Icon urls
             */
            'memberLargeProfilePicUrl' 				=> 	isset($memberPicUrlLarge[0])  ? $memberPicUrlLarge  : $defaultLargeProfilePicUrl,
            'memberMediumProfilePicUrl' 			=> 	isset($memberPicUrlMedium[0]) ? $memberPicUrlMedium : $defaultMediumProfilePicUrl,
            'memberSmallProfilePicUrl' 				=> 	isset($memberPicUrlSmall[0])  ? $memberPicUrlSmall  : $defaultSmallProfilePicUrl,
            'memberXSmallProfilePicUrl' 			=> 	isset($memberPicUrlXSmall[0]) ? $memberPicUrlXSmall : $defaultXSmallProfilePicUrl,
        );

        return $this->changeArrayFormat($output, $outputFormat);
    }




}