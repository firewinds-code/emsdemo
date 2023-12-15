<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$EmployeeID = clean($_SESSION['__user_logid']);
$re = cleanUserInput($_POST['revoke']);
if (isset($_SESSION)) {
	if (!isset($EmployeeID)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		if (isset($re)) {
			$revoke_on = date('Y-m-d H:i:s');
			$revoke = cleanUserInput($_POST['txtComent_agent']);
			$sql = "update resign_details set revoke_status = 1,revoke_on = ?,revoke_comment=?  where  EmployeeID = ? and rg_status = 1 and accept = 1";
			$update = $conn->prepare($sql);
			$update->bind_param("sss", $revoke_on, $revoke, $EmployeeID);
			$update->execute();
			$results = $update->get_result();
			// $myDB->query($sql);
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}

?>

<style>
	table td,
	td,
	table.dataTable tbody td,
	table.dataTable tbody td {
		line-height: 18px;
	}
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">EMS Alert Module</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>EMS Alert Module</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div class="flow-y-scroll">
					<?php
					$Getinfo = 'select w.DOJ,w.designation,w.clientname,w.Process,w.sub_process ,alert_details.*,pd1.EmployeeName,pd.EmployeeName as Suppervisor,pd1.img from alert_details left outer join whole_dump_emp_data w on w.EmployeeID = alert_details.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = alert_details.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo where (curdate() between alert_details.alert_start and alert_end) and (alert_details.EmployeeID = ? or account_head = ? or oh =  ? or th = ? or ReportTo = ? or  qh = ? or "CE03070003" =  ? or (select true from whole_details_peremp where cm_id  = "37" and des_id in  (1,5,7,8,10) and EmployeeID = ? ))';
					// $my_error = $myDB->getLastError();
					$selectQ = $conn->prepare($Getinfo);
					$selectQ->bind_param("ssssssss", $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID);
					$selectQ->execute();
					$chk_task = $selectQ->get_result();

					if ($chk_task->num_rows > 0 && $chk_task) {
						$table = '<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">';
						$table .= '<tbody>';
						foreach ($chk_task as $key => $value) {
							$img = '<img alt="user" style="height: 125px;border-radius: 2px;margin: 0px;width: 150px;border: 1px solid #eee;padding-top: 5px;" src="';
							if (file_exists("../Images/" . $value['img']) && $value['img'] != '') {
								$img .= "../Images/" . $value['img'];
							} else {
								$img .= "../Style/images/agent-icon.png";
							}
							$img .= '"/>';

							$table .= '<tr>
								<td style="font-weight: bold;color: #57734e;padding: 8px;width: 216px;text-align:center;">' . $img . '</td>';

							$table .= '<td>
							<span style="color:black;width:150px;float:left;">Name</span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['EmployeeName'] . '
							<span class="red-text">&nbsp;(&nbsp;' . $value['EmployeeID'] . '&nbsp;)&nbsp;</span><br />';

							$table .= '<span style="color:black;width:150px;float:left;">DOJ</span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['DOJ'] . '</br>';
							$table .= '<span style="color:black;width:150px;float:left;">Designation</span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['designation'] . '</br>';
							$table .= '<span style="color:black;width:150px;float:left;">Process</span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['Process'] . '</br>';
							$table .= '<span style="color:black;width:150px;float:left;">Sub Process</span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['sub_process'] . '</br>';
							$table .= '<span style="color:black;width:150px;float:left;">Client</span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['clientname'] . '</br>';
							$table .= '<span style="color:black;width:150px;float:left;">Supervisor</span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['Suppervisor'] . '</br>';
							'</br>';
							if ($value['type'] == 'NCNS In-active') {
								$table .= '<span style="color:black;font-weight: normal;padding: 5px;border: 1px solid rgb(29, 173, 196);border-radius: 2px;float: left;line-height: 15px;background: rgb(198, 247, 255);margin-top: 10px;" class="col s12 m12">Is <span class="red-text" style="font-weight: bold;">' . $value['type'] . '</span> From EMS on ' . date('d M,Y', strtotime($value['createdon'])) . '</span></br></td>';
							} else {
								$varr  =  explode('|', $value['type']);

								if (trim($varr[0])  == 'RESIGN Accept') {
									$table .= '<span style="color:black;font-weight: normal;padding: 5px;border: 1px solid rgb(29, 173, 196);border-radius: 2px;float: left;line-height: 15px;background: rgb(198, 247, 255);margin-top: 10px;" class="col s12 m12">Is on notice of <span class="red-text" style="font-weight: bold;">Resign Accepted </span> From ' . date('d M,Y', strtotime($varr[1])) . ' to ' . date('d M,Y', strtotime($varr[2])) . '</span></br>';
									$date1 = date_create(date('Y-m-d', strtotime($varr[2])));
									$date2 = date_create(date('Y-m-d', time()));
									$diff = date_diff($date2, $date1);
									$day_diff =  $diff->format("%R%a");


									$result_cs = $myDB->query('select revoke_status from  resign_details where  EmployeeID = "' . $value['EmployeeID'] . '"  and final_acceptance is null');
									if (count($result_cs) > 0 and $result_cs) {

										$status_revock = $result_cs[0]['revoke_status'];

										//$table .='<span> Your request for Resign Cancel is in Queue for approval</span>';
										if ($_SESSION['__user_logid'] == $value['EmployeeID'] && $day_diff >= 14 && $day_diff < 32 && empty($status_revock)) {
											$table .= '<div class="input-field col s9 m9 "><input type="text" value="" id="txtComent_agent" title="Remark for calcel Resign" name="txtComent_agent" /><label for="txtComent_agent">Remarks</label></div>';
											$table .= '<div class="input-field col s2 m2 right-align"><input type="submit" value="Revoke" id="revoke" class="btn waves-effect waves-green"  title="to calcel request"  name="revoke" data="' . $value['EmployeeID'] . '" /></div>';
										} elseif ($_SESSION['__user_logid'] == $value['EmployeeID'] && $status_revock == 1) {
											$table .= '<span> Your request for Resign Cancel is in Queue for approval</span>';
										}
									}


									$table .= '</td>';
								} else if (trim($varr[0]) == 'RESIGN Reject') {
									$table .= '<span style="color:black;font-weight: normal;padding: 5px;border: 1px solid rgb(29, 173, 196);border-radius: 2px;float: left;line-height: 15px;background: rgb(198, 247, 255);margin-top: 10px;" class="col s12 m12">Request for <span class="red-text" style="font-weight: bold;">Resign</span> From ' . date('d M,Y', strtotime($varr[1])) . ' to ' . date('d M,Y', strtotime($varr[2])) . ' <span class="red-text" style="font-weight: bold;">is Rejected by HR Head.</span></span></br></td>';
								} else if (trim($varr[0]) == 'RESIGN COMPLETE') {
									$table .= '<span style="color:black;font-weight: normal;padding: 5px;border: 1px solid rgb(29, 173, 196);border-radius: 2px;float: left;line-height: 15px;background: rgb(198, 247, 255);margin-top: 10px;" class="col s12 m12">Request for <span class="red-text" style="font-weight: bold;">Resign Completed  </span> on <b>' . date('d M,Y', strtotime($varr[1])) . ' </b> <span class="red-text" style="font-weight: bold;"> and  Inactive on EMS by HR Head.</span></span></br></td>';
								} else if (trim($varr[0]) == 'Resign cancellation request approved by HR') {
									$table .= '<span style="color:black;font-weight: normal;padding: 5px;border: 1px solid rgb(29, 173, 196);border-radius: 2px;float: left;line-height: 15px;background: rgb(198, 247, 255);margin-top: 10px;" class="col s12 m12">Request for <span class="red-text" style="font-weight: bold;">Resign Cancel  </span> Approved on EMS by HR Head.</span></span></br></td>';
								}
							}
							$table .= '</tr>';
						}
						$table .= '</tbody></table>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found.'); }); </script>";
					}
					?>
				</div>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(document).ready(function() {
		$('#revoke').click(function() {
			if (confirm("You want to cancel your Resign Request")) {
				if ($('#txtComent_agent').val() == '') {
					alert('Comment field should not be empty');
					return false;
				}
				return true;
			}
			return false;

		});
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>