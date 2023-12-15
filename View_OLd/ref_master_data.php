<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Only for user type administrator
$user_type = clean($_SESSION['__user_type']);
$user_logid = clean($_SESSION['__user_logid']);

if ($user_type != 'ADMINISTRATOR') {
	$location = URL . 'Error';
	header("Location: $location");
	exit();
}

// Global variable used in Page Cycle
$alert_msg = $_ITID = $_HRID = $_ReportsTo = $_Stipend = '';
$_StipendDays = 0;

// Trigger Button-Save Click Event and Perform DB Action
if (isset($_POST['txt_from']) && $_POST['hid_Ref_ID'] == "") {
	//echo "add";
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$txt_Ref_Name = cleanUserInput($_POST['txt_Ref_Name']);
		$_Scheme = (isset($txt_Ref_Name) ? $txt_Ref_Name : null);
		//$_ach=(isset($_POST['txt_cm_id'])? $_POST['txt_cm_id'] : null);
		//$_RefAmt=(isset($_POST['txt_RefAmount'])? $_POST['txt_RefAmount'] : null);
		$txt_from = cleanUserInput($_POST['txt_from']);
		$_from = (isset($txt_from) ? $txt_from : null);
		$txt_to = cleanUserInput($_POST['txt_to']);
		$_to = (isset($txt_to) ? $txt_to : null);


		$createBy = $user_logid;

		/*$Insert='CALL sp_getRefID1("'.$_from.'","'.$_to.'")';
		$myDB=new MysqliDb();
		$result=$myDB->rawQuery($Insert);
		$mysql_error = $myDB->getLastError();
		
		if(count($result) > 0 && $result)
		{
			echo "<script>$(function(){ toastr.error('Reference Scheme Date Already Exist, Please use another date'); }); </script>";		
			
		}
		else
		{*/
		$Insert = 'CALL sp_insert_RefScheme("' . trim($_Scheme) . '","' . $_from . '","' . $_to . '","' . $createBy . '")';
		$myDB = new MysqliDb();
		$myDB->rawQuery($Insert);
		$error = $myDB->getLastError();
		if (empty($error)) {
			if ($myDB->count > 0) {
				$sqlConnect = "select cm_id,amount,1st_pay,2nd_pay,window_month from client_ref_master where amount>0";
				$myDB = new MysqliDb();
				$result = $myDB->rawQuery($sqlConnect);
				$error = $myDB->getLastError();
				if (empty($error)) {
					if (count($result) > 0) {
						foreach ($result as $key => $value) {
							$_cm_id = $value['cm_id'];
							$_amount = $value['amount'];
							$_fpay = $value['1st_pay'];
							$_spay = $value['2nd_pay'];
							$_wmonth = $value['window_month'];

							$Insert = 'CALL sp_Insert_RefAmountMaster("' . $_cm_id . '","' . $_amount . '","' . $_fpay . '","' . $_spay . '","' . $_wmonth . '","' . $createBy . '")';
							#echo($Insert);
							$myDB = new MysqliDb();
							$myDB->rawQuery($Insert);
							$error = $myDB->getLastError();
						}
					}
				}
				echo "<script>$(function(){ toastr.success('Reference Scheme Added Successfully'); }); </script>";
			} else {
				echo "<script>$(function(){ toastr.error('Reference Scheme Not Added, May be Duplicate Entry Found check manualy'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Error : Reference Scheme not Added. Some error:::'); }); </script>";
		}
		//}
	}
}

