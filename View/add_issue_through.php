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

require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
date_default_timezone_set('Asia/Kolkata');
$value = $counEmployee = $countProcess = $countClient = 0;

$mymsg = '';
$EmployeeID = '';
$emailid = '';
$mobNo = "";
$loc = "";
if (isset($_REQUEST['ID'])) {
	$EmployeeID = $_REQUEST['ID'];
	$mobNo = $_REQUEST['mob'];
} else if (isset($_POST['EmployeeID'])) {
	$EmployeeID = $_POST['EmployeeID'];
	$mobNo = $_POST['mobno'];
}
$myDB = new MysqliDb();
$result = $myDB->rawQuery("select location from personal_details where employeeid='" . $EmployeeID . "'");
$loc = $result[0]['location'];


if (isset($_POST['btnSave'])) {
	if ($loc != '') {
		$contact_status = $_POST['contact_status'];
		$issue = $_POST['subb_issue'];
		$belongsto = $_POST['queryto'];
		$issue1 = $_POST['querysub'];
		$handler = $_POST['handler'];
		$remark = $_POST['remark'];
		$location = $_POST['loc'];
		$createdby = $EmployeeID;
		$myDB = new MysqliDb();
		$qry = 'call add_issueticket_for_ivr("' . $createdby . '","' . $belongsto . '","' . $issue1 . '","' . $handler . '","' . $remark . '","IVR","' . $contact_status . '","' . $issue . '");';
		$result = $myDB->rawQuery($qry);

		echo $myDB->getLastError();
		$rowCount = $myDB->count;
		if ($rowCount > 0) {

			$myDB = new MysqliDb();
			$gender_f = $myDB->rawQuery("call getGender('" . $createdby . "')");
			$gender_m = $gender_f[0]['Gender'];
			if (strtoupper($gender_m) == 'FEMALE') {
				$gender_last = 'Mrs';
			} else {
				$gender_last = 'Mr';
			}
			if (!empty($_POST['emailid'])) {
				$myDB = new MysqliDb();
				$pagename = 'add_issue_through';

				$select_email_array = $myDB->rawQuery("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='" . $pagename . "' and b.location ='" . $location . "'");
				$mysql_error = $myDB->getLastError();
				$rowCount = $myDB->count;
				$emailid = $_POST['emailid'];
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->Host = EMAIL_HOST;
				$mail->SMTPAuth = EMAIL_AUTH;
				$mail->Username = EMAIL_USER;
				$mail->Password = EMAIL_PASS;
				$mail->SMTPSecure = EMAIL_SMTPSecure;
				$mail->Port = EMAIL_PORT;
				$mail->setFrom(EMAIL_FROM,  'EMS:Cogent Grievance System');
				//$mail->AddAddress('md.masood@cogenteservices.com');
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
				$myDB = new MysqliDb();
				$refID = $myDB->rawQuery("select max(id) as id from issue_tracker  where requestby ='" . $createdby . "' and cast(request_date as date)= curdate() limit 1;");
				$refID_id = $refID[0]['id'];

				if ($location == "1") {
					$EMS_CenterName = "Noida";
				} else if ($location == "2") {
					$EMS_CenterName = "Mumbai";
				} else if ($location == "3") {
					$EMS_CenterName = "Meerut";
				} else if ($location == "4") {
					$EMS_CenterName = "Bareilly";
				} else if ($location == "5") {
					$EMS_CenterName = "Vadodara";
				} else if ($location == "6") {
					$EMS_CenterName = "Mangalore";
				} else if ($location == "7") {
					$EMS_CenterName = "Bangalore";
				} else if ($location == "8") {
					$EMS_CenterName = "Nashik";
				} else if ($location == "9") {
					$EMS_CenterName = "Anantapur";
				} else if ($location == "10") {
					$EMS_CenterName = "Gurgaon";
				} else if ($location == "11") {
					$EMS_CenterName = "Hyderabad";
				}

				$mail->Subject = 'Happy to help ' . $EMS_CenterName . ', Reference #' . $refID_id;
				$mail->isHTML(true);
				/*$pwd_='<span>Dear '.$_SESSION['__user_Name'].',<br/><br/><span><b>Your request for '.$issue.' issue Submited by Cogent Grievance System </b></span>.<br/> We will try our best to resolve it as soon as possible.<br/> Thank You</b>.<br/><br/><br/>Regard,<br/>Cogent E-Services Pvt.Ltd.</span><br /><div>You have received this mail from registered service by Cogent E Service Pvt. Ltd. This is a system-generated e-mail, please don\'t reply to this message. The message sent in this mail has been posted by the <b> EMS :: Happy to Help Service</b>. Cogent E Service Pvt. Ltd. has taken all reasonable steps to ensure that the information in this mailer is authentic. Please do not reply or revert back on this mail. Because it shall not have any responsibility in this regard. We recommend that you visit to your EMS tag <b>Happy to Help</b> for further information.</div>';*/
				$myDB = new MysqliDb();
				$info_emp = $myDB->rawQuery('call get_info_for_Issue_tracker("' . $createdby . '")');

				$mysqlError = $myDB->getLastError();
				$pwd_ = '<span>Dear Sir,<br/><br/><span><b>Please find below the concern raised in happy to help.</b></span>.<br /><br/> <b>Concern Subject: ' . $issue . '</b>.<br /><br /><b>Concern:</b> ' . $remark . '.<br/><br/><br/> Thank You</b>.<br/>Regard,<br/>' . strtoupper($_POST['EmployeeName']) . '(<b>&nbsp;' . $createdby . '&nbsp;</b>)<br/><b>Designation  &nbsp;:&nbsp;</b>' . strtoupper($info_emp[0]['Designation']) . '<br/><b>Process &nbsp;:&nbsp;</b>' . $info_emp[0]['Process'] . '&nbsp;(&nbsp;' . $info_emp[0]['sub_process'] . '&nbsp;)<br /><b>Account Head &nbsp;:&nbsp;</b>' . $info_emp[0]['AccountHead'] . '<br /><b>Report To &nbsp;:&nbsp;</b>' . $info_emp[0]['ReportTo'] . '<br />';

				$mail->Body = $pwd_;

				//print_r($mail);
				if (!$mail->send()) {
					$lbl_msg = 'Mailer Error:' . $mail->ErrorInfo;
				} else {

					$lbl_msg = 'Mail Send successfully.';
				}
				if ($mobNo != '') {

					$msg = "Your request for $issue Submited.We will try our best to resolve it as soon as possible. Thank You " . $gender_last . ". " . $_POST['EmployeeName'];

					/*$Priority="ndnd";
			 	$Smstype="normal";

			 	$url = "http://bhashsms.com/api/sendmsg.php"; */
					/*$fields = array( 'user'=>"cogent hr", 'pass'=>"T!ger@321", 'sender'=>"COGENT",'phone'=>$mobNo,'text'=>$msg, 'priority'=>$Priority, 'stype'=>$Smstype);*/
					/*$fields = array( 'user'=>"cogent hr", 'pass'=>"T!ger@321", 'sender'=>"COGENT",'phone'=>'9891886100','text'=>$msg, 'priority'=>$Priority, 'stype'=>$Smstype);
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
			 	curl_close ($ch);                                                                                                                        $ResultSMS=$response;*/

					/*$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HEADER, false);
				$data = curl_exec($curl);
				curl_close($curl);*/
					$TEMPLATEID = '1707161725833777455';
					$url = SMS_URL;
					$token = SMS_TOKEN;
					$credit = SMS_CREDIT;
					$sender = SMS_SENDER;
					$message = $msg;
					$number = $mobNo;
					$sendsms = new sendsms($url, $token);
					$message_id = $sendsms->sendmessage($credit, $sender, $message, $number, $TEMPLATEID);
					$response = $message_id;
					$ResultSMS = $response;

					$lbl_msg = $lbl_msg . ' and SMS : ' . $ResultSMS;
				}
			}
			//$mymsg="<span class='text-success'><b>Issuee Request Submited For ".$gender_last.". ".$_POST['EmployeeName']."</b> and ".$lbl_msg;
			echo "<script>$(function(){ toastr.success('Issuee Request Submited For " . $gender_last . " . " . $_POST['EmployeeName'] . " and " . $lbl_msg . " '); }); </script>";
		} else {
			//$mymsg='<div class="alert alert-danger"><b>Query Not Submited<b></div>';
			echo "<script>$(function(){ toastr.error('Query Not Submited'); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Employee Location is Missing'); }); </script>";
	}
}
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Welcome To Cogent </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Welcome To Cogent <span style="color: #03A60F;text-shadow: 1px 1px 1px #FFFFFF,1px 1px 1px #012B11,1px 1px 1px #012B11,1px 1px 1px #012B11;font-weight: bold;"> Happy to Help <img alt="Img" src="../Style/images/hth.jpg" height="35px;" /> </span> </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div class="input-field col s6 m6 ">
					<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />
					<input type="hidden" name="mobno" id="mobno" value="<?php echo $mobNo; ?>" />
					<input type="hidden" name="loc" id="loc" value="<?php echo $loc; ?>" />
				</div>


				<div>
					<?php

					$myDB = new MysqliDb();
					//echo 'call get_AllEmployee_byBesic("'.$EmployeeID.'")';
					$chk_task = $myDB->rawQuery('call get_AllEmployee_byBesic("' . $EmployeeID . '")');
					$my_error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if ($rowCount > 0 && $chk_task) {
						//<!--$table='<table id="myTable" class="view_table table table-bordered">-->

						$table = '<table id="myTable" class="data dataTable no-footer row-border view_table " cellspacing="0" width="100%"><thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>EmployeeID</td><td class="cls_right">' . $value['EmployeeID'] . '</td></tr>';
							$table .= '<tr><td>EmployeeName</td><td class="cls_right">' . $value['EmployeeName'] . '<input type="hidden" name="EmployeeName" id="EmployeeName" value="' . $value['EmployeeName'] . '" /></td></tr>';

							$table .= '<tr><td>Mobile No</td><td class="cls_right">' . $value['mobile'] . '</td></tr>';
							$table .= '<tr><td>Alt Mobile No</td><td class="cls_right">' . $value['altmobile'] . '</td></tr>';
							$table .= '<tr><td>EmailID</td><td class="cls_right">' . $value['emailid'] . '<input type="hidden" name="emailid" id="emailid" value="' . $value['emailid'] . '" /></td></tr>';	/*						
							$table .='<tr><td>Date Of Birth</td><td class="cls_right">'.$value[0]['DOB'].'</td></tr>';		
							$table .='<tr><td>Date Of Joining</td><td class="cls_right">'.$value[0]['DOJ'].'</td></tr>';	*/
							$table .= '<tr><td>Designation</td><td class="cls_right">' . $value['designation'] . '</td></tr>';
							$table .= '<tr><td>Report TO</td><td class="cls_right">' . $value['ReportTo'] . '</td></tr>';
							$table .= '<tr><td>AccountHead</td><td class="cls_right">' . $value['AccountHead'] . '</td></tr>';
							$table .= '<tr><td>Client </td><td class="cls_right">' . $value['client_name'] . '</td></tr>';
							$table .= '<tr><td>Process</td><td class="cls_right">' . $value['Process'] . '</td></tr>';
							$table .= '<tr><td>Sub Process</td><td class="cls_right">' . $value['sub_process'] . '</td></tr>';
							$table .= '<tr><td>Department Name</td><td class="cls_right">' . $value['dept_name'] . '</td></tr>';
						}
						$table .= '</tbody></table>';
						echo $table;
					} else {
						//$mymsg="<span class='text-danger'>No Data Found  ... ".$my_error." </span>";
						echo "<script>$(function(){ toastr.error('No Data Found  ... " . $my_error . "') }); </script>";
					}
					?>
					<div class="input-field col s12 m12 hidden">

						<select id="handler" name="handler" readonly="">

						</select>
						<label for="handler" class="active-drop-down active">Handler :</label>
					</div>
				</div>



				<div>
					<div class="input-field col s12 m12">
						<select id="contact_status" name="contact_status">
							<option value="NA">---Select---</option>
							<option>Contacted</option>
							<option>Not Contacted</option>
						</select>
						<label for="contact_status" class="active-drop-down active">Contact Status :</label>
					</div>
					<div class="input-field col s12 m12">

						<select id="subb_issue" name="subb_issue"></select>
						<label for="subb_issue" class="active-drop-down active">Disposition :</label>
					</div>

					<div class="input-field col s12 m12 hidden">

						<select id="handler" name="handler" readonly="">
							<option value="CE03070003">Sachin SIWACH</option>
						</select>
						<label for="handler" class="active-drop-down active">Handler :</label>
					</div>
					<div class="input-field col s12 m12 tohide">


						<select name="queryto" id="queryto">
							<option value="NA">---Select---</option>
							<option value="Human Resource">Human Resource</option>
							<option value="Information Technology">Information Technology</option>
							<option value="Operation">Operation</option>
							<option value="Administration">Administration</option>
							<?php

							/*$sqlBy = array(
													'table' => 'dept_master',
													'fields' => 'dept_id,dept_name',
													'condition' =>"1"); 
												$myDB=new MysqliDb();
												$resultBy=$myDB->select($sqlBy);
												if($resultBy){													
													$selec='';	
													foreach($resultBy as $key=>$value){
																											
														echo '<option value="'.$value['dept_master']['dept_id'].'" '.$selec.' >'.$value['dept_master']['dept_name'].'</option>';
													}
		
												}*/

							?>
						</select>
						<label for="queryto" class="active-drop-down active">Belongs To:</label>
					</div>
					<div class="input-field col s12 m12 tohide">
						<select type="text" name="querysub" id="querysub">
							<option value="NA">---Select---</option>
						</select>
						<label for="querysub" class="active-drop-down active">Issue</label>

					</div>
					<div class="input-field col s12 m12 hidden">

						<select id="handler" name="handler" readonly="">
							<option value="CE03070003">Sachin SIWACH</option>
						</select>
						<label for="handler" class="active-drop-down active">Handler </label>
					</div>


					<div class="input-field col s12 m12">
						<label>Remark</label>
						<textarea name="remark" id="remark" class="materialize-textarea materialize-textarea-size " placeholder="Remark Body" title="Type Your remark Here"></textarea>
					</div>
					<div class="input-field col s12 m12 right-align">
						<!--	<button type="submit"  id="btnSave" class="button button-3d-action button-rounded" name="btnSave" style="margin-left: 168px;margin-top: 10px;"> Save Request <i class="fa fa-send"></i>-->
						<button type="submit" name="btnSave" id="btnSave" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green">Save Request</button>
					</div>
				</div>
			</div>
			<hr />


			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	function checkRepeat(str) {
		var repeats = /(.)\1{3,}/;

		return repeats.test(str)
	}
	$(function() {

		$('#contact_status').change(function() {
			if ($(this).val() != 'Contacted') {
				$('#subb_issue').html('<option value="NA">---Select---</option><option >Switched Off</option><option >Ringing No Answer</option><option >Number Busy</option><option >Call Disconnected</option><option >Not a right party</option><option >Call Back</option><option >No. Not in use</option><option >Call Silent</option><option >Out Of Coverage Area</option>');
			} else {
				$('#subb_issue').html('<option value="NA">---Select---</option><option >Issue</option><option >No Issue</option>');
			}
			$('.tohide').addClass('hidden');
			$('select').formSelect();
		});
		$('#subb_issue').change(function() {
			if ($(this).val() != 'Issue') {
				$('.tohide').addClass('hidden');
			} else {
				$('.tohide').removeClass('hidden');
			}
		});
		$('#btnSave').click(function() {
			var validate = 0;
			var alert_msg = '';
			$('#queryto').closest('div').removeClass('has-error');
			$('#querysub').closest('div').removeClass('has-error');
			$('#querybody').closest('div').removeClass('has-error');
			$('#remark').closest('div').removeClass('has-error');


			if ($('#contact_status').val() == 'NA') {

				validate = 1;
				$('#contact_status').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_contact_status').size() == 0) {
					$('<span id="span_contact_status" class="help-block">Contact Status not be blank</span>').insertAfter('#contact_status');
				}

			}

			if ($('#subb_issue').val() == 'NA') {

				validate = 1;
				//alert_msg+='<li> Disposition not be blank </li>';
				$('#subb_issue').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#span_subb_issue').size() == 0) {
					$('<span id="span_subb_issue" class="help-block">Disposition not be blank</span>').insertAfter('#subb_issue');
				}
			} else {
				if ($('#subb_issue').val() == 'Issue') {
					if ($('#queryto').val() == 'NA') {
						//$('#queryto').closest('div').addClass('has-error');
						validate = 1;
						//alert_msg+='<li> Header of Query can not be blank </li>';
						$('#queryto').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
						if ($('#span_queryto').size() == 0) {
							$('<span id="span_queryto" class="help-block">Header of Query can not be blank</span>').insertAfter('#queryto');
						}

					}
					if ($('#querysub').val() == 'NA') {
						//$('#querysub').closest('div').addClass('has-error');
						validate = 1;
						//alert_msg+='<li> Issue can not be blank </li>';

						$('#querysub').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
						if ($('#span_querysub').size() == 0) {
							$('<span id="span_querysub" class="help-block"> Issue can not be blank</span>').insertAfter('#querysub');
						}
					}

				}
			}


			if (checkRepeat($('#remark').val())) {

				validate = 1;
				alert_msg += '<li> Remark should not contain Repeat character</li>';
				$('#remark').addClass('has-error');
				if ($('#stxt_remark').size() == 0) {
					$('<span id="stxt_remark" class="help-block">Remark should not contain Repeat character</span>').insertAfter('#remark');
				}

			}
			if ($('#remark').val().length < 1) {

				validate = 1;
				//alert_msg+='<li> Remark should not be blank </li>';

				$('#remark').addClass('has-error');
				if ($('#stxt_remark').size() == 0) {
					$('<span id="stxt_remark" class="help-block">Remark should not be blank</span>').insertAfter('#remark');
				}

			}


			if (validate == 1) {
				/*$('#alert_message').html('<ul class="text-danger">'+alert_msg+'</ul>');
				$('#alert_message').show().attr("class","SlideInRight animated");
				$('#alert_message').delay(10000).fadeOut("slow");
				*/
				if (alert_msg != "") {
					$(function() {
						toastr.error(alert_msg)
					});
				}
				return false;
			}

		});
		$('#querysub').change(function() {
			var tval = $(this).val();
			var loc = $('#loc').val();
			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/getHandler.php?id=" + tval + "&loc=" + loc
			}).done(function(data) { // data what is sent back by the php page
				//alert(data);
				$('#handler').html(data);
				$('select').formSelect();

			});
		});
		$('#queryto').change(function() {
			var tval = $(this).val();

			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/getIssue.php?id=" + tval + "&loc=" + <?php echo '"' . $_SESSION['__location'] . '"'; ?>
			}).done(function(data) { // data what is sent back by the php page
				//alert(data);
				$('#querysub').html(data);
				$('#querysub').val('NA');
				$('select').formSelect();
			});
		});

		$('.tohide').addClass('hidden');
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>