<?php
 /**
  * Class IpBin
  *
  * filename:   IpBin.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/22/14 10:10 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class IpBin extends Eloquent
{
    protected $table        =   'ip_bin';
    protected $primaryKey   =   'id';
    protected $connection   =   'utils_db';
    protected $fillable     =   array
                                (
                                    'user_id',
                                    'member_id',
                                    'ip_address',
                                    'ip_status',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );


    public function blockIPAddress($userID=0, $lockStatus, $memberID=0)
    {
        $newIPBinLock   =   IpBin::create
                            (
                                array
                                (
                                    'user_id'       =>  $userID,
                                    'member_id'     =>  $memberID,
                                    'ip_address'    =>  sprintf('%u', ip2long($_SERVER['REMOTE_ADDR'])),
                                    'ip_status'     =>  $lockStatus,
                                )
                            );
        $newIPBinLock->save();
    }

    public function isUserIPAddressAllowedAccess($ipBinID)
    {
        $result     =   DB::connection($this->connection)->table($this->table)
                        ->select('ip_status')
                        ->where('user_id'       , '=', $ipBinID)
                        ->orderBy('created_at', 'desc')
                        ->first()
        ;

        if(is_null($result))
        {
            $bool = TRUE;
        }
        elseif(isset($result) && FALSE == stristr($result->ip_status, 'Locked:'))
        {
            $bool = TRUE;
        }
        else
        {
            $bool = FALSE;
        }

        return $bool;
    }
}