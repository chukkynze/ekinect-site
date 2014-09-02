<?php
/**
 * Class VendorJob
 *
 * filename:   VendorJob.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
 * @since       7/7/14 8:30 PM
 * 
 * @copyright   Copyright (c) 2014 www.eKinect.com
 */
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class VendorJob extends Eloquent
{
    use SoftDeletingTrait;

    public $timestamps      =   true;

    protected $fillable     =   array
                                (
                                    'name',
                                    'vendor_id',
                                );

    public function vendor()
    {
        return $this->belongsTo('Vendor');
    }
}