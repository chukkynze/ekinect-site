<?php
 /**
  * Class Message
  *
  * filename:   Message.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/7/14 8:43 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Message extends Eloquent
{
    use SoftDeletingTrait;

    protected $table        =   'messages';
    protected $primaryKey   =   'message_id';
    protected $connection   =   'queue_db';

    protected $fillable     =   array
                                (
                                    'msg_type_id',
                                    'msg_category_id',

                                    'sender_email_address',
                                    'sender_fullname',
                                    'sender_firstname',
                                    'sender_lastname',
                                    'sender_phone',

                                    'recipient_email_address',
                                    'recipient_fullname',
                                    'recipient_firstname',
                                    'recipient_lastname',
                                    'recipient_phone',

                                    'message_body_text',
                                    'message_body_html',
                                    'message_template_text',
                                    'message_template_html',
                                );

    protected $guarded      =   array
                                (
                                    'message_id',
                                );

}