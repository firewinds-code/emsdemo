<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$statusdate = date('d');
//if(!($statusdate >='26'  && $statusdate<='30' &&  $_SESSION['__status_ah']==$_SESSION['__user_logid'])){
if (!($statusdate >= '21'  && $statusdate <= '30' &&  $_SESSION['__status_ah'] == $_SESSION['__user_logid'])) {
	$location = URL . 'Login';
	echo "<script>location.href='" . $location . "'</script>";
}

$old_client = $new_client = $move_date = $tcid_array = $remark = '';
$classvarr = "'.byID'";
$searchBy = '';
$client_name = '';
$cm_id = '';
$date = date("Y-m-d h:i:s");
$Loggin_location = clean($_SESSION["__location"]);
$account_head = clean($_SESSION['__user_logid']);
//$account_head='CE121621933';
$Query = 'select id,substatus from ryg_substatus_master;';
$myDB = new MysqliDb();
$remark_array = array();
$result = $myDB->query($Query);
foreach ($result as $lval) {
	$remark_array[$lval['id']] = $lval['substatus'];
}
$lQuery = 'select id,location from location_master;';
$myDB = new MysqliDb();
$location_array = array();
$lresult = $myDB->query($lQuery);
foreach ($lresult as $lval) {
	$location_array[$lval['id']] = $lval['location'];
}
if (isset($_POST['save_status'])) {
	/*echo "<pre>";
	print_r($_POST);
	echo "</pre>";*/
}

?>
<link rel="stylesheet" href="../Style/ryg_style.css">
<style>
	table.dataTable.row-border tbody td {
		white-space: normal !important;
		/*white-space: inherit !important;*/
	}
