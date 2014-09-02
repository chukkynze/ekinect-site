<?php
    /**
     * filename:   freelancer-cloud.blade.php
     *
     * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
     * @since       8/16/14 2:44 AM
     *
     * @copyright   Copyright (c) 2014 www.eKinect.com
     */
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Ekinect - Freelancer Dashboard.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,IE=9,IE=8,chrome=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="/app/customers/freelancer/theme/freelancer-module.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/app/customers/freelancer/theme/controllers/freelancer/css/index.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/app/customers/freelancer/theme/css/cloud-admin.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/app/customers/freelancer/theme/css/themes/default.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/app/customers/freelancer/theme/css/responsive.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/app/customers/freelancer/theme/font-awesome/css/font-awesome.min.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/app/customers/freelancer/theme/css/animatecss/animate.min.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/app/customers/freelancer/theme/js/bootstrap-daterangepicker/daterangepicker-bs3.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/app/customers/freelancer/theme/js/jquery-todo/css/styles.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/app/customers/freelancer/theme/js/fullcalendar/fullcalendar.min.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/app/customers/freelancer/theme/js/gritter/css/jquery.gritter.css" media="screen" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" media="screen" rel="stylesheet" type="text/css">
    <link href="/app/images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <!--[if lt IE 9]><script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <!--[if lt IE 9]><script type="text/javascript" src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->

</head>



<body>
<!-- HEADER -->
<header class="navbar clearfix" id="header">
<div class="container">
<div class="navbar-brand">
    <!-- COMPANY LOGO -->
    <a href="<?php echo $memberHomeLink; ?>">
        <img src="/app/images/site_images/logo.png" alt="Ekinect.com" class="img-responsive" height="30" width="120" style="margin-top:4px;">
    </a>
    <!-- /COMPANY LOGO -->

    <!-- Not implemented on mobile


    <!-- SIDEBAR COLLAPSE -->
    <div id="sidebar-collapse" class="sidebar-collapse btn">
        <i class="fa fa-bars"
           data-icon1="fa fa-bars"
           data-icon2="fa fa-bars" ></i>
    </div>
    <!-- /SIDEBAR COLLAPSE -->
</div>



