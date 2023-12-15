<?php
session_start();
if($_SESSION)
{
	if($_SESSION['UsrNm']!='' || $_SESSION['UsrNm']==NULL)
	{
		header("location:../index.php");
	
	}
	else
	{
		header("location:../LogOut.php");
		
	}
}
else
{
	header("location:../LogOut.php");
	
}
?>