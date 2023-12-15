<?php
if (isset($_POST['btn_df_Save'])) {
	$mySql = new MysqliDb();
	$connn = $mySql->dbConnect();
	$emp = clean($_SESSION["__user_logid"]);
	$cliproc = "SELECT clientname,Process,sub_process FROM whole_details_peremp where EmployeeID = ? ";
	$selectQ = $connn->prepare($cliproc);
	$selectQ->bind_param("s", $emp);
	$selectQ->execute();
	$result_cliproc = $selectQ->get_result();
	$results_cliproc = $result_cliproc->fetch_row();
	$Client = clean($_SESSION['__user_client_ID']);
	$Process = clean($_SESSION['__user_process']);
	$SubProcess = clean($_SESSION['__user_subprocess']);

	if ($result_cliproc->num_rows > 0) {
		$Client = $results_cliproc[0];
		$Process = $results_cliproc[1];
		$SubProcess = $results_cliproc[2];
	}

	$EmployeeID = clean($_SESSION["__user_logid"]);
	$EmployeeName = clean($_SESSION["__user_Name"]);
	$DateOn = cleanUserInput($_POST['txtDateOn']);
	$Designation = clean($_SESSION["__user_Desg"]);

	$CandidateName =  cleanUserInput($_POST['txtCandidateName']);
	$CandidateNumber =   cleanUserInput($_POST['txtCandidateNumber']);
	$CandidateAddress =  cleanUserInput($_POST['txtCandidateAddress']);
	$Remark = cleanUserInput($_POST['txtRemark']);
	$CandidateLevel = cleanUserInput($_POST['txtCandidateLevel']);
	$createdby = clean($_SESSION["__user_logid"]);
	$ID = 0;
	$Agreed = 'Yes';

	// $sqlConnect = 'call sp_getRefID()';
	// $myDB=new MysqliDb();
	// $result=$myDB->rawQuery($sqlConnect);
	// $mysql_error = $myDB->getLastError();

	// if(count($result) > 0 && $result)
	// {
	// 	$ID=$result[0]['ID'];
	// }			

	$query_insert = 'INSERT INTO tbl_reference_reg_detail(`EmployeeID`,`EmployeeName`,`Designation`,`Client`,`Process`,`SubProcess`,`CandidateName`,`CandidateNumber`,`CandidateAddress`,`Remark`,`CandidateLevel`,`createdby`,`RefID`,`Agreed`,source) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,"Web");';
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	$insert = $conn->prepare($query_insert);
	$insert->bind_param("sss", $EmployeeID, $EmployeeName, $Designation, $Client, $Process, $SubProcess, addslashes($CandidateName), $CandidateNumber, $CandidateAddress, $Remark, $CandidateLevel, $createdby, $ID, $Agreed);
	$insert->execute();
	$flag = $insert->get_result();
	if ($insert->affected_rows === 1) {
		// $flag =  $myDB->query($query_insert);
		// $myError = $myDB->getLastError();
		// if (empty($myError)) {


		echo "<script>$(function(){ toastr.success('Candidate Register with us successfully') }); </script>";


		// $url= 'http://192.168.220.35/outcallhiring/admin/wb_serve_as_cleint.php?mob='.urlencode($CandidateNumber).'&name='.urlencode($CandidateName).'&ds=EMS';

		// $curl = curl_init();
		// curl_setopt($curl, CURLOPT_URL, $url);
		// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($curl, CURLOPT_HEADER, false);
		// $data = curl_exec($curl);
		// curl_close($curl);
	} else {
		//echo "<script>$(function(){ toastr.error('Reference not registered. Some error occurred...') }); </script>";
		echo "<script>$(function(){ toastr.error('Reference not registered. Some error occurred...') }); </script>";
	}
}
?>
<script>
	$(document).ready(function() {
		$('#txtDateOn').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			minDate: '+1970/01/01'
		});
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
	});
