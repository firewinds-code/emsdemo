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
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;

$myDB = new MysqliDb();
$connn = $myDB->dbConnect();


$loginId = isset($_SESSION['__user_logid'])?$_SESSION['__user_logid']:0;
if (isset($_POST['submit'])) {
	
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {

		$loc_id = cleanUserInput($_POST['loc_id']);
		$new_location = cleanUserInput($_POST['new_location']);
		$client_id = cleanUserInput($_POST['client_id']);
		$process = cleanUserInput($_POST['process']);
		$current_rate = cleanUserInput($_POST['current_rate']);
		$vh = cleanUserInput($_POST['vh']);
		$svh = cleanUserInput($_POST['svh']);
		$fte_forcast = cleanUserInput($_POST['fte_forcast']);
		$fte_plan = cleanUserInput($_POST['fte_plan']);
		$rp_plan = cleanUserInput($_POST['rp_plan']);
		$fte_commit = cleanUserInput($_POST['fte_commit']);
		$rp_commit = cleanUserInput($_POST['rp_commit']);
		$fte_actuals = cleanUserInput($_POST['fte_actuals']);
		$rp_actuals = cleanUserInput($_POST['rp_actuals']);
		$mnth_year = cleanUserInput($_POST['mnth_year']);
		$model = cleanUserInput($_POST['model']);
		$mnth_yearArr = explode('-',$mnth_year);
		$tblName = 'revenue_master_'.$mnth_yearArr[1];

		$hiddenId = $_POST['hiddenId'];
		
		if ($hiddenId == '') {

			/* Check Duplicate Entries */
			$sqlQuery = "select * from $tblName where loc_id=? and new_location=? and client_id=? and process=? and model=? and mnth_year=?";
			$stmtDuplicate = $connn->prepare($sqlQuery);
			$stmtDuplicate->bind_param("ssssss", $loc_id,$new_location,$client_id,$process,$model,$mnth_year);
			if (!$stmtDuplicate) {
				echo "failed to run";
				die;
			}
			$stmtDuplicate->execute();
			$queryDup = $stmtDuplicate->get_result();
			$countDup = $queryDup->num_rows;
			/* Check Duplicate Entries */
			if($countDup > 0) {
				echo "<script>$(function(){toastr.error('Duplicate Entry'); }); </script>";
			}else{
				$insQry = "INSERT into $tblName(loc_id, new_location, client_id, process, current_rate, vh, svh, fte_forcast, fte_plan, rp_plan, fte_commit, rp_commit, fte_actuals, rp_actuals, mnth_year,model)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				$stmt = $connn->prepare($insQry);
				$stmt->bind_param("ssssssssssssssss", $loc_id, $new_location, $client_id,$process, $current_rate, $vh, $svh, $fte_forcast, $fte_plan, $rp_plan, $fte_commit, $rp_commit, $fte_actuals, $rp_actuals, $mnth_year,$model);
				if (!$stmt) {
					echo "failed to run";
					die;
				}
				$inst = $stmt->execute();
				$insertId = $connn->insert_id;
				echo "<script>$(function(){toastr.success('Inserted Successfully'); }); </script>";
			}
			
				
		} else {
			/* Check Duplicate Entries */
			$sqlQuery = "select * from $tblName where loc_id=? and new_location=? and client_id=? and process=? and model=? and mnth_year=? and id !=?";
			$stmtDuplicate = $connn->prepare($sqlQuery);
			$stmtDuplicate->bind_param("sssssss", $loc_id,$new_location,$client_id,$process,$model,$mnth_year,$hiddenId);
			if (!$stmtDuplicate) {
				echo "failed to run";
				die;
			}
			$stmtDuplicate->execute();
			$queryDup = $stmtDuplicate->get_result();
			$countDup = $queryDup->num_rows;
			/* Check Duplicate Entries */
			if($countDup > 0) {
				echo "<script>$(function(){toastr.error('Duplicate Entry'); }); </script>";
			}else{
				$updateQuery = "UPDATE $tblName SET loc_id=?, new_location=?, client_id=?, process=?, current_rate=?, vh=?, svh=?, fte_forcast=?, fte_plan=?, rp_plan=?, fte_commit=?, rp_commit=?, fte_actuals=?, rp_actuals=?, mnth_year=?,model=?,modifiedon=now() WHERE id=?";
				$stmt = $conn->prepare($updateQuery);
				$stmt->bind_param("ssssssssssssssssi", $loc_id, $new_location, $client_id,$process, $current_rate, $vh, $svh, $fte_forcast, $fte_plan, $rp_plan, $fte_commit, $rp_commit, $fte_actuals, $rp_actuals, $mnth_year,$model,$hiddenId);
				$stmt->execute();

				
				$resStmt = $stmt->get_result();
				if ($stmt->affected_rows === 1) {
					echo "<script>$(function(){toastr.success('Record Updated Successfully'); }); </script>";
				} else {
					echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
				}
			}
			
			
		}
	}
}


