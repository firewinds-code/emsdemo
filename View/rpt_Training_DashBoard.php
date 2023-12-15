<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
// Global variable used in Page Cycle
$value=$counEmployee=$countProcess=$countClient=$countSubproc=0;
ini_set('display_errors',1);
error_reporting(E_ERROR | E_PARSE );
function isDate($value) 
{
    if (!$value) {
        return false;
    }

    try {
        new \DateTime($value);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
		exit();
	}
	elseif(empty($_SESSION['__user_logid']) || $_SESSION['__user_logid'] == '')
	{
		die("access denied ! It seems like you try for a wrong action.");
		exit();
		
		
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
	exit();
}

$DateTo = '';
if(isset($_POST['txt_dateFor']))
{
	$DateTo = $_POST['txt_dateFor'];
	
}
else
{
	$DateTo = date('Y-m-d', strtotime('today - 30 days'));
	
}
if(isset($_POST['ddl_clfs_Process']))
{
	$process = $_POST['ddl_clfs_Process'];
	
}
?>

<script>
	$(function(){
		$('#txt_dateFor').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
    });
		$('#myTable').DataTable({
				        dom: 'Bfrtip',"paging": false,       
				         buttons: [
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
						        },'copy'
						        
						    ],
						    "bProcessing" : true,
							"bDestroy" : true,
							"bAutoWidth" : true,
							"iDisplayLength": 25,
							"sScrollX" : "100%",
							"bScrollCollapse" : true,
							"bLengthChange" : false,
							"fnDrawCallback":function() {
								$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
							}
							
				       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
			});
			
			$('.buttons-copy').attr('id','buttons_copy');
		   	$('.buttons-csv').attr('id','buttons_csv');
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-pdf').attr('id','buttons_pdf');
		   	$('.buttons-print').attr('id','buttons_print');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
		   	
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Training Dashboard Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

  <!-- Sub Main Div for all Page -->
  <div class="form-div">

<!-- Header for Form If any -->
	 <h4>Training Dashboard Report</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >

<div class="col s12 m12">

