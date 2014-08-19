<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<!-- Facebook sharing information tags -->
<?php echo $FB_OGS; ?>


<title><?php echo $EMAIL_SUBJECT; ?></title>


<style type="text/css">
/* Client-specific Styles */
#outlook a{padding:0;} /* Force Outlook to provide a "view in browser" button. */
body{width:100% !important;} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
body{-webkit-text-size-adjust:none;} /* Prevent Webkit platforms from changing default text sizes. */

/* Reset Styles */
body{margin:0; padding:0;}
img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
table td{border-collapse:collapse;}
#backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}


body, #backgroundTable{
    /*@editable*/ background-color:#FAFAFA;
}


#templateContainer{
    /*@editable*/ border: 2px solid #DDDDDD;
}


h1, .h1{
    /*@editable*/ color:#202020;
    display:block;
    /*@editable*/ font-family:Arial;
    /*@editable*/ font-size:34px;
    /*@editable*/ font-weight:bold;
    /*@editable*/ line-height:100%;
    margin-top:0;
    margin-right:0;
    margin-bottom:10px;
    margin-left:0;
    /*@editable*/ text-align:left;
}


h2, .h2{
    /*@editable*/ color:#202020;
    display:block;
    /*@editable*/ font-family:Arial;
    /*@editable*/ font-size:30px;
    /*@editable*/ font-weight:bold;
    /*@editable*/ line-height:100%;
    margin-top:0;
    margin-right:0;
    margin-bottom:10px;
    margin-left:0;
    /*@editable*/ text-align:left;
}


h3, .h3{
    /*@editable*/ color:#202020;
    display:block;
    /*@editable*/ font-family:Arial;
    /*@editable*/ font-size:26px;
    /*@editable*/ font-weight:bold;
    /*@editable*/ line-height:100%;
    margin-top:0;
    margin-right:0;
    margin-bottom:10px;
    margin-left:0;
    /*@editable*/ text-align:left;
}


h4, .h4{
    /*@editable*/ color:#202020;
    display:block;
    /*@editable*/ font-family:Arial;
    /*@editable*/ font-size:22px;
    /*@editable*/ font-weight:bold;
    /*@editable*/ line-height:100%;
    margin-top:0;
    margin-right:0;
    margin-bottom:10px;
    margin-left:0;
    /*@editable*/ text-align:left;
}


#templatePreheader{
    /*@editable*/ background-color:#FAFAFA;
}


.preheaderContent div{
    /*@editable*/ color:#505050;
    /*@editable*/ font-family:Arial;
    /*@editable*/ font-size:10px;
    /*@editable*/ line-height:100%;
    /*@editable*/ text-align:left;
}


.preheaderContent div a:link, .preheaderContent div a:visited, /* Yahoo! Mail Override */ .preheaderContent div a .yshortcuts /* Yahoo! Mail Override */{
    /*@editable*/ color:#336699;
    /*@editable*/ font-weight:normal;
    /*@editable*/ text-decoration:underline;
}


#templateHeader{
    /*@editable*/ background-color:#FFFFFF;
    /*@editable*/ border-bottom:0;
}


.headerContent{
    /*@editable*/ color:#202020;
    /*@editable*/ font-family:Arial;
    /*@editable*/ font-size:34px;
    /*@editable*/ font-weight:bold;
    /*@editable*/ line-height:100%;
    /*@editable*/ padding:0;
    /*@editable*/ text-align:center;
    /*@editable*/ vertical-align:middle;
}


.headerContent a:link, .headerContent a:visited, /* Yahoo! Mail Override */ .headerContent a .yshortcuts /* Yahoo! Mail Override */{
    /*@editable*/ color:#336699;
    /*@editable*/ font-weight:normal;
    /*@editable*/ text-decoration:underline;
}

#headerImage{
    height:auto;
    max-width:600px;
}

/* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: MAIN BODY /\/\/\/\/\/\/\/\/\/\ */


#templateContainer, .bodyContent{
    /*@editable*/ background-color:#FFFFFF;
}


.bodyContent div{
    /*@editable*/ color:#505050;
    /*@editable*/ font-family:Arial;
    /*@editable*/ font-size:14px;
    /*@editable*/ line-height:150%;
    /*@editable*/ text-align:left;
}


.bodyContent div a:link, .bodyContent div a:visited, /* Yahoo! Mail Override */ .bodyContent div a .yshortcuts /* Yahoo! Mail Override */{
    /*@editable*/ color:#336699;
    /*@editable*/ font-weight:normal;
    /*@editable*/ text-decoration:underline;
}

.bodyContent img{
    display:inline;
    height:auto;
}


#templateSidebar{
    /*@editable*/ background-color:#FFFFFF;
    /*@editable*/ border-right:0;
}


.sidebarContent div{
    /*@editable*/ color:#505050;
    /*@editable*/ font-family:Arial;
    /*@editable*/ font-size:12px;
    /*@editable*/ line-height:150%;
    /*@editable*/ text-align:left;
}


.sidebarContent div a:link, .sidebarContent div a:visited, /* Yahoo! Mail Override */ .sidebarContent div a .yshortcuts /* Yahoo! Mail Override */{
    /*@editable*/ color:#336699;
    /*@editable*/ font-weight:normal;
    /*@editable*/ text-decoration:underline;
}

