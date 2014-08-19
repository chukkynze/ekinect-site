<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| PDO Fetch Style
	|--------------------------------------------------------------------------
	|
	| By default, database results will be returned as instances of the PHP
	| stdClass object; however, you may desire to retrieve records in an
	| array format for simplicity. Here you can tweak the fetch style.
	|
	*/

	'fetch' => PDO::FETCH_CLASS,

	/*
	|--------------------------------------------------------------------------
	| Default Database Connection Name
	|--------------------------------------------------------------------------
	|
	| Here you may specify which of the database connections below you wish
	| to use as your default connection for all database work. Of course
	| you may use many connections at once using the Database library.
	|
	*/

	'default' => 'main_db',

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| Here are each of the database connections setup for your application.
	| Of course, examples of configuring each database platform that is
	| supported by Laravel is shown below to make development simple.
	|
	|
	| All database work in Laravel is done through the PHP PDO facilities
	| so make sure you have the driver for your particular database of
	| choice installed on your machine before you begin development.
	|
	*/

	'connections'   =>  array
                        (

                            'main_db'   =>  array
                                            (
                                                'driver'    => $_ENV['DB_CONNECTION_main_db_driver'],
                                                'host'      => $_ENV['DB_CONNECTION_main_db_host'],
                                                'database'  => $_ENV['DB_CONNECTION_main_db_database'],
                                                'username'  => $_ENV['DB_CONNECTION_main_db_username'],
                                                'password'  => $_ENV['DB_CONNECTION_main_db_password'],
                                                'charset'   => $_ENV['DB_CONNECTION_main_db_charset'],
                                                'collation' => $_ENV['DB_CONNECTION_main_db_collation'],
                                                'prefix'    => $_ENV['DB_CONNECTION_main_db_prefix'],
                                            ),

                            'utils_db'  =>  array
                                            (
                                                'driver'    => $_ENV['DB_CONNECTION_utils_db_driver'],
                                                'host'      => $_ENV['DB_CONNECTION_utils_db_host'],
                                                'database'  => $_ENV['DB_CONNECTION_utils_db_database'],
                                                'username'  => $_ENV['DB_CONNECTION_utils_db_username'],
                                                'password'  => $_ENV['DB_CONNECTION_utils_db_password'],
                                                'charset'   => $_ENV['DB_CONNECTION_utils_db_charset'],
                                                'collation' => $_ENV['DB_CONNECTION_utils_db_collation'],
                                                'prefix'    => $_ENV['DB_CONNECTION_utils_db_prefix'],
                                            ),

                            'queue_db'  =>  array
                                            (
                                                'driver'    => $_ENV['DB_CONNECTION_queue_db_driver'],
                                                'host'      => $_ENV['DB_CONNECTION_queue_db_host'],
                                                'database'  => $_ENV['DB_CONNECTION_queue_db_database'],
                                                'username'  => $_ENV['DB_CONNECTION_queue_db_username'],
                                                'password'  => $_ENV['DB_CONNECTION_queue_db_password'],
                                                'charset'   => $_ENV['DB_CONNECTION_queue_db_charset'],
                                                'collation' => $_ENV['DB_CONNECTION_queue_db_collation'],
                                                'prefix'    => $_ENV['DB_CONNECTION_queue_db_prefix'],
                                            ),

                        ),

	/*
	|--------------------------------------------------------------------------
	| Migration Repository Table
	|--------------------------------------------------------------------------
	|
	| This table keeps track of all the migrations that have already run for
	| your application. Using this information, we can determine which of
	| the migrations on disk haven't actually been run in the database.
	|
	*/

	'migrations' => 'freelife_migrations',

	/*
	|--------------------------------------------------------------------------
	| Redis Databases
	|--------------------------------------------------------------------------
	|
	| Redis is an open source, fast, and advanced key-value store that also
	| provides a richer set of commands than a typical key-value systems
	| such as APC or Memcached. Laravel makes it easy to dig right in.
	|
	*/

	'redis' => array(

		'cluster' => false,

		'default' => array(
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 0,
		),

	),

);
