<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$cm_id = '';
$cm_id = clean($_REQUEST['cm_id']);
if (isset($cm_id) && $cm_id != '') {
	$cm_id = clean($_REQUEST['cm_id']);
}
// $sql = 'SELECT b.ConsultancyName,b.id as cid FROM manage_consultancy a INNER JOIN consultancy_master b ON a.consultancy_id=b.id where a.cm_id="' . $cm_id . '"';
$sql = 'SELECT b.ConsultancyName,b.id as cid FROM manage_consultancy a INNER JOIN consultancy_master b ON a.consultancy_id=b.id where a.cm_id=?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cm_id);
$stmt->execute();
$result = $stmt->get_result();
// print_r($result);

if ($result->num_rows > 0 && $result) {
	echo '<option value="" >Select Consultancy</option>';
	foreach ($result as $key => $value) {
		echo '<option value="' . $value['cid'] . '" >' . $value['ConsultancyName'] . '</option>';
	}
} else {
	echo '<option value="" >Select Consultancy</option>';
}
