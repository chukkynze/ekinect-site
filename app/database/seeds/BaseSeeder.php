<?php
 /**
  * Class BaseSeeder
  *
  * filename:   BaseSeeder.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/23/14 9:37 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaseSeeder extends Seeder
{
    /**
     * DB table name
     *
     * @var string
     */
    protected $table;

    /**
     * CSV filename
     *
     * @var string
     */
    protected $filename;

    /**
     * config connection
     *
     * @var string
     */
    protected $connection;

    /**
     * In the case of large CSV files this is the number of rows in one chunk of broken off data
     *
     * @var string
     */
    protected $rowbreak;

    /**
     * Run DB seed if the content of the csv file is as needed for insertion.
     * Overwrite this method in seeder class if you need custom row formatting
     */
    public function run()
    {
        ini_set('memory_limit', '-1');

        DB::connection($this->connection)->table($this->table)->truncate();
        $seedData   =   $this->seedFromCSV($this->filename, ',');
        DB::connection($this->connection)->table($this->table)->insert($seedData);
    }

    /**
     * Collect data from a given CSV file and return as array if the content of the csv file
     * is as needed for insertion.
     * Overwrite this method in seeder class if you need custom row formatting
     *
     * @param $filename
     * @param string $deliminator
     *
     * @return array|bool
     */
    protected function seedFromCSV($filename, $deliminator = ",")
    {
        if(!file_exists($filename) || !is_readable($filename))
        {
            return FALSE;
        }

        $header =   NULL;
        $data   =   array();

        if(($handle = fopen($filename, 'r')) !== FALSE)
        {
            $x=0;
            while(($row = fgetcsv($handle, 1000, $deliminator)) !== FALSE)
            {
                if(!$header)
                {
                    $header =   $row;
                    print_r($header);
                }
                else
                {
                    $xdata =   array_combine($header, $row);
                    $data[] =   $xdata;
                    print_r($xdata);
                }
                $x++;
                if($x==5)break;
            }
            fclose($handle);
        }

        return $data;
    }

}