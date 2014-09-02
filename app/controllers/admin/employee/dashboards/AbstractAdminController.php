<?php
    /**
     * Class AbstractAdminController
     *
     * filename:   AbstractAdminController.php
     *
     * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
     * @since       7/8/14 5:19 AM
     *
     * @copyright   Copyright (c) 2014 www.eKinect.com
     */


    class AbstractAdminController extends BaseController
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




    }