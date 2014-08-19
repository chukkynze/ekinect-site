<?php
 /**
  * Class MemberDetails
  *
  * filename:   MemberDetails.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/28/14 8:20 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class MemberDetails extends Eloquent
{
    protected $table        =   'member_details';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'member_id',
                                    'prefix',
                                    'first_name',
                                    'mid_name1',
                                    'mid_name2',
                                    'last_name',
                                    'display_name',
                                    'suffix',
                                    'gender',
                                    'birth_date',
                                    'zipcode',
                                    'personal_summary',
                                    'profile_pic_url',
                                    'personal_website_url',
                                    'linkedin_url',
                                    'google_plus_url',
                                    'twitter_url',
                                    'facebook_url',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );


    public function doMemberDetailsExist($memberID)
    {
        $count  =   DB::connection($this->connection)->table($this->table)
                        ->select('id')
                        ->where('member_id'       , '=', $memberID)
                        ->count();

        return ($count == 1 ? TRUE : FALSE);
    }

    public function addMemberDetails($memberID, $fillableArray)
    {
        if($memberID > 0)
        {
            $newMemberDetail =   MemberDetails::create
                                (
                                    $fillableArray
                                );
            $newMemberDetail->save();
            return $newMemberDetail->id;
        }
        else
        {
            throw new \Whoops\Example\Exception("Member ID is invalid.");
        }
    }

    public function updateMemberDetails($memberID, $fillableArray)
    {
        if($memberID > 0)
        {
            try
            {
                $MemberDetail =   MemberDetails::where("member_id","=", $memberID)->first();
                $MemberDetail->fill($fillableArray);
                $MemberDetail->save();
                return TRUE;
            }
            catch(\Whoops\Example\Exception $e)
            {
                throw new \Whoops\Example\Exception($e);
            }
        }
        else
        {
            throw new \Whoops\Example\Exception("MemberDetails ID is invalid.");
        }
    }

    public function getMemberDetailsFromMemberID($memberID)
    {
        try
        {
            $query   =   DB::connection($this->connection)->table($this->table)
                ->select('*')
                ->where('member_id' , '=', $memberID)
                ->get();

            $result =   $query[0];
            return $result;
        }
        catch(\Whoops\Example\Exception $e)
        {
            throw new \Whoops\Example\Exception($e);
        }
    }
}