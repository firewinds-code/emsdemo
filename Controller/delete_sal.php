<?php

require_once(__dir__.'/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$sql='delete from salary_master where id="'.$_REQUEST['id'].'"'; 
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sql);
    $mysql_error=$myDB->getLastError();
    echo "<script>$(function(){ toastr.error('No Data Found ".$mysql_error."'); }); </script>";
    // if(empty($mysql_error)){
	// 	echo "Row  Data Deleted "; 
	// }
	// else
	// {
	// 	echo "Row Not Deleted Try Again";
	// }
	
?>