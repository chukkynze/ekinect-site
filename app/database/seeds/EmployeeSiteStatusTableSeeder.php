<?php
 /**
  * Class EmployeeSiteStatusTableSeeder
  *
  * filename:   EmployeeSiteStatusTableSeeder.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/31/14 2:35 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class EmployeeSiteStatusTableSeeder extends Seeder
{
    public function __construct()
    {
	    echo "------------------------------------------------------------------------------------";
        echo "Seeding Employee Site Status Data.\n";
        $this->table        = 'employee_site_status';
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

	    EmployeeSiteStatus::create(
		    array
            (
                'id'        =>  '1',
                'member_id' =>  '1',
                'status'    =>  'Employee startup details complete.',
            )
	    );

        $endTime    =   strtotime("now");
        $duration   =   $endTime-$startTime;
        echo "Seeded all rows in " . $duration . " seconds.\n";
	    echo "------------------------------------------------------------------------------------";
    }



}