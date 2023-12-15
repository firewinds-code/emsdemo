<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$empid = clean(base64_decode($_REQUEST['empid']));
// $sql = 'SELECT personal_details.EmployeeID,personal_details.EmployeeName,
// personal_details.BloodGroup,personal_details.img,personal_details.location , concat("XXXXXX",right(altmobile,4)) as \'mobile\' ,pd.EmployeeName as \'ah\' 
// from personal_details 
// left outer join contact_details on  personal_details.EmployeeID=contact_details.EmployeeID
// left outer join whole_dump_emp_data on  whole_dump_emp_data.EmployeeID = personal_details.EmployeeID
// left outer join personal_details pd on  pd.EmployeeID = whole_dump_emp_data.account_head
// where personal_details.EmployeeID="' . $_REQUEST['empid'] . '"';
$sql = 'SELECT personal_details.EmployeeID,personal_details.EmployeeName,
personal_details.BloodGroup,personal_details.img,personal_details.location , concat("XXXXXX",right(altmobile,4)) as \'mobile\' ,pd.EmployeeName as \'ah\' 
from personal_details 
left outer join contact_details on  personal_details.EmployeeID=contact_details.EmployeeID
left outer join whole_dump_emp_data on  whole_dump_emp_data.EmployeeID = personal_details.EmployeeID
left outer join personal_details pd on  pd.EmployeeID = whole_dump_emp_data.account_head
where personal_details.EmployeeID=?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $empid);
$stmt->execute();
$result_all = $stmt->get_result();
$rowAllResult = $result_all->fetch_row();

$img = '';
// $result_all = $myDB->query($sql);
// $mysql_error = $myDB->getLastError();
if ($result_all->num_rows > 0 && $result_all) {
	echo "<div class='PopUp zoomInDown animated'>";
	echo "<div class='input-field col s9 m9'>";
	echo "<table class='table table-bordered table-responsive' id='emp_table'>";
	foreach ($result_all as $key => $value) {
		echo '<table class="data" id="empinfo_tab"><tr>';
		echo "<td>Name  </td><td><b>" . $employeeName = clean($rowAllResult[1]);
		echo '</b></td></tr><tr>';
		//echo "<td>Date Of Birth  </td><td><b>".$result_all[0]['DOB'].'</b></td>';echo'</tr><tr>';
		//echo "<td>Father Name  </td><td><b>".$result_all[0]['FatherName'].'</b></td>';echo'</tr><tr>';
		//echo "<td>Mother Name </td><td><b>".$result_all[0]['MotherName'].'</b></td>';echo'</tr><tr>';
		//echo "<td>Gender  </td><td><b>".$result_all[0]['Gender'].'</b></td>';echo'</tr><tr>';
		echo "<td>Blood Group  </td><td><b>" . clean($rowAllResult[2]) . '</b></td>';
		echo '</tr><tr>';
		echo "<td>Mobile No.</td><td><b>" . clean($rowAllResult[5]) . '</b></td>';
		echo '</tr><tr>';
		//echo "<td>Alternate Mobile </td><td><b>".$result_all[0]['altmobile'].'</b></td>';echo'</tr><tr>';
		//echo "<td>Emergency Contact  </td><td><b>".$result_all[0]['em_contact'].'</b></td>';echo'</tr><tr>';
		//echo "<td>Relation  </td><td><b>".$result_all[0]['em_relation'];echo '</b></td>';
		echo "<td>Account Head</td><td><b>" . clean($rowAllResult[6]) . '</b></td>';
		echo '</tr><tr>';
		echo '</tr></table></div>';
		$img = $value['img'];
		$dir_location = "";

		if (is_numeric(clean($rowAllResult[4])) == "1" || is_numeric(clean($rowAllResult[4])) == "2") {
			$dir_location = "../Images/";
		} else if (is_numeric(clean($rowAllResult[4])) == "3") {
			$dir_location = "../Meerut/Images/";
		} else if (is_numeric(clean($rowAllResult[4])) == "4") {
			$dir_location = "../Bareilly/Images/";
		} else if (is_numeric(clean($rowAllResult[4])) == "5") {
			$dir_location = "../Vadodara/Images/";
		} else if (is_numeric(clean($rowAllResult[4])) == "6") {
			$dir_location = "../Manglore/Images/";
		} else if (is_numeric(clean($rowAllResult[4])) == "7") {
			$dir_location = "../Bangalore/Images/";
		} else if (is_numeric(clean($rowAllResult[4])) == "8") {
			$dir_location = "../Banglore_Fk/Images/";
		}

		if (file_exists($dir_location . $value['img']) && $value['img'] != '') {
			$src = $dir_location . $value['img'];
		} else {
			$src = "../Style/images/agent-icon.png";
		}

		/*if($result_all[0]['location']=="1" || $result_all[0]['location']=="2")
					{
						if(file_exists ("../Images/".$value['img'])&&$value['img']!='')
						{
							$src="../Images/".$value['img'];
						}
						else
						{
							$src="../Style/images/agent-icon.png";
						}
					}
					else if($result_all[0]['location']=="3")
					{
						if(file_exists ("../Meerut/Images/".$value['img'])&&$value['img']!='')
						{
							$src="../Meerut/Images/".$value['img'];
						}
						else
						{
							$src="../Style/images/agent-icon.png";
						}
					}*/

		echo "
					 <div class='input-field col s3 m3'>
					 <center><img style='height: 100px;position: relative;width: 100px;padding: 5px;background: #FFFFFF;' alt='No  Image' id='info_img' src='" . $src . "'/></center>
					 <div>
					 <h4 style='text-align: center;line-height: 100%;padding: 7px;color: #42a7b7;'>" . clean($rowAllResult[0]) . "</h4>
					 </div></div>";
		echo '<div id="imgBtn_close" class="col s12 m12 right-align"><button type="button" style="margin-bottom: 11px;" class="btn waves-effect modal-action modal-close waves-red close-btn imgBtn_close" imgBtn>Close</button></center>';
		echo '</div>';
	}
} else {
	echo "UnSuccessfull";
}