?>

<style>
	button.dt-button, div.dt-button, a.dt-button {
		color:gray;
		text-shadow:0 0 black;
		background-position: 0;
		background-size: 0;
		background-repeat: no-repeat;
		background-color: white;
		border: 1px solid #fff;
		margin: 0;
		padding: 0;
		cursor: pointer;
		border: 1px solid #fff;
		background-color:#fff;
		background-image:#fff !important;
		font-weight: 400;
	}
	
	.dataTables_filter {
	width: 50%;
	float: right;
	text-align: right;
	}
	.error {
		color: red;
	}

	#data-container {
		display: block;
		background: #2a3f54;

		max-height: 250px;
		overflow-y: auto;
		z-index: 9999999;
		position: absolute;
		width: 100%;

	}

	#data-container li {
		list-style: none;
		padding: 5px;
		border-bottom: 1px solid #fff;
		color: #fff;
	}

	#data-container li:hover {
		background: #26b99a;
		cursor: pointer;
	}

	.form-control:focus {
		border-color: #d01010;
		outline: 0;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(233, 102, 139, 0.6);

	}

	#overlay {
		position: fixed;
		top: 0;
		z-index: 100;
		width: 100%;
		height: 100%;
		display: none;
		background: rgba(0, 0, 0, 0.6);
	}

	.cv-spinner {
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.spinner {
		width: 40px;
		height: 40px;
		border: 4px #ddd solid;
		border-top: 4px #2e93e6 solid;
		border-radius: 50%;
		animation: sp-anime 0.8s infinite linear;
	}

	@keyframes sp-anime {
		100% {
			transform: rotate(360deg);
		}
	}

	.is-hide {
		display: none;
	}
</style>

<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Revenue Master</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Revenue Master</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
			<?php if(in_array($loginId,$authUserSite,true)){ ?>
				<div class="input-field col s12 m12" id="rpt_container">
					<form method="POST" action="#">
						<?php $_SESSION["token"] = csrfToken();	?>
						<input type="hidden" name="token" value="<?php echo $_SESSION["token"]; ?>">
						<input type="hidden" name="hiddenId" id="hiddenId">
						<div class="input-field col s4 m4">
							<select id="loc_id" name="loc_id" class="form-control" required>
								<option value="">----Select----</option>
								<?php
								$sqlBy = 'select id,location from location_master';
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
							<label for="location" class="Active dropdown-active active">Location</label>
						</div>
						<div class="input-field col s4 m4">
							<div class="form-group">
								<input type="text" class="form-control" name="new_location" id="new_location" required />
								<label title="Select new_location" for="new_location" class="Active">New Location</label>
							</div>
						</div>
						<div class="input-field col s4 m4">
							<select id="client_id" name="client_id" class="form-control" required onchange="changeClient(this.value,loc_id.value,'')">
								<option value="">----Select----</option>
								<?php
								$sqlBy = 'select client_id,client_name from client_master';
								$myDB = new MysqliDb();
								$resultBy = $myDB->rawQuery($sqlBy);
								$mysql_error = $myDB->getLastError();
								if (empty($mysql_error)) {
									foreach ($resultBy as $key => $value) {
										echo '<option value="' . $value['client_id'] . '"  >' . $value['client_name'] . '</option>';
									}
								}
								?>
							</select>
							<label for="location" class="Active dropdown-active active">Client</label>
						</div>
						<div class="input-field col s4 m4">
							<select id="process" name="process" class="form-control" required>
							</select>
							<label for="process" class="Active dropdown-active active">Process</label>
						</div>
						<div class="input-field col s4 m4">
							<select id="model" name="model" class="form-control" required>
								<option value="">----Select----</option>
								<option value="CPM">CPM</option>
								<option value="FTE">FTE</option>
								<option value="CPM/FTE">CPM/FTE</option>
								
							</select>
							<label for="model" class="Active dropdown-active active">Model</label>
						</div>		
						<div class="input-field col s4 m4">
							<div class="form-group">
								<input type="number" class="form-control" name="current_rate" id="current_rate" required />
								<label title="Select current_rate" for="current_rate" class="Active">Current Rate</label>
							</div>
						</div>
						
						<div class="input-field col s4 m4">
							<select id="vh" name="vh" class="form-control" required>
								<option value="">----Select----</option>
								<?php
									$sqlBy = 'select distinct VH,n.EmpName from new_client_master c left join EmpID_Name n on c.vh=n.empid where n.EmpName !="" ';
									$myDB = new MysqliDb();
									$resultBy = $myDB->rawQuery($sqlBy);
									$mysql_error = $myDB->getLastError();
									if (empty($mysql_error)) {
										foreach ($resultBy as $key => $value) {
											echo '<option value="' . $value['VH'] . '"  >' . $value['EmpName'] . '</option>';
										}
									}
								?>
							</select>
							<label for="vh" class="Active dropdown-active active">Vertical Head</label>
						</div>

						<div class="input-field col s4 m4">
							<select id="svh" name="svh" class="form-control" required>
								<option value="">----Select----</option>
								<?php
								$sqlBy = 'select EmpID,EmpName from (select account_head from new_client_master union
								select vh from new_client_master ) c left join EmpID_Name n on c.account_head=n.EmpID
								';
								$myDB = new MysqliDb();
								$resultBy = $myDB->rawQuery($sqlBy);
								$mysql_error = $myDB->getLastError();
								if (empty($mysql_error)) {
									foreach ($resultBy as $key => $value) {
										echo '<option value="' . $value['EmpID'] . '"  >' . $value['EmpName'] . '</option>';
									}
								}
								?>
							</select>
							<label for="svh" class="Active dropdown-active active">Sub Vertical Head</label>
						</div>
						<div class="input-field col s4 m4">
							<div class="form-group">
								<input type="number" class="form-control" name="fte_forcast" id="fte_forcast" required />
								<label title="Select fte_forcast" for="fte_forcast" class="Active">FTE Forecast</label>
							</div>
						</div>
						<div class="input-field col s4 m4">
							<div class="form-group">
								<input type="number" class="form-control" name="fte_plan" id="fte_plan" required />
								<label title="Select fte_plan" for="fte_plan" class="Active">FTE Plan</label>
							</div>
						</div>
						<div class="input-field col s4 m4">
							<div class="form-group">
								<input type="number" class="form-control" name="rp_plan" id="rp_plan" required />
								<label title="Select rp_plan" for="rp_plan" class="Active">R&P Plan</label>
							</div>
						</div>
						<div class="input-field col s4 m4">
							<div class="form-group">
								<input type="number" class="form-control" name="fte_commit" id="fte_commit" required />
								<label title="Select fte_commit" for="fte_commit" class="Active">FTE Commit</label>
							</div>
						</div>
						<div class="input-field col s4 m4">
							<div class="form-group">
								<input type="number" class="form-control" name="rp_commit" id="rp_commit" required />
								<label title="Select rp_commit" for="rp_commit" class="Active">R&P Commit</label>
							</div>
						</div>
						<div class="input-field col s4 m4">
							<div class="form-group">
								<input type="number" class="form-control" name="fte_actuals" id="fte_actuals" required />
								<label title="Select fte_actuals" for="fte_actuals" class="Active">FTE Actuals</label>
							</div>
						</div>
						<div class="input-field col s4 m4">
							<div class="form-group">
								<input type="number" class="form-control" name="rp_actuals" id="rp_actuals" required />
								<label title="Select rp_actuals" for="rp_actuals" class="Active">R&P Actuals</label>
							</div>
						</div>
						<div class="input-field col s4 m4">
							<input type="text" id="mnth_year_site" name="mnth_year" class="form-control" required autocomplete="off" />
							<label for="mnth_year_site" class="Active">Month</label>
						</div><br>
						<div class="input-field col s4 m4" style="float: right;">
							<div class="form-group">
								<input type="submit" name="submit" value="Submit" class="btn waves-effect waves-light" />
							</div>
						</div>						
					</form>
				</div>
				<?php } ?>
				<hr>
				<div>

				<div class="input-field col s4 m4">
					<input type="text" id="mnth_year_dt" name="mnth_year_dt" class="form-control" autocomplete="off" />
					<label for="mnth_year_dt" class="Active">Month</label>
				</div>
				<div class="input-field col s4 m3">
					<div class="form-group">
						<span name="submit123" value="submit123" class="btn waves-effect waves-light" onclick="getSiteMaster(mnth_year_dt.value)">Search</span>
					</div>
				</div>
				<div id="getIDTRdiv"></div>
				<br>
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
		
	});
