<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
require(ROOT_PATH . 'AppCode/nHead.php');


$createBy = $_SESSION['__user_logid'];
$EmployeeID = $_SESSION['__user_logid'];
$imsrc = URL . 'Style/images/agent-icon.png';

$uploadOk = 0;
if (isset($_POST["submit"]) && $_POST["EmployeeID"] != "") {
	$sqlResponse = "update mail_template set `name` = '" . $_POST['EmployeeName'] . "', `email`='" . $_POST['Email'] . "', `contact_no`='" . $_POST['Contactno'] . "', `doj`='" . $_POST['Doj'] . "', `designation`='" . $_POST['Designation'] . "', `immediate_manager`='" . $_POST['ImmediateManager'] . "', `assignment`='" . $_POST['Assignment'] . "' ,`linkdinLink`='" . $_POST['linkdinLink'] . "',flag=1, ModifiedBy = '" . $createBy . "', Modifiedon = now() where id = '" . $_POST['ID'] . "'";


	$myDB =  new MysqliDb();
	$Results = $myDB->rawQuery($sqlResponse);
	$mysql_error = $myDB->getLastError();

	$sqlResponse = "update contact_details set mobile= '" . $_POST['Contactno'] . "', `ofc_emailid`='" . $_POST['Email'] . "' where EmployeeID = '" . $_POST['EmployeeID'] . "'";


	$myDB =  new MysqliDb();
	$Results = $myDB->rawQuery($sqlResponse);
	$mysql_error = $myDB->getLastError();

	if (empty($mysql_error)) {
		$location = URL . 'View/mail_template.php';
		echo "<script>$(function(){ toastr.success('Data Save  Successfully '); }); </script>";
		echo "<script> window.location(" . $location . ")</script>";
	} else {
		echo "<script>$(function(){ toastr.warning('Data Not Save'); }); </script>";
	}
}


?>
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Mail Template</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->

		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Manage Mail Template</h4>


			<div class="schema-form-section row">
				<!--<h3 class="center">Filter</h3>-->
				<form method="POST" name="" action="<?php echo URL . 'View/mail_template.php'; ?>">
					<div class="row">


						<div class="input-field col s4 m4">
							<input type="text" id="EmployeeID" name="EmployeeID" readonly="true" required />
							<label for="EmployeeID" class="lable_item">Employee ID</label>
						</div>
						<div class="input-field col s4 m4">
							<input type="text" id="EmployeeName" name="EmployeeName" required />
							<label for="EmployeeName" class="lable_item">Employee Name</label>
						</div>
						<div class="input-field col s4 m4">
							<input type="text" id="Email" name="Email" required />
							<label for="Email" class="lable_item">Email</label>
						</div>
						<div class="input-field col s4 m4">
							<input type="text" id="Location" name="Location" readonly="true" required />
							<label for="Location" class="lable_item">Location</label>
						</div>
						<div class="input-field col s4 m4">
							<input type="text" class="datepicker" id="Doj" name="Doj" required />
							<label for="Doj" class="lable_item">DOJ</label>
						</div>
						<div class="input-field col s4 m4">
							<input type="text" id="Contactno" name="Contactno" maxlength="10" required />
							<label for="Contactno" class="lable_item">Contact No</label>
						</div>
						<div class="input-field col s4 m4">
							<input type="text" id="Designation" name="Designation" required />
							<label for="Designation" class="lable_item">Designation</label>
						</div>
						<div class="input-field col s4 m4">
							<input type="text" id="Assignment" name="Assignment" required />
							<label for="Assignment" class="lable_item">Assignment</label>
						</div>
						<div class="input-field col s4 m4">
							<input type="text" id="ImmediateManager" name="ImmediateManager" required />
							<label for="ImmediateManager" class="lable_item">Immediate Manager</label>
						</div>
						<div class="input-field col s4 m4">
							<input type="text" id="linkdinLink" name="linkdinLink" required pattern="https?://.+" />
							<label for="linkdinLink" class="lable_item">Linkdin Link</label>
						</div>

						<input type="hidden" name="ID" id="ID" />
					</div>

					<div class="row">
						<div class="col s12 center"><input type="submit" class="btn" name="submit" id="submit" value="Submit"></div>
					</div>
				</form>
				<?php
				if ($EmployeeID == '' && empty($EmployeeID)) {
					echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
					exit();
				}



				//$sqlConnect="select G1.empid as EmployeeID ,G1.name as EmployeeName,G1.email as Email,G1.location as Location,G1.doj as Doj,G1.contac_no as Contactno ,G1.designation as Designation,G1.assignment as Assignment ,G1.immediate_manager as 'Immediate Manager' from template_t1 G1 left join mail_template B on G1.empid =B.empid where B.empid is null";
				$sqlConnect = "select t1.id,empid as EmployeeID,name as EmployeeName,gender,location as Location,doj as Doj,assignment as Assignment,linkdinLink,t2.mobile as Contactno,case when t2.ofc_emailid is null then 'Email ID not created' when t2.ofc_emailid ='' then 'Email ID not created' else t2.ofc_emailid end as Email,
