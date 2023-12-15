<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');

?>

<div class="" style="top: 100px;right: 35px;height: 47px;float:right;">

	<p>

		<a href="<?php echo URL . 'View/testDocument?empid=' . $EmployeeID; ?>" class="btn-floating green darken-1 tooltipped" data-position="bottom" data-tooltip="Test Document">
			<i class="material-icons">Test Document</i></a>
		<a href="<?php echo URL . 'View/wt_doc?empid=' . $EmployeeID; ?>" class="btn-floating blue darken-1 tooltipped" data-position="bottom" data-tooltip="Warning Docs">
			<i class="material-icons">Warning Docs</i></a>
		<a href="<?php echo URL . 'View/empsave?empid=' . $EmployeeID; ?>" class="btn-floating green darken-1 tooltipped" data-position="bottom" data-tooltip="Profile Details">
			<i class="material-icons">account_box</i></a>
		<a href="<?php echo URL . 'View/education?empid=' . $EmployeeID; ?>" class="btn-floating red darken-1 tooltipped" data-position="bottom" data-tooltip="Education Details">
			<i class="material-icons">school</i></a>
		<a href="<?php echo URL . 'View/experience?empid=' . $EmployeeID; ?>" class="btn-floating grey darken-1 tooltipped" data-position="bottom" data-tooltip="Experience Details">
			<i class="material-icons">work</i></a>
		<a href="<?php echo URL . 'View/contact?empid=' . $EmployeeID; ?>" class="btn-floating blue lighten-1 tooltipped" data-position="bottom" data-tooltip="Contact Details">
			<i class="material-icons">contact_phone</i></a>
		<a href="<?php echo URL . 'View/document?empid=' . $EmployeeID; ?>" class="btn-floating blue lighten-1 tooltipped" data-position="bottom" data-tooltip="Document Details">
			<i class="material-icons">Doc Detail</i></a>
		<a href="<?php echo URL . 'View/address?empid=' . $EmployeeID; ?>" class="btn-floating yellow darken-1 tooltipped" data-position="bottom" data-tooltip="Address Details">
			<i class="material-icons">location_on</i></a>


		<a href="<?php echo URL . 'View/infradetails?empid=' . $EmployeeID; ?>" class="btn-floating green lighten-1 tooltipped" data-position="bottom" data-tooltip="Infra Detail">
			<i class="material-icons">Infra Detail</i></a>

		<?php
		$getDetails_sc = 'select cm_id from employee_map where EmployeeID="' . $EmployeeID . '"';
		$myDB = new MysqliDb();
		$result_all_sc = $myDB->query($getDetails_sc);
		if ($result_all_sc) {
			if ($result_all_sc[0]['cm_id'] == '531') {


		?>
				<a href="<?php echo URL . 'View/vehicledetails?empid=' . $EmployeeID; ?>" class="btn-floating green lighten-1 tooltipped" data-position="bottom" data-tooltip="Vehicle Detail">
					<i class="material-icons">Vehicle Detail</i></a>

		<?php }
		} ?>



		<a href="<?php echo URL . 'View/bankdetails?empid=' . $EmployeeID; ?>" class="btn-floating red lighten-1 tooltipped" data-position="bottom" data-tooltip="Bank Details">

			<i class="material-icons">account_balance</i></a>
		<!--<a href="<?php echo URL . 'View/salemp?empid=' . $EmployeeID; ?>" class="btn-floating green lighten-1 tooltipped" data-position="bottom" data-tooltip="Payroll Details">-->
		<?php
		$getDetails_sc = 'select location from personal_details where EmployeeID ="' . $EmployeeID . '"';
		$myDB = new MysqliDb();
		$result_all_sc = $myDB->query($getDetails_sc);
		if ($result_all_sc) {
			foreach ($result_all_sc as $key => $value) {
				$loc_sc = $value['location'];

				if ($loc_sc == "5" || $loc_sc == "2" || $loc_sc == "8") { ?>

					<a href="<?php echo URL . 'View/SalEmpV?empid=' . $EmployeeID; ?>" class="btn-floating green lighten-1 tooltipped" data-position="bottom" data-tooltip="Payroll Details">
					<?php
				} else if ($loc_sc == "6") { ?>

						<a href="<?php echo URL . 'View/SalEmpML?empid=' . $EmployeeID; ?>" class="btn-floating green lighten-1 tooltipped" data-position="bottom" data-tooltip="Payroll Details">

						<?php
					} else if ($loc_sc == "10") { ?>

							<a href="<?php echo URL . 'View/SalEmpHar?empid=' . $EmployeeID; ?>" class="btn-floating green lighten-1 tooltipped" data-position="bottom" data-tooltip="Payroll Details">

							<?php
						} else if ($loc_sc == "7" || $loc_sc == "9") { ?>

								<a href="<?php echo URL . 'View/SalEmpSU?empid=' . $EmployeeID; ?>" class="btn-floating green lighten-1 tooltipped" data-position="bottom" data-tooltip="Payroll Details">

								<?php
							} else { ?>

									<a href="<?php echo URL . 'View/salemp?empid=' . $EmployeeID; ?>" class="btn-floating green lighten-1 tooltipped" data-position="bottom" data-tooltip="Payroll Details">

							<?php

							}
						}
					}  ?>




							<i class="material-icons">payment</i></a>
									<a href="<?php echo URL . 'View/mapemp?empid=' . $EmployeeID; ?>" class="btn-floating blue darken-2 tooltipped" data-position="bottom" data-tooltip="Map Employee Details">
										<i class="material-icons">streetview</i></a>

									<a href="<?php echo URL . 'View/exitemp?empid=' . $EmployeeID; ?>" class="btn-floating red darken-3 tooltipped" data-position="bottom" data-tooltip="Exit Employee Manage">
										<i class="material-icons">exit_to_app</i></a>



	</p>
</div>