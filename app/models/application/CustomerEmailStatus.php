<?php
 /**
  * Class CustomerEmailStatus
  *
  * filename:   CustomerEmailStatus.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/31/14 8:46 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class CustomerEmailStatus extends Eloquent
{
    protected $table        =   'customer_email_status';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'email_address',
                                    'email_address_status',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );


    public function addCustomerEmailStatus($emailAddress, $status)
    {
        $newCustomerEmailStatus     =   CustomerEmailStatus::create
			                            (
			                                array
			                                (
			                                    'email_address'         =>  $emailAddress,
			                                    'email_address_status'  =>  $status,
			                                )
			                            );
        $newCustomerEmailStatus->save();
    }

    public function checkForPreviousCustomerEmailStatus($emailAddress, $status)
    {
        $count  =   DB::connection($this->connection)->table($this->table)
                        ->select('id')
                        ->where('email_address'         , '='   , $emailAddress)
                        ->where('email_address_status'  , '='   , $status)
                        ->where('created_at'            , '<='  , date("Y-m-d H:i:s"))
                        ->count();

        return ($count == 1 ? TRUE : FALSE);
    }

	public function getCustomerEmailStatus($emailAddress)
	{
        $result     =   DB::connection($this->connection)->table($this->table)
                        ->select('email_address_status')
                        ->where('email_address'       , '=', $emailAddress)
                        ->orderBy('created_at', 'desc')
                        ->first()
        ;

		return $result->email_address_status;
	}
}