<!--<div class="form-group col-sm-6" style="padding: 0px;">
<div class="form-group col-sm-12"  style="padding: 0px;">
<div class="form-group"  style="padding: 0px;">
<input  class="form-control" name="txt_dateFor" style="min-width: 250px;"  id="txt_dateFor" value="<?php echo $DateTo;?>"/>
<select class="form-control" id="ddl_clfs_Process" name="ddl_clfs_Process" style="max-width: 300px;min-width: 300px;">
<option value="NA">----Select----</option>	
<option value="ALL">----ALL----</option>	
<?php
	
	$sqlBy = 'select distinct Process,clientname,sub_process,cm_id from whole_details_peremp where function = "Operation" order by clientname'; 
	$myDB=new MysqliDb();
	$resultBy=$myDB->query($sqlBy);
	if($resultBy){													
		foreach($resultBy as $key=>$value){
			if($process == $value['whole_details_peremp']['cm_id'])
			{
				if($value['whole_details_peremp']['Process'] == $value['whole_details_peremp']['sub_process'])
				{
					echo '<option value="'.$value['whole_details_peremp']['cm_id'].'"  selected> '.$value['whole_details_peremp']['clientname'].' | '.$value['whole_details_peremp']['sub_process'].'</option>';
				}
				else
				{
					echo '<option value="'.$value['whole_details_peremp']['cm_id'].'"  selected>'.$value['whole_details_peremp']['clientname'].' | '.$value['whole_details_peremp']['Process'].' | '.$value['whole_details_peremp']['sub_process'].'</option>';
				}	
			}
			else
			{
				if($value['whole_details_peremp']['Process'] == $value['whole_details_peremp']['sub_process'])
				{
					echo '<option value="'.$value['whole_details_peremp']['cm_id'].'"  >'.$value['whole_details_peremp']['clientname'].' | '.$value['whole_details_peremp']['sub_process'].'</option>';
				}
				else
				{
					echo '<option value="'.$value['whole_details_peremp']['cm_id'].'"  >'.$value['whole_details_peremp']['clientname'].' | '.$value['whole_details_peremp']['Process'].' | '.$value['whole_details_peremp']['sub_process'].'</option>';
				}	
			}
															
			
		}

	}
												
	?>	</select>
	</div> 
	</div>

	</div>-->
		
		<div class="input-field col s12 m12 right-align">			
		
			<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Get Details </button>
			<button type="button" class="btn waves-effect waves-green hidden" name="btnExport" id="btnExport"> Export</button>
		</div>

	</div>
		<?php
			if(isset($_POST['btn_view']))
			{
				$myDB=new MysqliDb();
				$chk_task = array();
				if($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_type'] == 'CENTRAL MIS')
				{
					$chk_task = $myDB->query("select bm.BacthID,bm.BacthName,bm.client,bm.process,bm.subprocess,bm.clientid,new_client_master.cm_id,downtime_time_master.training_days,downtime_time_master.ojt_days,account_head,th from batch_master bm inner join new_client_master on new_client_master.client_name = bm.clientid and new_client_master.process = bm.process and new_client_master.sub_process = bm.subprocess inner join downtime_time_master on new_client_master.cm_id = downtime_time_master.cm_id where bm.createdon >=  '".$DateTo."' and bm.BacthName not like '%_retrain' ");

				}
				else
				{
					
					$chk_task = $myDB->query("select bm.BacthID,bm.BacthName,bm.client,bm.process,bm.subprocess,bm.clientid,new_client_master.cm_id,downtime_time_master.training_days,downtime_time_master.ojt_days,account_head,th from batch_master bm inner join new_client_master on new_client_master.client_name = bm.clientid and new_client_master.process = bm.process and new_client_master.sub_process = bm.subprocess inner join downtime_time_master on new_client_master.cm_id = downtime_time_master.cm_id where bm.createdon >=  '".$DateTo."' and bm.BacthID in (select distinct BatchID from whole_details_peremp where '".$_SESSION['__user_logid']."' in (account_head,th,qh,Trainer,Quality))   and bm.BacthName not like '%_retrain'");

				}
				
				//$chk_task = $myDB->query("select bm.BacthID,bm.BacthName,bm.client,bm.process,bm.subprocess,bm.clientid,new_client_master.cm_id,downtime_time_master.training_days,downtime_time_master.ojt_days,account_head,th from batch_master bm inner join new_client_master on new_client_master.client_name = bm.clientid and new_client_master.process = bm.process and new_client_master.sub_process = bm.subprocess inner join downtime_time_master on new_client_master.cm_id = downtime_time_master.cm_id where bm.createdon >=  '".$DateTo."' and bm.BacthID = 1387");
				
				$counter = 0;
				$my_error = $myDB->getLastError();
				if(empty($my_error))
				{   
					$date_first = date("Y-m-01",strtotime($DateTo));
					$date_last = date("Y-m-t",strtotime($DateTo));
					
					
					$table='<div class="panel panel-default col-sm-12" style="margin-top:10px;" id="tbl_div"><div class="panel-body">
					<table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
					// Details
					//Table structure Data
					$_hdr_table_data[] = 'Client';
					$_hdr_table_data[] = 'Process';					
					$_hdr_table_data[] = 'Sub Process';
					$_hdr_table_data[] = 'Batch Name';
					
					$_hdr_table_data[] = 'Trainer Name';
					$_hdr_table_data[] = 'Status As On Date';
					$_hdr_table_data[] = 'Current Active Count';
					$_hdr_table_data[] = 'Training Start Date';
					$_hdr_table_data[] = 'Planned Training End Date';
					$_hdr_table_data[] = 'Training Planned Days';
					$_hdr_table_data[] = 'Start Count Day 1';
					$_hdr_table_data[] = 'Addition Day 2+3';
					$_hdr_table_data[] = '3 day Attrition Abscond';
					$_hdr_table_data[] = '3 Days RHR';
					$_hdr_table_data[] = 'Day 3 Count';
					$_hdr_table_data[] = 'Actual Training End Date';
					$_hdr_table_data[] = 'Actual Training Days';
					$_hdr_table_data[] = 'Re-Training Count';
					$_hdr_table_data[] = 'Re-Training Start Date';
					$_hdr_table_data[] = 'Re-Training End Date';
					$_hdr_table_data[] = 'Training Overrun Mandays';
					$_hdr_table_data[] = 'Training Certified Trainee Count';
					$_hdr_table_data[] = 'Trainee Attrition in Training';
					$_hdr_table_data[] = 'OJT QA name';
					$_hdr_table_data[] = 'Planned OJT Start';
					$_hdr_table_data[] = 'Actual OJT Start';
					$_hdr_table_data[] = 'Planned OJT End Date';
					$_hdr_table_data[] = 'Actual OJT End Date';
					$_hdr_table_data[] = 'Re-OJT Count';
					$_hdr_table_data[] = 'Re-OJT Start Date';
					$_hdr_table_data[] = 'Re-OJT End Date';
					$_hdr_table_data[] = 'OJT Overrun Mandays';
					$_hdr_table_data[] = 'RHR OJT/D-Certified'; 
					$_hdr_table_data[] = 'Vol.OJT Attrition';
					$_hdr_table_data[] = 'Over All OJT Attrition';
					$_hdr_table_data[] = 'Floor Hit Count';
					$_hdr_table_data[] = 'Planned Floor Hit Date';
					$_hdr_table_data[] = 'Actual Floor Hit Date';
					$_hdr_table_data[] = 'Training Head';
					$_hdr_table_data[] = 'Account Head';
					$_hdr_table_data[] = 'No. of Call Audit';
					$_hdr_table_data[] = 'On Floor Quality Score';
					$_hdr_table_data[] = 'Supervisor Start Count';
					$_hdr_table_data[] = 'Supervisor Certified Count';
					$_hdr_table_data[] = 'Batch Status';

					foreach($_hdr_table_data as $th)
					{
						$table .="<th>".$th."</th>";
					}
					
				    $table .='</thead><tbody>';
					foreach($chk_task as $key => $value)
					{
						
						$table_data_per_td = array();
						
						$table_data_per_td['Client'] = $value['client'];
						$table_data_per_td['Process'] = $value['process'];
						$table_data_per_td['Batch Name'] = $value['BacthName'];
						$table_data_per_td['Sub Process'] = $value['subprocess'];
						
											
						//Check for Re-training
						$retrain_batch_id = 0;				
						$expected_batch_name = $value['BacthName'].'_retrain';
						$myDB = new MysqliDb();
						$dt_retrain_tr = $myDB->query('select BacthID from batch_master where BacthName  = "'.$expected_batch_name.'"');
						if($dt_retrain_tr && count($dt_retrain_tr) > 0)
						{
							$retrain_batch_id = $dt_retrain_tr[0]['BacthID']; 							
							
						}
						//echo $retrain_batch_id.','; // Check for Retrain ID
						$myDB = new MysqliDb();
						$tr_count = $myDB->query("select distinct Trainer,count(*) Count,EmployeeName as TrainerName from ( select max(id),EmployeeID,Trainer from status_training where BatchID ='".$value['BacthID']."'  group by EmployeeID union select max(id),EmployeeID,Trainer from status_training_log where BatchID ='".$value['BacthID']."'  group by EmployeeID) st inner join personal_details where st.Trainer = personal_details.EmployeeID ");
						$count_emplopyee = 0;
						if(count($tr_count) == 1 && $tr_count)
						{
							$table_data_per_td['Trainer Name'] = $tr_count[0]['TrainerName'];
							$table_data_per_td['Total Count'] = $count_emplopyee =  $tr_count[0]['Count'];
						}						
						else if(count($tr_count) == 1 && $tr_count)
						{
							$count_emplopyee = 0;
							foreach($tr_count as $k_tr => $val_tr)
							{
								$table_data_per_td['Trainer Name'] = $tr_count[0]['TrainerName'];
							    $count_emplopyee += $tr_count[0]['Count'];
							}
							$table_data_per_td['Total Count'] = $count_emplopyee;
						}
						
						// Status Training Data
						
						$Employee_data = array();	
						
						$myDB = new MysqliDb();
						$tr1_count = $myDB->query("select EmployeeID, BatchID, Trainer, `Status` , retrain_flag, Certification_1, c_status, date_cer_1, day_cer_1,`Table Status` from ( select max(id), EmployeeID, BatchID, Trainer, `Status` , retrain_flag, Certification_1, c_status, date_cer_1, day_cer_1,'Current' `Table Status` from status_training where BatchID ='".$value['BacthID']."'  group by EmployeeID union select max(id), EmployeeID, BatchID, Trainer, `Status` , retrain_flag, Certification_1, c_status, date_cer_1, day_cer_1,'Log' `Table Status` from status_training_log where BatchID ='".$value['BacthID']."'  group by EmployeeID) st ");						
						if(count($tr1_count) > 0 && $tr1_count)
						{
							foreach($tr1_count as $ky_tr1=>$val_employee_tr1)
							{
								$Employee = $val_employee_tr1['EmployeeID'];
								$validate = 0;
								if($val_employee_tr1['Table Status'] == 'Log')
								{
									if(in_array($Employee,$Employee_data['Employee']))
									{
										$validate = 1;
									}
								  	
								}
								if($validate == 0)
								{
									$Employee_data['Employee'][] = $Employee;
									$Employee_data['CertDate'][] = strtotime($val_employee_tr1['date_cer_1']);
									$Employee_data[$Employee]['ECertDate'] =  $val_employee_tr1['date_cer_1'];
									$Employee_data[$Employee]['tStatus'] =  $val_employee_tr1['Status'];
									$Employee_data[$Employee]['retrain_flag'] =  $val_employee_tr1['retrain_flag'];
									$Employee_data[$Employee]['Certification_1'] =  $val_employee_tr1['Certification_1'];
									$Employee_data[$Employee]['c_status'] =  $val_employee_tr1['c_status'];
								}
								
								
							}	
							
							
						}
						if($retrain_batch_id > 0)
						{
							$myDB = new MysqliDb();
							$tr1_rt_count = $myDB->query("select EmployeeID, BatchID, Trainer, `Status` , retrain_flag, Certification_1, c_status, date_cer_1, day_cer_1,`Table Status` from ( select max(id), EmployeeID, BatchID, Trainer, `Status` , retrain_flag, Certification_1, c_status, date_cer_1, day_cer_1,'Current' `Table Status` from status_training where BatchID ='".$retrain_batch_id."'  group by EmployeeID union select max(id), EmployeeID, BatchID, Trainer, `Status` , retrain_flag, Certification_1, c_status, date_cer_1, day_cer_1,'Log' `Table Status` from status_training_log where BatchID ='".$retrain_batch_id."'  group by EmployeeID) st ");	
							if(count($tr1_rt_count) > 0 && $tr1_rt_count)
							{
								
								foreach($tr1_rt_count as $ky_tr1=>$val_employee_rt_tr1)
								{
									$Employee = $val_employee_rt_tr1['EmployeeID'];
									$validate = 0;
									if($val_employee_rt_tr1['Table Status'] == 'Log')
									{
										if(in_array($Employee,$Employee_data['Employee']))
										{
											$validate = 1;
										}
									  	
									}
									if($validate == 0)
									{	
									    if(!in_array($Employee,$Employee_data['Employee']))
										{
											$Employee_data['Employee'][] = $Employee;
											$Employee_data['CertDate'][] = strtotime($val_employee_rt_tr1['date_cer_1']);
										    $Employee_data[$Employee]['ECertDate'] =  $val_employee_rt_tr1['date_cer_1'];
										}
										
										$Employee_data[$Employee]['tStatus'] =  $val_employee_rt_tr1['Status'];
										$Employee_data[$Employee]['retrain_flag'] =  $val_employee_rt_tr1['retrain_flag'];
										$Employee_data[$Employee]['Certification_1'] =  $val_employee_rt_tr1['Certification_1'];
										$Employee_data[$Employee]['c_status'] =  $val_employee_rt_tr1['c_status'];
									}
									
									
								}	
							}
						}
						
						$myDB = new MysqliDb();
						$tr2_count = $myDB->query("select EmployeeID, `Status`,InTraining, InOJT, OnFloor, OutTraining, InQAOJT, OutOJTQA, RetrainTime,reOJT,`Table Status` from ( select max(id),EmployeeID, `Status`,InTraining, InOJT, OnFloor, OutTraining, InQAOJT, OutOJTQA, RetrainTime,reOJT,'Current' `Table Status` from status_table where BatchID ='".$value['BacthID']."'  group by EmployeeID union  select max(id),EmployeeID, `Status`,InTraining, InOJT, OnFloor, OutTraining, InQAOJT, OutOJTQA, RetrainTime,reOJT,'Log' `Table Status` from status_table_log where BatchID ='".$value['BacthID']."'  group by EmployeeID ) st ");	
						
						if(count($tr2_count) > 0 && $tr2_count)
						{
													
							
							foreach($tr2_count as $ky_tr2=>$val_employee_tr2)
							{
								$Employee = $val_employee_tr2['EmployeeID'];
								$validate = 0;
								if($val_employee_tr2['Table Status'] == 'Log')
								{
									if(in_array($Employee,$Employee_data['Employee_st']))
									{
										$validate = 1;
									}
								  	
								}
								if($validate == 0)
								{
								    $Employee_data['Employee_st'][] = $Employee;
									$Employee_data['InTraining'][] = strtotime($val_employee_tr2['InTraining']);
									$Employee_data['OutTraining'][] = strtotime($val_employee_tr2['OutTraining']);
									$Employee_data['InQAOJT'][] = strtotime($val_employee_tr2['InQAOJT']);
									$Employee_data['OutOJTQA'][] = strtotime($val_employee_tr2['OutOJTQA']);
									
									$Employee_data[$Employee]['InTraining'] =  $val_employee_tr2['InTraining'];
									$Employee_data[$Employee]['OutTraining'] =  $val_employee_tr2['OutTraining'];
									$Employee_data[$Employee]['Status'] =  $val_employee_tr2['Status'];
									$Employee_data[$Employee]['InOJT'] =  $val_employee_tr2['InOJT'];
									$Employee_data[$Employee]['InQAOJT'] =  $val_employee_tr2['InQAOJT'];
									$Employee_data[$Employee]['OutOJTQA'] =  $val_employee_tr2['OutOJTQA'];
									$Employee_data[$Employee]['RetrainTime'] =  $val_employee_tr2['RetrainTime'];
									$Employee_data[$Employee]['reOJT'] =  $val_employee_tr2['reOJT'];
									$Employee_data[$Employee]['OnFloor'] =  $val_employee_tr2['OnFloor'];
								}
							}					
							
								
						}
						if($retrain_batch_id > 0)
						{
							$myDB = new MysqliDb();
							$tr2_rt_count = $myDB->query("select EmployeeID, `Status`,InTraining, InOJT, OnFloor, OutTraining, InQAOJT, OutOJTQA, RetrainTime,reOJT,`Table Status` from ( select max(id),EmployeeID, `Status`,InTraining, InOJT, OnFloor, OutTraining, InQAOJT, OutOJTQA, RetrainTime,reOJT,'Current' `Table Status` from status_table where BatchID ='".$retrain_batch_id."'  group by EmployeeID union  select max(id),EmployeeID, `Status`,InTraining, InOJT, OnFloor, OutTraining, InQAOJT, OutOJTQA, RetrainTime,reOJT,'Log' `Table Status` from status_table_log where BatchID ='".$retrain_batch_id."'  group by EmployeeID ) st ");	
							
							if(count($tr2_rt_count) > 0 && $tr2_rt_count)
							{
								foreach($tr2_rt_count as $ky_tr2=>$val_employee_rt_tr2)
								{
									$Employee = $val_employee_rt_tr2['EmployeeID'];
									$validate = 0;
									if($val_employee_rt_tr2['Table Status'] == 'Log')
									{
										if(in_array($Employee,$Employee_data['Employee_st']))
										{
											$validate = 1;
										}
									}
									if($validate == 0)
									{
										if(!in_array($Employee,$Employee_data['Employee_st']))
										{
											$Employee_data['Employee_st'][] = $Employee;
											$Employee_data[$Employee]['InTraining'] =  $val_employee_tr2['InTraining'];
										}
									    
										$Employee_data['OutTraining'][] = strtotime($val_employee_rt_tr2['OutTraining']);
										$Employee_data['InQAOJT'][] = strtotime($val_employee_rt_tr2['InQAOJT']);
										$Employee_data['OutOJTQA'][] = strtotime($val_employee_rt_tr2['OutOJTQA']);
										$Employee_data[$Employee]['OutTraining'] =  $val_employee_rt_tr2['OutTraining'];
										$Employee_data[$Employee]['Status'] =  $val_employee_rt_tr2['Status'];
										$Employee_data[$Employee]['InOJT'] =  $val_employee_rt_tr2['InOJT'];
										$Employee_data[$Employee]['InQAOJT'] =  $val_employee_rt_tr2['InQAOJT'];
										$Employee_data[$Employee]['OutOJTQA'] =  $val_employee_rt_tr2['OutOJTQA'];
										$Employee_data[$Employee]['RetrainTime'] =  $val_employee_rt_tr2['RetrainTime'];
										$Employee_data[$Employee]['reOJT'] =  $val_employee_rt_tr2['reOJT'];
										$Employee_data[$Employee]['OnFloor'] =  $val_employee_rt_tr2['OnFloor'];
									}
								}			
							}
						}
						$active_employee_current = 0;
						$inactive_employee_InTr = 0;
						$inactive_employee_InM_qr = 0;
						$inactive_employee_InOJT_qr = 0;
						$inactive_employee_InOJT_vol_qr = 0;
						if(count($Employee_data['Employee']) > 0 && $Employee_data['Employee'])
						{
							foreach($Employee_data['Employee'] as $Employee)							
							{
								$myDB = new MysqliDb();
								$data_check_active = $myDB->query('select employee_map.emp_status from employee_map where EmployeeID ="'.$Employee.'"');
								if(count($data_check_active) > 0 && $data_check_active)
								{
									if($data_check_active[0]['employee_map']['emp_status'] == 'Active')
									{
										$active_employee_current++;
										$Employee_data[$Employee]['Active'] = 'Active';
									}
									elseif($data_check_active[0]['employee_map']['emp_status'] == 'InActive')
									{
										if($Employee_data[$Employee]['Status'] == 3)
										{
										  	$inactive_employee_InTr++;
										}
										else if($Employee_data[$Employee]['Status'] >= 4 && $Employee_data[$Employee]['Status'] <= 5 )
										{
											if($Employee_data[$Employee]['Status'] == 5)
											{
												$inactive_employee_InM_qr++;												
											}
										  	$inactive_employee_InOJT_qr++; 
										}
										$Employee_data[$Employee]['Active'] = 'InActive';
									}
								}
							}
						}
						
						$table_data_per_td['Current Active Count'] = $active_employee_current;
						$data_date_CertDate = "";
						if(!empty($Employee_data['InTraining']))
						{
							if(count($Employee_data['InTraining']) > 0)
							{
								foreach($Employee_data['InTraining'] as $data_date)
								{
									if($data_date && !empty($data_date))
									{
										$data_date_CertDate[] = $data_date;
									}
								}
								if(count($data_date_CertDate) > 0  && $data_date_CertDate)
								{
									$data_date_CertDate = min($data_date_CertDate);
									if(date('Y-m-d',$data_date_CertDate) == '1970-01-01')
									{
										$data_date_CertDate = "";
									}
									else
									{
										$data_date_CertDate = date('Y-m-d',$data_date_CertDate);
									}
								}
							}
							
						}
						$table_data_per_td['Training Start Date'] = $data_date_CertDate;
						
						$data_date_Planned = "";
						if(!empty($Employee_data['CertDate']))
						{
							if(count($Employee_data['CertDate']) > 0)
							{
								foreach($Employee_data['CertDate'] as $data_date)
								{
									if($data_date && !empty($data_date))
									{
										$data_date_Planned[] = $data_date;
									}
								}
								if(count($data_date_Planned) > 0  && $data_date_Planned)
								{
									$data_date_Planned = max($data_date_Planned);
									if(date('Y-m-d',$data_date_Planned) == '1970-01-01')
									{
										$data_date_Planned = "";
									}
									else
									{
										$data_date_Planned = date('Y-m-d',$data_date_Planned);
									}
								}
							}
							
						}
						$table_data_per_td['Planned Training End Date'] = $data_date_Planned;
						$data_date_ActualEnd = "";
						if(!empty($Employee_data['OutTraining']))
						{
							if(count($Employee_data['OutTraining']) > 0)
							{
								foreach($Employee_data['OutTraining'] as $data_date)
								{
									if($data_date && !empty($data_date))
									{
										$data_date_ActualEnd[] = $data_date;
									}
								}
								if(count($data_date_ActualEnd) > 0  && $data_date_ActualEnd)
								{
									$data_date_ActualEnd = max($data_date_ActualEnd);
									if(date('Y-m-d',$data_date_ActualEnd) == '1970-01-01')
									{
										$data_date_ActualEnd = "";
									}
									else
									{
										$data_date_ActualEnd = date('Y-m-d',$data_date_ActualEnd);
									}
								}
							}
							
						}
						
						$table_data_per_td['Actual Training End Date'] = $data_date_ActualEnd;
						
						if(strtotime($data_date_ActualEnd) && strtotime($data_date_CertDate))
						{
							$Start = strtotime($data_date_CertDate); // or your date as well
							$End = strtotime($data_date_ActualEnd);
							$datediff = ($End - $Start);
							$table_data_per_td['Actual Training Days'] = (floor($datediff / (60 * 60 * 24)) + 1);	//Actual Training Days	
						}
						if(strtotime($data_date_Planned) && strtotime($data_date_CertDate))
						{
							$Start = strtotime($data_date_CertDate); // or your date as well
							$End = strtotime($data_date_Planned);
							$datediff = ($End - $Start);
							$table_data_per_td['Training Planned Days'] = (floor($datediff / (60 * 60 * 24)) + 1);	//Actual Training Days	
						}						

						$retrain_count = 0;
						//Test check fot Employee Count
						/*if($retrain_batch_id == 1344)					
						{echo '<pre>'; var_dump($Employee_data['Employee']);echo '</pre>'; }*/
						
						if(count($Employee_data['Employee']) > 0 && $Employee_data['Employee'])
						{
							foreach($Employee_data['Employee'] as $Employee)							
							{
								if($Employee_data[$Employee]['retrain_flag'] == 1 && strtotime($Employee_data[$Employee]['RetrainTime']))
								{
									$retrain_count++;
								}
								
							}
						}
						
						
						$table_data_per_td['Re-Training Count'] = $retrain_count;
						if($retrain_count > 0)
						{
							if($retrain_batch_id > 0)
							{
								$myDB = new MysqliDb();
						    	$status_retrain_dt = $myDB->query('select max(date_cer_1) as RT_End,(select min(RetrainTime) from status_table where BatchID = status_training.BatchID) RT_Start from status_training where BatchID = "'.$retrain_batch_id.'"');
						    	//Check for Retrain Data
						    	//var_dump($status_retrain_dt);
						    	if(count($status_retrain_dt) > 0 && $status_retrain_dt)
						    	{
									$table_data_per_td['Re-Training Start Date'] = ((strtotime($status_retrain_dt[0]['RT_Start']))?date('Y-m-d',strtotime($status_retrain_dt[0]['RT_Start'])):'');
						            $table_data_per_td['Re-Training End Date'] = ((strtotime($status_retrain_dt[0]['RT_End']))?date('Y-m-d',strtotime($status_retrain_dt[0]['RT_End'])):'');	
								}
								
							}
							
							
	
						}
					    				    
					    $training_over_run = 0;
					    $training_over_run_str = '';
					    if(count($Employee_data['Employee']) > 0 && $Employee_data['Employee'])
						{
							
							foreach($Employee_data['Employee'] as $Employee)							
							{
								$data_temp_check = $Employee_data[$Employee]['OutTraining'];
								$data_temp_check = $Employee_data[$Employee]['ECertDate'];
								if(strtotime($Employee_data[$Employee]['OutTraining']) && strtotime($Employee_data[$Employee]['ECertDate']))
								{
									if(strtotime($Employee_data[$Employee]['OutTraining']) > strtotime($Employee_data[$Employee]['ECertDate']))
									{
											$Start = strtotime($Employee_data[$Employee]['ECertDate']); // or your date as well
											$End = strtotime($Employee_data[$Employee]['OutTraining']);
											$datediff = ($End - $Start);
											if((floor($datediff / (60 * 60 * 24))) > 1)
											{
												$training_over_run += (floor($datediff / (60 * 60 * 24)));										
											    $training_over_run_str .= $Employee.' = '.(floor($datediff / (60 * 60 * 24))).', ';
											}
											
											
									}
								}
								
								
							}
						}
					    $table_data_per_td['Training Overrun Mandays']= $training_over_run;//$training_over_run_str for details
					    
					    
					    $str_certify = 0;
					    if(count($Employee_data['Employee']) > 0 && $Employee_data['Employee'])
						{
							
							foreach($Employee_data['Employee'] as $Employee)							
							{
								if(strtoupper($Employee_data[$Employee]['tStatus']) == 'YES' && $Employee_data[$Employee]['Status'] >= 3)
								{
										$str_certify++;
								}
								
							}
						}
						
					    $table_data_per_td['Training Certified Trainee Count'] = $str_certify;
					    //$inactive_employee_InTr $active_employee_current $count_emplopyee			
					    		    
					    $table_data_per_td['Trainee Attrition in Training'] = $inactive_employee_InTr;
 				        
						$intraining_counter = 0;
						$ojt_counter = 0;
						$onfloor = 0;
						$rf_hr = 0;
						$floor_hit_date = array();
						$inactive_counter = 0;
						if(count($Employee_data['Employee']) > 0 && $Employee_data['Employee'])
						{
							
							foreach($Employee_data['Employee'] as $Employee)							
							{
								if($Employee_data[$Employee]['Status'] == 3 && $Employee_data[$Employee]['Active'] == 'Active')
								{
								    $intraining_counter++;
								}
								elseif($Employee_data[$Employee]['Status'] > 3 && $Employee_data[$Employee]['Status'] < 6 && $Employee_data[$Employee]['Active'] == 'Active')
								{
									$ojt_counter++;
								}
								elseif($Employee_data[$Employee]['Status'] == 6 && $Employee_data[$Employee]['Active'] == 'Active')
								{
									$onfloor++;
									$floor_hit_date[] = $Employee_data[$Employee]['OnFloor'];
								}
								elseif($Employee_data[$Employee]['Active'] == 'Active' && $Employee_data[$Employee]['Status'] == 1)
								{
									$rf_hr++;
								}
								elseif($Employee_data[$Employee]['Active'] == 'InActive')
								{
									$inactive_counter++;
								}
							}
						}
						if($intraining_counter > 0 || $ojt_counter > 0 || $rf_hr > 0)
						{
						    //$table .='<td>Total : '.$count_emplopyee.' ,Inactive : '.$inactive_counter.' ,On Floor : '.$onfloor.' ,In Training : '.$intraining_counter.',In OJT : '.$ojt_counter.',Ref To HR : '.$rf_hr.' </td>';		
						    $table_data_per_td['Status As On Date'] = '';
						    $table_data_per_td['Batch Status'] = 'Active';
						}
						else
						{
							if($onfloor > 0)
							{
								$table_data_per_td['Status As On Date'] = '';
						        $table_data_per_td['Batch Status'] = 'Completed';		
							}
							else
							{
								$table_data_per_td['Status As On Date'] = '';	
						        $table_data_per_td['Batch Status'] = 'Undefined';	
							}
						    
						}
						
						//OJT
						$ojt_count_data_fqa  = "";
						if($retrain_batch_id > 0)
						{
							$ojt_count_data_fqa = "select distinct Quality,count(*) Count,EmployeeName as QualityName from ( select max(id),EmployeeID,Quality from status_quality where BatchID ='".$value['BacthID']."' or BatchID ='".$retrain_batch_id."' group by EmployeeID union select max(id),EmployeeID,Quality from status_quality_log where BatchID ='".$value['BacthID']."'  or BatchID ='".$retrain_batch_id."' group by EmployeeID) st inner join personal_details where st.Quality = personal_details.EmployeeID ";
						}
						else
						{
							$ojt_count_data_fqa = "select distinct Quality,count(*) Count,EmployeeName as QualityName from ( select max(id),EmployeeID,Quality from status_quality where BatchID ='".$value['BacthID']."' group by EmployeeID union select max(id),EmployeeID,Quality from status_quality_log where BatchID ='".$value['BacthID']."' group by EmployeeID) st inner join personal_details where st.Quality = personal_details.EmployeeID ";
						}
						
						
						$myDB = new MysqliDb();
						$qr_count = $myDB->query($ojt_count_data_fqa);
						$count_emplopyee_qr = 0;
						if(count($qr_count) == 1 && $qr_count)
						{
							$table_data_per_td['OJT QA name'] = $qr_count[0]['QualityName'];							
							$table_data_per_td['Total Count OJT'] = $qr_count[0]['Count'];
							$count_emplopyee_qr = $qr_count[0]['Count'];
						}						
						else if(count($qr_count) == 1 && $qr_count)
						{
							$count_emplopyee_qr=0;
							foreach($qr_count as $k_tr => $val_tr)
							{
								$table_data_per_td['OJT QA name'] = $val_tr['QualityName'];							
							    							
								$count_emplopyee_qr += $qr_count[0]['Count'];
							}
							$table_data_per_td['Total Count OJT'] = $count_emplopyee_qr;	
							
						}
						
						$myDB = new MysqliDb();
						$qr1_count = $myDB->query("select EmployeeID, BatchID, Quality, `Status` , ojt_status, Final_OJT_date,`Table Status` from ( select max(id), EmployeeID, BatchID, Quality, `Status` , ojt_status, case when ojt_status > 0 && ojt_duration > 0 and exists(select reOJT from ( select reOJT,EmployeeID from status_table where (BatchID ='1296' or BatchID ='1310' and reOJT is not null) union select reOJT,EmployeeID from status_table_log where (BatchID ='1296' or BatchID ='1310' and reOJT is not null) ) st where st.EmployeeID = status_quality.EmployeeID) then DATE_SUB(Final_OJT_date, INTERVAL ojt_duration DAY) else  Final_OJT_date end  Final_OJT_date,'Current' `Table Status` from status_quality where BatchID ='".$value['BacthID']."' group by EmployeeID union   select max(id), EmployeeID, BatchID, Quality, `Status` , ojt_status, case when ojt_status > 0 && ojt_duration > 0  then DATE_SUB(Final_OJT_date, INTERVAL ojt_duration DAY) else  Final_OJT_date end  Final_OJT_date,'Log'   `Table Status` from status_quality_log where BatchID ='".$value['BacthID']."'  group by EmployeeID) st  ");						
						if(count($qr1_count) > 0 && $qr1_count)
						{
							foreach($qr1_count as $ky_tr1=>$val_employee_qr1)
							{
								$Employee = $val_employee_qr1['EmployeeID'];
								$validate = 0;
								if($val_employee_qr1['Table Status'] == 'Log')
								{
									if(in_array($Employee,$Employee_data['Employee_qr']))
									{
										$validate = 1;
									}
								  	
								}
								if($validate == 0)
								{										
									$Employee_data['Employee_qr'][] = $Employee;								
									$Employee_data['q_CertDate'][] = strtotime($val_employee_qr1['Final_OJT_date']);
									$Employee_data[$Employee]['q_CertDate'] =  $val_employee_qr1['Final_OJT_date'];
									$Employee_data[$Employee]['q_Status'] =  $val_employee_qr1['Status'];
									$Employee_data[$Employee]['q_retrain_flag'] =  $val_employee_qr1['ojt_status'];
								}
								
								
							}					
							
							
						}
						
						if($retrain_batch_id > 0)
						{
							$myDB = new MysqliDb();
							$qr1_rt_count = $myDB->query("select EmployeeID, BatchID, Quality, `Status` , ojt_status, Final_OJT_date,`Table Status` from ( select max(id), EmployeeID, BatchID, Quality, `Status` , ojt_status, case when ojt_status > 0 && ojt_duration > 0 and exists(select reOJT from ( select reOJT,EmployeeID from status_table where (BatchID ='1296' or BatchID ='1310' and reOJT is not null) union select reOJT,EmployeeID from status_table_log where (BatchID ='1296' or BatchID ='1310' and reOJT is not null) ) st where st.EmployeeID = status_quality.EmployeeID) then DATE_SUB(Final_OJT_date, INTERVAL ojt_duration DAY) else  Final_OJT_date end  Final_OJT_date,'Current' `Table Status` from status_quality where BatchID ='".$retrain_batch_id."' group by EmployeeID union   select max(id), EmployeeID, BatchID, Quality, `Status` , ojt_status, case when ojt_status > 0 && ojt_duration > 0  then DATE_SUB(Final_OJT_date, INTERVAL ojt_duration DAY) else  Final_OJT_date end  Final_OJT_date,'Log'   `Table Status` from status_quality_log where BatchID ='".$retrain_batch_id."'  group by EmployeeID) st  ");						
							
							
							if(count($qr1_rt_count) > 0 && $qr1_rt_count)
							{
														
								
								foreach($qr1_rt_count as $ky_tr1=>$val_employee_rt_qr1)
								{
									$Employee = $val_employee_rt_qr1['EmployeeID'];
									$validate = 0;
									if($val_employee_rt_qr1['Table Status'] == 'Log')
									{
										if(in_array($Employee,$Employee_data['Employee_qr']))
										{
											$validate = 1;
										}
									  	
									}
									if($validate == 0)
									{
										if(!in_array($Employee,$Employee_data['Employee_qr']))
										{
											$Employee_data['Employee_qr'][] = $Employee;
											$Employee_data['q_CertDate'][] = strtotime($val_employee_rt_qr1['Final_OJT_date']);
										    $Employee_data[$Employee]['q_CertDate'] =  $val_employee_rt_qr1['Final_OJT_date'];
											
										}										
										
										$Employee_data[$Employee]['q_Status'] =  $val_employee_rt_qr1['Status'];
										$Employee_data[$Employee]['q_retrain_flag'] =  $val_employee_rt_qr1['ojt_status'];
									}	
								}		
							}
						}
						
						
					    	
					    $table_data_per_td['Planned OJT Start'] = $data_date_ActualEnd;
					    if(empty($data_date_ActualEnd) || !strtotime($data_date_ActualEnd))
					    {
					    	$table_data_per_td['Planned OJT Start'] = $data_date_Planned;
						}
						$data_date_InOJT = "";
						if(!empty($Employee_data['InQAOJT']))
						{
							if(count($Employee_data['InQAOJT']) > 0)
							{
								foreach($Employee_data['InQAOJT'] as $data_date)
								{
									if($data_date && !empty($data_date))
									{
										$data_date_InOJT[] = $data_date;
									}
								}
								if(count($data_date_InOJT) > 0  && $data_date_InOJT)
								{
									$data_date_InOJT = min($data_date_InOJT);
									if(date('Y-m-d',$data_date_InOJT) == '1970-01-01')
									{
										$data_date_InOJT = "";
									}
									else
									{
										$data_date_InOJT = date('Y-m-d',$data_date_InOJT);
									}
								}
							}
							
						}
						$table_data_per_td['Actual OJT Start'] = $data_date_InOJT;
						
						 
						$data_ojt_Planned = "";
						if(!empty($Employee_data['q_CertDate']))
						{
							if(count($Employee_data['q_CertDate']) > 0)
							{
								foreach($Employee_data['q_CertDate'] as $data_date)
								{
									if($data_date && !empty($data_date))
									{
										$data_ojt_Planned[] = $data_date;
									}
								}
								if(count($data_ojt_Planned) > 0  && $data_ojt_Planned)
								{
									$data_ojt_Planned = max($data_ojt_Planned);
									if(date('Y-m-d',$data_ojt_Planned) == '1970-01-01')
									{
										$data_ojt_Planned = "";
									}
									else
									{
										$data_ojt_Planned = date('Y-m-d',$data_ojt_Planned);
									}
								}
							}
							
						}
						
						$table_data_per_td['Planned OJT End Date'] = $data_ojt_Planned;
						$data_ojt_ActualEnd = "";
						if(!empty($Employee_data['OutOJTQA']))
						{
							if(count($Employee_data['OutOJTQA']) > 0)
							{
								foreach($Employee_data['OutOJTQA'] as $data_date)
								{
									if($data_date && !empty($data_date))
									{
										$data_ojt_ActualEnd[] = $data_date;
									}
								}
								if(count($data_ojt_ActualEnd) > 0  && $data_ojt_ActualEnd)
								{
									$data_ojt_ActualEnd = max($data_ojt_ActualEnd);
									if(date('Y-m-d',$data_ojt_ActualEnd) == '1970-01-01')
									{
										$data_ojt_ActualEnd = "";
									}
									else
									{
										$data_ojt_ActualEnd = date('Y-m-d',$data_ojt_ActualEnd);
									}
								}
							}
							
						}
						
						$table_data_per_td['Actual OJT End Date'] = $data_ojt_ActualEnd;
						
						if(strtotime($data_ojt_ActualEnd) && strtotime($data_date_InOJT))
						{
							$Start = strtotime($data_date_InOJT); // or your date as well
							$End = strtotime($data_ojt_ActualEnd);
							$datediff = ($End - $Start);
							$table_data_per_td['Actual OJT Days'] = (floor($datediff / (60 * 60 * 24)) + 1);
						}	
						if(strtotime($data_ojt_Planned) && strtotime($data_date_InOJT))
						{
							$Start = strtotime($data_date_InOJT); // or your date as well
							$End = strtotime($data_ojt_Planned);
							$datediff = ($End - $Start);
							$table_data_per_td['Planned OJT Days'] = (floor($datediff / (60 * 60 * 24)) + 1);
						}						
						
						$reojt_count = 0;						
						 if(count($Employee_data['Employee']) > 0 && $Employee_data['Employee'])
						{
							foreach($Employee_data['Employee'] as $Employee)							
							{
								if($Employee_data[$Employee]['q_retrain_flag'] != 0 && strtotime($Employee_data[$Employee]['reOJT']))
								{
									$reojt_count++;
								}
								
							}
						}
						
						
						$table_data_per_td['Re-OJT Count'] =  $reojt_count;
						if($reojt_count > 0)
						{
							
							$myDB = new MysqliDb();
					    	$status_reOJT_dt = $myDB->query('select min(reOJT) as `Start_ReOJT`,max(OutOJTQA) as `End_ReOJT` from status_table where BatchID = "'.$value['BacthID'].'" or  BatchID = "'.$retrain_batch_id.'" ');
					    	//Kent|Kent Inbound|Inbound|35_retrain
					    	if(count($status_reOJT_dt) > 0 && $status_reOJT_dt)
					    	{
					    		
								$table_data_per_td['Re-OJT Start Date'] = ((strtotime($status_reOJT_dt[0]['Start_ReOJT']))?date('Y-m-d',strtotime($status_reOJT_dt[0]['Start_ReOJT'])):'');
					            $table_data_per_td['Re-OJT End Date'] = ((strtotime($status_reOJT_dt[0]['End_ReOJT']))?date('Y-m-d',strtotime($status_reOJT_dt[0]['End_ReOJT'])):'');	
							}
													
	
						}					    
						
						$ojt_over_run = 0;
					    $ojt_over_run_str = '';
					    if(count($Employee_data['Employee']) > 0 && $Employee_data['Employee'])
						{
							
							foreach($Employee_data['Employee'] as $Employee)							
							{
								$data_temp_check = $Employee_data[$Employee]['OutOJTQA'];
								$data_temp_check = $Employee_data[$Employee]['q_CertDate'];
								if(strtotime($Employee_data[$Employee]['OutOJTQA']) && strtotime($Employee_data[$Employee]['q_CertDate']))
								{
									if(strtotime($Employee_data[$Employee]['OutOJTQA']) > strtotime($Employee_data[$Employee]['q_CertDate']))
									{
											$Start = strtotime($Employee_data[$Employee]['q_CertDate']); // or your date as well
											$End = strtotime($Employee_data[$Employee]['OutOJTQA']);
											$datediff = ($End - $Start);
											if((floor($datediff / (60 * 60 * 24))) > 1)
											{
												$ojt_over_run += (floor($datediff / (60 * 60 * 24)));										
											    $ojt_over_run_str .= $Employee.' = '.(floor($datediff / (60 * 60 * 24))).', ';
											}
											
									}
								}
								
								
							}
						}
					    $table_data_per_td['OJT Overrun Mandays'] = $ojt_over_run; //$ojt_over_run_str
					    $table_data_per_td['Vol. OJT Attrition'] = 0;
					    if($inactive_employee_InM_qr > 0)
					    {
					    	
					    	if(count($Employee_data[$Employee]['Status']) > 0 and $Employee_data[$Employee]['Status'])
					    	{
					    	    
					    		foreach($Employee_data['Employee'] as $Employee)
					    		{
									//<td>'.$inactive_employee_InM_qr.'</td>';
									if($Employee_data[$Employee]['Status'] == 5)
									{
										$query_exit = 'select * from exit_emp where EmployeeID = "'.$Employee.'"';
									    if(strtotime($data_ojt_ActualEnd))
									    {
											$query_exit .= ' and dol <= "'.$data_ojt_ActualEnd.'" order by id desc limit 1';
										}
										elseif(strtotime($data_ojt_Planned))
										{
											$query_exit .= ' and dol <= "'.$data_ojt_Planned.'"  order by id desc limit 1';
										}
										else
										{
											$query_exit .= ' order by id desc limit 1;';
										}
										$myDB = new MysqliDb();
										$exit_data_check = $myDB->query($query_exit);
										if(count($exit_data_check) > 0 && $exit_data_check)
										{
											if($exit_data_check[0]['exit_emp']['disposition'] == 'DCR')
											{
												$inactive_employee_InOJT_vol_qr++;
											}
										}
									    	
									}
									
								}
								$table_data_per_td['Vol. OJT Attrition'] = $inactive_employee_InOJT_vol_qr;
							}
							
						}
						
						$table_data_per_td['Over All OJT Attrition'] = $inactive_employee_InM_qr;						
						$table_data_per_td['Floor Hit Count'] = $onfloor;
						//$inactive_employee_InOJT_qr						
						//Comman Details
						//$retrain_batch_id
						
						$floor_hit_date_str = "";
						
						if(!empty($floor_hit_date))
						{
							if(count($floor_hit_date) > 0)
							{
								foreach($floor_hit_date as $data_date)
								{
									if($data_date && !empty($data_date))
									{
										$floor_hit_date_str[] = $data_date;
									}
								}
								
								if(count($floor_hit_date_str) > 0  && $floor_hit_date_str)
								{
									$floor_hit_date_str = max($floor_hit_date_str);
									
									if(date('Y-m-d',strtotime($floor_hit_date_str)) == '1970-01-01')
									{
										$floor_hit_date_str = "";
									}
									else
									{
										$floor_hit_date_str = date('Y-m-d',strtotime($floor_hit_date_str));
									}
									
								}
							}
							
						}
						
						//
						$table_data_per_td['Planned Floor Hit Date'] = $data_ojt_Planned;						
						$table_data_per_td['Actual Floor Hit Date'] = $floor_hit_date_str;						
						$myDB = new MysqliDb();//
						$get_client_detials = $myDB->query("select bm.BacthID,bm.BacthName,bm.client,bm.process,bm.subprocess,bm.clientid,account_head,oh,qh,th,DateCreated,bm.createdon from batch_master bm join (select * from ( select new_client_master.*,case when modifiedon is null then createdon else modifiedon end as 'DateCreated' from new_client_master union select new_client_master_log.*,case when modifiedon is null then createdon else modifiedon end as 'DateCreated' from new_client_master_log ) t1) new_client_master on new_client_master.client_name = bm.clientid and new_client_master.process = bm.process and new_client_master.sub_process = bm.subprocess where BacthID = '".$value['BacthID']."' and cast(DateCreated as date) <= cast(bm.createdon as date) order by DateCreated desc limit 1");
						if(count($get_client_detials) > 0 && $get_client_detials)
						{
							$myDB = new MysqliDb();
							$dt_head_details = $myDB->query("select EmployeeName from personal_details where EmployeeID ='".$get_client_detials[0]['new_client_master']['th']."';");
							
							$table_data_per_td['Training Head'] = $dt_head_details[0]['EmployeeName'];
							$myDB = new MysqliDb();
							$dt_head_details = $myDB->query("select EmployeeName from personal_details where EmployeeID ='".$get_client_detials[0]['new_client_master']['account_head']."';");
							
							$table_data_per_td['Account Head'] = $dt_head_details[0]['EmployeeName'];
							
							//$get_client_detials[0]['new_client_master']['account_head'].']</b></td>';
						
						}
						else
						{
						   $table_data_per_td['Training Head'] = $value['new_client_master']['th'];
						   $table_data_per_td['Account Head'] = $value['new_client_master']['account_head'];
							/*$myDB = new MysqliDb();
							$dt_head_details = $myDB->query("select EmployeeName from personal_details where EmployeeID ='".$value['new_client_master']['th']."';");
							
							$table .='<td>'.$value['new_client_master']['th'].'<b> ['.$get_client_detials[0]['new_client_master']['th'].']</b></td>';
							$myDB = new MysqliDb();
							$dt_head_details = $myDB->query("select EmployeeName from personal_details where EmployeeID ='".$value['new_client_master']['account_head']."';");
							
							$table .='<td>'.$dt_head_details[0]['EmployeeName'].'<b> ['.$value['new_client_master']['account_head'].']</b></td>';*/
						}
						/*$table .='<td>On Floor Quality Score</td>';
						$table .='<td>No. of Call Audit</td>';
						$table .='<td>Supervisor Start Count</td>';
						$table .='<td>Supervisor Certified Count</td>';*/
						
						
						//$data_date_InOJT $data_ojt_Planned $data_ojt_ActualEnd  $data_date_ActualEnd $data_date_Planned $data_date_CertDate
						$table_data_per_td['Status As On Date'] ='';
						if(strtotime($data_date_CertDate) && strtotime($data_date_CertDate) <= strtotime('today'))
						{
														
							if((strtotime($data_date_Planned) >= strtotime('today') && strtotime($data_date_Planned) ) || (strtotime($data_date_ActualEnd) >= strtotime('today') && strtotime($data_date_ActualEnd)))
							{
								$Start = strtotime($data_date_CertDate); // or your date as well
								$End = strtotime('today');
								$datediff = ($End - $Start);								
								$table_data_per_td['Status As On Date'] .= 'Training :'.((floor($datediff / (60 * 60 * 24))) + 1).'/'.$table_data_per_td['Training Planned Days'].' ';
							}							
							elseif(strtotime($data_date_ActualEnd) < strtotime('today') && strtotime($data_date_ActualEnd))
							{
								$table_data_per_td['Status As On Date'] .= 'Training :Close ';
							}
							elseif(!strtotime($data_ojt_ActualEnd))
							{
								$table_data_per_td['Status As On Date'] .= 'Training :Extended ';
							}
						}
						if(strtotime($data_date_InOJT) && strtotime($data_date_InOJT) <= strtotime('today'))
						{
								if((strtotime($data_ojt_Planned) >= strtotime('today') && strtotime($data_ojt_Planned) ) || (strtotime($data_ojt_ActualEnd) >= strtotime('today') && strtotime($data_ojt_ActualEnd)))
								{
									$Start = strtotime($data_date_InOJT); // or your date as well
									$End = strtotime('today');
									$datediff = ($End - $Start);								
									$table_data_per_td['Status As On Date'] .= 'OJT : '.((floor($datediff / (60 * 60 * 24))) + 1).'/'.$table_data_per_td['Planned OJT Days'].' ';
								}
								elseif(strtotime($data_ojt_ActualEnd) < strtotime('today') && strtotime($data_ojt_ActualEnd))
								{
									$table_data_per_td['Status As On Date'] .= 'OJT :Close ';
								}
								elseif(!strtotime($data_ojt_ActualEnd))
								{
									$table_data_per_td['Status As On Date'] .= 'OJT : Extended ';
								}
						}
						
						if($table_data_per_td['Status As On Date'] == 'Training :Close OJT :Close ')
						{
							$table_data_per_td['Status As On Date'] = 'Close';
						}
						$table_data_per_td['Start Count Day 1'] = 0;
						$table_data_per_td['Addition Day 2+3'] = 0;
						$table_data_per_td['3 day Attrition Abscond'] = 0;
						$table_data_per_td['3 Days RHR'] = 0;
						$table_data_per_td['Supervisor Start Count'] = 0;
						$table_data_per_td['Supervisor Certified Count'] = 0;
						$table_data_per_td['No. of Call Audit'] = 0;
						$table_data_per_td['On Floor Quality Score'] = 0;
						if(count($Employee_data['Employee']) > 0 && $Employee_data['Employee'])
						{
							$tmp_per_td['On Floor Quality Score'] = 0;
							foreach($Employee_data['Employee'] as $Employee)							
							{
								
								if(date('Y-m-d',strtotime($data_date_CertDate)) == date('Y-m-d',strtotime($Employee_data[$Employee]['InTraining'])))
								{
									$table_data_per_td['Start Count Day 1']++;
								}
								else
								{
									$table_data_per_td['Addition Day 2+3']++;
								}
								$Start = date('Y-m-d',strtotime($data_date_CertDate)); // or your date as well
								$End = date('Y-m-d',strtotime('+2 days '.$Start));
								if($Employee_data[$Employee]['Active'] == 'InActive')
								{
									
									$query_exit_pemp = "select distinct EmployeeID from exit_emp where disposition='ABSC' and EmployeeID = '".$Employee."' and dol between '".$Start."' and '".$End."'";
									$myDB = new MysqliDb();
									$exit_data_check_peremp = $myDB->query($query_exit_pemp);
									if(count($exit_data_check_peremp) > 0 && $exit_data_check_peremp)
									{
										$table_data_per_td['3 day Attrition Abscond']++;
									}
								}
								
								
								$query_hr_pemp = "select EmployeeID from hr_refered where EmployeeID = '".$Employee."' and createdon between '".$Start."' and '".$End."'";
								$myDB = new MysqliDb();
								$ht_data_check_peremp = $myDB->query($query_hr_pemp);
								if(count($ht_data_check_peremp) > 0 && $ht_data_check_peremp)
								{
									$table_data_per_td['3 Days RHR']++;
								}
								
								$query_hr_pemp = "select EmployeeID from hr_refered where EmployeeID = '".$Employee."' and ref_level like 'QH%' and createdon between '".$Start."' and '".$data_ojt_ActualEnd."'";
								$myDB = new MysqliDb();
								$ht_data_check_peremp = $myDB->query($query_hr_pemp);
								if(count($ht_data_check_peremp) > 0 && $ht_data_check_peremp)
								{
									$table_data_per_td['RHR OJT/D-Certified']++;
								}
								
								if($Employee_data[$Employee]['Status'] >= 5 && $Employee_data[$Employee]['q_Status'] == 'No')
								{
								   	$table_data_per_td['RHR OJT/D-Certified']++;
								}
								
								
								
								$query_des_id_pemp = "select des_id from whole_dump_emp_data where EmployeeID = '".$Employee."'";
								$myDB  = new MysqliDb();
								$data_des_id = $myDB->query($query_des_id_pemp);
								if(count($data_des_id) >0 && $data_des_id)
								{
									
									$de_id_emp = $data_des_id[0]['whole_dump_emp_data']['des_id'];
									if($de_id_emp != 9 && $de_id_emp != 12)
									{
										
										$table_data_per_td['Supervisor Start Count']++;										
										if(strtoupper($Employee_data[$Employee]['tStatus']) == 'YES' && isDate( $Employee_data[$Employee]['OutTraining']) &&  substr_count($Employee_data[$Employee]['OutTraining'],"-") >= 2)
										{
											$table_data_per_td['Supervisor Certified Count']++;	
										}
										
									}
								}
			


								
								if($Employee_data[$Employee]['Status'] == 6 && !empty($Employee_data[$Employee]['OnFloor']))
								{
									$client_chain = $value['client'].'|'.$value['process'].'|'.$value['subprocess'];
									$sdf= explode("-",$sdffd);								
									$ch = curl_init();
							        curl_setopt($ch, CURLOPT_URL,"http://192.168.202.60/qms/admin/service_qms_avg_data_peremp.php");
							        curl_setopt($ch, CURLOPT_POST, 1);
							        curl_setopt($ch, CURLOPT_POSTFIELDS,'EmployeeID'."=".$Employee.'&DateOnFloor='.$Employee_data[$Employee]['OnFloor'].'&process='.$client_chain);
							        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							        $server_output = curl_exec ($ch);
							        curl_close($ch);
							        if(!empty($server_output) && $server_output != '0|0')
							        {
										$srequest_emp_Dt = explode('|',$server_output);
										//var_dump($srequest_emp_Dt);
										$table_data_per_td['No. of Call Audit'] += intval( $srequest_emp_Dt[0]);
										$tmp_per_td['On Floor Quality Score'] += intval($srequest_emp_Dt[1]);
									}
							        
							        
									
								}
								
								//$tmp_per_td['No. of Call Audit'] = $srequest_emp_Dt[''];
								//$tmp_per_td['On Floor Quality Score'] = $srequest_emp_Dt[''];
							}
							
							if($table_data_per_td['No. of Call Audit'] != 0 && !empty($table_data_per_td['No. of Call Audit']))
							$table_data_per_td['On Floor Quality Score'] = round(($tmp_per_td['On Floor Quality Score'])/$table_data_per_td['No. of Call Audit'],2);
							
						}
						
						$table_data_per_td['Day 3 Count'] = ($table_data_per_td['Start Count Day 1'] + $table_data_per_td['Addition Day 2+3']) - ($table_data_per_td['3 day Attrition Abscond'] + $table_data_per_td['3 Days RHR']);
						
						$table .='<tr>';
						foreach($_hdr_table_data as $th)
						{
							if(!isset($table_data_per_td[$th]) && empty($table_data_per_td[$th]))
							{
								
							  $table .="<td>-</td>";
							}
							else
							{
								
								if(DateTime::createFromFormat('Y-m-d', $table_data_per_td[$th]) !== FALSE)								
								{
									$table .="<td>".date('d/m/Y',strtotime($table_data_per_td[$th]))."</td>";	
								}
								else
								{   if($th == 'Batch Status')
									{
										if($table_data_per_td['Batch Status'] == 'Completed')
										{
											$table .="<td class='alert alert-success'><b>".$table_data_per_td['Batch Status']."</b></td>";		
										}
										else
										{
											$table .="<td class='alert alert-danger'><b>".$table_data_per_td['Batch Status']."</b></td>";		
										}
										
									   
									}
									else
									{
										 
										if($th == 'Training Certified Trainee Count' || $th == 'RHR OJT/D-Certified')
										{
											if($table_data_per_td[$th] == 0 && $table_data_per_td['Batch Status'] != 'Completed')
											{
													$table .="<td>WIP</td>";			
											}
											else
											{
													$table .="<td>".$table_data_per_td[$th]."</td>";				
											}
											
										}
										else
										{
										    $table .="<td>".$table_data_per_td[$th]."</td>";				
										}
										
									}
									
								}
								
								
							}
							
						}
						$table .='</tr>';
					}		
					$table .='</tbody></table></div></div>';
					echo $table;
				
				}
				else
				{
					$alert_msg = 'No data found';
					echo "<script>$(function(){ toastr.error('".$alert_msg."'); }); </script>";
				}
			}	
		 ?>	
					    	
		<div id="overlay" class="hidden">	
			<div id="modal_div"><div id="loader_content"></div> Loading team data,please wait.</div>
		</div>
 
        <div class="hidden modelbackground" id="myDiv">
   	

</div>
<!--Form container End -->	
</div>     
  
  </div>
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>  
<script>
	$(function(){
		$("#btn_view").on("click",function(){
				$(this).addClass('hidden');	
		});
	});	
	$("#btnExport").on('click',function(e) {
	        //getting values of current time for generating the file name
	        var dt = new Date();
	        var day = dt.getDate();
	        var month = dt.getMonth() + 1;
	        var year = dt.getFullYear();
	        var hour = dt.getHours();
	        var mins = dt.getMinutes();
	        var sec = dt.getSeconds();
	        var postfix = day + "." + month + "." + year + "_" + hour + "." + mins + "." + sec ;
	        //creating a temporary HTML link element (they support setting file names)
	        var a = document.createElement('a');
	        //getting data from our div that contains the HTML table
	        var data_type = 'data:application/vnd.ms-excel';
	        var table_div = document.getElementById('tbl_div');
	        var table_html = table_div.outerHTML.replace(/ /g, '%20');
	        a.href = data_type + ', ' + table_html;
	        //setting the file name
	        a.download = 'exported_table_' + postfix + '.xls';
	        //triggering the function
	        a.click();
	        //just in case, prevent default behaviour
	        e.preventDefault();
	    });
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
