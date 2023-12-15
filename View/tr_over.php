<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');


$last_to=$last_from=$last_to=$dept=$emp_nam=$status='';
$msg='';
if(isset($_POST['btnSave']))
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
					$empID=$val;
					$myDB=new MysqliDb();
					$save='call manage_status_th_after("'.$empID.'","'.$_POST['txt_Remark_'.$empID].'","'.$createBy.'","TH OVER")';	
					$resulti = $myDB->query($save);
					$mysql_error = $myDB->getLastError();	
					if(empty($mysql_error))
					{
						echo "<script>$(function(){ toastr.success('Employee $empID Refer to QH successfully.'); }); </script>";
						
					}
					else
					{
						echo "<script>$(function(){ toastr.error('Record not updated.$mysql_error'); }); </script>";
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
if(isset($_POST['btn_refer']))
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
					$empID=$val;
					$myDB=new MysqliDb();
					$save='call manage_refer_hr("'.$empID.'","'.$createBy.'","'.$_POST['txt_Remark_'.$empID].'","TH OVER REFER")';	
					$resulti = $myDB->query($save);
					$mysql_error= $myDB->getLastError();	
					if(empty($mysql_error))
					{
						echo "<script>$(function(){ toastr.success('Employee  $empID reffered to HR successfully.'); }); </script>";
						
					}
					else
					{
						echo "<script>$(function(){ toastr.error('Record not updated for $empID. $mysql_error'); }); </script>";
						
					}
				}
			
			
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Record not updated, No Employee Selected.'); }); </script>";
		}
		
	}	
	else
	{
		echo "<script>$(function(){ toastr.error('Record not updated, No Employee Selected.'); }); </script>";
	}
}
if(isset($_POST['btn_retrain']))
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
					$empID=$val;
					
					$myDB=new MysqliDb();
					$check_rtr_q = $myDB->query('select EmployeeID from status_training where retrain_flag = 1 and EmployeeID = "'.$empID.'"');
					if(count($check_rtr_q) > 0 && $check_rtr_q)
					{
						echo "<script>$(function(){ toastr.error('$empID not referred to Re-Training, Employee already referred in Re-Training once'); }); </script>";
					}
					else
					{
						$myDB=new MysqliDb();
						$save='call manage_retrain("'.$empID.'","'.$createBy.'","'.$_POST['txt_Remark_'.$empID].'","TH OVER Retrain")';	
						$resulti = $myDB->query($save);
						$mysql_error= $myDB->getLastError();	
						if(empty($mysql_error))
						{
							echo "<script>$(function(){ toastr.success('$empID reffered to Trainer successfully.'); }); </script>";
						}
						else
						{
							echo "<script>$(function(){ toastr.success('Record not updated for $empID.$mysql_error'); }); </script>";
						}
					}
				}
			
			
		}
		else
		{
			echo "<script>$(function(){ toastr.success('Record not updated.No Employee Selected.'); }); </script>";
		}
		
	}	
	else
	{
		echo "<script>$(function(){ toastr.success('Record not updated.No Employee Selected.'); }); </script>";
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
	    min-height: 2rem;
	}
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Manage Employee Training Head Over Rule-Training</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Manage Trainee</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >

			 <div class="form-inline" >
			     
			     <div class="input-field col s12 m12 no-padding statuscheck">
			      <div class="input-field col s6 m6 statuscheck">
			      	  <input  type="submit" value="Refer To HR" name="btn_refer" id="btn_refer" title="All the checked Employee Refer To HR" class="btn waves-effect waves-light green lighten-1"/>			      	  
			      	  <input  type="submit" value="Back to BATCH " name="btn_retrain" id="btn_retrain" title="All the checked Employee Refer To  Trainer" class="btn waves-effect waves-light red darken-1"/>
				      
			      </div>
			      <div class="input-field col s6 m6 statuscheck right-align">
				      <!--<input  type="submit" value="Send to QH" name="btnSave" id="btnSave" class="btn waves-effect waves-green"/>-->
				      <input  type="button" value="Cancel" name="btnCan" id="btnCan" class="btn waves-effect waves-red close-btn"/>
			      </div>
			    </div>
			    
			     
			  	<div id="pnlTable" class="col s12 m12 card">
			    <?php 
			    	$sqlConnect='call get_thchecklist_over("'.$_SESSION['__user_logid'].'")';
			    	$myDB=new MysqliDb();
					$result = $myDB->query($sqlConnect);
					//echo $sqlConnect;
					$error= $myDB->getLastError();
					if(count($result) > 0 && $result)
					{?>
						
			   			 <div class="flow-x-scroll">																											  <table id="myTable" class="data dataTable no-footer" cellspacing="0">
						    <thead>
						        <tr>
						            <th><input type="checkbox" id="cbAll" name="cbAll" value="ALL"><label for="cbAll">EmployeeID</label></th>
						            <th class="hidden">Employee ID</th>
						            <th>Employee Name</th>
						            <th>Certification Status</th>
						            <th class="">Training Staus</th>
						            <th class="">Retrain Staus</th>
						            <th>Client</th>
						            <th>Process</th>
						            <th>Sub Process</th>
						            <th>Remark</th>
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       $count=0;
					        foreach($result as $key=>$value){
					        	$count++;
							echo '<tr>';							
							echo '<td class="EmpId"><input type="checkbox" id="cb'.$count.'" class="cb_child" name="cb[]" value="'.$value['EmployeeID'].'"><label for="cb'.$count.'" style="color: #059977;font-size: 14px;font-weight: bold;}">'.$value['EmployeeID'].'</label></td>';
							echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="'.$value['EmployeeID'].'">'.$value['EmployeeID'].'</a></td>';
							echo '<td class="FullName">'.$value['EmployeeName'].'</td>';					
							echo '<td class="c_status">'.$value['c_status'].'</td>';	
							if($value['de_status'] > 0)
							{
								echo '<td class="de_status">Training Incomplete</td>';		
							}				
							else
							{
								echo '<td class="de_status text-danger">Training Complete</td>';	
							}
								
							if($value['retrain_flag'] == '1' )
							{
								echo '<td class="cirtification_level  ">Re- Training</td>';		
							}				
							else
							{
								echo '<td class="cirtification_level  ">Training</td>';	
							}			
							echo '<td class="client_name">'.$value['client_name'].'</td>';					
							echo '<td class="process">'.$value['process'].'</td>';					
							echo '<td class="sub_process">'.$value['sub_process'].'</td>';					
							echo '<td class="Remark no-padding input-field" ><textarea type="text" style="min-width:200px;" class="materialize-textarea" name="txt_Remark_'.$value['EmployeeID'].'" id="txt_Remark_'.$value['EmployeeID'].'" class="txt_remark" placeholder="Enter Remarks for '.$value['EmployeeName'].'" ></textarea></td>';					
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
							echo "<script>$(function(){ toastr.info('Congratulations have been aligned to concern departments. ".$error." '); }); </script>";
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
		
		$('#btnSave,#btn_refer,#btn_retrain').click(function(){
	    	 var validate=0;
	    	 var alert_msg='';
	    	
	    	 if($('input.cb_child:checkbox:checked').length<=0)
		     {
		     	validate=1;
		     	toastr.info('Check Atleast On Employee.');
			 }
			$('input.cb_child:checkbox:checked').each(function(){
			 	if($(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val() == '' || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length < 10  || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length > 100) {
					validate = 1;
				//toastr.info('Remark Can\'t be Empty or not less than 100 For any Checked Employee.');
					toastr.info('Remark can\'t be empty, it should be between 10 to 100 characters');
				}
			 	
			 	
			 	
			 });
	    	 if(validate==1)
		      	{		      		
		      		return false;
				}
	   	});
		$('#btnCan').click(function(){
			$("input:checkbox").prop('checked',false);
			$('#txt_thcheck_Trainer').val('NA');
			$('.statuscheck').addClass('hidden');			
			$('#docTable').html('');
			$('#docstable').addClass('hidden');
		});
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
		    $("input:checkbox").prop('checked', $(this).prop("checked"));
		});
		$("input:checkbox").change(function(){
			if($('input.cb_child:checkbox:checked').length>0)
			{
				checklistdata();
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
		$('#btn_refer').click(function(){
			
			if(confirm("You really want to Refer these Employee to HR..."))
			{
				return true;
			}
			else
			{
				return false;
			}
			return false;
		});
		$('#btn_retrain').click(function(){
			
			if(confirm("You really want to Refer these Employee For ReTraining because if any Employee is already Retrain then that auto refer to HR..."))
			{
				return true;
			}
			else
			{
				return false;
			}
			return false;
		});
		
	});
	function checklistdata(){
			//$('#txt_thcheck_EmplyeeID').val($(el).attr('data'));
			$('.statuscheck').removeClass('hidden');
			
		}
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>