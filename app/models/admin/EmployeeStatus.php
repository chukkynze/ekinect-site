<?php
 /**
  * Class
  *
  * filename:   EmployeeStatus.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:11 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class EmployeeStatus extends Eloquent
{
    protected $table        =   'employee_status';
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




    public function addEmployeeStatus($newStatus, $memberID)
    {
	    try
	    {
		    if($memberID > 0)
	        {
		        if($newStatus != "")
		        {
			        $newEmployeeStatus    =   EmployeeStatus::create
		                                    (
		                                        array
		                                        (
		                                            'member_id' =>  $memberID,
		                                            'status'    =>  $newStatus,
		                                        )
		                                    );
		            $newEmployeeStatus->save();
		            return TRUE;
		        }
		        else
		        {
			        throw new Exception("Empty employee status to be added.");
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
		    throw new Exception("Could not add new employee status [$newStatus] for member id [$memberID]. - " . $e );
	    }
    }

    public function checkEmployeeHasNoForce($memberID)
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
																	 * 2. Inform Employee not to do stupid things like change password to what it was before
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

    public function isEmployeeStatusLocked($memberID)
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