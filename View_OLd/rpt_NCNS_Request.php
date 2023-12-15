<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
// require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
// require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$alert_msg = '';
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$email_table = $erpr = $table = '';
$email_empliyeeID  = array();
$btn_inactive = isset($_POST['btn_inactive']);
if ($btn_inactive) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_check = $_POST['txt_check'];
		foreach ($txt_check as $Employee) {
			$empid = cleanUserInput($Employee);
			$remark = cleanUserInput($_POST['txt_remark_' . $Employee]);
			$myDB = new MysqliDb();
			$rstl = $myDB->rawQuery('call manage_ncns_hr("' . $empid . '","' . $remark . '","' . $user_logid . '","1")');
			$mysql_error = $myDB->getLastError();
			$rowCount = $myDB->count;
			if ($rowCount > 0) {
				$_empid = $empid;
				$_rsnleave = 'NCNS Request (ABSC)';
				$_dol = date('Y-m-d', time());
				$_hrcmt = 'NCNS Request (ABSC)';
				$_opscmt = 'NCNS Request (ABSC)';
				$createBy = clean($_SESSION['__user_logid']);
				$disposition = 'ABSC';

				$Insert = 'call exit_employee("' . $_empid . '","' . $_dol . '","' . $_rsnleave . '","' . $_hrcmt . '","' . $_opscmt . '","' . $createBy . '","' . $disposition . '")';
				$myDB = new MysqliDb();
				$result = $myDB->rawQuery($Insert);
				$mysql_error = $myDB->getLastError();
				$rowCount = $myDB->count;
				if ($rowCount > 0) {
					// $myDB = new MysqliDb();
					$sql = 'INSERT INTO alert_details(EmployeeID,alert_start,alert_end,type,createdon,createdby)VALUES(?,"' . date('Y-m-d', time()) . '","' . date('Y-m-d', strtotime('+3 days')) . '","NCNS In-active","' . date('Y-m-d H:i:s', time()) . '",?)';
					$ins = $conn->prepare($sql);
					$ins->bind_param("ss", $_empid, $createBy);
					$ins->execute();
					$result2 = $ins->get_result();

					// $erpr = $myDB->getLastError();
					// $rowCount = $myDB->count;
					// $myDB = new MysqliDb();
					$qury = 'select whole_dump_emp_data.EmployeeID,whole_dump_emp_data.EmployeeName,whole_dump_emp_data.clientname,whole_dump_emp_data.Process,whole_dump_emp_data.sub_process,whole_dump_emp_data.account_head,whole_dump_emp_data.designation,whole_dump_emp_data.DOJ,cd1.ofc_emailid as ah_mail,cd2.ofc_emailid as oh_mail,cd3.ofc_emailid as th_mail ,cd4.ofc_emailid as qh_mail from whole_dump_emp_data left outer join contact_details cd1 on cd1.EmployeeID = whole_dump_emp_data.account_head left outer join contact_details cd2 on cd2.EmployeeID = whole_dump_emp_data.oh left outer join contact_details cd3 on cd3.EmployeeID = whole_dump_emp_data.th left outer join contact_details cd4 on cd4.EmployeeID = whole_dump_emp_data.qh  where whole_dump_emp_data.EmployeeID  =?';
					$selectQ = $conn->prepare($qury);
					$selectQ->bind_param("s", $_empid);
					$selectQ->execute();
					$result = $selectQ->get_result();
					$result_whole = $result->fetch_row();

					// $erpr = $myDB->getLastError();
					// $rowCount = $myDB->count;
					if ($result->num_rows > 0) {

						$email_empliyeeID[] = $result_whole[8];
						$email_empliyeeID[] = $result_whole[9];
						$email_empliyeeID[] = $result_whole[10];
						$email_empliyeeID[] = $result_whole[11];
						$email_table .= '<td style="padding:5px;">' . $result_whole[0] . '</td>';
						$email_table .= '<td  style="padding:5px;">' . $result_whole[1] . '</td>';
						$email_table .= '<td  style="padding:5px;"><b>NCNS Request (ABSC)</b></td>';
						$email_table .= '<td style="padding:5px;">' . $result_whole[6] . '</td>';
						$email_table .= '<td style="padding:5px;">' . $result_whole[2] . '</td>';
						$email_table .= '<td style="padding:5px;">' . $result_whole[3] . '</td>';
						$email_table .= '<td style="padding:5px;">' . $result_whole[4] . '</td>';
						$email_table .= '<td style="padding:5px;">' . $result_whole[5] . '</td>';
						$email_table .= '</tr>';
					}
					/*if($rowCount>0)
					{
						$myDB=new MysqliDb();
						$result1 = $myDB->rawQuery("UPDATE employee_map SET emp_status='InActive'  WHERE EmployeeID = '".$_empid."'");
						$rowCount1=mysql_affected_rows();
						if($rowCount1 > 0)
						{
							$alert_msg='<span class="text-success"><b>Message :</b> Employee InActive Successfully ...</span>';	
							
						}
						else
						{
							$alert_msg='<span class="text-warning"><b>Message :</b>  Data not updated or Employee already InActive ...</span>';
						}
						
					}*/
					/*else
					{
						$alert_msg='<span class="text-warning"><b>Message :</b>  Data not updated ...</span>';
					}*/
				}

				$msg = "You are not reporting to office since long time without any information to us, So you are In-active from Cogent on " . date('d/m/Y', time()) . ". To ensure that advised you to contact HR department in person ASAP";


				// $myDB = new MysqliDb();

				$rstcontact = 'select mobile,altmobile from contact_details where EmployeeID = ? limit 1';
				$selQ = $conn->prepare($rstcontact);
				$selQ->bind_param("s", $empid);
				$selQ->execute();
				$resu = $selQ->get_result();
				$rst_contact = $resu->fetch_row();
				// $erpr = $myDB->getLastError();
				// $rowCount = $myDB->count;
				if (!empty($rst_contact[0])) {
					// Msg API

					/*$fields = array( 'user'=>"cogent hr", 'pass'=>"T!ger@321", 'sender'=>"COGENT",'phone'=>$rst_contact[0]['contact_details']['mobile'],'text'=>$msg, 'priority'=>$Priority, 'stype'=>$Smstype);
				 	$postvars = '';
				 	foreach($fields as $key=>$value)
				 	{
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



					/*$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_HEADER, false);
					$data = curl_exec($curl);
					curl_close($curl);*/
					//$lbl_msg = ' SMS : '.$response;
				}

				//   $alert_msg = '<span class="text-success">Request to In-active for selected Employee is complete Successfully.</span>';
				echo "<script>$(function(){ toastr.success('Request to In-active for selected Employee is complete Successfully.'); }); </script>";
			} else {
				//$alert_msg = '<span class="text-danger">Request to In-active for selected Employee is not completed '.$erpr.'</span>';
				echo "<script>$(function(){ toastr.error('Request to In-active for selected Employee is not completed .'); }); </script>";
			}
		}

		if (!empty($email_empliyeeID) && count($email_empliyeeID) > 0 && !empty($email_table)) {
			// $myDB = new MysqliDb();
			$pagename = 'rpt_NCNS_Request';
			$location = clean($_SESSION["__location"]);
			$select_email = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename=? and b.location =?";
			$selectQ = $conn->prepare($select_email);
			$selectQ->bind_param("si", $pagename, $location);
			$selectQ->execute();
			$select_email_array = $selectQ->get_result();

			// $erpr = $myDB->getLastError();
			// $rowCount = $myDB->count;
			$array_to_mail = array_unique($email_empliyeeID);
			// $mail = new PHPMailer;
			// $mail->isSMTP();
			// $mail->Host = 'mail.cogenteservices.in';  // Specify main and backup SMTP servers
			// $mail->SMTPAuth = true;                               // Enable SMTP authentication
			// $mail->Host = EMAIL_HOST;
			// $mail->SMTPAuth = EMAIL_AUTH;
			// $mail->Username = EMAIL_USER;
			// $mail->Password = EMAIL_PASS;
			// $mail->SMTPSecure = EMAIL_SMTPSecure;
			// $mail->Port = EMAIL_PORT;
			// $mail->setFrom(EMAIL_FROM, 'EMS:Cogent Alert System');
			$validate = 0;
			//$mail->AddAddress($_POST['emailid']);
			foreach ($array_to_mail as $email) {
				if ($email != '') {
					$mail->AddAddress($email);
				}
			}
			if ($rowCount > 0) {
				foreach ($select_email_array as $key => $email_array) {
					$email_address = $email_array['email_address'];
					if ($email_address != "") {
						$mail->AddAddress($email_address);
					}
					$cc_email = $email_array['ccemail'];
					if ($cc_email != "") {
						$mail->addCC($cc_email);
					}
				}
			}

			$EMS_CenterName = "";
			//$mail->Subject = 'Happy to help '.EMS_CenterName.', Reference #'.$refID_id;
			$loc = clean($_SESSION["__location"]);
			if ($loc == "1") {
				$EMS_CenterName = "Noida";
			} else if ($loc == "2") {
				$EMS_CenterName = "Mumbai";
			} else if ($loc == "3") {
				$EMS_CenterName = "Meerut";
			} else if ($loc == "4") {
				$EMS_CenterName = "Bareilly";
			} else if ($loc == "5") {
				$EMS_CenterName = "Vadodara";
			} else if ($loc == "6") {
				$EMS_CenterName = "Mangalore";
			} else if ($loc == "7") {
				$EMS_CenterName = "Bangalore";
			} else if ($loc == "8") {
				$EMS_CenterName = "Nashik";
			} else if ($loc == "9") {
				$EMS_CenterName = "Anantapur";
			}

			$mail->Subject = 'EMS ' . $EMS_CenterName . ':  Alert for NCNS Request Accept [' . date('d M,Y', time()) . ']';

			$mail->isHTML(true);
			$pwd_ = '<span>Dear All,<br/><br/><span><b>Please find below the concern Employee in table are Inactive from the system</b></span>.<br /><br/><table border="1"><thead><tr style="font-weight: bold;border-bottom:2px solid black;"><td style="padding:5px;">EmployeeID</td><td style="padding:5px;">Employee Name</td><td style="padding:5px;">Alert For</td><td style="padding:5px;">Designation</td><td style="padding:5px;">Client</td><td style="padding:5px;">Process</td><td style="padding:5px;">Sub-process</td><td style="padding:5px;">Account Head ID</td></tr></thead><tbody>' . $email_table . '</tbody></table><br/><br/> Thank You</b>.<br/>Regard,<br/>EMS - Alert <br />';
			$mail->Body = $pwd_;
			$mymsg = "";
			if (!$mail->send()) {
				$mymsg .= '.Mailer Error:' . $mail->ErrorInfo;
			} else {

				$mymsg .= '.Mail Send successfully.';
			}
		}
	}
}
$btn_calcle = isset($_POST['btn_calcle']);
if ($btn_calcle) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_check = $_POST['txt_check'];
		foreach ($txt_check as $Employee) {
			$empid = cleanUserInput($Employee);
			$remark = cleanUserInput($_POST['txt_remark_' . $Employee]);
			$userid = clean($_SESSION['__user_logid']);
			$myDB = new MysqliDb();
			$rstl = $myDB->rawQuery('call manage_ncns_hr("' . $empid . '","' . $remark . '","' . $userid  . '","2")');
			$my_error = $myDB->getLastError();
			$rowCount = $myDB->count;
			if ($rowCount > 0) {
				// $alert_msg = '<span class="text-success">Request to In-active for selected Employee is cancel Successfully.</span>';
				echo "<script>$(function(){ toastr.success('Request to In-active for selected Employee is cancel Successfully'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Request to In-active for selected Employee is not completed . " . $my_error . "'); }); </script>";
			}
		}
	}
}
?>
<script>
	$(function() {
		/*$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});*/


		// DataTable
		var table1 = $('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [


				{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}, 'pageLength'

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false

			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">NCNS : Requests </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>NCNS : Requests </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12 right-align ">

					<button type="submit" name="btn_inactive" id="btn_inactive" value="In-Active" class="btn waves-effect waves-green hidden">In-Active</button>
					<button type="submit" name="btn_calcle" id="btn_calcle" value="Cancel Request" class="btn waves-effect waves-red close-btn hidden">Cancel Request</button>
				</div>


				<?php
				//$chk_task=$myDB->rawQuery('select * from ncns_cases left outer join whole_details_peremp on whole_details_peremp.EmployeeID = ncns_cases.EmployeeID left outer join personal_details on personal_details.EmployeeID = ReportTo where ncns_cases.status = 0');
				$loc = clean($_SESSION["__location"]);
				if ($loc == "1") {
					$chktask = 'select nc.EmployeeID,wh.EmployeeName,nc.remark,wh.DOJ,wh.designation,wh.clientname,wh.Process,wh.sub_process,pd.EmployeeName as PDEmployeeName from ncns_cases nc left outer join whole_details_peremp wh on wh.EmployeeID = nc.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo where nc.status = 0 and pd.location in (1,2)';
					$selectQ = $conn->prepare($chktask);
					$selectQ->execute();
					$chk_task = $selectQ->get_result();
				} else {
					$chktask = 'select nc.EmployeeID,wh.EmployeeName,nc.remark,wh.DOJ,wh.designation,wh.clientname,wh.Process,wh.sub_process,pd.EmployeeName as PDEmployeeName from ncns_cases nc left outer join whole_details_peremp wh on wh.EmployeeID = nc.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo where nc.status = 0 and pd.location in (?)';
					$selectQ = $conn->prepare($chktask);
					$selectQ->bind_param("s", $loc);
					$selectQ->execute();
					$chk_task = $selectQ->get_result();
				}

				// $my_error = $myDB->getLastError();
				// $rowCount = $myDB->count;
				if ($chk_task->num_rows > 0) {
					$table .= '<div class="had-container pull-left row card dataTableInline"  id="tbl_div" ><div class=""  ><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
				<thead><tr>';
					$table .= '<th>EmployeeID</th>';
					$table .= '<th>EmployeeName</th>';
					$table .= '<th>HR Remark</th>';
					$table .= '<th>Remark</th>';
					$table .= '<th>DOJ</th>';
					$table .= '<th>Designation</th>';
					$table .= '<th>Client</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Sub Process</th>';
					$table .= '<th>Supervisor</th>';
					$table .= '</tr></thead><tbody>';

					foreach ($chk_task as $key => $value) {

						$table .= '<tr><td><input type="checkbox" name="txt_check[]" id="txt_check_' . $value['EmployeeID'] . '" value ="' . $value['EmployeeID'] . '"  onclick="javascript:return checkbox_click();"/><label for="txt_check_' . $value['EmployeeID'] . '"><span></span>' . $value['EmployeeID'] . '</label> </td>';
						$table .= '<td>' . $value['EmployeeName'] . '</td>';
						$table .= '<td ><textarea id="txt_remark_' . $value['EmployeeID'] . '" name="txt_remark_' . $value['EmployeeID'] . '" class="materialize-textarea materialize-textarea-size "></textarea></td>';
						$table .= '<td>' . $value['remark'] . '</td>';
						$table .= '<td>' . $value['DOJ'] . '</td>';
						$table .= '<td>' . $value['designation'] . '</td>';
						$table .= '<td>' . $value['clientname'] . '</td>';
						$table .= '<td>' . $value['Process'] . '</td>';
						$table .= '<td>' . $value['sub_process'] . '</td>';
						$table .= '<td>' . $value['PDEmployeeName'] . '</td>';
						$table .= '</tr>';
					}
					$table .= '</tbody></table></div></div>';
					echo $table;
				} else {

					echo "<script>$(function(){ toastr.error('No Data Found  ..'); }); </script>";
				}
				?>
			</div>


			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	$(function() {
		$('#btn_inactive').click(function() {
			var validate = 0;
			var alert_msg = '';

			$('input[type="checkbox"]:checked').each(function() {

				if ($('#txt_remark_' + $(this).val()).val().length < 50) {
					validate = 1;
					$('#txt_remark_' + $(this).val()).addClass('has-error');
					alert_msg += '<li>Remark should be greater than 50 character for Inactive request.</li>';
				}
			});

			if (validate == 1) {
				/*$('#alert_message').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		$('#alert_message').show().attr("class","SlideInRight animated");
	      		$('#alert_message').delay(5000).fadeOut("slow");
	      		
				return false;
				*/

				$(function() {
					toastr.error(alert_msg)
				});
				return false;
			}

		});


		$('#btn_calcle').click(function() {
			var validate = 0;
			var alert_msg = '';

			$('input[type="checkbox"]:checked').each(function() {

				if ($('#txt_remark_' + $(this).val()).val().length < 50) {
					validate = 1;
					$('#txt_remark_' + $(this).val()).addClass('has-error');
					alert_msg += '<li>Remark should be greater than 50 character for Cancel request.</li>';
				}
			});

			if (validate == 1) {
				/*$('#alert_message').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		$('#alert_message').show().attr("class","SlideInRight animated");
	      		$('#alert_message').delay(5000).fadeOut("slow");
	      		
				return false;*/

				$(function() {
					toastr.error(alert_msg)
				});
				return false;

			}

		});


	});

	function checkbox_click() {
		$('input[type="checkbox"]:checked').each(function() {
			/*alert($(this).val());*/
		});

		if ($('input[type="checkbox"]:checked').length > 0) {
			$('#btn_inactive').removeClass('hidden');
			$('#btn_calcle').removeClass('hidden');
		} else {
			$('#btn_inactive').addClass('hidden');
			$('#btn_calcle').addClass('hidden');
		}
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>