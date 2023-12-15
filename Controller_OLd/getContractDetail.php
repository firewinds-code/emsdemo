<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$tablename = clean($_REQUEST['tablename']);
$user_logid = clean($_SESSION['__user_logid']);
if (isset($tablename) && $tablename != "") {
	$myDB = new MysqliDb();
	$para_array = $myDB->query("select parameters, Alias from ctrctpram");
	$array_lavel = array();
	foreach ($para_array as $paraval) {
		$array_lavel[$paraval['Alias']] = $paraval['parameters'];
	}
	$myDB = new MysqliDb();
	$sqlcmid = "call getCmidUsingAhVhId('" . $user_logid . "')";
	$resultcm = $myDB->query($sqlcmid);
	$cmid_array = array();
	foreach ($resultcm as $val) {
		if ($val['cm_id'] != "") {
			$cmid_array[] = $val['cm_id'];
		}
	}
	$id = clean($_REQUEST['id']);
	$tablename = clean($_REQUEST['tablename']);
	$cmid = clean($_REQUEST['cmid']);
	$data = '';

	$myDB = new MysqliDb();
	$sql = "select  ack_flg,oh_ack,vh_ack, ack_date  from ctrctdetails_master where table_name=?";
	$selectQ = $conn->prepare($sql);
	$selectQ->bind_param("s", $tablename);
	$selectQ->execute();
	$results = $selectQ->get_result();
	$para_array = $results->fetch_row();
	$ack_flg = '';
	$oh_ack = '';
	$vh_ack = '';
	if ($para_array[0] == 1) {
		$ack_flg = '1';
	}
	if ($para_array[1] == 1) {
		$oh_ack = '1';
	}
	if ($para_array[2] == 1) {
		$vh_ack = '1';
	}
	$sqlBankcQuery = "select b.Client_Info,a.* from " . $tablename . " a inner join (SELECT cm_id,concat(client_master.client_name,' | ',process,' | ',sub_process) as Client_info FROM ems.new_client_master inner join client_master on client_master.client_id = new_client_master.client_name  where cm_id=?) b on a.cm_id=b.cm_id ";

	// $myDB = new MysqliDb();
	$j = 1;
	$error = '';
	$select = $conn->prepare($sqlBankcQuery);
	$select->bind_param("i", $cmid);
	$select->execute();
	$comment_array = $select->get_result();
	// $comment_array = $myDB->query($sqlBankcQuery);
	// $error = $myDB->getLastError();
	if ($comment_array->num_rows > 0 && $comment_array) {
		foreach ($comment_array as $val_array) {
			foreach ($val_array as $key => $val) {
				if ($key != 'cm_id') {
					$FileType = strtolower(pathinfo($val, PATHINFO_EXTENSION));
					$level = '';
					if (array_key_exists($key, $array_lavel)) {
						$level = ucwords($array_lavel[$key]);
					} else {
						$level = ucwords($key);
					}

					//$FileType = strtolower(pathinfo($val,PATHINFO_EXTENSION));
					//if($file=='FILE')
					if ($FileType == "jpg" || $FileType == "png" || $FileType == "jpeg" || $FileType == "pdf") {
						$filePath = URL . "parafile/" . $val;
						if ($FileType == 'pdf') {
							$data .= '<div class="input-field col s6 m6"><a href="' . $filePath . '" target="_blank">Download File</a><label for="txt_edu_lvl_' . $j . '" class="active-drop-down active">' . $level . '</label></div>';
						} else {
							$data .= '<div class="input-field col s6 m6"><a  onclick="javascript:return Download(this);"  data="parafile/' . $val . '" >Download File</a><label for="txt_edu_lvl_' . $j . '" class="active-drop-down active">' . $level . '</label></div>';
						}

						//}
					} else {
						$data .= '<div class="input-field col s6 m6"><span style="readonly:readonly" id="txt_edu_lvl_' . $j . '">' . $val . '</span> <br><label for="txt_edu_lvl_' . $j . '" class="active-drop-down active">' . $level . '</label><br></div>';
					}

					$j++;
				}
			}
		}
		//print_r($data);
		$acknowledge = "";
		if (in_array($cmid, $cmid_array)) {
			$data .= '<div class="input-field col s12 m12 ackdiv">';

			if (clean($_SESSION["__status_vh"]) != '' && clean($_SESSION["__status_vh"]) == clean($_SESSION['__user_logid'])) {
				$acknowledge = '';
				if ($vh_ack == '1') {
					$acknowledge = "Acknowledged";
				}
			}

			if (clean($_SESSION["__status_ah"]) != '' && clean($_SESSION["__status_ah"]) == clean($_SESSION['__user_logid'])) {
				$acknowledge = '';
				if ($ack_flg == '1') {
					$acknowledge = "Acknowledged";
				}
			}
			if (clean($_SESSION["__status_oh"]) != '' && clean($_SESSION["__status_oh"]) == clean($_SESSION['__user_logid'])) {
				$acknowledge = '';
				if ($oh_ack == '1') {
					$acknowledge = "Acknowledged";
				}
			}
			if ($acknowledge == "Acknowledged") {
				$data .= '<button type="button"  class="btn waves-effect waves-green" style="margin-top: -8px;">Acknowledged</button>';
			} else {
				$data .= '<input type="checkbox" id="ack" name="ack" data="' . $id . '"  value="1"><label for="ack" class="active"><button type="button" name="btn_ack" id="btn_ack" style="margin-top: -8px;" data="' . $tablename . '" onclick="javascript:return ack1(' . $id . ');" class="btn waves-effect waves-green">Acknowledge</button></label>';
			}
		}
		$data .= '</div>';
		echo ($data);
		$Employee = clean($_SESSION['__user_logid']);
		$insert_Log = "INSERT INTO view_contract_log set  createdBy=?, createdOn='" . Date("Y-m-d h:i:s") . "', table_name=?, cm_id=?";
		$insert = $conn->prepare($insert_Log);
		$insert->bind_param("ssi", $Employee, $tablename, $cmid);
		$insert->execute();
		$result = $insert->get_result();
		// $myDB = new MysqliDb();
		// $myDB->query($insert_Log);
	} else {
		echo ("table not fouud");
	}
}
