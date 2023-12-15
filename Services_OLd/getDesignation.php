<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
ini_set('display_errors', '1');
$fid = "";

if (isset($_REQUEST['fid']) && (trim($_REQUEST['fid']))) {
	$fid = clean($_REQUEST['fid']);
}

$sql = "";
if ($fid != "") {
	// $sql = "select designation_master.ID,designation_master.Designation,df_id from df_master inner join designation_master on df_master.des_id=designation_master.ID where function_id='" . $fid . "' ";
	$sql = "select designation_master.ID,designation_master.Designation,df_id from df_master inner join designation_master on df_master.des_id=designation_master.ID where function_id=? ";
	$stmte = $conn->prepare($sql);
	$stmte->bind_param("s", $fid);
	$stmte->execute();
	$res = $stmte->get_result();
	$designation = $res->fetch_all(MYSQLI_ASSOC);
} else {
	$sql = "select designation_master.ID,designation_master.Designation,df_id from df_master inner join designation_master on df_master.des_id=designation_master.ID  ";

	$stmte = $conn->prepare($sql);
	$stmte->execute();
	$res = $stmte->get_result();
	$designation = $res->fetch_all(MYSQLI_ASSOC);
}

if ($res->num_rows > 0) {
	echo json_encode($designation);
}



// if ($sql != "") {
// 	$myDB = new MysqliDb();
// 	$designation = $myDB->query($sql);
// 	$rowCount = $myDB->count;
// 	if ($rowCount > 0) {
// 		echo json_encode($designation);
// 	}
// }
