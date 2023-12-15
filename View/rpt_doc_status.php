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
		
		if($isPostBack && isset($_POST['txt_docStaus']))
		{
			$doctype = $_POST['txt_doctype'];
			$docStatus =$_POST['txt_docStaus'];
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
						        },'copy','pageLength'
						        
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
<span id="PageTittle_span" class="hidden">Docs Status Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Docs Status Report</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
		<div class="input-field col s5 m5">	
			<select name="txt_doctype" id="txt_doctype" class="form-control">
				<option value="PAN Card">PAN Card</option>
				<option value="Adhar Card">Aadhar Card</option>
			</select>
		</div>
		<div class="input-field col s5 m5">
			<select name="txt_docStaus" id="txt_docStaus" class="form-control">
				<option value="Having">Having</option>
				<option value="Not Having">Not Having</option>
			</select>
		</div>
		<div class="input-field col s2 m2 right-align">
			<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
			<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
		</div>
		
<!--Form element model popup End-->
<!--Reprot / Data Table start -->
	
		<?php
			if(isset($_POST['btn_view']))
			{
				$myDB=new MysqliDb();
				$chk_task  =array();
				if($docStatus == 'Having')
				{
					$sqlBy="select  distinct t1.EmployeeID, EmployeeName, t2.dov_value as '".(($doctype == 'Adhar Card')?'Aadhar Card':$doctype)."', t2.doc_file,  Process, sub_process, designation from  whole_details_peremp t1  join doc_details t2 on t1.EmployeeID=t2.EmployeeID where doc_stype='".(($doctype == 'Adhar Card')?'Aadhar Card':$doctype)."' and  dov_value is not null and dov_value!='' and doc_file is not null and doc_file!=''";
					
				}
				else
				{
					$sqlBy="select  distinct t1.EmployeeID, EmployeeName, Process, sub_process from  whole_details_peremp t1 where t1.EmployeeID not in  (select EmployeeID from  doc_details  where doc_stype='".(($doctype == 'Adhar Card')?'Aadhar Card':$doctype)."' and doc_file is not null and dov_value is not null and dov_value!='' and doc_file !='')";
				}
				
				//echo $sqlBy;
				$myDB=new MysqliDb();
			    $chk_task=$myDB->rawQuery($sqlBy);
				$mysql_error = $myDB->getLastError();
				if(empty($mysql_error))
				{   
					$table='<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
					
					foreach($chk_task[0] as $key=>$value)
					{
						$table .= '<th>'.$key.'</th>';
					}
					
				    $table .='</tr><thead><tbody>';
    
					foreach($chk_task as $key=>$value)
					{
						$table .= '<tr>';
						foreach($value as $k=>$v)
						{
							$table .= '<td>'.(($v == 'Adhar Card')?'Aadhar Card':$v).'</td>';
						}
						
						$table .= '</tr>';
					}
					$table .='</tbody></table></div>';
					echo $table;
				}
				else
				{
					echo "<script>$(function(){ toastr.error('No Data Found ".$mysql_error."'); }); </script>";
				}
			}
			
		 ?>

<!--Reprot / Data Table End -->	
	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
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
		$("#btn_view").click(function(){
			$(this).addClass('hidden');
		});
	});
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>