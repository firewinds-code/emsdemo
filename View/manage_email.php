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
//print_r($_SESSION);
$clientID=$process=$subprocess=$bheading=$remark1=$remark2=$remark3=$email='';
$classvarr="'.byID'";
$searchBy='';
$question_num="";
$id="";
if(isset($_GET['id']) && $_GET['id']!=""){
	$id=$_GET['id'];
}
if(isset($_GET['delid']) && $_GET['delid']!="")
{
	$myDB=new MysqliDb();
	$delete_query="DELETE from add_email_address  where ID='".$_GET['delid']."'";
	$resultBy=$myDB->rawQuery($delete_query);
	
	
	$delete_query="DELETE from manage_module_email  where emailID='".$_GET['delid']."'";
	$resultBy=$myDB->rawQuery($delete_query);
	$mysql_error = $myDB->getLastError();
	if(empty($mysql_error))
	{
		echo "<script>$(function(){ toastr.error('Email Deleted Successfully'); }); </script>";
	}
}

if(isset($_POST['email_address'],$_POST['addbriefing']))
{	
    $date=date('Y-m-d');
	$createdBy=$_SESSION['__user_logid'];
	$email_address=trim($_POST['email_address']);
	$regex = '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,3})$/'; 
	if (!preg_match($regex, $email_address)) {
	$email_address="";
	} 
			$myDB=new MysqliDb();		
		if($email_address!="")
		{
			$select_query="select id from add_email_address where email_address='".$email_address."' ";
			$resultBy=$myDB->rawQuery($select_query);
			$mysql_error = $myDB->getLastError();
			if($myDB->count > 0)
			{
				echo "<script>$(function(){ toastr.info('Duplicate entry not allowed'); }); </script>";
			}
			else
			{
			 	$insertQuery="INSERT INTO add_email_address set email_address='".$email_address."',created_on='".$date."' ";
				$resultBy=$myDB->rawQuery($insertQuery);
			    $mysql_error = $myDB->getLastError();
				echo "<script>$(function(){ toastr.success('Email Added Successfully'); }); </script>";
			}	
	     }
	     else
	     {
		        echo "<script>$(function(){ toastr.error('Email Address is not true'); }); </script>";
	     }
}
//print_r($_POST);
if(isset($_POST['savebriefing']) && $_POST['id']!="")
{ // print_r($_POST);
	$email_address=$_POST['email_address'];
	$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
	if (!preg_match($regex, $email_address)) {
	$email_address="";
	} 
	$id=$_POST['id'];
	$myDB=new MysqliDb();				
	if($email_address!=""){
	     $updateQuery="Update  add_email_address set email_address='".$email_address."' where ID='".$id."'";
		$resultBy=$myDB->query($updateQuery);
		echo "<script>$(function(){ toastr.error('Email Updated Successfully'); }); </script>";
	}
	
}
?>

