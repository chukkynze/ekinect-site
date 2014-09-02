<?php
 /**
  * Class EmployeeDetailsContactInfo
  *
  * filename:   EmployeeDetailsContactInfo.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/28/14 9:42 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class EmployeeDetailsContactInfo extends Eloquent
{
    protected $table        =   'employee_details_contact_info';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'member_id',
                                    'business_email',
                                    'phone_number',
                                    'fax_number',
                                    'cell_number',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );



}