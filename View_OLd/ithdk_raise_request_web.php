<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_SESSION)) {
	$user_logid = clean($_SESSION['__user_logid']);
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
	/* if($_SESSION["__user_type"]!='ADMINISTRATOR' &&  $user_login != 'CE10091236')
	{
		$location= URL.'Login';
		$_SESSION['MsgLg'] = "You are not allowed to acces this page." ;
		echo "<script>location.href='".$location."'</script>";
	} */
} else {
	$location = URL . 'Login';
	header("Location: $location");
}



$user_login = clean($_SESSION["__user_logid"]);
$username	= clean($_SESSION["__user_Name"]);
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">IT Help Desk</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">


		<!-- Sub Main Div for all Page -->
		<div class="form-div">
			<!-- Header for Form If any -->
			<h4>Raise Ticket</h4>
			<!-- Form container if any -->
			<div class="schema-form-section row">
				<!--Form element model popup start-->
				<div class="input-field col s12 m12">
					<div class="input-field col s6 m6">
						<Select name="categories" id="categories1" onchange="categorySelect(this.value)">
							<option value='na'>Select</option>
							<option value='Internal'>Internal</option>
							<option value='External'>External</option>
						</Select>
						<label for="categories" class="active-drop-down active">Categories</label>
					</div>
					<div class="col s6 m6" id="empInfo" style="display:none">

						<?php
						$sqlBy = "select mobile,ofc_emailid from contact_details where EmployeeID = ? limit 1";
						$selectQury = $conn->prepare($sqlBy);
						$selectQury->bind_param("s", $user_login);
						$selectQury->execute();
						$resultBy = $selectQury->get_result();

						// $resultBy = $myDB->rawQuery($sqlBy);
						// $mysql_error = $myDB->getLastError();
						if ($resultBy->num_rows > 0) {
							foreach ($resultBy as $key => $value) {
								//echo '<option value="'.$value['client_name'].'">'.$value['client_name'].'</option>';

								echo '<input type="text"  id="emailOff" name="emailOff" value = "' . $value['ofc_emailid'] . '"  >';
								echo '<input type="text"  id="mobileNo" name="mobileNo" value = "' . $value['mobile'] . '"  >';
							}
						}
						?>

					</div>

					<div class="input-field col s6 m6" id="divIssueType1" style="display:none">
						<Select name="issueType1" id="issueType1">
							<option value='na'>Select</option>
							<option value='Voice Issue'>Voice Issue</option>
							<option value='Reports Issue'>Reports Issue</option>
							<option value='NGUCC Not working'>NGUCC Not working</option>
							<option value='CCSP Not Working'>CCSP Not Working</option>
							<option value='CRM Not working'>CRM Not working</option>
							<option value='Power Issue'>Power Issue</option>
							<option value='Other'>Other</option>
						</Select>
						<label for="issueType1" class="active-drop-down active">Issue Type</label>
					</div>
					<div class="input-field col s6 m6" id="divIssueType2" style="display:none">
						<Select name="issueType2" id="issueType2">
							<option value='na'>Select</option>
							<option value='PRI Issue'>PRI Issue</option>
							<option value='ILL Issue'>ILL Issue</option>
							<option value='MPLS Issue'>MPLS Issue</option>
							<option value='P2P Link Issue'>P2P Link Issue</option>
							<option value='Other'>Other</option>
						</Select>
						<label for="issueType2" class="active-drop-down active">Issue Type</label>
					</div>
				</div>
				<div class="input-field col s12 m12">
					<div class="input-field col s6 m6">
						<Select name="location" id="location" required="required">
							<option value='na'>Select</option>
							<option value='Noida-Cogent C121'>Noida-Cogent C121</option>
							<option value='Noida-Cogent C100'>Noida-Cogent C100</option>
							<option value='Noida-Cogent A61'>Noida-Cogent A61</option>
							<option value='Bangalore Gopalna'>Bangalore Gopalna</option>
							<option value='Bangalore Hebbal'>Bangalore Hebbal</option>
							<option value='Vadodara'>Vadodara</option>
							<option value='Mangalore Raj Tower'>Mangalore Raj Tower</option>
							<option value='Mangalore Fortune'>Mangalore Fortune</option>
							<option value='Mangalore- Karuna Pride'>Mangalore- Karuna Pride</option>
							<option value='Meerut'>Meerut</option>
							<option value='Bareilly'>Bareilly</option>
							<option value='Mumbai Kalpataru'>Mumbai Kalpataru</option>
						</Select>
						<label for="location" class="active-drop-down active">Location</label>
					</div>
					<div class="input-field col s6 m6">
						<Select name="priorty" id="priorty">
							<option value='na'>Select</option>
							<option value='Low'>Low</option>
							<option value='Medium'>Medium</option>
							<option value='High'>High</option>

						</Select>
						<label for="priorty" class="active-drop-down active">Priorty</label>
					</div>
				</div>
				<div class="col s12 m12">
					<div class="col s6 m6">
						<label for="Client" class="active-drop-down active" style="color: #19aec4;">Client</label>
						<Select name="Client" id="Client" multiple>
							<option value='notSelect'>Select</option>
							<?php
							$sqlBy = "SELECT distinct c.client_name FROM ems.new_client_master nc inner join client_master c on nc.client_name=c.client_id left join client_status_master cm on nc.cm_id=cm.cm_id where cm.cm_id is null order by c.client_name";
							$resultBy = $myDB->rawQuery($sqlBy);
							$mysql_error = $myDB->getLastError();
							if (empty($mysql_error)) {
								foreach ($resultBy as $key => $value) {
									echo '<option value="' . $value['client_name'] . '">' . $value['client_name'] . '</option>';
								}
							}
							?>
						</Select>
					</div>
					<div class="input-field col s6 m6">
						<input type="text" id="process" name="process" title="Process Name" required="required">
						<label for="process"> Process Name</label>
					</div>
				</div>
				<div class="input-field col s12 m12">
					<div class="input-field col s6 m6">
						<input type="number" min="1" max="30" id="tat" name="tat" title="TAT" required="required">
						<label for="tat">TAT(Hour)</label>
					</div>
					<div class="input-field col s6 m6">
						<input type="number" min="1" max="999" id="totalAgents" name="totalAgents" title="Agent Impacted" required="required">
						<label for="totalAgents">Total Agents</label>
					</div>

				</div>
				<div class="input-field col s12 m12">

					<div class="input-field col s6 m6">
						<input type="number" min="1" max="999" id="agentImpacted" name="agentImpacted" title="Agent Impacted" required="required">
						<label for="agentImpacted">Agent Impacted</label>
					</div>
					<div class="input-field col s6 m6">
						<input type="text" id="reqEmpId" name="reqEmpId" title="Employee ID" required="required">
						<label for="reqEmpId">Requester Employee ID</label>

					</div>

				</div>

				<div class="input-field col s12 m12">

					<div class="input-field col s6 m6">
						<input type="text" id="issueDesc" name="issueDesc" title="Describe the requester issue" required="required">

						<label for="issueDesc">Issue Discription</label>
					</div>
				</div>
				<div class="col s12 m12 right-align">
					<button class="btn waves-effect waves-green" type="button" name="btn_submit" id="btn_submit" value="Submit"> Submit</button>
				</div>
			</div>
			<!--Form container End -->
		</div>
		<!--Sub Main Div for all Page End -->

	</div>
	<!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
