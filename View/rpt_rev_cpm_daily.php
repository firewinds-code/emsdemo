<?php  
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
date_default_timezone_set('Asia/Kolkata');
$value=$counEmployee=$countProcess=$countClient=$countSubproc=0;

if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
		exit();
	}
	else
	{
				
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
	$DateTo = date('F Y',strtotime("today"));	
	
}
if(isset($_POST['ddl_clfs_Process']))
{
	$process = $_POST['ddl_clfs_Process'];
	
}


function _daysInMonth($month=null,$year=null)
{
         
    if(null==($year))
        $year =  date("Y",time()); 

    if(null==($month))
        $month = date("m",time());
         
    return date('t',strtotime($year.'-'.$month.'-01'));
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>-::: Report CPM :::-</title>
<?php include(ROOT_PATH.'AppCode/head.mpt'); ?>
<?php include(ROOT_PATH.'AppCode/DataTable.mpt'); ?>
<link rel="stylesheet" href="<?php echo STYLE.'jquery.datetimepicker.css' ;?>"/>
<script src="<?php echo SCRIPT.'jquery.datetimepicker.full.min.js' ;?>"></script>

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
				        dom: 'Bfrtip',
				        lengthMenu: [
				            [ 5,10,15, 25, 50, -1 ],
				            ['5 rows','10 rows','15 rows', '25 rows', '50 rows', 'Show all' ]
				        ],
				         "pageLength": 5,
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
						        
						    ],
						    "bProcessing" : true,
							"bDestroy" : true,
							"bAutoWidth" : true,
							"sScrollY" : "350",
							"sScrollX" : "100%",
							"bScrollCollapse" : true,
							"bLengthChange" : false
							
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
<style>
	
