<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$loc = '';
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$loc = clean($_REQUEST['locid']);
$conid = clean($_REQUEST['conid']);
$cmid = clean($_REQUEST['cmid']);
if (isset($loc) && $loc != '' && isset($conid) && $conid != '' && isset($cmid) && $cmid != '') {


	$sql = "select payout,tenure from manage_consultancy where consultancy_id=? and locid=? and cm_id=?;";
	$selectQ = $conn->prepare($sql);
	$selectQ->bind_param("iii", $conid, $loc, $cmid);
	$selectQ->execute();
	$result = $selectQ->get_result();
	$resu = $result->fetch_row();
	// $result = $myDB->query($sql);
	// $mysql_error = $myDB->getLastError();

	if ($result->num_rows > 0 && $result) {
		echo is_numeric($resu[0]) . '|$|' . is_numeric($resu[1]);
	}
}
