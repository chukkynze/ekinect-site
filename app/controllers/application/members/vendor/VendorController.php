<?php
 /**
  * Class VendorController
  *
  * filename:   VendorController.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/15/14 11:30 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class VendorController extends AbstractVendorController
{
    public $viewRootFolder = 'application/members/vendor/';
    protected $memberID;
    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.vendor-cloud';

    public function __construct()
    {
        $this->getSiteUser();   // Find/Create a SiteUser uid from cookie
        $this->setSiteHit();    // Register a SiteHit

        $this->memberID     =   Auth::id();

    }

    public function showDashboard()
    {

        $viewData   =   array
                        (
                            'memberID'      =>  $this->memberID,
                            'displayName'  =>  'XYZ',
                            'profileLink'  =>  'XYZ',

                            'crewsSectionButtonText_xs'  =>  'crewsxs',
                            'crewsSectionButtonText_sm'  =>  'crewssm',
                            'crewsSectionButtonText_md'  =>  'crewsmd',
                            'crewsSectionButtonText_lg'  =>  'crewslg',

                            'jobsSectionButtonText_xs'  =>  'jobsxs',
                            'jobsSectionButtonText_sm'  =>  'jobssm',
                            'jobsSectionButtonText_md'  =>  'jobsmd',
                            'jobsSectionButtonText_lg'  =>  'jobslg',

                            'calendarSectionButtonText_xs'  =>  'calendarxs',
                            'calendarSectionButtonText_sm'  =>  'calendarssm',
                            'calendarSectionButtonText_md'  =>  'calendarmd',
                            'calendarSectionButtonText_lg'  =>  'calendarlg',

                            'analyticsSectionButtonText_xs'  =>  'analyticsxs',
                            'analyticsSectionButtonText_sm'  =>  'analyticssm',
                            'analyticsSectionButtonText_md'  =>  'analyticsmd',
                            'analyticsSectionButtonText_lg'  =>  'analyticslg',

                            'reportsSectionButtonText_xs'  =>  'reportsxs',
                            'reportsSectionButtonText_sm'  =>  'reportssm',
                            'reportsSectionButtonText_md'  =>  'reportsmd',
                            'reportsSectionButtonText_lg'  =>  'reportslg',

                            'helpSectionButtonText_xs'  =>  'helpxs',
                            'helpSectionButtonText_sm'  =>  'helpsm',
                            'helpSectionButtonText_md'  =>  'helpmd',
                            'helpSectionButtonText_lg'  =>  'helplg',
                        );

        return $this->makeResponseView($this->viewRootFolder . 'dashboard', $viewData);
    }

}