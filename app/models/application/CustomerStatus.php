<?php
 /**
  * Class
  *
  * filename:   CustomerStatus.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:11 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class CustomerStatus extends Eloquent
{
    protected $table        =   'customer_status';
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




    public function addCustomerStatus($newStatus, $memberID)
    {
	    try
	    {
		    if($memberID > 0)
	        {
		        if($newStatus != "")
		        {
			        $newCustomerStatus    =   CustomerStatus::create
		                                    (
		                                        array
		                                        (
		                                            'member_id' =>  $memberID,
		                                            'status'    =>  $newStatus,
		                                        )
		                                    );
		            $newCustomerStatus->save();
		            return TRUE;
		        }
		        else
		        {
			        throw new Exception("Empty member status to be added.");
		        }
	        }
	        else
	        {
		        Log::info("Empty status [". $newStatus ."].");
		        throw new Exception("Empty member id to be added.");
	        }
	    }
	    catch(Exception $e)
	    {
		    throw new Exception("Could not add new member status [$newStatus] for member id [$memberID]. - " . $e );
	    }
    }

    public function checkCustomerHasNoForce($memberID)
    {
        $result     =   DB::connection($this->connection)->table($this->table)
                        ->select('status')
                        ->where('member_id'       , '=', $memberID)
                        ->orderBy('created_at', 'desc')
                        ->first()
        ;

        $memberStatus   =   $result->status;

        if(is_null($result))
        {
            $AttemptStatus 			=	TRUE;
			$AttemptStatusRoute 	=	'';
        }
        elseif(substr($memberStatus, 0, 6) == 'Force:')
		{
			switch($memberStatus)
			{
				case 'Force:ChangePasswordWithVerifyEmailLink' 	:   $AttemptStatus 			=	FALSE;
																	$AttemptStatusRoute 	=	'change-password-verification';
																	break;

				case 'Force:ChangePasswordWithOldPassword'		:   /**
																	 * Force member to change password
																	 * Keep in mind this presents a slew of problems
																	 * 1. Password cannot be the same as the previous
																	 * 2. Inform customer not to do stupid things like change password to what it was before
																	 * 3. Add Status 'PasswordChange:WithOld
																	 */
																	$AttemptStatus 			=	FALSE;
																	$AttemptStatusRoute 	=	'force-change-password-2';
																	break;

				default : 	$AttemptStatus 			=	TRUE;
							$AttemptStatusRoute 	=	'';
			}
		}
		else
		{
			$AttemptStatus 			=	TRUE;
			$AttemptStatusRoute 	=	'';
		}

		return 	array
				(
					'AttemptStatus' 		=>	$AttemptStatus,
					'AttemptStatusRoute' 	=>	$AttemptStatusRoute,
				);
    }

    public function isCustomerStatusLocked($memberID)
    {
        $result     =   DB::connection($this->connection)->table($this->table)
                        ->select('status')
                        ->where('member_id'       , '=', $memberID)
                        ->orderBy('created_at', 'desc')
                        ->first()
        ;

        $memberStatus   =   $result->status;

        if(is_null($result))
        {
            $bool = FALSE;
        }
        elseif(substr($memberStatus, 0, 6) == 'Locked')
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