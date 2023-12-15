<?php  
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
date_default_timezone_set('Asia/Kolkata');
require_once(LIB.'PHPExcel/IOFactory.php');
if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
		exit();
	}
	
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
	exit();
}
error_reporting(E_ERROR );
ini_set('display_errors', 1);
$alert_msg ='';
$client ='NA';
$process = 'NA';
$subprocess = 'NA';
if(isset($_POST['txt_empmap_client']) && !empty($_POST['txt_empmap_process']) && $_POST['txt_empmap_subprocess'] !='NA')
{
	
	$client = $_POST['txt_empmap_client'];
	$process = $_POST['txt_empmap_process'];
	$subprocess = $_POST['txt_empmap_subprocess'];
}

if(isset($_POST['btn_df_Save']))
{
	$myDB = new mysql();
	$_cm_id	= $myDB->query('select distinct  cm_id from new_client_master where client_name="'.$_POST['txt_empmap_client'].'" and process="'.$_POST['txt_empmap_process'].'" and sub_process="'.$_POST['txt_empmap_subprocess'].'" limit 1'); 
	$_cm_id = $_cm_id[0]['new_client_master']['cm_id'];
	
	if(!empty($_cm_id) && $_cm_id !='NA')
	{
		
		$insert_data = array(
		  "cm_id"=> $_cm_id,		
		  "Model"=> $_POST['txt_Model'],
		  "AHT"=> $_POST['txt_AHT'],		  		  
		  "Rate"=> $_POST['txt_Rate'],
		  "MG"=> $_POST['txt_MG'],
		  "CotactType"=> $_POST['txt_CotactType'],
		  "StartDate"=> $_POST['txt_StartDate'],
		  "EndDate"=> $_POST['txt_EndDate'],
		  "Period"=> $_POST['txt_Period'],
		  "RenewalDate"=> $_POST['txt_RenewalDate'],
		  "RateRevision"=> $_POST['txt_RateRevision'],
		  "RevisionDate"=> $_POST['txt_RevisionDate'],
		  "RnP"=> $_POST['txt_RnP'],
		  "Reward"=> $_POST['txt_Reward'],
		  "Penality"=> $_POST['txt_Penality'],
		  "TerminationNotice"=> $_POST['txt_Termination'],
		  "FTE_Defination"=> $_POST['txt_FTEDefination'],
		  "CappingApplicable"=> $_POST['txt_CappingApplicable'],
		  "CappingTarget"=> $_POST['txt_CappingTarget'],
		  "MGConditions"=> $_POST['txt_MGConditions'],
		  "SL"=> $_POST['txt_SL'],
		  "AL"=> $_POST['txt_AL'],
		  "ABND"=> $_POST['txt_ABND'],
		  "createdby"=>  $_SESSION['__user_logid']
		);		
		$myDB = new mysql();
		$flag = $myDB->insert("revenue_contract_bt_master",$insert_data);
		$mysql_Error = mysql_error();
		if($flag)
		{
			$alert_msg = "<span class='text-success'><b>Message :</b>  Contract has been saved successfully.</span>";
		}
		else
		{
			$alert_msg = "<span class='text-danger'><b>Message :</b>  Contract not saved. Error :".$mysql_Error."</span>";
		}
	}
	
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>-:: Contract Master ::-</title>
<?php include(ROOT_PATH.'AppCode/head.mpt'); ?>
<?php include(ROOT_PATH.'AppCode/DataTable.mpt'); ?>
<link rel="stylesheet" href="<?php echo STYLE.'jquery.datetimepicker.css' ;?>"/>
<script src="<?php echo SCRIPT.'jquery.datetimepicker.full.min.js' ;?>"></script>
<style>
	
	.form-control
	{
		    margin-bottom: 5px;
		    min-width: 250px;
		    padding: 0px 0px 0px 10px;
		    height: 30px;
	}
	@media only screen and (max-width : 480px) {
		.form-control
		{
			margin-bottom: 5px;
			min-width: auto;
		}
	 }
		
	button.dt-button, div.dt-button, a.dt-button
	{
		    text-shadow: 1px 1px 1px #fff, 1px 2px 0px rgba(0, 0, 0, 0.38);
		    font-size: 14px;
  			font-weight: bolder;
  			color: black;
		    background-position:5% 50%;
		    background-size: 25px 25px;
		    background-repeat: no-repeat;
		    background-color: white;
		    border: 1px solid #DAD1D1;
		    margin: 2px;
		    padding: 5px 5px 5px 30px ;
		    cursor: pointer;
	}
	div.dt-button-collection button.dt-button, div.dt-button-collection div.dt-button, div.dt-button-collection a.dt-button
	{
		  background-image: url('../Style/img/Page-Icon.png');
	}
	.dataTables_scrollHead
	{
		height: 45px;
	}
	#buttons_copy 
	{
		    
		    background-image: url('../Style/img/1451997178_Copy.png');
		    
	}
	#buttons_csv 
	{
		    
		    background-image: url('../Style/img/csv-icon.png');
		    
	}
	#buttons_excel 
	{
		    
		    background-image: url('../Style/img/excel-xls-icon.png');
		    
	}
	#buttons_pdf
	{
		    
		    background-image: url('../Style/img/pdf-icon.png');
		    
	}
	#buttons_print 
	{
		    
		    background-image: url('../Style/img/1451997207_vector_66_15.png');
		   
	}
	#buttons_page_length 
	{
		    
		    background-image: url('../Style/img/Page-Icon.png');
		   
	}
	.dt-buttons
	{
	 	float:left;
	}
	table.dataTable tbody th, table.dataTable tbody td
	{
		padding: 4px;
	}
	table.data {border:none;font-size: 12px;}
	table.data td{border:none; padding: 5px; vertical-align:top;}

	table.data {border: 1px solid #dcdcdc; margin: 10px 0px 20px 0px; width: 97%; border-collapse: collapse;font-size: 12px;}
	table.data  th {padding: 3px 4px 3px 4px;border: 1px solid #346F0F; background-color:#71B335;text-shadow: 1px 0px #003362;color: #fff }
	table.data td {padding: 4px; border: 1px solid #dcdcdc;color: #5b5b5b;vertical-align: top;}
	table.data tr {background: #fff}
	table.data tr:hover {background: #E0E0E0;}
	table.data tr small a{color: #fff;}
	table.data tr small{color: #fff;}
	table.data tr:hover small a{color: #527e19;}
	table.data tr:hover small a:hover{color: #000;}
	table.data tr:hover small {color: #5b5b5b;}	
	.imgBtn
	{
		padding-left: 5px;
		cursor: pointer;
	}
	table.dataTable tbody th, table.dataTable tbody td
	{
		white-space: nowrap;
	}
	label {
	    min-width: 200px;
	}
</style>
<script>
	$(document).ready(function(){
		$('#txt_RevisionDate').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});
		
		$('#txt_EndDate,#txt_StartDate').datetimepicker({
			timepicker:false,
			format:'Y-m-d',
			onChangeDateTime:function(dp,$input){
				$('#txt_StartDate').closest('div').removeClass('has-error');
				
			    
			    if($('#txt_StartDate').val() == "")
			    {
					$('#txt_StartDate').closest('div').addClass('has-error');
					$('#txt_StartDate').focus();
					$input.val("");
				}				
				else
				{
					if($('#txt_EndDate').val() != "" && $('#txt_StartDate').val() != "")
					{
						
						var start = new Date($('#txt_StartDate').val());
						var end = new Date($('#txt_EndDate').val());
						
						var diff = new Date(end - start);

						// get days
						console.log(diff);
						var days = diff/1000/60/60/24;
						if(end < start)
						{
							$('#txt_StartDate').closest('div').addClass('has-error');
							$('#txt_StartDate').focus();
							$("#txt_RenewalDate").val("");
							$("#txt_Period").val("0");
						}
						else
						{
							$("#txt_Period").val(days + 1);
						    var date = new Date($('#txt_EndDate').val());
							date.setDate(date.getDate() + 1);
							var yyyy = date.getFullYear().toString();
						    var mm = (date.getMonth()+1).toString(); // getMonth() is zero-based
						    var dd  = date.getDate().toString();
						    yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); 
			    
							$("#txt_RenewalDate").val(yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]));
							
						}
						
					}
					
				}
			    
			  }

		});
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
				        scrollY: 195,				        
				        scrollCollapse: true,
				        lengthMenu: [
				            [ 5,10, 25, 50, -1 ],
				            ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
				        ],
							"sScrollY" : "350",
							"sScrollX" : "100%",
				         buttons: [
						          
						        {
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
						        'print',
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
						        },'copy','pageLength'
						        
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
</head>
<body>
<form name="indexForm" role="form"  id="indexForm" method="post"  action="<?php echo($_SERVER['REQUEST_URI']); ?>"  enctype="multipart/form-data">
<div class="container MainDiv" id="wrap">
  
 <?php include(ROOT_PATH.'AppCode/header.mpt'); ?>

 <div id="main" class="clearfix">
	 <div id="left_menu"  class="container drawer drawer--left pull-left">	
		<?php include(ROOT_PATH.'AppCode/left_menu.mpt'); ?>
	 </div>

	<div class="container" style="overflow-y: auto;overflow-x: hidden;">
			<div>
				<h4 style="border-bottom: 2px solid #8DC73E;padding: 5px;border-right:2px solid green;text-shadow: 1px 1px 1px #FFFFFF, 1px 2px 0px rgba(2, 2, 2, 0.36);border-radius: 4px;box-shadow: 0px 0px 1px 1px #49ab0c;">Contract Master</h4>
			</div>
			 <div class="container" style="height: 89%;overflow: auto;">
			 
			 <div class="form-inline form-group">
			 
			    <div  class="alert" id="alert_message">
			    	<div class="container" id="alert_msg"><?php echo $alert_msg;?></div>
			    	<a href="javascript:void(0);" id="alert_msg_close" style="position: absolute;background-image: url('../Style/images/Oxygen480-actions-dialog-close.png');float: right;margin: 0px;padding: 0px;right: 0px;color: royalblue;height: 40px;width: 40px;background-position: 20px;background-size: 20px 20px;background-repeat: no-repeat;top: 0px;"></a></div>
			    		 	
			    <div class="form-group col-sm-6"  style="padding: 0px;">
			         <label for="txt_empmap_client">Client :</label>
			     		
				            <select class="form-control clsInput" id="txt_empmap_client" name="txt_empmap_client"  style="max-width: 300px;min-width: 300px;">
				            <option value="NA">----Select----</option>	
						      	<?php
						      					
												$sqlBy = array(
													'table' => 'client_master',
													'fields' => 'client_id,client_name',
													'condition' =>"1");
												$myDB=new mysql();
												$resultBy=$myDB->select($sqlBy);
												if($resultBy){		
														
													foreach($resultBy as $key=>$value){
														if($client == $value['client_master']['client_id'])
														{
															echo '<option value="'.$value['client_master']['client_id'].'"  selected>'.$value['client_master']['client_name'].'</option>';
														}
														else
														{
															echo '<option value="'.$value['client_master']['client_id'].'">'.$value['client_master']['client_name'].'</option>';
														}
																					
														
													}
		
												}
												
						      	?>
				            </select>
 							
			    </div>
			    <div class="form-group col-sm-6"  style="padding: 0px;">
			      <label for="txt_empmap_process">Process :</label>
			     		
				            <select class="form-control clsInput" id="txt_empmap_process" name="txt_empmap_process"  style="max-width: 300px;min-width: 300px;">
				           		<option value="NA">----Select----</option>
				           		<?php 
				           		if($process != 'NA' && !empty($process))
				           		{
									echo '<option selected>'.$process.'</option>';
								}
				           		?>
				            </select>
 							
			    </div>
			    <div class="form-group col-sm-6"  style="padding: 0px;">
			      <label for="txt_empmap_subprocess">Sub Process :</label>
			     		
				            <select class="form-control clsInput"  id="txt_empmap_subprocess" name="txt_empmap_subprocess"  style="max-width: 300px;min-width: 300px;">
				           		<option value="NA">----Select----</option>
				           		<?php 
				           		if($subprocess != 'NA' && !empty($subprocess))
				           		{
									echo '<option selected>'.$subprocess.'</option>';
								}
				           		?>
				           	</select>
 							
			    </div>
		             <div class="form-group col-sm-6" style="padding: 0px;">
							      <label for="txt_Model">Model :</label>
							     		<div class="form-group">
								            <select class="form-control" id="txt_Model" name="txt_Model" style="max-width: 300px;min-width: 300px;" >
								            <option>FTE</option>
								            <option>CPM</option>
								            </select>	
								         </div> 
					</div>
		             <div class="form-group col-sm-6 div_cpm_show" style="padding: 0px;">
							      <label for="txt_AHT">AHT :</label>
							     		<div class="form-group">
								            <input type="number" class="form-control" min="0" id="txt_AHT" step="any" name="txt_AHT" style="max-width: 300px;min-width: 300px;" value="0"/>	
								         </div> 
							</div> 
							<div class="form-group col-sm-6" style="padding: 0px;">
							      <label for="txt_Rate" id="lbl_rate">Rate / FTE  :</label>
							     		<div class="form-group">
								            <input type="number" class="form-control" step="any"  min="0" id="txt_Rate" name="txt_Rate" style="max-width: 300px;min-width: 300px;"  value="0"/>	
								         </div> 
							</div> 							
							<div class="form-group col-sm-6 div_cpm_show" style="padding: 0px;">
							      <label for="txt_MG">MG :</label>
							     		<div class="form-group">
								            <input type="number" class="form-control" step="any" min="0" id="txt_MG" name="txt_MG" style="max-width: 300px;min-width: 300px;" value="0" />	
								         </div> 
							</div> 
							
							 
							<div class="form-group col-sm-6" style="padding: 0px;">
							      <label for="txt_CotactType">Cotact Type :</label>
							     		<div class="form-group">
								            <select class="form-control" id="txt_CotactType" name="txt_CotactType" style="max-width: 300px;min-width: 300px;" >
								            <option>Contract</option>
								            <option>LOI</option>
								            <option>NFA</option>
								            
								            </select>	
								         </div> 
							</div>
							<div class="form-group col-sm-6" style="padding: 0px;">
							      <label for="txt_StartDate">Start Date :</label>
							     		<div class="form-group">
								            <input type="text" readonly="true" class="form-control" id="txt_StartDate" name="txt_StartDate" style="max-width: 300px;min-width: 300px;" />	
								         </div> 
							</div>
							<div class="form-group col-sm-6" style="padding: 0px;">
							      <label for="txt_EndDate">End Date :</label>
							     		<div class="form-group">
								            <input type="text" readonly="true" class="form-control" id="txt_EndDate" name="txt_EndDate" style="max-width: 300px;min-width: 300px;" />	
								         </div> 
							</div>
							<div class="form-group col-sm-6" style="padding: 0px;">
							      <label for="txt_Period">Period :</label>
							     		<div class="form-group">
								            <input type="number" readonly="true" class="form-control" step="any" min="0" id="txt_Period" name="txt_Period" style="max-width: 300px;min-width: 300px;"  value="0"/>	
								         </div> 
							</div> 
							<div class="form-group col-sm-6" style="padding: 0px;">
							      <label for="txt_RenewalDate">Renewal Date :</label>
							     		<div class="form-group">
								            <input type="text" readonly="true" class="form-control" id="txt_RenewalDate" name="txt_RenewalDate" style="max-width: 300px;min-width: 300px;" />	
								         </div> 
							</div>
							<div class="form-group col-sm-6" style="padding: 0px;">
							      <label for="txt_RateRevision">Rate Revision :</label>
							     		<div class="form-group">
								            <select class="form-control" id="txt_RateRevision" name="txt_RateRevision" style="max-width: 300px;min-width: 300px;" >
								            
								            
								            <option>NO</option>
								            <option>YES</option>
								            </select>	
								         </div> 
							</div>
							<div class="form-group col-sm-6 div_rate_rev" style="padding: 0px;">
							      <label for="txt_RevisionDate">Revision Date :</label>
							     		<div class="form-group">
								            <input type="text" readonly="true" class="form-control" id="txt_RevisionDate" name="txt_RevisionDate" style="max-width: 300px;min-width: 300px;" />	
								         </div> 
							</div>
							
							<div class="form-group col-sm-6" style="padding: 0px;">
							      <label for="txt_RnP">RnP :</label>
							     		<div class="form-group">
								            <select class="form-control" id="txt_RnP" name="txt_RnP" style="max-width: 300px;min-width: 300px;" >
								            
								            
								            <option>NO</option>
								            <option>YES</option>
								            </select>	
								         </div> 
							</div>
							<div class="form-group col-sm-6 div_rnp_show" style="padding: 0px;">
							      <label for="txt_Reward">Reward :</label>
							     		<div class="form-group">
								            <input type="number" class="form-control" step="any" min="0" id="txt_Reward" name="txt_Reward" style="max-width: 300px;min-width: 300px;" />	
								         </div> 
							</div> 
							<div class="form-group col-sm-6 div_rnp_show" style="padding: 0px;">
							      <label for="txt_Penality">Penality :</label>
							     		<div class="form-group">
								            <input type="number" class="form-control" step="any" min="0" id="txt_Penality" name="txt_Penality" style="max-width: 300px;min-width: 300px;" />	
								         </div> 
							</div> 
							<div class="form-group col-sm-6" style="padding: 0px;">
							      <label for="txt_Termination">Termination Notice (Days) :</label>
							     		<div class="form-group">
								            <input type="number" class="form-control" min="0" id="txt_Termination" name="txt_Termination" style="max-width: 300px;min-width: 300px;" />	
								         </div> 
							</div>
							<div class="form-group col-sm-6 div_fte_show" style="padding: 0px;">
							      <label for="txt_FTEDefination">FTE Defination :</label>
							     		<div class="form-group">
								            <input type="text" max="250" class="form-control" id="txt_FTEDefination" name="txt_FTEDefination" style="max-width: 300px;min-width: 300px;" />	
								         </div> 
							</div> 
							<div class="form-group col-sm-6 div_fte_show" style="padding: 0px;">
							      <label for="txt_CappingApplicable">Capping Applicable :</label>
							     		<div class="form-group">
								            <select class="form-control" id="txt_CappingApplicable" name="txt_CappingApplicable" style="max-width: 300px;min-width: 300px;" >
								            
								            <option>YES</option>
								            <option>NO</option>
								            
								            </select>	
								         </div> 
							</div>
							<div class="form-group col-sm-6 div_fte_show" style="padding: 0px;">
							      <label for="txt_CappingTarget">Capping Target (mins) :</label>
							     		<div class="form-group">
								            <input type="number" class="form-control" min="0" id="txt_CappingTarget" name="txt_CappingTarget" style="max-width: 300px;min-width: 300px;" />	
								         </div> 
							</div>
							<div class="form-group col-sm-6 div_cpm_show" style="padding: 0px;">
							      <label for="txt_MGConditions">MG Conditions :</label> 
							     		<div class="form-group">
								            <select class="form-control" id="txt_MGConditions" name="txt_MGConditions" style="max-width: 300px;min-width: 300px;" >
								            
								            <option>YES</option>
								            <option>NO</option>
								            
								            </select>	
								         </div> 
							</div>
							<div class="form-group col-sm-6 div_cpm_show" style="padding: 0px;">
							      <label for="txt_SL">SL :</label>
							     		<div class="form-group">
								            <input type="number"  class="form-control" step="any" min="0" id="txt_SL" name="txt_SL" style="max-width: 300px;min-width: 300px;" />	
								         </div> 
							</div> 
							<div class="form-group col-sm-6 div_cpm_show" style="padding: 0px;">
							      <label for="txt_AL">AL :</label>
							     		<div class="form-group">
								            <input type="number"  class="form-control" step="any" min="0" id="txt_AL" name="txt_AL" style="max-width: 300px;min-width: 300px;" />	
								         </div> 
							</div> 
							<div class="form-group col-sm-6 div_cpm_show" style="padding: 0px;">
							      <label for="txt_ABND">ABND :</label>
							     		<div class="form-group">
								            <input type="number"  class="form-control" step="any" min="0" id="txt_ABND" name="txt_ABND" style="max-width: 300px;min-width: 300px;" />	
								         </div> 
							</div> 

						    
		        </div> 
		    <button type="submit" name="btn_df_Save" id="btn_df_Save" class="button button-rounded button-3d-action"><i class="fa fa-upload"></i> Save Contract</button>
		    <hr/>
		    
			    <?php 
					if($_cm_id != 'NA')
					{
						$sqlConnect = 'select * from (SELECT client_master.client_name `Client`, process as `Process`,sub_process `Sub Process`,   Model, AHT, Rate, MG, CotactType, StartDate, EndDate, Period, RenewalDate, RateRevision, RevisionDate, RnP, Reward, Penality, TerminationNotice, FTE_Defination, CappingApplicable, CappingTarget, MGConditions, SL, AL, ABND, revenue_contract_bt_master.createdon `Created On`, pd.EmployeeName `Created By`, pd1.EmployeeName `Account Head`  FROM revenue_contract_bt_master  inner join new_client_master on new_client_master.cm_id = revenue_contract_bt_master.cm_id  inner join client_master  on new_client_master.client_name = client_master.client_id left outer join personal_details pd  on pd.EmployeeID = revenue_contract_bt_master.createdby left outer join personal_details pd1  on pd1.EmployeeID = new_client_master.account_head) t1';
						
						$myDB=new mysql();
						$result=$myDB->query($sqlConnect);
						if($result){?>
							
				   			 <div class="panel panel-default" style="margin-top: 10px;">
							  <div class="panel-body"  >																															<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
							    <thead>
							        <tr>
							        	<?php
						        			foreach($result[0]['t1'] as $key=>$value){
						        				
						        				echo '<th>'.$key.'</th>';	
						        			}
							        	?>
							            
							        </tr>
							    </thead>
						    <tbody>					        
						       <?php
						        foreach($result as $key=>$value){
								echo '<tr>';							
								foreach($value['t1'] as $k=>$val)
								{
									echo '<td>'.$val.'</td>';
								}
								echo '</tr>';
								}	
								?>			       
						    </tbody>
							</table>
							  </div>
							</div>
							<?php
						 } 
					}
					
					
					?>
				
			   
			  </div>
		</div>
	

       
       
</div>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
</div>

<script>
	$(document).ready(function(){
		$('#alert_msg_close').click(function(){
			$('#alert_message').hide();
		});
		if($('#alert_msg').text()=='')
		{
			$('#alert_message').hide();
		}
		else
		{
			$('#alert_message').delay(5000).fadeOut("slow");
		}
		$(".div_cpm_show").addClass("hidden");
		$(".div_rnp_show").addClass("hidden");
		$(".div_fte_show").removeClass("hidden");
		$(".div_rate_rev").addClass("hidden");
		$("#txt_Model").on("change",function(){
			$("#txt_CappingTarget,#txt_AHT,#txt_MG").val("0");
			$("#txt_ABND,#txt_AL,#txt_SL").val("");
			$("#txt_FTEDefination").val("");
			
			
			if($(this).val() == "CPM")
			{ 
				$("#lbl_rate").text("Rate / Min");
				$(".div_cpm_show").removeClass("hidden");
				$(".div_fte_show").addClass("hidden");
			}
			else
			{
				$("#lbl_rate").text("Rate / FTE");
				$(".div_cpm_show").addClass("hidden");
				$(".div_fte_show").removeClass("hidden");
			}
		});
		
		$("#txt_RnP").on("change",function(){
			$("#txt_Reward,#txt_Penality").val("0");
			if($(this).val() == "YES")
			{
				$(".div_rnp_show").removeClass("hidden");
				
			}
			else
			{
				$(".div_rnp_show").addClass("hidden");
				
			}
		});
		
		$("#txt_RateRevision").on("change",function(){
			$("#txt_RevisionDate").val("");
			if($(this).val() == "YES")
			{
				$(".div_rate_rev").removeClass("hidden");
				
			}
			else
			{
				$(".div_rate_rev").addClass("hidden");
				
			}
		});
		  $('#txt_empmap_client').change(function(){
	    		var tval = $(this).val();
	    		value_click_pro =2;
	    		var location = <?php echo $_SESSION["__location"] ?>;
			    $.ajax({
				  url: <?php echo '"'.URL.'"';?>+"Controller/getprocess.php?id="+tval+"&loc="+location
				}).done(function(data) { // data what is sent back by the php page
					$('#txt_empmap_process').html(data);
					$('#txt_empmap_process').val('NA');
					$('#txt_empmap_subprocess').val('NA');
				});
	    });
	     $('#txt_empmap_process').change(function(){
	    		var tval = $(this).val();
	    		value_click_spro=2;
	    		var id = $('#txt_empmap_client').val();
	    		var location = <?php echo $_SESSION["__location"] ?>;
			    $.ajax({
				  url: <?php echo '"'.URL.'"';?>+"Controller/getsubprocess.php?id="+id+"&proc="+tval+"&loc="+location
				}).done(function(data) { // data what is sent back by the php page
					$('#txt_empmap_subprocess').html(data);
				});
	    });
		    $('#btn_df_Save').on('click', function(){
		        var validate=0;
		        var alert_msg='';
		        	 
		       
		        $('#txt_AHT').closest('div').removeClass('has-error');	
		        $('#txt_Model').closest('div').removeClass('has-error');	
		        $('#txt_Rate').closest('div').removeClass('has-error');	
		        $('#txt_MG').closest('div').removeClass('has-error');	
		        
		        $('#txt_empmap_client').closest('div').removeClass('has-error');		
		        $('#txt_empmap_process').closest('div').removeClass('has-error');		
		        $('#txt_empmap_subprocess').closest('div').removeClass('has-error');		
		        
		        $('#txt_CappingApplicable').closest('div').removeClass('has-error');
		        $('#txt_FTEDefination').closest('div').removeClass('has-error');
		        $('#txt_CappingTarget').closest('div').removeClass('has-error');
		        
		        
		        $('#txt_StartDate').closest('div').removeClass('has-error');
		        $('#txt_EndDate').closest('div').removeClass('has-error');
		        $('#txt_RenewalDate').closest('div').removeClass('has-error');
		        
		        
		       if($('#txt_empmap_client').val()=='NA'||$('#txt_empmap_client').val()==null ||$('#txt_empmap_client').val()=='')
		        {
					$('#txt_empmap_client').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> Client should not be Empty </li>';
				}
				if($('#txt_empmap_process').val()=='NA'||$('#txt_empmap_process').val()==null ||$('#txt_empmap_process').val()=='')
		        {
					$('#txt_empmap_process').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> Process should not be Empty </li>';
				}
				if($('#txt_empmap_subprocess').val()=='NA'||$('#txt_empmap_subprocess').val()==null ||$('#txt_empmap_subprocess').val()=='')
		        {
					$('#txt_empmap_subprocess').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> Sub Process should not be Empty </li>';
				}
				
				if($('#txt_Model').val()=='NA'||$('#txt_Model').val()==null ||$('#txt_Model').val()=='')
		        {
					$('#txt_Model').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> Model should not be Empty </li>';
				}
				if($('#txt_Model').val() == "FTE")
				{
					if($('#txt_CappingApplicable').val()=='NA'||$('#txt_CappingApplicable').val()==null ||$('#txt_CappingApplicable').val()=='')
			        {
						$('#txt_CappingApplicable').closest('div').addClass('has-error');
						validate=1;
						alert_msg+='<li>Capping Applicable should not be Empty </li>';
					}
					if($('#txt_FTEDefination').val()=='NA'||$('#txt_FTEDefination').val()==null ||$('#txt_FTEDefination').val()=='')
			        {
						$('#txt_FTEDefination').closest('div').addClass('has-error');
						validate=1;
						alert_msg+='<li> FTE Defination should not be Empty </li>';
					}
					if($('#txt_CappingTarget').val()=='NA'||$('#txt_CappingTarget').val()==null ||$('#txt_CappingTarget').val()=='')
			        {
						$('#txt_CappingTarget').closest('div').addClass('has-error');
						validate=1;
						alert_msg+='<li> Capping Target should not be Empty </li>';
					}
				}
				if($('#txt_Model').val() == "CPM")
				{
					if($('#txt_AHT').val()=='NA'||$('#txt_AHT').val()==null ||$('#txt_AHT').val()=='')
			        {
						$('#txt_AHT').closest('div').addClass('has-error');
						validate=1;
						alert_msg+='<li> AHT should not be Empty </li>';
					}
					if($('#txt_MG').val()=='NA'||$('#txt_MG').val()==null ||$('#txt_MG').val()=='')
			        {
						$('#txt_MG').closest('div').addClass('has-error');
						validate=1;
						alert_msg+='<li> MG should not be Empty </li>';
					}
				}
				if($('#txt_Rate').val()=='NA'||$('#txt_Rate').val()==null ||$('#txt_Rate').val()=='')
		        {
					$('#txt_Rate').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> Rate / Min / FTE should not be Empty </li>';
				}		
				if($('#txt_StartDate').val()=='NA'||$('#txt_StartDate').val()==null ||$('#txt_StartDate').val()=='')
		        {
					$('#txt_StartDate').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> Start Date should not be Empty </li>';
				}
				if($('#txt_EndDate').val()=='NA'||$('#txt_EndDate').val()==null ||$('#txt_EndDate').val()=='')
		        {
					$('#txt_EndDate').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> End Date should not be Empty </li>';
				}
				if($('#txt_RenewalDate').val()=='NA'||$('#txt_RenewalDate').val()==null ||$('#txt_RenewalDate').val()=='')
		        {
					$('#txt_RenewalDate').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> Renewal Date should not be Empty </li>';
				}
				
		       
		      	if(validate==1)
		      	{		      		
		      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
		      		$('#alert_message').show().attr("class","SlideInRight animated");
		      		$('#alert_message').delay(5000).fadeOut("slow");
					return false;
				}
		       
		    });
	});	
	
	
</script>
</form>
</body>
</html>