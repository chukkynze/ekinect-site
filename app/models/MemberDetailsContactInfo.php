<?php
 /**
  * Class MemberDetailsContactInfo
  *
  * filename:   MemberDetailsContactInfo.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/28/14 9:42 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class MemberDetailsContactInfo extends Eloquent
{
    protected $table        =   'member_details_contact_info';
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