<!-- BEGIN TOP NAVIGATION MENU -->
<ul class="nav navbar-nav pull-right">


    <!-- BEGIN NOTIFICATION DROPDOWN -->
    <li class="dropdown" id="header-notification">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell"></i>
            <span class="badge"><?php echo $ALERT_totalMessageCount ?></span>
        </a>
        <ul class="dropdown-menu notification">
            <li class="dropdown-title">
                <span><i class="fa fa-bell"></i> <?php echo $ALERT_title ?></span>
            </li>


            <?php
                foreach($ALERT_listItemsArray as $alertKey => $alertDetails)
                {
                    echo 	'<li>
											<a href="' . $alertDetails['alertLink'] . '' . $alertDetails['alertLinkID'] . '">
												<span class="' . $alertDetails['alertLabelClass'] . '"><i class="' . $alertDetails['alertIconClass'] . '"></i></span>
												<span class="body">
													<span class="message">' . $alertDetails['alertContent'] . '&nbsp;</span>
													<span class="time">
														<i class="fa fa-clock-o"></i>
														<span>' . $alertDetails['alertFuzzyTime'] . '</span>
													</span>
												</span>
											</a>
										</li>';
                }
            ?>


            <li class="footer">
                <a href="<?php echo $ALERT_footerLink ?>">See all notifications <i class="fa fa-arrow-circle-right"></i></a>
            </li>
        </ul>
    </li>
    <!-- END NOTIFICATION DROPDOWN -->


    <!-- BEGIN INBOX DROPDOWN -->
    <li class="dropdown" id="header-message">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-envelope"></i>
            <span class="badge"><?php echo $INBOX_totalMessageCount ?></span>
        </a>
        <ul class="dropdown-menu inbox">
            <li class="dropdown-title">
                <span><i class="fa fa-envelope-o"></i> <?php echo $INBOX_title ?></span>
                <span class="compose pull-right tip-right" title="Compose message"><a href="<?php echo $INBOX_composeNewLink ?>"><i class="fa fa-pencil-square-o"></i></a></span>
            </li>

            <?php
                foreach($INBOX_listItemsArray as $msgKey => $msgDetails)
                {
                    echo 	'<li>
											<a href="' . $msgDetails['messageLink'] . '' . $msgDetails['messageLinkID'] . '">
												<img src="' . $msgDetails['messageAvatar'] . '" alt="' . $msgDetails['messageAvatarAltText'] . '" />
												<span class="body">
													<span class="from">' . $msgDetails['messageFromMemberType'] . ' - ' . $msgDetails['messageFromShort'] . '</span>
													<span class="message">' . $msgDetails['messageContentShort'] . '</span>
													<span class="time">
														<i class="fa fa-clock-o"></i>
														<span>' . $msgDetails['messageFuzzyTime'] . '</span>
													</span>
												</span>
											</a>
										</li>';
                }
            ?>

            <li class="footer">
                <a href="<?php echo $INBOX_footerLink ?>">See all messages <i class="fa fa-arrow-circle-right"></i></a>
            </li>
        </ul>
    </li>
    <!-- END INBOX DROPDOWN -->


    <!-- BEGIN TODO DROPDOWN -->
    <li class="dropdown" id="header-tasks">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-tasks"></i>
            <span class="badge"><?php echo $TODO_listTotalNumber; ?></span>
        </a>
        <ul class="dropdown-menu tasks">
            <li class="dropdown-title">
                <span><i class="fa fa-check"></i> You have <?php echo $TODO_listTotalNumber; ?> <?php echo "task" . ($TODO_listTotalNumber == 1 ? "" : "s") ?> in progress</span>
            </li>

            <?php
                foreach($TODO_listItemsArray as $taskKey => $taskDetails)
                {
                    echo 	'<li>
											<a href="#">
												<span class="header clearfix">
													<span class="pull-left">' . $taskDetails['taskHeading'] . '</span>
													<span class="pull-right">' . $taskDetails['taskOverallCompletionLevel'] . '%</span>
												</span>
												<div class="progress '. ( $taskDetails['taskProgressBarIsStriped'] ? 'progress-striped' : '') .' ' . ( $taskDetails['taskProgressBarIsStriped'] && $taskDetails['taskProgressBarIsStripedActive'] ? 'active' : '') . '">';

                    $isOnlyOneBit	=	( count($taskDetails['taskBits']) == 1 && count($taskDetails['taskBits']) > 0 ? TRUE : FALSE);
                    foreach( $taskDetails['taskBits'] as $taskBit )
                    {
                        $onlyOneBItProperties 	=	($isOnlyOneBit ? 'role="progressbar" aria-valuenow="' . $taskBit['taskBitCompletionLevel'] . '" aria-valuemin="0" aria-valuemax="100"' : '');
                        echo 	'<div class="progress-bar progress-bar-' . $taskBit['taskBitProgressBarType'] . '" ' . $onlyOneBItProperties . ' style="width: ' . $taskBit['taskBitCompletionLevel'] . '%;">
																<span class="sr-only">' . $taskBit['taskBitCompletionLevel'] . '% Complete</span>
									  						</div>';
                    }

                    echo 	'		</div>
											</a>
										</li>';
                }
            ?>

            <li class="footer">
                <a href="<?php echo $TODO_footerLink; ?>">See all tasks <i class="fa fa-arrow-circle-right"></i></a>
            </li>
        </ul>
    </li>
    <!-- END TODO DROPDOWN -->




    <!-- BEGIN USER LOGIN DROPDOWN -->
    <li class="dropdown user" id="header-user">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img id="ProfileBox_pic_ImageUserIcon" alt="" src="<?php echo $memberSmallProfilePicUrl; ?>" />
            <span class="username"><?php echo $memberLoginDropDownDisplayName; ?>&nbsp;</span>
            <i class="fa fa-angle-down"></i>
        </a>

        <?php

            if(is_array($memberUserMenuArray) && count($memberUserMenuArray) > 0)
            {
                echo '<ul class="dropdown-menu">';

                foreach($memberUserMenuArray as $menuKey => $menuDetails)
                {
                    echo 	'<li>
											<a href="' . $menuDetails['link'] . '">
												<span class="' . $menuDetails['labelClass'] . '"><i class="' . $menuDetails['iconClass'] . '"></i></span>&nbsp;&nbsp;&nbsp;' . $menuDetails['sectionName'] . '
											</a>
										</li>';
                }

                echo '</ul>';
            }

        ?>

    </li>
    <!-- END USER LOGIN DROPDOWN -->
