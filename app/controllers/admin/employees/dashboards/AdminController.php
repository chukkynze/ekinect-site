<?php
 /**
  * Class AdminController
  *
  * filename:   AdminController.php
  *
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/15/14 11:30 PM
  *
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */


class AdminController extends AbstractAdminController
{
    public $viewRootFolder = 'admin/employees/';

    public function __construct()
    {
        parent::__construct();
    }

    public function showDashboard()
    {

        $viewData   =   array
                        (
                            'display_name'  =>  'XYZ',
                        );

        return $this->makeResponseView($this->viewRootFolder . 'dashboard', $viewData);
    }

}