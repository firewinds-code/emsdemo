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
$status=$batch_id=$searchBy=$msg='';
$classvarr="'.byID'";

if(isset($_POST['btnSave']))
{
	$cm_id = $_POST['ddl_cm_id'];
	$EmployeeID = $_POST['ddl_emp_id'];
	if(!empty($cm_id) && $cm_id != 'NA' && !empty($EmployeeID) && $EmployeeID !='NA')
	{
		$myDB = new MysqliDb();
		$insert = "insert into tbl_nestor (EmployeeID) values ('".$EmployeeID."')";
		$myDB->rawQuery($insert);
		$my_error = $myDB->getLastError();
		if(empty($my_error))
		{
			echo "<script>$(function(){ toastr.success('".$EmployeeID." add into nestor data...'); }); </script>";
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Request not completed ".$my_error." '); }); </script>";
		}
	}	
	else
	{
		echo "<script>$(function(){ toastr.error('Request not Updated ,No Employee Selected '); }); </script>";
	}
	
}
if(!empty($_GET['action'])){
	if($_GET['action'] == "delete" && !empty($_GET['empid']))
	{
		$EmployeeID = $_GET['empid'];
		if(!empty($EmployeeID) && $EmployeeID !='NA')
		{
			$myDB = new MysqliDb();
			$delete = "delete from tbl_nestor where EmployeeID = '".$EmployeeID."'";
			$myDB->rawQuery($delete);
			$my_error = $myDB->getLastError();
			if(empty($my_error))
			{
				echo "<script>$(function(){ toastr.success('Employee Deleted from nestor'); }); </script>";
			}
			else
			{
				echo "<script>$(function(){ toastr.error('Request not completed ".$my_error." '); }); </script>";
			}	
		}	
		else
		{
			echo "<script>$(function(){ toastr.error('Request Not Updated ,No Employee Selected '); }); </script>";
		}
	}
}

?>

