<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$ID = clean($_REQUEST['ID']);
$sql = 'select corrective_action_form_id,file_path from corrective_action_form_files where corrective_action_form_id=?';
$sel = $conn->prepare($sql);
$sel->bind_param("i", $ID);
$sel->execute();
$result = $sel->get_result();
// $result = $myDB->query($sql);
// $mysql_error = $myDB->getLastError();
if ($result->num_rows > 0 && $result) {
	$i = 1;
	foreach ($result as $key => $value) {
		//echo '<p><span><b>File '.$i.'</b><b>'.$value['created_by'].'</b> </span> <span class="blue-text">'.$value['created_at'].'</span> : '.$value['comment'].'</p>';
		echo '<a href="../corrective_action_form/' . $value['file_path'] . '" target="_blank"><b>File' . $i . '</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$i++;
	}
	echo '<br />';
} else {
	echo 'Not Exist';
}
