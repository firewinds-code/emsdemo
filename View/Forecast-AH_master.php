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
$alert_msg ='';
$_cm_id = 'NA';
if(isset($_POST['btn_df_Save']))
{
	
	$btnUploadCheck=1;
	$noData_Uploadfor_down='';
	$target_dir = ROOT_PATH.'Upload/';
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
	
	// Check if file already exists
	/*if (file_exists($target_file)) {
	    $alert_msg =$alert_msg."<p  class='msgFile text-danger'>Sorry, file already exists.</p>";
	    $uploadOk = 0;
	}*/
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 5000000) {
	    $alert_msg =$alert_msg."<p  class='msgFile text-danger'>Sorry, your file is too large of Size ".$_FILES["fileToUpload"]["size"].".</p>";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($FileType != "xlsx") {
	    $alert_msg =$alert_msg." <p  class='msgFile text-danger'> Sorry, only XLS and XLSX files are allowed.</p>";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    $alert_msg =$alert_msg." <p class='msgFile text-danger'> Sorry, your file was not uploaded.</p>";
	// if everything is ok, try to upload file
	} 
	else
	{
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
	    {
	    	$alert_msg =$alert_msg."<p  class='msgFile text-success'>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. </p>";
					       
			$uploader = $_SESSION['__user_logid'];
	        $document = PHPExcel_IOFactory::load($target_file);
			// Get the active sheet as an array
			$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
			
			//print_r($activeSheetData.'<br/>');
			 $alert_msg=$alert_msg."<p  class='msgFile text-success'>Rows available In Sheet : <code>" . (count($activeSheetData)-1) . "</code></p>";
			 $row_counter=0;
			 $EmployeeCounter=0;
			 $flag=0;
			 $validate = 0;
			 $noData_Uploadfor = '';
			 foreach ($activeSheetData as $row)
			 {
			 	$date_current_tab = '';
			 	
				if(is_numeric($row['A']))
				{
					$date_current_tab = (($row['A'] - 25569) * 86400);
				}
				elseif(strtotime($row['A']))
				{
					$date_current_tab = strtotime($row['A']);
				}
			 	if($row_counter > 0 && !empty($row['A']) && $row['A']!='' && $date_current_tab)
			 	{
			 		
			 		if($_POST['txt_UploadType'] == 'FTE_M' )
			 		{
						$date_current_tab = date('Y-m-01',$date_current_tab);			 		 
						$_cm_id = $_POST['txt_cm_id'];
		
						$myDB = new mysql();
						$check_dt_rev = $myDB->query("SELECT id FROM revenue_fte_mtd where cm_id = '".$_cm_id."' and date_forecast ='".$date_current_tab."';");
						
						
						if(count($check_dt_rev) > 0 && $check_dt_rev)
						{
							$id_revenue_fte_mtd  = $check_dt_rev[0]['revenue_fte_mtd']['id'];
							if(is_numeric($id_revenue_fte_mtd) && !empty($id_revenue_fte_mtd))
							{
								$update_data = array(
									"date_forecast"=> $date_current_tab,
									"FTE"=> $row['B'],
									"FTE_def"=> $row['C'],
									"Cons_login"=> $row['D'],						
									"modifiedby"=> $_SESSION['__user_logid'],
									"modifiedon"=> date('Y-m-d H:i:s',time())
								);
								
								$myDB = new mysql();
								$flag = $myDB->update("revenue_fte_mtd",$update_data,"id=".$id_revenue_fte_mtd);
								if($flag)
								{
									//$alert_msg=$alert_msg."<span class='text-success'><b>Message :</b>  Forecast has been saved successfully for '".$row['A']."'.</span><br />";
								}
								else
								{
									$alert_msg=$alert_msg."<span class='text-danger'><b>Message :</b>  Data not saved for '".$row['A']."'. Error :".$mysql_Error."</span><br />";
								}
							}
							else
							{
								$alert_msg=$alert_msg."<span class='text-danger'><b>Message :</b>  Data not saved for '".$row['A']."'. Error: <code>No data id found for Edit</code>.</span><br />";
							}
						}
						else
						{
								$insert_data = array(
									"cm_id"=> $_cm_id,
									"date_forecast"=> $date_current_tab,
									"FTE"=> $row['B'],
									"FTE_def"=> $row['C'],
									"Cons_login"=> $row['D'],						
									"createdby"=> $_SESSION['__user_logid']
								);
								$myDB = new mysql();
								$flag = $myDB->insert("revenue_fte_mtd",$insert_data);
								if($flag)
								{
									//$alert_msg=$alert_msg."<span class='text-success'><b>Message :</b>  Forecast has been saved successfully for '".$row['A']."'.</span><br />";
								}
								else
								{
									$alert_msg=$alert_msg."<span class='text-danger'><b>Message :</b>  Data not saved for '".$row['A']."'. Error :".$mysql_Error."</span><br />";
								}
						}
			 			$count++;
					}
			 		else if($_POST['txt_UploadType'] == 'CPM_M')
			 		{
						$date_current_tab = date('Y-m-01',$date_current_tab);			 		 
						$_cm_id = $_POST['txt_cm_id'];
		
						$myDB = new mysql();
						$check_dt_rev = $myDB->query("SELECT id FROM revenue_cpm_mtd where cm_id = '".$_cm_id."' and date_forecast ='".$date_current_tab."';");
						
						
						if(count($check_dt_rev) > 0 && $check_dt_rev)
						{
							$id_revenue_fte_mtd  = $check_dt_rev[0]['revenue_cpm_mtd']['id'];
							if(is_numeric($id_revenue_fte_mtd) && !empty($id_revenue_fte_mtd))
							{
								$update_data = array(
									"date_forecast"=> $date_current_tab,
									"AHT"=> $row['B'],
									"Forecast"=> $row['C'],						
									"modifiedby"=> $_SESSION['__user_logid'],
									"modifiedon"=> date('Y-m-d H:i:s',time())
								);
								
								$myDB = new mysql();
								$flag = $myDB->update("revenue_cpm_mtd",$update_data,"id=".$id_revenue_fte_mtd);
								if($flag)
								{
									//$alert_msg=$alert_msg."<span class='text-success'><b>Message :</b>  Forecast has been saved successfully for '".$row['A']."'.</span><br />";
								}
								else
								{
									$alert_msg=$alert_msg."<span class='text-danger'><b>Message :</b>  Data not saved for '".$row['A']."'. Error :".$mysql_Error."</span><br />";
								}
							}
							else
							{
								$alert_msg=$alert_msg."<span class='text-danger'><b>Message :</b>  Data not saved for '".$row['A']."'. Error: <code>No data id found for Edit</code>.</span><br />";
							}
						}
						else
						{
								$insert_data = array(
									"cm_id"=> $_cm_id,
									"date_forecast"=> $date_current_tab,
									"AHT"=> $row['B'],
									"Forecast"=> $row['C'],						
									"createdby"=> $_SESSION['__user_logid']
								);
								$myDB = new mysql();
								$flag = $myDB->insert("revenue_cpm_mtd",$insert_data);
								if($flag)
								{
									//$alert_msg=$alert_msg."<span class='text-success'><b>Message :</b>  Forecast has been saved successfully for '".$row['A']."'.</span><br />";
								}
								else
								{
									$alert_msg=$alert_msg."<span class='text-danger'><b>Message :</b>  Data not saved for '".$row['A']."'. Error :".$mysql_Error."</span><br />";
								}
						}
			 			$count++;
					}
				}
				$row_counter++;
			 }
								 		
								 		
			 if($count > 0 && $validate == 0)
             {
             	$alert_msg =$alert_msg."<p  class='msgFile text-danger'>Total ".$count." Record  for ".($row_counter - 1) ." Employees are Updated Sucessfully.</p>";
             	
             	if(file_exists($target_dir . basename($_FILES["fileToUpload"]["name"])))
				{
					$ext = pathinfo($target_file, PATHINFO_EXTENSION);
					rename($target_file,$target_dir.time().'_'.$uploader."_".$_POST['txt_UploadType']."_Forecast_AccountHead.".$ext);
				}
             }
             else
              $alert_msg =$alert_msg."<p  class='msgFile text-danger'>Sorry, there was an error uploading your file ".$mysql_error."</p>";
		}
		else
		{
			
        	$alert_msg =$alert_msg."<p  class='msgFile text-danger'>Sorry, there was an error uploading your file. </p> ";
    	}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>-:: Forecast-AH Master ::-</title>
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
	    min-width: 100px;
	}
	/*.dataTables_scrollHead {
	    height: 80px;
	}*/
</style>
<script>
	$(document).ready(function(){
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

	<div class="container">
		<div class="container" style="overflow-y: auto;overflow-x: hidden;">
			<div>
				<h4 style="border-bottom: 2px solid #8DC73E;padding: 5px;border-right:2px solid green;text-shadow: 1px 1px 1px #FFFFFF, 1px 2px 0px rgba(2, 2, 2, 0.36);border-radius: 4px;box-shadow: 0px 0px 1px 1px #49ab0c;">Forecast-AH Master</h4>
			</div>
			 <div class="form-inline form-group" >
			 
			 <div class="form-group col-sm-12">
			    <div  class="alert" id="alert_message">
			    	<div class="container" id="alert_msg"><?php echo $alert_msg;?></div>
			    	<a href="javascript:void(0);" id="alert_msg_close" style="position: absolute;background-image: url('../Style/images/Oxygen480-actions-dialog-close.png');float: right;margin: 0px;padding: 0px;right: 0px;color: royalblue;height: 40px;width: 40px;background-position: 20px;background-size: 20px 20px;background-repeat: no-repeat;top: 0px;"></a></div>
			    </div>
			 	
			    <div class="form-group col-sm-12" style="padding-bottom:5px; ">
			    <div class="form-group col-sm-6" style="padding: 0px;">
			      <label for="txt_cm_id">Process :</label>
			      <select class="form-control" id="txt_cm_id" name="txt_cm_id" style="max-width: 300px;min-width: 300px;">
		            <optgroup>
		            	<option value="NA">---Select---</option>
		            	<?php
				      					
										$sqlBy ='select * from new_client_master inner join client_master  on new_client_master.client_name = client_master.client_id order by client_master.client_name '; 
										$myDB=new mysql();
										$resultBy=$myDB->query($sqlBy);
										if($resultBy){													
											foreach($resultBy as $key=>$value){
													if($process == $value['new_client_master']['cm_id'])	
													{
														echo '<option value="'.$value['new_client_master']['cm_id'].'"  selected>'.$value['client_master']['client_name'].' | '.$value['new_client_master']['process'].' | '.$value['new_client_master']['sub_process'].'</option>';
													}
													else
													{
														echo '<option value="'.$value['new_client_master']['cm_id'].'"  >'.$value['client_master']['client_name'].' | '.$value['new_client_master']['process'].' | '.$value['new_client_master']['sub_process'].'</option>';
													}												
												
											}

										}
										
				      	?>
		            </optgroup>
		            </select>
		            </div>		            
		            <div class="form-group col-sm-6" style="padding: 0px;">
											 	  
			     		<div class="form-group">
				            <input type="file" id="fileToUpload" name="fileToUpload" class="form-control" style="max-width: 300px;min-width: 300px;" />	
				         </div> 
					</div> 
					<div class="form-group col-sm-6" style="padding: 0px;">
							      <label for="txt_UploadType">Upload Type :</label>
							     		<div class="form-group">
								            <select class="form-control" id="txt_UploadType" name="txt_UploadType" style="max-width: 300px;min-width: 300px;" >
								            <option value="FTE_M">FTE [Monthly]</option>
								            <option value="CPM_M">CPM [Monthly]</option>
								            </select>	
								         </div> 
							</div>
				     <div class="form-group col-sm-6">
				    	<button type="submit" name="btn_df_Save" id="btn_df_Save" class="button button-rounded button-3d-action"><i class="fa fa-upload"></i>Upload</button>
				    </div>
		        </div> 
		        
		        <hr style="float: left;width: 100%;margin: 5px;"/>	
			     
			    <div id="pnlTable" class="pull-left col-sm-12" style="margin-top: 10px;">
			    <?php 
					if($_cm_id != 'NA')
					{
						$sqlConnect = '';
						if($_POST['txt_UploadType'] == "FTE_M")
						{
							$sqlConnect = 'select * from (SELECT client_master.client_name `Client`, process as `Process`,sub_process `Sub Process`, Rate, revenue_fte_mtd.FTE `FTE`,revenue_fte_mtd.FTE_def `FTE Defination`,date_format(revenue_fte_mtd.date_forecast , \'%M,%Y\') `MONTH`,pd1.EmployeeName `Account Head` FROM revenue_contract_bt_master  inner join revenue_fte_mtd on revenue_fte_mtd.cm_id = revenue_fte_mtd.cm_id inner join new_client_master on new_client_master.cm_id = revenue_contract_bt_master.cm_id  inner join client_master  on new_client_master.client_name = client_master.client_id left outer join personal_details pd  on pd.EmployeeID = revenue_contract_bt_master.createdby left outer join personal_details pd1  on pd1.EmployeeID = new_client_master.account_head where revenue_fte_mtd.date_forecast between revenue_contract_bt_master.StartDate and revenue_contract_bt_master.EndDate and revenue_contract_bt_master.cm_id = "'.$_cm_id.'" order by revenue_contract_bt_master.id desc limit 1) t1';
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
						if($_POST['txt_UploadType'] == "CPM_M")
						{
							$sqlConnect = 'select * from (SELECT client_master.client_name `Client`, process as `Process`,sub_process `Sub Process`, Rate, revenue_cpm_mtd.AHT `AHT`,revenue_cpm_mtd.Forecast `Forecast(NoOf Calls)`,date_format(revenue_cpm_mtd.date_forecast , \'%M,%Y\') `MONTH`,pd1.EmployeeName `Account Head` FROM revenue_contract_bt_master  inner join revenue_cpm_mtd on revenue_cpm_mtd.cm_id = revenue_cpm_mtd.cm_id inner join new_client_master on new_client_master.cm_id = revenue_contract_bt_master.cm_id  inner join client_master  on new_client_master.client_name = client_master.client_id left outer join personal_details pd  on pd.EmployeeID = revenue_contract_bt_master.createdby left outer join personal_details pd1  on pd1.EmployeeID = new_client_master.account_head where revenue_cpm_mtd.date_forecast between revenue_contract_bt_master.StartDate and revenue_contract_bt_master.EndDate and revenue_contract_bt_master.cm_id = "'.$_cm_id.'" order by revenue_contract_bt_master.id desc limit 1) t1';
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
						
					}
					
					
					?>
				</div>
			  </div>
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
		  
		    $('#btn_df_Save').on('click', function(){
		        var validate=0;
		        var alert_msg='';
		        	 
		        $('#fileToUpload').closest('div').removeClass('has-error');	
		        $('#txt_cm_id').closest('div').removeClass('has-error');	
		        
		        if($('#fileToUpload').val()=='NA'||$('#fileToUpload').val()==null ||$('#fileToUpload').val()=='')
		        {
					$('#fileToUpload').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> File should not be Empty </li>';
				}
				
				if($('#txt_cm_id').val()=='NA'||$('#txt_cm_id').val()==null ||$('#txt_cm_id').val()=='')
		        {
					$('#txt_cm_id').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> Process should not be Empty </li>';
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