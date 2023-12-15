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
$ceatedBy=$_SESSION['__user_logid'];
$date=date('Y-m-d H-i-s');
//print_r($_SESSION);
$clientID=$module_name=$searchBy=$id='';
$classvarr="'.byID'";
if(isset($_GET['id']) && $_GET['id']!="")
{
	$id=$_GET['id'];
} 
if(isset($_POST['specialization'],$_POST['addmodule']) and trim($_POST['specialization'])!="")
{	
	$specialization=trim(addslashes($_POST['specialization']));
			$myDB=new MysqliDb();		
		if($specialization!=""){
			$select_query="select id from education_specilization where specilization='".$specialization."'  ";
			$resultBy=$myDB->rawQuery($select_query);
			$mysql_error = $myDB->getLastError();
			if($myDB->count > 0)
			{
				echo "<script>$(function(){ toastr.info('Duplicate entry not allowed'); }); </script>";
			}
			else  
			{
				$insertQuery="INSERT INTO  education_specilization set specilization='".$specialization."',createdby='".$ceatedBy."'";
				$resultBy=$myDB->rawQuery($insertQuery);
				echo "<script>$(function(){ toastr.success('Specialization Added Successfully'); }); </script>";
			} 
	}else{
		echo "<script>$(function(){ toastr.error('Please enter specialization'); }); </script>";
	}
}
//print_r($_POST);
if(isset($_POST['savemodule']) && $_POST['id']!="")
{  
	$specialization=trim(addslashes($_POST['specialization']));
	$id=$_POST['id'];
	$myDB=new MysqliDb();				
	if($specialization!=""){
		$updateQuery="Update  education_specilization set specilization='".$specialization."',updatedon='".$date."',updatedby='".$ceatedBy."'  where id='".$id."'";
		$resultBy=$myDB->query($updateQuery);
		echo "<script>$(function(){ toastr.success('Specialization Updated Successfully'); }); </script>";
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
<span id="PageTittle_span" class="hidden">Manage Education Specialization Master</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Specialization Master <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Specialization"><i class="material-icons">ohrm_filter</i></a> </h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	      <div id="myModal_content" class="modal">
		    <!-- Modal content-->
		    <div class="modal-content">
		      	<h4 class="col s12 m12 model-h4" >Add/Edit Specialization</h4>
		      	<div class="modal-body">	
				 	<div id="medit" >  
					     <div class="input-field col s6 m6">
				      		<input type="text" name="specialization" id="specialization" title="Specialization" value="" required >
					        <label for="specialization"> Specialization</label>
					    </div>
				    </div>  
					<input type='hidden' name='id' id='id' value=''>
					<div class="input-field col s12 m12 right-align">
					<button type="submit" name="savemodule" id="savemodule" class="btn waves-effect waves-green" style="display:none;" >Save</button>
					<button type="submit" name="addmodule" id="addmodule" class="btn waves-effect waves-green" style="display:none;" >Add</button>
					<button type="button" name="cancle" id='cancelID' class="btn waves-effect waves-green"  >Cancel</button>
					</div>
			  	</div>
			</div>
		</div>
			  	  <?php 
					$sqlConnect = "SELECT id,specilization from  education_specilization order by specilization"; 
					$myDB=new MysqliDb();
					$result=$myDB->query($sqlConnect);
					//print_r($result);
					$error=$myDB->getLastError();
					if($result){?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
					    <thead>
					        <tr>
					        	<th> Srl.No.</th>
								<th>Specilization </th>
					            <th> Edit </th>
					        </tr>
					    </thead>
					    <tbody>					        
					       <?php
					       $i=1;
					        foreach($result as $key=>$val){
					        	
					        	$ID=$val['id'];
								echo '<tr>';	
								echo '<td class="module_id" >'.$i.'</td>';						
								echo '<td class="specia_name">'.$val['specilization'].'</td>';
								
								?>
								
								<td class="manage_item">
								<a onclick="editData('<?php echo $ID; ?>','<?php echo $val['specilization']; ?>');" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a></td>
								
						<?php
							echo '</tr>';
							$i++;
							}	
							?>			       
					    </tbody>
						</table>
						<?php }
						else
						{
							echo "<script>$(function(){ toastr.error('No Data Found ".$error."'); }); </script>";
						}
						
					?>
					
				</div>
			 
			  
		</div>
	</div>

       
       
</div>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
</div>

<script>
	$(document).ready(function(){
	//Model Assigned and initiation code on document load
	$('.modal').modal({
			onOpenStart:function(elm)
			{
				
				
			},
			onCloseEnd:function(elm)
			{
				$('#btn_Department_Can').trigger("click");
			}
		});
		
		$('#ContentAdd').click(function(){
			$('#medit').show();
			$('#state').val('');
			$('select').formSelect();	
			$('#district').val('');
			$('#id').val('');
			$('#savemodule').hide();
			$('#addmodule').show();
		});
	// This code for cancel button trigger click and also for model close
     $('#cancelID').on('click', function(){
        $('#module_name').val('');
        $('#myModal_content').modal('close');
       //$('#medit').hide();
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
    
	$('#savemodule ,#addmodule').on('click', function(){
	        var validate=0;
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
				return false;
			} 
	});
	$('#medit').hide();
	});
	function editData(id,specilization){
		$('#medit').show();
		$('#specialization').val(specilization);
		$('select').formSelect();	
		$('#id').val(id);
		$('#savemodule').show();
		$('#addmodule').hide();
		
		$('#myModal_content').modal('open');
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
