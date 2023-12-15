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
$process ='NA';
if(isset($_POST['txt_cm_id']) && !empty($_POST['txt_cm_id']) && $_POST['txt_cm_id'] !='NA')
{
	$process = $_POST['txt_cm_id'];	
}

if(isset($_POST['btn_df_Save']))
{
	
	$btnUploadCheck=1;
	$noData_Uploadfor_down='';
	$myDB = new mysql();
		$check_dt_rev = $myDB->query("SELECT id FROM revenue_forecast_cpm_daily where cm_id = '".$_POST['txt_cm_id']."' and date_forecast ='".$_POST['txt_date_forecast']."';");
		
		if(count($check_dt_rev) > 0 && $check_dt_rev)
		{
			$id_forecast_ah  = $check_dt_rev[0]['revenue_forecast_cpm_daily']['id'];
			if(is_numeric($id_forecast_ah) && !empty($id_forecast_ah))
			{ 
				$update_data = array(					
					"forecast"=> $_POST['txt_num_of_call'],
					"aht"=>$_POST['txt_aht'],
					"modifiedby"=>$_SESSION['__user_logid'],
					"modifiedon"=>date('Y-m-d H:i:s')
				);
				
				$myDB = new mysql();
				$flag = $myDB->update("revenue_forecast_cpm_daily",$update_data," cm_id = '".$_POST['txt_cm_id']."' and date_forecast ='".$_POST['txt_date_forecast']."'");
				$mysql_Error = mysql_error();
				if($flag)
				{
					$alert_msg=$alert_msg."<span class='text-success'><b>Message :</b>  Forecast has been saved successfully for '".$_POST['txt_date_forecast']."'.</span><br />";
				}
				else
				{
					$alert_msg=$alert_msg."<span class='text-danger'><b>Message :</b>  Forecast not saved for '".$_POST['txt_date_forecast']."'. Error :".$mysql_Error."</span><br />";
				}
			}
			else
			{
				$alert_msg=$alert_msg."<span class='text-danger'><b>Message :</b>  Forecast not saved for '".$_POST['txt_date_forecast']."'. Error: <code>No data id found for Edit</code>.</span><br />";
			}
		}
		else
		{
			
        		$insert_data = array(					
        		    "cm_id" => $_POST['txt_cm_id'],
					"forecast"=> $_POST['txt_num_of_call'],
					"date_forecast" => $_POST['txt_date_forecast'],
					"aht"=>$_POST['txt_aht'],
					"modifiedby"=>$_SESSION['__user_logid']
				);
				
				$myDB = new mysql();
				$flag = $myDB->insert("revenue_forecast_cpm_daily",$insert_data);
				$mysql_Error = mysql_error();
				if($flag)
				{
					$alert_msg=$alert_msg."<span class='text-success'><b>Message :</b>  Forecast has been saved successfully for '".$_POST['txt_date_forecast']."'.</span><br />";
				}
				else
				{
					$alert_msg=$alert_msg."<span class='text-danger'><b>Message :</b>  Forecast not saved for '".$_POST['txt_date_forecast']."'. Error :".$mysql_Error."</span><br />";
				}
    	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>-:: Forecast-AH No of Calls Module ::-</title>
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
	.dataTables_scrollHead {
	    height: 80px;
	}
</style>
<script>
	$(document).ready(function(){
		$('#txt_date_forecast').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
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

	<div class="container">
		<div class="container" style="overflow-y: auto;overflow-x: hidden;">
			<div>
				<h4 style="border-bottom: 2px solid #8DC73E;padding: 5px;border-right:2px solid green;text-shadow: 1px 1px 1px #FFFFFF, 1px 2px 0px rgba(2, 2, 2, 0.36);border-radius: 4px;box-shadow: 0px 0px 1px 1px #49ab0c;">Forecast-AH No of Call Module </h4>
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
			      <label for="txt_date_forecast">Date For :</label>
			      <input type="text" class="form-control" id="txt_date_forecast" name="txt_date_forecast" readonly="true" style="max-width: 300px;min-width: 300px;" />
		           
		        </div>
		        <div class="form-group col-sm-6" style="padding: 0px;">
			      <label for="txt_num_of_call">No. of Calls :</label>
			      <input type="number" class="form-control" min="0" id="txt_num_of_call" name="txt_num_of_call" style="max-width: 300px;min-width: 300px;" step="any"/>
		            
		           
		        </div>
		        <div class="form-group col-sm-6" style="padding: 0px;">
			      <label for="txt_aht">AHT :</label>
			      <input type="number" class="form-control" min="0" id="txt_aht" name="txt_aht" style="max-width: 300px;min-width: 300px;" step="any"/>
		            
		           
		        </div>
		        
		        <div class="form-group col-sm-6" style="padding: 0px;">
			     
			      <input type="submit" class="button button-rounded button-3d-primary" id="btn_df_Save" name="btn_df_Save" />
		            
		           
		        </div>
		         
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
		        	 
		        $('#txt_num_of_call').closest('div').removeClass('has-error');	
		        $('#txt_date_forecast').closest('div').removeClass('has-error');
		        $('#txt_cm_id').closest('div').removeClass('has-error');	
		         $('#txt_aht').closest('div').removeClass('has-error');	
		        
		        if($('#txt_num_of_call').val()=='NA'||$('#txt_num_of_call').val()==null ||$('#txt_num_of_call').val()=='')
		        {
					$('#txt_num_of_call').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> No. Of Calls should not be Empty </li>';
				}
				if($('#txt_aht').val()=='NA'||$('#txt_aht').val()==null ||$('#txt_aht').val()=='')
		        {
					$('#txt_aht').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> AHT should not be Empty </li>';
				}
				 if($('#txt_date_forecast').val()=='NA'||$('#txt_date_forecast').val()==null ||$('#txt_date_forecast').val()=='')
		        {
					$('#txt_date_forecast').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> Date For should not be Empty </li>';
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