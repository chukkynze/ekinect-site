<?php
 /**
  * Class AbstractModel
  *
  * filename:   AbstractModel.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/23/14 12:11 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class AbstractModel extends Eloquent
{


    public function getPrimaryKeyUsingMemberID($memberID)
    {
        try
        {
            $query   =   DB::connection($this->connection)->table($this->table)
                ->select('id')
                ->where('member_id' , '=', $memberID)
                ->get();

            $result =   $query[0];
            return $result->id;
        }
        catch(\Whoops\Example\Exception $e)
        {
            throw new \Whoops\Example\Exception($e);
        }
    }



}