<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$mid = cleanUserInput($_GET['mid']);
$locid = cleanUserInput($_GET['locid']);
if (isset($mid)  and $mid != "" and isset($locid)  and $locid != "") {

	if ($mid != "") {

		$sqlConnect2 = 'select  a.emailID,a.cc_email,a.location,b.module_name,c.location from  manage_module_email a inner join module_manager  b on  b.ID=a.moduleID left outer join location_master c on a.location=c.id where a.moduleID=? and a.location=?';

		//echo "<br>";
		$email = array();
		$ccemail = array();
		$selectQ = $conn->prepare($sqlConnect2);
		$selectQ->bind_param("ii", $mid, $locid);
		$selectQ->execute();
		$result2 = $selectQ->get_result();

		// $result2 = $myDB->query($sqlConnect2);
		foreach ($result2 as $key => $value) {
			if ($value['emailID'] != "") {
				$email[] = $value['emailID'];
			} else {
				$email[] = "";
			}
			if ($value['cc_email'] != "") {
				$ccemail[] = $value['cc_email'];
			} else {
				$ccemail[] = "";
			}
			$mname = $value['module_name'];
			$lname = $value['location'];
		}
		$sqlBy2 = 'select email_address,ID from add_email_address  ';
		$resultBy2 = $myDB->query($sqlBy2);
		if ($resultBy2) {
			$checkbox_check = "";
			foreach ($resultBy2 as $key => $value2) {
				$checkedId = "";
				//echo $value2['email_address'];
				$emailId = $value2['ID'];
				if (in_array($emailId, $email)) {
					$checkedId = "checked";
				}
				$checkbox_check .= '<div class="col s4 m4 l4">
						<input type="checkbox"  name="email_address[]" id="' . $value2['ID'] . '" value="' . $value2['ID'] . '" ' . $checkedId . ' >
						<label for="' . $value2["ID"] . '">' . $value2['email_address'] . '</label></div>';
			}
		}
		$resultBy3 = $myDB->query($sqlBy2);
		$ecc_email_check = "";
		if ($resultBy2) {
			foreach ($resultBy3 as $key => $value3) {
				$ccchecked = "";
				$emailId3 = $value3['ID'];
				if (in_array($emailId3, $ccemail)) {
					$ccchecked = "checked";
				}
				$ecc_email_check .= '<div class="col s4 m4 l4">
						<input type="checkbox"  name="cc_email[]" id="cc' . $value3['ID'] . '" value="' . $value3['ID'] . '" ' . $ccchecked . ' >
						<label for="cc' . $value3["ID"] . '">' . $value3['email_address'] . '</label></div>';
			}
		}

		echo  $htmldata = '<div class="input-field col s12 m12">
						 <select name="module_id" id="module_id">
						 <option  value="' . $mid . '">' . $mname . '</option>
						 </select>
						 <label for="module_id" class="active-drop-down active">Select Module</label>
	                   </div> 
	                   
	                   <div class="input-field col s12 m12">
						 <select name="location_id" id="location_id">
						 <option  value="' . $locid . '">' . $lname . '</option>
						 </select>
						 <label for="location_id" class="active-drop-down active">Select Location</label>
	                   </div> 
	                   
					 <h4>To Email Address</h4>
					 <div class="input-field col s12 m12">' . $checkbox_check . '</div> 
					 
					 <h4>CC Email</h4>
					 <div class="input-field col s12 m12">' . $ecc_email_check . '</div>';
		exit;
	}
}
