<?php
    /**
     * Class AbstractVendorController
     *
     * filename:   AbstractVendorController.php
     *
     * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
     * @since       7/8/14 5:19 AM
     *
     * @copyright   Copyright (c) 2014 www.eKinect.com
     */


    class AbstractVendorController extends AbstractMemberController
    {

        /**
         * @param \Illuminate\View\View $layout
         */
        public function setLayout($layout)
        {
            $this->layout = $layout;
        }

        /**
         * @return \Illuminate\View\View
         */
        public function getLayout()
        {
            return $this->layout;
        }



        public function vendorLogout()
        {
            // Perform vendor specific action before logging out

            $this->memberLogout();

            // Redirect to the logged out page
            return $this->makeResponseView('application/members/member-logout', array());
        }




    }