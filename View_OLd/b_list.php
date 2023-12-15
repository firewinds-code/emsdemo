<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$ofc_loc = clean($_SESSION['__location']);
if ($ofc_loc == "1" || $ofc_loc == "2") {
	$locationdir = "";
} else if ($ofc_loc == "3") {
	$locationdir = "Meerut/";
} else if ($ofc_loc == "4") {
	$locationdir = "Bareilly/";
} else if ($ofc_loc == "5") {
	$locationdir = "Vadodara/";
} else if ($ofc_loc == "6") {
	$locationdir = "Manglore/";
} else if ($ofc_loc == "7") {
	$locationdir = "Bangalore/";
}


?>
<link href="../FileContainer/crosscover-1.0.2/dist/css/crosscover.min.css" rel="stylesheet" />
<script src="../FileContainer/crosscover-1.0.2/dist/js/crosscover.min.js" charset="utf-8"></script>
<style>
	div#the_message_blist {

		position: fixed;
		top: 45%;
		left: 20%;
		right: 20%;
		background-color: #000;
		background: #000c;
		border: 1px solid #a6a6a6;
		padding: 10px;
		color: #ffffff;
		text-align: left;


	}
</style>
<script>
	$(function() {
		$('.crosscover').crosscover({
			controller: false,
			dotNav: true,
			inClass: 'lightSpeedIn',
			outClass: 'lightSpeedOut'
			/*inClass:'fadeIn',
			outClass:'fadeOut'*/
		});
		$("#scroll_div1").scroll(function() {
			$("#scroll_div1").removeClass('hidden');
		});
		$('#main').scroll(function() {
			var y = $(this).scrollTop();
			if (y > 80) {

				$('#scroll_div1').removeClass('hidden');
			} else {
				$('#scroll_div1').addClass('hidden');
			}

		});
		$('#the_message_blist').removeClass('hidden').addClass('fadeInright animated');
		//$('#the_message_blist').delay(10000).fadeOut();
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Birth Day List</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Birth Day List</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">


				<div class="row">
					<!-- <div class="col s12 m12"> -->
					<?php
					$sql_blist = 'call get_birthday_list()';
					$myDB = new MysqliDb();
					$result_blist = $myDB->query($sql_blist);
					$table = '';
					if ($result_blist) {
						$count = 0;
						foreach ($result_blist as $key => $value) {
							// echo "<pre>";
							// print_r($value);
							$ofc_loc = $value['location'];

							// $ofc_loc = $_SESSION['__location'];
							if ($ofc_loc == "1" || $ofc_loc == "2") {
								$locationdir = "";
							} else if ($ofc_loc == "3") {
								$locationdir = "Meerut/";
							} else if ($ofc_loc == "4") {
								$locationdir = "Bareilly/";
							} else if ($ofc_loc == "5") {
								$locationdir = "Vadodara/";
							} else if ($ofc_loc == "6") {
								$locationdir = "Manglore/";
							} else if ($ofc_loc == "7") {
								$locationdir = "Bangalore/";
							}

							// echo $locationdir;
							// echo $img .= URL . $locationdir . 'Images/' . $value['img'];
							$img = '<img alt="user" style="height: 110px;width: 90px;position: relative;top: 25px;right: 10px;border-radius: 10px;" src="';
							if ($value['img'] != '') {
								$img .= URL . $locationdir . 'Images/' . $value['img'];
							} else {
								//$img .= URL . $locationdir . 'Images/' . $value['img'];
								$img .= "../Style/images/agent-icon.png";
							}
							$img .= '"/>'; ?>


							<div class="col s5 m5" style="box-shadow: 0px 5px 10px 0px rgb(0 0 0 / 50%);margin: 8px 8px;height:200px;border-radius: 16px;">
								<div class="row">
									<div class="col s3 m3" style="margin: 10px 0;"><?php echo $img ?></div>
									<div class="col s9 m9" style="font-size:10px;margin: 10px 0;">
										<!-- <span><b>Employee ID:</b> <?php echo $value['EmployeeID'] ?></span></br>
										<span><b>Name:</b> <?php echo $value['EmployeeName'] ?></span><br />
										<span><b>Process:</b> <?php echo $value['process'] ?></span></br>
										<span><b>Sub Process:</b> <?php echo $value['sub_process'] ?></span></br>
										<span><b>Client:</b> <?php echo $value['client_name'] ?></span></br> -->
										<table>
											<!-- <tr>
												<td><b>Employee ID</b></td>
												<td style="padding:0"><?php //echo $value['EmployeeID'] 
																		?></td>
											</tr> -->
											<tr>
												<td><b>Name</b></td>
												<td style="padding:0"><?php echo $value['EmployeeName'] ?></td>
											</tr>

											<tr>
												<td><b>Client</b></td>
												<td style="padding:0"><?php echo $value['client_name'] ?></td>
											</tr>
											<tr>
												<td><b>Process</b></td>
												<td style="padding:0"><?php echo $value['process'] ?></td>
											</tr>
											<tr>
												<td><b>Sub Process</b></td>
												<td style="padding:0"><?php echo $value['sub_process'] ?></td>
											</tr>
											<tr>
												<td><b>Designation</b></td>
												<td style="padding:0"><?php echo $value['Designation'] ?></td>
											</tr>
											<tr>
												<td><b>Location</b></td>
												<td style="padding:0"><?php echo $value['locname'] ?></td>
											</tr>
										</table>

									</div>
								</div>
							</div>


					<?php	}
						//echo $table;
					}
					?>

					<!-- </div> -->
					<!--<div id="myModal_content" class="modal">
    <div class="modal-content">
      <h4>Birthday Wishes</h4>
      <p>Wishing you all the great things in life, hope this day will bring you an extra share of all that makes you happiest. Happy Birthday to ALL.</p>
    </div>
    <div class="modal-footer">
	<a href="#!" class="btn waves-effect modal-action modal-close waves-red close-btn">Cogent EMS</a>      
    </div>
  </div>-->

				</div>

			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(function() {
		$('button').click(function(event) {
			event.preventDefault();
			//$(document).on("keydown", disableF5);
		});
		/*$('.modal').modal(
		{
			onOpenStart:function(elm)
			{
				
			},
			onCloseEnd:function(elm)
			{
				
			}
		});
		$('#myModal_content').modal('open');
		setTimeout(function(){$('#myModal_content').modal('close');},10000);*/
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>