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
$alert_msg ='';
// Trigger Button-Save Click Event and Perform DB Action

// Trigger Button-Edit Click Event and Perform DB Action
$type=(isset($_POST['hiddentype'])? $_POST['hiddentype'] : null);

if(isset($_POST['btn_Assign_Batch']))
{	
	if(isset($_POST['cb']))
	{
		$checked_arr = $_POST['cb'];
		$count_check = count($checked_arr);
		$cm_id=(isset($_POST['hiddensubprocess'])? $_POST['hiddensubprocess'] : null);
		$batch_id=(isset($_POST['txt_batch'])? $_POST['txt_batch'] : null);
		$mode=(isset($_POST['txt_type'])? $_POST['txt_type'] : null);
		
		$createBy=$_SESSION['__user_logid'];
		if($count_check>0)
		{	
					
			foreach($_POST['cb'] as $val)
			{
				$trainer = $tday = $roster = $cert_date = $loc = $EmployeeID = $In = $Out = '';
				$empID=$val;
				$Insert='insert into batch_mapping(batch_id,IntID,CreatedBy,mode) values("'.$batch_id.'","'.$empID.'","'.$createBy.'","'.$mode.'");';
				$myDB=new MysqliDb();
			    $myDB->rawQuery($Insert);
			    $mysql_error = $myDB->getLastError();
				if(empty($mysql_error))
				{
					$sqlBy ='call get_trainer("'.$empID.'","'.$batch_id.'")'; 
					$myDB=new MysqliDb();
					$resultBy=$myDB->rawQuery($sqlBy);
					$mysql_error = $myDB->getLastError();

					if(count($resultBy)>0)
					{
						$trainer = $resultBy[0]['ReportTo'];
						$EmployeeID = $resultBy[0]['EmpID'];
						$roster = $resultBy[0]['roster'];
						
						if(strlen($roster)>30)
						{
							$In =  substr($roster,8,5);
							$Out = substr($roster,23,5);
						}
						else
						{
							$In = "09:00";
							$Out = "18:00";
						}
						$sql='select t2.location from status_table t1 join personal_details t2 on t1.ReportTo=t2.EmployeeID where t1.EmployeeID ="'.$trainer.'"';
						$myDB=new MysqliDb();
						$result=$myDB->query($sql);
						$loc = $result[0]['location'];
						
						$sql='select nc.cm_id,dt.training_days from batch_master bt inner join new_client_master nc on bt.clientid  = nc.client_name and bt.process = nc.process and bt.subprocess = nc.sub_process inner join downtime_time_master dt on dt.cm_id = nc.cm_id where bt.BacthID ="'.$batch_id.'" and nc.location="'.$loc.'"';
						$myDB=new MysqliDb();
						$result=$myDB->query($sql);
						$mysql_error=$myDB->getLastError();
						
						if(count($result) > 0 && $result)
						{
							$tday = $result[0]['training_days'];
							/*foreach($result as $key=>$value)
							{
								echo $value['training_days'];
									
							}*/
						}
						else
						{
							$tday = 0;
						}
						
						if($tday != 0)
						{
							$str = $tday.' days';
							$cert_date =  date('Y-m-d', strtotime($str));
							$tday = $tday - 1;
							$str = $tday.' days';
							
							$startDate = new DateTime(date('Y-m-d'));
							$endDate = new DateTime(date('Y-m-d', strtotime($str)));

							$sundays = array();
							$wo='';
							while ($startDate <= $endDate) {
							    if ($startDate->format('w') == 0) {
							        $sundays[] = $startDate->format('Y-m-d');
							    }
							    
							    $startDate->modify('+1 day');
							}

							//var_dump($sundays);
							if(count($sundays))
							{
								$i=0;
								while($i<count($sundays))
								{
									$wo.= $sundays[$i].'|';
									$i++;	
								}
								
							}
							$wo = substr($wo,0,strlen($wo)-1);
							$roster = 'InTime :'.$In.',OutTime :'.$Out.',WO:'.$wo.',HO:';
							//$roster='InTime :13:00,OutTime :22:00,WO:'.$wo.',HO:';
						}
						
						$sql='select retrain_flag,no_of_Certification,date_cer_1,date_cer_2,date_cer_3, date_cer_4,date_cer_5,day_cer_1,day_cer_2,day_cer_3,day_cer_4 ,day_cer_5,`no_of_Certification` from status_training where BatchID ="'.$batch_id.'" and Trainer="'.$trainer.'" order by id desc limit 1';
						$myDB=new MysqliDb();
						$result=$myDB->query($sql);
						$mysql_error=$myDB->getLastError();
						
						if(count($result) > 0 && $result)
						{
							$date_1_crt = (!empty($result[0]['date_cer_1'])?'"'.$result[0]['date_cer_1'].'"':"NULL");
							$date_2_crt = (!empty($result[0]['date_cer_2'])?'"'.$result[0]['date_cer_2'].'"':"NULL");
							$date_3_crt = (!empty($result[0]['date_cer_3'])?'"'.$result[0]['date_cer_3'].'"':"NULL");
							$date_4_crt = (!empty($result[0]['date_cer_4'])?'"'.$result[0]['date_cer_4'].'"':"NULL");
							$date_5_crt = (!empty($result[0]['date_cer_5'])?'"'.$result[0]['date_cer_5'].'"':"NULL");
					
							$Insert='call manage_status_th_auto("'.$EmployeeID.'","'.$trainer.'","'.$batch_id.'","'.$result[0]['no_of_Certification'].'",'.$date_1_crt.','.$date_2_crt.','.$date_3_crt.','.$date_4_crt.','.$date_5_crt.',"'.$result[0]['day_cer_1'].'","'.$result[0]['day_cer_2'].'","'.$result[0]['day_cer_3'].'","'.$result[0]['day_cer_4'].'","'.$result[0]['day_cer_5'].'","'.$roster.'");';
							$myDB=new MysqliDb();
						    $myDB->rawQuery($Insert);
						    $mysql_error = $myDB->getLastError();
							if(empty($mysql_error))
							{
								$startDate = new DateTime(date('Y-m-d'));
								for($i = $startDate; $startDate <= $endDate; $i->modify('+1 day'))
								{
													
									/*if(in_array($i->format("Y-m-d"),$holist))
									{
										$intime_roster = 'HO';
										$outtime_roster = 'HO';
									}*/
									if(in_array($i->format("Y-m-d"),$sundays))
									{
										$intime_roster = 'WO';
										$outtime_roster = 'WO';
									}
									else
									{
										$intime_roster = $In;
										$outtime_roster = $Out;
									}
								    $str_insert_ros = 'call  sp_insert_roster_backdate("'.$EmployeeID.'","'.$i->format("Y-m-d").'","'.$intime_roster.'","'.$outtime_roster.'","1","WFOB")';
								    
								    $myDB = new MysqliDb();
								    $myDB->query($str_insert_ros);
								    $mnError = $myDB->getLastError();
								}
							}
							
						
						}
					}
					
				}
			}
			echo "<script>$(function(){ toastr.success('Batch Assigned Successfully'); }); </script>";
			
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Record not updated, no Employee selected.'); }); </script>";
			
		}
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Record not updated, no Employee selected.'); }); </script>";
	}	
				
}
?>
<script>
	$(document).ready(function(){
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
				        "paging": false,
				        "iDisplayLength": 25,	        
				        scrollCollapse: true,
				        lengthMenu: [
				            [ 5,10, 25, 50, -1 ],
				            ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
				        ],
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
						        }
						        /*,'copy'*/,
						        'pageLength'
						        
						    ]
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


