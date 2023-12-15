<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

// Global variable used in Page Cycle
$alert_msg = '';
// Trigger Button-Save Click Event and Perform DB Action

// Trigger Button-Edit Click Event and Perform DB Action
$type = (isset($_POST['hiddentype']) ? $_POST['hiddentype'] : null);

if (isset($_POST['btn_Assign_Batch'])) {
	$notSalaryslab = $notVersant = $notBGV = '';
	$Applicable = '';
	if (isset($_POST['cb'])) {
		$checked_arr = $_POST['cb'];
		$count_check = count($checked_arr);
		$cm_id = (isset($_POST['txt_batch']) ? $_POST['txt_batch'] : null);
		$training_head = '';
		$operation_head = '';
		$createBy = $_SESSION['__user_logid'];
		$date = date("Y-m-d H:i:s");
		$myDB = new MysqliDb();
		$select_th = $myDB->rawQuery("Select th,oh from new_client_master where cm_id=$cm_id");
		$mysql_error = $myDB->getLastError();
		$rowCount = $myDB->count;
		if ($rowCount > 0) {
			$training_head = $select_th[0]['th'];
			$operation_head = $select_th[0]['oh'];
		}

		if ($count_check > 0) {

			foreach ($_POST['cb'] as $val) {
				$empID = $val;
				$mysquery = "select ctc,cm_id,designation,id,w.des_id from salary_details s inner join whole_details_peremp w on w.EmployeeID=s.EmployeeID where s.EmployeeID='" . $empID . "' and (CAST(SUBSTRING_INDEX(ctc, '-', -1) AS UNSIGNED)  <= (select CAST(SUBSTRING_INDEX(max_lim, '-', -1) AS UNSIGNED) as maxslab from tbl_salary_slab_by_cps  where cm_id='" . $cm_id . "') or w.des_id not  in (9,12,33,34,35,36) )";

				$myDB = new MysqliDb();
				$resultBy = $myDB->rawQuery($mysquery);
				$mysql_error = $myDB->getLastError();
				$rowCount = $myDB->count;

				if (empty($mysql_error) && $rowCount > 0) {
					$versantflag = 0;
					if ($resultBy[0]['des_id'] == '9' || $resultBy[0]['des_id'] == '12' || $resultBy[0]['des_id'] == '33' || $resultBy[0]['des_id'] == '34' || $resultBy[0]['des_id'] == '35' || $resultBy[0]['des_id'] == '36') {
						$mysquery = "select cert_name,cm_id from certification_require_by_cmid where cm_id='" . $cm_id . "'";

						$myDB = new MysqliDb();
						$versant = $myDB->rawQuery($mysquery);
						$mysql_error = $myDB->getLastError();
						$versantrowCount = $myDB->count;
						if (empty($mysql_error) && $versantrowCount > 0) {
							$mysquery = "select test_name from test_score where EmpID='" . $empID . "'";
							$myDB = new MysqliDb();
							$testname = $myDB->rawQuery($mysquery);
							$mysql_error = $myDB->getLastError();
							$testrowCount = $myDB->count;
							if (empty($mysql_error) && $testrowCount > 0) {
								if ($testrowCount >= $versantrowCount) {
									//echo 'In';
									$flag = '';
									foreach ($versant as $key => $value1) {
										if ($flag == 1) {
											$versantflag = 1;
											break;
										}
										$flag = 1;
										$cert_name = trim($value1['cert_name']);
										//echo 'certification_require_by_cmid - ' . $cert_name . '<br/>';
										foreach ($testname as $key => $value) {
											if ($cert_name == trim($value['test_name']))
												$flag = 0;
											else {
												if (strpos($cert_name, "ant -") == '4' && strpos($value['test_name'], "ant -") == '4') {
													//echo 'there1';
													if ((int)substr($value['test_name'], strlen($value['test_name']) - 1, 1) > (int)substr($cert_name, strlen($cert_name) - 1, 1)) {
														//echo 'there';
														$flag = 0;
													}
												}
											}
											//echo 'test_name - ' . $value['test_name'] . '<br/>';
										}
									}
									if ($flag == 1) {
										$versantflag = 1;
									}
								} else {
									$versantflag = 1;
								}
							} else {
								$versantflag = 1;
							}
						}
					}
					//echo $versantflag;
					if ($versantflag == 1) {
						$notVersant .= $empID . ',';
					} else {
						$bgvflag = 0;

						if ($resultBy[0]['des_id'] == '9' || $resultBy[0]['des_id'] == '12' || $resultBy[0]['des_id'] == '33' || $resultBy[0]['des_id'] == '34' || $resultBy[0]['des_id'] == '35' || $resultBy[0]['des_id'] == '36') {
							$query = $myDB->rawQuery("SELECT * FROM bgv_matrix where cm_id = '" . $cm_id . "' and desig = (select case when df_id in (74,77,146, 147,148,149) then 'CSA' else 'Support' end as desig from employee_map where EmployeeID='" . $empID . "') and (Addr='Yes' or Edu='Yes' or Emp='Yes' or Crim='Yes');");
							if ($myDB->count > 0) {
								$query = $myDB->rawQuery("select doc_file from doc_details where employeeid='" . $empID . "' and doc_stype='BG verification';");
								if ($myDB->count <= 0) {
									$bgvflag = 1;
								}
							}
						}
						if ($bgvflag == 1) {
							$notBGV .= $empID . ',';
						} else {
							//die;
							//echo "insert into tbl_client_toclient_move (EmployeeID, move_date, new_cm_id, old_cm_id, flag, status, updated_on, transfer_by, hr_comment, ah_comment, HR_updated_by, AH_updated_on, HR_updated_on, AH_updated_by, Updated_by) values('".$empID."',now(),'".$cm_id."','".$resultBy[0]['cm_id']."','FM','transfered',now(),'".$createBy."','Manual Client Movement','Manual Client Movement','".$createBy."',now(),now(),'".$createBy."','Manual Client Movement'); ";
							$data_status_query = $myDB->rawQuery("insert into tbl_client_toclient_move (EmployeeID, move_date, new_cm_id, old_cm_id, flag, status, updated_on, transfer_by, hr_comment, ah_comment, HR_updated_by, AH_updated_on, HR_updated_on, AH_updated_by, Updated_by) values('" . $empID . "',now(),'" . $cm_id . "','" . $resultBy[0]['cm_id'] . "','FM','transfered',now(),'" . $createBy . "','Manual Client Movement','Manual Client Movement','" . $createBy . "',now(),now(),'" . $createBy . "','Manual Client Movement'); ");
							$mysql_error = $myDB->getLastError();
							$rowCount = $myDB->count;
							if ($rowCount > 0) {
								$Applicable .= $empID . ',';
								$data_status_query = $myDB->rawQuery("insert into status_table_log  select  * from status_table  where EmployeeID='" . $empID . "'");
								$mysql_error = $myDB->getLastError();
								$rowCount = $myDB->count;
								if ($rowCount > 0) {
									if (($resultBy[0]['id'] == "7" || $resultBy[0]['id'] == "8" || $resultBy[0]['id'] == "10") && ($resultBy[0]['designation'] == "Executive" || $resultBy[0]['designation'] == "Team Leader" || $resultBy[0]['designation'] == "Senior Executive" || $resultBy[0]['designation'] == "Assistant Team Leader" || $resultBy[0]['designation'] == "Group Team Leader" || $resultBy[0]['des_id'] == '9' || $resultBy[0]['des_id'] == '12' || $resultBy[0]['des_id'] == '33' || $resultBy[0]['des_id'] == '34' || $resultBy[0]['des_id'] == '35' || $resultBy[0]['des_id'] == '36')) {

										$myDB = new MysqliDb();
										$update_status_table = $myDB->rawQuery("Update status_table set Status='2',ReportTo='" . $training_head . "',Qa_ops='',BatchID='0',createdon='" . $date . "',InTraining=NULL,InOJT=NULL,OnFloor=NULL,OutTraining=NULL,InQAOJT=NULL,OutOJTQA=NULL,RetrainTime=NULL,roster=NULL,reOJT=NULL,mapped_date='" . $date . "'  where  EmployeeID='" . $empID . "' ");
										$mysql_error = $myDB->getLastError();
										$rowCount = $myDB->count;
									} else {
										echo "Update status_table set  ReportTo='" . $operation_head . "',createdon='" . $date . "'  where  EmployeeID='" . $empID . "' ";
										$myDB = new MysqliDb();
										$update_status_table = $myDB->rawQuery("Update status_table set  ReportTo='" . $operation_head . "',createdon='" . $date . "'  where  EmployeeID='" . $empID . "' ");
									}

									//echo "insert into employee_map_updates ( select * from employee_map  where EmployeeID='".$empID."')"."<br/>";
									//echo "UPDATE  employee_map set cm_id='".$cm_id."',modifiedon='".date("Y-m-d H:i:s")."',modifiedby='Server' where EmployeeID = '".$empID."'";
									$myDB = new MysqliDb();
									$update_status_table = $myDB->rawQuery("insert into employee_map_updates ( select * from employee_map  where EmployeeID='" . $empID . "')");

									$myDB = new MysqliDb();
									$update_status_table = $myDB->rawQuery("UPDATE  employee_map set cm_id='" . $cm_id . "',modifiedon='" . $date . "',modifiedby='Server' where EmployeeID = '" . $empID . "' ");

									$myDB = new MysqliDb();
									$update_status_table = $myDB->rawQuery("call update_module_master_empid('" . $cm_id . "','" . $empID . "','" . $createBy . "')");
								}
							}
						}
					}
				} else {
					$notSalaryslab .= $empID . ',';
				}
			}
			//echo "<script>$(function(){ toastr.success('Batch Assigned Successfully'); }); </script>";
			if ($notSalaryslab != "") {
				$notSalaryslab = substr($notSalaryslab, 0, -1);
			}
			if ($notVersant != "") {
				$notVersant = substr($notVersant, 0, -1);
			}
			if ($notBGV != "") {
				$notBGV = substr($notBGV, 0, -1);
			}
			if ($Applicable != "") {
				$Applicable = substr($Applicable, 0, -1);
			}

			if ($notSalaryslab != "") {
				echo "<script>$(function(){ toastr.error('[" . $notSalaryslab . "] Employee(s) Movement can not proceed due to lower salary slab'); }); </script>";
			}
			if ($notVersant != "") {
				echo "<script>$(function(){ toastr.error('[" . $notVersant . "] Employee(s) Movement can not proceed due to test not updated'); }); </script>";
			}
			if ($notBGV != "") {
				echo "<script>$(function(){ toastr.error('[" . $notBGV . "] Employee(s) Movement can not proceed due to BGV not updated'); }); </script>";
			}

			if ($Applicable != "") {
				echo "<script>$(function(){ toastr.success('[" . $Applicable . "] Employee(s) Movement initiated Successfully.'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Record not updated, no Employee selected.'); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Record not updated, no Employee selected.'); }); </script>";
	}
}
?>
<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"paging": false,
			"iDisplayLength": 25,
			scrollCollapse: true,
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
				}
				/*,'copy'*/
				,
				'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
	});
