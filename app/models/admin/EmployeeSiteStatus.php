<?php
 /**
  * Class EmployeeSiteStatus
  *
  * filename:   EmployeeSiteStatus.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:11 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class EmployeeSiteStatus extends Eloquent
{
    protected $table        =   'employee_site_status';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'member_id',
                                    'status',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );




    public function addEmployeeSiteStatus($newStatus, $memberID)
    {
        if($memberID > 0)
        {
            $newEmployeeSiteStatus  =   EmployeeSiteStatus::create
	                                    (
	                                        array
	                                        (
	                                            'member_id' =>  $memberID,
	                                            'status'    =>  $newStatus,
	                                        )
	                                    );
            $newEmployeeSiteStatus->save();
            return TRUE;
        }
    }

    public function updateEmployeeSiteStatus($memberID, $fillableArray)
    {
        if($memberID > 0)
        {
            try
            {
                $EmployeeSiteStatus =   EmployeeSiteStatus::where("member_id","=", $memberID)->first();
                $EmployeeSiteStatus->fill($fillableArray);
                $EmployeeSiteStatus->save();
                return TRUE;
            }
            catch(\Whoops\Example\Exception $e)
            {
                throw new \Whoops\Example\Exception($e);
            }
        }
        else
        {
            throw new \Whoops\Example\Exception("EmployeeSiteStatus member ID is invalid.");
        }
    }

    public function isEmployeeSiteStatusLocked($memberID)
    {
        $result     =   DB::connection($this->connection)->table($this->table)
                        ->select('status')
                        ->where('member_id'       , '=', $memberID)
                        ->orderBy('created_at', 'desc')
                        ->first()
        ;

        $EmployeeSiteStatus   =   $result->status;

        if(is_null($result))
        {
            $bool = FALSE;
        }
        elseif(substr($EmployeeSiteStatus, 0, 6) == 'Locked')
		{
			$bool = TRUE;
		}
		else
		{
			$bool = FALSE;
		}

		return 	$bool;
    }
}