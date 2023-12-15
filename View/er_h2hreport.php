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
		
		if($isPostBack && isset($_POST['txt_dateTo']))
		{
		
			$date_To = $_POST['txt_dateTo'];
			$date_From =$_POST['txt_dateFrom'];
		}
		else
		{
			$date_To = date('Y-m-d',time()); 
			$date_From= date('Y-m-d',time()); 
		}
		
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}

/*if(file_exists('../Vacination/NA'))
{
	echo 'Yes';
}
else
{
	echo 'No';
}*/
?>

<script>
	$(function(){
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});
		$('#myTable').DataTable({
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
<span id="PageTittle_span" class="hidden">ER Grievance Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>ER Grievance Report</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	



		
	
	<div class="input-field col s12 m12" id="rpt_container">
		<div class="input-field col s3 m3">
			
			<input type="text" name="txt_dateFrom"  id="txt_dateFrom" readonly="true" value="<?php echo $date_From;?>"/>
		</div>
		<div class="input-field col s3 m3">
			
			<input type="text" name="txt_dateTo"  id="txt_dateTo" readonly="true" value="<?php echo $date_To;?>"/>
		</div>
		
		<div class="input-field col s2 m2">			
		
			<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
			<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
		</div>
		
	</div>
		<?php
			if(isset($_POST['btn_view']))
			{
				$myDB = new MysqliDb();
				$sqlstr='';
				
				if($_SESSION['__user_type'] =='HR' && (($_SESSION['__status_ah']!='No' && $_SESSION['__status_ah']==$_SESSION['__user_logid']) && $_SESSION['__status_ah']!=''))
				 {
				 	$sqlstr = "SELECT issue_tracker.*,whole_dump_emp_data.emp_status,whole_dump_emp_data.EmployeeName ,whole_dump_emp_data.Process,whole_dump_emp_data.sub_process,pd.EmployeeName as Suppervisor,designation,dept_name,DOJ,clientname,l1.location FROM ems.issue_tracker 
inner join whole_dump_emp_data on whole_dump_emp_data.EmployeeID  = issue_tracker.requestby inner join personal_details pd on whole_dump_emp_data.ReportTo  = pd.EmployeeID inner join location_master l1 on l1.id  = pd.location where (cast(issue_tracker.request_date as date) between cast('".$date_From."' as date) and cast('".$date_To."' as date)) and pd.location='".$_SESSION["__location"]."' and issue_tracker.queary!='Supervisor Behavior Issue'";	
				 }
				 else if(($_SESSION['__status_er']!='No' && $_SESSION['__status_er']==$_SESSION['__user_logid']) && $_SESSION['__status_er']!='')
				 {
				 	$sqlstr = "SELECT issue_tracker.*,whole_dump_emp_data.emp_status,whole_dump_emp_data.EmployeeName ,whole_dump_emp_data.Process,whole_dump_emp_data.sub_process,pd.EmployeeName as Suppervisor,designation,dept_name,DOJ,clientname,l1.location FROM ems.issue_tracker inner join whole_dump_emp_data on whole_dump_emp_data.EmployeeID  = issue_tracker.requestby inner join personal_details pd on whole_dump_emp_data.ReportTo  = pd.EmployeeID inner join location_master l1 on l1.id  = pd.location join new_client_master nw on nw.cm_id = whole_dump_emp_data.cm_id where (cast(issue_tracker.request_date as date) between cast('".$date_From."' as date) and cast('".$date_To."' as date)) and pd.location='".$_SESSION["__location"]."' and issue_tracker.queary!='Supervisor Behavior Issue' and nw.er_scop= '".$_SESSION['__user_logid']."' ";	
				 }
				 
				//echo $sqlstr;			
				$chk_task=$myDB->query($sqlstr);
				$my_error= $myDB->getLastError();	
			
				if(count($chk_task) > 0 && $chk_task)
				{  
					$table='<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
					$table .='<th>EmployeeID</th>';
					$table .='<th>Location</th>';
					$table .='<th>EmployeeName</th>';
					$table .='<th>Mobile No.</th>';
					$table .='<th>Issue</th>';
					$table .='<th>Belongs To</th>';
					$table .='<th>Status</th>';
					$table .='<th>Request On</th>';
					$table .='<th>Last Updated by Requester</th>';	
					$table .='<th>Last Updated by Handler</th>';		
					$table .='<th>Concern off</th>';
					$table .='<th>Employee Status</th>';
					$table .='<th>Designation</th>';
					$table .='<th>Dept Name</th>';
					$table .='<th>DOJ</th>';
					$table .='<th>Client</th>';
					$table .='<th>Process</th>';
					$table .='<th>Sub Process</th>';				
					$table .='<th>Supervisor</th>';
					$table .='<th>Type</th>';
					$table .='<th>Contact Status</th>';
					$table .='<th>Disposition</th>';
					$table .='<th>Handler</th>';
					$table .='<th>Refer To</th>';
					$table .='<th>Requester Comments</th>';
				    $table .='<th>Handler Comments</th>';
				    $table .='<th>Rating</th>';
				    $table .='<th>Feedback</th>';
				     $table .='<thead><tbody>';
    
					foreach($chk_task as $key=>$value)
					{
																		
						$requester_remark=$value['requester_remark'];
						if(strstr($requester_remark,'>') ){
							$requester_remark=str_replace('>','greater than',$requester_remark);
						}
						if(strstr($requester_remark,'<')){
							$requester_remark=str_replace('<','less than',$requester_remark);
						}
						$handler_remark=$value['handler_remark'];
						if(strstr($requester_remark,'>') ){
							$handler_remark=str_replace('>','greater than',$handler_remark);
						}
						if(strstr($handler_remark,'<')){
							$handler_remark=str_replace('<','less than',$handler_remark);
						}
						$table .='<tr><td>'.$value['requestby'].'</td>';
						$table .='<td>'.$value['location'].'</td>';
						$table .='<td>'.$value['EmployeeName'].'</td>';
						$table .='<td>'.$value['mobileNo'].'</td>';
						$table .='<td>'.$value['queary'].'</td>';
						$table .='<td>'.$value['bt'].'</td>';
						$table .='<td>'.$value['status'].'</td>';
						$table .='<td>'.$value['request_date'].'</td>';
						$table .='<td>'.$value['updateby_requester'].'</td>';
						$table .='<td>'.$value['updateby_handler'].'</td>';
						$table .='<td>'.$value['concern_off'].'</td>';
						$table .='<td>'.$value['emp_status'].'</td>';
						$table .='<td>'.$value['designation'].'</td>';
						$table .='<td>'.$value['dept_name'].'</td>';
						$table .='<td>'.$value['DOJ'].'</td>';
						$table .='<td>'.$value['clientname'].'</td>';
						$table .='<td>'.$value['Process'].'</td>';
						$table .='<td>'.$value['sub_process'].'</td>';				
						$table .='<td>'.$value['Suppervisor'].'</td>';
						$table .='<td>'.$value['type'].'</td>';
						$table .='<td>'.$value['contatc_staus'].'</td>';
						$table .='<td>'.$value['disposition'].'</td>';
						$table .='<td>'.$value['issue_handler'].'</td>';
						$table .='<td>'.$value['lo1'].'</td>';
						$table .='<td style="max-width: 50px;overflow: hidden;">'.$requester_remark.'</td>';
						$table .='<td  style="max-width: 50px;overflow: hidden;">'.$handler_remark.'</td>';
						$table .='<td>'.$value['rating'].'</td>';
						$table .='<td  style="max-width: 50px;overflow: hidden;">'.$value['feedback'].'</td></tr>';	
					}
					$table .='</tbody></table></div>';
					echo $table;
				}
				else
				{
					echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
					
				}
			}
			
			
			
		 ?>
      
      </div>   
<!--Reprot / Data Table End -->	       
	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>		

<script>
	$("#btn_view").click(function(){
		//alert($("#txt_location").val());
			
	});
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>