</script>
<div class="form-div" style="margin-top: 45px;">
	<!-- Header for Form If any -->
	<h4>Reference Registration</h4>
	<!-- Form container if any -->
	<div class="schema-form-section row">

		<div class="input-field col s6 m6" style="height: 90px;">
			<input type="text" id="txtCandidateName" name="txtCandidateName" />
			<label for="txtCandidateName">Candidate Name</label>
		</div>

		<div class="input-field col s6 m6" style="height: 90px;">
			<input type="text" id="txtCandidateNumber" name="txtCandidateNumber" maxlength="10" />
			<label for="txtCandidateNumber">Candidate Number</label>

		</div>

		<div class="input-field col s6 m6 hidden">
			<input type="text" id="txtDateOn" name="txtDateOn" readonly="true">
			<label for="txtDateOn">Expected Interview Date</label>
		</div>

		<div class="input-field col s6 m6">

			<select id="txtCandidateLevel" name="txtCandidateLevel">
				<option value="NA">--Select--</option>
				<option>Customer Support Associate</option>
				<option>Support</option>

			</select>
			<label for="txtCandidateLevel" class="active-drop-down active">Candidate Level</label>
		</div>

		<div class="input-field col s6 m6 hidden">
			<textarea id="txtCandidateAddress" name="txtCandidateAddress" class="materialize-textarea"></textarea>
			<label for="txtCandidateAddress">Candidate Address</label>
		</div>

		<div class="input-field col s6 m6 hidden">
			<textarea id="txtRemark" name="txtRemark" class="materialize-textarea"></textarea>
			<label for="txtRemark">Remark (If Any)</label>
		</div>

		<div id="divdisc">
			<div class="input-field col s12 m12">
				<br />
				<div style="overflow: auto;">
					<fieldset>
						<legend></legend>

						<p style="background-color: -moz-any; text-align: justify; font-family: verdana; font-size: 11pt;">
							The person refered above is known to me and i have check his interest in types of job we offered and he/she may be call telephonically for the same. </p>
					</fieldset>

				</div>
			</div>

			<div class="col s6 m6 right-align">
				<br />
				<input type='checkbox' name="Accept" id="Accept">
				<label for="Accept">Accept</label>
			</div>
		</div>

		<div id="divbtn" class="input-field col s6 m6 right-align">
			<input type="submit" class="btn waves-effect waves-green" value="Submit" id="btn_df_Save" name="btn_df_Save" />
		</div>
		<!--<div class="col s12 m12 "> 
		           <div class=" col s12 m12" style='border:1px solid;border-color:#121212; height: 400px;'>	   
		            <img src="<?php echo URL; ?>Style/images/Zomato Refferal.JPG" style='height: 390px; width:100%;' > 
                   </div>

		      </div>-->


		<?php
		if (false) {
			$myDB = new MysqliDb();
			$conn = $myDB->dbConnect();
			$emp = clean($_SESSION['__user_logid']);
			$Getinfo = "SELECT CandidateName, CandidateNumber, CandidateAddress, createdon FROM tbl_reference_reg_detail where EmployeeID = ? ;";
			$selectQ = $conn->prepare($Getinfo);
			$selectQ->bind_param("s", $emp);
			$selectQ->execute();
			$chk_task = $selectQ->get_result();

			$counter = 0;
			$my_error = $myDB->getLastError();
			if ($chk_task->num_rows > 0 && $chk_task) {

				$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><div class=""><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';

				foreach ($chk_task as $key_kl => $value_kl) {
					if ($key_kl == 'DateOn') {
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Expected Interview Date</th>';
					} else {
						$table .= '<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">' . $key_kl . '</th>';
					}
				}
				$table .= '</tr>';
				$table .= '</thead><tbody>';
				foreach ($chk_task as $key => $value) {
					$table .= '<tr>';
					foreach ($value as $key_kl => $value_kl) {
						$table .= '<td>' . $value_kl . '</td>';
					}
					$table .= '</tr>';
				}
				$table .= '</tbody></table></div></div>';
				echo $table;
			} else {
				echo "<script>$(function(){ toastr.error('No data found') }); </script>";
			}
		}
		?>
		<div class="alert col-sm-12 hidden" style="margin: 0px;">
			<p style="font-size: 10pt; font-family: 'Verdana',sans-serif; color: #093a42;"><b>Referral Bonanza Scheme is running from 17<sup>th</sup> to 24<sup>th</sup> Mar&rsquo;18</b><br /><br />&bull; Refer your friend or family member within above mention period and Get <span class="infinite_ammount"><b>Rs.1000/-</b></span> per employee as a reference incentive.<br />&bull; The Amount will be paid in two installments i.e. <span class="infinite_ammount"><b>Rs. 500/-</b></span> on <b>25<sup>th</sup> Apr&rsquo;18</b> &amp; <span class="infinite_ammount"><b>Rs. 500/-</b></span> on <b>25<sup>th</sup> May&rsquo;18</b>.<br />&bull; Referred employee status should be Active at the time of Amount disbursement.<br /><br /><b>**Offer is valid up to AM level, across departments.</b></p>
		</div>

	</div>
	<!--Form container End -->
</div>

