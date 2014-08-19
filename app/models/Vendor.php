<?php
/**
 * Class Vendor
 *
 * filename:   Vendor.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
 * @since       7/7/14 8:30 PM
 * 
 * @copyright   Copyright (c) 2014 www.eKinect.com
 */
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Vendor extends Eloquent
{
    use SoftDeletingTrait;

    public $timestamps      =   true;

    public function jobs()
    {
        return $this->hasMany('Vendor');
    }
}