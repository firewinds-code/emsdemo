<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}
// Global variable used in Page Cycle
$remark=$empname=$empid=$searchBy=$msg='';
$classvarr="'.byID'";		
?>

<script>
	$(document).ready(function(){
		
		$('#txt_ED_joindate_to').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#txt_ED_joindate_from').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
		                "iDisplayLength": 25,				        
				        scrollCollapse: true,
				        lengthMenu: [
				            [ 5,10, 25, 50, -1 ],
				            ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
				        ],
				         buttons: [
						       /* {
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
						        'print',*/
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
						        }
						        /*,'copy'*/
						        ,'pageLength'
						        
						    ]
				       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
				    });
		   	
		   	$('.buttons-copy').attr('id','buttons_copy');
		   	$('.buttons-csv').attr('id','buttons_csv');
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-pdf').attr('id','buttons_pdf');
		   	$('.buttons-print').attr('id','buttons_print');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
		   	$('.byID').addClass('hidden');
		   	$('.byDate').addClass('hidden');
		   	$('.byDept').addClass('hidden');
		   	var classvarr=<?php echo $classvarr; ?>;
		   	$(classvarr).removeClass('hidden');
		   	$('#searchBy').change(function(){
		   		$('.byID').addClass('hidden');
			   	$('.byDate').addClass('hidden');
			   	$('.byDept').addClass('hidden');
			   	$('#txt_ED_joindate_to').val('');
			   	$('#txt_ED_joindate_from').val('');
			   	$('#txt_ED_Dept').val('NA');
			   	$('#ddl_ED_Emp_Name').val('');
		   		if($(this).val()=='By ID')
		   		{
					$('.byID').removeClass('hidden');
				}
				else if($(this).val()=='By Date')
		   		{
					$('.byDate').removeClass('hidden');
				}
				else if($(this).val()=='By Dept')
		   		{
					$('.byDept').removeClass('hidden');
				}
				
				
		   	});
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Alteration Employee Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Alteration Employee Report</h4>				
<!-- Form container if any -->
			<div class="schema-form-section row">
			    <?php
			    $logtype='';
			     if(isset($_GET['altertype']) && trim($_GET['altertype'])!=""){
				 	$logtype=$_GET['altertype'];
				 }
				 ?>
			    <div class="input-field col s12 m12">
					<select name='altertype' id='altertype'>
						<option value='' <?php if($logtype==''){ echo 'selected'; } ?>>Select Alter Type</option>
						<option value='QAreports_to' <?php if($logtype=='QAreports_to'){ echo 'selected'; } ?>>Report To QA</option>
						<option value='reports_to' <?php if($logtype=='reports_to'){ echo 'selected'; } ?>>Report To</option>
						<option value='reactive' <?php if($logtype=='reactive'){ echo 'selected'; } ?> >Reactive </option>
						<option value='Designation' <?php if($logtype=='Designation'){ echo 'selected'; } ?> >Designation </option>
					</select>
				</div>
			  	
			  	<!--Reprot / Data Table start -->
			  	
			  	<div id="pnlTable">
			    <?php
			    function getEmpName($empID){
			    	$myDB=new MysqliDb();
			     	$empname_array=$myDB->query("SELECT EmployeeName from personal_details where EmployeeID='".$empID."'");
			     	//$empname_array=mysqli_fetch_array($select_empname_query);
			     	if(count($empname_array)>0){
						return $empname_array[0]['EmployeeName'];
					}
			     	
			    }
			    function getDesignation($dfid){
			    	$myDB=new MysqliDb();
			     	$desig_array=$myDB->query("select a.Designation  from designation_master a INNER JOIN df_master b ON a.ID=b.des_id where  b.df_id='".$dfid."' ");
			     	//$desig_array=mysql_fetch_array($select_empname_query);
			     	if(count($desig_array)>0){
						return $desig_array[0]['Designation'];
					}
			     	
			    }
			    
			    
			    if($logtype!=""){
			    	 $sqlConnect="SELECT a.*,b.Process,b.sub_process,b.EmployeeName,b.DOJ,st.ReportTo,st.Qa_ops,b.df_id from tbl_log_altaration a inner join whole_dump_emp_data b  on a.EmployeeID=b.EmployeeID inner join status_table st  on a.EmployeeID=st.EmployeeID where type='".$logtype."' order by a.log_date desc limit 0,1000 ";
			    	$myDB=new MysqliDb();
					$result=$myDB->rawQuery($sqlConnect);
					$error = $myDB->getLastError();
					if(empty($error)){?>
						  <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						           <th>Srl. No. </th> 
						           <th>Employee ID</th>
						           <th>Employee Name</th>
						           <th>Process</th>
						           <th>Sub Process</th> 
						           <?php if($logtype=='reports_to'){?>
								   	<th>Pre Reports To </th>
								  <?php  } ?> 
								  <?php if($logtype=='QAreports_to'){?>
								   	<th>Pre QA Reports To </th>
								  <?php  } ?>
						            <th>Current Reports To</th>
					             <?php if($logtype=='Designation'){?>
					             	<th>Pre Designation </th>
					             	<th>Current Designation </th>
					           
					             <?php  } ?>
					            <th>Alteration Date </th>
						         
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       $count=0;
					        foreach($result as $key=>$value){
					        	
					        	$count++;
								echo '<tr>';	
									echo '<td id="countc'.$count.'">'.$count.'</td>';						
									echo '<td class="empid" id="empid'.$count.'">'.$value['EmployeeID'].'</td>';
									echo '<td class="empname" id="empname'.$count.'">'.$value['EmployeeName'].'</td>';			
									echo '<td class="Process" id="Process'.$count.'">'.$value['Process'].'</td>';	
									echo '<td class="sub_process"  id="sub_process'.$count.'" >'.$value['sub_process'].'</td>';
									 if($logtype=='reports_to'){		
										echo '<td class="Prereport"  id="prereport'.$count.'" >'.getEmpName($value['Reports_to']).'</td>';
										echo '<td class="Process"  id="Process'.$count.'" >'.getEmpName($value['ReportTo']).'</td>';	
									}else
									if($logtype=='QAreports_to'){		
										echo '<td class="Prereport"  id="prereport'.$count.'" >'.getEmpName($value['Reports_to']).'</td>';	
										echo '<td class="Process"  id="Process'.$count.'" >'.getEmpName($value['Qa_ops']).'</td>';
									}else
										echo '<td class="Process"  id="Process'.$count.'" >'.getEmpName($value['ReportTo']).'</td>';
									if($logtype=='Designation'){
					             		echo '<td class="Process"  id="Process'.$count.'" >'. getDesignation($value['df_id']).'</td>';
					             		echo '<td class="Process"  id="Process'.$count.'" >'. getDesignation($value['df_id']).'</td>';
					           
					            	 } 
									echo '<td class="Alterationdate"  id="Alterationdate'.$count.'" >'.$value['log_date'].'</td>';	
								echo '</tr>';	
							}	
							?>			       
					    </tbody>
						</table>
						
						<?php
							 }
						
						else
						{
							echo '<div id="div_error" class="slideInDown animated hidden">Data Not Found:: <code >'.$error.'</code> </div>';
						} 
					
			  	}
				?>
				
               
                <!--Reprot / Data Table End -->
                </div>	
             </div>	
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>

			  
<script>
	$(document).ready(function(){
		$('#altertype').on('change',function(){
			if($('#altertype').val()!=""){
				var dtype=$('#altertype').val();
				location.href='AlterationReport.php?altertype='+dtype;
			}
		})
	});
	function checklistdata(){
			//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
			$('.statuscheck').removeClass('hidden');
			
	}
	function getEditData(id){
			var Process= $('#Process'+id).html();
			var SubProcess= $('#sub_process'+id).html();
			var empid= $('#empid'+id).html();
			var empname= $('#empname'+id).html();
			$('#ProcessEdit').val(Process);
			$('#SubProcessEdit').val(SubProcess);
			$('#empidEdit').val(empid);
			$('#empnameEdit').val(empname);
			
			$('.statuscheck').removeClass('hidden');	
			$('#hiddenid').val(id);
			$('#editdataid').show();
		}
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