</style>
<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			"iDisplayLength": 10,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

				'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});


		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');

		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');
		$('#searchBy').change(function() {
			$('.byID').addClass('hidden');
			if ($(this).val() == 'By ID') {
				$('.byID').removeClass('hidden');
			}

		});
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">RYG Status Account Head</span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>RYG Status Account Head </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<div id="pnlTable">
					<div class="had-container pull-left row card">
						<div class="">
							<?php
							$query = "SELECT distinct(ep.EmployeeID),ncm.account_head,pd.EmployeeName,ryg.ryg_status,ryg_substatus,ryg_remark,rstatus,rsubstatus,rremark,ohstatus,ohsubstatus,ohremark,pd.location,cm.client_name,ncm.`process`,ncm.`sub_process`  from employee_map ep  inner join personal_details pd on ep.EmployeeID=pd.EmployeeID inner join new_client_master ncm on ncm.cm_id=ep.cm_id inner Join client_master cm on cm.client_id=ncm.client_name  left join ( select * from ryg_ah    where   Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE()) ) ryg on ep.EmployeeID=ryg.EmployeeID left JOIN  ( select EmployeeID,ryg_status as rstatus,ryg_substatus as rsubstatus,ryg_remark  as rremark from ryg_reportto where   Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE()) ) reportto  on ep.EmployeeID=reportto.EmployeeID  left JOIN  ( select EmployeeID,ryg_status as ohstatus,ryg_substatus as ohsubstatus,ryg_remark  as ohremark from ryg_oh where   Month(created_on)=MONTH(CURRENT_DATE()) and YEAR(created_on)=YEAR(CURRENT_DATE()) ) rygoh  on ep.EmployeeID=rygoh.EmployeeID where ep.emp_status='Active' and ncm.account_head=? and ep.EmployeeID!=?";
							$myDB = new MysqliDb();
							$conn = $myDB->dbConnect();
							$selectQ = $conn->prepare($query);
							$selectQ->bind_param("ss", $account_head, $account_head);
							$selectQ->execute();
							$result = $selectQ->get_result();
							// $result = $myDB->rawQuery($query);
							// $mysql_error = $myDB->getLastError();
							// $rowCount = $myDB->count;
							if ($result && $result->num_rows > 0) { ?>

								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<!--<th >SNo.</th>-->
											<th> Action</th>
											<th>Emp Id (Emp Name)</th>
											<th>AH Status</th>
											<th>AH Sub Status</th>
											<th>AH Remarks</th>
											<th>TL/OH Status</th>
											<th>TL Sub Status</th>
											<th>OH Sub Status</th>
											<!-- <th>OH Remarks</th>    -->
											<!--<th>TL RYG Status</th>-->

											<!-- <th>TL Remarks</th> -->
											<!--<th>Client</th>-->
											<th>Process</th>
											<th>Sub-Process</th>
											<!--<th>Site</th>-->
										</tr>
									</thead>
									<tbody id="emplist">
										<!--<input class='empclass hidden ' type='text' name='OHid'  id="OHid" value="<?php echo $_SESSION['__user_logid']; ?>" >-->
										<input class='empclass hidden ' type='text' name='AHid' id="AHid" value="<?php echo $account_head; ?>">
										<?php
										$i = 0;
										$j = 1;
										foreach ($result as $key => $data_array) {
											$ryg_location = '';
											$ryg_substatus = '';
											$rsubstatus = '';
											$ohsubstatus = '';
											if ($data_array['ryg_substatus'] != "") {
												$ryg_substatus = $remark_array[$data_array['ryg_substatus']];
											}
											if ($data_array['rsubstatus'] != "") {
												$rsubstatus = $remark_array[$data_array['rsubstatus']];
											}
											if ($data_array['ohsubstatus'] != "") {
												$ohsubstatus = $remark_array[$data_array['ohsubstatus']];
											}
											if ($data_array['location'] != "") {
												$ryg_location = $location_array[$data_array['location']];
											}

											$rcolorclass = "";
											$ohcolorclass = "";
											if ($data_array['rstatus'] == 'Red') {
												$rcolorclass = "rygred";
											} else
										if ($data_array['rstatus'] == 'Yellow') {
												$rcolorclass = "rygyellow";
											} else
										if ($data_array['rstatus'] == 'Green') {
												$rcolorclass = "ryggreen";
											}
											if ($data_array['ohstatus'] == 'Red') {
												$ohcolorclass = "rygred";
											} else
										if ($data_array['ohstatus'] == 'Yellow') {
												$ohcolorclass = "rygyellow";
											} else
										if ($data_array['ohstatus'] == 'Green') {
												$ohcolorclass = "ryggreen";
											}
											$i++;
											echo '<tr>';

										?>
											<td>
												<div class="input-field col s12 m12 right-align">
													<!--button type='button' name='save'  class="btn waves-effect waves-green rygsave" style="min-width:15px !important;" onclick="rygSave(<?php echo  $j; ?>)">save</button>	-->
													<span name='save' class=" waves-green rygsave <?php if ($data_array['ryg_remark'] != "") { ?>   savedclass <?php } ?> " style="min-width:15px !important;" onclick="rygSave(<?php echo  $j; ?>)" id="sid<?php echo  $j; ?>" title='save'><i class="fa fa-lg fa-save"></i></span>
												</div>
											</td>
											<td class="EmployeeID "><?php echo $data_array['EmployeeID']; ?> (<?php echo ucwords(strtolower($data_array['EmployeeName'])); ?>)<input class="empclass" id="empid<?php echo $j; ?>" type="hidden" name="EmployeeID[]" value="<?php echo $data_array['EmployeeID']; ?>"></td>
											<td>

												<select name="status[<?php echo  $j; ?>]" id="status<?php echo  $j; ?>" class="rygclass" onchange="getData(<?php echo  $j; ?>);">
													<option value="" <?php if ($data_array['ryg_status'] == '') {
																			echo "selected";
																		} ?>>Pending</option>
													<option value="Red" <?php if ($data_array['ryg_status'] == 'Red') {
																			echo "selected";
																		} ?>>Red</option>
													<option value="Yellow" <?php if ($data_array['ryg_status'] == 'Yellow') {
																				echo "selected";
																			} ?>>Yellow</option>
													<option value="Green" <?php if ($data_array['ryg_status'] == 'Green') {
																				echo "selected";
																			} ?>>Green</option>
												</select>
											</td>
											<td><select name="substatus[]" id="substatus<?php echo  $j; ?>" class="substatusclass">
													<?php
													if ($data_array['ryg_substatus'] != null && $data_array['ryg_substatus'] != "") {
													?>
														<option value="<?php echo $data_array['ryg_substatus']; ?>"><?php echo $ryg_substatus; ?></option>
													<?php
													} else { ?>
														<option value="">Select</option>
													<?php	}
													?>
												</select>
											</td>
											<td><textarea name="remarks" id="remarks<?php echo  $j; ?>" class="remarksclass" maxlength="255"><?php echo  $data_array['ryg_remark']; ?></textarea></td>
											<td class="rstatus"><span class="<?php echo $rcolorclass; ?>" <?php if ($data_array['rremark'] != "") {
																											?> title="<?php echo  $data_array['rremark']; ?>" <?php } else {
																																								?> title='Pending' <?php } ?>><i class="fa fa-lg fa-circle"></i></span><span style="padding-left:2px; " class="<?php echo $ohcolorclass; ?>" <?php if ($data_array['ohremark'] != "") {
																																																																												?>title="<?php echo  wordwrap($data_array['ohremark'], 30); ?>" <?php } else {
																																																																																												?> title='Pending' <?php } ?>><i class="fa fa-lg fa-circle"></i></span></td>
											<!--<td class="ohstatus"><?php echo  $data_array['ohstatus']; ?></td>-->
											<td class="rsubstatus"><?php echo  $rsubstatus; ?></td>
											<td class="rsubstatus"><?php echo $ohsubstatus; ?></td>
											<!--<td class="rremark"><?php echo  $data_array['ohremark']; ?></td>-->
											<!--<td class="rstatus"><?php echo  $data_array['rstatus']; ?></td>-->

											<!--<td class="rremark"><?php echo  $data_array['rremark']; ?></td>-->
										<?php


											//echo '<td class="Client">'.$data_array['client_name'].'</td>';
											echo '<td class="Process">' . $data_array['process'] . '</td>';
											echo '<td class="Sub-Process">' . $data_array['sub_process'] . '</td>';
											/*echo '<td class="Location">'.$ryg_location.'</td>';*/
											/*echo '</tr>';
										echo '<tr>';*/


											/*echo '</tr>';
									echo '</table>';*/
											echo '</tr>';
											$j++;
										}



										?>

									</tbody>
								</table>
								<!--<div class="input-field col s12 m12 right-align">
 				<button type="submit"  name="save_status" id="save_status" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green">Submit</button>
  			
						  </div>-->
						</div>
					<?php
							} else {
								echo "<script>$(function(){ toastr.error('Data not found '); }); </script>";
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
			var table = $('#myTable').DataTable();
			$('#update_status').click(function() {

				var validate = 0;
				var alert_msg = '';

				if ($('input.cb_child:checkbox:checked').length <= 0) {
					validate = 1;
					alert_msg += '<li> Check Atleast One Employee ....  </li>';
				} else {

					var checkedValue = null;
					var inputElements = document.getElementsByClassName('cb_child');
					var ahComment = document.getElementsByClassName('ahcomment');
					var empclass = document.getElementsByClassName('empclass');
					for (var i = 0; inputElements[i]; ++i) {
						if (inputElements[i].checked) {
							checkedValue = ahComment[i].value.trim();
							empname = empclass[i].value.trim();
							if (checkedValue == "") {
								validate = 1;
								alert_msg += '<li> Write the comment for ' + empname + '</li>';
								break;
							}
						}
					}

				}
				if (validate == 1) {


					$(function() {
						toastr.error(alert_msg)
					});
					return false;
				}
			});



		});

		function getData(id) {
			var sval = $('#status' + id + '').val();
			if (sval != "") {
				$.ajax({
						method: "GET",
						url: '../Controller/getRYG_substatus.php',
						data: {
							'getr': 'getremark',
							'rygstatus': sval
						}
					})
					.done(function(msg) {
						//	alert(msg);
						$('#substatus' + id + '').html(msg);
						// alert( "Data Saved: " + msg );
					});

			}
		}

		function rygSave(id) {
			var empid = $('#empid' + id + '').val();
			var AHid = $('#AHid').val();
			var remarks = $('#remarks' + id + '').val();
			var rlen = remarks.trim().length;
			var sval = $('#status' + id + '').val();
			if (sval == "") {
				alert('Please select status');
				return false;
			}
			var substatus = $('#substatus' + id + '').val();
			if (substatus == "") {
				alert('Please select sub-status');
				return false;
			}
			if (rlen < 30) {
				alert('Please enter mimimum 30 characters for remark');
				//$('#remarks'+id+'').css('border-color','red');
				$('#remarks' + id + '').focus();
				return false;
			} else {

				if (/[^a-zA-Z0-9\-,.: ]/.test(remarks)) {
					alert('Remark only contain alphanumeric characters ');
					// $('#remarks'+id+'').css('border-color','red');
					$('#remarks' + id + '').focus();
					return false;
				} else {
					//$('#remarks'+id+'').css('border-color','');
				}
			}
			if (sval != "" && substatus != "" && AHid != "") {
				if (remarks != "") {
					$.ajax({
						method: "POST",
						url: '../Controller/saveRYGAHstatus.php',
						data: {
							'saves': 'saves',
							'rygstatus': sval,
							'substatus': substatus,
							'rygsr': remarks,
							'empid': empid,
							'ah': AHid
						}
					}).done(function(msg) {
						$("#sid" + id + "").addClass('savedclass');
						alert("Data Saved: " + msg);
					});
				} else {
					alert('Please enter remark');
				}

			} else {
				alert('Please select status and sub status');
			}
		}
	</script>

	<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>