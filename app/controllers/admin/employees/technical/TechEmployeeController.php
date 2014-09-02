<?php
 /**
  * Class TechEmployeeController
  *
  * filename:   TechEmployeeController.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/15/14 11:30 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class TechEmployeeController extends AbstractTechEmployeeController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function showDashboard()
    {
        $customViewData     =   array();
        $viewData           =   array_merge($this->layoutData, $customViewData);

        return $this->makeResponseView($this->viewRootFolder . 'dashboard', $viewData);
    }

}