</div>



<script>
	function categorySelect(cat) {

		//alert(cat);
		if (cat != 'na') {
			if (cat == 'Internal') {
				$('#divIssueType1').show();
				$('#divIssueType2').hide();
			} else {
				$('#divIssueType1').hide();
				$('#divIssueType2').show();
			}
		}
	}

	$('#btn_submit').on('click', function() {
		var categories1 = $('#categories1').val();
		var issueType = '';
		var location = $('#location').val();
		var priority = $('#priorty').val();
		var client = $('#Client').val();


		var processName = $('#process').val();
		var tat = $('#tat').val();
		var totalAgents = $('#totalAgents').val();
		var agentImpacted = $('#agentImpacted').val();
		var requesterEmpId = $('#reqEmpId').val();
		var issueDesc = $('#issueDesc').val();
		var handlerEmpId = <?php echo "'" . $user_login . "'"; ?>;
		var handlerName = <?php echo "'" . $username . "'"; ?>;
		var handlerEmail = $('#emailOff').val();
		var handlerMobile = $('#mobileNo').val();

		var isError = 0;
		var categoryError = '';
		var issueTypeError = '';
		var locationError = '';
		var priortyError = '';
		var clientError = '';

		//Validating Categories
		if (isValid(categories1)) {
			isError = 1;
			categoryError = 'Please select a category first.';
		}

		if (categories1 == 'Internal') {
			issueType = $('#issueType1').val();
		} else {
			issueType = $('#issueType2').val();
		}

		//Validating IssueType
		if (isValid(issueType)) {
			isError = 1;
			issueTypeError = 'Please select a issue type first.';
		}

		//Validating Location
		if (isValid(location)) {
			isError = 1;
			locationError = 'Please select a location first.';
		}

		//Validating Priority
		if (isValid(priority)) {
			isError = 1;
			priortyError = 'Please select a priority first.';
		}
		//Validating Client

		if (isValidClient(client)) {
			isError = 1;
			clientError = 'Please select a client first.';
		}

		//Validating Other Text Boxes
		if (isValid(processName) || isValid(tat) || isValid(totalAgents) || isValid(agentImpacted) || isValid(requesterEmpId) || isValid(issueDesc)) {
			isError = 2;
			return false;
		}
		if (agentImpacted > totalAgents) {
			alert('Total agents can not be less than impacted agents.');
			return false;
		}
		//
		if (isError == 1) {
			alert(categoryError + '\n' + issueTypeError + '\n' + locationError + '\n' + priortyError + '\n' + clientError);
			return false;
		} else if (isError == 2) {
			return false;
		} else {
			$.ajax({
				type: 'POST',
				url: '../Controller/ithdk_raise_ticket_web_service.php',
				data: {
					"handlerEmpId": handlerEmpId,
					"requesterEmpId": requesterEmpId,
					"client": client.toString(),
					"priorty": priority,
					"process": processName,
					"category": categories1,
					"issueType": issueType,
					"location": location,
					"tat": tat,
					"totalAgents": totalAgents,
					"agentImpacted": agentImpacted,
					"issueDisc": issueDesc,
					"handlerName": handlerName,
					"handlerEmail": handlerEmail,
					"handlerMobile": handlerMobile,
					"appkey": "raise_ticket"
				}
			}).done(function(data) {
				var obj = JSON.parse(data);
				//alert(data);
				alert(obj['msg']);
				//consol.log(data);
				// Optionally alert the user of success here...
			}).fail(function(data) {
				// Optionally alert the user of an error here...
				alert('Unable to raise issue, try again later.');
			});
		}
	});

	function isValid(str) {
		return (!str || str.length === 0 || str == 'na');
	}

	function isValidClient(str) {
		return (!str || str.length === 0 || str == 'notSelect' || str.includes("notSelect"));
	}
</script>



<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>