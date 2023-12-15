<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$getr = clean($_REQUEST['getr']);
$rygstatus = clean($_REQUEST['rygstatus']);
if (isset($getr) && $getr == 'getremark' &&  $rygstatus != "") {
	$rygstatus = clean($_REQUEST['rygstatus']);
	$sql = "select id,substatus from ryg_substatus_master where RYG=?";
	$selectQury = $conn->prepare($sql);
	$selectQury->bind_param("s", $rygstatus);
	$selectQury->execute();
	$result = $selectQury->get_result();
	// $result = $myDB->query($sql);
	// $mysql_error = $myDB->getLastError();

	if (($result) && $result->num_rows > 0) {
		if ($result->num_rows > 1) {
			$option = "<option value=''  >Select</option>";
		}
		foreach ($result  as $val) {
			$option .= "<option value='" . $val['id'] . "'  >" . $val['substatus'] . "</option>";
		}
	}
	echo $option;
}
