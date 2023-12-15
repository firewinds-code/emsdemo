<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$refid = $_REQUEST['id'];
//$sql = "SELECT * from referral_dispo where ref_id='" . $refid . "'";

$sql = "select distinct cm.client_id,cm.client_name from new_client_master nc left join client_master cm on nc.client_name=cm.client_id where nc.cm_id not in (select cm_id from client_status_master) and nc.location=? order by cm.client_name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $refid);
if (!$stmt) {
	echo "failed to run";
	die;
}
$stmt->execute();
$query = $stmt->get_result();
$count = $query->num_rows;
$option ='<option value="">--Select--</option>';
if ($count > 0) {
	//echo '<option value=""  >--Select--</option>'
	foreach ($query as $key => $value) {
		$option .='<option value="' . $value['client_id'] . '"  >' . $value['client_name'] . '</option>';
	}
		echo $option;									
}

?>