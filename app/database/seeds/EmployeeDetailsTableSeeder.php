<?php
 /**
  * Class EmployeeDetailsTableSeeder
  *
  * filename:   EmployeeDetailsTableSeeder.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/31/14 2:33 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class EmployeeDetailsTableSeeder extends Seeder
{
    public function __construct()
    {
	    echo "------------------------------------------------------------------------------------";
        echo "Seeding Employee Details Data.\n";
        $this->table        = 'employee_details';
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

	    EmployeeDetails::create(
		    array
            (
                'id'                    =>  1,
                'member_id'             =>  1,
                'prefix'                =>  '',
                'first_name'            =>  'Chukky',
                'mid_name1'             =>  '',
                'mid_name2'             =>  '',
                'last_name'             =>  'Nze',
                'display_name'          =>  'Chukky',
                'suffix'                =>  '',
                'gender'                =>  1,
                'birth_date'            =>  '0000-00-00',
                'zipcode'               =>  '91607',
                'personal_summary'      =>  '',
                'profile_pic_url'       =>  '',
                'title'                 =>  '',
                'department'            =>  'SuperUser',
                'hire_date'             =>  '2014-08-31',
                'fire_date'             =>  '0000-00-00',
            )
	    );

        $endTime    =   strtotime("now");
        $duration   =   $endTime-$startTime;
        echo "Seeded all rows in " . $duration . " seconds.\n";
	    echo "------------------------------------------------------------------------------------";
    }



}