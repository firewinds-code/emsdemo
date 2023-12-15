<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
// Only for user type administrator
if($_SESSION['__user_type']=='ADMINISTRATOR' || $_SESSION['__user_logid']=='CE10091236' || $_SESSION['__user_logid']=='CE12102224')
{
// proceed further
}
else
{
		$location= URL.'Error'; 
	header("Location: $location");
	exit();
	
}
// Global variable used in Page Cycle
$last_to=$last_from=$last_to=$dept=$emp_nam=$status='';
$classvarr="'.byID'";
$searchBy='';
$msg='';
// Trigger Button-Edit Click Event and Perform DB Action
if(isset($_POST['btnSave']))
{
	
	$createBy=$_SESSION['__user_logid'];
	$QualityID=trim($_POST['QualityID']);
	$TrainingID=trim($_POST['TrainingID']);
	$OpsID=trim($_POST['OpsID']);
	$ID=$_POST['ID'];
	$HRID=trim($_POST['HRID']);
	$ITID=trim($_POST['ITID']);
	$ReportsTo=trim($_POST['ReportsTo']);
	if($QualityID!="" && $TrainingID!="" && $OpsID!="" && $HRID!="" && $ITID!="" && $ReportsTo !="")
	{
		//$empID=$val;
		$myDB=new MysqliDb();
		//$save='call manage_status_reporting("'.$empID.'","'.$status.'")';	
		$update="UPDATE downtimereqid1 set QualityID='".$QualityID."' , TrainingID='".$TrainingID."' , OpsID='".$OpsID."' , HRID='".$HRID."' , ITID='".$ITID."',ReportsTo='".$ReportsTo."',updated_on=now()  where ID='".$ID."'";
		$myDB->rawQuery($update);
		$mysql_error = $myDB->getLastError();
		$rowCount = $myDB->count;
		if(empty($mysql_error))
		{
			echo "<script>$(function(){ toastr.success('Data Updated Successfully'); }); </script>";
			
		}
		else
		{
			echo "<script>$(function(){ toastr.success('Data Not Updated ::Error :- <code>'.$mysql_error.'</code>'); }); </script>";
		}

	}
}
?>
<script>
	$(document).ready(function(){
		$('#txt_ED_joindate_to').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#txt_ED_joindate_from').datetimepicker({ format:'Y-m-d', timepicker:false});
			$('#myTable').DataTable({
			dom: 'Bfrtip',	
			scrollX: '100%',		        
			scrollCollapse: true,
			"iDisplayLength": 25,
			lengthMenu: [
			    [ 5,10, 25, 50, -1 ],
			    ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
			],
			 buttons: [
			          
			      /*  {
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
			       /* ,'copy'*/
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
<span id="PageTittle_span" class="hidden">Manage Down Time Details</span>

<!-- Main Div for all Page -->
  <div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
    <div class="form-div">

<!-- Header for Form If any -->
	 <h4>Manage Down Time Details</h4>				

<!-- Form container if any -->
	  <div class="schema-form-section row" >
<!--Form element model popup start-->
        <div id="myModal_content" class="modal">
		 <!-- Modal content-->
		    <div class="modal-content">
		      <h4 class="col s12 m12 model-h4">Manage Down Time</h4>
		      <div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
							<div class="input-field col s6 m6" >
						      <input type="text" readonly="true" id="ProcessEdit" name="Process" value="<?php echo 'Process';?>" required/> 
						      <label for="txt_Comment">Process</label>
						    </div>
						    <div class="input-field col s6 m6">
						      <input type="text" readonly="true" id="SubProcessEdit" name="SubProcess" value="<?php echo 'Sub Process';?>" required/>
						      <label for="txt_Comment">Sub Process</label>
						    </div>
						    <div class="input-field col s6 m6">
						       <input type="text" id="QualityIDedit" name="QualityID" required/>
						      <label for="txt_Request">Quality ID</label>
						    </div>
						    <div class="input-field col s6 m6">
						      <input type="text" id="TrainingIDedit" name="TrainingID" required/>
						      <label for="txt_DateFrom">Training ID</label>
						    </div>
						    <div class="input-field col s6 m6">
						      <input type="text" id="OpsIDedit" name="OpsID" required/>
						      <label for="txt_DateTo">Ops ID</label>
						    </div>
						    <div class="input-field col s6 m6">
						      	 <input type="text" id="HRIDedit" name="HRID" required>
						    	 <label for="txt_Comment">HR ID</label>
						    </div>
							 <div class="input-field col s6 m6">
						      	 <input type="text" id="ITIDedit" name="ITID" required>
						    	 <label for="txt_Comment">IT ID</label>
						    </div>
						    <div class="input-field col s6 m6">
						      	 <input type="text" id="ReportsToedit" name="ReportsTo" required> 
						      	 <label for="txt_Comment">Reporting To</label>
						      	 <input type="hidden" id="hiddenid" name="ID"  >
						    </div>
							<div class="input-field col s12 m12 right-align">
					      <input  type="submit" value="Update" name="btnSave" id="btnSave1" class="btn waves-effect waves-green"/>
					      <input  type="button" value="Cancel" name="btnCan" id="btnCancel" class="btn waves-effect modal-action modal-close waves-red close-btn"/>
				   </div>
		         </div> 
		           
				   
				   	
		       </div>
            </div>
       
<!--Form element model popup End-->
<!--Reprot / Data Table start -->
		  	 <div id="pnlTable">
			    <?php 
			    	$sqlConnect='call get_downtime()';
			    	$myDB=new MysqliDb();
					$result=$myDB->rawQuery($sqlConnect);
					$error = $myDB->getLastError();
					if(empty($mysql_error)){?>
						
			   			 <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						       		<th>Srl. No. </th> 
						            <th><label for="cbAll">Process</label></th>
						           
						            <th>Sub Process</th>
						            <th>Quality ID</th>
						            <th>Training ID</th>
						            <th>Ops ID</th>
						            <th>HR ID </th>   
						            <th>IT ID </th> 
						            <th>Reporting To  </th> 
						            <th>Action  </th> 
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       $count=0;
					        foreach($result as $key=>$value){
					        	$count++;
					        	$id=$value['ID'];
								echo '<tr>';	
								echo '<td id="countc'.$id.'">'.$count.'</td>';						
								echo '<td class="Process" id="Process'.$id.'">'.$value['Process'].'</td>';			
								echo '<td class="SubProcess"  id="SubProcess'.$id.'" >'.$value['SubProcess'].'</td>';					
								echo '<td class="QualityID" id="QualityID'.$id.'"  >'.$value['QualityID'].'</td>';					
								echo '<td class="TrainingID" id="TrainingID'.$id.'">'.$value['TrainingID'].'</td>';					
								echo '<td class="OpsID" id="OpsID'.$id.'">'.$value['OpsID'].'</td>';					
								echo '<td class="HRID" id="HRID'.$id.'">'.$value['HRID'].'</td>';	
								echo '<td class="ITID" id="ITID'.$id.'">'.$value['ITID'].'</td>';
								echo '<td class="ReportsTo" id="ReportsTo'.$id.'">'.$value['ReportsTo'].'</td>';
								
								?>	
								<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="return getEditData('<?php echo $id; ?>');" data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>
						<?php	
							echo '</tr>';
							}	
							?>			       
					    </tbody>
						</table>
						 
						<?php
						}
						else
						{
						 echo '<div id="div_error" class="slideInDown animated hidden">Data Not Found (May be You Not Have Any Employee Assigned ):: <code >'.$error.'</code> </div>';
						} 
					
			  	
				?>	
			 </div>
	  
	   </div>
	  </div>
			
     </div>
   </div>

<script>
$(document).ready(function(){
	
//Model Assigned and initiation code on document load	
$('.modal').modal(
{
	onOpenStart:function(elm)
	{
		
	},
	onCloseEnd:function(elm)
	{
		$('#btn_Client_Can').trigger("click");
	}
});
		
// This code for remove error span from all element contain .has-error class on listed events
$(document).on("click blur focus change",".has-error",function(){
	$(".has-error").each(function(){
		if($(this).hasClass("has-error"))
		{
			$(this).removeClass("has-error");
			$(this).next("span.help-block").remove();
			if($(this).is('select'))
			{
				$(this).parent('.select-wrapper').find("span.help-block").remove();
			}
			if($(this).hasClass('select-dropdown'))
			{
				$(this).parent('.select-wrapper').find("span.help-block").remove();
			}
		}
	});
});


$('#btnCancel').click(function(){
	
        // This code for remove error span from input text on model close and cancel
        $(".has-error").each(function(){
			if($(this).hasClass("has-error"))
			{
				$(this).removeClass("has-error");
				$(this).next("span.help-block").remove();
				if($(this).is('select'))
				{
					$(this).parent('.select-wrapper').find("span.help-block").remove();
				}
				if($(this).hasClass('select-dropdown'))
				{
					$(this).parent('.select-wrapper').find("span.help-block").remove();
				}
				
			}
		});
		// This code active label on value assign when any event trigger and value assign by javascript code.
		
        $("#myModal_content input,#myModal_content textarea").each(function(index, element) {
        	
	         if($(element).val().length > 0) {
	           $(this).siblings('label, i').addClass('active');
	         }
	         else
	         {
			 	$(this).siblings('label, i').removeClass('active');
			 }
			        
		});
		$('select').formSelect(); 
});


// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
    
	$('#btnSave1').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			if ($('#QualityIDedit').val() == '') {
				$('#QualityIDedit').addClass("has-error");
				if ($('#spanQualityIDedit').size() == 0) {
					$('<span id="spanQualityIDedit" class="help-block">Required*</span>').insertAfter('#QualityIDedit');
				}
				validate = 1;
			}
			if ($('#TrainingIDedit').val() == '') {
				$('#TrainingIDedit').addClass("has-error");
				if ($('#spanTrainingIDedit').size() == 0) {
					$('<span id="spanTrainingIDedit" class="help-block">Required*</span>').insertAfter('#TrainingIDedit');
				}
				validate = 1;
			}
			if ($('#HRIDedit').val() == '') {
				$('#HRIDedit').addClass("has-error");
				if ($('#spanHRIDedit').size() == 0) {
					$('<span id="spanHRIDedit" class="help-block">Required*</span>').insertAfter('#HRIDedit');
				}
				validate = 1;
			}
			if ($('#OpsIDedit').val() == '') {
				$('#OpsIDedit').addClass("has-error");
				if ($('#spanOpsIDedit').size() == 0) {
					$('<span id="spanOpsIDedit" class="help-block">Required*</span>').insertAfter('#OpsIDedit');
				}
				validate = 1;
			}
			if ($('#ITIDedit').val() == '') {
				$('#ITIDedit').addClass("has-error");
				if ($('#spanITIDedit').size() == 0) {
					$('<span id="spanITIDedit" class="help-block">Required*</span>').insertAfter('#ITIDedit');
				}
				validate = 1;
			}
			if ($('#ReportsToedit').val() == '') {
				$('#ReportsToedit').addClass("has-error");
				if ($('#spanReportsToedit').size() == 0) {
					$('<span id="spanReportsToedit" class="help-block">Required*</span>').insertAfter('#ReportsToedit');
				}
				validate = 1;
			}
			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
				$('#alert_message').show().attr("class","SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");*/
				alert_msg = 'Please fill all required field';

				$(function() {
					toastr.error(alert_msg);
				});
				//alert('1');
				return false;
			}
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			// $("input,select,textarea").each(function(){
			// var spanID =  "span" + $(this).attr('id');		        	
			// $(this).removeClass('has-error');
			// if($(this).is('select'))
			// 	{
			// 		$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
			// 	}
			// var attr_req = $(this).attr('required');
			// if(($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown'))
			// {
			// 		validate=1;	
			// 		$(this).addClass('has-error');
			// 		if($(this).is('select'))
			// 		{
			// 			$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
			// 		}
			// 		if ($('#'+spanID).size() == 0) {
			// 	            $('<span id="'+spanID+'" class="help-block"></span>').insertAfter('#'+$(this).attr('id'));
			// 	        }
			// 	    var attr_error = $(this).attr('data-error-msg');
			// 	    if(!(typeof attr_error !== typeof undefined && attr_error !== false))
			// 	    {
			// 			$('#'+spanID).html('Required *');	
			// 		}
			// 		else
			// 		{
			// 			$('#'+spanID).html($(this).attr("data-error-msg"));
			// 		}
			// 	}
			// })

			// if(validate==1)
			// {		      		
			// 	$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
			// 	$('#alert_message').show().attr("class","SlideInRight animated");
			// 	$('#alert_message').delay(50000).fadeOut("slow");
			// 	return false;
			// }

		});
			
});
function checklistdata(){
		$('.statuscheck').removeClass('hidden');		
}
function getEditData(id){
	var Process= $('#Process'+id).html();
	var SubProcess= $('#SubProcess'+id).html();
	var QualityID= $('#QualityID'+id).html();
	var TrainingID= $('#TrainingID'+id).html();
	var OpsID= $('#OpsID'+id).html();
	var HRID= $('#HRID'+id).html();
	var ITID= $('#ITID'+id).html();
	var ReportsTo= $('#ReportsTo'+id).html();
	
	$('#ProcessEdit').val(Process);
	$('#SubProcessEdit').val(SubProcess);
	$('#QualityIDedit').val(QualityID);
	$('#TrainingIDedit').val(TrainingID);
	$('#OpsIDedit').val(OpsID);
	$('#HRIDedit').val(HRID);
	$('#ITIDedit').val(ITID);
	$('#ReportsToedit').val(ReportsTo);
	$('#hiddenid').val(id);
	$('#editdataid').show();
	
	$('#myModal_content').modal('open');
        $("#myModal_content input,#myModal_content textarea").each(function(index, element) {
        	
	         if($(element).val().length > 0) {
	           $(this).siblings('label, i').addClass('active');
	         }
	         else
	         {
			 	$(this).siblings('label, i').removeClass('active');
			 }
			        
		});
		$('select').formSelect(); 
}		
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>