</script>


<div id="content" class="content">
	<span id="PageTittle_span" class="hidden">Employee Movement</span>

	<div class="pim-container row" id="div_main">
		<div class="form-div">
			<input type="hidden" id="hiddenclient" name="hiddenclient" />
			<input type="hidden" id="hiddensubprocess" name="hiddensubprocess" />
			<input type="hidden" id="hiddensubprocessid" name="hiddensubprocessid" />
			<input type="hidden" id="hiddentype" name="hiddentype" />

			<h4>Employee Movement</h4>
			<div class="schema-form-section row">


				<div class="input-field col s12 m12" id="rpt_container">

					<!--<div class="input-field col s4 m4">
				            <select id="txt_type" name="txt_type">
				            	<option value="NA" <?php if ($type == 'NA' || $type == '' || empty($type)) {
														echo ('selected');
													} ?>>----Select----</option>	
						      	<option value="Offline" <?php if ($type == 'Offline') {
																echo ('selected');
															} ?>>Offline</option>	
						      	<option value="Online" <?php if ($type == 'Online') {
																echo ('selected');
															} ?>>Online</option>	
				            </select>
				            
		            		<label for="txt_type" class="active-drop-down active">Employee Source *</label>
			   		 	</div>-->

					<div class="input-field col s4 m4" id="divloc1">
						<select id="txt_location" name="txt_location">
							<option value="NA">----Select----</option>
							<?php
							$sqlBy = 'select id,location from location_master;';
							$myDB = new MysqliDb();
							$resultBy = $myDB->rawQuery($sqlBy);
							$mysql_error = $myDB->getLastError();
							if (empty($mysql_error)) {
								foreach ($resultBy as $key => $value) {
									echo '<option value="' . $value['id'] . '"  >' . $value['location'] . '</option>';
								}
							}
							?>
						</select>

						<label for="txt_location" class="active-drop-down active">Location *</label>
					</div>


					<div class="input-field col s4 m4" id="divclient1">
						<select id="txt_Client" name="txt_Client">

						</select>

						<label for="txt_Client" class="active-drop-down active">Client *</label>
					</div>



					<div class="input-field col s4 m4" id="divproc1">
						<select id="txt_Process" name="txt_Process">

						</select>

						<label for="txt_Process" class="active-drop-down active">Process *</label>
					</div>



					<div class="input-field col s4 m4" id="divsubproc1">
						<select id="txt_subProcess" name="txt_subProcess">

						</select>

						<label for="txt_subProcess" class="active-drop-down active">Sub Process *</label>
					</div>

					<div class="batchdetails">
						<div class="input-field col s12 m12">
							<select id="txt_batch" name="txt_batch">
								<option value="NA">----Select----</option>
								<?php

								$loc = (isset($_POST['txt_location']) ? $_POST['txt_location'] : null);
								$cmid = (isset($_POST['txt_subProcess']) ? $_POST['txt_subProcess'] : null);
								$sqlBy = '';
								if ($loc != "") {
									//$sqlBy = "select distinct concat(clientname,'|',Process,'|',sub_process) as process,cm_id from whole_details_peremp where location='" . $loc . "' and cm_id not in (select cm_id from client_status_master) and cm_id!='" . $cmid . "' and id in (7,8,10) order by clientname;";

									$sqlBy = "select distinct concat(t2.client_name,'|',process,'|',sub_process)as process,cm_id from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where location='" . $loc . "' and cm_id not in (select cm_id from client_status_master) and cm_id!='" . $cmid . "' and dept_id=1 order by t2.client_name;";

									$myDB = new MysqliDb();
									$resultBy = $myDB->rawQuery($sqlBy);
									$mysql_error = $myDB->getLastError();
									if (empty($mysql_error)) {
										foreach ($resultBy as $key => $value) {
											echo '<option value="' . $value['cm_id'] . '"  >' . $value['process'] . '</option>';
										}
									}
								}

								?>
							</select>

							<label for="txt_batch" class="active-drop-down active">Client *</label>
						</div>

						<div class="input-field col s4 m4">
							<button type="submit" name="btn_Assign_Batch" id="btn_Assign_Batch" class="btn waves-effect waves-green">Move Employee</button>
						</div>
						<br /><br />
					</div>

					<div class="input-field col s12 m12 getdetails right-align">

						<input type="hidden" class="form-control hidden" id="hid_Department_ID" name="hid_Department_ID" />
						<button type="submit" name="btn_Department_Save" id="btn_Department_Save" class="btn waves-effect waves-green">Get Details</button>

					</div>

				</div>


				<?php
				if (isset($_POST['btn_Department_Save'])) {
					$cmid = (isset($_POST['txt_subProcess']) ? $_POST['txt_subProcess'] : null);
					$resultBy = '';
					$emptype = (isset($_POST['txt_type']) ? $_POST['txt_type'] : null);
					$sqlBy = 'select EmployeeID,EmployeeName,designation,DATE_FORMAT(doj, "%d-%M-%Y") as DOJ,clientname,Process,sub_process,location_master.location from whole_details_peremp join location_master on whole_details_peremp.location=location_master.id where cm_id="' . $cmid . '" and status=6 and whole_details_peremp.id in (7,8,10) order by EmployeeName;';
					$myDB = new MysqliDb();
					$resultBy = $myDB->rawQuery($sqlBy);
					$mysql_error = $myDB->getLastError();
					if (count($resultBy) > 0) {

				?>

						<div class="flow-x-scroll" style="margin-top: 10px;width: 100%;padding: 15px;overflow:scroll; height:400px;">

							<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>
											<input type="checkbox" id="cbAll" name="cbAll" value="ALL">
											<label for="cbAll">Employee ID</label>
										</th>
										<th class="hidden">Employee ID</th>
										<th>Employee Name</th>
										<th>Designation</th>
										<th>DOJ</th>
										<th>Location</th>
										<th>Client</th>
										<th>Process</th>
										<th>Sub Process</th>

									</tr>
								</thead>
								<tbody>
									<?php
									$count = 0;
									foreach ($resultBy as $key => $value) {
										$count++;
										echo '<tr>';
										echo '<td class="EmpId"><input type="checkbox" id="cb' . $count . '" class="cb_child" name="cb[]" value="' . $value['EmployeeID'] . '"><label for="cb' . $count . '" style="color: #059977;font-size: 14px;font-weight: bold;}">' . $value['EmployeeID'] . '</label></td>';
										echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="' . $value['EmployeeID'] . '">' . $value['EmployeeID'] . '</a></td>';
										echo '<td class="FullName">' . $value['EmployeeName'] . '</td>';
										echo '<td class="designation">' . $value['designation'] . '</td>';
										echo '<td class="DOJ">' . $value['DOJ'] . '</td>';
										echo '<td class="location">' . $value['location'] . '</td>';
										echo '<td class="client_name">' . $value['clientname'] . '</td>';
										echo '<td class="process">' . $value['Process'] . '</td>';
										echo '<td class="sub_process">' . $value['sub_process'] . '</td>';

										echo '</tr>';
									}
									?>
								</tbody>
							</table>

						</div>

				<?php
					} else {
						echo "<script>$(function(){ toastr.info('No Employee Found.'); }); </script>";
					}
				}
				?>




			</div>
		</div>
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	$(document).ready(function() {
		//Model Assigned and initiation code on document load
		$('.batchdetails').addClass('hidden');
		$('.getdetails').removeClass('hidden');


		$('.modal').modal({
			onOpenStart: function(elm) {


			},
			onCloseEnd: function(elm) {
				$('#btn_Department_Can').trigger("click");
			}
		});
		// This code for cancel button trigger click and also for model close
		$('#btn_Department_Can').on('click', function() {
			//alert($('#txt_location').val());
			$('#txt_location').val('NA');
			$('#txt_Process').children().remove();
			$('#txt_date').val('');
			$('#txt_count').val('');
			$('#btn_Department_Save').removeClass('hidden');
			$('#btn_Department_Edit').addClass('hidden');

			$('#divloc1').removeClass('hidden');
			$('#divclient1').removeClass('hidden');
			$('#divproc1').removeClass('hidden');
			$('#divsubproc1').removeClass('hidden');

			$('#divloc2').addClass('hidden');
			$('#divclient2').addClass('hidden');
			$('#divproc2').addClass('hidden');
			$('#divsubproc2').addClass('hidden');

			$('#divstatus').addClass('hidden');

			$('#txt_locationhidden').val('');
			$('#txt_clienthidden').val('');
			$('#txt_processhidden').val('');
			$('#txt_subprocesshidden').val('');
			//$('#btn_Department_Can').addClass('hidden');

			// This code for remove error span from input text on model close and cancel

		});

		$('#txt_type').change(function() {
			$('#txt_location').val('NA');
			$('#txt_Process').empty();
			$('#txt_subProcess').empty();
			$('#txt_Client').empty();

			$('#divloc1').addClass('hidden');
			$('#divclient1').addClass('hidden');
			$('#divproc1').addClass('hidden');
			$('#divsubproc1').addClass('hidden');

			$('.batchdetails').addClass('hidden');
			$('.getdetails').removeClass('hidden');
			$('#myTable').html('');

			if ($(this).val() == 'Offline') {
				$('#divloc1').removeClass('hidden');
				$('#divclient1').removeClass('hidden');
				$('#divproc1').removeClass('hidden');
				$('#divsubproc1').removeClass('hidden');

			} else if ($(this).val() == 'Online') {
				$('#divproc1').removeClass('hidden');
				$('#divclient1').removeClass('hidden');


				var xmlhttp;
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else { // code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


						var Resp = xmlhttp.responseText;
						//$('#txt_Client_ach').html(Resp);
						//$('#txt_vertical_head').html(Resp);
						/*$('#txt_Client_oh').html(Resp);
						$('#txt_Client_qh').html(Resp);
						$('#txt_Client_th').html(Resp);*/
						$('#txt_Client').html(Resp);
						$('select').formSelect();
					}

				}

				//var location = <?php echo $_SESSION["__location"] ?>;
				//alert(el);
				//$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
				xmlhttp.open("GET", "../Controller/getalignmentforbatchmaster.php?mode=" + $(this).val() + "&type=client", true);
				xmlhttp.send();
			}
		});

		$('#txt_location').change(function() {

			$('#txt_Process').empty();
			$('#txt_subProcess').empty();
			var xmlhttp;
			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


					var Resp = xmlhttp.responseText;
					//$('#txt_Client_ach').html(Resp);
					//$('#txt_vertical_head').html(Resp);
					/*$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);*/
					$('#txt_Client').html(Resp);
					$('select').formSelect();
				}

			}

			//var location = <?php echo $_SESSION["__location"] ?>;
			//alert(el);
			//$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
			xmlhttp.open("GET", "../Controller/getalignmentforbatchmaster.php?loc=" + $(this).val() + "&type=client", true);
			xmlhttp.send();
		});

		$('#txt_Client').change(function() {
			$('#txt_subProcess').empty();
			$('#hiddenclient').val($("#txt_Client option:selected").text());

			var xmlhttp;
			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


					var Resp = xmlhttp.responseText;
					//$('#txt_Client_ach').html(Resp);
					//$('#txt_vertical_head').html(Resp);
					/*$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);*/
					$('#txt_Process').html(Resp);
					$('select').formSelect();
				}

			}

			xmlhttp.open("GET", "../Controller/getalignmentforbatchmaster.php?loc=" + $('#txt_location').val() + "&client=" + $(this).val() + "&type=Process", true);
			xmlhttp.send();

		});

		$('#txt_Process').change(function() {
			var xmlhttp;
			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


					var Resp = xmlhttp.responseText;
					//$('#txt_Client_ach').html(Resp);
					//$('#txt_vertical_head').html(Resp);
					/*$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);*/
					$('#txt_subProcess').html(Resp);
					$('select').formSelect();
				}

			}

			//var location = <?php echo $_SESSION["__location"] ?>;
			//alert(el);
			//$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
			xmlhttp.open("GET", "../Controller/getsubprocess_movement.php?loc=" + $('#txt_location').val() + "&client=" + $('#txt_Client').val() + "&process=" + $(this).val() + "&type=SubProcess", true);
			xmlhttp.send();
		});

		$('#txt_subProcess').change(function() {
			$('#hiddensubprocessid').val($("#txt_subProcess option:selected").text());
			$('#hiddensubprocess').val($('#txt_subProcess').val());
		});


		// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.

		$('#btn_Department_Edit').on('click', function() {
			var validate = 0;


			var alert_msg = '';
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			$("input,select,textarea").each(function() {
				var spanID = "span" + $(this).attr('id');
				$(this).removeClass('has-error');
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
				}
				var attr_req = $(this).attr('required');
				if (($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) {
					validate = 1;
					$(this).addClass('has-error');
					if ($(this).is('select')) {
						$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					}
					if ($('#' + spanID).size() == 0) {
						$('<span id="' + spanID + '" class="help-block"></span>').insertAfter('#' + $(this).attr('id'));
					}
					var attr_error = $(this).attr('data-error-msg');
					if (!(typeof attr_error !== typeof undefined && attr_error !== false)) {
						$('#' + spanID).html('Required *');
					} else {
						$('#' + spanID).html($(this).attr("data-error-msg"));
					}
				}
			})

			if ($('#txt_count').val() == '') {

				$('#txt_count').addClass("has-error");
				if ($('#spantxt_count').size() == 0) {
					$('<span id="spantxt_count" class="help-block">*</span>').insertAfter('#txt_count');
				}
				validate = 1;
			}



			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");
				return false;
			}
		});

		$('#btn_Assign_Batch').on('click', function() {
			var validate = 0;
			//alert($('#txt_batch').val());
			if ($('#txt_batch').val() == 'NA') {

				validate = 1;
				$('#txt_batch').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_batch').size() == 0) {
					$('<span id="spantxt_batch" class="help-block">*</span>').insertAfter('#txt_batch');
				}
			}

			if (validate == 1) {

				return false;
			}

		});

		$('#btn_Department_Save').on('click', function() {

			var validate = 0;
			var alert_msg = '';
			if ($('#txt_type').val() == 'NA') {

				validate = 1;
				$('#txt_type').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_type').size() == 0) {
					$('<span id="spantxt_type" class="help-block">*</span>').insertAfter('#txt_type');
				}
			}

			if ($('#txt_location').val() == 'NA' && $('#txt_type').val() == 'Offline') {

				validate = 1;
				$('#txt_location').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_location').size() == 0) {
					$('<span id="spantxt_location" class="help-block">*</span>').insertAfter('#txt_location');
				}
			}

			if ($('#txt_Client').val() == 'NA') {

				validate = 1;
				$('#txt_Client').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client').size() == 0) {
					$('<span id="spantxt_Client" class="help-block">*</span>').insertAfter('#txt_Client');
				}
			}

			if ($('#txt_Process').val() == 'NA') {

				validate = 1;
				$('#txt_Process').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Process').size() == 0) {
					$('<span id="spantxt_Process" class="help-block">*</span>').insertAfter('#txt_Process');
				}
			}

			if ($('#txt_subProcess').val() == 'NA' && $('#txt_type').val() == 'Offline') {

				validate = 1;
				$('#txt_subProcess').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_subProcess').size() == 0) {
					$('<span id="spantxt_subProcess" class="help-block">*</span>').insertAfter('#txt_subProcess');
				}
			}

			if ($('#txt_date').val() == '') {

				$('#txt_date').addClass("has-error");
				if ($('#spantxt_date').size() == 0) {
					$('<span id="spantxt_date" class="help-block">*</span>').insertAfter('#txt_date');
				}
				validate = 1;
			}

			if ($('#txt_count').val() == '') {

				$('#txt_count').addClass("has-error");
				if ($('#spantxt_count').size() == 0) {
					$('<span id="spantxt_count" class="help-block">*</span>').insertAfter('#txt_count');
				}
				validate = 1;
			}

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");
				return false;
			}

			$('#hiddentype').val($('#txt_type').val());
			/*alert($('#hiddentype').val());
			return false;	*/

		});
		// This code for remove error span from input text on model close and cancel
		$(".has-error").each(function() {
			if ($(this).hasClass("has-error")) {
				$(this).removeClass("has-error");
				$(this).next("span.help-block").remove();
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find("span.help-block").remove();
				}
				if ($(this).hasClass('select-dropdown')) {
					$(this).parent('.select-wrapper').find("span.help-block").remove();
				}

			}
		});
		// This code for remove error span from all element contain .has-error class on listed events

		$('#txt_date').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			minDate: '+1970/01/01',
			scrollInput: false
		});

		$('#txt_count').keydown(function(event) {
			if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||

				// Allow: Ctrl+A
				(event.keyCode == 65 && event.ctrlKey === true) ||

				// Allow: Ctrl+V
				(event.ctrlKey == true && (event.which == '118' || event.which == '86')) ||

				// Allow: Ctrl+c
				(event.ctrlKey == true && (event.which == '99' || event.which == '67')) ||

				// Allow: Ctrl+x
				(event.ctrlKey == true && (event.which == '120' || event.which == '88')) ||

				// Allow: home, end, left, right
				(event.keyCode >= 35 && event.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
			} else {
				// Ensure that it is a number and stop the keypress
				if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
					event.preventDefault();
				}
			}
		});
	});


	// This code for trigger edit on all data table also trigger model open on a Model ID

	function EditData(el) {
		var tr = $(el).closest('tr');
		var id = tr.find('.id').text();
		var loc = tr.find('.loc').text();
		var location = tr.find('.location').text();
		var process = tr.find('.process').text();
		var batch_no = tr.find('.batch_no').text();
		var target_date = tr.find('.target_date').text();
		var target_count = tr.find('.target_count').text();
		var status = tr.find('.status').text();
		var array = process.split('|');

		$('#divloc1').addClass('hidden');
		$('#divclient1').addClass('hidden');
		$('#divproc1').addClass('hidden');
		$('#divsubproc1').addClass('hidden');

		$('#divloc2').removeClass('hidden');
		$('#divclient2').removeClass('hidden');
		$('#divproc2').removeClass('hidden');
		$('#divsubproc2').removeClass('hidden');

		$('#divstatus').removeClass('hidden');

		// alert(array[0]);
		$('#hid_Department_ID').val(id);

		$('#txt_locationhidden').val(location);
		$('#txt_clienthidden').val(array[0]);

		$('#txt_processhidden').val(array[1]);
		$('#txt_subprocesshidden').val(array[2]);


		$('#txt_status').val(status);

		$('#txt_date').val(target_date);
		$('#txt_count').val(target_count);

		$('#btn_Department_Save').addClass('hidden');
		$('#btn_Department_Edit').removeClass('hidden');





		//$('#btn_Department_Can').removeClass('hidden');
		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
	}

	// This code for trigger del*t*

	function ApplicationDataDelete(el) {
		var currentUrl = window.location.href;
		var Cnfm = confirm("Do You Want To Delete This ");
		if (Cnfm) {
			var xmlhttp;
			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


					var Resp = xmlhttp.responseText;
					alert(Resp);
					window.location.href = currentUrl;



				}
			}

			xmlhttp.open("GET", "../Controller/DeleteDepartment.php?ID=" + el.id, true);
			xmlhttp.send();
		}
	}


	function getProcess(el) {

		var currentUrl = window.location.href;

		var xmlhttp;
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


				var Resp = xmlhttp.responseText;
				$('#txt_Process').html(Resp);
				$('#txt_vertical_head').html(Resp);
				$('#txt_Client_oh').html(Resp);
				$('#txt_Client_qh').html(Resp);
				$('#txt_Client_th').html(Resp);
				$('select').formSelect();
			}

		}

		var location = <?php echo $_SESSION["__location"] ?>;
		alert(el);
		$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
		xmlhttp.open("GET", "../Controller/getprocessByLocation.php?loc=" + $('#txt_location').val(), true);
		xmlhttp.send();


	}

	$("#cbAll").change(function() {
		$("input.cb_child:checkbox").prop('checked', $(this).prop("checked"));
		if ($("input.cb_child:checkbox:checked").length > 0) {
			$('.batchdetails').removeClass('hidden');
		} else {
			$('.batchdetails').addClass('hidden')
		}
		$('select').formSelect();
		$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
	});

	$("input:checkbox").click(function() {
		if ($('input:checkbox:checked').length > 0) {
			$('.batchdetails').removeClass('hidden');
			$('.getdetails').addClass('hidden');
			//alert('1');
			/*checklistdata();
			$('#div_date_1').removeClass('hidden');
			$('#div_duration_1').removeClass('hidden');
			$('#txt_Date_crt_1').removeClass('hidden');*/
		} else {
			$('.batchdetails').addClass('hidden');
			$('.getdetails').removeClass('hidden');
			//alert('2');
			/*$('#txt_thcheck_Trainer').val('No');
			$('.statuscheck').addClass('hidden');			
			$('#docTable').html('');
			$('#docstable').addClass('hidden');
			
			$('#div_date_1').addClass('hidden');
			$('#div_duration_1').addClass('hidden');
			$('#txt_Date_crt_1').addClass('hidden');
			
			$('#div_date_2').addClass('hidden');
			$('#txt_Date_crt_2').addClass('hidden');
			
			$('#div_date_3').addClass('hidden');
			$('#txt_Date_crt_3').addClass('hidden');
			
			$('#div_date_4').addClass('hidden');
			$('#txt_Date_crt_4').addClass('hidden');
			 
			$('#div_date_5').addClass('hidden');
			$('#txt_Date_crt_5').addClass('hidden');*/
		}
		$('select').formSelect();
	});
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>