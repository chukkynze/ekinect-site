<?php
 /**
  * Class EmployeeEmailStatusTableSeeder
  *
  * filename:   EmployeeEmailStatusTableSeeder.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/31/14 2:34 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class EmployeeEmailStatusTableSeeder extends Seeder
{
    public function __construct()
    {
	    echo "------------------------------------------------------------------------------------";
        echo "Seeding Employee Email Status Data.\n";
        $this->table        = 'employee_email_status';
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

	    EmployeeEmailStatus::create(
		    array
            (
                'id'                    =>  '1',
                'email_address'         =>  'chukky.nze@ekinect.me',
                'email_address_status'  =>  'AddedUnverified',
            )
	    );

	    EmployeeEmailStatus::create(
		    array
            (
                'id'                    =>  '2',
                'email_address'         =>  'chukky.nze@ekinect.me',
                'email_address_status'  =>  'VerificationSent',
            )
	    );

	    EmployeeEmailStatus::create(
		    array
            (
                'id'                    =>  '3',
                'email_address'         =>  'chukky.nze@ekinect.me',
                'email_address_status'  =>  'Verified',
            )
	    );

        $endTime    =   strtotime("now");
        $duration   =   $endTime-$startTime;
        echo "Seeded all rows in " . $duration . " seconds.\n";
	    echo "------------------------------------------------------------------------------------";
    }



}