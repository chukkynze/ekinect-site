<?php
 /**
  * Class MemberSiteStatus
  *
  * filename:   MemberSiteStatus.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:11 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class MemberSiteStatus extends Eloquent
{
    protected $table        =   'member_site_status';
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




    public function addMemberSiteStatus($newStatus, $memberID)
    {
        if($memberID > 0)
        {
            $newMemberSiteStatus    =   MemberSiteStatus::create
                                    (
                                        array
                                        (
                                            'member_id' =>  $memberID,
                                            'status'    =>  $newStatus,
                                        )
                                    );
            $newMemberSiteStatus->save();
            return TRUE;
        }
    }

    public function updateMemberSiteStatus($memberID, $fillableArray)
    {
        if($memberID > 0)
        {
            try
            {
                $MemberSiteStatus =   MemberSiteStatus::where("member_id","=", $memberID)->first();
                $MemberSiteStatus->fill($fillableArray);
                $MemberSiteStatus->save();
                return TRUE;
            }
            catch(\Whoops\Example\Exception $e)
            {
                throw new \Whoops\Example\Exception($e);
            }
        }
        else
        {
            throw new \Whoops\Example\Exception("MemberSiteStatus ID is invalid.");
        }
    }

    public function isMemberSiteStatusLocked($memberID)
    {
        $result     =   DB::connection($this->connection)->table($this->table)
                        ->select('status')
                        ->where('member_id'       , '=', $memberID)
                        ->orderBy('created_at', 'desc')
                        ->first()
        ;

        $MemberSiteStatus   =   $result->status;

        if(is_null($result))
        {
            $bool = FALSE;
        }
        elseif(substr($MemberSiteStatus, 0, 6) == 'Locked')
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