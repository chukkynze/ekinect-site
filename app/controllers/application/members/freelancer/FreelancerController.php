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
    public $viewRootFolder = 'application/members/freelancer/';

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