div#search_field,div#comment_box {
	    float: left;
	    margin-top: 10px;
	    border: 1px solid #ececec;
	    width: 100%;
	}
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
		height: 37px;
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
	 	margin-bottom: 10px;
	}
	table.data tbody th, table.data tbody td
	{
		padding: 4px;
	}
	
	table.data td{border:none; padding: 5px; vertical-align:top;}

	
	table.data  th {padding: 8px 5px 8px 5px;border: 1px solid #bdbdbd; background-color:#71B335;text-shadow: 1px 0px #003362;color: #fff; text-align: center;    border: 1px solid #2d2d2d;}
	table.data td {padding: 4px; border: 1px solid #dcdcdc;color: #5b5b5b;vertical-align: top;text-align: center;}
	table.data tr {background: #fff}
	table.data tr:hover {background: #E0E0E0;}
	table.data tr small a{color: #fff;}
	table.data tr small{color: #fff;}
	table.data tr:hover small a{color: #527e19;}
	table.data tr:hover small a:hover{color: #000;}
	table.data tr:hover small {color: #5b5b5b;}	
	table.data thead th, table.data thead td
	{
		border-bottom: 3px solid #666767;
		
	}
	
	
	.imgBtn
	{
		padding-left: 5px;
		cursor: pointer;
	}
	.clsInput {
	    border: 1px solid #A0A0A0;
	    border-radius: 2px;
	    min-width: 200px;
	    max-width: 200px;
	    width: 200px;
	   }
	.daterangepicker .calendar {
	    display: none;
	    margin: 4px;
	    max-width: 300px;
	}
	table.data thead th, table.data tbody td {
	  
	    white-space: nowrap;
	    vertical-align: middle;
	    min-width: 120px;
	}
	select[readonly]
	{
		    pointer-events: none;
	}
	.ui-datepicker-calendar {
    display: none;
    }
    table.data
    {
		    border-collapse: collapse;
		    border: none;
	}
   
</style>
</head>
<body>
<form name="indexForm"  id="indexForm" method="post" action="<?php echo($_SERVER['REQUEST_URI']); ?>">
<div class="container MainDiv" id="wrap">
  
 <?php include(ROOT_PATH.'AppCode/header.mpt'); ?>

 <div id="main" class="clearfix" style="overflow-y: auto;overflow-x: hidden;height: 78% !important;">
 <div id="leftmenu"  class="container drawer drawer--left">	
	<?php include(ROOT_PATH.'AppCode/left_menu.mpt'); ?>
 </div>
<div class="container " style="margin: 0px;width: 100%;padding-left: 30px;">
	<div>
		<h4 style="border-bottom: 2px solid #8DC73E;padding: 5px;border-right:2px solid green;text-shadow: 1px 1px 1px #FFFFFF, 1px 2px 0px rgba(2, 2, 2, 0.36);border-radius: 4px;box-shadow: 0px 0px 1px 1px #49ab0c;"><b> CPM </b> Report </h4>
	</div>
		<div class="form-inline col-sm-12" id="rpt_container"  style="padding: 0px;">
		<div class="form-group col-sm-6" style="padding: 0px;">
			
			
			<div class="form-group col-sm-12"  style="padding: 0px;">
			      
			     		<div class="form-group"  style="padding: 0px;">
			     			<input  class="form-control" name="txt_dateFor" style="min-width: 250px;"  id="txt_dateFor" value="<?php echo $DateTo;?>"/>
				            <select class="form-control" id="ddl_clfs_Process" name="ddl_clfs_Process" style="max-width: 300px;min-width: 300px;">
				            <option value="NA">----Select----</option>	
						      	<?php
						      					
												$sqlBy = 'select distinct Process,clientname,sub_process,cm_id from whole_details_peremp order by clientname';
												$myDB=new mysql();
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
			
		</div>
		
		<div class="form-group col-sm-4"  style="padding: 0px;">			
		
			<button type="submit" class="button button-3d-primary button-rounded" name="btn_view" id="btn_view"><i class="fa fa-search"></i> Search</button>
			<button type="button" class="button button-3d-action button-rounded hidden" name="btnExport" id="btnExport"><i class="fa fa-download"></i> Export</button>
		</div>
		
	</div>
		<?php
			function getDatesFromRange($start, $end, $format = 'd') 
		    {
			    $array = array();
			    $interval = new DateInterval('P1D');

			    $realEnd = new DateTime($end);
			    $realEnd->add($interval);

			    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

			    foreach($period as $date) { 
			        $array[] = intval($date->format($format)); 
			    }
				sort($array);
			    return $array;
			}
			if(isset($_POST['btn_view']))
			{
				
					$date_first = date("Y-m-01",strtotime($DateTo));
					$date_last = date("Y-m-t",strtotime($DateTo));
					
					
					$table='<div class="panel panel-default col-sm-12" style="margin-top:10px;" id="tbl_div"><div class="panel-body"><table id="myTable" class="data"><thead><tr>';
					
					$table .='<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Date </th>';
					$table .='<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">AHT</th>';
					$table .='<th style="background-color:#cc99ff;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Approved Rate</th>';
					$table .='<th style="color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Forecast(NoOf Calls)</th>';
					$table .='<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">MG</th>';
					$table .='<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">PlannedRevenew</th>';
					$table .='<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Actual Calls/Day</th>';
					$table .='<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Actual AHT/Day</th>';
					$table .='<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">ActualRevenew</th>';
					$table .='<th style="background-color:skyblue;color:black;text-shadow: 1px 0px rgba(121, 132, 142, 0.46);">Variance</th>';

				    $table .='</thead><tbody>';
					if(strtotime($DateTo) && $_POST['ddl_clfs_Process'] != 'NA')
					{
						
						
						
						$start_date  = new DateTime($date_first);
						$end_date  = new DateTime($date_last);
						$cmid = $_POST['ddl_clfs_Process'];
	            		$query = "SELECT  Rate,MG,revenue_cpm_mtd.AHT,revenue_cpm_mtd.Forecast FROM revenue_contract_bt_master inner join revenue_cpm_mtd on revenue_cpm_mtd.cm_id = revenue_contract_bt_master.cm_id where (revenue_cpm_mtd.date_forecast between revenue_contract_bt_master.StartDate and revenue_contract_bt_master.EndDate ) and revenue_contract_bt_master.cm_id = '".$cmid."' and date_format(revenue_cpm_mtd.date_forecast , '%M,%Y') =  date_format('".$date_first."' , '%M,%Y') order by revenue_contract_bt_master.id desc limit 1";
	            		
	            		$myDB = new mysql();
	            		$rst_rev = $myDB->query($query);
	            		
	            		$DateMTD = $DateTo;
	            		$AHT_tsum = 0; 
	            		$ApprovedRate_tsum  = 0;
	            		$Forecast_tsum = 0;
	            		$Forecast_tsum_act = 0;
	            		$MG_tsum = 0;
	            		$PlannedRevenew_tsum = 0;	
	            		$ActualCalls_tsum = 0;
	            		$ActualAHT_tsum = 0;
	            		$ActualRevenew_tsum = 0;


				        $tbl_total_Set = '';
				        $tbl_row_set = '';
						for($i_date = $start_date; $start_date <= $end_date; $i_date->modify('+1 day'))
						{
								
								$date__ = $i_date->format('Y-m-d');
								
								$row_data = '';
								
				            	if(!empty($date__) && $date__ != '1970-01-01')
				            	{
				            		$tbl_row_set .='<tr>';
				            		$tbl_row_set .='<td>'.$date__.'</td>';
				            		
				            		if(count($rst_rev) >0 && $rst_rev )
				            		{
										foreach($rst_rev as $key=>$val)
										{
											
											$myDB = new mysql();
											$data_per_day_upl = $myDB->query("SELECT aht,forecast FROM revenue_cpm_mtd where date_forecast = '".date('Y-m-01',strtotime($date__))."' and cm_id = '".$cmid."' order by id desc limit 1;");
											if(count($data_per_day_upl) > 0 && $data_per_day_upl){
												$data_per_day_upl = $data_per_day_upl[0]['revenue_cpm_mtd'];
												
											    $row_data .='<td>'.$data_per_day_upl['aht'].'</td>';
												$row_data .='<td>'.$val['revenue_contract_bt_master']['Rate'].'</td>';
												$noofdays_iftem = date('t',strtotime($date__));
												$row_data .='<td>'.floor(($data_per_day_upl['forecast'] / $noofdays_iftem )) .'</td>';
												$row_data .='<td>'.$val['revenue_contract_bt_master']['MG'].'</td>';
												
												$I3 = ($val['revenue_contract_bt_master']['MG']/100);
												$F3 = $data_per_day_upl['aht'];
												$G3 = $val['revenue_contract_bt_master']['Rate'];
												$H3 = floor(($data_per_day_upl['forecast'] / $noofdays_iftem ));
												$PlannedRevenew = ($F3/60)*$G3*$H3*($I3);
												
												// Total SUM 
												
												$AHT_tsum = $F3; 
							            		$ApprovedRate_tsum  = $G3;
							            		$Forecast_tsum = $H3;
							            		$Forecast_tsum_act = $data_per_day_upl['forecast'];
							            		$MG_tsum = $val['revenue_contract_bt_master']['MG'];
							            		$PlannedRevenew_tsum += round($PlannedRevenew,2);	
							            		
												$row_data .='<td>'.round($PlannedRevenew,2).'</td>';
												
												$myDB = new mysql();
												$data_per_day_actl = $myDB->query("SELECT aht,forecast FROM revenue_forecast_cpm_daily where date_forecast = '".$date__."' and cm_id = '".$cmid."' order by id desc limit 1;");
												if(count($data_per_day_actl) > 0 && $data_per_day_actl)
												{
													$data_per_day_actl = $data_per_day_actl[0]['revenue_forecast_cpm_daily'];
													
													
													$row_data .='<td>'.$data_per_day_actl['forecast'].'</td>';
													$row_data .='<td>'.$data_per_day_actl['aht'].'</td>';
													$F3_ = $data_per_day_actl['aht'];
													$K3 = $data_per_day_actl['forecast'];
													$ActualRevenew = 0;
													if($K3 < ($I3*$H3))
													{
														$ActualRevenew = ($F3_/60)*$G3*($H3*$I3);
													}
													else
													{
														$ActualRevenew = ($F3_/60)*$G3*($K3);
													}
													
													//Total Actual
													
														
								            		$ActualAHT_tsum = $F3_;
								            		$ActualCalls_tsum += $K3;
													$ActualRevenew_tsum += round($ActualRevenew,2);
													
													$row_data .='<td>'.round($ActualRevenew,2).'</td>';
													if($ActualRevenew != 0 && !empty($ActualRevenew))
													{
														$row_data .='<td>'.round((($ActualRevenew / $PlannedRevenew) * 100 ),2).'%</td>';
													}
													else
													{
														$row_data .='<td>-</td>';
													}
													
												}
												else
												{
													$row_data .='<td>-</td>';
													$row_data .='<td>-</td>';
													$row_data .='<td>-</td>';
													$row_data .='<td>-</td>';
												}
												
												
												
											}
											else
											{
													$row_data .='<td>-</td>';
													$row_data .='<td>-</td>';
													$row_data .='<td>-</td>';
													$row_data .='<td>-</td>';
													$row_data .='<td>-</td>';
													$row_data .='<td>-</td>';
													$row_data .='<td>-</td>';
													$row_data .='<td>-</td>';
													$row_data .='<td>-</td>';
											}
											
											
									        
										}
					            		
									}
				            		
				            		if($row_data != '')
				            		{
										$tbl_row_set .= $row_data;
										
									}
									else
									{
										$tbl_row_set .= "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
									}
									$tbl_row_set .='</tr>';

 
				            		
								}
								
								
							}
						
						if(!empty($tbl_row_set) && $AHT_tsum != 0)
						{
							
							$PlanedRevenue_mtd = ($AHT_tsum/60)*$ApprovedRate_tsum*$Forecast_tsum_act*($MG_tsum / 100);
							$Variance1 = 0;
							if($PlanedRevenue_mtd != 0 && !empty($PlanedRevenue_mtd))
		            		{
								$Variance1  = round((($ActualRevenew_tsum / $PlanedRevenue_mtd) * 100 ),2);
							}
							
							$tbl_total_Set .= '<tr style="background: #ffc31a;font-weight: bold;font-size: 13px;"><td>MTD : '.$DateMTD.'</td><td>'.$AHT_tsum.'</td><td>'.$ApprovedRate_tsum.'</td><td>'.$Forecast_tsum_act.'</td><td>'.$MG_tsum.'</td><td>'.round($PlanedRevenue_mtd,2).'</td><td>'.$ActualCalls_tsum.'</td><td>'.$ActualAHT_tsum.'</td><td>'.$ActualRevenew_tsum.'</td><td>'.$Variance1.'</td></tr>';
							
							
							
		            		if($PlannedRevenew_tsum != 0 && !empty($PlannedRevenew_tsum))
		            		{
								$Variance  = round((($ActualRevenew_tsum / $PlannedRevenew_tsum) * 100 ),2);
							}
		            		 
		            		
							$tbl_total_Set .= '<tr style="background: #ffc31a;font-weight: bold;font-size: 13px;"><td>TOTAL : '.$DateMTD.'</td><td>'.$AHT_tsum.'</td><td>'.$ApprovedRate_tsum.'</td><td>'.$Forecast_tsum.'</td><td>'.$MG_tsum.'</td><td>'.$PlannedRevenew_tsum.'</td><td>'.$ActualCalls_tsum.'</td><td>'.$ActualAHT_tsum.'</td><td>'.$ActualRevenew_tsum.'</td><td>'.$Variance.'</td></tr>';
							
					        
						}
						$table  .= $tbl_total_Set.$tbl_row_set;
					}		
					$table .='</tbody></table></div></div>';
					echo $table;
			}
			
		 ?>
			<div  class="alert" id="alert_message">
						<div class="container" id="alert_msg"><?php echo $alert_msg;?></div>
							    	<a href="javascript:void(0);" id="alert_msg_close" style="position: absolute;background-image: url('../Style/images/Oxygen480-actions-dialog-close.png');float: right;margin: 0px;padding: 0px;right: 0px;color: royalblue;height: 40px;width: 40px;background-position: 20px;background-size: 20px 20px;background-repeat: no-repeat;top: 0px;"></a></div>
		<style>
			#overlay {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 10;
}

/* just some content with arbitrary styles for explanation purposes */
#modal_div {
    width: 350px;
    height: 50px;
    line-height: 50px;
    position: fixed;
    top: 50%;
    left: 50%;
    margin-top: -50px;
    margin-left: -150px;
    background: radial-gradient(#FFC107,#FFEB3B,#CDDC39);
    border-radius: 5px;
    text-align: right;
    z-index: 11;
    font-weight: bold;
    color: #ffe9c8;
    text-shadow: 0px 0px 1px white, 1px 1px 1px black;
    font-size: 18px;
    padding-right: 5px;
    border: 2px solid #FF9800;
}
#loader_content {
    border: 6px solid #f3f3f3;
    border-radius: 50%;
    border-top: 6px solid #FF9800;
    border-bottom: 6px solid #FF9800;
    width: 40px;
    height: 40px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
    margin: 5px;
    padding: 0px;
    float: left;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
		</style>					    	
		<div id="overlay" class="hidden">
			
			<div id="modal_div"><div id="loader_content"></div> Loading Month data,please wait.</div>
		</div>
  		
	

  <style>
	.modelbackground
	{
		position: fixed;
		height: 100%;
		width: 100%;
		top:0px;
		left: 0px;
		background: rgba(0, 0, 0, 0.3);
		z-index: 1000;
	}
	.PopUp
	{
		position: absolute;
	    float: left;
	    width: 60%;
	    overflow: auto;
	    top: 25%;
	    background: rgba(255, 255, 255, 0.7);
	    left: 20%;
	    box-shadow: 0px 0px 6px 0px gray inset,0px 0px 10px 0px rgba(255, 255, 255, 0.95);
	    border: 1px solid #67A1AD;
	    border-radius: 10px;
	    padding: 10px;
	    text-shadow: 1px 1px 0px #FFF8F8, 1px 2px 0px rgba(0, 0, 0, 0.28);
	}
	.imgBtn_close
	{
		position: absolute;
	    top: 0;
	    right: 0;
	}
	#empinfo_tab td:nth-child(odd)	
	{
		border: 1px solid #A3CCA3;
    	color: black;
    	text-shadow: none;
    	padding-left: 30px;
	}
	#empinfo_tab td:nth-child(even)	
	{
		border: 1px solid #A3CCA3;
    	color: #033313;
    	font-weight: bold;
    	text-transform: uppercase;
    	padding-left: 10px;
	}
</style>
   <div class="hidden modelbackground" id="myDiv">
   	
   </div>  
</div>
</div>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
</div>

<script>
	$(function(){
		
		
		$('#alert_msg_close').click(function(){
			$('#alert_message').hide();
		});
		if($('#alert_msg').text()=='')
		{
			$('#alert_message').hide();
		}
		else
		{
			$('#alert_message').delay(10000).fadeOut("slow");
		}
		 
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
</form>
</body>
</html>