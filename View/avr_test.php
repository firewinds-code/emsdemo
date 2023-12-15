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
				
					$getData="SELECT EmployeeID, adhar_no, created_by, aadhar_status, EmpName, FatherName, DOB, created_at, dist, loc, country, subdist, street, vtc, state, house, po, zip, image, remarks FROM ems.aadhar_verifiaction_test";
					$myDB=new MysqliDb();
					$allData=$myDB->query($getData);
					$my_error= $myDB->getLastError();			
					if(count($allData) > 0 )
					{  
						$table='<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								  <div class=""  >																											                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
									$table .='<th>EmployeeID</th>';
									$table .='<th>Name</th>';	
									$table .='<th>Father Name</th>';				
									$table .='<th>Aadhar No</th>';
									$table .='<th>DOB</th>';
									$table .='<th>Status</th>';
									$table .='<th>District</th>';
									$table .='<th>Location</th>';
									$table .='<th>Street</th>';
									$table .='<th>State</th>';
									$table .='<th>House No#</th>';
									$table .='<th>Post Office</th>';
									$table .='<th>Zip Code</th>';
									$table .='<th>Date</th>';
									$table .='<th>Remarks</th>';
									$table .= '<thead><tbody>';
					    
									foreach($allData as $key=>$value)
									{
										$EmpName = encrypt($value['EmpName'], "decrypt");
										$aadhaarno = encrypt($value['adhar_no'],"decrypt");
										$aadhaarno = "XXXX XXXX ".substr($aadhaarno,-4);
										$fathername = encrypt($value['FatherName'],"decrypt");
										$dob = encrypt($value['DOB'],"decrypt");
										
										$table .='<td>'.$value['EmployeeID'].'</td>';
										$table .='<td>'.$EmpName.'</td>';
										$table .='<td>'.$fathername.'</td>';
										$table .='<td>'.$aadhaarno.'</td>';
										$table .='<td>'.$dob.'</td>';
										$table .='<td>'.$value['aadhar_status'].'</td> ';
										$table .='<td>'.$value['dist'].'</td> ';
										$table .='<td>'.$value['loc'].'</td> ';
										$table .='<td>'.$value['street'].'</td> ';
										$table .='<td>'.$value['state'].'</td> ';
										$table .='<td>'.$value['house'].'</td> ';
										$table .='<td>'.$value['po'].'</td> ';
										$table .='<td>'.$value['zip'].'</td> ';
										$table .='<td>'.$value['created_at'].'</td> ';
										$table .='<td>'.$value['remarks'].'</td></tr>';
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
