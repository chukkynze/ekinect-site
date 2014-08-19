<?php
 /**
  * Class Member
  *
  * filename:   Member.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:04 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
 

class Member extends Eloquent
             implements UserInterface, RemindableInterface
{
	use UserTrait, RemindableTrait;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table        =   'members';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'member_type',
                                    'password',
                                    'salt1',
                                    'salt2',
                                    'salt3',
                                    'paused',
                                    'cancelled',
                                    'remember_token'
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden       =   array
                                (
                                    'password',
                                    'salt1',
                                    'salt2',
                                    'salt3',
                                );

    /**
     * Get the unique identifier for the member.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the member.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }




    public function addMember($LoginCredentials)
    {
        $NewMember  =   Member::create
                        (
                            array
                            (
                                'member_type'       =>  'unknown',
                                'password'          =>  Hash::make($LoginCredentials[0]),
                                'salt1'             =>  $LoginCredentials[1],
                                'salt2'             =>  $LoginCredentials[2],
                                'salt3'             =>  $LoginCredentials[3],
                                'paused'            =>  0,
                                'cancelled'         =>  0,
                                'remember_token'    =>  '',
                            )
                        );
        $NewMember->save();
        return $NewMember->id;
    }

    public function updateMember($memberID, $fillableArray)
    {
        if($memberID > 0)
        {
            try
            {
                $Member =   Member::where("id","=", $memberID)->first();
                $Member->fill($fillableArray);
                $Member->save();
                return TRUE;
            }
            catch(\Whoops\Example\Exception $e)
            {
                throw new \Whoops\Example\Exception($e);
            }
        }
        else
        {
            throw new \Whoops\Example\Exception("Member ID is invalid.");
        }
    }

    public function getMemberSaltFromID($memberID)
    {
        try
        {
            $query   =   DB::connection($this->connection)->table($this->table)
                                ->select('salt1', 'salt2', 'salt3')
                                ->where('id', '=', $memberID)
                                ->get();

            $result =   $query[0];
            return array
            (
                'salt1' => $result->salt1,
                'salt2' => $result->salt2,
                'salt3' => $result->salt3,
            );
        }
        catch(\Whoops\Example\Exception $e)
        {
            throw new \Whoops\Example\Exception($e);
        }
    }
}