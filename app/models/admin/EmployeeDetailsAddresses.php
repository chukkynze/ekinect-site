<?php
 /**
  * Class EmployeeDetailsAddresses
  *
  * filename:   EmployeeDetailsAddresses.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/28/14 9:38 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class EmployeeDetailsAddresses extends Eloquent
{
    protected $table        =   'employee_details_addresses';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'member_id',
                                    'address_type',
                                    'business_name',
                                    'address_line_1',
                                    'address_line_2',
                                    'address_line_3',
                                    'county',
                                    'city',
                                    'state',
                                    'zipcode',
                                    'zipcode_ext',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );



}