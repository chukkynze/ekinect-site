<?php
 /**
  * Class CustomerDetails
  *
  * filename:   CustomerDetails.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/28/14 8:20 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class CustomerDetails extends AbstractModel
{
    protected $table        =   'customer_details';
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

    public function getCustomerDetailsPrefix($format)
    {
       	$outputValues	=	array
							(
								0	=>	0,
								''	=>	'',
								1 	=> 'Ms',
								2 	=> 'Miss',
								3 	=> 'Mrs',
								4 	=> 'Mr',
								5 	=> 'Dr',
							);
		switch(trim(strtolower($format)))
		{
			case 'text'	:	$output = $outputValues[$this->prefix]; break;
			case 'raw'	:	$output = $this->prefix; break;
			default		:	$output = $outputValues[$this->prefix];
		}

		return $output;
    }

    public function getCustomerDetailsFirstName()
    {
        return (isset($this->first_name) ? $this->first_name : "Valued");
    }

    public function getCustomerDetailsMidName1()
    {
        return $this->mid_name1;
    }

    public function getCustomerDetailsMidName2()
    {
        return $this->mid_name2;
    }

    public function getCustomerDetailsLastName()
    {
        return  (isset($this->last_name) ? $this->last_name : "Customer");
    }

    public function getCustomerDetailsDisplayName()
    {
        return 	(isset($this->display_name) && $this->display_name != ''
					? 	$this->display_name
					:	$this->first_name);
    }

    public function getCustomerDetailsFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function getCustomerDetailsSuffix($format)
    {
        $outputValues	=	array
							(
								0	=>	0,
								''	=>	'',
								1 	=> 'II',
								2 	=> 'III',
								3 	=> 'IV',
								4 	=> 'Jr',
								5 	=> 'Sr',
								6 	=> 'PhD',
							);
		switch(trim(strtolower($format)))
		{
			case 'text'	:	$output = $outputValues[$this->suffix]; break;
			case 'raw'	:	$output = $this->suffix; break;
			default		:	$output = $outputValues[$this->suffix];
		}

		return $output;
    }

    public function getCustomerDetailsGender($format)
    {
		$outputValues	=	array
							(
								0 => 'Other',
								1 => 'Female',
								2 => 'Male',
							);
		switch(trim(strtolower($format)))
		{
			case 'text'	:	$output = $outputValues[$this->gender]; break;
			case 'abbr'	:	$output = $outputValues[$this->gender][0]; break;
			case 'raw'	:	$output = $this->gender; break;
			default		:	$output = $outputValues[$this->gender];
		}

		return $output;
    }

    public function getCustomerDetailsBirthDate()
    {
		// Database format is YYYY-MM-DD. Change to MM-DD-YYYY
		$birthDate 		=	$this->birth_date;
		$birthDateYear	=	substr($birthDate, 0, 4);
		$birthDateMonth	=	substr($birthDate, 5, 2);
		$birthDateDay	=	substr($birthDate, 8, 2);
        return $birthDateMonth . "-". $birthDateDay . "-" . $birthDateYear;
    }

    public function getCustomerDetailsZipCode()
    {
        return $this->zipcode;
    }

    public function getCustomerDetailsPersonalSummary()
    {
        return $this->personal_summary;
    }

    public function getCustomerDetailsProfilePicUrl()
    {
        return $this->profile_pic_url;
    }

    public function getCustomerDetailsPersonalSiteUrl()
    {
        return $this->personal_website_url;
    }

    public function getCustomerDetailsLinkedInUrl()
    {
        return $this->linkedin_url;
    }

    public function getCustomerDetailsGooglePlusUrl()
    {
        return $this->google_plus_url;
    }

    public function getCustomerDetailsTwitterUrl()
    {
        return $this->twitter_url;
    }

    public function getCustomerDetailsFacebookUrl()
    {
        return $this->facebook_url;
    }








    public function doCustomerDetailsExist($memberID)
    {
        $count  =   DB::connection($this->connection)->table($this->table)
                        ->select('id')
                        ->where('member_id'       , '=', $memberID)
                        ->count();

        return ($count == 1 ? TRUE : FALSE);
    }

    public function addCustomerDetails($memberID, $fillableArray)
    {
        if($memberID > 0)
        {
            $newMemberDetail =   CustomerDetails::create
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

    public function updateCustomerDetails($memberID, $fillableArray)
    {
        if($memberID > 0)
        {
            try
            {
                $MemberDetail =   CustomerDetails::where("member_id","=", $memberID)->first();
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
            throw new \Whoops\Example\Exception("Member ID is invalid for Customer Details.");
        }
    }

    public function getCustomerDetailsFromMemberID($memberID)
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