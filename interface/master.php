<?php
session_start(); 

if (empty($_SESSION['user_name'])) {
    header('Location: index.php');
    exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>MJNS ARS</title>
    <meta charset="utf-8">
    <meta name = "format-detection" content = "telephone=no" />
    <meta name="description" content="MJNS-ARS">
    <meta name="author" content="MJNS ARS">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/mjnsars.css">

    <!--[if lt IE 8]>
       <div style=' clear: both; text-align:center; position: relative;'>
         <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
           <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsin$
         </a>
      </div>
    <![endif]-->
    <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="css/ie.css">
    <![endif]-->

    </head>

    <body>
	<ul class="navigation-bar">
<!--	    <li class="nav-item top-nav-item"><a href="#" id="dashboard"><i class="fa-navbar fa fa-tachometer fa-3x"></i>Dashboard</a></li>-->
	    <li class="nav-item top-nav-item"><a href="#" id="ars-list"><i class="fa-navbar fa fa-reply-all fa-3x"></i>Abuse List</a></li>
	    <li class="nav-item"><a href="#" id="ars-matched-customers"><i class="fa-navbar fa fa-user-plus fa-3x"></i>Matched</a></li>
	    <li class="nav-item"><a href="#" id="ars-customers"><i class="fa-navbar fa fa-users fa-3x"></i>Customers</a></li>
	    <li class="nav-item"><a href="#" id="ars-routes"><i class="fa-navbar fa fa-sitemap fa-3x"></i>Routes</a></li>
	    <li class="nav-item"><a href="#" id="ars-settings"><i class="fa-navbar fa fa-sliders fa-3x"></i>Settings</a></li>
	</ul>
	<ul class="logout">
	    <li class="nav-item-logout"><a href="http://support.mjnshosting.com" target="_blank"><i class="fa-navbar fa fa-support fa-3x"></i>Remote Support</a></li>
	    <li class="nav-item-logout"><a href="https://mjnshosting.atlassian.net/wiki/pages/viewpage.action?pageId=18022402" target="_blank"><i class="fa-navbar fa fa-book fa-3x"></i>Documentation</a></li>
	    <li class="nav-item-logout"><a href="#" id="license"><i class="fa-navbar fa fa-file-text-o fa-3x"></i>License/Credit</a></li>
	    <li class="nav-item-logout" style="padding-bottom:10px"><a href="http://www.mjnshosting.com/index-5.html" target="_blank"><i class="fa-navbar fa fa-envelope-o fa-3x"></i>Contact Us</a></li>
            <li><a href="logout.php" id="logout"><i class="fa-navbar fa fa-sign-out fa-3x"></i>Logout</a></li>
        </ul>
	<input type="checkbox" id="nav-trigger" class="nav-trigger">
	<label for="nav-trigger"></label>
	<div class="site-wrap">
	    <div class="bg-1 page_home">
		    <section class="main">
			<div id="topmenu"></div>
                        <div id="master-dashboard" align="center"></div>
                        <div id="master-arslist" align="center"></div>
			<div id="master-license" align="center"></div>
			<div id="master-arsitem" align="center"></div>
			<div id="master-arsmatched" align="center"></div>
			<div id="master-arscustomers" align="center"></div>
			<div id="master-arsroutes" align="center"></div>
			<div id="master-arssettings" align="center"></div>
			    <footer>
				<div id="footer"></div>
			    </footer>
		    </section>
	    </div>
        </div>
  </body>
</html>

    <script src="js/jquery.js"></script>
    <script src="js/jquery-migrate-1.2.1.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/mjns-ars-master.js"></script>
    <script src="js/superfish.js"></script>
    <script src="js/script.js"></script>
    <script src="js/jquery.mobilemenu.js"></script>
    <!--[if (gt IE 9)|!(IE)]><!-->
        <script src="js/jquery.mobile.customized.min.js"></script>
    <!--<![endif]-->

    <script src="js/jquery.ui.totop.js"></script>
    <script src="js/jquery.carouFredSel-6.1.0-packed.js"></script>
    <script src="js/jquery.mousewheel.min.js"></script>
    <script src="js/jquery.touchSwipe.min.js"></script>

