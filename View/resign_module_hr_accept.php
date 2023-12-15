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
$status='';
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
					$Status=$_POST['txt_Status_'.$Counter];
					$Remark=$_POST['txt_Remark_'.$Counter];
					
					if($Status !='NA'||$Status !='Na')
					{
						$myDB=new MysqliDb();
						
						if($Status == 'Accept')
						{
							$Status = 1;
							$sql='update resign_details set accept = "'.$Status.'" ,accept_time = now(),rg_status=1,accepter_remark ="'.$Remark.'",accepter ="'.$createBy.'" where EmployeeID = "'.$EmpID.'" and rg_status = 0';
						}
						else
						{
							$Status = 2;
							$sql='update resign_details set accept = "'.$Status.'" ,accept_time = now(),rg_status=9,accepter_remark ="'.$Remark.'",accepter ="'.$createBy.'" where EmployeeID = "'.$EmpID.'" and rg_status = 0';
						}
						
						
						$resulti = $myDB->rawQuery($sql);
						$mysql_error=$myDB->getLastError();	
						$rowCount = $myDB->count;
						if($rowCount>0)
						{
							$request_for ='';
							$myDB=new MysqliDb();
							if($Status == 1)
							{
								$result2 = $myDB->rawQuery('select nt_start,nt_end  from resign_details where EmployeeID = "'.$EmpID.'" and rg_status = 1 and accept = 1 order by accept_time desc limit 1;');
								$request_for ='RESIGN Accept|'.$result2[0]['nt_start'].'|'.$result2[0]['nt_end'];
								$myDB=new MysqliDb();
							    $result3 = $myDB->rawQuery('INSERT INTO alert_details(EmployeeID,alert_start,alert_end,type,createdon,createdby)VALUES("'.$EmpID.'","'.$result2[0]['nt_start'].'","'.$result2[0]['nt_end'].'","'.$request_for.'","'.date('Y-m-d H:i:s',time()).'","'.$createBy.'")');
							}
							else
							{
								$result2 = $myDB->rawQuery('select nt_start,nt_end  from resign_details where EmployeeID = "'.$EmpID.'" and rg_status = 9 and accept = 2 order by accept_time desc limit 1;');
								$request_for ='RESIGN Reject|'.$result2[0]['nt_start'].'|'.$result2[0]['nt_end'];
								$myDB=new MysqliDb();
							    $result3 = $myDB->rawQuery('INSERT INTO alert_details(EmployeeID,alert_start,alert_end,type,createdon,createdby)VALUES("'.$EmpID.'","'.date('Y-m-d',time()).'","'.date('Y-m-d',strtotime('+3 days')).'","'.$request_for.'","'.date('Y-m-d H:i:s',time()).'","'.$createBy.'")');
							}

							echo "<script>$(function(){ toastr.success('Request Updated Successfully...'); }); </script>";
														
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
		   	
		   	$('.buttons-copy').attr('id','buttons_copy');
		   	$('.buttons-csv').attr('id','buttons_csv');
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-pdf').attr('id','buttons_pdf');
		   	$('.buttons-print').attr('id','buttons_print');
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
<span id="PageTittle_span" class="hidden">Employee transfer to HR - Resigned </span>

<!-- Main Div for all Page -->
<div class="pim-container " >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Employee transfer to HR - Resigned </h4>	
	 
<!-- Form container if any -->
	<div class="schema-form-section row" >
	
				<div class="input-field col s12 m12 right-align">
						<button type="submit"   value="Click for Update Request " name="btnSave" id="btnSave"  onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green">Click for Update Request</button>
				</div>


			  	<div id="pnlTable">
			    <?php 
			    if($_SESSION["__location"]=="1")
			    {
					$sqlConnect='select  wh.EmployeeID,wh.EmployeeName,rs.remark,rs.nt_start,rs.rg_status ,rs.nt_end,wh.Process,wh.sub_process,wh.clientname,pd1.img from resign_details rs inner join whole_details_peremp  wh on wh.EmployeeID = rs.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID =rs.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo where (rs.accept = 0 or rs.accept is null) and rg_status = 0 and wh.location in (1,2)';
				}
				else
				{
					$sqlConnect='select  wh.EmployeeID,wh.EmployeeName,rs.remark,rs.nt_start,rs.rg_status ,rs.nt_end,wh.Process,wh.sub_process,wh.clientname,pd1.img from resign_details rs inner join whole_details_peremp  wh on wh.EmployeeID = rs.EmployeeID left outer join personal_details pd1 on pd1.EmployeeID =rs.EmployeeID left outer join personal_details pd on pd.EmployeeID = ReportTo where (rs.accept = 0 or rs.accept is null) and rg_status = 0 and wh.location in ("'.$_SESSION["__location"].'")';
				}
					
			    	$myDB=new MysqliDb();
					$result=$myDB->rawQuery($sqlConnect);
					//echo $sqlConnect;
					$error=$myDB->getLastError();
					$rowCount = $myDB->count;
					if($result && $rowCount>0){ ?>
						
			   		<div class="had-container pull-left row card dataTableInline "  >
						<div class=""  >																																		 								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						        <thead>
						          <tr>
						            <th><input type="checkbox" id="cbAll" name="cbAll" value="ALL"><label for="cbAll">EmployeeID</label></th>
						            <th class="hidden">EmployeeID</th>
						            <th class="hidden">EmployeeID</th>
						            <th class="">Employee Name</th>
						            <th class="">Remark </th>
						            <th class="">Notice Start</th>
						            <th class="">Notice End</th>
						            <th class="edit_info_header hidden">Request Status </th>
						            <th class="edit_info_header hidden">HR Remark</th>
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
							echo '<td class="EmpId"><input type="checkbox" id="cb'.$count.'" class="cb_child" name="cb[]" value="'.$count.'"><label for="cb'.$count.'" >'.$value['EmployeeID'].'</label></td>';
							
							echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"   class="ckeckdata" data="'.$value['EmployeeID'].'">'.$value['EmployeeID'].'</a></td>';
							echo '<td class="FullName  ">'.$value['EmployeeName'].'</td>';	
							
							echo '<td class="remark  ">'.$value['remark'].'</td>';			
							echo '<td class="nt_start  ">'.$value['nt_start'].'</td>';			
							echo '<td class="nt_end  ">'.$value['nt_end'].'</td>';					
							
							
							echo '<td class="hidden" ><input  type="input" name="txt_EmployeeID_'.$count.'" id="txt_EmployeeID_'.$count.'" readonly="true" value="'.$value['EmployeeID'].'" /></td>';
							echo '<td class="edit_info_body hidden"  ><select  title="Status" name="txt_Status_'.$count.'" id="txt_Status_'.$count.'"><option value="Accept">Accept</option>	<option value="Reject">Reject</option></select></td>';
							echo '<td class="edit_info_body hidden"  ><textarea class="materialize-textarea materialize-textarea-size" name="txt_Remark_'.$count.'" id="txt_Remark_'.$count.'"  title="Remark">NA</textarea></td>';
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
		     	alert_msg+='<li> Check Atleast On Employee </li>';
			 }
			$('input.cb_child:checkbox:checked').each(function(){
			 	if($(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val() == '' || $(this).parent('td').closest('tr').find('textarea[id^="txt_Remark_"]').val().length < 50 && $(this).parent('td').closest('tr').find('select[id^="txt_Status_"]').val() == 'Reject')
			 	{
					validate=1;
					alert_msg+='<li> Remark Can\'t be Empty or not less than 50 For any Rejected Requests...</li>';
				}
			 	
			 	
			 	
			 });
	    	 if(validate==1)
		      	{		      		
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