<script>
	$(document).ready(function() {
		$('#divbtn').addClass('hidden');
		$('#divdisc').addClass('hidden');
		$('#Accept').change(function() {
			//alert('hii');
			//

			if ($(this).prop('checked')) {
				//alert('chk');
				//$('#btn_df_Save').removeClass('hidden');
				$('#divbtn').removeClass('hidden');
			} else {
				//alert('not');
				$('#divbtn').addClass('hidden');
			}
		});

		$('#txtCandidateNumber').keydown(function(event) {
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

		$('#txtCandidateNumber').keyup(function() {
			if ($('#txtCandidateNumber').val().length == 10) {
				var number = $('#txtCandidateNumber').val();
				//alert(number);
				jQuery.ajax({
					url: <?php echo '"' . URL . '"'; ?> + "Controller/number_validation.php?number=" + number
				}).done(function(data) {
					//alert(data);
					if (data == 0) {
						$('#txtCandidateNumber').parent('.select-wrapper').find('.select-dropdown').addClass('has-error');
						if ($('#stxtCandidateNumber').length == 0) {
							$('<span id="stxtCandidateNumber" class="help-block">Allready exists</span>').insertAfter('#txtCandidateNumber');

							$('#divbtn').addClass('hidden');
							$('#divdisc').addClass('hidden');
							//alert('Number Already Exist. Please Try Another');
						} else {
							$('#stxtCandidateNumber').text('Allready exists');
							$('#divbtn').addClass('hidden');
							$('#divdisc').addClass('hidden');
						}
						/*validate=1;
						return false;*/
					} else {
						//alert('');
						$('#divdisc').removeClass('hidden');
						$('#Accept').prop('checked', false);
						$('#stxtCandidateNumber').empty();


						//$('<span id="stxtCandidateNumber" class="help-block">dfdf</span>').insertAfter('#txtCandidateNumber');
					}
				});
			} else {
				$('#divbtn').addClass('hidden');
				$('#divdisc').addClass('hidden');
			}
		});


		$('#btn_df_Save').on('click', function() {
			var validate = 0;
			var alert_msg = '';

			/*if($('#txtDateOn').val()=='' || $('#txtDateOn').val()=='NA')
		        {
					$('#txtDateOn').addClass('has-error');
					validate=1;
					if($('#stxtDateOn').length == 0)
					{
					   $('<span id="stxtDateOn" class="help-block">Expected Interview Date is mandatory.</span>').insertAfter('#txtDateOn');
					}
				}*/
			if ($('#txtCandidateName').val() == '' || $('#txtCandidateName').val() == 'NA') {
				$('#txtCandidateName').addClass('has-error');
				validate = 1;
				if ($('#stxtCandidateName').length == 0) {
					$('<span id="stxtCandidateName" class="help-block">Candidate Name can\'t be blank.</span>').insertAfter('#txtCandidateName');
				}
			}
			/*if($('#txtCandidateLevel').val()=='' || $('#txtCandidateLevel').val()=='NA')
		        {
					$('#txtCandidateLevel').addClass('has-error');
					validate=1;
					alert_msg+='<li> Candidate Level should be a valid mobile number </li>';
				}*/
			/*if($('#txtCandidateAddress').val()=='' || $('#txtCandidateAddress').val()=='NA')
		        {
					$('#txtCandidateAddress').addClass('has-error');
					validate=1;
					alert_msg+='<li> Candidate Address should be a valid mobile number </li>';
				}*/
			if ($('#txtCandidateNumber').val() == '' || $('#txtCandidateNumber').val().length < 10 || $('#txtCandidateNumber').val().length > 10) {
				$('#txtCandidateNumber').addClass('has-error');
				validate = 1;
				if ($('#stxtCandidateNumber').length == 0) {
					$('<span id="stxtCandidateNumber" class="help-block">Candidate Number should be a valid mobile number.</span>').insertAfter('#txtCandidateNumber');
				}
			}
			if ($('#txtCandidateLevel').val() == 'NA') {
				$('#txtCandidateLevel').addClass('has-error');
				validate = 1;
				if ($('#spantxtCandidateLevel').length == 0) {
					$('<span id="spantxtCandidateLevel" class="help-block">Please select candidate level</span>').insertAfter('#txtCandidateLevel');
				}
			}
			/*if($('#txtRemark').val()=='' || $('#txtRemark').val()=='NA' )
		        {
					$('#txtRemark').addClass('has-error');
					validate=1;
					alert_msg+='<li> Remark should be a valid mobile number </li>';
				}*/
			if (validate == 1) {
				return false;
			}

		});
	});
</script>