</ul>
<!-- END TOP NAVIGATION MENU -->
</div>



</header>
<!--/HEADER -->



<!-- PAGE -->
<section id="<?php echo $cloudLayoutJSPageName; ?>">




<!-- SIDEBAR -->
<div id="sidebar" class="sidebar">
    <div class="sidebar-menu nav-collapse">
        <div class="divide-20"></div>
        <!-- SEARCH BAR -->
        <div id="search-bar">
            <input class="search" type="text" placeholder="Search"><i class="fa fa-search search-icon"></i>
        </div>
        <!-- /SEARCH BAR -->

        <!-- SIDEBAR QUICK-LAUNCH -->
        <!-- <div id="quicklaunch">
        <!-- /SIDEBAR QUICK-LAUNCH -->

        <!-- SIDEBAR MENU -->
        <ul>
            <li class="active">
                <a href="<?php echo $memberHomeLink; ?>">
                    <i class="fa fa-home fa-fw"></i> <span class="menu-text">Home</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="has-sub">
                <a href="javascript:;" class="">
                    <i class="fa fa-bar-chart-o fa-fw"></i> <span class="menu-text">Dashboards</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="/freelancer/dashboards/overview"><span class="sub-menu-text">Overview</span></a></li>
                    <li><a class="" href="/freelancer/dashboards/jobs"><span class="sub-menu-text">Jobs</span></a></li>
                    <li><a class="" href="/freelancer/dashboards/history"><span class="sub-menu-text">History</span></a></li>
                    <li><a class="" href="/freelancer/dashboards/analytics"><span class="sub-menu-text">Analytics</span></a></li>
                    <li><a class="" href="/freelancer/dashboards/documents"><span class="sub-menu-text">Document Center</span></a></li>
                </ul>
            </li>
            <li class="has-sub">
                <a href="javascript:;" class="">
                    <i class="fa fa-envelope-o fa-fw"></i> <span class="menu-text">Inbox <span class="badge pull-right"><?php echo $INBOX_totalMessageCount ?></span></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="<?php echo $INBOX_sidebarLink_new ?>"><span class="sub-menu-text">New</span></a></li>
                    <li><a class="" href="<?php echo $INBOX_sidebarLink_favorites ?>"><span class="sub-menu-text">Favorites</span></a></li>
                    <li><a class="" href="<?php echo $INBOX_sidebarLink_all ?>"><span class="sub-menu-text">All</span></a></li>
                </ul>
            </li>

            <li class="divider"></li>

            <li class="has-sub">
                <a href="javascript:;" class="">
                    <i class="fa fa-paperclip fa-fw"></i> <span class="menu-text">Jobs</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="/freelancer/crew-request"><span class="sub-menu-text">Job History</span></a></li>
                    <li><a class="" href="/freelancer/preferred-crews"><span class="sub-menu-text">Upcoming Jobs</span></a></li>
                </ul>
            </li>

            <li><a class="" href="freelancer/calendar"><i class="fa fa-calendar fa-fw"></i>
								<span class="menu-text">
                                    Calendar
									<span class="tooltip-error pull-right" title="" data-original-title="3 New Events">
										<span class="label label-success">New</span>
									</span>
								</span>
                </a>
            </li>

            <li class="has-sub">
                <a href="javascript:;" class="">
                    <i class="fa fa-th-large fa-fw"></i> <span class="menu-text">Analytics</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="elements.html"><span class="sub-menu-text">Elements</span></a></li><li><a class="" href="notifications.html"><span class="sub-menu-text">Hubspot Notifications</span></a></li>
                    <li><a class="" href="buttons_icons.html"><span class="sub-menu-text">Buttons & Icons</span></a></li>
                    <li><a class="" href="sliders_progress.html"><span class="sub-menu-text">Sliders & Progress</span></a></li>
                    <li><a class="" href="typography.html"><span class="sub-menu-text">Typography</span></a></li>
                    <li><a class="" href="tabs_accordions.html"><span class="sub-menu-text">Tabs & Accordions</span></a></li>
                    <li><a class="" href="treeview.html"><span class="sub-menu-text">Treeview</span></a></li>
                    <li><a class="" href="nestable_lists.html"><span class="sub-menu-text">Nestable Lists</span></a></li>
                    <li class="has-sub-sub">
                        <a href="javascript:;" class=""><span class="sub-menu-text">Third Level Menu</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-sub">
                            <li><a class="" href="#"><span class="sub-sub-menu-text">Item 1</span></a></li>
                            <li><a class="" href="#"><span class="sub-sub-menu-text">Item 2</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="has-sub">
                <a href="javascript:;" class="">
                    <i class="fa fa-folder-open-o fa-fw"></i> <span class="menu-text">Document Center</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="elements.html"><span class="sub-menu-text">Elements</span></a></li><li><a class="" href="notifications.html"><span class="sub-menu-text">Hubspot Notifications</span></a></li>
                    <li><a class="" href="buttons_icons.html"><span class="sub-menu-text">Buttons & Icons</span></a></li>
                    <li><a class="" href="sliders_progress.html"><span class="sub-menu-text">Sliders & Progress</span></a></li>
                    <li><a class="" href="typography.html"><span class="sub-menu-text">Typography</span></a></li>
                    <li><a class="" href="tabs_accordions.html"><span class="sub-menu-text">Tabs & Accordions</span></a></li>
                    <li><a class="" href="treeview.html"><span class="sub-menu-text">Treeview</span></a></li>
                    <li><a class="" href="nestable_lists.html"><span class="sub-menu-text">Nestable Lists</span></a></li>
                    <li class="has-sub-sub">
                        <a href="javascript:;" class=""><span class="sub-menu-text">Third Level Menu</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-sub">
                            <li><a class="" href="#"><span class="sub-sub-menu-text">Item 1</span></a></li>
                            <li><a class="" href="#"><span class="sub-sub-menu-text">Item 2</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>


            <li class="has-sub">
                <a href="javascript:;" class="">
                    <i class="fa fa-map-marker fa-fw"></i> <span class="menu-text">Help</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href=""><span class="sub-menu-text">FAQ</span></a></li>
                    <li><a class="" href=""><span class="sub-menu-text">Freelancer Forums</span></a></li>
                    <li><a class="" href=""><span class="sub-menu-text">Customer Support</span></a></li>
                </ul>
            </li>
        </ul>
        <!-- /SIDEBAR MENU -->
    </div>
