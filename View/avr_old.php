<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
include(__dir__.'/../Controller/endecript.php');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
// Global variable used in Page Cycle
$value=$counEmployee=$countProcess=$countClient=$countSubproc=0;
error_reporting(0);
$empID='';
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
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}
$show = ' hidden';
$link =$btn_view=$btn_view1=$alert_msg='';
?>

<script>
$(function(){
	
	$("#myTable .text-danger").css('color','red');
	$("#myTable .text-success").css('color','green');
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
					"fnDrawCallback":function()
					{
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
<span id="PageTittle_span" class="hidden">Report Aadhar Verification</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Report Aadhar Verification</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >

		
		
	
		
		<div id="pnlTable">
		<?php
				
					$getData="select t1.EmployeeID,t1.EmployeeName,t1.DOB,t1.FatherName,t1.clientname,t1.Process,t1.sub_process,t1.DOJ,t1.designation,t1.emp_status,t3.dol,t2.aadhar_status ,t4.EmployeeName as 'Created_by_Name',t2.created_by ,t2.created_at,t2.remarks from  whole_dump_emp_data t1 join aadhar_verifiaction t2 on t1.EmployeeID=t2.EmployeeID left join exit_emp t3 on t2.EmployeeID = t3.EmployeeID join whole_dump_emp_data t4 on t2.created_by = t4.EmployeeID";
					$myDB=new MysqliDb();
					$allData=$myDB->query($getData);
					$my_error= $myDB->getLastError();			
					if(count($allData) > 0 )
					{  
						$table='<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								  <div class=""  >																											                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
									$table .='<th>Employee ID</th>';
									$table .='<th>Employee Name</th>';
									$table .='<th>DOB</th>';	
									$table .='<th>Father Name</th>';
									$table .='<th>Client</th>';
									$table .='<th>Process</th>';
									$table .='<th>Sub Process</th>';
									$table .='<th>DOJ</th>';
									$table .='<th>Designation</th>';
									$table .='<th>Employee Status</th>';
									$table .='<th>LWD</th>';
									$table .='<th>Verification Status</th>';
									$table .='<th>Verified By</th>';
									$table .='<th>Verified By ID</th>';
									$table .='<th>Verification Date</th>';
									$table .='<th>Verification Remarks</th>';
									$table .= '<thead><tbody>';
					    
									foreach($allData as $key=>$value)
									{
										//$EmpName = encrypt($value['EmpName'], "decrypt");
										if($value['EmployeeID']!=""){
											$table .='<td>'.$value['EmployeeID'].'</td>';
											$table .='<td>'.$value['EmployeeName'].'</td>';
											$table .='<td>'.$value['DOB'].'</td> ';
											$table .='<td>'.$value['FatherName'].'</td>';
											$table .='<td>'.$value['clientname'].'</td> ';
											$table .='<td>'.$value['Process'].'</td> ';
											$table .='<td>'.$value['sub_process'].'</td> ';
											$table .='<td>'.$value['DOJ'].'</td> ';
											$table .='<td>'.$value['designation'].'</td> ';
											$table .='<td>'.$value['emp_status'].'</td> ';
											$table .='<td>'.$value['dol'].'</td> ';
											$table .='<td>'.$value['aadhar_status'].'</td> ';
											$table .='<td>'.$value['Created_by_Name'].'</td> ';
											$table .='<td>'.$value['created_by'].'</td> ';
											$table .='<td>'.$value['created_at'].'</td>';
											$table .='<td>'.$value['remarks'].'</td></tr>';
										}
									}
									$table .='</tbody></table></div></div>';
									echo $table;
								}
								else
								{
									echo "<script>$(function(){ toastr.error('No Data Found ".$my_error."'); }); </script>";
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

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
