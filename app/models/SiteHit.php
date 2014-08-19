<?php
 /**
  * Class SiteHit
  *
  * filename:   SiteHit.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:11 AM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class SiteHit extends Eloquent
{
    protected $table        =   'site_hits';
    protected $primaryKey   =   'id';
    protected $connection   =   'main_db';
    protected $fillable     =   array
                                (
                                    'user_id',
                                    'cookies',
                                    'url_location',
                                    'client_time',
                                    'server_time',
                                );
    protected $guarded      =   array
                                (
                                    'id',
                                );



}