<div id="content" class="content" >
<span id="PageTittle_span" class="hidden">Batch Assign Page</span>

	<div class="pim-container row" id="div_main" >
		<div class="form-div">
		<input type="hidden" id="hiddenclient" name="hiddenclient" />
		<input type="hidden" id="hiddensubprocess" name="hiddensubprocess" />
		<input type="hidden" id="hiddensubprocessid" name="hiddensubprocessid" />
		<input type="hidden" id="hiddentype" name="hiddentype" />
		
			<h4>Batch Assign Page</h4>			
			 <div class="schema-form-section row" >
			   
				      				      
				      <div class="input-field col s12 m12" id="rpt_container">
				      
				      <div class="input-field col s4 m4">
				            <select id="txt_type" name="txt_type">
				            	<option value="NA" <?php if($type=='NA'||$type==''||empty($type)){ echo('selected'); }?>>----Select----</option>	
						      	<option value="Offline" <?php if($type=='Offline'){ echo('selected'); }?>>Offline</option>	
						      	<option value="Online" <?php if($type=='Online'){ echo('selected'); }?>>Online</option>	
						      	<option value="empid" <?php if($type=='empid'){ echo('selected'); }?>>EMS ID</option>	
				            </select>
				            
		            		<label for="txt_type" class="active-drop-down active">Employee Source *</label>
			   		 	</div>
			   		 	
				        <div class="input-field col s4 m4" id="divloc1">
				            <select id="txt_location" name="txt_location">
				            	<option value="NA">----Select----</option>	
						      	<?php		
								$sqlBy ='select id,location from location_master;'; 
								$myDB=new MysqliDb();
								$resultBy=$myDB->rawQuery($sqlBy);
								$mysql_error = $myDB->getLastError();
								if(empty($mysql_error)){													
									foreach($resultBy as $key=>$value)
									{						
										echo '<option value="'.$value['id'].'"  >'.$value['location'].'</option>';
									}
								}			
						      	?>
				            </select>
				            
		            		<label for="txt_location" class="active-drop-down active">Location *</label>
			   		 	</div>
			   		 	
			   		 				   		 	
			   		 	<div class="input-field col s4 m4" id="divclient1">
				            <select id="txt_Client" name="txt_Client" >
								
							</select>
							
							<label for="txt_Client" class="active-drop-down active">Client *</label>
			   		 	</div>
			   		 	
			   		 	
			   		 				   		 	
			   		 	<div class="input-field col s4 m4" id="divproc1">
				            <select id="txt_Process" name="txt_Process" >
								
							</select>
							
							<label for="txt_Process" class="active-drop-down active">Process *</label>
			   		 	</div>
			   		 	
			   		 	
			   		 	
			   		 	<div class="input-field col s4 m4" id="divsubproc1">
				            <select id="txt_subProcess" name="txt_subProcess" >
								
							</select>
							
							<label for="txt_subProcess" class="active-drop-down active">Sub Process *</label>
			   		 	</div>
			   		 	
			   		 	<div class="batchdetails">
			   		 		<div class="input-field col s12 m12" >
					            <select id="txt_batch" name="txt_batch">
				            	<option value="NA">----Select----</option>	
						      	<?php
						      	$cmid=(isset($_POST['txt_subProcess'])? $_POST['txt_subProcess'] : null);
						      	$process=(isset($_POST['txt_Process'])? $_POST['txt_Process'] : null);
						      	$type=(isset($_POST['txt_type'])? $_POST['txt_type'] : null);
						      	$sqlBy='';
						      	if($type=="Offline" || $type=="empid")
						      	{
									$sqlBy ="select t2.BacthID,t2.BacthName from batch_status t1 join batch_master t2 on t1.cm_id=t2.cm_id and t1.batch_no=t2.batch_no where status!='Close' and t2.cm_id= '".$cmid."';"; 
								}
								else if($type=="Online")
						      	{
									$sqlBy ="select t2.BacthID,t2.BacthName from batch_status t1 join batch_master t2 on t1.cm_id=t2.cm_id and t1.batch_no=t2.batch_no where status!='Close' and t1.process= '".$process."';";
								}
								
								if($process !='')
								{
									$myDB=new MysqliDb();
									$resultBy=$myDB->rawQuery($sqlBy);
									$mysql_error = $myDB->getLastError();
									if(empty($mysql_error))
									{													
										foreach($resultBy as $key=>$value)
										{						
											echo '<option value="'.$value['BacthID'].'"  >'.$value['BacthName'].'</option>';
										}
									}	
								}
										
						      	?>
				            </select>
								
								<label for="txt_batch" class="active-drop-down active">Batch Name *</label>
			   		 		</div>
			   		 		
			   		 		<div class="input-field col s4 m4">
					            <button type="submit" name="btn_Assign_Batch" id="btn_Assign_Batch" class="btn waves-effect waves-green">Assign Batch</button>
			   		 		</div>
			   		 		<br/><br/>	
			   		 	</div>			   		 				   		 				   		 	
					    
					    <div class="input-field col s12 m12 getdetails right-align">
					     
					    	<input type="hidden" class="form-control hidden" id="hid_Department_ID"  name="hid_Department_ID"/>
						    <button type="submit" name="btn_Department_Save" id="btn_Department_Save" class="btn waves-effect waves-green">Get Details</button>
						    
					    </div>
				      
				   </div>
			    
			  
			    <?php 
			    if(isset($_POST['btn_Department_Save']))
			    {
			    	$resultBy = '';
			    	$emptype=(isset($_POST['txt_type'])? $_POST['txt_type'] : null);
			    	if($emptype=='Offline')
			    	{
						$cmid=(isset($_POST['txt_subProcess'])? $_POST['txt_subProcess'] : null);
				    	$clientname=(isset($_POST['hiddenclient'])? $_POST['hiddenclient'] : null);
						$process=(isset($_POST['txt_Process'])? $_POST['txt_Process'] : null);
						$subprocess=(isset($_POST['hiddensubprocessid'])? $_POST['hiddensubprocessid'] : null);
				    	
						$api=INTERVIEW_URL."getCandidateforBatch.php?cmid=".$cmid."&type=".$emptype;
					   //echo $api;
					    $curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, $api);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_HEADER, false);
						$data = curl_exec($curl);	
						$data_array=json_decode($data);	
						$error= $myDB->getLastError();
						
						$myDB=new MysqliDb();
											
						$result=$myDB->rawQuery("truncate table batch_tmp");
						//var_dump($data_array); die;
						if(count($data_array)>0)
						{
							
							$count=0;
					        foreach($data_array as $key=>$value)
					        {
					        	$count++;
					        	$myDB=new MysqliDb();
							
								$result=$myDB->rawQuery('insert into batch_tmp (Intid, Name,doj) values("'.$value->EmployeeID.'", "'.$value->EmployeeName.'", "'.$value->doj.'")');
					        }
					     }  
				     	
				     	$sqlBy ='select Intid as EmpID,name,doj from batch_tmp  where intid not in (select intid from batch_mapping)'; 
						$myDB=new MysqliDb();
						$resultBy=$myDB->rawQuery($sqlBy);
						$mysql_error = $myDB->getLastError();
				     	
					}
			    	
			    	else if($emptype=='Online')
			    	{
						$cmid=(isset($_POST['txt_subProcess'])? $_POST['txt_subProcess'] : null);
				    	$clientname=(isset($_POST['hiddenclient'])? $_POST['hiddenclient'] : null);
						$process=(isset($_POST['txt_Process'])? $_POST['txt_Process'] : null);
						$subprocess=(isset($_POST['hiddensubprocessid'])? $_POST['hiddensubprocessid'] : null);
				    	$process = str_replace(" ","%20",$process);
						$api=INTERVIEW_URL."getCandidateforBatch.php?cmid=".$process."&type=".$emptype;
					   //echo $api;
					    $curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, $api);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_HEADER, false);
						$data = curl_exec($curl);	
						$data_array=json_decode($data);	
						$error= $myDB->getLastError();
						
						$myDB=new MysqliDb();
											
						$result=$myDB->rawQuery("truncate table batch_tmp");
						//var_dump($data_array); die;
						if(count($data_array)>0)
						{
							
							$count=0;
					        foreach($data_array as $key=>$value)
					        {
					        	$count++;
					        	$myDB=new MysqliDb();
							
								$result=$myDB->rawQuery('insert into batch_tmp (Intid, Name,doj) values("'.$value->EmployeeID.'", "'.$value->EmployeeName.'", "'.$value->doj.'")');
					        }
					     }  
				     	
				     	$sqlBy ='select Intid as EmpID,name,doj from batch_tmp  where intid not in (select intid from batch_mapping)'; 
						$myDB=new MysqliDb();
						$resultBy=$myDB->rawQuery($sqlBy);
						$mysql_error = $myDB->getLastError();
				     	
					}
					
					else if($emptype=='empid')
					{
						$cmid=(isset($_POST['txt_subProcess'])? $_POST['txt_subProcess'] : null);
				    	$clientname=(isset($_POST['hiddenclient'])? $_POST['hiddenclient'] : null);
						$process=(isset($_POST['txt_Process'])? $_POST['txt_Process'] : null);
						$subprocess=(isset($_POST['hiddensubprocessid'])? $_POST['hiddensubprocessid'] : null);
						
						$sqlBy ='select distinct t1.EmployeeID,t2.EmployeeName,DATE_FORMAT(t3.dateofjoin,"%d-%b-%Y") as doj from tbl_client_toclient_move t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID join employee_map t3 on t1.EmployeeID=t3.EmployeeID join status_table t4 on t1.EmployeeID=t4.EmployeeID where t1.flag="FM" and t4.Status=2 and t1.new_cm_id="'.$cmid.'" and t3.emp_status="Active" order by t1.id desc'; 
						$myDB=new MysqliDb();
						$data_array=$myDB->rawQuery($sqlBy);
						$mysql_error = $myDB->getLastError();
						
						
						$myDB=new MysqliDb();
											
						$result=$myDB->rawQuery("truncate table batch_tmp");
						//var_dump($data_array); die;
						if(count($data_array)>0)
						{
							
							$count=0;
					        foreach($data_array as $key=>$value)
					        {
					        	$count++;
					        	$myDB=new MysqliDb();
							
								$result=$myDB->rawQuery('insert into batch_tmp (Intid, Name,doj) values("'.$value['EmployeeID'].'", "'.$value['EmployeeName'].'", "'.$value['doj'].'")');
					        }
					     }  
				     	
				     	$sqlBy ='select Intid as EmpID,name,doj from batch_tmp  where intid not in (select intid from batch_mapping)'; 
						$myDB=new MysqliDb();
						$resultBy=$myDB->rawQuery($sqlBy);
						$mysql_error = $myDB->getLastError();
						
					} 
				        
						if(count($resultBy)>0)
						{													
							$process = str_replace("%20"," ",$process);
						?>
						
			   			 <div class="flow-x-scroll" style="margin-top: 10px;width: 100%;padding: 15px; overflow:scroll; height:400px;">
						  															
						  <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						            <th>
							            <input type="checkbox" id="cbAll" name="cbAll" value="ALL">
							            <label for="cbAll">Employee ID</label>
						            </th>
						            <th class="hidden">Employee ID</th>
						            <th>Employee Name</th>
						            <th>DOJ</th>
						            <th>Client</th>
						            <th>Process</th>
						            <th>Sub Process</th>
						            
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       $count=0;
					        foreach($resultBy as $key=>$value){
					        	$count++;
							echo '<tr>';							
							echo '<td class="EmpId"><input type="checkbox" id="cb'.$count.'" class="cb_child" name="cb[]" value="'.$value['EmpID'].'"><label for="cb'.$count.'" style="color: #059977;font-size: 14px;font-weight: bold;}">'.$value['EmpID'].'</label></td>';
							echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="'.$value['EmpID'].'">'.$value['EmpID'].'</a></td>';
							echo '<td class="FullName">'.$value['name'].'</td>';					
							echo '<td class="DOJ">'.$value['doj'].'</td>';					
							echo '<td class="client_name">'.$clientname.'</td>';					
							echo '<td class="process">'.$process.'</td>';					
							echo '<td class="sub_process">'.$subprocess.'</td>';										
							
							echo '</tr>';
							
							
							}	
							?>			       
					    </tbody>
						</table>
						
						</div>
						
						<?php
							 }
						
						else
						{
							echo "<script>$(function(){ toastr.info('No Employee Found.'); }); </script>";
						}   
				}
					?>
				
			
			
			
			</div> 
		</div>
	</div>    
