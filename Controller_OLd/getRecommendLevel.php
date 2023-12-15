<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$loc = '';
$EmpID = clean($_REQUEST['EmpID']);
if (isset($EmpID) && $EmpID != "") {

	//$sql='call get_process_byclient("'.$_REQUEST['id'].'","'.$loc.'")';
	$sql = 'call apr_reclevel("' . $EmpID . '")';
	$myDB = new MysqliDb();
	$result = $myDB->query($sql);
	$mysql_error = $myDB->getLastError();
	if (count($result) > 0 && $result) {
		//echo '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			echo $value['ID'] . '|$|' . $value['Designation'] . '|$$|';
			//echo '<option value="'.$value['ID'].'"  >'.$value['Designation'].'</option>';

		}
	} else {
		echo '<option value="NA" >---Select---</option>';
	}
}