.sidebarContent img{
    display:inline;
    height:auto;
}


#templateFooter{
    /*@editable*/ background-color:#FFFFFF;
    /*@editable*/ border-top:0;
}


.footerContent div{
    /*@editable*/ color:#707070;
    /*@editable*/ font-family:Arial;
    /*@editable*/ font-size:12px;
    /*@editable*/ line-height:125%;
    /*@editable*/ text-align:left;
}


.footerContent div a:link, .footerContent div a:visited, /* Yahoo! Mail Override */ .footerContent div a .yshortcuts /* Yahoo! Mail Override */{
    /*@editable*/ color:#336699;
    /*@editable*/ font-weight:normal;
    /*@editable*/ text-decoration:underline;
}

.footerContent img{
    display:inline;
}


#social{
    /*@editable*/ background-color:#FAFAFA;
    /*@editable*/ border:0;
}


#social div{
    /*@editable*/ text-align:center;
}


#utility{
    /*@editable*/ background-color:#FFFFFF;
    /*@editable*/ border:0;
}


#utility div{
    /*@editable*/ text-align:center;
}

#monkeyRewards img{
    max-width:190px;
}
</style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    	<center>
        	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable">
            	<tr>
                	<td align="center" valign="top">

                    	<table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer">
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- // Begin Template Header \\ -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader">
                                        <tr>
                                            <td class="headerContent">

                                            	<!-- // Begin Module: Standard Header Image \\ -->
                                            	<img src="<?php echo $EML_MAIN_LOGO; ?>" style="max-width:600px;" id="headerImage campaign-icon" mc:label="header_image" mc:edit="header_image" mc:allowdesigner mc:allowtext />
                                            	<!-- // End Module: Standard Header Image \\ -->

                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // End Template Header \\ -->

                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- // Begin Template Body \\ -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="templateBody">
                                    	<tr>
                                        	<td valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td valign="top" class="bodyContent">

                                                            <!-- // Begin Module: Standard Content \\ -->
                                                            <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td valign="top">
			                                                            <div mc:edit="std_content00">
                                                                            <h1 class="h1">We Apologize</h1>
                                                                            <h2 class="h2">For The Inconvenience.</h2>
                                                                            <br />
                                                                            It appears that you may be having problems resetting your password with <?php echo $COMPANY_NAME; ?>.
                                                                            <br />
                                                                            <br />
                                                                            We detected multiple attempts to reset your password on our site using this email address. If this was not you, we advise that you contact us at <?php echo $CUSTOMER_SERVICE_EMAIL; ?> so we can make sure your account is properly setup and secured.
                                                                            <br />
                                                                            <br />
                                                                            <br />
                                                                            To ensure delivery of this and future emails, please add <a href="mailto:<?php echo $ADD_THIS_EMAIL; ?>"><?php echo $ADD_THIS_EMAIL; ?></a> to your Address Book or Safe List.
                                                                            <br />
                                                                            <br />
                                                                            Your email address and contact information will not be shared with any third parties without your permission, unless we are required to do so by law or we need to perform necessary site functions.
                                                                            <br />
                                                                            <br />
                                                                            Sincerely,
                                                                            <br />
                                                                            <?php echo $COMPANY_SIGN_EMAIL_AS; ?>
                                                                            <br />
                                                                            <?php echo $CUSTOMER_SERVICE_NUMBER; ?>
                                                                            <br />
                                                                            <br />
                                                                            P.S.: We are truly sorry that you are having issues with our site. We will do all that is possible to get you up and running. Thank you for your business.
                                                                            <br />
			                                                                <br />
			                                                            </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <!-- // End Module: Standard Content \\ -->

                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // End Template Body \\ -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- // Begin Template Footer \\ -->
                                	<table border="0" cellpadding="10" cellspacing="0" width="600" id="templateFooter">
                                    	<tr>
                                        	<td valign="top" class="footerContent">

                                                <!-- // Begin Module: Standard Footer \\ -->
                                                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="middle" id="social">
                                                            <center><table border="0" cellpadding="0" cellspacing="4">
                                                                <tr mc:hideable>
                                                                    <td align="left" valign="middle" width="16">
                                                                        <img src="<?php echo $FB_LINK_LOGO; ?>" style="margin:0 !important;" />
                                                                    </td>
                                                                    <td align="left" valign="top">
                                                                        <div mc:edit="sbwi_link_one">
                                                                            <a href="<?php echo $FB_LINK; ?>">Like on Facebook</a>
                                                                        </div>
                                                                    </td>
                                                                    <td align="left" valign="middle" width="16">
                                                                        <img src="<?php echo $TWT_LINK_LOGO; ?>" style="margin:0 !important;" />
                                                                    </td>
                                                                    <td align="left" valign="top">
                                                                        <div mc:edit="sbwi_link_two">
                                                                            <a href="<?php echo $TWT_LINK; ?>">Follow on Twitter</a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table></center>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="top" width="350">
                                                            <div mc:edit="std_footer">
																<center><em>&copy; <?php echo $CURR_YR; ?> <?php echo $COMPANY_NAME; ?>, All rights reserved.</em></center>
																<br />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- // End Module: Standard Footer \\ -->

                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // End Template Footer \\ -->
                                </td>
                            </tr>
                        </table>
                        <br />
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>