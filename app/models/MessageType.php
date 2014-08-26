<?php
 /**
  * Class MessageType
  *
  * filename:   MessageType.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/7/14 8:43 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 use Illuminate\Database\Eloquent\SoftDeletingTrait;

class MessageType extends Eloquent
{
    use SoftDeletingTrait;

    protected $table        =   'message_types';
    protected $primaryKey   =   'message_type_id';
    protected $connection   =   'queue_db';

    protected $fillable     =   array
                                (
                                    'message_type',
                                );

    protected $guarded      =   array
                                (
                                    'message_type_id',
                                );

}