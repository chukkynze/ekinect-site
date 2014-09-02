<?php
 /**
  * Class EmployeeStatusTableSeeder
  *
  * filename:   EmployeeStatusTableSeeder.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/31/14 2:36 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class EmployeeStatusTableSeeder extends Seeder
{
    public function __construct()
    {
	    echo "------------------------------------------------------------------------------------";
        echo "Seeding Employee Status Data.\n";
        $this->table        = 'employee_status';
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

	    EmployeeStatus::create(
		    array
            (
                'id'        =>  '1',
                'member_id' =>  '1',
                'status'    =>  'Successful-Signup',
            )
	    );

	    EmployeeStatus::create(
		    array
            (
                'id'        =>  '2',
                'member_id' =>  '1',
                'status'    =>  'VerifiedEmail',
            )
	    );

	    EmployeeStatus::create(
		    array
            (
                'id'        =>  '3',
                'member_id' =>  '1',
                'status'    =>  'VerifiedStartupDetails',
            )
	    );

	    EmployeeStatus::create(
		    array
            (
                'id'        =>  '4',
                'member_id' =>  '1',
                'status'    =>  'ValidEmployee',
            )
	    );


        $endTime    =   strtotime("now");
        $duration   =   $endTime-$startTime;
        echo "Seeded all rows in " . $duration . " seconds.\n";
	    echo "------------------------------------------------------------------------------------";
    }



}