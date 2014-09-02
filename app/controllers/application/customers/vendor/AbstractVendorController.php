<?php
    /**
     * Class AbstractVendorController
     *
     * filename:   AbstractVendorController.php
     *
     * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
     * @since       7/8/14 5:19 AM
     *
     * @copyright   Copyright (c) 2014 www.eKinect.com
     */


class AbstractVendorController extends AbstractCustomerController
{
    use MemberControls;
    use CustomerControls;
    use SecurityControls;

    public $layoutData;
    public $viewRootFolder = 'application/customers/vendor/';
	
	public $vendorNamePrefix;
    public $vendorFirstName;
    public $vendorMidName1;
    public $vendorMidName2;
    public $vendorLastName;
    public $vendorFullName;
    public $vendorDisplayName;
    public $vendorNameSuffix;

    public $vendorGender;
    public $vendorGenderRaw;
    public $vendorBirthDate;

    public $vendorPersonalSummary;

    public $vendorLargeProfilePicUrl;
    public $vendorMediumProfilePicUrl;
    public $vendorSmallProfilePicUrl;
    public $vendorXSmallProfilePicUrl;

    public $vendorPersonalWebsiteLink;
    public $vendorSocialLinkLinkedIn;
    public $vendorSocialLinkGooglePlus;
    public $vendorSocialLinkTwitter;
    public $vendorSocialLinkFacebook;

    public $vendorHomeLink;
    public $vendorProfileLink;

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.vendor-cloud';


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

    public function vendorLogout()
    {
        // Perform vendor specific action before logging out
		$this->addCustomerSiteStatus("Vendor successfully logged out.", $this->customerID);

	    // Generic Customer logout Activities
	    $this->customerLogout();

        // Redirect to the logged out page
        return $this->makeResponseView('application/customers/customer-logout', array());
    }

