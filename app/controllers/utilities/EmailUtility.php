<?php
/**
 * Class EmailUtility
 *
 * filename:   EmailUtility.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
 * @since       8/4/14 5:36 PM
 * 
 * @copyright   Copyright (c) 2014 www.ekinect.com
 */ 


class EmailUtility
{
    // Replaceable Variables
    public $cccc;
    private $fb_ogs             			=  "<meta property=\"og:title\"          	content=\"Welcome to ekinect.com\" />
                                    			<meta property=\"og:type\"            	content=\"website\" />
												<meta property=\"og:url\"             	content=\"https://www.ekinect.com\" />
												<meta property=\"og:image\"           	content=\"\" />
												<meta property=\"og:site_name\"       	content=\"ekinect.com\" />
												<meta property=\"og:description\"     	content=\"ekinect.com offers a web based business suite designed for today's audio-video professional freelancer and the vendors that need them!\" />
												<meta property=\"og:locality\"        	content=\"Los Angeles\" />
												<meta property=\"og:region\"          	content=\"CA\" />
												<meta property=\"og:country-name\"    	content=\"USA\" />
												<meta property=\"og:email\"           	content=\"welcome@ekinect.com\" />";
												//<meta property=\"og:phone_number\"    	content=\"+1-310-402-8018\" />";
												// [[**FB_OGS**]]

    private $pre_hdr1           			=   "ekinect.com - ekinect slogan goes here";    						                    // [[**PRE_HDR1**]]
    private $pre_hdr2           			=   "Use this email to activate your ekinect.com account.";    								// [[**PRE_HDR2**]]

    private $eml_main_logo      			=   "http://www.ekinect.com/images/site/logos/paypal_header_image.png";      	            // [[**EML_MAIN_LOGO**]]

    private $fb_link_logo       			=   "http://www.ekinect.com/images/site/buttons/sfs_icon_facebook.png";      	            // [[**FB_LINK_LOGO**]]
    private $fb_link            			=   "http://www.facebook.com/ekinect";                                                 		// [[**FB_LINK**]]

    private $twt_link_logo      			=   "http://www.ekinect.com/images/site/buttons/sfs_icon_twitter.png";       	            // [[**TWT_LINK_LOGO**]]
    private $twt_link           			=   "https://www.twitter.com/ekinect";                                                 	    // [[**TWT_LINK**]]

    private $mail_addy          			=   "";             																			// [[**MAIL_ADDY**]]
    private $vrfy_email_link      			=   "http://www.ekinect.com/email-verification/";             								// [[**VRFY_EML_LINK**]]
    private $vrfy_pwd_link      			=   "http://www.ekinect.com/reset-password/";             									// [[**VRFY_EML_LINK**]]

	private $break_character    			=   "\\n";             																			// [[**BREAK**]]
    private $customer_service_number      	=   "(000) 000-0000";             									                            // [[**CUSTOMER_SERVICE_NUMBER**]]

	private $templateFolder					=	"email/db_templates/";

	private $mbr_type                       =   "Vendor";
	private $email_subject                  =   "";

	private $customerServiceEmail           =   "customerservice@ekinect.me";
	private $addThisEmail                   =   "welcome@ekinect.me";
	private $companyName                    =   "Ekinect";
	private $companySite                    =   "Ekinect.me";
	private $companySignEmailAs             =   "The Ekinect Team";