// Trigger Button-Edit Click Event and Perform DB Action
if (isset($_POST['txt_from']) && $_POST['hid_Ref_ID'] != "") {	//echo "edit";
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$DataID = cleanUserInput($_POST['hid_Ref_ID']);
		$txt_Ref_Name = cleanUserInput($_POST['txt_Ref_Name']);
		$_Name = (isset($txt_Ref_Name) ? $txt_Ref_Name : null);
		$txt_from = cleanUserInput($_POST['txt_from']);
		$_from = (isset($txt_from) ? $txt_from : null);
		$txt_to = cleanUserInput($_POST['txt_to']);
		$_to = (isset($txt_to) ? $txt_to : null);

		$ModifiedBy = clean($_SESSION['__user_logid']);

		$Update = 'call sp_Update_RefScheme("' . $DataID . '","' . trim($_Name) . '","' . $_from . '","' . $_to . '","' . $ModifiedBy . '")';
		$myDB = new MysqliDb();
		if (!empty($DataID) || $DataID != '') {
			$myDB->rawQuery($Update);
			$mysql_error = $myDB->getLastError();
			if (empty($mysql_error)) {
				if ($myDB->count > 0) {
					echo "<script>$(function(){ toastr.success('Reference Scheme Updated Successfully'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Reference Scheme Not Updated, May be Duplicate Entry Found check manualy'); }); </script>";
				}
			} else {
				echo "<script>$(function(){ toastr.error('Error : Reference Scheme not Updated.'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First :: <code>(If Not Resolved then contact to technical person)</code>'); }); </script>";
		}
	}
}
?>

<link rel="stylesheet" href="../Style/jquery.datetimepicker.css" />
<script src="../Script/jquery.datetimepicker.full.min.js"></script>

