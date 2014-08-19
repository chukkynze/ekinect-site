<?php
 /**
  * Class MessageCategory
  *
  * filename:   MessageCategory.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/7/14 8:43 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class MessageCategory extends Eloquent
{
    use SoftDeletingTrait;

    protected $table        =   'message_categories';
    protected $primaryKey   =   'message_category_id';
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