    public function getEmailTemplate($emailIdentifier, $emailVariables)
    {
        switch($emailIdentifier)
        {
            case 'verify-new-member'    							:   if(!isset($emailVariables['verifyEmailLink']) || !array_key_exists('verifyEmailLink', $emailVariables))
																		{
																			throw new \Exception('missing verifyEmailLink key for ' . $emailIdentifier . ' template');
																		}

																		$emailTextLink      =   'emails.auth.verify-new-member.text';
																		$emailHTMLLink      =   'emails.auth.verify-new-member.html';
																		$emailSubject       =   'Verify Your Email Address';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
																									'EMAIL_SUBJECT'             =>  $emailSubject,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'VRFY_EML_LINK'             =>  $emailVariables['verifyEmailLink'],
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_EMAIL'    =>  $this->customerServiceEmail,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
																									'ADD_THIS_EMAIL'            =>  $this->addThisEmail,
																									'COMPANY_NAME'              =>  $this->companyName,
																									'COMPANY_SITE'              =>  $this->companySite,
																									'COMPANY_SIGN_EMAIL_AS'     =>  $this->companySignEmailAs,
																								);
																		break;


            case 'verify-new-member-again'    						:   if(!isset($emailVariables['verifyEmailLink']) || !array_key_exists('verifyEmailLink', $emailVariables))
																		{
																			throw new \Exception('missing verifyEmailLink key for ' . $emailIdentifier . ' template');
																		}

																		$emailTextLink      =   'emails.auth.verify-new-member-again.text';
																		$emailHTMLLink      =   'emails.auth.verify-new-member-again.html';
																		$emailSubject       =   'Verify Your Email Address';
																		$templateVariables  =   array
                                                                                                (
                                                                                                    'FB_OGS'                    =>  $this->fb_ogs,
                                                                                                    'EMAIL_SUBJECT'             =>  $emailSubject,
                                                                                                    'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
                                                                                                    'FB_LINK_LOGO'              =>  $this->fb_link_logo,
                                                                                                    'FB_LINK'                   =>  $this->fb_link,
                                                                                                    'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
                                                                                                    'TWT_LINK'                  =>  $this->twt_link,
                                                                                                    'VRFY_EML_LINK'             =>  $emailVariables['verifyEmailLink'],
                                                                                                    'TEXT_BREAK'                =>  $this->break_character,
                                                                                                    'CUSTOMER_SERVICE_EMAIL'    =>  $this->customerServiceEmail,
                                                                                                    'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
                                                                                                    'CURR_YR'                   =>  date('Y'),
                                                                                                    'ADD_THIS_EMAIL'            =>  $this->addThisEmail,
                                                                                                    'COMPANY_NAME'              =>  $this->companyName,
                                                                                                    'COMPANY_SITE'              =>  $this->companySite,
                                                                                                    'COMPANY_SIGN_EMAIL_AS'     =>  $this->companySignEmailAs,
                                                                                                );
                                                                                                break;


            case 'excessive-logins'  								:   $emailTextLink      =   'emails.auth.excessive-logins.text';
																		$emailHTMLLink      =   'emails.auth.excessive-logins.html';
																		$emailSubject       =   $this->companySite . ' Access Issue';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
                                                                                                    'EMAIL_SUBJECT'             =>  $emailSubject,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_EMAIL'    =>  $this->customerServiceEmail,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
                                                                                                    'ADD_THIS_EMAIL'            =>  $this->addThisEmail,
                                                                                                    'COMPANY_NAME'              =>  $this->companyName,
                                                                                                    'COMPANY_SITE'              =>  $this->companySite,
                                                                                                    'COMPANY_SIGN_EMAIL_AS'     =>  $this->companySignEmailAs,
																								);
																		break;


