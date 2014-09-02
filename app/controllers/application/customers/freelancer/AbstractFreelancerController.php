<?php
/**
 * Class AbstractFreelancerController
 *
 * filename:   AbstractFreelancerController.php
 *
 * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
 * @since       7/8/14 5:19 AM
 *
 * @copyright   Copyright (c) 2014 www.eKinect.com
 */


class AbstractFreelancerController extends AbstractCustomerController
{
    use MemberControls;
    use CustomerControls;
    use SecurityControls;

    public $layoutData;
    public $viewRootFolder = 'application/customers/freelancer/';

	public $freelancerNamePrefix;
    public $freelancerFirstName;
    public $freelancerMidName1;
    public $freelancerMidName2;
    public $freelancerLastName;
    public $freelancerFullName;
    public $freelancerDisplayName;
    public $freelancerNameSuffix;

    public $freelancerGender;
    public $freelancerGenderRaw;
    public $freelancerBirthDate;

    public $freelancerPersonalSummary;

    public $freelancerLargeProfilePicUrl;
    public $freelancerMediumProfilePicUrl;
    public $freelancerSmallProfilePicUrl;
    public $freelancerXSmallProfilePicUrl;

    public $freelancerPersonalWebsiteLink;
    public $freelancerSocialLinkLinkedIn;
    public $freelancerSocialLinkGooglePlus;
    public $freelancerSocialLinkTwitter;
    public $freelancerSocialLinkFacebook;

    public $freelancerHomeLink;
    public $freelancerProfileLink;

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.freelancer-cloud';


