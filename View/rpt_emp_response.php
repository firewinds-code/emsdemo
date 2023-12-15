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
	echo "<script>location.href='". $location."'</script>";
}
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
			
		   
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Employee Response Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Employee Response</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >

		<?php
			$myDB = new MysqliDb();
			
			$chk_task=$myDB->query("SELECT a.EmployeeID, a.response, a.remarks, a.createdOn,b.EmployeeName,b.clientname,b.Process,b.Process,b.sub_process from emp_question_response  a INNER JOIN  whole_details_peremp b ON a.EmployeeID=b.EmployeeID order by  a.id desc");
			$my_error= $myDB->getLastError();	
			
			if(count($chk_task) > 0 && $chk_task)
			{  
				$table='<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
				$table .='<th>EmployeeID</th>';
				$table .='<th>EmployeeName</th>';
				$table .='<th>Client</th>';
				$table .='<th>Process</th>';
				$table .='<th>Sub Process</th>';				
				$table .='<th>Response</th>';
				$table .='<th>Remark</th>';
				$table .='<th>Response Date</th>';
				
			     $table .='<thead><tbody>';
    
				foreach($chk_task as $key=>$value)
				{
					$table .='<tr><td>'.$value['EmployeeID'].'</td>';
					$table .='<td>'.$value['EmployeeName'].'</td>';
					$table .='<td>'.$value['clientname'].'</td>';
					$table .='<td>'.$value['Process'].'</td>';
					$table .='<td>'.$value['sub_process'].'</td>';				
					$table .='<td>'.$value['response'].'</td>';
					$table .='<td>'.$value['remarks'].'</td>';
					$table .='<td>'.$value['createdOn'].'</td>';
					$table .='</tr>';	
				}
				$table .='</tbody></table></div>';
				echo $table;
			}
			else
			{
				echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
				
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