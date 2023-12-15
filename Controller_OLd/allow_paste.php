<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
$val = clean($_REQUEST['val']);
$__myclipsession = clean($_SESSION['__myclipsession']);
if (isset($val)) {
	$__myclipsession = $val;
	echo $__myclipsession;
}
