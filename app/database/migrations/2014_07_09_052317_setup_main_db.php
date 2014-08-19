<?php
 /**
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/9/14 5:23 AM
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupMainDb extends Migration
{
    protected $connection   =   "main_db";

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        /**
         * Entity: SiteUser
         * Table: site_users
         */
		Schema::connection($this->connection)->dropIfExists('site_users');
		Schema::connection($this->connection)->create('site_users', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->integer('member_id')->default(0);
            $table->string('agent', 240)->default('');
            $table->integer('ip_address')->default(0);
            $table->enum('user_status', array
                                        (
                                            'Open',
                                            'Locked:Excessive-Login-Attempts',
                                            'Locked:Excessive-Signup-Attempts',
                                            'Locked:Excessive-ForgotLogin-Attempts',
                                            'Locked:Excessive-ChangeVerifiedLinkPassword-Attempts',
                                            'Locked:Excessive-ChangeOldPassword-Attempts',
                                            'Locked:Excessive-LostSignupVerification-Attempts'
                                        ))->default('Open');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(array('member_id')                , 'ndx1');
            $table->index(array('ip_address')               , 'ndx2');
            $table->index(array('id', 'member_id')          , 'ndx0_1');
            $table->index(array('id', 'agent')              , 'ndx0_a');
            $table->index(array('ip_address', 'agent')      , 'ndx2_a');
        });

        /**
         * Entity: SiteHit
         * Table: site_hits
         */
		Schema::connection($this->connection)->dropIfExists('site_hits');
		Schema::connection($this->connection)->create('site_hits', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->text('cookies');
            $table->text('url_location');
            $table->integer('client_time')->default(0);
            $table->integer('server_time')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(array('user_id')                      , 'ndx1');
            $table->index(array('client_time')                  , 'ndx2');
            $table->index(array('server_time')                  , 'ndx3');
            $table->index(array('client_time','server_time')    , 'ndx2_3');
            $table->index(array('server_time','user_id')        , 'ndx3_1');
            
            
        });

        /**
         * Entity: Member
         * Table: members
         */
		Schema::connection($this->connection)->dropIfExists('members');
		Schema::connection($this->connection)->create('members', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->enum('member_type', array
                                        (
                                            'unknown',
                                            'vendor',
                                            'vendor-client',
                                            'freelancer',
                                            'employee',
                                            'report-viewer',
                                        ));
            $table->string('password', 256);
            $table->string('salt1', 32);
            $table->string('salt2', 32);
            $table->string('salt3', 32);
            $table->tinyInteger('paused')->default(0);
            $table->tinyInteger('cancelled')->default(0);

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(array('salt1', 'salt2', 'salt3')  , 'ndx1');
        });

        /**
         * Entity: MemberStatus
         * Table: member_status
         */
		Schema::connection($this->connection)->dropIfExists('member_status');
		Schema::connection($this->connection)->create('member_status', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->integer('member_id');
            $table->enum('status',  array
                                    (
                                        'ValidMember',
                                        'Successful-Signup',
                                        'VerifiedEmail',
                                        'VerifiedStartupDetails',
                                        'BeginFirst90Days',
                                        'First90DaysPlus30',
                                        'TrialPeriodExpired',
                                        'Premium',
                                        'Standard',
                                        'Basic',
                                        'ChangedPassword',
                                        'Paused-Member',
                                        'Cancelled-Member',
                                        'Cancelled-Financial',
                                        'Locked:Excessive-Login-Attempts',
                                        'Locked:Excessive-Signup-Attempts',
                                        'Locked:Excessive-ForgotLogin-Attempts',
                                        'Locked:Excessive-ChangeVerifiedLinkPassword-Attempts',
                                        'Locked:Excessive-ChangeOldPassword-Attempts',
                                        'Locked:Excessive-LostSignupVerification-Attempts',
                                    ));

            $table->timestamps();

            // Indexes
            $table->index(array('member_id')                        , 'ndx1');
            $table->index(array('created_at')                       , 'ndx2');
            $table->index(array('member_id', 'status')              , 'ndx1_s');
            $table->index(array('member_id', 'created_at')          , 'ndx1_2');
            $table->index(array('member_id', 'status', 'created_at'), 'ndx1_s_2');
        });

        /**
         * Entity: MemberEmails
         * Table: member_emails
         */
		Schema::connection($this->connection)->dropIfExists('member_emails');
		Schema::connection($this->connection)->create('member_emails', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->integer('member_id');
            $table->tinyInteger('is_primary')->default(1);
            $table->string('email_address', 120);
            $table->tinyInteger('verification_sent');
            $table->integer('verification_sent_on');
            $table->tinyInteger('verified');
            $table->integer('verified_on');

            $table->timestamps();

            // Indexes
            $table->unique(array('member_id', 'email_address')                                              , 'ndx1');
            $table->unique(array('email_address')                                                           , 'ndx2');

            $table->index(array('verification_sent')                                                        , 'ndx3');
            $table->index(array('verification_sent_on')                                                     , 'ndx4');
            $table->index(array('verified')                                                                 , 'ndx5');
            $table->index(array('verified_on')                                                              , 'ndx6');
            $table->index(array('created_at', 'email_address')                                              , 'ndx7');
            $table->index(array('updated_at', 'email_address')                                              , 'ndx8');
            $table->index(array('email_address', 'verified', 'verification_sent', 'verification_sent_on')   , 'ndx9');
        });

        /**
         * Entity: MemberDetails
         * Table: member_details
         */
		Schema::connection($this->connection)->dropIfExists('member_details');
		Schema::connection($this->connection)->create('member_details', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->integer('member_id');
            $table->string('prefix', 60)->default("");
            $table->string('first_name', 60);
            $table->string('mid_name1', 60)->default("");
            $table->string('mid_name2', 60)->default("");
            $table->string('last_name', 60);
            $table->string('display_name', 60)->default("");
            $table->string('suffix', 60)->default("");
            $table->integer('gender');
            $table->date('birth_date')->default("0000-00-00");
            $table->string('zipcode', 8);
            $table->text('personal_summary');
            $table->text('profile_pic_url');
            $table->text('personal_website_url');
            $table->text('linkedin_url');
            $table->text('google_plus_url');
            $table->text('twitter_url');
            $table->text('facebook_url');

            $table->timestamps();

            // Indexes
            $table->unique(array('member_id')               , 'ndx1');

            $table->index(array('member_id','id')           , 'ndx2');
            $table->index(array('member_id','birth_date')   , 'ndx3');
            $table->index(array('member_id','gender')       , 'ndx4');
        });

        /**
         * Entity: MemberDetailsAddresses
         * Table: member_details_addresses
         */
		Schema::connection($this->connection)->dropIfExists('member_details_addresses');
		Schema::connection($this->connection)->create('member_details_addresses', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->integer('member_id');
            $table->integer('address_type')->default(1);
            $table->string('business_name', 160);
            $table->string('address_line_1', 160);
            $table->string('address_line_2', 160);
            $table->string('address_line_3', 160);
            $table->string('county', 160);
            $table->string('city', 160);
            $table->string('state', 8);
            $table->string('zipcode', 8);
            $table->string('zipcode_ext', 8);

            $table->timestamps();

            // Indexes
            $table->unique(array('member_id','address_type')            , 'ndx1');

            $table->index(array('member_id','county')                   , 'ndx2');
            $table->index(array('member_id','state')                    , 'ndx3');
            $table->index(array('member_id', 'state', 'county')         , 'ndx4');
            $table->index(array('member_id','zipcode')                  , 'ndx5');
            $table->index(array('member_id','zipcode', 'zipcode_ext')   , 'ndx6');
            $table->index(array('address_type','business_name')         , 'ndx7');
            $table->index(array
                            (
                                'address_type',
                                'business_name',
                                'address_line_1',
                                'address_line_2',
                                'address_line_3',
                                'county',
                                'city',
                                'state',
                                'zipcode',
                                'zipcode_ext'
                            )                                       , 'ndx8');
        });

        /**
         * Entity: MemberDetailsContactInfo
         * Table: member_details_contact_info
         */
		Schema::connection($this->connection)->dropIfExists('member_details_contact_info');
		Schema::connection($this->connection)->create('member_details_contact_info', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->integer('member_id');
            $table->string('business_email', 120);
            $table->string('phone_number', 24);
            $table->string('fax_number', 24);
            $table->string('cell_number', 24);

            $table->timestamps();

            // Indexes
            $table->index(array('member_id','business_email')   , 'ndx1');
            $table->index(array('member_id','phone_number')     , 'ndx2');
            $table->index(array('member_id','fax_number')       , 'ndx3');
            $table->index(array('member_id','cell_number')      , 'ndx4');
        });

        /**
         * Entity: EmailStatus
         * Table: email_status
         */
		Schema::connection($this->connection)->dropIfExists('email_status');
		Schema::connection($this->connection)->create('email_status', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->string('email_address', 120);
            $table->enum('email_address_status', array
                                                (
                                                    'AddedUnverified',
                                                    'VerificationSent',
                                                    'VerificationSentAgain',
                                                    'Verified',
                                                    'Forgot',
                                                    'LostSignupVerification',
                                                    'Remembered',
                                                    'Paused',
                                                    'MadeDefault',
                                                    'Deleted',
                                                    'ChangedPassword',
                                                    'Locked:Excessive-Login-Attempts',
                                                    'Locked:Excessive-Signup-Attempts',
                                                    'Locked:Excessive-ForgotLogin-Attempts',
                                                    'Locked:Excessive-ChangeVerifiedLinkPassword-Attempts',
                                                    'Locked:Excessive-ChangeOldPassword-Attempts',
                                                    'Locked:Excessive-LostSignupVerification-Attempts',
                                                ));
            $table->timestamps();

            // Indexes
            $table->index(array('email_address')                                        , 'ndx1');
            $table->index(array('email_address','email_address_status')                 , 'ndx2');
            $table->index(array('email_address','email_address_status' ,'created_at')   , 'ndx3');
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        /**
         * Entity: SiteUser
         * Table: site_user
         */
		Schema::connection($this->connection)->dropIfExists('site_user');
        /**
         * Entity: SiteHit
         * Table: site_hits
         */
        Schema::connection($this->connection)->dropIfExists('site_hit');
        /**
         * Entity: Member
         * Table: members
         */
        Schema::connection($this->connection)->dropIfExists('members');
        /**
         * Entity: MemberStatus
         * Table: member_status
         */
		Schema::connection($this->connection)->dropIfExists('member_status');
        /**
         * Entity: MemberEmails
         * Table: member_emails
         */
		Schema::connection($this->connection)->dropIfExists('member_emails');
        /**
         * Entity: MemberDetails
         * Table: member_details
         */
		Schema::connection($this->connection)->dropIfExists('member_details');
        /**
         * Entity: MemberDetailsAddresses
         * Table: member_details_addresses
         */
		Schema::connection($this->connection)->dropIfExists('member_details_addresses');
        /**
         * Entity: MemberDetailsContactInfo
         * Table: member_details_contact_info
         */
		Schema::connection($this->connection)->dropIfExists('member_details_contact_info');
        /**
         * Entity: EmailStatus
         * Table: email_status
         */
		Schema::connection($this->connection)->dropIfExists('email_status');
	}

}
