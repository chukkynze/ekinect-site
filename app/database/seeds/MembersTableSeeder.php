<?php
 /**
  * Class MembersTableSeeder
  *
  * filename:   MembersTableSeeder.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/31/14 2:37 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class MembersTableSeeder extends BaseSeeder
{
    public function __construct()
    {
	    echo "------------------------------------------------------------------------------------";
        echo "Seeding Members Data.\n";
        $this->table        = 'members';
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

	    Member::create(
		    array
            (
                'id'                =>  1,
                'member_type'       =>  'employee',
                'password'          =>  '$2y$10$enFuIaT2eE3CfrDF/hTgOe6JqlmrOLq/94XhfTMRqS8DcqCRxE5WK',
                'salt1'             =>  '515402e8a6657711.43498175',
                'salt2'             =>  '235402e8a66577a0.22657932',
                'salt3'             =>  '155402e8a66577d2.67966433',
                'paused'            =>  0,
                'cancelled'         =>  0,
                'remember_token'    =>  '',
            )
	    );

        $endTime    =   strtotime("now");
        $duration   =   $endTime-$startTime;
        echo "Seeded all rows in " . $duration . " seconds.\n";
	    echo "------------------------------------------------------------------------------------";
    }



}