designation as Designation,immediate_manager as 'Immediate Manager' from mail_template t1 join contact_details t2 on t1.empid = t2.EmployeeID join employee_map t3 on t1.empid=t3.EmployeeID where t3.emp_status='Active' and ( t1.flag=0 or t1.flag=1);";

				$myDB = new MysqliDb();
				$result = $myDB->query($sqlConnect);
				//print_r($result);
				if ($result) {
				?>
					<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th class="hidden">ID</th>
								<th>EmployeeID</th>
								<th>Employee Name</th>
								<th>Email</th>
								<th>contac_no</th>
								<th>doj</th>
								<th>designation</th>
								<th>location</th>
								<th>immediate_manager</th>
								<th>assignment</th>
								<th class="hidden">Linkdin Link</th>
								<th>Action</th>


							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($result as $key => $value) {
								echo '<tr>';
								echo '<td class="hidden">' . $value['id'] . '</td>';
								echo '<td class="empid">' . $value['EmployeeID'] . '</td>';
								echo '<td class="name">' . $value['EmployeeName'] . '</td>';
								echo '<td class="email">' . $value['Email'] . '</td>';
								echo '<td class="contac_no">' . $value['Contactno'] . '</td>';
								echo '<td class="doj">' . $value['Doj'] . '</td>';
								echo '<td class="designation">' . $value['Designation'] . '</td>';
								echo '<td class="location">' . $value['Location'] . '</td>';
								echo '<td class="immediate_manager">' . $value['Immediate Manager'] . '</td>';
								echo '<td class="assignment">' . $value['Assignment'] . '</td>';
								echo '<td class="linkdinLink hidden">' . $value['linkdinLink'] . '</td>';

								echo '<td class="manage_item" style="text-align:center">';


								echo '<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped req" id="' . $value['id'] . '" EmployeeID="' . $value['EmployeeID'] . '"  EmployeeName="' . $value['EmployeeName'] . '" Email="' . $value['Email'] . '" Contactno="' . $value['Contactno'] . '" Doj="' . $value['Doj'] . '" Designation="' . $value['Designation'] . '" Location="' . $value['Location'] . '" ImmediateManager="' . $value['Immediate Manager'] . '" Assignment="' . $value['Assignment'] . '" linkdinLink="' . $value['linkdinLink'] . '" data-position="left" data-tooltip="Edit">ohrm_edit</i>';



								echo '</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				<?php
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
				/*,'copy'*/
				, 'pageLength'
			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('#submit').click(function() {

			var regex = /^[a-zA-Z\s]*$/;
			var name = $('#EmployeeName').val();
			if (regex.test(name)) {
				$('#EmployeeName').css('border-color', '');
			} else {
				$('#EmployeeName').css('border-color', 'red');
				alert('Enter character only in name');
				return false;
			}

			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if (!emailReg.test($('#Email').val())) {
				$('#Email').css('border-color', 'red');
				return false;
			} else {
				$('#Email').css('border-color', '');
			}

			var phoneno = /^\d{10}$/;
			if (($('#Contactno').val().match(phoneno))) {
				$('#Contactno').css('border-color', '');
			} else {
				$('#Contactno').css('border-color', 'red');
				return false;
			}

			var linkdinurl = /\b(?:(?:https?|ftp):\/\/|\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i;
			if (($('#linkdinLink').val().match(linkdinurl))) {
				$('#linkdinLink').css('border-color', '');
			} else {
				$('#linkdinLink').css('border-color', 'red');
				alert('Please enter a valid URL https://');
				return false;
			}

		});

	});
</script>


<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>
<script>
	$('.req').click(function() {
		var ID = $(this).attr("id");
		var EmployeeID = $(this).attr("EmployeeID");
		var EmployeeName = $(this).attr("EmployeeName");
		var Email = $(this).attr("Email");
		var Location = $(this).attr("Location");
		var Doj = $(this).attr("Doj");
		var Contactno = $(this).attr("Contactno");
		var Designation = $(this).attr("Designation");
		var Assignment = $(this).attr("Assignment");
		var linkdinLink = $(this).attr("linkdinLink");
		var ImmediateManager = $(this).attr("ImmediateManager");
		$("input[name='EmployeeID']").val(EmployeeID);
		$("input[name='EmployeeName']").val(EmployeeName);
		$("input[name='Email']").val(Email);
		$("input[name='Location']").val(Location);
		$("input[name='Doj']").val(Doj);
		$("input[name='Contactno']").val(Contactno);
		$("input[name='Designation']").val(Designation);
		$("input[name='Assignment']").val(Assignment);
		$("input[name='linkdinLink']").val(linkdinLink);
		$("input[name='ImmediateManager']").val(ImmediateManager);
		$("input[name='ID']").val(ID);

		$(".lable_item").addClass("active");
	});



	// Or with jQuery

	$('.datepicker').datepicker({
		dateFormat: 'yy-mm-dd'
	});
</script>