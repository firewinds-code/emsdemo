<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$UserName = '';
$msgLogin = '';

function get_client_ip_ref()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
/*$android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
if ($android == true) {
    $location = 'https://ems.cogentlab.com/emsapp/';
    echo "<script>location.href='" . $location . "'</script>";
} else {
    //$location= 'https://cogentems.in/erpm/Login';
    //echo "<script>location.href='".$location."'</script>";
}
*/
/*$myDB = new MysqliDb ();
		$ip_list = $myDB->rawQuery("select distinct IP from whitelistedip where IP = '".get_client_ip_ref()."' order by id desc limit 1;");
		if(count($ip_list) > 0 && $ip_list)
		{
			//do nothing;
		}
		else
		{
			if($_SESSION['__user_logid']=="CE03070003")
			{
				
			}
			else
			{
				$location= URL.'Error1.php'; 
				echo "<script>location.href='".$location."'</script>";	
			}			
			
		}*/

if (isset($_SESSION['__user_logid'])) {
    if ($_SESSION['__user_logid'] != '' || $_SESSION['__user_logid'] == NULL) {

        $UserName = $_SESSION['__user_logid'];
        if (isset($_SESSION['MsgLg'])) {
            $msgLogin = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error" style="display: block;"><button type="button" class="toast-close-button" role="button">×</button><div class="toast-message">' . $_SESSION['MsgLg'] . '</div></div></div>';
            //echo '<div class="tmp_div slideInRight animated"><img alt="Close" class="msg-close1" src="'.STYLE.'images/close_window.png"/>'.$msgLogin.'</div>';
        }
    } else {
        $UserName = '';
    }
} else {
    if (isset($_SESSION['MsgLg'])) {


        $msgLogin = '<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-error" style="display: block;"><button type="button" class="toast-close-button" role="button">×</button><div class="toast-message">' . $_SESSION['MsgLg'] . '</div></div></div>';
        //echo '<div class="tmp_div fadeInLeftBig  animated hidden"><img alt="Close" class="msg-close1" src="'.STYLE.'images/close_window.png"/>'.$msgLogin.'</div>';
    }
    $UserName = '';
}

