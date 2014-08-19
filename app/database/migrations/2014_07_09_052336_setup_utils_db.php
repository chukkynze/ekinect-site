<?php
 /**
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/9/14 5:23 AM
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupUtilsDb extends Migration
{
    protected $connection   =   "utils_db";

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/**
         * Entity: AccessAttempt
         * Table: access_attempts
         */
		Schema::connection($this->connection)->dropIfExists('access_attempts');
		Schema::connection($this->connection)->create('access_attempts', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->enum('attempt_type',array
                                        (
                                            'LoginForm',
                                            'LoginCaptchaForm',
                                            'SignupForm',
                                            'VerificationDetailsForm',
                                            'ForgotForm',
                                            'LostSignupVerificationForm',
                                            'ChangePasswordWithVerifyLinkForm',
                                            'ChangePasswordWithOldPasswordForm'
                                        ));
            $table->string('success', 160)->default('0');
            $table->integer('attempted_at')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(array('user_id')                                              , 'ndx1');
            $table->index(array('attempt_type')                                         , 'ndx2');
            $table->index(array('success')                                              , 'ndx3');
            $table->index(array('attempted_at')                                         , 'ndx4');
            $table->index(array('user_id', 'attempt_type')                              , 'ndx1_2');
            $table->index(array('user_id', 'success')                                   , 'ndx1_3');
            $table->index(array('attempt_type', 'success')                              , 'ndx2_3');
            $table->index(array('attempt_type', 'attempted_at')                         , 'ndx2_4');
            $table->index(array('user_id', 'attempt_type', 'success')                   , 'ndx1_2_3');
            $table->index(array('user_id', 'attempt_type', 'success', 'attempted_at')   , 'ndx1_2_3_4');
        });

		/**
         * Entity: IpBin
         * Table: ip_bin
         */
		Schema::connection($this->connection)->dropIfExists('ip_bin');
		Schema::connection($this->connection)->create('ip_bin', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('member_id');
            $table->integer('ip_address')->default(0);
            $table->string('ip_status', 160)->default('0');

            $table->timestamps();

            // Indexes
            $table->index(array('user_id')                  , 'ndx1');
            $table->index(array('member_id')                , 'ndx2');
            $table->index(array('ip_address')               , 'ndx3');
            $table->index(array('ip_address', 'ip_status')  , 'ndx3_is');
        });

		/**
         * Entity: LocationData
         * Table: location_data
         */
		Schema::connection($this->connection)->dropIfExists('location_data');
		Schema::connection($this->connection)->create('location_data', function($table)
        {
            // Parameters
            $table->engine = 'InnoDB';

            // Primary Key
            $table->increments('id');

            // Columns
            $table->string('country_name'   , 120)->default('USA');
            $table->string('postal_code'    ,  16)->default('');
            $table->string('city'           , 120)->default('');
            $table->string('county_name'    , 120)->default('');
            $table->string('county_fips'    ,  24)->default('');
            $table->string('state_name'     , 120)->default('');
            $table->string('state_abbr'     ,   8)->default('');
            $table->string('area_codes'     ,  40)->default('');
            $table->string('time_zone'      , 120)->default('');
            $table->string('utc'            ,   8)->default('');
            $table->string('dst'            ,   8)->default('');
            $table->string('longitude'      ,  24)->default('');
            $table->string('latitude'       ,  24)->default('');

            // Indexes
            $table->index(array('city','state_abbr','postal_code','longitude','latitude')   , 'ndx1');
            $table->index(array('state_abbr','postal_code','county_name')                   , 'ndx2');
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
         * Entity: AccessAttempt
         * Table: access_attempts
         */
		Schema::connection($this->connection)->dropIfExists('access_attempts');

        /**
         * Entity: IpBin
         * Table: ip_bin
         */
		Schema::connection($this->connection)->dropIfExists('ip_bin');

		/**
         * Entity: LocationData
         * Table: location_data
         */
		Schema::connection($this->connection)->dropIfExists('location_data');
	}

}
