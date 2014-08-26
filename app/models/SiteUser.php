<?php
 /**
  * Class SiteUser
  *
  * filename:   SiteUser.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:11 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class SiteUser extends Eloquent
{
    protected $table        =   'site_users';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'member_id',
                                    'agent',
                                    'ip_address',
                                    'user_status',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );


    public function getId()
    {
        return $this->id;
    }


    public function getMemberId()
    {
        return $this->member_id;
    }

    public function setUserStatus($lockStatus, $userID)
    {
        $SiteUser   =   SiteUser::where("id","=", $userID)->first();
        $newData    =   array
                        (
                            'user_status'   =>  $lockStatus,
                        );
        $SiteUser->fill($newData);
        $SiteUser->save();
    }

    public function setSiteUserMemberID($userID, $memberID)
    {
        $SiteUser   =   SiteUser::where("id","=", $userID)->first();
        $newData    =   array
                        (
                            'member_id'   =>  $memberID,
                        );
        $SiteUser->fill($newData);
        $SiteUser->save();
    }
}