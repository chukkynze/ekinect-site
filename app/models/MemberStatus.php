<?php
 /**
  * Class MemberStatus
  *
  * filename:   MemberStatus.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:11 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class MemberStatus extends Eloquent
{
    protected $table        =   'member_status';
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




    public function addMemberStatus($newStatus, $memberID)
    {
        if($memberID > 0)
        {
            $newMemberStatus    =   MemberStatus::create
                                    (
                                        array
                                        (
                                            'member_id' =>  $memberID,
                                            'status'    =>  $newStatus,
                                        )
                                    );
            $newMemberStatus->save();
            return TRUE;
        }
    }

    public function updateMemberStatus($memberID, $fillableArray)
    {
        if($memberID > 0)
        {
            try
            {
                $MemberStatus =   MemberStatus::where("member_id","=", $memberID)->first();
                $MemberStatus->fill($fillableArray);
                $MemberStatus->save();
                return TRUE;
            }
            catch(\Whoops\Example\Exception $e)
            {
                throw new \Whoops\Example\Exception($e);
            }
        }
        else
        {
            throw new \Whoops\Example\Exception("MemberStatus ID is invalid.");
        }
    }

    public function checkMemberHasNoForce($memberID)
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
																	 * 2. Inform members not to do stupid things like change password to what it was before
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

    public function isMemberStatusLocked($memberID)
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