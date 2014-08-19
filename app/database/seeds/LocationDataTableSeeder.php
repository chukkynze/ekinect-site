<?php
 /**
  * Class LocationDataTableSeeder
  *
  * filename:   LocationDataTableSeeder.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/23/14 9:34 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class LocationDataTableSeeder extends BaseSeeder
{
    public function __construct()
    {
        echo "Seeding Location Data.\n";
        $this->table        = 'location_data';
        $this->connection   = 'utils_db';
        $this->filename     = app_path().'/database/csv/location_data.csv';
        $this->rowbreak     = 1000;
    }


    public function run()
    {
        ini_set('memory_limit', '-1');
        $startTime          =   strtotime("now");
        $seedData           =   $this->seedFromCSV($this->filename, ',');
        $totalRowsToInsert  =   isset($seedData) ? count($seedData) : 0;
        $totalBreaksNeeded  =   ceil(($totalRowsToInsert/$this->rowbreak));
        echo "Extracted " . $totalRowsToInsert . " rows from file.\n";

        if($totalRowsToInsert > 0)
        {
            DB::connection($this->connection)->table($this->table)->truncate();
            $offset=0;
            $rowsInsertedSoFar=0;
            $loopSecondsSummed=0;
            for($i=1; $i<=$totalBreaksNeeded; $i++)
            {
                $loopStartTime      =   strtotime("now");
                $chunk              =   array_slice($seedData, $offset, $this->rowbreak);
                $offset             =   $offset+$this->rowbreak;
                DB::connection($this->connection)->table($this->table)->insert($chunk);
                $rowsInsertedSoFar  =   $rowsInsertedSoFar + count($chunk);
                $loopEndTime        =   strtotime("now");
                $loopSeconds        =   $loopEndTime-$loopStartTime;
                $loopSecondsSummed  =   $loopSecondsSummed + ($loopSeconds);

                echo "Inserted " . count($chunk) . " - " . $rowsInsertedSoFar . " of "
                    . $totalRowsToInsert . " rows [" . number_format((($rowsInsertedSoFar/$totalRowsToInsert)*100),2)
                    . "%] in " . ($loopSeconds) . " seconds: " . $loopSecondsSummed . " seconds so far.\n";
            }
        }

        $endTime    =   strtotime("now");
        $duration   =   $endTime-$startTime;
        echo "Seeded " . $totalRowsToInsert . " rows in " . $duration . " seconds.\n";
    }

    /**
     * Collect data from a given CSV file and return as array
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
            #$x=0;
            while(($row = fgetcsv($handle, 1000, $deliminator)) !== FALSE)
            {
                if(!$header)
                {
                    $header =   $row;
                }
                else
                {
                    $row[1] =   str_pad($row[1], 5, 0, STR_PAD_LEFT);   // PO Box needs zeros
                    $data[] =   array_combine($header, $row);
                }
                #$x++;
                #if($x==50)break;
            }
            fclose($handle);
        }

        return $data;
    }
}