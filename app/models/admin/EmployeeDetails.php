<?php
 /**
  * Class EmployeeDetails
  *
  * filename:   EmployeeDetails.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/28/14 8:20 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class EmployeeDetails extends AbstractModel
{
    protected $table        =   'employee_details';
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
                                    'title',
                                    'department',
                                    'hire_date',
                                    'fire_date',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );

    public function getEmployeeDetailsPrefix($format)
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
								6 	=> 'Atty',
								7 	=> 'Ofc',
								8 	=> 'Ntry',
							);
		switch(trim(strtolower($format)))
		{
			case 'text'	:	$output = $outputValues[$this->prefix]; break;
			case 'raw'	:	$output = $this->prefix; break;
			default		:	$output = $outputValues[$this->prefix];
		}

		return $output;
    }

    public function getEmployeeDetailsFirstName()
    {
        return (isset($this->first_name) ? $this->first_name : "Valued");
    }

    public function getEmployeeDetailsMidName1()
    {
        return $this->mid_name1;
    }

    public function getEmployeeDetailsMidName2()
    {
        return $this->mid_name2;
    }

    public function getEmployeeDetailsLastName()
    {
        return  (isset($this->last_name) ? $this->last_name : "Member");
    }

    public function getEmployeeDetailsDisplayName()
    {
        return 	(isset($this->display_name) && $this->display_name != ''
					? 	$this->display_name
					:	$this->first_name);
    }

    public function getEmployeeDetailsFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function getEmployeeDetailsSuffix($format)
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
								7 	=> 'PC',
								8 	=> 'Ntry',
							);
		switch(trim(strtolower($format)))
		{
			case 'text'	:	$output = $outputValues[$this->suffix]; break;
			case 'raw'	:	$output = $this->suffix; break;
			default		:	$output = $outputValues[$this->suffix];
		}

		return $output;
    }

    public function getEmployeeDetailsGender($format)
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

    public function getEmployeeDetailsBirthDate()
    {
		// Database format is YYYY-MM-DD. Change to MM-DD-YYYY
		$birthDate 		=	$this->birth_date;
		$birthDateYear	=	substr($birthDate, 0, 4);
		$birthDateMonth	=	substr($birthDate, 5, 2);
		$birthDateDay	=	substr($birthDate, 8, 2);
        return $birthDateMonth . "-". $birthDateDay . "-" . $birthDateYear;
    }

    public function getEmployeeDetailsZipCode()
    {
        return $this->zipcode;
    }

    public function getEmployeeDetailsPersonalSummary()
    {
        return $this->personal_summary;
    }

    public function getEmployeeDetailsProfilePicUrl()
    {
        return $this->profile_pic_url;
    }

    public function getEmployeeDetailsTitle()
    {
        return $this->title;
    }

    public function getEmployeeDetailsDepartment()
    {
        return $this->department;
    }

    public function getEmployeeDetailsHireDate()
    {
        return $this->hire_date;
    }

    public function getEmployeeDetailsFireDate()
    {
        return $this->fire_date;
    }








    public function doEmployeeDetailsExist($memberID)
    {
        $count  =   DB::connection($this->connection)->table($this->table)
                        ->select('id')
                        ->where('member_id'       , '=', $memberID)
                        ->count();

        return ($count == 1 ? TRUE : FALSE);
    }

    public function addEmployeeDetails($memberID, $fillableArray)
    {
        if($memberID > 0)
        {
            $newMemberDetail =   EmployeeDetails::create
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

    public function updateEmployeeDetails($memberID, $fillableArray)
    {
        if($memberID > 0)
        {
            try
            {
                $MemberDetail =   EmployeeDetails::where("member_id","=", $memberID)->first();
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
            throw new \Whoops\Example\Exception("EmployeeDetails ID is invalid.");
        }
    }

    public function getEmployeeDetailsFromMemberID($memberID)
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