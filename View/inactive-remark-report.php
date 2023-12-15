<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
$remark=$empname=$empid=$msg=$searchBy='';
$classvarr="'.byID'";	
?>

<script>
	$(document).ready(function(){
		$('.statuscheck').addClass('hidden');
		$('#txt_ED_joindate_to').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#txt_ED_joindate_from').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
				        "iDisplayLength": 25,
				        scrollX: "100%",				        
				        scrollCollapse: true,
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
<span id="PageTittle_span" class="hidden">Manage Inactive Employee Report</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Manage Inactive Employee Report</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	
<!--Reprot / Data Table start -->
			  	 <div id="pnlTable">
			    <?php 
			    	$sqlConnect="call InActiveCallReport()";
			    	$myDB=new MysqliDb();
					$result=$myDB->query($sqlConnect);
					//print_r($result);
					//echo $sqlConnect;
					$error=mysql_error();
					if($result){?>
			   			<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						           	<th>Srl. No. </th> 
						          	<th>Employee ID</th>
						           	<th>Employee Name</th>
						           	<th>Client</th>
						           	<th>Process</th>
						           	<th>Sub Process</th>
						           	<th>Contact Status</th>		
						        	<th>Call Disposition</th>
						            <th>Called Remark</th>
						            <th>Date of Joinig </th>
						            <th>Date of Leaving </th>
						            <th>Resion of Leaving </th>
						            <th>Exit Disposition </th>		
						            <th>Called Date</th>
						            <th>Called By</th>
						            <th>Future Company Name</th>
						            <th>Future Company Location</th>
						            <th>Future Company Level</th> 
						            <th>Future Company CTC</th> 
						            <th>Future Join Date</th> 
						            <th>Did You Apply Leave</th>
						            <th>Apply to Whome</th>
						            <th>Is Applied Leave</th>
						            <th>Rejoin Date</th>
						            <th>Learning Type</th>
						            <th>Are You Join</th>
						            <th>Join Back Date</th>
						            <th>Willing to work</th>
						            <th>Preferred Location</th>
						            <th>Preferred Rejoin Date</th>
						            <th>Preferred Rejoin Location</th> 
						            <th>Preferred Shift</th>
						            <th>Wlling to rejoin</th>
						            <th>Pref. Rejoin Date</th>
						            <th>Offer Other Process</th>
						            <th>Willing Join back</th>
						            <th>Offer Join Date</th>
						         
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
								echo '<td class="Disposition" id="Disposition'.$count.'">'.$value['clientname'].'</td>';	
								echo '<td class="Process"  id="Process'.$count.'" >'.$value['Process'].'</td>';	
								echo '<td class="sub_process"  id="sub_process'.$count.'" >'.$value['sub_process'].'</td>';
								echo '<td class="dol"  id="dol'.$count.'" >'.$value['contact_status'].'</td>';	
								echo '<td class="Disposition" id="Disposition'.$count.'">'.$value['CallDisp'].'</td>';	
								echo '<td class="Disposition" id="Disposition'.$count.'">'.$value['remark'].'</td>';		
								echo '<td class="doj"  id="doj'.$count.'" >'.$value['DOJ'].'</td>';	
								echo '<td class="dol"  id="dol'.$count.'" >'.$value['dol'].'</td>';	
								echo '<td class="dol"  id="dol'.$count.'" >'.$value['rsnofleaving'].'</td>';
								echo '<td class="dol"  id="dol'.$count.'" >'.$value['exitdisp'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['ContactedOn'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['ContactedBy'].'</td>';
									
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['future_company_name'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['future_company_location'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['future_company_level'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['future_company_ctc'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['future_join_date'].'</td>';
									
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['fdid_apply_leave'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['whome_apply'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['apply_leave'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['familyr_rejoin_date'].'</td>';	
								
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['learning_type'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['join_back'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['join_date'].'</td>';
									
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['willing_work'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['job_pref_location'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['job_pref_date'].'</td>';
									
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['pref_location'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['pref_shift'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['pref_distance_join'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['pref_join_date'].'</td>';
									
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['op_offer'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['op_join'].'</td>';	
								echo '<td class="created_on" id="created_on'.$count.'">'.$value['op_join_date'].'</td>';	
							 }	
							?>			       
					    </tbody>
						</table>
						<?php
						  }
						  else
						  {
							echo "<script>$(function(){ toastr.error('Data Not Found ".$error."'); }); </script>";
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
		}else
		{
			$('#alert_message').delay(5000).fadeOut("slow");
		}
		
		$('#div_error').removeClass('hidden');
		
		
		$('#btnCancel').click(function(){
			$('.statuscheck').addClass('hidden');	;
		});
		$('#btnSave1').click(function(){
			//alert('hide');
			var  remark=$('#remark').val().trim();
			if(remark==""){
				validate=1;
		     	alert_msg='<li> Remark should not be empty</li>';
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
</form>
</body>
</html>