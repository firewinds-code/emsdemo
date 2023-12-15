<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
include_once(__dir__ . '/../Services/sendsms_API1.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
// Global variable used in Page Cycle

// Trigger Button-Save Click Event and Perform DB Action
//print_r($_SESSION);

$clean_user_loginid = cleanUserInput($_SESSION['__user_logid']);

$isset_loginid = isset($clean_user_loginid);

if ($isset_loginid and ($clean_user_loginid != 'CE03070003' and $clean_user_loginid != 'CE01145570' and $clean_user_loginid != 'CE12102224')) {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
}
$ans_arra = '0';
$clean_ed_search = cleanUserInput($_POST['btn_ED_Search']);
$isset_ed = isset($clean_ed_search);
if ($isset_ed) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$clean_ed_emp_name = cleanUserInput($_POST['ddl_ED_Emp_Name']);
		if (($clean_ed_emp_name) != "") {
			$EmployeeID = $clean_ed_emp_name;

			$query = "select secques,secans from employee_map where EmployeeID=?";
			$select = $conn->prepare($query);
			$select->bind_param("s", $EmployeeID);
			$select->execute();
			$res = $select->get_result();
			$ans_array = $res->fetch_row();
			if ($res->num_rows > 0 && $ans_array[0] != "" && $ans_array[1] != "") {
				$TEMPLATEID = '1707161726325602862';
				//$msg ="Hello, Your security Answer is '".$ans_array[0]['secans']."' for EmployeeID is ".$EmployeeID;
				$msg = "Hello, Your security Answer is '" . $ans_array[1] . "' for EmployeeID is '" . $EmployeeID . "' - Cogent E Services";
				/*$Priority="ndnd";
			$Smstype="normal";
			$url = "http://bhashsms.com/api/sendmsg.php";*/

				$mobile = '';

				/*$fields = array( 'user'=>"cogent hr", 'pass'=>"T!ger@321", 'sender'=>"COGENT",'phone'=>$mobNo,'text'=>$msg, 'priority'=>$Priority, 'stype'=>$Smstype);*/

				$sql = 'select mobile from contact_details where EmployeeID =? limit 1';
				$sel = $conn->prepare($sql);
				$sel->bind_param("s", $EmployeeID);
				$sel->execute();
				$res_contact = $sel->get_result();
				$rst_contact = $res_contact->fetch_row();
				// echo $rst_contact[0];
				if (!empty($rst_contact[0])) {
					$mobile = $rst_contact[0];
					// die;
					/*$fields = array( 'user'=>"cogent hr", 'pass'=>"T!ger@321", 'sender'=>"COGENT",'phone'=>$mobile,'text'=>$msg, 'priority'=>$Priority, 'stype'=>$Smstype);
				$postvars = '';
				foreach ($fields as $key=>$value) {
					$postvars .= $key . "=" . $value . "&";
				}
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch,CURLOPT_POST, 0);
				curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
				curl_setopt($ch,CURLOPT_TIMEOUT, 20);
				$response = curl_exec($ch);
				curl_close ($ch);*/

					$url = SMS_URL;
					$token = SMS_TOKEN;
					$credit = SMS_CREDIT;
					$sender = SMS_SENDER;
					$message = $msg;
					$number = $mobile;
					$sendsms = new sendsms($url, $token);
					$message_id = $sendsms->sendmessage($credit, $sender, $message, $number, $TEMPLATEID);
					$response = $message_id;

					$lbl_msg = ' SMS : ' . $response;
					//echo $lbl_msg;
				}
				//  $alert_msg = '<span class="text-success">Request to In-active for selected Employee is saved Successfully.</span>';
				echo "<script>$(function(){ toastr.success('Security Answer has been sent on Mobile Number for EmployeeID " . $EmployeeID . " .'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Security Question/ Answer is blank for EmployeeID " . $EmployeeID . " , Please contact Compliance Team .'); }); </script>";
			}
		}
	}
}
?>
<script>
	$(document).ready(function() {
		$('#txt_ED_joindate_to').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
		$('#txt_ED_joindate_from').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});

	});
</script>

<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Employee Security Answer</span>
	<div class="pim-container">
		<div class="form-div">
			<h4>Search for Security Answer</h4>
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="">
					<div class="input-field col s6 m6 8">
						<select name="searchBy" id="searchBy" class="input-field col s12 m12 l6" title="Select Search Option">
							<option value="By ID">Employee ID</option>

						</select>
						<label title="" for="searchBy" class="active-drop-down active">Search By</label>
					</div>
				</div>
				<div class=" byID">
					<!--<label for="ddl_ED_Emp_Name">Emp. ID :</label>-->
					<div class="input-field col s6 m6 8">
						<input type="text" id="ddl_ED_Emp_Name" name="ddl_ED_Emp_Name" maxlength="15" title="Enter Employee ID Must Start With CE and Not Less Then 10 Char">
						<label for="ddl_ED_Emp_Name"> Employee ID</label>
					</div>

				</div>



				<div class="input-field col s12 m12 right-align">
					<button type="submit" name="btn_ED_Search" title="Click Here To Get Search Result" id="btn_ED_Search" class="btn waves-effect waves-green">Search</button>

				</div>
			</div>
		</div>

	</div>
</div>

<script>
	$(document).ready(function() {
		$('#btn_ED_Search').click(function() {
			var validate = 0;
			var alert_msg = '';

			$('#ddl_ED_Emp_Name').removeClass('has-error');

			if ($('#searchBy').val() == 'By ID') {
				if ($('#ddl_ED_Emp_Name').val() == '') {
					$('#ddl_ED_Emp_Name').addClass('has-error');
					if ($('#spanMessage_empid').length == 0) {
						$('<span id="spanMessage_empid" class="help-block"></span>').insertAfter('#ddl_ED_Emp_Name');
					}

					$('#spanMessage_empid').html('Employee Id can not be Empty');
					validate = 1;
				}
			}
			if (validate == 1) {
				return false;
			}
		});
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>