    public function __construct()
    {
        parent::__construct();

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



    public function freelancerLogout()
    {
        // Perform freelancer specific action before logging out
		$this->addCustomerSiteStatus("Freelancer successfully logged out.", $this->customerID);

	    // Generic Customer logout Activities
	    $this->customerLogout();

        // Redirect to the logged out page
        return $this->makeResponseView('application/customers/customer-logout', array());
    }



    public function forceFreelancerLogout()
    {
        // Redirect to the logged out page
        return  array
        (
            'name'  =>  'freelancerLogout',
            'data'  =>  array(),
        );
    }

    /**
     * Gets default/current values for the cloud layout.
     * Subsequent values are defined by regular AJAX calls
     *
     * @param   string  $outputFormat
     *
     * @return bool|string|array
     */
    public function getCloudLayoutVariables($outputFormat="array")
    {
        /**
         * Update Class Properties
         */
        $this->freelancerNamePrefix   			=   $this->customerDetails->getCustomerDetailsPrefix("text");
        $this->freelancerFirstName   			=   $this->customerDetails->getCustomerDetailsFirstName();
        $this->freelancerMidName1   			=   $this->customerDetails->getCustomerDetailsMidName1();
        $this->freelancerMidName2  				=   $this->customerDetails->getCustomerDetailsMidName2();
        $this->freelancerLastName   			=   $this->customerDetails->getCustomerDetailsLastName();
        $this->freelancerFullName				=	$this->customerDetails->getCustomerDetailsFullName();
        $this->freelancerDisplayName			=	$this->customerDetails->getCustomerDetailsDisplayName();
        $this->freelancerNameSuffix				=	$this->customerDetails->getCustomerDetailsSuffix("text");

        $this->freelancerGender					=	$this->customerDetails->getCustomerDetailsGender('text');
        $this->freelancerGenderRaw				=	$this->customerDetails->getCustomerDetailsGender('raw');
        $this->freelancerBirthDate				=	$this->customerDetails->getCustomerDetailsBirthDate();

        $this->freelancerPersonalSummary		=	$this->customerDetails->getCustomerDetailsPersonalSummary();

        $this->freelancerLargeProfilePicUrl 	=	$this->customerDetails->getCustomerDetailsProfilePicUrl();
        $this->freelancerMediumProfilePicUrl 	=	$this->customerDetails->getCustomerDetailsProfilePicUrl();
        $this->freelancerSmallProfilePicUrl 	=	$this->customerDetails->getCustomerDetailsProfilePicUrl();
        $this->freelancerXSmallProfilePicUrl 	=	$this->customerDetails->getCustomerDetailsProfilePicUrl();

        $this->freelancerPersonalWebsiteLink 	=	$this->customerDetails->getCustomerDetailsPersonalSiteUrl();
        $this->freelancerSocialLinkLinkedIn 	=	$this->customerDetails->getCustomerDetailsLinkedInUrl();
        $this->freelancerSocialLinkGooglePlus 	=	$this->customerDetails->getCustomerDetailsGooglePlusUrl();
        $this->freelancerSocialLinkTwitter 		=	$this->customerDetails->getCustomerDetailsTwitterUrl();
        $this->freelancerSocialLinkFacebook		=	$this->customerDetails->getCustomerDetailsFacebookUrl();

        $this->freelancerHomeLink				=	'/freelancer/home';
        $this->freelancerProfileLink			=	'/freelancer/profile';

        /**
         * ALERT Dropdown Variables
         */
        $ALERT_listItemsArray	=	array
        (
            array
            (
                'alertLink' 			=>	'/freelancer/alert/notice/',
                'alertLinkID'			=>	'1',
                'alertLabelClass'		=>	'label label-success',
                'alertIconClass'		=>	'fa fa-user',
                'alertContent'			=>	'5 users online.',
                'alertFuzzyTime'		=>	'Just Now',
                'alertExactTime'		=>	'1234567890',
            ),
            array
            (
                'alertLink' 			=>	'/freelancer/alert/notice/',
                'alertLinkID'			=>	'1',
                'alertLabelClass'		=>	'label label-primary',
                'alertIconClass'		=>	'fa fa-comment',
                'alertContent'			=>	'5 users online.',
                'alertFuzzyTime'		=>	'Just Now',
                'alertExactTime'		=>	'1234567890',
            ),
            array
            (
                'alertLink' 			=>	'/freelancer/alert/notice/',
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
                'messageLink' 			=>	'/freelancer/inbox/message/',
                'messageLinkID'			=>	'1',
                'messageAvatar'			=>	'/app/customers/freelancer/theme/img/avatars/avatar8.jpg',
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
                'messageLink' 			=>	'/freelancer/inbox/message/',
                'messageLinkID'			=>	'2',
                'messageAvatar'			=>	'/app/customers/freelancer/theme/img/avatars/avatar7.jpg',
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
                'messageLink' 			=>	'/freelancer/inbox/message/',
                'messageLinkID'			=>	'3',
                'messageAvatar'			=>	'/app/customers/freelancer/theme/img/avatars/avatar6.jpg',
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
                'messageLink' 			=>	'/freelancer/inbox/message/',
                'messageLinkID'			=>	'3',
                'messageAvatar'			=>	'/app/customers/freelancer/theme/img/avatars/default-male.jpg',
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
                'messageLink' 			=>	'/freelancer/inbox/message/',
                'messageLinkID'			=>	'3',
                'messageAvatar'			=>	'/app/customers/freelancer/theme/img/avatars/default-male.jpg',
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

		$defaultLargeProfilePicUrl  	=	isset($this->freelancerGender) ? '/app/customers/freelancer/theme/img/avatars/default-' . strtolower($this->freelancerGender) . '-large.jpg'    :   '/app/customers/freelancer/theme/img/avatars/default-female-large.jpg';
		$defaultMediumProfilePicUrl  	=	isset($this->freelancerGender) ? '/app/customers/freelancer/theme/img/avatars/default-' . strtolower($this->freelancerGender) . '.jpg'          :   '/app/customers/freelancer/theme/img/avatars/default-female.jpg';
		$defaultSmallProfilePicUrl  	=	isset($this->freelancerGender) ? '/app/customers/freelancer/theme/img/avatars/default-' . strtolower($this->freelancerGender) . '.jpg'          :   '/app/customers/freelancer/theme/img/avatars/default-female.jpg';
		$defaultXSmallProfilePicUrl  	=	isset($this->freelancerGender) ? '/app/customers/freelancer/theme/img/avatars/default-' . strtolower($this->freelancerGender) . '.jpg'          :   '/app/customers/freelancer/theme/img/avatars/default-female.jpg';

		$memberPicUrlLarge				=	$this->customerDetails->getCustomerDetailsProfilePicUrl();
        $memberPicUrlMedium				=	$this->customerDetails->getCustomerDetailsProfilePicUrl();
        $memberPicUrlSmall				=	$this->customerDetails->getCustomerDetailsProfilePicUrl();
        $memberPicUrlXSmall				=	$this->customerDetails->getCustomerDetailsProfilePicUrl();


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
                'link'			=>	'/freelancer/profile',
                'iconClass'		=>	'fa fa-user',
                'sectionName'	=>	'My Profile',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/freelancer/account-ettings',
                'iconClass'		=>	'fa fa-cog',
                'sectionName'	=>	'Account Settings',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/freelancer/address-book',
                'iconClass'		=>	'fa fa-book',
                'sectionName'	=>	'Address Book',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/freelancer/privacy-settings',
                'iconClass'		=>	'fa fa-eye',
                'sectionName'	=>	'Privacy Settings',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/freelancer/change-password',
                'iconClass'		=>	'fa fa-lock',
                'sectionName'	=>	'Change Password',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/freelancer/logout',
                'iconClass'		=>	'fa fa-power-off',
                'sectionName'	=>	'Log Out',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/freelancer/terms',
                'iconClass'		=>	'fa fa-lock',
                'sectionName'	=>	'Terms',
                'labelClass'	=>	'',
            ),
            array
            (
                'link'			=>	'/freelancer/privacy',
                'iconClass'		=>	'fa fa-power-off',
                'sectionName'	=>	'Privacy Policy',
                'labelClass'	=>	'',
            ),
        );


        $output =   array
        (
            'memberID'      =>  $this->memberID,
            'displayName'  =>  $this->freelancerDisplayName,
            'profileLink'  =>  '/freelancer/profile',


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
            'ModuleDirectoryReference' 				=>	'/freelancer/',
            'cloudLayoutJSPageName'					=>	'freelancerHome',
            'actionSpecificCSSFilesArray'			=>	array(),
            'actionSpecificJSFilesTopArray'			=>	array(),
            'actionSpecificJSFilesBottomArray'		=>	array(),


            /**
             * NOTIFICATION/Alerts Dropdown Variables
             */
            'ALERT_footerLink'  					=> 	'/freelancer/alerts',
            'ALERT_totalMessageCount'  				=> 	(string) $ALERT_listItemsCount > 0 ? $ALERT_listItemsCount : '0',
            'ALERT_title'  							=> 	'' . $ALERT_listItemsCount . ' Notification' . ($ALERT_listItemsCount == 1 ? '' : 's'),
            'ALERT_listItemsArray'  				=> 	$ALERT_listItemsArray,


            /**
             * INBOX Dropdown Variables
             */
            'INBOX_sidebarLink'  					=> 	'/freelancer/inbox',
            'INBOX_sidebarLink_all'  				=> 	'/freelancer/inbox/all',
            'INBOX_sidebarLink_new'  				=> 	'/freelancer/inbox/new',
            'INBOX_sidebarLink_favorites'  			=> 	'/freelancer/inbox/favorites',
            'INBOX_footerLink'  					=> 	'/freelancer/inbox',
            'INBOX_composeNewLink'  				=> 	'/freelancer/inbox/compose-new-message',
            'INBOX_totalMessageCount'  				=> 	(string) $INBOX_listItemsCount > 0 ? $INBOX_listItemsCount : '0',
            'INBOX_title'  							=> 	'' . $INBOX_listItemsCount . ' Message' . ($INBOX_listItemsCount == 1 ? '' : 's'),
            'INBOX_listItemsArray'  				=> 	$INBOX_listItemsArray,


            /**
             * TODO_ Dropdown Variables
             */
            'TODO_footerLink'  						=> 	'/freelancer/tasks',
            'TODO_listTotalNumber'  				=> 	(string) count($TODO_listItemsArray) > 0 ? count($TODO_listItemsArray) : '0',
            'TODO_listItemsArray'  					=> 	$TODO_listItemsArray,


            /**
             * User Login Dropdown Variables
             */
            'memberLoginDropDownDisplayName' 		=> 	$this->customerDetails->getCustomerDetailsFirstName(),
            'memberFullName' 						=> 	$this->customerDetails->getCustomerDetailsFullName(),
            'memberHomeLink' 						=> 	'/freelancer/home',
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





    public function showChangePasswordWithOldPassword()
    {
        $this->addCustomerSiteStatus("Freelancer has chosen to change password.", $this->memberID);

        $FormMessages       =   '';
        $AttemptMessages    =   '';
        $customViewData     =   array
        (
            'FormMessages'      =>  $FormMessages,
            'AttemptMessages'   =>  $AttemptMessages,
        );
        $viewData           =   array_merge($this->layoutData, $customViewData);

        return $this->makeResponseView($this->viewRootFolder . 'change-password-with-old-password', $viewData);
    }

    public function postChangePasswordWithOldPassword()
    {
        $this->addCustomerSiteStatus("Freelancer is submitting a password change.", $this->memberID);

        $FormName           =   'ChangePasswordWithOldPasswordForm';
        $AttemptMessages    =   '';
        $FormMessages       =   '';
        $returnToRoute      =   array
        (
            'name'  =>  FALSE,
            'data'  =>  FALSE,
        );

        if(Request::isMethod('post'))
        {
            if($this->isFormClean($FormName, Input::all()))
            {
                $formFields     =   array
					                (
					                    'current_password'          =>  Input::get('current_password'),
					                    'password'                  =>  Input::get('password'),
					                    'password_confirmation '    =>  Input::get('password_confirmation'),
					                );
                $formRules      =   array
					                (
					                    'current_password'          =>  array
													                    (
													                        'required',
													                        'between:10,256',
													                    ),
					                    'password'                  =>  array
													                    (
													                        'required',
													                        'between:10,256',
													                        'different:current_password'
													                    ),
					                    'password_confirmation '    =>  array
													                    (
													                        'same:password',
													                    ),
					                );
                $formMessages   =   array
					                (
					                    'current_password.required'     =>  "Please enter your current password.",
					                    'current_password.between'      =>  "Valid passwords are more than 10 digits.",

					                    'password.required'             =>  "Please enter your new password.",
					                    'password.between'              =>  "Passwords must be more than 10 digits.",
					                    'password.different'            =>  "Your New Password must be different from your Current Password.",

					                    'password_confirmation.same'    =>  "A password confirmation is required.",
					                );

                $validator      =   Validator::make($formFields, $formRules, $formMessages);
                $passwordCheck  =   $this->checkPasswordStrength($formFields['password']);

                if ($validator->passes() && $passwordCheck['status'])
                {
                    $salts              =   $this->getMemberSaltFromID($this->memberID);
                    $loginCredentials   =   $this->generateMemberLoginCredentials($this->customerPrimaryEmail, $formFields['current_password'], $salts['salt1'], $salts['salt2'], $salts['salt3']);

                    // create our user data for the authentication
                    $authData           =   array
                    (
                        'id' 	    =>  $this->memberID,
                        'password'  =>  $loginCredentials,
                    );

                    if (Auth::attempt($authData, true))
                    {
                        $LoginCredentials       =   $this->generateLoginCredentials($this->customerPrimaryEmail, $formFields['password']);
                        $memberFillableArray    =   array
                        (
                            'password'          =>  Hash::make($LoginCredentials[0]),
                            'salt1'             =>  $LoginCredentials[1],
                            'salt2'             =>  $LoginCredentials[2],
                            'salt3'             =>  $LoginCredentials[3],
                        );
                        $this->updateMember($this->memberID, $memberFillableArray);
                        $this->addCustomerStatus("ChangedPassword", $this->memberID);
                        $this->addCustomerStatus("ValidMember", $this->memberID);
                        $this->addCustomerSiteStatus("Freelancer has changed their password.", $this->memberID);

                        $successMessage[]   =   'Congratulations. You have successfully changed your password!';
                        Session::put('successFlashMessage', $successMessage);
                        $returnToRoute      =   array
                                                (
                                                    'name'  =>  'showFreelancerDashboard',
                                                    'data'  =>  array(),
                                                );
                    }
                    else
                    {
                        $this->addAdminAlert();
                        Session::put('customerLogoutMessage', 'We did not recognize your login credentials. Please login and retry.');
                        Log::info($FormName . " - invalid login credentials.");
                        $returnToRoute  =   $this->forceFreelancerLogout();
                    }
                }
                else
                {
                    $FormErrors   =   $validator->messages()->toArray();
                    $FormMessages =   array();
                    foreach($FormErrors as $errors)
                    {
                        $FormMessages[]   =   $errors[0];
                    }

                    if(array_key_exists('errors', $passwordCheck))
                    {
                        foreach($passwordCheck['errors'] as $errors)
                        {
                            $FormMessages[]   =   $errors;
                        }
                    }

                    Log::info($FormName . " - form values did not validate.");
                }
            }
            else
            {
                $this->addAdminAlert();
                Session::put('customerLogoutMessage', 'Unfortunately, there was an issue with your submission. Please login and retry.');
                Log::warning($FormName . " is not clean.");
                $returnToRoute  =   $this->forceFreelancerLogout();
            }
        }
        else
        {
            $this->addAdminAlert();
            Session::put('customerLogoutMessage', 'Unfortunately, there was an issue with your submission. Please login and retry.');
            Log::info($FormName . " - is not being correctly posted to.");
            $returnToRoute  =   $this->forceFreelancerLogout();
        }



        if(FALSE != $returnToRoute['name'])
        {
            return Redirect::route($returnToRoute['name'],$returnToRoute['data']);
        }
        else
        {
            $customViewData   = array
            (
                'AttemptMessages'      =>  $AttemptMessages,
                'FormMessages'         =>  $FormMessages,
            );
            $viewData           =   array_merge($this->layoutData, $customViewData);

            return $this->makeResponseView($this->viewRootFolder . 'change-password-with-old-password', $viewData);
        }

    }


}