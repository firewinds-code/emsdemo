<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$action = cleanUserInput($_GET['action']);
$empid = cleanUserInput($_GET['empid']);

if (isset($action) and $action == 'search' and $empid  != "") {

	// $action = $_GET['action'];
	// $empid = trim($_GET['empid']);

	if ($empid != '') {
		// $sqlConnect = "select * from tbl_chat_message where to_empid='" . $empid . "' order by ID desc";
		$sqlConnect = "select * from tbl_chat_message where to_empid=? order by ID desc";
		$stmt = $conn->prepare($sqlConnect);
		$stmt->bind_param("s", $empid);
		$stmt->execute();
		$result = $stmt->get_result();
		// $result = $myDB->query($sqlConnect);
		$tableValue = "";
		//echo "hello";
		//if(mysql_num_rows($result)>0)
		if ($result->num_rows > 0 && $result) {

			$count = 0;
			$tableValue .= "<table border='1' cellpadding='5' cellspacing='5' style='font-size:12px;'>";
			$tableValue .= "<tr>
		       		<td>Srl. No.</td>	
					<td>Date</td>
					<td>Message</td>
				</tr>";
			foreach ($result as $key => $value) {
				$count++;
				$tableValue .= "<tr>";
				$tableValue .= "<td>" . $count . "</td>";
				$tableValue .= "<td>" . $value['msg_date'] . "</td>";
				$tableValue .= "<td>" . $value['text_msg'] . "</td>";

				$tableValue .= "</tr>";
			}

			$tableValue .= "</table>";
		} else {
			$tableValue .= "Message not available";
		}

		echo $tableValue;
	}
}