<!--Content Div for all Page End -->  
</div>

<script>
	
$(document).ready(function(){
	//Model Assigned and initiation code on document load
	$('.batchdetails').addClass('hidden');
	$('.getdetails').removeClass('hidden');
	
	$('#divloc1').addClass('hidden');
	$('#divclient1').addClass('hidden');
	$('#divproc1').addClass('hidden');
	$('#divsubproc1').addClass('hidden');
    	
    	
	$('.modal').modal({
			onOpenStart:function(elm)
			{
				
				
			},
			onCloseEnd:function(elm)
			{
				$('#btn_Department_Can').trigger("click");
			}
		});
	// This code for cancel button trigger click and also for model close
     $('#btn_Department_Can').on('click', function(){
     	//alert($('#txt_location').val());
        $('#txt_location').val('NA');
        $('#txt_Process').children().remove();
        $('#txt_date').val('');
        $('#txt_count').val('');
        $('#btn_Department_Save').removeClass('hidden');
        $('#btn_Department_Edit').addClass('hidden');
        
        $('#divloc1').removeClass('hidden');
        $('#divclient1').removeClass('hidden');
		$('#divproc1').removeClass('hidden');
		$('#divsubproc1').removeClass('hidden');
		
		$('#divloc2').addClass('hidden');
		$('#divclient2').addClass('hidden');
		$('#divproc2').addClass('hidden');
		$('#divsubproc2').addClass('hidden');
		
		$('#divstatus').addClass('hidden');
		
		$('#txt_locationhidden').val('');
		$('#txt_clienthidden').val('');
        $('#txt_processhidden').val('');
        $('#txt_subprocesshidden').val('');
        //$('#btn_Department_Can').addClass('hidden');
        
        // This code for remove error span from input text on model close and cancel
        
    });
    
     $('#txt_type').change(function(){
     	$('#txt_location').val('NA');
		$('#txt_Process').empty();
    	$('#txt_subProcess').empty();
    	$('#txt_Client').empty();
    	
    	$('#divloc1').addClass('hidden');
    	$('#divclient1').addClass('hidden');
    	$('#divproc1').addClass('hidden');
    	$('#divsubproc1').addClass('hidden');
    	
    	$('.batchdetails').addClass('hidden');
		$('.getdetails').removeClass('hidden');
    	$('#myTable').html('');
    	
     	if($(this).val()=='Offline' || $(this).val()=='empid')
     	{
     		$('#divloc1').removeClass('hidden');
			$('#divclient1').removeClass('hidden');
			$('#divproc1').removeClass('hidden');
			$('#divsubproc1').removeClass('hidden');
			
		}
		else if($(this).val()=='Online')
		{
			$('#divproc1').removeClass('hidden');
			$('#divclient1').removeClass('hidden');
		
		
		var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
			   
			
					var Resp=xmlhttp.responseText;
					//$('#txt_Client_ach').html(Resp);
					//$('#txt_vertical_head').html(Resp);
					/*$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);*/
					$('#txt_Client').html(Resp);
					$('select').formSelect();
				}
				
			}
			
			//var location = <?php echo $_SESSION["__location"] ?>;
			//alert(el);
			//$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
			xmlhttp.open("GET", "../Controller/getalignmentforbatchmaster.php?mode=" + $(this).val()+"&type=client", true);
			xmlhttp.send();
    	}
     });
    
    $('#txt_location').change(function(){
    	
    	$('#txt_Process').empty();
    	$('#txt_subProcess').empty();
    	var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
			   
			
					var Resp=xmlhttp.responseText;
					//$('#txt_Client_ach').html(Resp);
					//$('#txt_vertical_head').html(Resp);
					/*$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);*/
					$('#txt_Client').html(Resp);
					$('select').formSelect();
				}
				
			}
			
			//var location = <?php echo $_SESSION["__location"] ?>;
			//alert(el);
			//$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
			xmlhttp.open("GET", "../Controller/getalignmentforbatchmaster.php?loc=" + $(this).val()+"&type=client", true);
			xmlhttp.send();
    });
    
    $('#txt_Client').change(function(){
    	$('#txt_subProcess').empty();
    	$('#hiddenclient').val($("#txt_Client option:selected").text());
    	
    	if($('#txt_type').val()=="Offline" || $('#txt_type').val()=="empid")
    	{
			var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
			   
			
					var Resp=xmlhttp.responseText;
					//$('#txt_Client_ach').html(Resp);
					//$('#txt_vertical_head').html(Resp);
					/*$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);*/
					$('#txt_Process').html(Resp);
					$('select').formSelect();
				}
				
			}
			
			xmlhttp.open("GET", "../Controller/getalignmentforbatchmaster.php?loc=" + $('#txt_location').val()+"&client="+ $(this).val()+"&type=Process", true);
			xmlhttp.send();
			
		}
    	
    	else if($('#txt_type').val()=="Online")
    	{
			var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
			   
			
					var Resp=xmlhttp.responseText;
					//$('#txt_Client_ach').html(Resp);
					//$('#txt_vertical_head').html(Resp);
					/*$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);*/
					$('#txt_Process').html(Resp);
					$('select').formSelect();
				}
				
			}
			
			xmlhttp.open("GET", "../Controller/getalignmentforbatchmaster.php?mode=" + $('#txt_type').val()+"&client="+ $(this).val()+"&type=Process", true);
			xmlhttp.send();
		
		}
			
			//var location = <?php echo $_SESSION["__location"] ?>;
			//alert(el);
			//$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
			
    });
    
    $('#txt_Process').change(function(){
    	var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
			   
			
					var Resp=xmlhttp.responseText;
					//$('#txt_Client_ach').html(Resp);
					//$('#txt_vertical_head').html(Resp);
					/*$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);*/
					$('#txt_subProcess').html(Resp);
					$('select').formSelect();
				}
				
			}
			
			//var location = <?php echo $_SESSION["__location"] ?>;
			//alert(el);
			//$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
			xmlhttp.open("GET", "../Controller/getalignmentforbatchmaster.php?loc=" + $('#txt_location').val()+"&client="+ $('#txt_Client').val()+"&process="+ $(this).val()+"&type=SubProcess", true);
			xmlhttp.send();
    });
    
    $('#txt_subProcess').change(function(){
    	$('#hiddensubprocessid').val($("#txt_subProcess option:selected").text());
    	$('#hiddensubprocess').val($('#txt_subProcess').val());
    });
    
    
    // This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
    
	$('#btn_Department_Edit').on('click', function(){
	        var validate=0;
	        
	        
	        var alert_msg='';
	        // <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
	        $("input,select,textarea").each(function(){
	        	var spanID =  "span" + $(this).attr('id');		        	
	        	$(this).removeClass('has-error');
	        	if($(this).is('select'))
				{
					$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
				}
	        	var attr_req = $(this).attr('required');
	        	if(($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown'))
	        	{
					validate=1;	
					$(this).addClass('has-error');
					if($(this).is('select'))
					{
						$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					}
					if ($('#'+spanID).size() == 0) {
				            $('<span id="'+spanID+'" class="help-block"></span>').insertAfter('#'+$(this).attr('id'));
				        }
				    var attr_error = $(this).attr('data-error-msg');
				    if(!(typeof attr_error !== typeof undefined && attr_error !== false))
				    {
						$('#'+spanID).html('Required *');	
					}
					else
					{
						$('#'+spanID).html($(this).attr("data-error-msg"));
					}
				}
	        })
	        
	        if($('#txt_count').val()=='')
	        {
									
				$('#txt_count').addClass("has-error");
	        	if($('#spantxt_count').size() == 0)
				{
		            $('<span id="spantxt_count" class="help-block">*</span>').insertAfter('#txt_count');
		        }
		        validate=1;
			}
		
				
	        		    
	      	if(validate==1)
	      	{		      		
	      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		$('#alert_message').show().attr("class","SlideInRight animated");
	      		$('#alert_message').delay(50000).fadeOut("slow");
				return false;
			} 
	});
    
    $('#btn_Assign_Batch').on('click', function(){
    	 var validate=0;
    	 //alert($('#txt_batch').val());
    	if($('#txt_batch').val()=='NA')
        {
								
			validate=1;
			$('#txt_batch').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
        	if($('#spantxt_batch').size() == 0)
			{
	            $('<span id="spantxt_batch" class="help-block">*</span>').insertAfter('#txt_batch');
	        }
		}
		
		if(validate==1)
      	{		      		
      		
			return false;
		} 
		
    });	
    	
     $('#btn_Department_Save').on('click', function(){
	       
	   var validate=0;
	   var alert_msg='';    
	        if($('#txt_type').val()=='NA')
	        {
									
				validate=1;
				$('#txt_type').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
	        	if($('#spantxt_type').size() == 0)
				{
		            $('<span id="spantxt_type" class="help-block">*</span>').insertAfter('#txt_type');
		        }
			}
	         
	       if($('#txt_location').val()=='NA' && ($('#txt_type').val()=='Offline' || $('#txt_type').val()=='empid'))
	        {
									
				validate=1;
				$('#txt_location').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
	        	if($('#spantxt_location').size() == 0)
				{
		            $('<span id="spantxt_location" class="help-block">*</span>').insertAfter('#txt_location');
		        }
			}
			
			if($('#txt_Client').val()=='NA')
	        {
									
				validate=1;
				$('#txt_Client').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
	        	if($('#spantxt_Client').size() == 0)
				{
		            $('<span id="spantxt_Client" class="help-block">*</span>').insertAfter('#txt_Client');
		        }
			}
			
			if($('#txt_Process').val()=='NA')
	        {
									
				validate=1;
				$('#txt_Process').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
	        	if($('#spantxt_Process').size() == 0)
				{
		            $('<span id="spantxt_Process" class="help-block">*</span>').insertAfter('#txt_Process');
		        }
			}
			
			if($('#txt_subProcess').val()=='NA' && ($('#txt_type').val()=='Offline' || $('#txt_type').val()=='empid'))
	        {
									
				validate=1;
				$('#txt_subProcess').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
	        	if($('#spantxt_subProcess').size() == 0)
				{
		            $('<span id="spantxt_subProcess" class="help-block">*</span>').insertAfter('#txt_subProcess');
		        }
			}
		
			if($('#txt_date').val()=='')
	        {
									
				$('#txt_date').addClass("has-error");
	        	if($('#spantxt_date').size() == 0)
				{
		            $('<span id="spantxt_date" class="help-block">*</span>').insertAfter('#txt_date');
		        }
		        validate=1;
			}
		
			if($('#txt_count').val()=='')
	        {
									
				$('#txt_count').addClass("has-error");
	        	if($('#spantxt_count').size() == 0)
				{
		            $('<span id="spantxt_count" class="help-block">*</span>').insertAfter('#txt_count');
		        }
		        validate=1;
			}
		
		if(validate==1)
      	{		      		
      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
      		$('#alert_message').show().attr("class","SlideInRight animated");
      		$('#alert_message').delay(50000).fadeOut("slow");
			return false;
		} 
		
		$('#hiddentype').val($('#txt_type').val());	
		/*alert($('#hiddentype').val());
		return false;	*/
				
	});  
    // This code for remove error span from input text on model close and cancel
    $(".has-error").each(function(){
		if($(this).hasClass("has-error"))
		{
			$(this).removeClass("has-error");
			$(this).next("span.help-block").remove();
			if($(this).is('select'))
			{
				$(this).parent('.select-wrapper').find("span.help-block").remove();
			}
			if($(this).hasClass('select-dropdown'))
			{
				$(this).parent('.select-wrapper').find("span.help-block").remove();
			}
			
		}
	});
    	// This code for remove error span from all element contain .has-error class on listed events
	
    $('#txt_date').datetimepicker({timepicker:false,format:'Y-m-d',minDate:'+1970/01/01', scrollInput : false});
    
    $('#txt_count').keydown(function(event) 
    {
		    if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || 

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
		    }
		    else {
		    // Ensure that it is a number and stop the keypress
		        if ( event.shiftKey|| (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 ) ){
		        event.preventDefault(); 
		        }
	    }
		    });
});

	$("#cbAll").change(function () {
		    $("input.cb_child:checkbox").prop('checked', $(this).prop("checked"));
		    if($("input.cb_child:checkbox:checked").length>0)
		    {
				$('.batchdetails').removeClass('hidden');
			}
			else
			{
				$('.batchdetails').addClass('hidden')
			}
		    $('select').formSelect();
		    $(".schema-form-section input,.schema-form-section textarea").each(function(index, element)
	        {
		         if($(element).val().length > 0)
		         {
		           $(this).siblings('label, i').addClass('active');
		         }
		         else
		         {
				 	$(this).siblings('label, i').removeClass('active');
				 }
				        
			});
		});
    
    $("input:checkbox").click(function(){
			if($('input:checkbox:checked').length>0)
			{
				$('.batchdetails').removeClass('hidden');
				$('.getdetails').addClass('hidden');
				//alert('1');
				/*checklistdata();
				$('#div_date_1').removeClass('hidden');
				$('#div_duration_1').removeClass('hidden');
				$('#txt_Date_crt_1').removeClass('hidden');*/
			}
			else
			{
				$('.batchdetails').addClass('hidden');
				$('.getdetails').removeClass('hidden');
				//alert('2');
				/*$('#txt_thcheck_Trainer').val('No');
				$('.statuscheck').addClass('hidden');			
				$('#docTable').html('');
				$('#docstable').addClass('hidden');
				
				$('#div_date_1').addClass('hidden');
				$('#div_duration_1').addClass('hidden');
				$('#txt_Date_crt_1').addClass('hidden');
				
				$('#div_date_2').addClass('hidden');
				$('#txt_Date_crt_2').addClass('hidden');
				
				$('#div_date_3').addClass('hidden');
				$('#txt_Date_crt_3').addClass('hidden');
				
				$('#div_date_4').addClass('hidden');
				$('#txt_Date_crt_4').addClass('hidden');
				 
				$('#div_date_5').addClass('hidden');
				$('#txt_Date_crt_5').addClass('hidden');*/
			}
			$('select').formSelect();
		});
