<?php 

require_once(__dir__.'/Config/init.php');
$location= 'http://' . $_SERVER['HTTP_HOST'].'/ems/View/';
 
//echo 'http://' . $_SERVER['HTTP_HOST'].'/ems/';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>404 - Page Not Found</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="robots" content="noarchive" />
	
	<link rel="stylesheet" href="<?php echo STYLE.'fontaws/css/font-awesome.min.css' ;?>" />
	<style type="text/css">
	body {
		background: url(<?php echo STYLE.'img/404background.jpg' ;?>);
		font-family: Helvetica, arial, sans-serif;
		color: #ccc;
	}
	.alert-container {
		background: url(<?php echo STYLE.'img/404_textbox.png' ;?>);
		width: 918px;
		height: 142px;
		margin: 82px auto 0px;
	}
	.alert-inner {
		padding: 24px 0px 0px 209px;
	}
	.alert-heading {
		font-size: 50px;
		font-weight: bold;
		line-height: 50px;
	}
	.alert-subheading {
		margin-top: 8px;
		font-size: 28px;
		line-height: 28px;
	}
	.redirect {
		width: 918px;
		margin: 24px auto 0px;
		font-size: 14px;
		line-height: 14px;
		text-align: center;
	}
	.redirect a {
		color: #ffb300;
	    text-decoration: none;
	    border: 1px solid #8B6A1D;
	    padding: 5px;
	}
	code
	{
		color: #DBA914;
	}
	</style>
</head>
<body>
	<div class="alert-container">
		<div class="alert-inner">
			<div class="alert-heading">404 - Page Not Found</div>
			<div class="alert-subheading">Sorry, the page you are looking for does not exist ...</div>
		</div>
	</div>
	<div class="redirect">Please check the URL. <code>May be not heaving the rights Too access</code>  Otherwise, <a href="<?php echo $location; ?>">  <i class="fa fa-home"></i> click here</a> to be redirected to the <i class="fa fa-home"></i> Home.</div>
	
</body>
</html>