<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$status = '';
$batch_id = '';
$classvarr = "'.byID'";
$searchBy = '';
$msg = '';
if (isset($_POST['btnSave1'])) {
	//echo "<script>alert('loginID')</script>";
	$createBy = $_SESSION['__user_logid'];
	$FiletoUpload = '';
	if (is_array($_FILES)) {
		$count = 0;
		foreach ($_FILES['txt_doc_name_']['name'] as $name => $value) {
			$count++;
			if (is_uploaded_file($_FILES['txt_doc_name_']['tmp_name'][$name])) {
				$sourcePath = $_FILES['txt_doc_name_']['tmp_name'][$name];
				$targetPath = ROOT_PATH . "TraineeDocs/" . basename($_FILES['txt_doc_name_']['name'][$name]);
				if (move_uploaded_file($sourcePath, $targetPath)) {
					$ext = pathinfo(basename($_FILES['txt_doc_name_']['name'][$name]), PATHINFO_EXTENSION);
					$filename = $createBy . '_' . $_POST['text_trcheck_Batch'] . '_' . date("mdYhis") . '.' . $ext;
					$file = rename($targetPath, ROOT_PATH . 'TraineeDocs/' . $filename);
					if (file_exists(ROOT_PATH . 'TraineeDocs/' . $filename)) {
						$FiletoUpload = $filename;
					}
				}
			}
		}
	}
	//print_r($_POST);
	//die;			
	if (isset($_POST['cb'])) {
		$checked_arr = $_POST['cb'];
		$count_check = count($checked_arr);
		if ($count_check > 0) {


			foreach ($_POST['cb'] as $val) {
				$Counter = $val;
				$EmpID = $_POST['txt_EmployeeID_' . $Counter];
				$Status = $_POST['txt_Status_' . $Counter];
				$Remark = $_POST['txt_Remark_' . $Counter];
				$Score = $_POST['txt_Score_' . $Counter];
				$myDB = new MysqliDb();
				$chek_level = $myDB->query('call get_Cirtification_level("' . $EmpID . '")');
				$clevel = $chek_level[0]['cirtification_level'];

				$c_no_count = $chek_level[0]['no_of_Certification'];
				if ($clevel == 5 || $clevel > 5) {
					$clevel = 4;
				}
				if ($Status != 'NA' || $Status != 'Na') {
					if ($clevel == '1') {
						$myDB = new MysqliDb();
						$query = $myDB->rawQuery("UPDATE status_training SET  Status = 'YES', Certification_1 = '" . $Status . "',cirtification_level=2,Score ='" . $Score . "',FileName1 = '" . $FiletoUpload . "',modifiedon = now(),modifiedby = '" . $createBy . "',c_status = '" . $Status . "'  WHERE `EmployeeID` ='" . $EmpID . "'");
						$count4 = $myDB->count;
						$myDB = new MysqliDb();
						$insert_query1 = $myDB->rawQuery("INSERT INTO common_comment_table(EmployeeID,CreatedBy,Comment,type) VALUES('" . $EmpID . "','" . $createBy . "','" . $Remark . "','tr Level 1')");
						$count3 = $myDB->count;
						$myDB = new MysqliDb();
						$insert_query2 = $myDB->rawQuery("INSERT INTO Certification_Log(EmployeeID,Certification_level,DoneBy,DoneDate,score,file)VALUES('" . $EmpID . "','Level 1','" . $createBy . "',now(),'" . $Score . "','" . $FiletoUpload . "')");
						$count2 = $myDB->count;
						$myDB = new MysqliDb();
						$update_Query1 = $myDB->rawQuery("UPDATE status_table  SET Status =3,OutTraining=now(),ReportTo =(SELECT new_client_master.th FROM employee_map inner join new_client_master on new_client_master.cm_id=employee_map.cm_id where EmployeeID='" . $EmpID . "' limit 1) WHERE EmployeeID ='" . $EmpID . "'");
						$count1 = $myDB->count;


						if ($count4 == 1 && $count3 == 1 && $count2 == 1 && $count1 == 1) {
							echo "<script>$(function(){ toastr.success('Employee $EmpID Data Updated Successfully.'); }); </script>";
						} else {
							echo "<script>$(function(){ toastr.error('Record not updated.$mysql_error'); }); </script>";
						}
					} else
						
						if ($clevel > 1 && $c_no_count == $clevel) {

						$myDB = new MysqliDb();
						$save = 'call manage_status_tr' . $clevel . '("' . $EmpID . '","' . $Status . '","' . $Remark . '","' . $Score . '","' . $createBy . '","' . $FiletoUpload . '")';

						$resulti = $myDB->query($save);
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) {

							if (($c_no_count == $clevel) || $Status == 'NO' || $Status == 'No') {
								$myDB = new MysqliDb();
								$save = 'call manage_status_tr("' . $EmpID . '","' . $Status . '")';
								$resulti = $myDB->query($save);
								$mysql_error = $myDB->getLastError();
								if (empty($mysql_error)) {
									echo "<script>$(function(){ toastr.success('Employee $EmpID Data Updated Successfully.'); }); </script>";
								} else {
									echo "<script>$(function(){ toastr.error('Record not updated.$mysql_error'); }); </script>";
								}
							} else {


								if ($c_no_count != $clevel) {
									echo "<script>$(function(){ toastr.error('Employee $EmpID record not updated, has difference in level of certification.'); }); </script>";
								} else {
									echo "<script>$(function(){ toastr.error('Employee $EmpID record not updated.'); }); </script>";
								}
							}
						} else {
							echo "<script>$(function(){ toastr.success('Record not updated. $mysql_error'); }); </script>";
						}
					} else
						if ($c_no_count != $clevel) {
						echo "<script>$(function(){ toastr.error('Employee $EmpID record not updated, has difference in level of certification.'); }); </script>";
					} else {
						echo "<script>$(function(){ toastr.error('Employee $EmpID record not updated.'); }); </script>";
					}





					/*
						$myDB=new MysqliDb();
						echo $save='call manage_status_tr'.$clevel.'("'.$EmpID.'","'.$Status.'","'.$Remark.'","'.$Score.'","'.$createBy.'","'.$FiletoUpload.'")';
							echo "<br>";
						$resulti = $myDB->query($save);
						$mysql_error=$myDB->getLastError();	
						if(empty($mysql_error))
						{
							
							if(($c_no_count == $clevel)|| $Status =='NO' || $Status == 'No') 
							{
								$myDB=new MysqliDb();
								echo  $save='call manage_status_tr("'.$EmpID.'","'.$Status.'")';	
								echo "<br>";
								$resulti = $myDB->query($save);
								$mysql_error =$myDB->getLastError();
								if(empty($mysql_error))
								{
									echo "<script>$(function(){ toastr.success('Employee $EmpID Data Updated Successfully.'); }); </script>";	
								}
								else
								{
									echo "<script>$(function(){ toastr.error('Record not updated.$mysql_error'); }); </script>";
								}
								
								
							}
							else
							{
								
							
								if($c_no_count != $clevel)
								{
									echo "<script>$(function(){ toastr.error('Employee $EmpID record not updated, has difference in level of certification.'); }); </script>";	
								}
								else
								{
									echo "<script>$(function(){ toastr.error('Employee $EmpID record not updated.'); }); </script>";	
								}
								
							}
							
						}
						else
						{
							echo "<script>$(function(){ toastr.success('Record not updated. $mysql_error'); }); </script>";
						}*/
				} else {
					echo "<script>$(function(){ toastr.error('Record not updated. $EmpID has wrong Status selection.'); }); </script>";
				}
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
		$('.statuscheck').addClass('hidden');

	});
</script>
<style>
	textarea.materialize-textarea {
		overflow-y: hidden;
		padding: 0px 8px;
		resize: none;
		min-width: 200px;
	}
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Employee Trainer</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Manage Employee</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<div class="form-inline">

					<div class="input-field col s6 s6">
						<?php
						if ($_SESSION['training_approver'] != 'No') {
							$sqlBy = 'SELECT  distinct  batch_master.BacthID,batch_master.BacthName FROM employee_map  left outer join status_table on status_table.EmployeeID=employee_map.EmployeeID  left outer join status_training on employee_map.EmployeeID=status_training.EmployeeID  left outer join batch_master on batch_master.BacthID=status_training.BatchID left join new_client_master on new_client_master.cm_id =employee_map.cm_id  where status_training.EmployeeID !="' . $_SESSION['__user_logid'] . '" and status_table.Status=3 and new_client_master.client_name in (select client_id from training_master where Approver_id="' . $_SESSION['__user_logid'] . '") and employee_map.emp_status="Active" order by BacthName';
						} else {
							$sqlBy = 'SELECT  distinct  batch_master.BacthID,batch_master.BacthName FROM employee_map  left outer join status_table on status_table.EmployeeID=employee_map.EmployeeID  left outer join status_training on employee_map.EmployeeID=status_training.EmployeeID  left outer join batch_master on batch_master.BacthID=status_training.BatchID left join new_client_master on new_client_master.cm_id =employee_map.cm_id  where status_training.Trainer="' . $_SESSION['__user_logid'] . '" and status_training.EmployeeID !="' . $_SESSION['__user_logid'] . '" and status_table.Status=3 and employee_map.emp_status="Active" and new_client_master.client_name not in (select client_id from training_master) order by BacthName';
						}
						?>
						<select id="text_trcheck_Batch" name="text_trcheck_Batch">
							<option value="NA">----Select----</option>
							<?php
							$batch_id = 0;
							$myDB = new MysqliDb();
							$resultBy = $myDB->query($sqlBy);
							$error = $myDB->getLastQuery();
							if (count($resultBy) > 0 && $resultBy) {
								$selec = '';
								foreach ($resultBy as $key => $value) {
									if (isset($_POST['text_trcheck_Batch'])) {
										$batch_id = $_POST['text_trcheck_Batch'];
									}

									if ($batch_id == $value['BacthID']) {
										echo '<option value="' . $value['BacthID'] . '" selected >' . $value['BacthName'] . '</option>';
									} else {
										echo '<option value="' . $value['BacthID'] . '" >' . $value['BacthName'] . '</option>';
									}
								}
							}

							?>
						</select>
						<label for="text_trcheck_Batch" class="active-drop-down active">Select Batch Number</label>

					</div>
					<div class="input-field col s6 s6 btnslogs">
						<input type="submit" value="Click for batch details" name="btnSave" id="btnSave" class="btn waves-effect waves-green" />

					</div>
					<div class="col s12 m12 btnslogs-down">
						<hr />
					</div>
					<div class="col s12 m12 btnslogs-down">

						<div class="file-field input-field col s6 m6 ">
							<div class="btn red darken-1">
								<span>Training Document</span>
								<input type="file" name="txt_doc_name_[]" id="text_trcheck_FileBatch" />
							</div>
							<div class="file-path-wrapper">
								<input class="file-path validate" type="text">
							</div>
						</div>

						<div class="input-field col s6 s6 btnslogs right-align">
							<input type="submit" value="Submit" name="btnSave1" id="btnSave1" class="btn waves-effect waves-green" />

						</div>
					</div>

					<div id="pnlTable" class="col s12 m12 card">
						<?php
						if (isset($_POST['text_trcheck_Batch'])) {
							$batch_id = $_POST['text_trcheck_Batch'];
						} else {
							$batch_id = 'NA';
						}
						if ($batch_id != 'NA') {
							$sqlConnect = 'call get_trchecklist("' . $_SESSION['__user_logid'] . '",' . $batch_id . ')';
							$myDB = new MysqliDb();
							$result = $myDB->query($sqlConnect);
							//echo $sqlConnect;
							$error = $myDB->getLastError();
							if (count($result) > 0 && $result) { ?>

								<div class="flow-x-scroll">
									<table id="myTable1" class="data dataTable no-footer" cellspacing="0">
										<thead>
											<tr>
												<th><input type="checkbox" id="cbAll" name="cbAll" value="ALL"><label for="cbAll">EmployeeID</label></th>
												<th class="hidden">Employee ID</th>
												<th class="hidden">Employee ID</th>
												<th class="">Employee Name</th>
												<th class="">Retrain Staus</th>
												<th class="">Certification Level</th>
												<th class="">Total Certifications Levels</th>
												<th class="edit_info_header hidden">Certification Status </th>
												<th class="edit_info_header hidden">Remark</th>
												<th class="edit_info_header hidden">Marks</th>
												<th class="edit_view_header">Client</th>
												<th class="edit_view_header">Process</th>
												<th class="edit_view_header">Sub Process</th>

											</tr>
										</thead>
										<tbody>
											<?php
											$count = 0;
											foreach ($result as $key => $value) {
												$count++;
												echo '<tr>';
												echo '<td class="EmpId"><input type="checkbox" id="cb' . $count . '" class="cb_child" name="cb[]" value="' . $count . '"><label for="cb' . $count . '" style="color: #059977;font-size: 14px;font-weight: bold;}">' . $value['EmpID'] . '</label></td>';

												echo '<td class="EmpID hidden"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="' . $value['EmpID'] . '">' . $value['EmpID'] . '</a></td>';
												echo '<td class="FullName  ">' . $value['EmpName'] . '</td>';
												if ($value['retrain_flag'] == '1') {
													echo '<td class="cirtification_level  ">Re- Training</td>';
												} else {
													echo '<td class="cirtification_level  ">Training</td>';
												}

												echo '<td class="cirtification_level  ">' . $value['cirtification_level'] . '</td>';
												echo '<td class="levelno  ">' . $value['no_of_Certification'] . '</td>';

												echo '<td class="hidden" style="padding:0px;"><input style="min-width:200px;" type="text" name="txt_EmployeeID_' . $count . '" id="txt_EmployeeID_' . $count . '" readonly="true" value="' . $value['EmpID'] . '" /></td>';
												echo '<td class="edit_info_body hidden input-field"  style="padding:0px;"><select style="min-width:200px;" title="Status" name="txt_Status_' . $count . '" id="txt_Status_' . $count . '" onchange="javascript:return onchange_this(this);"><option>NO</option><option>YES</option></select></td>';
												echo '<td class="edit_info_body hidden input-field"  style="padding:0px;"><textarea name="txt_Remark_' . $count . '" id="txt_Remark_' . $count . '"  placeholder="Remark for ' . $value['EmpName'] . '" class="materialize-textarea"></textarea></td>';
												echo '<td class="edit_info_body hidden input-field"  style="padding:0px;"><input style="min-width:200px;"  title="Score" type="text" name="txt_Score_' . $count . '" id="txt_Score_' . $count . '" value="0" /></td>';



												echo '<td class="client_name edit_view">' . $value['client_name'] . '</td>';
												echo '<td class="process edit_view">' . $value['process'] . '</td>';
												echo '<td class="sub_process edit_view">' . $value['sub_process'] . '</td>';

												echo '</tr>';
											}
											?>
										</tbody>
									</table>

								</div>
								<script>
									$(function() {
										$(".btnslogs-down").show();
									});
								</script>
							<?php
							} else {

							?>
								<script>
									$(function() {
										$(".btnslogs-down").hide();
										toastr.info("Congratulations, all employees have been aligned to concern departments. " + <?php echo '"' . $error . '"'; ?>);

									});
								</script>
							<?php

							}
						} else {
							?>
							<script>
								$(function() {
									$(".btnslogs-down").hide();

								});
							</script>
						<?php
						}




						?>

					</div>
				</div>


			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(5000).fadeOut("slow");
		}

		$('#btnSave1').click(function() {
			var validate = 0;
			var alert_msg = '';

			if ($('input.cb_child:checkbox:checked').length <= 0) {
				validate = 1;
				toastr.info('Check Atleast On Employee');
			}

			$('input.cb_child:checkbox:checked').each(function() {

				if ($(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val() == '' || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length < 10 || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length > 100) {
					validate = 1;
				//toastr.info('Remark Can\'t be Empty or not less than 100 For any Checked Employee.');
					toastr.info('Remark can\'t be empty, it should be between 10 to 100 characters');
				}



			});
			if (validate == 1) {
				return false;
			}
		});

		$('#div_error').removeClass('hidden');
		$("input:checkbox").click(function() {
			if ($('input:checkbox:checked').length > 0) {
				checklistdata();
			} else {
				$('#txt_thcheck_Trainer').val('No');
				$('.statuscheck').addClass('hidden');
				$('#docTable').html('');
				$('#docstable').addClass('hidden');
			}
		});
		$('#div_error').removeClass('hidden');
		$("#cbAll").change(function() {
			$("input:checkbox").prop('checked', $(this).prop("checked"));
		});
		$("input:checkbox").change(function() {
			checkbox_check();
			if ($('input.cb_child:checkbox:checked').length > 0) {
				if ($('input.cb_child:checkbox:checked').length == $('input.cb_child:checkbox').length) {

					$("#cbAll").prop("checked", true);
				} else {
					$("#cbAll").prop("checked", false);
				}
			} else {
				$("#cbAll").prop("checked", false);
				$('#txt_thcheck_Trainer').val('NA');
				$('.statuscheck').addClass('hidden');
				$('#docTable').html('');
				$('#docstable').addClass('hidden');
			}
		});
		$('#text_trcheck_Batch').change(function() {
			$('.btnslogs').removeClass('hidden');
		});
		if ($('#text_trcheck_Batch').val() == '' || $('#text_trcheck_Batch').val() == 'NA' || $('#text_trcheck_Batch').val() == null) {
			$('.btnslogs').addClass('hidden');
		}

	});

	function checkbox_check() {
		$('.edit_info_body').addClass('hidden');
		$('.edit_info_header').addClass('hidden');
		$('.edit_view').removeClass('hidden');
		$('.edit_view_header').removeClass('hidden');
		if ($('input.cb_child:checkbox:checked').length > 0) {
			$('input.cb_child:checkbox:checked').each(function() {

				$(this).closest('tr').find('.edit_info_body').removeClass('hidden');
				$(this).closest('tr').find('.edit_view').addClass('hidden');
				$('.edit_info_header').removeClass('hidden');
				$('.edit_view_header').addClass('hidden');
			});

		}

	}

	function checklistdata(el) {

	}

	function onchange_this(el) {
		if ($(el).val() == 'YES') {

			$(el).closest('tr').children('td').css('background-color', '#b8e676');

		} else {
			$(el).closest('tr').children('td').css('background-color', '#ffc614');
		}
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>