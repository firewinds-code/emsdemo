<?php

require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
if(isset($_REQUEST['val']))
{
	$_SESSION['__myclipsession']=$_REQUEST['val'];
	echo $_SESSION['__myclipsession'];
}

?>

				