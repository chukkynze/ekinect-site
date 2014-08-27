<?php
 /**
  * Class LocationData
  *
  * filename:   LocationData.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/22/14 10:09 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class LocationData extends Eloquent
{
    protected $table        =   'location_data';
    protected $primaryKey   =   'id';
    protected $connection   =   'utils_db';
    protected $fillable     =   array
                                (
                                    'country_name',
                                    'postal_code',
                                    'city',
                                    'county_name',
                                    'county_fips',
                                    'state_name',
                                    'state_abbr',
                                    'area_codes',
                                    'time_zone',
                                    'utc',
                                    'dst',
                                    'longitude',
                                    'latitude',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );



}