<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$ID = clean($_REQUEST['id']);
// $sql='select designation_master.ID,designation_master.Designation from df_master inner join designation_master on df_master.des_id=designation_master.ID where function_id="'.$_REQUEST['id'].'"';
$sql = 'select designation_master.ID,designation_master.Designation from df_master inner join designation_master on df_master.des_id=designation_master.ID where function_id=?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ID);
$stmt->execute();
$result = $stmt->get_result();
// $myDB = new MysqliDb();
// $result = $myDB->query($sql);
// $mysql_error = $myDB->getLastError();
if ($result->num_rows > 0 && $result) {
	echo '<option value="NA" >---Select---</option>';
	foreach ($result as $key => $value) {
		echo '<option value="' . $value['ID'] . '" >' . $value['Designation'] . '</option>';
	}
} else {
	echo '<option value="NA" >---Select---</option>';
}
