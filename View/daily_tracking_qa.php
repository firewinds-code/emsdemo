<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

$status='';
$batch_id='';
$searchBy='';
$msg='';
if(isset($_POST['btnSave1']))
{
	
	$createBy=$_SESSION['__user_logid'];
				
	if(isset($_POST['cb']))
	{
		$checked_arr = $_POST['cb'];
		$count_check = count($checked_arr);
		if($count_check>0)
		{		
				foreach($_POST['cb'] as $val)
				{
					$Counter=$val;
					$EmpID=$_POST['txt_EmployeeID_'.$Counter];
					$Remark=$_POST['txt_Remark_'.$Counter];
					
					
					$myDB = new MysqliDb();
					
					$save='call insert_qa_dailyrpt("'.$EmpID.'","'.$createBy.'","'.$Remark.'")';	
					$resulti = $myDB->query($save);
					$mysql_error = $myDB->getLastError();	
					if(empty($mysql_error))
					{
						echo "<script>$(function(){ toastr.success('Record updated successfully.'); }); </script>";	
						if(isset($_POST['de'.$Counter]))
						{
							$myDB=new MysqliDb();
						    $save1='call manage_status_qa("'.$EmpID.'","NO","'.$Remark.'","0","'.$createBy.'","")';	
						
						    $resulti2 = $myDB->query($save1);
						}
						
					}
					else
					{
						echo "<script>$(function(){ toastr.error('Record not updated. $mysql_error'); }); </script>";
					}
					
					
					
					
					
				}
			
			
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
		}
		
	}	
	else
	{
		echo "<script>$(function(){ toastr.error('Record not updated, No Employee selected.'); }); </script>";
	}
	
}

?>

<script>
	$(document).ready(function(){
		$('.statuscheck').addClass('hidden');	
	});
