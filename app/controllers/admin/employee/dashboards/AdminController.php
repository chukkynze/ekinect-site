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
    public $viewRootFolder = 'admin/employee/dashboards/';

    public function __construct()
    {
        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit
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