            case 'excessive-signups'  								:   $emailTextLink      =   'emails.auth.excessive-signups.text';
																		$emailHTMLLink      =   'emails.auth.excessive-signups.html';
																		$emailSubject       =   $this->companySite . ' Access Issues';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
                                                                                                    'EMAIL_SUBJECT'             =>  $emailSubject,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_EMAIL'    =>  $this->customerServiceEmail,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
                                                                                                    'ADD_THIS_EMAIL'            =>  $this->addThisEmail,
                                                                                                    'COMPANY_NAME'              =>  $this->companyName,
                                                                                                    'COMPANY_SITE'              =>  $this->companySite,
                                                                                                    'COMPANY_SIGN_EMAIL_AS'     =>  $this->companySignEmailAs,
																								);
																		break;


            case 'excessive-forgot-logins'  						:   $emailTextLink      =   'emails.auth.excessive-forgot-logins.text';
																		$emailHTMLLink      =   'emails.auth.excessive-forgot-logins.html';
																		$emailSubject       =   $this->companySite . ' Access Issue';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
                                                                                                    'EMAIL_SUBJECT'             =>  $emailSubject,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_EMAIL'    =>  $this->customerServiceEmail,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
                                                                                                    'ADD_THIS_EMAIL'            =>  $this->addThisEmail,
                                                                                                    'COMPANY_NAME'              =>  $this->companyName,
                                                                                                    'COMPANY_SITE'              =>  $this->companySite,
                                                                                                    'COMPANY_SIGN_EMAIL_AS'     =>  $this->companySignEmailAs,
																								);
																		break;


            case 'excessive-change-verified-member-password' 		:   $emailTextLink      =   'emails.auth.excessive-change-verified-member-password.text';
																		$emailHTMLLink      =   'emails.auth.excessive-change-verified-member-password.html';
																		$emailSubject       =   $this->companySite . ' Access Issue';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
                                                                                                    'EMAIL_SUBJECT'             =>  $emailSubject,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_EMAIL'    =>  $this->customerServiceEmail,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
                                                                                                    'ADD_THIS_EMAIL'            =>  $this->addThisEmail,
                                                                                                    'COMPANY_NAME'              =>  $this->companyName,
                                                                                                    'COMPANY_SITE'              =>  $this->companySite,
                                                                                                    'COMPANY_SIGN_EMAIL_AS'     =>  $this->companySignEmailAs,
																								);
																		break;


            case 'excessive-change-old-member-password' 			:   $emailTextLink      =   'emails.auth.excessive-change-old-member-password.text';
																		$emailHTMLLink      =   'emails.auth.excessive-change-old-member-password.html';
																		$emailSubject       =   $this->companySite . ' Access Issue';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
                                                                                                    'EMAIL_SUBJECT'             =>  $emailSubject,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_EMAIL'    =>  $this->customerServiceEmail,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
                                                                                                    'ADD_THIS_EMAIL'            =>  $this->addThisEmail,
                                                                                                    'COMPANY_NAME'              =>  $this->companyName,
                                                                                                    'COMPANY_SITE'              =>  $this->companySite,
                                                                                                    'COMPANY_SIGN_EMAIL_AS'     =>  $this->companySignEmailAs,
																								);
																		break;


            case 'excessive-lost-signup-verification' 				:   $emailTextLink      =   'emails.auth.excessive-lost-signup-verification.text';
																		$emailHTMLLink      =   'emails.auth.excessive-lost-signup-verification.html';
																		$emailSubject       =   $this->companySite . ' Access Issue';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
                                                                                                    'EMAIL_SUBJECT'             =>  $emailSubject,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_EMAIL'    =>  $this->customerServiceEmail,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
                                                                                                    'ADD_THIS_EMAIL'            =>  $this->addThisEmail,
                                                                                                    'COMPANY_NAME'              =>  $this->companyName,
                                                                                                    'COMPANY_SITE'              =>  $this->companySite,
                                                                                                    'COMPANY_SIGN_EMAIL_AS'     =>  $this->companySignEmailAs,
																								);
																		break;


            case 'forgot-logins-success'  							:   if(!isset($emailVariables['verifyEmailLink']) || !array_key_exists('verifyEmailLink', $emailVariables))
																		{
																			throw new \Exception('missing verifyEmailLink key for ' . $emailIdentifier . ' template');
																		}

																		if(!isset($emailVariables['first_name']) || !array_key_exists('first_name', $emailVariables))
																		{
																			throw new \Exception('missing first_name key for ' . $emailIdentifier . ' template');
																		}

																		if(!isset($emailVariables['last_name']) || !array_key_exists('last_name', $emailVariables))
																		{
																			throw new \Exception('missing last_name key for ' . $emailIdentifier . ' template');
																		}

																		$emailTextLink      =   'emails.auth.forgot-logins-success.text';
																		$emailHTMLLink      =   'emails.auth.forgot-logins-success.html';
																		$emailSubject       =   $this->companySite . ' Access Issue';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
                                                                                                    'EMAIL_SUBJECT'             =>  $emailSubject,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_EMAIL'    =>  $this->customerServiceEmail,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'VRFY_EML_LINK'             =>  $emailVariables['verifyEmailLink'],
																									'CURR_YR'                   =>  date('Y'),
                                                                                                    'ADD_THIS_EMAIL'            =>  $this->addThisEmail,
                                                                                                    'COMPANY_NAME'              =>  $this->companyName,
                                                                                                    'COMPANY_SITE'              =>  $this->companySite,
                                                                                                    'COMPANY_SIGN_EMAIL_AS'     =>  $this->companySignEmailAs,

																									'FIRST_NAME'                =>  ucwords(strtolower($emailVariables['first_name'])),
																									'LAST_NAME'                 =>  ucwords(strtolower($emailVariables['last_name'])),
																									'FULL_NAME'                 =>  ucwords(strtolower($emailVariables['first_name'])) . " " . ucwords(strtolower($emailVariables['last_name'])),
																								);
																		break;


            case 'genericProfileInformationChange'  				:   if(!isset($emailVariables['first_name']) || !array_key_exists('first_name', $emailVariables))
																		{
																			throw new \Exception('missing first_name key for ' . $emailIdentifier . ' template');
																		}

																		if(!isset($emailVariables['last_name']) || !array_key_exists('last_name', $emailVariables))
																		{
																			throw new \Exception('missing last_name key for ' . $emailIdentifier . ' template');
																		}

																		$emailTextLink      =   'emails.auth.generic-profile-information-change.text';
																		$emailHTMLLink      =   'emails.auth.generic-profile-information-change.html';
                                                                        $emailSubject       =   $this->companySite . ' Profile Change Notification';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
                                                                                                    'EMAIL_SUBJECT'             =>  $emailSubject,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_EMAIL'    =>  $this->customerServiceEmail,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
                                                                                                    'ADD_THIS_EMAIL'            =>  $this->addThisEmail,
                                                                                                    'COMPANY_NAME'              =>  $this->companyName,
                                                                                                    'COMPANY_SITE'              =>  $this->companySite,
                                                                                                    'COMPANY_SIGN_EMAIL_AS'     =>  $this->companySignEmailAs,

																									'FIRST_NAME'                =>  ucwords(strtolower($emailVariables['first_name'])),
																									'LAST_NAME'                 =>  ucwords(strtolower($emailVariables['last_name'])),
																									'FULL_NAME'                 =>  ucwords(strtolower($emailVariables['first_name'])) . " " . ucwords(strtolower($emailVariables['last_name'])),
																								);
																		break;


            case 'genericPasswordChange'  							:   if(!isset($emailVariables['first_name']) || !array_key_exists('first_name', $emailVariables))
																		{
																			throw new \Exception('missing first_name key for ' . $emailIdentifier . ' template');
																		}

																		if(!isset($emailVariables['last_name']) || !array_key_exists('last_name', $emailVariables))
																		{
																			throw new \Exception('missing last_name key for ' . $emailIdentifier . ' template');
																		}

																		$emailTextLink      =   'emails.auth.generic-password-change.text';
																		$emailHTMLLink      =   'emails.auth.generic-password-change.html';
																		$emailSubject       =   $this->companySite . ' Password Change Notification';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
                                                                                                    'EMAIL_SUBJECT'             =>  $emailSubject,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_EMAIL'    =>  $this->customerServiceEmail,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
                                                                                                    'ADD_THIS_EMAIL'            =>  $this->addThisEmail,
                                                                                                    'COMPANY_NAME'              =>  $this->companyName,
                                                                                                    'COMPANY_SITE'              =>  $this->companySite,
                                                                                                    'COMPANY_SIGN_EMAIL_AS'     =>  $this->companySignEmailAs,

																									'FIRST_NAME'                =>  ucwords(strtolower($emailVariables['first_name'])),
																									'LAST_NAME'                 =>  ucwords(strtolower($emailVariables['last_name'])),
																									'FULL_NAME'                 =>  ucwords(strtolower($emailVariables['first_name'])) . " " . ucwords(strtolower($emailVariables['last_name'])),
																								);
																		break;

            default : throw new \Exception('Need an email template identifier to continue.');
        }

        return  array
                (
                    'textView'          =>  $emailTextLink,
                    'htmlView'          =>  $emailHTMLLink,
                    'subject'           =>  $emailSubject,
                    'templateVariables' =>  $templateVariables
                );
    }
}