<script>
	$(document).ready(function(){
		$('#from_date').datetimepicker({ format:'Y-m-d H:i',step:30 });
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
				        "iDisplayLength": 25,
				        scrollX: '100%',				        
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
		   	$('.byProc').addClass('hidden');
		   	$('.byName').addClass('hidden');
		   	var classvarr=<?php echo $classvarr; ?>;
		   	$(classvarr).removeClass('hidden');
		   
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Manage Email Address</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Manage Email Address<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Email"><i class="material-icons">add</i></a></h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
			 <?php   	
			    if(isset($_GET['id']) and $_GET['id']!=""){
					$Id=$_GET['id'];
					$sqlConnect2 = 'select  * from  add_email_address  where ID="'.$Id.'" '; 
					$email_address="";
					$myDB=new MysqliDb();
					$result2=$myDB->query($sqlConnect2);
					foreach($result2 as $key=>$value){
						$email=$value['email_address'];	
					}
			}	
			?>
			 	
 	<div id="myModal_content" class="modal">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <h4 class="col s12 m12 model-h4">Manage Department</h4>
	      
	      <div class="modal-body">
		    <div class="input-field col s6 m6">
		       <input type='text' name="email_address" id="email_address"  title="email" maxlength="100" value="<?php echo $email; ?>" required >
			   <label for="email_address">Email Address</label> 
		    </div>
		   
			<input type='hidden' name="id" id="id" value="<?php echo $id; ?>" >
			 <div class="input-field col s6 m6 right-align">
			      <button type="submit" name="savebriefing"  id="savebriefing" class="btn waves-effect waves-green" style="display:none;">Save</button>
			      <button type="submit" name="addbriefing"  id="addbriefing" class="btn waves-effect waves-green">Submit</button>
			      <button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
			    </div>
			</div>
		</div>
	</div>
			
			
<!--Form element model popup End-->
<!--Reprot / Data Table start -->
	           <div id="pnlTable">
			  	  <?php 
					$sqlConnect = 'select  * from add_email_address '; 
					$myDB=new MysqliDb();
					$result=$myDB->query($sqlConnect);
					$error=$myDB->getLastError();
					if($result){?>
			   			 <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						        	<th> Srl.No.</th>
									<th> Email Address</th>
						            <th> Edit </th>
						           <th> Delete </th>		 
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       $i=1;
					        foreach($result as $key=>$val){
					        	$add_email_address=$val['email_address'];
					        	$ID=$val['ID'];
								echo '<tr>';	
								echo '<td class="client_name">'.$i.'</td>';						
								echo '<td class="email">'.$add_email_address.'</td>';
								?>
								<td class="manage_item">
								<a onclick="editData('<?php echo $ID; ?>','<?php echo $add_email_address; ?>');" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a></td>
								
								<td class="manage_item">
								<a onclick="return confirm('Do you want to detete it?');" href="manage_email.php?delid=<?php echo $ID; ?>" ><i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" data-position="left" data-tooltip="Delete">ohrm_delete</i></a></td>
								
								
								
								<!--<td class="edit"><a onclick="return confirm('Do you want to detete it?');" href="manage_email.php?delid=<?php echo $ID; ?>" ><img class="imgBtn imgBtnEdit editClass"  src="../Style/images/users_delete.png"/></a></td>-->	
								
						<?php
							echo '</tr>';
							$i++;
							}	
							?>			       
					    </tbody>
						</table>
						</div>
				<?php
				 }
					else
					{
					    echo "<script>$(function(){ toastr.error('No Data Found ".$error."'); }); </script>";
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
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>

<script>
	$(document).ready(function(){
		$('#savebriefing').hide();
	    $('#addbriefing').show();
	
	//Model Assigned and initiation code on document load
	$('.modal').modal({
			onOpenStart:function(elm)
			{
				
				
			},
			onCloseEnd:function(elm)
			{
				$('#btn_Can').trigger("click");
			}
		});
	// This code for cancel button trigger click and also for model close
     $('#btn_Can').on('click', function(){
        $('#email_address').val('');
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
    });
    
    // This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
	$('#addbriefing, #savebriefing').on('click', function(){
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
	      		//$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		//$('#alert_message').show().attr("class","SlideInRight animated");
	      		//$('#alert_message').delay(50000).fadeOut("slow");
	      		if(alert_msg!=""){
					$(function(){ toastr.error(alert_msg); });
				}
	      		
				return false;
			} 
	});
    
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
});
	
// This code for trigger edit on all data table also trigger model open on a Model ID
function editData(id,email)
{
	 $('#myModal_content').modal('open');
	$('#email_address').val(email);
	$('#id').val(id);
	$('#savebriefing').show();
	$('#addbriefing').hide();
	
   
    $("#myModal_content input,#myModal_content textarea").each(function(index, element)
    {
         if($(element).val().length > 0)
         {
           $(this).siblings('label, i').addClass('active');
         }
         else
         {
		 	$(this).siblings('label, i').removeClass('active');
		 }
		        
	});
}	
</script>