</div>
<!-- /SIDEBAR -->





<div id="main-content">






    <!-- SAMPLE BOX CONFIGURATION MODAL FORM-->
    <div class="modal fade" id="box-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Box Settings</h4>
                </div>
                <div class="modal-body">
                    Here goes box setting content.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /SAMPLE BOX CONFIGURATION MODAL FORM-->


    @if(Session::has('successFlashMessage'))
    <div class="alert alert-block alert-success fade in">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
        <ul>
            @foreach(Session::pull('successFlashMessage') as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(Session::has('warningFlashMessage'))
    <div class="alert alert-block alert-warning fade in">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
        <ul>
            @foreach(Session::pull('warningFlashMessage') as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(Session::has('errorFlashMessage'))
    <div class="alert alert-block alert-danger fade in">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
        <ul>
            @foreach(Session::pull('errorFlashMessage') as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    @yield('content')




</div>
</section>
<!--/PAGE -->

<script type="text/javascript" src="/app/customers/freelancer/theme/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/bootstrap-dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/bootstrap-daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/jQuery-slimScroll-1.3.0/slimScrollHorizontal.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/jQuery-BlockUI/jquery.blockUI.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/sparklines/jquery.sparkline.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/jquery-easing/jquery.easing.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/jquery-todo/js/paddystodolist.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/jQuery-Cookie/jquery.cookie.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/gritter/js/jquery.gritter.min.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/js/script.js"></script>
<script type="text/javascript" src="/app/customers/freelancer/theme/controllers/freelancer/js/index.js"></script>

<script>
    var ModuleDirectoryReference = 'freelancer/';
    jQuery(document).ready(function() {
        App.setPage("FreelancerHome");  //Set current page
        App.init(); //Initialise plugins and elements
    });
</script>

</body>
</html>