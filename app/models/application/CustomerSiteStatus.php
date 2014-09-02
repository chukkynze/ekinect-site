<?php
 /**
  * Class CustomerSiteStatus
  *
  * filename:   CustomerSiteStatus.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:11 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class CustomerSiteStatus extends Eloquent
{
    protected $table        =   'customer_site_status';
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




    public function addCustomerSiteStatus($newStatus, $memberID)
    {
        if($memberID > 0)
        {
            $newCustomerSiteStatus    =   CustomerSiteStatus::create
                                    (
                                        array
                                        (
                                            'member_id' =>  $memberID,
                                            'status'    =>  $newStatus,
                                        )
                                    );
            $newCustomerSiteStatus->save();
            return TRUE;
        }
    }

    public function updateCustomerSiteStatus($memberID, $fillableArray)
    {
        if($memberID > 0)
        {
            try
            {
                $CustomerSiteStatus =   CustomerSiteStatus::where("member_id","=", $memberID)->first();
                $CustomerSiteStatus->fill($fillableArray);
                $CustomerSiteStatus->save();
                return TRUE;
            }
            catch(\Whoops\Example\Exception $e)
            {
                throw new \Whoops\Example\Exception($e);
            }
        }
        else
        {
            throw new \Whoops\Example\Exception("CustomerSiteStatus member ID is invalid.");
        }
    }

    public function isCustomerSiteStatusLocked($memberID)
    {
        $result     =   DB::connection($this->connection)->table($this->table)
                        ->select('status')
                        ->where('member_id'       , '=', $memberID)
                        ->orderBy('created_at', 'desc')
                        ->first()
        ;

        $CustomerSiteStatus   =   $result->status;

        if(is_null($result))
        {
            $bool = FALSE;
        }
        elseif(substr($CustomerSiteStatus, 0, 6) == 'Locked')
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