<style>
	.ui-datepicker-calendar tbody {

		border: 1px solid #bdbdbd;

	}

	/* DatePicker Container */
	.ui-datepicker {
		width: 250px;
		height: auto;
		margin: 5px auto 0;
		font: 9pt Arial, sans-serif;
		-webkit-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);
		-moz-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);
		box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);
	}

	.ui-datepicker a {
		text-decoration: none;
	}

	/* DatePicker Table */
	.ui-datepicker table {
		width: 100%;
	}

	.ui-datepicker-header {
		background: #1daec5;
		color: #e0e0e0;
		font-weight: bold;
		-webkit-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, 1);
		-moz-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2);
		box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2);
		text-shadow: 1px -1px 0px #1daec5;
		filter: dropshadow(color=#337ab7, offx=1, offy=-1);
		line-height: 30px;
		border-width: 1px 0 0 0;
		border-style: solid;
		border-color: #20c9e4;
	}

	.ui-datepicker-title {
		text-align: center;
	}

	.ui-datepicker-prev,
	.ui-datepicker-next {
		display: inline-block;
		width: 30px;
		height: 30px;
		text-align: center;
		cursor: pointer;
		background-repeat: no-repeat;
		line-height: 600%;
		overflow: hidden;
	}

	.ui-datepicker-prev {
		float: left;
		background-position: center -30px;
	}

	.ui-datepicker-next {
		float: right;
		background-position: center 0px;
	}

	.ui-datepicker thead {
		background-color: #f7f7f7;
		background-image: -moz-linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
		background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #f7f7f7), color-stop(100%, #f1f1f1));
		background-image: -webkit-linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
		background-image: -o-linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
		background-image: -ms-linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
		background-image: linear-gradient(top, #f7f7f7 0%, #f1f1f1 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f7f7f7', endColorstr='#f1f1f1', GradientType=0);
		border-bottom: 1px solid #bbb;
	}

	.ui-datepicker th {
		text-transform: uppercase;
		font-size: 6pt;
		padding: 5px 0;
		color: #666666;
		text-shadow: 1px 0px 0px #fff;
		filter: dropshadow(color=#fff, offx=1, offy=0);
	}

	.ui-datepicker tbody td {
		padding: 0;
		border-right: 1px solid #bbb;
	}

	.ui-datepicker tbody td:last-child {
		border-right: 0px;
	}

	.ui-datepicker tbody tr {
		border-bottom: 1px solid #bbb;
	}

	.ui-datepicker tbody tr:last-child {
		border-bottom: 0px;
	}

	.ui-datepicker td span,
	.ui-datepicker td a {
		display: inline-block;
		font-weight: bold;
		text-align: center;
		width: 100%;
		line-height: 30px;
		color: #666666;
		text-shadow: 1px 1px 0px #fff;
		filter: dropshadow(color=#fff, offx=1, offy=1);
	}

	.ui-datepicker-calendar .ui-state-default {
		background: #ededed;
		background: -moz-linear-gradient(top, #ededed 0%, #dedede 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ededed), color-stop(100%, #dedede));
		background: -webkit-linear-gradient(top, #ededed 0%, #dedede 100%);
		background: -o-linear-gradient(top, #ededed 0%, #dedede 100%);
		background: -ms-linear-gradient(top, #ededed 0%, #dedede 100%);
		background: linear-gradient(top, #ededed 0%, #dedede 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dedede', GradientType=0);
		-webkit-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
		-moz-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
		box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
	}

	.ui-datepicker-calendar .ui-state-hover {
		background: #f7f7f7;
	}

	.ui-datepicker-calendar .ui-state-active {
		background: #6eafbf;
		-webkit-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);
		-moz-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);
		box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);
		color: #e0e0e0;
		text-shadow: 0px 1px 0px #4d7a85;
		filter: dropshadow(color=#4d7a85, offx=0, offy=1);
		border: 1px solid #55838f;
		position: relative;
		margin: 0px;
	}

	.ui-datepicker-unselectable .ui-state-default {
		background: #f4f4f4;
		color: #b4b3b3;
	}

	.ui-datepicker-calendar td:first-child .ui-state-active {

		margin-left: 0;
	}

	.ui-datepicker-calendar td:last-child .ui-state-active {

		margin-right: 0;
	}

	.ui-datepicker-calendar tr:last-child .ui-state-active {

		margin-bottom: 0;
	}

	.ui-datepicker-month,
	.ui-datepicker-year {
		color: #fff;
		font-weight: bold;
	}

	.ui-datepicker select.ui-datepicker-month,
	.ui-datepicker select.ui-datepicker-year {
		font-size: 16px;
		border-radius: 15px;
		border: 1px solid #0070d0;
		padding-left: 15px;
	}

	.ui-state-default,
	.ui-widget-content .ui-state-default,
	.ui-widget-header .ui-state-default {
		border: none;
	}

	.ui-state-highlight {
		color: #1c94c4 !important;
	}
</style>


<script>
	//contain load event for data table and other importent rand required trigger event and searches if any
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

				/*   {
				       extend: 'csv',
				       text: 'CSV',
				       extension: '.csv',
				       exportOptions: {
				           modifier: {
				               page: 'all'
				           }
				       },
				       title: 'table'
				   }, 						         
				   'print',*/
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
				, 'pageLength'
			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});
		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');

		/*$('#txt_Ref_Name').datetimepicker({
		timepicker:false,
		format:'d/m/Y',
		showDuration: false

		});*/
		/*$('#txt_1st_PayDate').datepicker({
                  maxDate: "+30d",
    			  minDate:"-30d",
           		  dateFormat:'yy-mm-dd',
           		  
           		  onSelect: function (dateStr) {
                      var max = $('#txt_1st_PayDate').datepicker('getDate'); // Get selected date
                      var start = $("#txt_to").datepicker("getDate");
                      var end = $("#txt_1st_PayDate").datepicker("getDate");
                      
                      if (start != null ) {
                          var days = (end - start) / (1000 * 60 * 60 * 24);
                         txtDays =days + 1;

                          

                          if (days < 0) {

                              alert("1st Pay Date should be greater then Scheme End Date");
                              $("#txt_1st_PayDate").val('');
                              return false;
                          }
                      }
                      else {
                          alert("Select Reference End Date First...");
                          $("#txt_1st_PayDate").val('');
                      }
					 $("#1stLvlId").addClass("Active");   
                  }
              });*/


		/*$('#txt_2nd_PayDate').datepicker({
                  maxDate: "+30d",
    			  minDate:"-30d",
           		  dateFormat:'yy-mm-dd',
           		  
           		  onSelect: function (dateStr) {
                      var max = $('#txt_2nd_PayDate').datepicker('getDate'); // Get selected date
                      var start = $("#txt_1st_PayDate").datepicker("getDate");
                      var end = $("#txt_2nd_PayDate").datepicker("getDate");
                      
                      if (start != null ) {
                          var days = (end - start) / (1000 * 60 * 60 * 24);
                         txtDays =days + 1;

                          

                          if (days < 0) {

                              alert("2nd Pay Date should be greater then 1st Pay Date");
                              $("#txt_2nd_PayDate").val('');
                              return false;
                          }
                        
                      }
                      else {
                          alert("Select 1st Pay Date First...");
                          $("#txt_2nd_PayDate").val('');
                      }
					 $("#2stLvlId").addClass("Active");   
                  }
              });*/

		$('#txt_from').datepicker({
			maxDate: "+30d",
			minDate: "-30d",
			dateFormat: 'yy-mm-dd'
		});


		$('#txt_to').datepicker({
			maxDate: "+120d",
			minDate: "-30d",
			dateFormat: 'yy-mm-dd',

			onSelect: function(dateStr) {
				var max = $('#txt_to').datepicker('getDate'); // Get selected date
				var start = $("#txt_from").datepicker("getDate");
				var end = $("#txt_to").datepicker("getDate");

				if (start != null) {
					var days = (end - start) / (1000 * 60 * 60 * 24);
					txtDays = days + 1;



					if (days < 0) {

						alert("To Date should be greater then From Date");
						$("#txt_to").val('');
						return false;
					}
				} else {
					alert("Select From Date First...");
					$("#txt_to").val('');
				}
				$("#AppToId").addClass("Active");
			}

		});



		$('#txt_RefAmount').keyup(function() {
			this.value = this.value.replace(/[^0-9.]/g, '');

			$('#txt_1st_PayAmt').val('');
			$('#txt_2nd_PayAmt').val('');


		});

		$('#txt_WindowDay').keyup(function() {
			this.value = this.value.replace(/[^0-9.]/g, '');


		});


		$('#txt_1st_PayAmt').keyup(function() {
			this.value = this.value.replace(/[^0-9.]/g, '');
			var amt1 = $('#txt_RefAmount').val(),
				amt2 = 0;

			if (amt1 == 'NA') {
				$('#txt_RefAmount').val('');
			}

			if (parseInt($('#txt_RefAmount').val().length) == 0 || parseInt($('#txt_1st_PayAmt').val()) > parseInt($('#txt_RefAmount').val())) {
				this.value = '';
				$('#txt_2nd_PayAmt').val('');
				alert('1st Pay Amount should be less than or equal to total pay amount');
			} else {
				amt2 = $('#txt_1st_PayAmt').val();
				//alert(parseInt(amt1) - parseInt(amt2))
				//$('#txt_2nd_PayAmt').value = parseInt(amt1) - parseInt(amt2);
				$('#txt_2nd_PayAmt').val(parseInt(amt1) - parseInt(amt2));
				//alert($('#txt_1st_PayDate').val());
			}




		});


		$('#txt_1st_PayAmt').focusout(function() {

			if ($('#txt_2nd_PayAmt').val() != 0 && $('#txt_2nd_PayAmt').val() != 'NULL') {
				$('#div_2ndAmt').removeClass('hidden');
			} else {
				$('#div_2ndAmt').addClass('hidden');
			}
		});


		$('#txt_1st_PayDate').change(function() {
			//alert($('#txt_1st_PayDate').val());
			if (parseInt($('#txt_2nd_PayAmt').val().length) > 0) {
				if ($('#txt_1st_PayDate').val() == 'Primary Payroll') {
					$('#txt_2nd_PayDate').val('Secondary Payroll');
				} else if ($('#txt_1st_PayDate').val() == 'Secondary Payroll') {
					$('#txt_2nd_PayDate').val('Primary Payroll');
				}
			}

		});

	});
</script>



<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Reference Scheme Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">
			<h4>Reference Scheme Details</h4>
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<!-- Form container if any -->
				<div class="schema-form-section row" id="ActiveValue">

					<div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">

						<!--<div class="col s12 m12">
		      <div class="input-field col s4 m4">
					<select id="txt_Client" name="txt_Client" required>
					<option value="NA">----Select----</option>	
				    <?php
					$sqlBy = 'select  distinct concat(clientname,"|",Process,"|",sub_process) as Process ,cm_id from whole_details_peremp';
					$myDB = new MysqliDb();
					$resultBy = $myDB->rawQuery($sqlBy);
					$mysql_error = $myDB->getLastError();
					if (empty($mysql_error)) {
						foreach ($resultBy as $key => $value) {
							echo '<option value="' . $value['cm_id'] . '"  >' . $value['Process'] . '</option>';
						}
					}
					?>
					</select>
					<label for="txt_Client" class="active-drop-down active">Process</label>
			    </div>
		      </div>-->
						<!--   <div id='refvel'>test</div>-->
						<div class="col s12 m12">
							<div class="input-field col s6 m6">
								<input type="text" id="txt_Ref_Name" name="txt_Ref_Name" required />
								<label for="txt_Ref_Name">Scheme Name</label>
							</div>



						</div>

						<div class="col s12 m12">
							<div class="input-field col s6 s6">
								<input type="text" id="txt_from" name="txt_from" required />
								<label for="txt_from">Applicable From</label>
							</div>

							<div class="input-field col s6 s6">
								<input type="text" id="txt_to" name="txt_to" required />
								<label for="txt_to" id="AppToId">Applicable To</label>
							</div>
						</div>

						<div class="input-field col s12 m12 right-align">
							<input type="hidden" class="form-control hidden" id="h_dtid" name="h_dtid" />
							<input type="hidden" class="form-control hidden" id="hid_Ref_ID" name="hid_Ref_ID" />
							<button type="button" name="btn_Client_Save" id="btn_Client_Save" class="btn waves-effect waves-green">Add</button>
							<button type="button" name="btn_Client_Edit" id="btn_Client_Edit" class="btn waves-effect waves-green hidden">Save</button>
							<button type="button" name="btn_Client_Can" id="btn_Client_Can" class="btn waves-effect modal-action modal-close waves-red close-btn hidden">Cancel</button>
						</div>

					</div>

					<!--Form element model popup End-->
					<!--Reprot / Data Table start -->
					<div id="pnlTable">
						<?php
						$sqlConnect = 'call sp_getRefSchemedata()';
						$myDB = new MysqliDb();
						$result = $myDB->rawQuery($sqlConnect);
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) {
						?>
							<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th class="hidden">ID</th>
										<th>Scheme Name</th>
										<th>Applicable From </th>
										<th>Applicable To</th>
										<th>CreatedOn</th>
										<th>Manage</th>
										<!--<th>Delete</th>-->
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($result as $key => $value) {
										echo '<tr>';
										echo '<td class="ID hidden">' . $value['ID'] . '</td>';
										echo '<td class="ref_schema">' . $value['ref_schema'] . '</td>';
										echo '<td class="ApplicableFrom">' . $value['ApplicableFrom'] . '</td>';
										echo '<td class="ApplicableTo">' . $value['ApplicableTo'] . '</td>';
										echo '<td class="CreatedOn">' . $value['CreatedOn'] . '</td>';

										echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['ID'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';

										//echo '<td class="manage_item" ><i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" onclick="javascript:return DeleteReq(this);" id="'.$value['ID'].'"   data-position="left" data-tooltip="Delete">ohrm_delete</i> </td>';

									?>

									<?php
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						<?php } ?>
					</div>
					<!--Reprot / Data Table End -->
				</div>
				<!--Form container End -->
			</div>
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(document).ready(function() {

		//Model Assigned and initiation code on document load	
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Client_Can').trigger("click");
			}
		});

		// This code for remove error span from all element contain .has-error class on listed events
		$(document).on("click blur focus change", ".has-error", function() {
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
		});

		// This code for cancel button trigger click and also for model close
		$('#btn_Client_Can').on('click', function() {

			$('#txt_Ref_Name').val('');
			$('#hid_Ref_ID').val('');
			//$('#txt_RefAmount').val('');	
			$('#txt_from').val('');
			$('#txt_to').val('');
			//$('#txt_1st_PayAmt').val('');	
			//$('#txt_1st_PayDate').val('');	
			//$('#txt_2nd_PayAmt').val('');	
			//$('#txt_WindowDay').val('');
			// $('#txt_2nd_PayDate').val('');		        

			$('#btn_Client_Save').removeClass('hidden');
			$('#btn_Client_Edit').addClass('hidden');
			$('#btn_Client_Can').addClass('hidden');


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
			// This code active label on value assign when any event trigger and value assign by javascript code.

			$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});
			$('select').formSelect();

		});

		// This code for submit button and form submit for all model field validation if this contain a required attributes also has some manual code validation to if needed.

		$('#btn_Client_Edit,#btn_Client_Save').on('click', function() {
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
					if ($('#' + spanID).length == 0) {
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

			/*if($('#txt_2nd_PayAmt').val() != 0 && $('#txt_2nd_PayAmt').val() != 'NULL')
        {
        	if($('#txt_2nd_PayDate').val()==null || $('#txt_2nd_PayDate').val()=='')
	        {
				$('#txt_2nd_PayDate').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
				if($('#stxt_2nd_PayAmt').length == 0)
					{
					   $('<span id="stxt_2nd_PayAmt" class="help-block">Required *</span>').insertAfter('#txt_2nd_PayDate');
					}
				validate=1;
			}
			
		}*/

			var DateFrom = $('#txt_from').val();
			//alert(DateFrom);
			var DateTo = $('#txt_to').val();
			var ID = '0';
			if ($('#hid_Ref_ID').val() != '') {
				//alert('edit');
				ID = $('#hid_Ref_ID').val();
			} else {
				//alert('add');

			}
			if (DateFrom != "" && DateTo != "") {
				jQuery.ajax({
					url: <?php echo '"' . URL . '"'; ?> + "Controller/ref_validation.php?todate=" + DateTo + "&fromdate=" + DateFrom + "&ID=" + ID
				}).done(function(data) {
					//alert(data);
					if (data == 0) {
						$('#txt_from').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
						if ($('#stxt_txt_from').length == 0) {
							$('<span id="stxt_txt_from" class="help-block">Allready exists</span>').insertAfter('#txt_from');
						}
						validate = 1;
						return false;
					} else {
						$('#indexForm').submit();
					}
				});
			}
			//alert(validate); 
			if (validate == 1) {
				//alert('dsd');
				if (alert_msg != "") {
					$(function() {
						toastr.error(alert_msg);
					});
				}
				return false;
			} else {
				//$('#indexForm').submit();
			}

		});


		//$('#div_2ndAmt').addClass('hidden');


	});


	// This code for trigger edit on all data table also trigger model open on a Model ID

	function EditData(el) {

		var tr = $(el).closest('tr');
		var ID = tr.find('.ID').text();
		var ref_schema = tr.find('.ref_schema').text();
		var ApplicableFrom = tr.find('.ApplicableFrom').text();
		var ApplicableTo = tr.find('.ApplicableTo').text();
		/*var Amount = tr.find('.Amount').text();
		var FPay_Amount = tr.find('.1st_Pay_Amount').text();
		var FPay_Date = tr.find('.1st_Pay_Date').text();
		var SPay_Amount = tr.find('.2nd_Pay_Amount').text();  
		var SPay_Date = tr.find('.2nd_Pay_Date').text();
		var winday = tr.find('.WinDay').text();*/

		$('#hid_Ref_ID').val(ID);

		$('#txt_Ref_Name').val(ref_schema);
		//$('#txt_RefAmount').val(Amount);	
		$('#txt_from').val(ApplicableFrom);
		$('#txt_to').val(ApplicableTo);
		/*$('#txt_1st_PayAmt').val(FPay_Amount);	
		$('#txt_1st_PayDate').val(FPay_Date);	
		$('#txt_2nd_PayAmt').val(SPay_Amount);*/
		//$('#txt_2nd_PayDate').val(SPay_Date);	
		//$('#txt_WindowDay').val(winday);	

		$('#btn_Client_Save').addClass('hidden');
		$('#btn_Client_Edit').removeClass('hidden');
		$('#btn_Client_Can').removeClass('hidden');

		$('#txt_Ref_Name').addClass('active');

		//$(this).siblings('label, i').addClass('active');
		//alert(SPay_Amount);

		// This code active label on value assign when any event trigger and value assign by javascript code.

		$("#ActiveValue input,#ActiveValue textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}
		});
		$('select').formSelect();
	}

	function DeleteReq(el) {
		if (confirm("Do you Want to Delete Request")) {
			$item = $(el);
			var tr = $(el).closest('tr');
			var ID = tr.find('.ID').text();
			alert(ID);
			$.ajax({
				url: "../Controller/deleteRefScheme.php?ID=" + $item.attr("ID"),
				success: function(result) {
					var data = result.split('|');

					toastr.info(data[1]);
					if (data[0] == 'Done') {

						$item.closest('td').parent('tr').remove();
					}
				}
			});
		}


	}

	// This code for trigger edit on Sub Proc data table also trigger model open on a Model ID

	function AddSubProc(el) {
		var tr = $(el).closest('tr');
		var client_id = tr.find('.cm_id').text();
		var client_name = tr.find('.client_name').text();
		var account_head = tr.find('.account_head').text();
		var dept_id = tr.find('.dept_id').text();
		var process = tr.find('.process').text();
		var oh = tr.find('.oh').text();
		var qh = tr.find('.qh').text();
		var th = tr.find('.th').text();
		$('#hid_Ref_ID').val(client_id);
		$('#txt_Scheme_Name').val(client_name);
		$('#txt_cm_id').val(account_head);
		$('#txt_RefAmount').val(dept_id);
		$('#txt_1st_PayAmt').val(process);
		$('#txt_1st_PayDate').val(oh);
		$('#txt_2nd_PayAmt').val(qh);
		$('#txt_Client_th').val(th);
		$('#txt_Client_subproc').val('');
		$('#btn_Client_Save').removeClass('hidden');
		$('#btn_Client_Edit').addClass('hidden');
		//$('#btn_Client_Can').removeClass('hidden');
		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
	}

	// This code for trigger edit on Proc data table also trigger model open on a Model ID

	function AddProc(el) {
		var tr = $(el).closest('tr');
		var client_id = tr.find('.cm_id').text();
		var client_name = tr.find('.client_name').text();
		var account_head = tr.find('.account_head').text();
		var dept_id = tr.find('.dept_id').text();
		$('#hid_Ref_ID').val(client_id);
		$('#txt_Scheme_Name').val(client_name);
		$('#txt_cm_id').val(account_head);
		$('#txt_RefAmount').val(dept_id);
		$('#txt_1st_PayAmt').val('');
		$('#txt_1st_PayDate').val('NA');
		$('#txt_2nd_PayAmt').val('NA');
		$('#txt_Client_th').val('NA');
		$('#txt_Client_subproc').val('');
		$('#btn_Client_Save').removeClass('hidden');
		$('#btn_Client_Edit').addClass('hidden');
		//$('#btn_Client_Can').removeClass('hidden');
		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
	}

	// This code for trigger del*t*

	// function ApplicationDataDelete(el, dtid) {
	// 	////alert(el);
	// 	var currentUrl = window.location.href;
	// 	var Cnfm = confirm("Do You Want To Delete This ");
	// 	if (Cnfm) {
	// 		var xmlhttp;
	// 		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
	// 			xmlhttp = new XMLHttpRequest();
	// 		} else { // code for IE6, IE5
	// 			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	// 		}
	// 		xmlhttp.onreadystatechange = function() {
	// 			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	// 				var Resp = xmlhttp.responseText;
	// 				//alert(Resp);
	// 				window.location.href = currentUrl;
	// 			}
	// 		}
	// 		xmlhttp.open("GET", "../Controller/DeleteClient.php?ID=" + el.id + "&dttid" + dtid, true);
	// 		xmlhttp.send();
	// 	}
	// }

	function isNumber(evt) {
		var iKeyCode = (evt.which) ? evt.which : evt.keyCode
		if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
			return false;
		return true;
	}
	/*function test(){
		 
	                        var todate=$('#txt_from').val();
							var fromate=$('#txt_to').val();	
							 alert(todate);
							jQuery.ajax({url: <?php echo '"' . URL . '"'; ?>+"Controller/ref_validation.php?todate="+todate+"&fromdate="+fromate, success: function(result){
								if(result='0')
									{
										
										alert('Exit');
									}
									else
									{
										alert('Insert');
										
									}
							}});
	}*/
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>