</script>
<script>
	function getSiteMaster(dt) {
		// mnth_year_dt
		if(dt == ''){
			alert("Please select month");
			$('#mnth_year_dt').focus();
		}
		curntDateYear = dt;
		$.ajax({
			url: '../Controller/revenue_master_controller.php',
			type: 'GET',
			data: {
				dt: curntDateYear,
			},
			success: function(response) {
				//$('.defaultIDTR').hide();
				//$('.getIDTR').show();
				//console.log(response);
				$('#getIDTRdiv').html(response);
				console.log(response);
				$('#myTable').DataTable({
					dom: 'Bfrtip',
					"pageLength": 20,
					"scrollX":true,
					buttons: [{
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
						
					]
					// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
				});

			}
		});
	}

	function getClient(locId) {

		$('#locationSeatId').val(locId);
		$.ajax({
			url: '../Controller/get_client_controller.php',
			type: 'GET',
			data: {
				id: locId,
			},
			success: function(response) {

				$('#client_id').html(response);
			}
		});
	}
	$(document).ready(function() {
		var curntDateYear = '';
		var currentYear = (new Date).getFullYear();
		var currentMonth = (new Date).getMonth() + 1;

		if (currentMonth < 10) {
			currentMonth = '0' + currentMonth;
		}
		//alert(currentMonth);
		curntDateYear = currentYear + '-' + currentMonth;
		$('#mnth_year_dt').val(curntDateYear);
		getSiteMaster(curntDateYear);
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Can').trigger("click");

				$('#hiddenSeatId').val('');
				$('#sitemasterid').val('');
				$('#locationSeatId').val('');
				$('#client_id').val('');
				$('#process').val('');
				$('#seat').val('');
				$('#mnth_year').val('');$('#hiddenCostId').val('');
				$('#sitemasteridcost').val('');
				$('#costitem').val('');
				$('#txt_date').val('');
				$('#price').val('');

			}
		});
		

		// This code active label on value assign when any event trigger and value assign by javascript code.
		$("#myModal_content input,#myModal_content textarea, #myModal_content_view").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}
		});
		
		$('#txt_date,#mnth_year,#mnth_year_dt,#mnth_year_site').datetimepicker({
			timepicker: false,
			format: 'Y-m'
		});

		
		
	});
	function changeClient(id, locId, prcess) {
		if(locId !=''){
			const clientId = id;
			$.ajax({
				url: '../Controller/get_process_byid.php',
				type: 'GET',
				data: {
					clientId: clientId,
					prcess: prcess,
					locId: locId,
				},
				success: function(response) {
					console.log("response", response);
					//alert(response);
					$("#process").html(response);
				}
			});
		}else{
			alert("Please select location");
			$('#loc_id').focus();
		}
		
	}

	

	function editFunction(id,loc_id,new_location,client_id , process, model, current_rate, vh, svh, fte_forcast, fte_plan, rp_plan, fte_commit, rp_commit, fte_actuals, mnth_year,rp_actuals) {	
			//alert(client_id);		
			$('#hiddenId').val(id);
			$('#loc_id').val(loc_id);
			//$('#loc_id').addClass('Active');
			$('#new_location').val(new_location);
			
			$('#model').val(model);
			$('#current_rate').val(current_rate);
			$('#vh').val(vh);
			$('#svh').val(svh);
			$('#fte_forcast').val(fte_forcast);
			$('#fte_plan').val(fte_plan);
			$('#rp_plan').val(rp_plan);
			$('#fte_commit').val(fte_commit);
			$('#rp_commit').val(rp_commit);
			$('#fte_actuals').val(fte_actuals);
			$('#mnth_year_site').val(mnth_year);
			$('#rp_actuals').val(rp_actuals);
			$('#client_id').val(client_id);
			changeClient(client_id, loc_id, process);
			$('select').formSelect();
		}


	function deleteFunction(id,mnth) {
		var confm =  confirm("Are you sure to Delete?");
		if(confm){
			$.ajax({
			url: '../Controller/deleteRevenueMasterController.php',
			type: 'GET',
			data: {
				id: id,mnth:mnth,
			},
			success: function(response) {
				alert(response);
				location.reload();
			}
		});
		}
	}

	
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