if (isset($_COOKIE['usrnm'])) {
    $UserName = $_COOKIE['usrnm'];
    setcookie('usrnm', '', time() - (86400 * 30), "/"); // 86400 = 1 day

}
if (isset($_COOKIE['reload'])) {
    $divinfoShow = 'hidden';
    setcookie('reload', '', time() - (86400 * 30), "/"); // 86400 = 1 day

} else {
    $divinfoShow = '';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" class="">

<head>

    <title>EMS Login</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


    <link rel="icon" href="<?php echo STYLE . 'images/icon-cm-reporting.png'; ?>" />

    <link rel="icon" type="image/png" href="<?php echo STYLE . 'img/favicon.png'; ?>" />
    <link rel="stylesheet" href="<?php echo STYLE . 'fontaws/css/font-awesome.min.css'; ?>" />
    <!--<script src="<?php echo SCRIPT . 'jquery.js'; ?>"></script>-->
    <!--<script src="<?php echo SCRIPT . 'jquery-ui.min.js'; ?>"></script>-->
    <link rel="stylesheet" href="<?php echo STYLE . 'animate.css'; ?>" />
    <link rel="stylesheet" href="<?php echo STYLE . 'jquery-ui.min.css'; ?>" />
    <!--<script src="<?php echo STYLE . 'Theme/Waves-master/waves.js'; ?>"></script>-->
    <link rel="stylesheet" href="<?php echo STYLE . 'Theme/lib-file.css'; ?>" />
    <link rel="stylesheet" href="<?php echo STYLE . 'Theme/tipTip.css'; ?>" />
    <link rel="stylesheet" href="<?php echo STYLE . 'bootstrap.min.css'; ?>" />


    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
            <script type="text/javascript" src="/webres_5a782f8c2bc2c7.65161071/js/html5shiv/html5shiv.min.js"></script>
        <![endif]-->

</head>

<body>

    <div id="wrapper">

        <div id="content" style="padding-top:0">
            <?php echo $msgLogin; ?>
            <style type="text/css">
                body {
                    background-color: #eaeaea;
                }

                .toast-title {
                    font-weight: bold;
                }

                .toast-message {
                    -ms-word-wrap: break-word;
                    word-wrap: break-word;
                }

                .toast-message a,
                .toast-message label {
                    color: #ffffff;
                }

                .toast-message a:hover {
                    color: #cccccc;
                    text-decoration: none;
                }

                .toast-close-button {
                    position: relative;
                    right: -0.3em;
                    top: -0.3em;
                    float: right;
                    font-size: 20px;
                    font-weight: bold;
                    color: #ffffff;
                    -webkit-text-shadow: 0 1px 0 #ffffff;
                    text-shadow: 0 1px 0 #ffffff;
                    opacity: 0.8;
                    -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=80);
                    filter: alpha(opacity=80);
                }

                .toast-close-button:hover,
                .toast-close-button:focus {
                    color: #000000;
                    text-decoration: none;
                    cursor: pointer;
                    opacity: 0.4;
                    -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=40);
                    filter: alpha(opacity=40);
                }

                /*Additional properties for button version
 iOS requires the button element instead of an anchor tag.
 If you want the anchor version, it requires `href="#"`.*/
                button.toast-close-button {
                    padding: 0;
                    cursor: pointer;
                    background: transparent;
                    border: 0;
                    -webkit-appearance: none;
                }

                .toast-top-center {
                    top: 0;
                    right: 0;
                    width: 100%;
                }

                .toast-bottom-center {
                    bottom: 0;
                    right: 0;
                    width: 100%;
                }

                .toast-top-full-width {
                    top: 0;
                    right: 0;
                    width: 100%;
                }

                .toast-bottom-full-width {
                    bottom: 0;
                    right: 0;
                    width: 100%;
                }

                .toast-top-left {
                    top: 12px;
                    left: 12px;
                }

                .toast-top-right {
                    top: 12px;
                    right: 12px;
                }

                .toast-bottom-right {
                    right: 12px;
                    bottom: 12px;
                }

                .toast-bottom-left {
                    bottom: 12px;
                    left: 12px;
                }

                #toast-container {
                    position: fixed;
                    z-index: 999999;
                    /*overrides*/

                }

                #toast-container * {
                    -moz-box-sizing: border-box;
                    -webkit-box-sizing: border-box;
                    box-sizing: border-box;
                }

                #toast-container>div {
                    position: relative;
                    overflow: hidden;
                    margin: 0 0 6px;
                    padding: 15px 15px 15px 50px;
                    width: 300px;
                    -moz-border-radius: 3px 3px 3px 3px;
                    -webkit-border-radius: 3px 3px 3px 3px;
                    border-radius: 3px 3px 3px 3px;
                    background-position: 15px center;
                    background-repeat: no-repeat;
                    -moz-box-shadow: 0 0 12px #999999;
                    -webkit-box-shadow: 0 0 12px #999999;
                    box-shadow: 0 0 12px #999999;
                    color: #ffffff;
                    opacity: 0.8;
                    -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=80);
                    filter: alpha(opacity=80);
                }

                #toast-container>div:hover {
                    -moz-box-shadow: 0 0 12px #000000;
                    -webkit-box-shadow: 0 0 12px #000000;
                    box-shadow: 0 0 12px #000000;
                    opacity: 1;
                    -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=100);
                    filter: alpha(opacity=100);
                    cursor: pointer;
                }

                #toast-container>.toast-info {
                    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGwSURBVEhLtZa9SgNBEMc9sUxxRcoUKSzSWIhXpFMhhYWFhaBg4yPYiWCXZxBLERsLRS3EQkEfwCKdjWJAwSKCgoKCcudv4O5YLrt7EzgXhiU3/4+b2ckmwVjJSpKkQ6wAi4gwhT+z3wRBcEz0yjSseUTrcRyfsHsXmD0AmbHOC9Ii8VImnuXBPglHpQ5wwSVM7sNnTG7Za4JwDdCjxyAiH3nyA2mtaTJufiDZ5dCaqlItILh1NHatfN5skvjx9Z38m69CgzuXmZgVrPIGE763Jx9qKsRozWYw6xOHdER+nn2KkO+Bb+UV5CBN6WC6QtBgbRVozrahAbmm6HtUsgtPC19tFdxXZYBOfkbmFJ1VaHA1VAHjd0pp70oTZzvR+EVrx2Ygfdsq6eu55BHYR8hlcki+n+kERUFG8BrA0BwjeAv2M8WLQBtcy+SD6fNsmnB3AlBLrgTtVW1c2QN4bVWLATaIS60J2Du5y1TiJgjSBvFVZgTmwCU+dAZFoPxGEEs8nyHC9Bwe2GvEJv2WXZb0vjdyFT4Cxk3e/kIqlOGoVLwwPevpYHT+00T+hWwXDf4AJAOUqWcDhbwAAAAASUVORK5CYII=") !important;
                }

                #toast-container>.toast-error {
                    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAHOSURBVEhLrZa/SgNBEMZzh0WKCClSCKaIYOED+AAKeQQLG8HWztLCImBrYadgIdY+gIKNYkBFSwu7CAoqCgkkoGBI/E28PdbLZmeDLgzZzcx83/zZ2SSXC1j9fr+I1Hq93g2yxH4iwM1vkoBWAdxCmpzTxfkN2RcyZNaHFIkSo10+8kgxkXIURV5HGxTmFuc75B2RfQkpxHG8aAgaAFa0tAHqYFfQ7Iwe2yhODk8+J4C7yAoRTWI3w/4klGRgR4lO7Rpn9+gvMyWp+uxFh8+H+ARlgN1nJuJuQAYvNkEnwGFck18Er4q3egEc/oO+mhLdKgRyhdNFiacC0rlOCbhNVz4H9FnAYgDBvU3QIioZlJFLJtsoHYRDfiZoUyIxqCtRpVlANq0EU4dApjrtgezPFad5S19Wgjkc0hNVnuF4HjVA6C7QrSIbylB+oZe3aHgBsqlNqKYH48jXyJKMuAbiyVJ8KzaB3eRc0pg9VwQ4niFryI68qiOi3AbjwdsfnAtk0bCjTLJKr6mrD9g8iq/S/B81hguOMlQTnVyG40wAcjnmgsCNESDrjme7wfftP4P7SP4N3CJZdvzoNyGq2c/HWOXJGsvVg+RA/k2MC/wN6I2YA2Pt8GkAAAAASUVORK5CYII=") !important;
                }

                #toast-container>.toast-success {
                    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADsSURBVEhLY2AYBfQMgf///3P8+/evAIgvA/FsIF+BavYDDWMBGroaSMMBiE8VC7AZDrIFaMFnii3AZTjUgsUUWUDA8OdAH6iQbQEhw4HyGsPEcKBXBIC4ARhex4G4BsjmweU1soIFaGg/WtoFZRIZdEvIMhxkCCjXIVsATV6gFGACs4Rsw0EGgIIH3QJYJgHSARQZDrWAB+jawzgs+Q2UO49D7jnRSRGoEFRILcdmEMWGI0cm0JJ2QpYA1RDvcmzJEWhABhD/pqrL0S0CWuABKgnRki9lLseS7g2AlqwHWQSKH4oKLrILpRGhEQCw2LiRUIa4lwAAAABJRU5ErkJggg==") !important;
                }

                #toast-container>.toast-warning {
                    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGYSURBVEhL5ZSvTsNQFMbXZGICMYGYmJhAQIJAICYQPAACiSDB8AiICQQJT4CqQEwgJvYASAQCiZiYmJhAIBATCARJy+9rTsldd8sKu1M0+dLb057v6/lbq/2rK0mS/TRNj9cWNAKPYIJII7gIxCcQ51cvqID+GIEX8ASG4B1bK5gIZFeQfoJdEXOfgX4QAQg7kH2A65yQ87lyxb27sggkAzAuFhbbg1K2kgCkB1bVwyIR9m2L7PRPIhDUIXgGtyKw575yz3lTNs6X4JXnjV+LKM/m3MydnTbtOKIjtz6VhCBq4vSm3ncdrD2lk0VgUXSVKjVDJXJzijW1RQdsU7F77He8u68koNZTz8Oz5yGa6J3H3lZ0xYgXBK2QymlWWA+RWnYhskLBv2vmE+hBMCtbA7KX5drWyRT/2JsqZ2IvfB9Y4bWDNMFbJRFmC9E74SoS0CqulwjkC0+5bpcV1CZ8NMej4pjy0U+doDQsGyo1hzVJttIjhQ7GnBtRFN1UarUlH8F3xict+HY07rEzoUGPlWcjRFRr4/gChZgc3ZL2d8oAAAAASUVORK5CYII=") !important;
                }

                #toast-container.toast-top-center>div,
                #toast-container.toast-bottom-center>div {
                    width: 300px;
                    margin-left: auto;
                    margin-right: auto;
                }

                #toast-container.toast-top-full-width>div,
                #toast-container.toast-bottom-full-width>div {
                    width: 96%;
                    margin-left: auto;
                    margin-right: auto;
                }

                .toast {
                    background-color: #030303;
                }

                .toast-success {
                    background-color: #51a351;
                }

                .toast-error {
                    background-color: #bd362f;
                }

                .toast-info {
                    background-color: #2f96b4;
                }

                .toast-warning {
                    background-color: #f89406;
                }

                .toast-progress {
                    position: absolute;
                    left: 0;
                    bottom: 0;
                    height: 4px;
                    background-color: #000000;
                    opacity: 0.4;
                    -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=40);
                    filter: alpha(opacity=40);
                }

                /*Responsive Design*/
                @media all and (max-width: 240px) {
                    #toast-container>div {
                        padding: 8px 8px 8px 50px;
                        width: 11em;
                    }

                    #toast-container .toast-close-button {
                        right: -0.2em;
                        top: -0.2em;
                    }
                }

                @media all and (min-width: 241px) and (max-width: 480px) {
                    #toast-container>div {
                        padding: 8px 8px 8px 50px;
                        width: 18em;
                    }

                    #toast-container .toast-close-button {
                        right: -0.2em;
                        top: -0.2em;
                    }
                }

                @media all and (min-width: 481px) and (max-width: 768px) {
                    #toast-container>div {
                        padding: 15px 15px 15px 50px;
                        width: 25em;
                    }
                }

                #toast-container>.toast-error {
                    background-color: #F44336;

                }

                .social-buttons {
                    height: 50px;
                    margin-top: 20px;
                    font-size: 0;
                    text-align: center;
                    top: 0;
                    bottom: 0;
                    left: 0;
                    right: 0;
                }

                .social-button {
                    display: inline-block;
                    background-color: #fff;
                    width: 40px;
                    height: 40px;
                    line-height: 40px;
                    margin: 0 5px 5px;
                    text-align: center;
                    position: relative;
                    overflow: hidden;
                    opacity: .99;
                    border-radius: 25%;
                    box-shadow: 0 0 30px 0 rgba(0, 0, 0, 0.05);
                    -webkit-transition: all 0.35s cubic-bezier(0.31, -0.105, 0.43, 1.59);
                    transition: all 0.35s cubic-bezier(0.31, -0.105, 0.43, 1.59);
                }

                .social-button:before {
                    content: '';

                    width: 120%;
                    height: 120%;
                    position: absolute;
                    top: 90%;
                    left: -110%;
                    -webkit-transform: rotate(45deg);
                    transform: rotate(45deg);
                    -webkit-transition: all 0.35s cubic-bezier(0.31, -0.105, 0.43, 1.59);
                    transition: all 0.35s cubic-bezier(0.31, -0.105, 0.43, 1.59);
                }

                .social-button .fa {
                    font-size: 38px;
                    vertical-align: middle;
                    -webkit-transform: scale(0.8);
                    transform: scale(0.8);
                    -webkit-transition: all 0.35s cubic-bezier(0.31, -0.105, 0.43, 1.59);
                    transition: all 0.35s cubic-bezier(0.31, -0.105, 0.43, 1.59);
                }

                .social-button.facebook:before {
                    background-color: #3B5998;
                }

                .social-button.facebook .fa {
                    color: #3B5998;
                }

                .social-button.twitter:before {
                    background-color: #3CF;
                }

                .social-button.twitter .fa {
                    color: #3CF;
                }

                .social-button.google:before {
                    background-color: #DC4A38;
                }

                .social-button.google .fa {
                    color: #DC4A38;
                }

                .social-button.linkedin:before {
                    background-color: #539dea;
                }

                .social-button.linkedin .fa {
                    color: #539dea;
                }

                .social-button.youtube:before {
                    background-color: #e8275e;
                }

                .social-button.youtube .fa {
                    color: #e8275e;
                }

                .social-button.dribbble:before {
                    background-color: #F26798;
                }

                .social-button.dribbble .fa {
                    color: #F26798;
                }

                .social-button.skype:before {
                    background-color: #00AFF0;
                }

                .social-button.skype .fa {
                    color: #00AFF0;
                }

                .social-button:focus:before,
                .social-button:hover:before {
                    top: -10%;
                    left: -10%;
                }

                .social-button:focus .fa,
                .social-button:hover .fa {
                    color: #fff;
                    -webkit-transform: scale(1);
                    transform: scale(1);
                }

                .outer {
                    width: 100%;
                    border: 10px;

                    /* Firefox */
                    display: -moz-box;
                    -moz-box-pack: center;
                    -moz-box-align: center;

                    /* Safari and Chrome */
                    display: -webkit-box;
                    -webkit-box-pack: center;
                    -webkit-box-align: center;

                    /* W3C */
                    display: box;
                    box-pack: center;
                    box-align: center;
                }

                .divCopyright {
                    display: block;
                    float: left;
                    margin-top: 10px;
                    margin-bottom: 10px;
                    width: 100%;
                    text-align: center;
                    color: rgba(51, 51, 51, 1);
                    font-family: "Open Sans", sans-serif;
                    font-size: 12px;
                }

                #divLoginButton {
                    margin-top: 14px;
                    float: left;
                    width: 100%;
                }

                #loginAsButtonGroup {
                    width: 100%;
                }

                #loginAsButtonGroup button {
                    width: 100%;
                }

                #loginAsButtonGroup button span {
                    margin-left: 2%;
                }

                #loginAsButtonGroup .dropdown-menu {
                    width: 100%;
                }

                #loginFormContainer {
                    background: #f68422;
                    color: #ffffff;
                    min-height: 360px;
                }

                #largeScreenPaddingDiv {
                    margin-top: 140px;
                }

                #paneContainer {
                    background: #ffffff;
                }

                #spanMessage {
                    text-align: center;
                    display: block;
                    margin: 1% auto 1% auto;
                }

                .btn-success {
                    background: #47a447;
                    border-color: #47a447;
                    min-width: 100%;
                    margin-bottom: 2%;
                }

                .btn-lg {
                    padding-left: 30px;
                    padding-right: 30px;
                }

                .marginBottom10 {
                    margin-bottom: 10px;
                }

                .paddingTop20 {
                    padding-top: 20px;

                }

                #content,
                .default-bg,
                body {
                    background-color: transparent;
                }

                #content,
                .default-bg,
                body {
                    background-color: #ececec;
                }

                .container .row {
                    margin: 0;

                }

                div.footer {
                    height: 90px;
                    padding-top: 100px;
                }

                div#paneContainer {
                    padding: 0px;
                }

                div.footer {
                    height: 90px;
                    padding-top: 40px;
                    font-size: 12px;
                    font-weight: 400;
                    color: #626262;
                }

                .divCopyright {
                    display: block;
                    float: left;
                    margin-top: 10px;
                    margin-bottom: 10px;
                    width: 100%;
                    text-align: center;
                    color: rgba(51, 51, 51, 1);
                    font-family: "Open Sans", sans-serif;
                    font-size: 12px;
                }

                #txt_usr_pwd,
                #txt_usrId {
                    outline: none;
                }

                #forgetpass {
                    color: white;
                }
            </style>
            <link rel="stylesheet" href="<?php echo STYLE . 'Theme2.css'; ?>">
            </link>
            <div class=" hidden-sm hidden-xs" id="largeScreenPaddingDiv"> </div>
            <div class="outer" style="background: #ebebeb;">
                <div class="container col-md-7 col-sm-8 col-lg-7" id="paneContainer">
                    <div class="row">
                        <div class="col-md-5" style="padding: 0px;">
                            <div class="outer hidden-xs">

                                <div class="col-md-12 hidden-sm  pull-left social-buttons outer ">

                                </div>

                            </div>
                            <div class="outer hidden-xs" style="height: 40px;">

                                <div class="col-md-12 hidden-sm  pull-left social-buttons outer " style="height: 40px;">

                                </div>

                            </div>
                            <div class="col-md-12 marginBottom10 outer"> <img style="width: 80%;" src="<?php echo STYLE . 'images/Cogent.png'; ?>" class="img-responsive"> </div>


                            <div class="outer hidden-xs">
                                <!-- social media -->
                                <div class="col-md-12 hidden-sm  pull-left social-buttons outer ">
                                    <!--<a href="//www.facebook.com/OrangeHRM" class="social-button facebook"><i class="fa fa-facebook"></i></a>
                        <a href="//twitter.com/orangehrm" class="social-button twitter"><i class="fa fa-twitter"></i></a>
                        <a href="//plus.google.com/+OrangeHRM/" class="social-button google"><i class="fa fa-google"></i></a>
                        <a href="//www.linkedin.com/company/orangehrm" class="social-button linkedin"><i class="fa fa-linkedin"></i></a>
                        <a href="//www.youtube.com/user/orangehrm" class="social-button youtube"><i class="fa fa-youtube"></i></a>-->
                                </div>
                                <!-- social media -->


                            </div>
                            <div class="center-align">

                                <div class="divCopyright">Cogent EMS.<br>
                                    &copy; <?php echo date('Y') . '-' . date('Y', strtotime("next year")); ?> <a href="http://www.cogenteservices.com" target="_blank">Cogent ES</a> All rights reserved.
                                </div>

                            </div>
                        </div>


                        <div class="col-md-7 " id="loginFormContainer">

                            <div class="col-md-12 paddingTop20" id="midPaneContentWrapper">
                                <h1><b>Login</b></h1>
                                <div class="col-md-6" style="margin-left: 0px !important;padding-left: 0px !important;">
                                    <h3>EMS </h3>
                                </div>
                                <div class="col-md-6" style="margin-top: 26px;margin-right: 0px !important;padding-right: 13px !important;text-align: right;"><b><a href="<?php echo URL . 'FileContainer/ISMS_Awareness.pdf'; ?>" target="_blank" style="color: white;">ISMS AWARENESS</a></b></div>

                                <div class="divForm form">
                                    <form name="indexForm" id="indexForm" method="post" action="<?php echo (INCL . 'ConLog.php'); ?>">

                                        <div id="divUsername" class="textInputContainer marginBottom10 form-group">
                                            <input type="text" placeholder="User Id" name="txt_usrId" required="" id="txt_usrId" class="input usrbx form-control" value="<?php echo $UserName; ?>">
                                        </div>
                                        <div id="divPassword" class="textInputContainer marginBottom10 form-group">
                                            <input type="password" placeholder="Password" required="" name="txt_usr_pwd" id="txt_usr_pwd" class="input passbx form-control">

                                        </div>
                                        <div id="divLoginButton">
                                            <input type="submit" name="btn_login" class="btn btn-success" id="btn_login" value="LOGIN">

                                            <a href="<?php echo URL . 'View/sign_up.php'; ?>" id="btn_sign" class="btn btn-primary btn-block">Sign Up</a>
                                            <div class="btn-group col-sm-12" id="loginAsButtonGroup">
                                                <p></p>
                                                <div class="col-sm-6">

                                                    <input type="checkbox" checked="true" name="logchek" id="logchek" value="10">
                                                    <span class="securetxt">Keep me signed in</span>

                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="<?php echo URL . 'View/pwd_rcvr.php'; ?>"><span id="forgetpass">Forgot Password?</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <script type="text/javascript">
                $('a.login-as').on('click', function() {
                    $('#txtUsername').val($(this).data('username'));
                    $('#txtPassword').val($(this).data('password'));
                    $('#frmLogin').submit();
                });
            </script>

            <script type="text/javascript">
                var csrfUrl = '';
                var formReadyToSubmit = false;

                function calculateUserTimeZoneOffset() {

                }

                function addHint(inputObject, hintImageURL) {

                }

                function removeHint() {
                    $('.form-hint').css('display', 'none');
                }

                function showMessage(message) {
                    if ($('#spanMessage').size() == 0) {
                        $('<span id="spanMessage"></span>').insertAfter('#btn_login');
                    }

                    $('#spanMessage').html(message);
                }

                $("#btn_login").click(function() {
                    var isEmptyPasswordAllowed = false;

                    if ($('#txt_usrId').val() == '') {
                        showMessage('User ID cannot be empty');
                        return false;
                    }
                    if ($("#txt_usr_pwd").length) {
                        var txtPasswordExist = true;
                    } else {
                        var txtPasswordExist = false;
                    }

                    if (!isEmptyPasswordAllowed) {
                        if ($('#txt_usr_pwd').val() == '' || !txtPasswordExist) {
                            showMessage('Password cannot be empty');
                            return false;
                        }
                    }
                    return true;
                });


                $(document).ready(function() {
                    /*Set a delay to compatible with chrome browser*/

                    $('#txtUsername').focus(function() {
                        removeHint();
                    });

                    $('#txtPassword').focus(function() {
                        removeHint();
                    });

                    $('.form-hint').click(function() {
                        removeHint();
                        $('#txtUsername').focus();
                    });


                });
            </script>

        </div> <!-- content -->

    </div> <!-- wrapper -->
    <script type="text/javascript">
        $(document).ready(function() {
            <?php
            echo "var servertime = new Date('" . date("Y-m-d H:i:s", time()) . "');";
            $serverTime = date("Y-m-d H:i:s", time());
            ?>

            var dt = new Date();
            var timeDifference = dt - servertime;
            var n = dt.getTimezoneOffset();

            if (Math.abs(timeDifference / (1000 * 60 * 60)) > 12) {
                alert(" Server  Time Is " + servertime);
                $("body").empty().append("<Style>body {background: url(<?php echo STYLE . 'img/404background.jpg'; ?>);font-family: Helvetica, arial, sans-serif;color: #ccc;}.alert-container {background: url(<?php echo STYLE . 'img/404_textbox.png'; ?>);width: 918px;height: 142px;margin: 82px auto 0px;}.alert-inner {padding: 24px 0px 0px 209px;}.alert-heading {font-size: 35px;font-weight: bold;line-height: 50px;}.alert-subheading {margin-top: 8px;font-size: 18px;line-height: 28px;}.redirect {width: 918px;margin: 24px auto 0px;font-size: 14px;line-height: 14px;text-align: center;}.redirect a {color: #ffb300;text-decoration: none;border: 1px solid #8B6A1D;padding: 5px;}code{color: #DBA914;}</style><div class='alert-container'><div class='alert-inner'><div class='alert-heading'>Error - System Time Missmatch </div><div class='alert-subheading'>Sorry, you can't access the page without time match.For access set your system time with server. </div></div></div><div class='redirect'> If not resolve then show to your supervisor and contact your IT person. If done , <a href=''>  <i class='fa fa-home'></i> click here</a> to be redirected to the <i class='fa fa-home'></i> Home.</div>");
            }

            $('#msg_div').hide();
            $('#msgpanel').show("drop", {
                direction: "top"
            }, "slow");
            $('#msgpanel').hide();
            $('.tmp_div').removeClass('hidden');
            $('#msg_div > div.msg-hdr').html('');
            $('.tmp_div').delay(20000).fadeOut(400);
            $('.msg-close1').click(function() {

                $('.tmp_div').empty().remove();

            });
            $('#msg_div > div.msg-div').html('');
            $('#btn_login').click(function() {
                var validate = 0;
                $('#txt_usrId').removeAttr('style');
                $('#txt_usr_pwd').removeAttr('style');
                if ($('#txt_usrId').val() == '') {
                    validate = 1;
                    $('#txt_usrId').css('border-color', 'red');
                }
                if ($('#txt_usr_pwd').val() == '') {
                    validate = 1;
                    $('#txt_usr_pwd').css('border-color', 'red');
                }


                if (validate != 0) {
                    $('#msgpanel').html('Please fill Red bordered field...');
                    $('#msgpanel').show("drop", {
                        direction: "top"
                    }, "slow");
                    return false;

                } else {
                    $('#btn_login').addClass('hidden');
                }

            });
            $('#txt_usrId,#txt_usr_pwd').click(function() {
                $('#msgpanel').html('');
                $('#msgpanel').hide(200);
                $('#txt_usrId').removeAttr('style');
                $('#txt_usr_pwd').removeAttr('style');
            });
            $(".toplink").click(function() {

                $("#blackOver").css('display', 'block');
                $("#blackOver").css('visibility', 'visible');

                $("#light").css('display', 'block');
                $("#light").css('visibility', 'visible');


            });
            $("#CloseLight").click(function() {

                $("#blackOver").css('display', 'none');
                $("#blackOver").css('visibility', 'hidden');
                $("#light").css('display', 'none');
                $("#light").css('visibility', 'hidden');


            });

        });
    </script>
    <script>
        localizedDateTextObject = JSON.parse('{"Today":"Today","Tomorrow":"Tomorrow","Yesterday":"Yesterday","Monday":"Monday","Tuesday":"Tuesday","Wednesday":"Wednesday","Thursday":"Thursday","Friday":"Friday","Saturday":"Saturday","Sunday":"Sunday","Jan":"Jan","Feb":"Feb","Mar":"Mar","Apr":"Apr","May":"May","Jun":"Jun","Jul":"Jul","Aug":"Aug","Sep":"Sep","Oct":"Oct","Nov":"Nov","Dec":"Dec","Sun":"Sun","Mon":"Mon","Tue":"Tue","Wed":"Wed","Thu":"Thu","Fri":"Fri","Sat":"Sat","January":"January","February":"February","March":"March","April":"April","June":"June","July":"July","August":"August","September":"September","October":"October","November":"November","December":"December"}');
    </script>

    <script type="text/javascript">
        $(".toast").click(function() {
            $(this).remove();
        });
        $('.forgotpasslink').click(function() {
            $('#MessageDiv').removeClass('hidden').addClass('bounceInDown animated').delay(5000).queue(function(next) {
                $('#MessageDiv').removeClass('bounceInDown animated').addClass("bounceOutDown animated").delay(1000).queue(function(next) {
                    $('#MessageDiv').removeClass('bounceOutDown animated').addClass("hidden");
                    next();
                });
                next();
            });
            $('#MessageDiv_info').html('Please wait redirect to the Password Recover Page...');
            //	$('#MessageDiv').delay(5000).addClass('hidden');
        });
        $('.img-circle-close').click(function() {
            $('#MessageDiv').removeClass('bounceInDown animated').addClass("hidden").stop();
        });

        $('.theName').click(function() {
            if ($('.popup_div').hasClass('hidden')) {
                $('.popup_div').removeClass('hidden');
            } else {
                $('.popup_div').removeClass('zoomOutUp').addClass('zoomInDown');
            }

        });
        $(document).keyup(function(e) {

            if (e.keyCode == 27) {
                $('.popup_div').fadeOut("slow");
            }
        });
    </script>

</body>

</html>