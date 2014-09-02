<?php

class DatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('MembersTableSeeder');

		$this->call('EmployeeDetailsTableSeeder');
		$this->call('EmployeeEmailsTableSeeder');
		$this->call('EmployeeEmailStatusTableSeeder');
		$this->call('EmployeeSiteStatusTableSeeder');
		$this->call('EmployeeStatusTableSeeder');

		$this->call('LocationDataTableSeeder');
	}

}
