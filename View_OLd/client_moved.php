<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

//require(ROOT_PATH.'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Employee Movement : Client to Client </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee Movement : Client to Client (Final Movement ) </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$myDB = new MysqliDb();
				// echo "update tbl_client_toclient_move set  flag='Moved', status='transfered' , Updated_by='Server',updated_on=now() where  flag='NCA' and status='AHApprove' and move_date <= curdate()";
				$updateforAh = $myDB->rawQuery("update tbl_client_toclient_move set  flag='Moved', status='transfered' , Updated_by='Server',updated_on=now() where  flag='NCA' and status='AHApprove' and move_date <= curdate()");
				$mysql_error = $myDB->getLastError();
				$rowCount = $myDB->count;
				if ($rowCount > 0) {
					$select_data_from_move_table = $myDB->rawQuery(" SELECT distinct a.EmployeeID,a.old_cm_id,a.new_cm_id,b.df_id FROM  tbl_client_toclient_move a Inner join whole_details_peremp b on a.EmployeeID=b.EmployeeID where a.flag='Moved' and a.status='transfered' and a.move_date <= curdate()");
					//echo " SELECT distinct a.EmployeeID,a.old_cm_id,a.new_cm_id,b.df_id FROM  tbl_client_toclient_move a Inner join whole_details_peremp b on a.EmployeeID=b.EmployeeID where a.flag='Moved' and a.status='transfered' and a.move_date <= curdate()";	
					$mysql_error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if ($rowCount > 0) {
						foreach ($select_data_from_move_table as $key => $data_array) {
							$training_head = "";
							$operation_head = "";
							$df_id = $data_array['df_id'];
							$old_cm_id = $data_array['old_cm_id'];
							$new_cm_id = $data_array['new_cm_id'];
							$EmployeeID = $data_array['EmployeeID'];
							$date = date("Y-m-d H:i:s");
							// $select_th = $myDB->rawQuery("Select th,oh from new_client_master where cm_id=$new_cm_id");
							$select_thQry = "Select th,oh from new_client_master where cm_id=?";
							$stmt = $conn->prepare($select_thQry);
							$stmt->bind_param("i", $new_cm_id);
							$stmt->execute();
							$select_th = $stmt->get_result();
							$select_thRow = $select_th->fetch_row();
							// $mysql_error = $myDB->getLastError();
							$rowCount = $select_th->num_rows;
							if ($rowCount > 0) {
								$training_head = $select_thRow[0]; //['th'];
								$operation_head = $select_thRow[1]; //['oh'];
							}

							// $data_status_query = $myDB->rawQuery("update tbl_client_toclient_move set  flag='FM' where  flag='Moved' and EmployeeID='" . $data_array['EmployeeID'] . "'");
							$data_status_queryQry = "update tbl_client_toclient_move set  flag='FM' where  flag='Moved' and EmployeeID=?";
							$stmt1 = $conn->prepare($data_status_queryQry);
							$stmt1->bind_param("s", $EmployeeID);
							$stmt1->execute();
							$data_status_query = $stmt1->get_result();

							// $data_status_query = $myDB->rawQuery("insert into status_table_log  select  * from status_table  where EmployeeID='" . $data_array['EmployeeID'] . "'");
							$data_status_queryQry = "insert into status_table_log  select  * from status_table  where EmployeeID=?";
							$stmt1 = $conn->prepare($data_status_queryQry);
							$stmt1->bind_param("s", $EmployeeID);
							$stmt1->execute();
							$data_status_query = $stmt1->get_result();

							// $mysql_error = $myDB->getLastError();
							$rowCount = $data_status_query->num_rows;
							if ($rowCount > 0) {
								$myDB = new MysqliDb();
								$df_ids = $myDB->rawQuery("select df_id from df_master t1 join designation_master t2 on t1.des_id = t2.id join function_master t3 on t1.function_id = t3.id where t3.function in ('Operation','Quality','Training') and t2.Designation in ('Executive','Team Leader','Senior Executive','Assistant Team Leader','CSA','Group Team Leader','Senior CSA'); ");
								$mysql_error = $myDB->getLastError();
								$rowCount = $myDB->count;
								$dfid_array = array();
								if ($rowCount > 0) {
									foreach ($df_ids as $val) {
										$dfid_array[] = $val['df_id'];
									}
								}
								//$dfid_array=array(67,68,69,71,74,76,77,81,82,83,88,103,104,105,110); //df of below AM

								if (in_array($df_id, $dfid_array)) {
									$update_status = '2';
									// $update_status_table = $myDB->rawQuery("Update status_table set Status='" . $update_status . "',ReportTo='" . $training_head . "',Qa_ops='',BatchID='0',createdon='" . $date . "',InTraining=NULL,InOJT=NULL,OnFloor=NULL,OutTraining=NULL,InQAOJT=NULL,OutOJTQA=NULL,RetrainTime=NULL,roster=NULL,reOJT=NULL,mapped_date='" . $date . "'  where  EmployeeID='" . $EmployeeID . "' ");
									$update_status_tableQry = "Update status_table set Status=?,ReportTo=?,Qa_ops='',BatchID='0',createdon=?,InTraining=NULL,InOJT=NULL,OnFloor=NULL,OutTraining=NULL,InQAOJT=NULL,OutOJTQA=NULL,RetrainTime=NULL,roster=NULL,reOJT=NULL,mapped_date=? where  EmployeeID=? ";
									$stmt = $conn->prepare($update_status_tableQry);
									$stmt->bind_param("sssss", $update_status, $training_head, $date, $date, $EmployeeID);
									$stmt->execute();
									$update_status_table = $stmt->get_result();
									// $mysql_error = $myDB->getLastError();
									// $rowCount = $myDB->count;
								} else {
									// $update_status_table = $myDB->rawQuery("Update status_table set  ReportTo='" . $operation_head . "',createdon='" . $date . "'  where  EmployeeID='" . $EmployeeID . "' ");
									$update_status_tableQry = "Update status_table set ReportTo=?,createdon=? where  EmployeeID=? ";
									$stmt = $conn->prepare($update_status_tableQry);
									$stmt->bind_param("sss", $operation_head, $date, $EmployeeID);
									$stmt->execute();
									$update_status_table = $stmt->get_result();
								}
								//$select_employee_map=$myDB->rawQuery("insert into employee_map_updates  select id, EmployeeID, df_id, cm_id, dateofjoin, password, emp_level, emp_status, secques, secans, createdon, createdby, now(), modifiedby,flag ,password_updated_time from employee_map  where EmployeeID='".$data_array['EmployeeID']."'");

								// $select_employee_map = $myDB->rawQuery("insert into employee_map_updates ( select * from employee_map  where EmployeeID='" . $EmployeeID . "')");
								$select_employee_mapQry = "insert into employee_map_updates ( select * from employee_map  where EmployeeID=?)";
								$stm = $conn->prepare($select_employee_mapQry);
								$stm->bind_param("s", $EmployeeID);
								$stm->execute();
								$select_employee_map = $stm->get_result();
								// $mysql_error = $myDB->getLastError();
								$rowCount = $select_employee_map->num_rows;
								if ($rowCount > 0) {
									echo "<br>";
									// echo "UPDATE  employee_map set cm_id='" . $new_cm_id . "',modifiedon='" . $date . "',modifiedby='Server' where EmployeeID = '" . $EmployeeID . "'";
									// $update_emp_map = $myDB->rawQuery("UPDATE  employee_map set cm_id='" . $new_cm_id . "',modifiedon='" . $date . "',modifiedby='Server' where EmployeeID = '" . $EmployeeID . "'");
									$update_emp_mapQry = "UPDATE  employee_map set cm_id=?,modifiedon=?,modifiedby='Server' where EmployeeID = ?";
									$stmt = $conn->prepare($update_emp_mapQry);
									$stmt->bind_param("iss", $new_cm_id, $date, $EmployeeID);
									$stmt->execute();
									$update_emp_map = $stmt->get_result();

									$myDB = new MysqliDb();
									$update_status_table = $myDB->rawQuery("call update_module_master_empid('" . $cm_id . "','" . $empID . "','" . $createBy . "')");
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