</script>
<style>
	textarea.materialize-textarea {
	    overflow-y: hidden;
	    padding: 0px 8px;
	    resize: none;
	    min-width: 200px;
	}
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Manage Employee Quality Analyst Daily LOG</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Quality Auditor Log</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >

			 <div class="form-inline" >
			 	
			   	<div class="input-field col s6 s6">
		            <select id="text_trcheck_Batch"  name="text_trcheck_Batch" >
		            <option value="NA">----Select----</option>	
				      	<?php
				      					 
							$sqlBy ='SELECT  distinct  batch_master.BacthID,batch_master.BacthName FROM employee_map  left outer join status_table on status_table.EmployeeID=employee_map.EmployeeID  left outer join status_quality on employee_map.EmployeeID=status_quality.EmployeeID  left outer join batch_master on batch_master.BacthID=status_quality.BatchID  where status_quality.Quality="'.$_SESSION['__user_logid'].'" and status_quality.EmployeeID !="'.$_SESSION['__user_logid'].'" and status_table.Status=5 and ojt_status < 2'; 
							$batch_id=0;
							$myDB = new MysqliDb();
							$resultBy = $myDB->query($sqlBy);
							$error = $myDB->getLastError();
							if(count($resultBy) > 0 && $resultBy)
							{
								$selec='';	
								foreach($resultBy as $key=>$value){
									if(isset($_POST['text_trcheck_Batch'])){
										$batch_id=$_POST['text_trcheck_Batch'];	
									}
									
									if($batch_id == $value['BacthID'])
									{
											echo '<option value="'.$value['BacthID'].'" selected >'.$value['BacthName'].'</option>';
									}
									else
									{
											echo '<option value="'.$value['BacthID'].'" >'.$value['BacthName'].'</option>';
									}
								
								}

							}
										
				      	?>
		            </select>
		            <label for="text_trcheck_Batch" class="active-drop-down active">Select Batch Number</label>
		        </div> 
			    <div class="input-field col s6 s6 btnslogs">
			       <input  type="submit" value="Search" name="btnSave" id="btnSave" class="btn waves-effect waves-green"/>
			      
			    </div>
			    <div class="input-field col s12 s12 btnslogs">
			    	<hr/>
			    </div>
			    <div class="input-field col s12 s12 btnslogs right-align">
			       <input  type="submit" value="Submit" name="btnSave1" id="btnSave1" class="btn waves-effect waves-green"/>
			       
			    </div>
			  	<div id="pnlTable" class="col s12 m12 card">
			    <?php 
			    	if(isset($_POST['text_trcheck_Batch']))
			    	{
						$batch_id=$_POST['text_trcheck_Batch'];
					}
					else
					{
						$batch_id = 'NA';
					}
					if($batch_id != 'NA')
					{
						$sqlConnect='call get_qachecklist_daily("'.$_SESSION['__user_logid'].'",'.$batch_id.')';
			    	$myDB=new MysqliDb();
					$result=$myDB->query($sqlConnect);
					//echo $sqlConnect;
					$error=$myDB->getLastError();
					if($result){?>
						
			   			 <div class="flow-x-scroll">
						  <table id="myTable" class="data dataTable no-footer" cellspacing="0" >
					        <thead>
					          <tr>
					            <th><input type="checkbox" id="cbAll" name="cbAll" value="ALL"><label for="cbAll">EmployeeID</label></th>
					            <th >Not Trainable (To QH)</th>
					            <th class="hidden">Employee ID</th>
					            
					            <th class="hidden">Employee ID</th>
					            <th class="">Employee Name</th>
					            <th class="">Training Status</th>						            
					            <th class="">Final Date</th>
					            <th class="edit_info_header hidden">Remark</th>			            
					            
					            
					            <th class="edit_view_header">Client</th>
					            <th class="edit_view_header">Process</th>
					            <th class="edit_view_header">Sub Process</th>
					            
					         </tr>
					        </thead>
					    <tbody>					        
					       <?php
					       $count=0;
					        foreach($result as $key=>$value){
					        	$count++;
							echo '<tr>';							
							echo '<td class="EmpId"><input type="checkbox" id="cb'.$count.'" class="cb_child" name="cb[]" value="'.$count.'"><label for="cb'.$count.'" style="color: #059977;font-size: 14px;font-weight: bold;}">'.$value['EmployeeID'].'</label></td>';
							
							if($value['DateDiff'] =='0' || $value['DateDiff'] =='-1')
							{
								echo '<td ><input type="checkbox" id="de'.$count.'" class="de_child" name="de'.$count.'" value="'.$count.'"><label for="de'.$count.'" style="color: #059977;font-size: 14px;font-weight: bold;}">';
							}
							else
							{
								echo '<td><kbd>NA</kbd></td>';					
							}
							echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="'.$value['EmployeeID'].'">'.$value['EmployeeID'].'</a></td>';
							echo '<td class="FullName  ">'.$value['EmployeeName'].'</td>';	
							if($value['ojt_status'] >= '2' )
							{
								echo '<td class="retrain_flag  ">Re-OJT</td>';		
							}				
							else
							{
								echo '<td class="retrain_flag  ">OJT</td>';	
							}
							
							
							echo '<td class="date_cer_1 ">'.date('d M,Y',strtotime($value['Final_OJT_date'])).'</td>';
						
							echo '<td class="hidden input-field" style="padding:0px;"><input type="test" name="txt_EmployeeID_'.$count.'" id="txt_EmployeeID_'.$count.'" readonly="true" value="'.$value['EmployeeID'].'" /></td>';
							
							echo '<td class="edit_info_body hidden input-field"  style="padding:0px;"><textarea name="txt_Remark_'.$count.'" id="txt_Remark_'.$count.'"  class="materialize-textarea" title="Remark">NA</textarea></td>';
							
							
							
							
							echo '<td class="client_name edit_view">'.$value['client_name'].'</td>';					
							echo '<td class="process edit_view">'.$value['process'].'</td>';					
							echo '<td class="sub_process edit_view">'.$value['sub_process'].'</td>';								
							
							echo '</tr>';
							}	
							?>			       
					    </tbody>
						</table>
						  
						</div>
						<?php
							 }
						
						else
						{
							?>
							<Script>
							toastr.info("Congratulations have been aligned to concern departments." + <?php echo '"'.$error.'"';?>);
							</script>
							<?php
						} 
					}
			    	
					
			  	
				?>
					
				</div>
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
		
		$('#btnSave1').click(function(){
	    	 var validate=0;
	    	 var alert_msg='';
	    	
	    	if($('input.cb_child:checkbox:checked').length <= 0)
		     {
		     	validate=1;
		     	toastr.info('Check Atleast On Employee');
			 }
			$('input.cb_child:checkbox:checked').each(function(){
			 	if($(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val() == '' || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length < 100)
			 	{
					validate=1;
					toastr.info('Remark Can\'t be Empty or not less than 100 For any Checked Employee.');
				}
			 	
			 	
			 	
			 });
	    	 if(validate==1)
		      	{		      		
		      		
					return false;
				}
	   	});
	   	
		$('#div_error').removeClass('hidden');
		$("input:checkbox").click(function(){
			if($('input:checkbox:checked').length>0)
			{
				checklistdata();
			}
			else
			{
				$('#txt_thcheck_Trainer').val('No');
				$('.statuscheck').addClass('hidden');			
				$('#docTable').html('');
				$('#docstable').addClass('hidden');
			}
		});
		$('#div_error').removeClass('hidden');
		$("#cbAll").change(function () {
		    $("input.cb_child:checkbox").prop('checked', $(this).prop("checked"));
		});
		$("input:checkbox").change(function(){
			checkbox_check();
			if($('input.cb_child:checkbox:checked').length>0)
			{
				if ($('input.cb_child:checkbox:checked').length ==$('input.cb_child:checkbox').length) {
					
			        $("#cbAll").prop("checked",true);
			    }
			    else
			    {
					$("#cbAll").prop("checked",false);
				}
			}
			else
			{
				$("#cbAll").prop("checked",false);
				$('#txt_thcheck_Trainer').val('NA');
				$('.statuscheck').addClass('hidden');			
				$('#docTable').html('');
				$('#docstable').addClass('hidden');
			}
		});
		$('#text_trcheck_Batch').change(function(){
			$('.btnslogs').removeClass('hidden');
		});
		if($('#text_trcheck_Batch').val()==''||$('#text_trcheck_Batch').val()=='NA'||$('#text_trcheck_Batch').val()==null)
		{
			$('.btnslogs').addClass('hidden');
		}
		
	});
	function checkbox_check()
	{
		$('.edit_info_body').addClass('hidden');
		$('.edit_info_header').addClass('hidden');
		$('.edit_view').removeClass('hidden');
		$('.edit_view_header').removeClass('hidden');	
		if($('input.cb_child:checkbox:checked').length>0)
		{
				$('input.cb_child:checkbox:checked').each(function(){
				
				$(this).closest('tr').find('.edit_info_body').removeClass('hidden');
				$(this).closest('tr').find('.edit_view').addClass('hidden');
				$('.edit_info_header').removeClass('hidden');
				$('.edit_view_header').addClass('hidden');
			});
		
		}	
		
	}
	function checklistdata(el)
	{
		
	} 
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>