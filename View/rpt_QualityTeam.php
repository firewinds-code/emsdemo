<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

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
		$isPostBack = false;

		$referer = "";
		$alert_msg="";
		$thisPage = REQUEST_SCHEME."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])){
		    $referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage){
		    $isPostBack = true;
		} 
		
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}
?>
<script>
	$(function(){
		/*$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});*/
		
		    // DataTable
		    var table = $('#myTable').DataTable({
				        dom: 'Bfrtip',
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
						        },'pageLength'
						        
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
			});;
		  	$('#txt_Subproc, #txt_process').keyup( function() {
	        	table.draw();
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
.dataTables_scrollHead
	{
		height: 80px;
	}
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Team Quality Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Team Quality Report</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	
       <div id="pnlTable">
		
				<?php 
					$myDB = new MysqliDb();
					$dt_Test = $myDB->query("select distinct EmployeeID from status_table where (status_table.ReportTo = '".$_SESSION['__user_logid']."' or status_table.Qa_ops = '".$_SESSION['__user_logid']."')");
					if(count($dt_Test) > 0 && $dt_Test)
					{
						$table ='<table id="myTable" class="data dataTable no-footer row-border centered" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<tr>';
						$table .= '<th rowspan = "2" style="text-align:center">EmployeeID</th>';
						$table .= '<th rowspan = "2" style="text-align:center">Employee Name</th>';
						$table .= '<th rowspan = "2" style="text-align:center">Team Lead (Report To)</th>';							
						$table .= '<th colspan="3">Last Week</th>';
						$table .= '<th colspan="3">Current Week</th>';
						$table .= '<th colspan="3">MTD</th>';
						$table .= '</tr>';
						$table .= '<th>Audit Count</th>';
						$table .= '<th>Fatal Count</th>';
						$table .= '<th>Quality Score</th>';
						$table .= '<th>Audit Count</th>';
						$table .= '<th>Fatal Count</th>';
						$table .= '<th>Quality Score</th>';
						$table .= '<th>Audit Count</th>';
						$table .= '<th>Fatal Count</th>';
						$table .= '<th>Quality Score</th>';
						$table .= '</tr>';
						
						
						
						$table .='</thead><tbody>';
						
						foreach($dt_Test as $key=>$value)
						{
							$date_on = date('Y-m-d',time());
							$monday ='';
							if( strtolower(date('l',strtotime($date_on))) == 'monday')
							{
								$monday =  date('Y-m-d',strtotime($date_on));
							}
							else
							{
								$monday =  date('Y-m-d',strtotime($date_on.' last monday'));	
							}
							$datecurrent = date('Y-m-d',strtotime($monday.' +7 days'));
							
							$datelast_mon = date('Y-m-d',strtotime($monday.' -7 days'));
							$datelast = date('Y-m-d',strtotime($monday.' -1 days'));
							
							$table .= '<tr>';
							$table .= '<td>'.$value['EmployeeID'].'</td>';
							$db_in = new MysqliDb();
							$data_emp = $db_in->query("SELECT pd1.EmployeeName,status_table.ReportTo,pd2.EmployeeName as ReportToName FROM status_table inner join personal_details pd1 on pd1.EmployeeID  = status_table.EmployeeID inner join personal_details pd2 on pd2.EmployeeID  = status_table.ReportTo where status_table.EmployeeID = '".$value['EmployeeID']."'");
							if(!empty($data_emp[0]['EmployeeName'])){
								$table .= '<td>'.$data_emp[0]['EmployeeName'].'</td>';
							}
							else
							{
								$table .= '<td></td>';
							}
							
							if(!empty($data_emp[0]['ReportToName'])){
								$table .= '<td>'.$data_emp[0]['ReportToName'].'</td>';
							}
							else
							{
								$table .= '<td></td>';
							}
							
							$myDB = new MysqliDb();
							$query_i = $myDB->query('select team_quality.* from team_quality where team_quality.EmployeeID = "'.$value['EmployeeID'].'"  and team_quality.date between "'.$datelast_mon.'" and "'.$datelast.'"');
							$prev_audit = 0;
							$prev_fatal = 0;
							$prev_score = 0;
							$prev_avg_score = 0;
							if(count($query_i) > 0 && $query_i)
							{
								foreach($query_i as $qsn=>$data_i)
								{
									$prev_audit = $prev_audit + $data_i['audit'];
									$prev_fatal = $prev_fatal + $data_i['fatal'];
									$prev_score = $prev_score + $data_i['quality'];
								}
								$prev_avg_score = round($prev_score / count($query_i),2);
								
							}
							$table .= '<td>'.$prev_audit.'</td>';
							$table .= '<td>'.$prev_fatal.'</td>';
							$table .= '<td>'.$prev_avg_score.'</td>';
							
							
							$myDB = new MysqliDb();
							$query_i = $myDB->query('select team_quality.* from team_quality where team_quality.EmployeeID = "'.$value['EmployeeID'].'"  and team_quality.date between "'.$monday.'" and "'.$datecurrent.'"');
							$_audit = 0;
							$_fatal = 0;
							$_score = 0;
							$_avg_score = 0;
							if(count($query_i) > 0 && $query_i)
							{
								foreach($query_i as $qsn=>$data_i)
								{
									$_audit = $_audit + $data_i['audit'];
									$_fatal = $_fatal + $data_i['fatal'];
									$_score = $_score + $data_i['quality'];
								}
								$_avg_score = round($_score / count($query_i),2);
								
							}
							$table .= '<td>'.$_audit.'</td>';
							$table .= '<td>'.$_fatal.'</td>';
							$table .= '<td>'.$_avg_score.'</td>';
							
							
							
							$db_in = new MysqliDb();
							$query_i = $myDB->query('select team_quality.* from team_quality where team_quality.EmployeeID = "'.$value['EmployeeID'].'"  and month(team_quality.date) =  month("'.$date_on.'")');
							$mtd_audit = 0;
							$mtd_fatal = 0;
							$mtd_score = 0;
							$mtd_avg_score = 0;
							if(count($query_i) > 0 && $query_i)
							{
								foreach($query_i as $qsn=>$data_i)
								{
									$mtd_audit = $mtd_audit + $data_i['audit'];
									$mtd_fatal = $mtd_fatal + $data_i['fatal'];
									$mtd_score = $mtd_score + $data_i['quality'];
								}
								
								$mtd_avg_score = round($mtd_score / count($query_i),2);
							}
							$table .= '<td>'.$mtd_audit.'</td>';
							$table .= '<td>'.$mtd_fatal.'</td>';
							$table .= '<td>'.$mtd_avg_score.'</td>';
							
							
							$table .= '</tr>';
						}
						$table .= '</tbody></table>';
						
						echo $table;
						
						
					}
					else
					{
						echo "<script>$(function(){ toastr.info('No Data Found.'); }); </script>";
					}
				
				 	
				 	
				?>	
			
			
          </div>
</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>		
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>