// This code for trigger edit on all data table also trigger model open on a Model ID
   	
	function EditData(el)
	{
		var tr = $(el).closest('tr');
        var id = tr.find('.id').text();
        var loc = tr.find('.loc').text();
        var location = tr.find('.location').text();
        var process = tr.find('.process').text();
        var batch_no = tr.find('.batch_no').text();
        var target_date = tr.find('.target_date').text();
        var target_count = tr.find('.target_count').text();
        var status = tr.find('.status').text();
        var array = process.split('|');
        
        $('#divloc1').addClass('hidden');
        $('#divclient1').addClass('hidden');
        $('#divproc1').addClass('hidden');
        $('#divsubproc1').addClass('hidden');
        
        $('#divloc2').removeClass('hidden');
        $('#divclient2').removeClass('hidden');
        $('#divproc2').removeClass('hidden');
        $('#divsubproc2').removeClass('hidden');
        
        $('#divstatus').removeClass('hidden');
        
       // alert(array[0]);
        $('#hid_Department_ID').val(id);
        
        $('#txt_locationhidden').val(location);	
        $('#txt_clienthidden').val(array[0]);	
        	       
        $('#txt_processhidden').val(array[1]);	
        $('#txt_subprocesshidden').val(array[2]);	
        
        
        $('#txt_status').val(status);	
        	       
        $('#txt_date').val(target_date);		       
        $('#txt_count').val(target_count);
        		       
        $('#btn_Department_Save').addClass('hidden');
        $('#btn_Department_Edit').removeClass('hidden');
        
        
        
        
        
        //$('#btn_Department_Can').removeClass('hidden');
        $('#myModal_content').modal('open');
        $("#myModal_content input,#myModal_content textarea").each(function(index, element)
        {
	         if($(element).val().length > 0)
	         {
	           $(this).siblings('label, i').addClass('active');
	         }
	         else
	         {
			 	$(this).siblings('label, i').removeClass('active');
			 }
			        
		});
	}

// This code for trigger del*t*
	
	function ApplicationDataDelete(el)
	{
		var currentUrl = window.location.href;
		var Cnfm=confirm("Do You Want To Delete This ");
		if(Cnfm)
		{
			var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
			   
			
					var Resp=xmlhttp.responseText;
					alert(Resp);
					window.location.href = currentUrl;
					
				
			
				}
			}
		
			xmlhttp.open("GET", "../Controller/DeleteDepartment.php?ID=" + el.id, true);
			xmlhttp.send();
		}
	}

	
	function getProcess(el)
	{
		
		var currentUrl = window.location.href;
		
			var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
			   
			
					var Resp=xmlhttp.responseText;
					$('#txt_Process').html(Resp);
					$('#txt_vertical_head').html(Resp);
					$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);
					$('select').formSelect();
				}
				
			}
			
			var location = <?php echo $_SESSION["__location"] ?>;
			alert(el);
			$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
			xmlhttp.open("GET", "../Controller/getprocessByLocation.php?loc=" + $('#txt_location').val(), true);
			xmlhttp.send();
			
		   
	}
	
	
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>