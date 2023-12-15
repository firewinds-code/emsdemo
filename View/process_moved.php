<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
$_SESSION['__user_logid'] = 'schedulerPage';
//require(ROOT_PATH.'AppCode/nHead.php');
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Employee Movement : Process to Process </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee Movement : Process to Process (Final Movement ) </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$myDB = new MysqliDb();
				$updateforAh = $myDB->rawQuery("update tbl_oh_tooh_move set  flag='Moved', status='transfered' , Updated_by='Server',updated_on=now() where  flag='NPA' and status='OHApprove' and move_date <= curdate()");
				$mysql_error = $myDB->getLastError();
				$rowCount = $myDB->count;
				if ($rowCount > 0) {
					$select_data_from_move_table = $myDB->rawQuery("SELECT a.EmployeeID,a.cm_id,a.new_cm_id,a.move_date,a.tr_required,b.account_head,b.th,b.df_id FROM tbl_oh_tooh_move a Inner join whole_details_peremp b on a.EmployeeID=b.EmployeeID where a.flag='Moved' and a.status='transfered' and a.move_date <= curdate()");
					echo "SELECT a.EmployeeID,a.cm_id,a.new_cm_id,a.move_date,a.tr_required,b.account_head,b.th,b.df_id FROM tbl_oh_tooh_move a Inner join whole_details_peremp b on a.EmployeeID=b.EmployeeID where a.flag='Moved' and a.status='transfered' and a.move_date <= curdate()";
					$mysql_error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if ($rowCount > 0) {
						foreach ($select_data_from_move_table as $key => $data_array) {
							$training_head = "";
							$operation_head = "";
							$df_id = $data_array['df_id'];
							$old_cm_id = $data_array['cm_id'];
							$new_cm_id = $data_array['new_cm_id'];
							$EmployeeID = $data_array['EmployeeID'];
							$date = date("Y-m-d H:i:s");
							$moveDate = $data_array['move_date'];
							$tr_required = $data_array['tr_required'];
							$myDB = new MysqliDb();
							$select_th = $myDB->rawQuery("Select th,oh from new_client_master where cm_id=$new_cm_id");
							$mysql_error = $myDB->getLastError();
							$rowCount = $myDB->count;
							if ($rowCount > 0) {
								$training_head = $select_th[0]['th'];
								$operation_head = $select_th[0]['oh'];
							}
							$myDB = new MysqliDb();
							$data_status_query = $myDB->rawQuery("update tbl_oh_tooh_move set  flag='FM' where  flag='Moved' and EmployeeID='" . $data_array['EmployeeID'] . "'");
							$myDB = new MysqliDb();
							//echo "insert into status_table_log  select  * from status_table  where EmployeeID='".$data_array['EmployeeID']."'";
							$data_status_query = $myDB->rawQuery("insert into status_table_log  select  * from status_table  where EmployeeID='" . $data_array['EmployeeID'] . "'");
							$mysql_error = $myDB->getLastError();
							$rowCount = $myDB->count;
							if ($rowCount > 0) {
								if ($tr_required == 'r') {

									$movedate_string = " InOJT=NULL, InQAOJT=NULL ,OutOJTQA=NULL,OnFloor=NULL,InTraining=NULL,OutTraining=NULL,RetrainTime=NULL,roster=NULL,reOJT=NULL,Status=2 , mapped_date='" . $date . "' ,";
								} else {
									$update_status = '6';
									$movedate_string = "";
									$training_head = $operation_head;
								}
								$myDB = new MysqliDb();
								$update_status_table = $myDB->rawQuery("Update status_table set ReportTo='" . $training_head . "',Qa_ops='', $movedate_string BatchID='0',createdon='" . $date . "'  where  EmployeeID='" . $EmployeeID . "'");
								$myDB = new MysqliDb();
								/*$insert_emp_map_updates=$myDB->rawQuery("insert into employee_map_updates  select id, EmployeeID, df_id, cm_id, dateofjoin, password, emp_level, emp_status, secques, secans, createdon, createdby, now(), modifiedby,flag,password_updated_time from employee_map  where EmployeeID='".$data_array['EmployeeID']."'");*/
								$insert_emp_map_updates = $myDB->rawQuery("insert into employee_map_updates ( select * from employee_map  where EmployeeID='" . $data_array['EmployeeID'] . "')");

								$mysql_error = $myDB->getLastError();
								$rowCount = $myDB->count;
								if ($rowCount > 0) {
									$myDB = new MysqliDb();
									echo "<br>";
									echo  "UPDATE  employee_map set cm_id='" . $new_cm_id . "',modifiedon='" . $date . "',modifiedby='Server' where EmployeeID='" . $EmployeeID . "'  ";
									$update_emp_map = $myDB->rawQuery("UPDATE  employee_map set cm_id='" . $new_cm_id . "',modifiedon='" . $date . "',modifiedby='Server' where EmployeeID='" . $EmployeeID . "'  ");

									$myDB = new MysqliDb();
									$update_status_table = $myDB->rawQuery("call update_module_master_empid('" . $new_cm_id . "','" . $EmployeeID . "','Server')");
								} else {
									echo "<br>";
									echo " employee map not updated";
								}
							}
						}
					}
				}


				?>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php // include(ROOT_PATH.'AppCode/footer.mpt'); 
?>