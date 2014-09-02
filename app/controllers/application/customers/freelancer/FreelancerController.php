<?php
 /**
  * Class FreelancerController
  *
  * filename:   FreelancerController.php
  *
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/8/14 5:24 AM
  *
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */


class FreelancerController extends AbstractFreelancerController
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