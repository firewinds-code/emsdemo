<?php  
session_start();

if((($_SESSION['__status_th']!='No' && $_SESSION['__status_th']==$_SESSION['__user_logid']) && $_SESSION['__status_th']!='' && $_SESSION['__user_type'] !='ADMINISTRATOR')|| (($_SESSION['__status_ah']!='No' && $_SESSION['__status_ah']==$_SESSION['__user_logid']) && $_SESSION['__status_ah']!='' && $_SESSION['__user_type'] !='ADMINISTRATOR') || (($_SESSION['__status_qh']!='No' && $_SESSION['__status_qh']==$_SESSION['__user_logid']) && $_SESSION['__status_qh']!='' && $_SESSION['__user_type'] !='ADMINISTRATOR'))
{
	$location= 'http://' . $_SERVER['HTTP_HOST'] .'/emsr2/login_conlog.php?key='.$_SERVER['HTTP_HOST'].'&userid='.$_SESSION['__user_logid'].'&usertype=Admin'; 
}
elseif($_SESSION['__user_type']=='ADMINISTRATOR')
{
	$location= 'http://' . $_SERVER['HTTP_HOST'] .'/emsr2/login_conlog.php?key='.$_SERVER['HTTP_HOST'].'&userid='.$_SESSION['__user_logid'].'&usertype=Administrator'; 
}
else
{
	$location= 'http://' . $_SERVER['HTTP_HOST'] .'/emsr2/login_conlog.php?key='.$_SERVER['HTTP_HOST'].'&userid='.$_SESSION['__user_logid'].'&usertype='.$_SESSION['__user_type']; 
}
header("Location: $location");
die();
?>