    public function forceVendorLogout()
    {
        // Redirect to the logged out page
        return  array
                (
                    'name'  =>  'vendorLogout',
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
		$this->vendorNamePrefix   			=   $this->customerDetails->getCustomerDetailsPrefix("text");
		$this->vendorFirstName   			=   $this->customerDetails->getCustomerDetailsFirstName();
		$this->vendorMidName1   			=   $this->customerDetails->getCustomerDetailsMidName1();
		$this->vendorMidName2  				=   $this->customerDetails->getCustomerDetailsMidName2();
    	$this->vendorLastName   			=   $this->customerDetails->getCustomerDetailsLastName();
    	$this->vendorFullName				=	$this->customerDetails->getCustomerDetailsFullName();
    	$this->vendorDisplayName			=	$this->customerDetails->getCustomerDetailsDisplayName();
    	$this->vendorNameSuffix				=	$this->customerDetails->getCustomerDetailsSuffix("text");

    	$this->vendorGender					=	$this->customerDetails->getCustomerDetailsGender('text');
    	$this->vendorGenderRaw				=	$this->customerDetails->getCustomerDetailsGender('raw');
    	$this->vendorBirthDate				=	$this->customerDetails->getCustomerDetailsBirthDate();

		$this->vendorPersonalSummary		=	$this->customerDetails->getCustomerDetailsPersonalSummary();

		$this->vendorLargeProfilePicUrl 	=	$this->customerDetails->getCustomerDetailsProfilePicUrl();
		$this->vendorMediumProfilePicUrl 	=	$this->customerDetails->getCustomerDetailsProfilePicUrl();
		$this->vendorSmallProfilePicUrl 	=	$this->customerDetails->getCustomerDetailsProfilePicUrl();
		$this->vendorXSmallProfilePicUrl 	=	$this->customerDetails->getCustomerDetailsProfilePicUrl();

		$this->vendorPersonalWebsiteLink 	=	$this->customerDetails->getCustomerDetailsPersonalSiteUrl();
		$this->vendorSocialLinkLinkedIn 	=	$this->customerDetails->getCustomerDetailsLinkedInUrl();
		$this->vendorSocialLinkGooglePlus 	=	$this->customerDetails->getCustomerDetailsGooglePlusUrl();
		$this->vendorSocialLinkTwitter 		=	$this->customerDetails->getCustomerDetailsTwitterUrl();
		$this->vendorSocialLinkFacebook		=	$this->customerDetails->getCustomerDetailsFacebookUrl();

    	$this->vendorHomeLink				=	'/vendor/home';
    	$this->vendorProfileLink			=	'/vendor/profile';

		/**
		 * ALERT Dropdown Variables
		 */
		$ALERT_listItemsArray	=	array
									(
										array
										(
											'alertLink' 			=>	'/vendor/alert/notice/',
											'alertLinkID'			=>	'1',
											'alertLabelClass'		=>	'label label-success',
											'alertIconClass'		=>	'fa fa-user',
											'alertContent'			=>	'5 users online.',
											'alertFuzzyTime'		=>	'Just Now',
											'alertExactTime'		=>	'1234567890',
										),
										array
										(
											'alertLink' 			=>	'/vendor/alert/notice/',
											'alertLinkID'			=>	'1',
											'alertLabelClass'		=>	'label label-primary',
											'alertIconClass'		=>	'fa fa-comment',
											'alertContent'			=>	'5 users online.',
											'alertFuzzyTime'		=>	'Just Now',
											'alertExactTime'		=>	'1234567890',
										),
										array
										(
											'alertLink' 			=>	'/vendor/alert/notice/',
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
											'messageLink' 			=>	'/vendor/inbox/message/',
											'messageLinkID'			=>	'1',
											'messageAvatar'			=>	'/app/customers/vendor/theme/img/avatars/avatar8.jpg',
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
											'messageLink' 			=>	'/vendor/inbox/message/',
											'messageLinkID'			=>	'2',
											'messageAvatar'			=>	'/app/customers/vendor/theme/img/avatars/avatar7.jpg',
											'messageAvatarAltText'	=>	'Jane Doe',
											'messageFromMemberType'	=>	'Vendor',
											'messageFrom'			=>	'Jane Doe',
											'messageFromShort'		=>	'Jane Doe',
											'messageContent'		=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
											'messageContentShort'	=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
											'messageFuzzyTime'		=>	'Just Now',
											'messageExactTime'		=>	'1234567890',
										),
										array
										(
											'messageLink' 			=>	'/vendor/inbox/message/',
											'messageLinkID'			=>	'3',
											'messageAvatar'			=>	'/app/customers/vendor/theme/img/avatars/avatar6.jpg',
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
											'messageLink' 			=>	'/vendor/inbox/message/',
											'messageLinkID'			=>	'3',
											'messageAvatar'			=>	'/app/customers/vendor/theme/img/avatars/default-male.jpg',
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
											'messageLink' 			=>	'/vendor/inbox/message/',
											'messageLinkID'			=>	'3',
											'messageAvatar'			=>	'/app/customers/vendor/theme/img/avatars/default-male.jpg',
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

		$defaultLargeProfilePicUrl  	=	isset($this->vendorGender) ? '/app/customers/vendor/theme/img/avatars/default-' . strtolower($this->vendorGender) . '-large.jpg'    :   '/app/customers/vendor/theme/img/avatars/default-female-large.jpg';
		$defaultMediumProfilePicUrl  	=	isset($this->vendorGender) ? '/app/customers/vendor/theme/img/avatars/default-' . strtolower($this->vendorGender) . '.jpg'          :   '/app/customers/vendor/theme/img/avatars/default-female.jpg';
		$defaultSmallProfilePicUrl  	=	isset($this->vendorGender) ? '/app/customers/vendor/theme/img/avatars/default-' . strtolower($this->vendorGender) . '.jpg'          :   '/app/customers/vendor/theme/img/avatars/default-female.jpg';
		$defaultXSmallProfilePicUrl  	=	isset($this->vendorGender) ? '/app/customers/vendor/theme/img/avatars/default-' . strtolower($this->vendorGender) . '.jpg'          :   '/app/customers/vendor/theme/img/avatars/default-female.jpg';

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
											'link'			=>	'/vendor/profile',
											'iconClass'		=>	'fa fa-user',
											'sectionName'	=>	'My Profile',
											'labelClass'	=>	'',
										),
										array
										(
											'link'			=>	'/vendor/account-ettings',
											'iconClass'		=>	'fa fa-cog',
											'sectionName'	=>	'Account Settings',
											'labelClass'	=>	'',
										),
										array
										(
											'link'			=>	'/vendor/address-book',
											'iconClass'		=>	'fa fa-book',
											'sectionName'	=>	'Address Book',
											'labelClass'	=>	'',
										),
										array
										(
											'link'			=>	'/vendor/privacy-settings',
											'iconClass'		=>	'fa fa-eye',
											'sectionName'	=>	'Privacy Settings',
											'labelClass'	=>	'',
										),
										array
										(
											'link'			=>	'/vendor/change-password',
											'iconClass'		=>	'fa fa-lock',
											'sectionName'	=>	'Change Password',
											'labelClass'	=>	'',
										),
										array
										(
											'link'			=>	'/vendor/logout',
											'iconClass'		=>	'fa fa-power-off',
											'sectionName'	=>	'Log Out',
											'labelClass'	=>	'',
										),
										array
										(
											'link'			=>	'/vendor/terms',
											'iconClass'		=>	'fa fa-lock',
											'sectionName'	=>	'Terms',
											'labelClass'	=>	'',
										),
										array
										(
											'link'			=>	'/vendor/privacy',
											'iconClass'		=>	'fa fa-power-off',
											'sectionName'	=>	'Privacy Policy',
											'labelClass'	=>	'',
										),
									);


        $output =   array
                    (
                        'memberID'      =>  $this->memberID,
                        'displayName'  =>  $this->vendorDisplayName,
                        'profileLink'  =>  '/vendor/profile',


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
                        'ModuleDirectoryReference' 				=>	'/vendor/',
                        'cloudLayoutJSPageName'					=>	'vendorHome',
                        'actionSpecificCSSFilesArray'			=>	array(),
                        'actionSpecificJSFilesTopArray'			=>	array(),
                        'actionSpecificJSFilesBottomArray'		=>	array(),


                        /**
                         * NOTIFICATION/Alerts Dropdown Variables
                         */
                        'ALERT_footerLink'  					=> 	'/vendor/alerts',
                        'ALERT_totalMessageCount'  				=> 	(string) $ALERT_listItemsCount > 0 ? $ALERT_listItemsCount : '0',
                        'ALERT_title'  							=> 	'' . $ALERT_listItemsCount . ' Notification' . ($ALERT_listItemsCount == 1 ? '' : 's'),
                        'ALERT_listItemsArray'  				=> 	$ALERT_listItemsArray,


                        /**
                         * INBOX Dropdown Variables
                         */
                        'INBOX_sidebarLink'  					=> 	'/vendor/inbox',
                        'INBOX_sidebarLink_all'  				=> 	'/vendor/inbox/all',
                        'INBOX_sidebarLink_new'  				=> 	'/vendor/inbox/new',
                        'INBOX_sidebarLink_favorites'  			=> 	'/vendor/inbox/favorites',
                        'INBOX_footerLink'  					=> 	'/vendor/inbox',
                        'INBOX_composeNewLink'  				=> 	'/vendor/inbox/compose-new-message',
                        'INBOX_totalMessageCount'  				=> 	(string) $INBOX_listItemsCount > 0 ? $INBOX_listItemsCount : '0',
                        'INBOX_title'  							=> 	'' . $INBOX_listItemsCount . ' Message' . ($INBOX_listItemsCount == 1 ? '' : 's'),
                        'INBOX_listItemsArray'  				=> 	$INBOX_listItemsArray,


                        /**
                         * TODO_ Dropdown Variables
                         */
                        'TODO_footerLink'  						=> 	'/vendor/tasks',
                        'TODO_listTotalNumber'  				=> 	(string) count($TODO_listItemsArray) > 0 ? count($TODO_listItemsArray) : '0',
                        'TODO_listItemsArray'  					=> 	$TODO_listItemsArray,


                        /**
                         * User Login Dropdown Variables
                         */
                        'memberLoginDropDownDisplayName' 		=> 	$this->customerDetails->getCustomerDetailsFirstName(),
                        'memberFullName' 						=> 	$this->customerDetails->getCustomerDetailsFullName(),
                        'memberHomeLink' 						=> 	'/vendor/home',
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
        $this->addCustomerSiteStatus("Vendor has chosen to change password.", $this->memberID);

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
        $this->addCustomerSiteStatus("Vendor is submitting a password change.", $this->memberID);

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
	                // Create current login credentials
                    $salts                      =   $this->getMemberSaltFromID($this->memberID);
                    $currentLoginCredentials    =   $this->generateMemberLoginCredentials
			                                        (
			                                             $this->customerPrimaryEmail,
			                                             $formFields['current_password'],
			                                             $salts['salt1'],
			                                             $salts['salt2'],
			                                             $salts['salt3']
			                                        );

                    $currentAuthData            =   array
		                                            (
		                                                'id' 	    =>  $this->memberID,
		                                                'password'  =>  $currentLoginCredentials,
		                                            );

	                // Check if current login credentials work
                    if (Auth::attempt($currentAuthData, true))
                    {
                        // Ensure customer did not change to same password
                        $newCheckLoginCredentials   =   $this->generateMemberLoginCredentials
				                                        (
				                                             $this->customerPrimaryEmail,
				                                             $formFields['password'],
				                                             $salts['salt1'],
				                                             $salts['salt2'],
				                                             $salts['salt3']
				                                        );
	                    $newCheckAuthData           =   array
			                                            (
			                                                'id' 	    =>  $this->memberID,
			                                                'password'  =>  $newCheckLoginCredentials,
			                                            );

	                    if (!Auth::attempt($newCheckAuthData))
	                    {
		                    // Create New Login credentials
	                        $NewLoginCredentials    =   $this->generateLoginCredentials($this->customerPrimaryEmail, $formFields['password']);
	                        $memberFillableArray    =   array
	                                                    (
	                                                        'password'          =>  Hash::make($NewLoginCredentials[0]),
	                                                        'salt1'             =>  $NewLoginCredentials[1],
	                                                        'salt2'             =>  $NewLoginCredentials[2],
	                                                        'salt3'             =>  $NewLoginCredentials[3],
	                                                    );

	                        $this->updateMember($this->memberID, $memberFillableArray);
	                        $this->addCustomerStatus("ChangedPassword", $this->memberID);
	                        $this->addCustomerStatus("ValidMember", $this->memberID);
	                        $this->addCustomerSiteStatus("Vendor has changed their password.", $this->memberID);

	                        $successMessage[]   =   'Congratulations. You have successfully changed your password!';
	                        Session::put('successFlashMessage', $successMessage);
                            $returnToRoute      =   array
	                                                (
	                                                    'name'  =>  'showVendorDashboard',
	                                                    'data'  =>  array(),
	                                                );
	                    }
	                    else
	                    {
		                    $FormMessages   =   array();
		                    $FormMessages[] =   "Please change your password to something new.";
		                    Log::info($FormName . " - form values did not validate.");
	                    }
                    }
                    else
                    {
                        $this->addAdminAlert();
                        Session::put('customerLogoutMessage', 'We did not recognize your login credentials. Please login and retry.');
                        Log::info($FormName . " - invalid login credentials.");
                        $returnToRoute  =   $this->forceVendorLogout();
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
                $returnToRoute  =   $this->forceVendorLogout();
            }
        }
        else
        {
            $this->addAdminAlert();
            Session::put('customerLogoutMessage', 'Unfortunately, there was an issue with your submission. Please login and retry.');
            Log::info($FormName . " - is not being correctly posted to.");
            $returnToRoute  =   $this->forceVendorLogout();
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