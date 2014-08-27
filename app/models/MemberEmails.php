<?php
 /**
  * Class MemberEmails
  *
  * filename:   MemberEmails.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:11 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class MemberEmails extends Eloquent
{
    protected $table        =   'member_emails';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'member_id',
                                    'email_address',
                                    'is_primary',
                                    'verification_sent',
                                    'verification_sent_on',
                                    'verified',
                                    'verified_on',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );


    public function addMemberEmail($memberEmail, $memberID)
    {
        if($memberID > 0)
        {
            $newMemberEmail =   MemberEmails::create
                                (
                                    array
                                    (
                                        'member_id'             =>  $memberID,
                                        'email_address'         =>  $memberEmail,
                                        'is_primary'            =>  1,
                                        'verification_sent'     =>  0,
                                        'verification_sent_on'  =>  0,
                                        'verified'              =>  0,
                                        'verified_on'           =>  0,
                                    )
                                );
            $newMemberEmail->save();
            return $newMemberEmail->id;
        }
        else
        {
            throw new \Whoops\Example\Exception("Member ID is invalid.");
        }
    }

    public function updateMemberEmail($memberEmailsID, $fillableArray)
    {
        if($memberEmailsID > 0)
        {
            try
            {
                $MemberEmail =   MemberEmails::where("id","=", $memberEmailsID)->first();
                $MemberEmail->fill($fillableArray);
                $MemberEmail->save();
                return TRUE;
            }
            catch(\Whoops\Example\Exception $e)
            {
                throw new \Whoops\Example\Exception($e);
            }
        }
        else
        {
            throw new \Whoops\Example\Exception("MemberEmails ID is invalid.");
        }
    }

    public function isEmailVerified($email)
    {
        $count     =   DB::connection($this->connection)->table($this->table)
                        ->select('id')
                        ->where('email_address'   , '=', $email)
                        ->where('verified'        , '=', 1)
                        ->where('verified_on'     , '<', strtotime("now"))
                        ->count()
        ;

        return ($count == 1 ? TRUE : FALSE);
    }

    public function wasVerificationLinkSent($emailAddress)
    {
        $count  =   DB::connection($this->connection)->table($this->table)
                        ->select('id')
                        ->where('email_address'       , '=', $emailAddress)
                        ->where('verification_sent'   , '=', 1)
                        ->where('verification_sent_on', '<', strtotime('now'))
                        ->count();

        return ($count == 1 ? TRUE : FALSE);
    }

    public function getMemberIDFromEmailAddress($emailAddress)
    {
        try
        {
            $query   =   DB::connection($this->connection)->table($this->table)
                                ->select('member_id')
                                ->where('email_address'       , '=', $emailAddress)
                                ->get();

            $result =   $query[0];
            return $result->member_id;
        }
        catch(\Whoops\Example\Exception $e)
        {
            throw new \Whoops\Example\Exception($e);
        }
    }

    public function getMemberEmailIDFromEmailAddress($emailAddress)
    {
        try
        {
            $query   =   DB::connection($this->connection)->table($this->table)
                                ->select('id')
                                ->where('email_address'       , '=', $emailAddress)
                                ->get();

            $result =   $query[0];
            return $result->id;
        }
        catch(\Whoops\Example\Exception $e)
        {
            throw new \Whoops\Example\Exception($e);
        }
    }

    public function getEmailAddressesFromMemberID()
    {

    }

    public function getPrimaryEmailAddressFromMemberID($member_id)
    {
        try
        {
            $query   =   DB::connection($this->connection)->table($this->table)
                ->select('email_address')
                ->where('member_id' , '=', $member_id)
                ->where('is_primary', '=', 1)
                ->get();

            $result =   $query[0];
            return $result->email_address;
        }
        catch(\Whoops\Example\Exception $e)
        {
            throw new \Whoops\Example\Exception($e);
        }
    }
}