<script>
$(document).ready(function(){
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
"sScrollY" : "192",
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
<span id="PageTittle_span" class="hidden">Nestor Manage</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Nestor Manage</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	
		
			 	<div class="input-field col s5 m5">
					<select name="ddl_cm_id" id="ddl_cm_id">
					<option value="NA">---Select---</option>
					<?php
					$sqlBy = 'select distinct cm_id,Process,sub_process,clientname from whole_details_peremp  where des_id in (9,12) order by Process desc';
					$myDB=new MysqliDb();
					$resultBy=$myDB->query($sqlBy);
					if($resultBy){													
					foreach($resultBy as $key=>$value)
						{
						if($_GET['pid'] == $value['cm_id'])
							{
								if($value['Process'] == $value['sub_process'])
								{
									echo '<option value="'.$value['cm_id'].'"  selected>'.$value['clientname'].' | '.$value['sub_process'].'</option>';
								}
								else
								{
									echo '<option value="'.$value['cm_id'].'"  selected>'.$value['clientname'].' | '.$value['Process'].' | '.$value['sub_process'].'</option>';
								}	
							}
						else
							{
								if($value['Process'] == $value['sub_process'])
								{
									echo '<option value="'.$value['cm_id'].'"  >'.$value['clientname'].' | '.$value['sub_process'].'</option>';
								}
								else
								{
									echo '<option value="'.$value['cm_id'].'"  >'.$value['clientname'].' | '.$value['Process'].' | '.$value['sub_process'].'</option>';
								}	
							}	
						}
					}
					?>
					</select>
			      	<label for="ddl_cm_id" class="active-drop-down active">Process</label>
			    </div> 
			    <div class="input-field col s5 m5">
			       <select name="ddl_emp_id" id="ddl_emp_id">
			       	<option value="NA">---Select---</option>
			       <?php
					$sqlBy = 'select distinct EmployeeID,EmployeeName from whole_details_peremp  where des_id in (9,12) and cm_id ="'.$_GET['pid'].'"  order by EmployeeName';
					$myDB=new MysqliDb();
					$resultBy=$myDB->query($sqlBy);
					if($resultBy)
						{													
							foreach($resultBy as $key=>$value){		
								echo '<option value="'.$value['EmployeeID'].'"  >'.$value['EmployeeName'].'  [ '.$value['EmployeeID'].' ] </option>';
							}
						}
					?>
			       </select>
			       <label for="ddl_emp_id" class="active-drop-down active">Employee</label>
			    </div> 
			 	<div class="input-field col s2 m2">
			       <input type="submit" value="Add to Nestor " name="btnSave" id="btnSave" class="btn waves-effect waves-green"/>
			    </div> 
<?php if(!empty($_GET['pid'])){ ?>
				<div id="pnlTable">
			    <?php 
			    	$myDB=new MysqliDb();
					$chk_task=$myDB->query('select * from whole_details_peremp inner join tbl_nestor on tbl_nestor.EmployeeID = whole_details_peremp.EmployeeID where des_id in (9,12) and  cm_id ='.$_GET['pid']); 
					//echo $sqlConnect;
					$error=$myDB->getLastError();
					if($chk_task){?>
			   			<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
						  <div class=""  >																											                                     <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						        <thead>
						          <tr>
						            <th>EmployeeID</th>
									<th>EmployeeName</th>
									<th>Designation</th>
									<th>Dept Name</th>
									<th>DOJ</th>
									<th>Client</th>
									<th>Process</th>
									<th>Sub Process</th>
									<th>Delete</th>
						         </tr>
						        </thead>
							    <tbody>					        
							       <?php
							       $count=0;
							        foreach($chk_task as $key=>$value)
							        {
							        	$count++;
										echo '<tr>';	
										echo '<td class="client_name edit_view">'.$value['EmployeeID'].'</td>';					
										echo '<td class="client_name edit_view">'.$value['EmployeeName'].'</td>';					
										echo '<td class="client_name edit_view">'.$value['designation'].'</td>';					
										echo '<td class="process edit_view">'.$value['dept_name'].'</td>';					
										echo '<td class="sub_process edit_view">'.$value['DOJ'].'</td>';
										echo '<td class="client_name edit_view">'.$value['clientname'].'</td>';					
										echo '<td class="process edit_view">'.$value['Process'].'</td>';					
										echo '<td class="sub_process edit_view">'.$value['sub_process'].'</td>';	
										echo '<td><a href="#" data-ID="'.$value['EmployeeID'].'" class="a__ID" onclick="javascirpt:return ApplicationDataDelete('."'".$value['EmployeeID']."'".');"><i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" data-position="left" data-tooltip="Delete">ohrm_delete</i></a></td>';					
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
							echo "<script>$(function(){ toastr.success('Congratulations, all employees have been aligned to concern departments ".$error." '); }); </script>";
						} 
				?>
				<!--Reprot / Data Table End -->	
				</div>
<?php } ?>				
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>							
			
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>


<script>
	$(document).ready(function(){
		$('#ddl_cm_id').change(function(){
			window.location = "nestor_Details.php?pid="+$('#ddl_cm_id').val();	
		});	
	});
	function ApplicationDataDelete(el)
	{
		window.location = "nestor_Details.php?pid="+$('#ddl_cm_id').val()+"&empid="+el+"&action=delete";
	}
	// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
$('#btn_Client_Edit,#btn_Client_Save').on('click', function(){
        var validate=0;
        var alert_msg='';
        // <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
        $("input,select,textarea").each(function(){
        	var spanID =  "span" + $(this).attr('id');		        	
        	$(this).removeClass('has-error');
        	if($(this).is('select'))
			{
				$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
			}
        	var attr_req = $(this).attr('required');
        	if(($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown'))
        	{
				validate=1;	
				$(this).addClass('has-error');
				if($(this).is('select'))
				{
					$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
				}
				if ($('#'+spanID).size() == 0) {
			            $('<span id="'+spanID+'" class="help-block"></span>').insertAfter('#'+$(this).attr('id'));
			        }
			    var attr_error = $(this).attr('data-error-msg');
			    if(!(typeof attr_error !== typeof undefined && attr_error !== false))
			    {
					$('#'+spanID).html('Required *');	
				}
				else
				{
					$('#'+spanID).html($(this).attr("data-error-msg"));
				}

			    
			    
			}
        })
        		    
      	if(validate==1)
      	{		      		
      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
      		$('#alert_message').show().attr("class","SlideInRight animated");
      		$('#alert_message').delay(50000).fadeOut("slow");
			return false;
		}
       
    });
  	
	
</script>
