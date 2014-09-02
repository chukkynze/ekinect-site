<?php
 /**
  * Class EmployeeEmailsTableSeeder
  *
  * filename:   EmployeeEmailsTableSeeder.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/31/14 2:37 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class EmployeeEmailsTableSeeder extends Seeder
{
    public function __construct()
    {
	    echo "------------------------------------------------------------------------------------";
        echo "Seeding Employee Emails Data.\n";
        $this->table        = 'employee_emails';
        $this->connection   = 'main_db';
    }


    /**
     * Run the database seeder for this table
     * 
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '-1');
        $startTime          =   strtotime("now");
	    DB::connection($this->connection)->table($this->table)->truncate();

	    EmployeeEmails::create(
		    array
            (
                'id'                    =>  1,
                'member_id'             =>  1,
                'is_primary'            =>  1,
                'email_address'         =>  'chukky.nze@ekinect.me',
                'verification_sent'     =>  1,
                'verification_sent_on'  =>  1409476775,
                'verified'              =>  1,
                'verified_on'           =>  1409476808,
            )
	    );

        $endTime    =   strtotime("now");
        $duration   =   $endTime-$startTime;
        echo "Seeded all rows in " . $duration . " seconds.\n";
	    echo "------------------------------------------------------------------------------------";
    }



}