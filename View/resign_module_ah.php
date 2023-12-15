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
$Status='';
$batch_id='';
$classvarr="'.byID'";
$searchBy='';
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
			$myDB=new MysqliDb();
			
				foreach($_POST['cb'] as $val)
				{
					$Counter=$val;
					$EmpID=$_POST['txt_EmployeeID_'.$Counter];
					$Remark=$_POST['txt_Remark_'.$Counter];
					$myDB=new MysqliDb();
					
					if($Status !='NA'||$Status !='Na')
					{
						$myDB=new MysqliDb();
						
						$sql='update resign_details set status_ah = "1" where EmployeeID = "'.$EmpID.'"';
						
						$resulti = $myDB->rawQuery($sql);
						$mysql_error=$myDB->getLastError();
						$rowCount = $myDB->count;	
						if($rowCount>0)
						{
							echo "<script>$(function(){ toastr.success('Request Updated Successfully...'); }); </script>";
							$myDB=new MysqliDb();
							$sql1 = 'INSERT INTO resign_comment_log(`EmployeeID`,`CreatedBy`,`CreatedOn`,`Comment`,`type`)VALUES("'.$EmpID.'","'.$createBy.'",now(),"'.$Remark.'","AH")';
							$rstdt = $myDB->rawQuery($sql1);
														
						}
						else
						{
							echo "<script>$(function(){ toastr.error('Request Not Updated ::Error :- <code>".$mysql_error."</code>'); }); </script>";
						}
					}
					
				}	
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Request Not Updated ::Error :- <code>No Employee Selected ....</code>'); }); </script>";
		}
		
	}	
	else
	{
		echo "<script>$(function(){ toastr.error('Request Not Updated ::Error :- <code>No Employee Selected ....</code>'); }); </script>";
	}
	
}
?>
<script>
	$(document).ready(function(){
		$('.datetimepicker_text').each(function(){
			$(this).datetimepicker({timepicker:false,format:'Y-m-d'});
		});
		$('#myTable').DataTable({
				        dom: 'Bfrtip',	
				        scrollX: '100%',
				        "iDisplayLength": 25,				        
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
		   	
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
		   	$('.byID').addClass('hidden');
		   
		   	var classvarr=<?php echo $classvarr; ?>;
		   	$(classvarr).removeClass('hidden');
		   	$('#searchBy').change(function(){
		   		$('.byID').addClass('hidden');
		   		if($(this).val()=='By ID')
		   		{
					$('.byID').removeClass('hidden');
				}
		   	});
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Employee transfer to HR - Resigned [AH]</span>

<!-- Main Div for all Page -->
<div class="pim-container " >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Employee transfer to HR - Resigned [AH]</h4>	
	 
<!-- Form container if any -->
	<div class="schema-form-section row" >
			    <div class="input-field col s12 m12 right-align">
						<button type="submit"   value="Save Request " name="btnSave" id="btnSave"  onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green">Save Request</button>
				</div>
				
			  	<div id="pnlTable">
			    <?php 
			    	$myDB=new MysqliDb();
					$chk_task=$myDB->rawQuery('select whole_details_peremp.EmployeeID,whole_details_peremp.EmployeeName,resign_details.remark,resign_details.nt_start,resign_details.nt_end,whole_details_peremp.Process,whole_details_peremp.sub_process,whole_details_peremp.clientname from resign_details inner join whole_details_peremp on whole_details_peremp.EmployeeID = resign_details.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = resign_details.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo where (curdate() between cast(DATE_SUB(resign_details.nt_end, INTERVAL 3 DAY) as date)  and cast(resign_details.nt_end as date)) and  (account_head = "'.$_SESSION['__user_logid'].'") and (status_ah  is null or status_ah = 0) and nt_end >= curdate() and rg_status < 9');
					//echo $sqlConnect;
				/*	$chk_task=$myDB->rawQuery('select whole_details_peremp.EmployeeID,whole_details_peremp.EmployeeName,resign_details.remark,resign_details.nt_start,resign_details.nt_end,whole_details_peremp.Process,whole_details_peremp.sub_process,whole_details_peremp.clientname from resign_details inner join whole_details_peremp on whole_details_peremp.EmployeeID = resign_details.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID = resign_details.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo');*/
					
					$error=$myDB->getLastError();
					$rowCount = $myDB->count;
					if($chk_task && $rowCount>0){?>
						
			   			<div class="had-container pull-left row card dataTableInline"  >
							<div class=""  >																																		 							<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						        <thead>
						          <tr>
						            <th><input type="checkbox" id="cbAll" name="cbAll" value="ALL"><label for="cbAll">EmployeeID</label></th>
						            <th class="hidden">EmployeeID</th>
						            <th class="hidden">EmployeeID</th>
						            <th class="">Employee Name</th>
						            <th class="">Account Head Remark </th>
						            <th class="">Notice Start</th>
						            <th class="">Notice End</th>
						            <th class="edit_info_header hidden">Remark</th>
						            <th class="edit_view_header">Client</th>
						            <th class="edit_view_header">Process</th>
						            <th class="edit_view_header">Sub Process</th>
						           
						         </tr>
						        </thead>
					    <tbody>					        
					       <?php
					       $count=0;
					        foreach($chk_task as $key=>$value){
					        	$count++;
							echo '<tr>';							
							echo '<td class="EmpId"><input type="checkbox" id="cb'.$count.'" class="cb_child" name="cb[]" value="'.$count.'"><label for="cb'.$count.'" >'.$value['EmployeeID'].'</label></td>';
							
							echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  class="ckeckdata" data="'.$value['EmployeeID'].'">'.$value['EmployeeID'].'</a></td>';
							echo '<td class="FullName  ">'.$value['EmployeeName'].'</td>';	
							
							echo '<td class="remark  ">'.$value['remark'].'</td>';			
							echo '<td class=" nt_start  ">'.$value['nt_start'].'</td>';			
							echo '<td class=" nt_end  ">'.$value['nt_end'].'</td>';					
							
							
							echo '<td class="hidden" ><input  type="input" name="txt_EmployeeID_'.$count.'" id="txt_EmployeeID_'.$count.'" readonly="true" value="'.$value['EmployeeID'].'" /></td>';
							
							echo '<td class="edit_info_body hidden"  ><textarea  class="materialize-textarea materialize-textarea-size" name="txt_Remark_'.$count.'" id="txt_Remark_'.$count.'"  title="Remark">NA</textarea></td>';
							echo '<td class="client_name edit_view">'.$value['clientname'].'</td>';					
							echo '<td class="process edit_view">'.$value['Process'].'</td>';					
							echo '<td class="sub_process edit_view">'.$value['sub_process'].'</td>';					
							
							echo '</tr>';
							}	
							?>			       
					    </tbody>
						</table>
						  </div>
						</div>
						<?php
						}
						
						else
						{
								echo "<script>$(function(){ toastr.success('Congratulations, all employees have been aligned to concern departments.<code >".$error."</code>'); }); </script>";
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
		
		$("input:checkbox").click(function(){
			
		});
		
		$("#cbAll").change(function () {
		    $("input:checkbox").prop('checked', $(this).prop("checked"));
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
				
				
			}
		});
		$('#btnSave').click(function(){
	    	 var validate=0;
	    	 var alert_msg='';
	    	
	    	 if($('input.cb_child:checkbox:checked').length<=0)
		     {
		     	validate=1;
		     	alert_msg+='<li> Check Atleast On Employee ....  </li>';
			 }
			$('input.cb_child:checkbox:checked').each(function(){
			 	if($(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val() == '' || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length < 50 && $(this).parent('td').closest('tr').find('select[id^="txt_Status_"]').val() == 'Reject')
			 	{
					validate=1;
					alert_msg+='<li> Remark Can\'t be Empty or not less than 50 For any Rejected Requests...</li>';
				}
			 	
			 	
			 	
			 });
	    	 if(validate==1)
		      	{	/*	      		
		      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
		      		$('#alert_message').show().attr("class","SlideInRight animated");
		      		$('#alert_message').delay(5000).fadeOut("slow");
					return false;
					*/
					$(function(){ toastr.error(alert_msg) });
					return false;
				}
	   	});
		
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
		
	
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>