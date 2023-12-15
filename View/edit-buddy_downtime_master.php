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
		exit();
	}
	if($_SESSION['__user_type']!='ADMINISTRATOR')
	{
		$location= URL.'Error'; 
		header("Location: $location");
		exit();
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
	exit();
}
$alert_msg ='';
if(isset($_POST['btn_df_Save']))
{
	$_cm_id=$_POST['txt_cm_id'];
	$txt_min_time=$_POST['txt_min_time'];
	$txt_max_time=$_POST['txt_max_time'];
	
	$myDB=new MysqliDb();
	$result_check = $myDB->query('SELECT * FROM buddy_dtmatrix where cm_id = "'.$_cm_id.'"');
	if(count($result_check) > 0 && $result_check)
	{
		echo "<script>$(function(){ toastr.info('Already exists delete or edit existing entry first'); }); </script>";
	}
	else
	{
		$createBy=$_SESSION['__user_logid'];
		$Insert='INSERT INTO buddy_dtmatrix(cm_id,Min_Time,Max_Time,CreatedBy) VALUES("'.$_cm_id.'","'.$txt_min_time.'","'.$txt_max_time.'","'.$createBy.'");';
		$myDB=new MysqliDb();
		$result = $myDB->query($Insert);
		$mysql_error=$myDB->getLastError();
		if(empty($mysql_error))
		{
			echo "<script>$(function(){ toastr.success('Added Successfully'); }); </script>";
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Not Added $mysql_error'); }); </script>";	
		}
	}
	
	
	
}
if(isset($_POST['btn_df_Edit']))
{
	$_cm_id=$_POST['txt_cm_id'];
	if(empty($_POST['txtEditID']) && !is_numeric($_POST['txtEditID']))
	{
		echo "<script>$(function(){ toastr.error('No data found to update'); }); </script>";
	}
	else
	{
		$createBy=$_SESSION['__user_logid'];
		$txt_min_time=$_POST['txt_min_time'];
		$txt_max_time=$_POST['txt_max_time'];
		$Update='update buddy_dtmatrix set Min_Time = "'.$txt_min_time.'",Max_Time = "'.$txt_max_time.'" where cm_id = "'.$_cm_id.'" and id = "'.$_POST['txtEditID'].'";';
		$myDB=new MysqliDb();
		$result = $myDB->query($Update);
		$mysql_error=$myDB->getLastError();
		if(empty($mysql_error))
		{
			echo "<script>$(function(){ toastr.success('Updated Successfully'); }); </script>";
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Not Updated $mysql_error'); }); </script>";		
		}
	}
}
?>

<script>
$(document).ready(function(){
$('#myTable').DataTable({
	        dom: 'Bfrtip',
	        "iDisplayLength": 25,
	        "sScrollX" : "100%",		        
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
<span id="PageTittle_span" class="hidden">Buddy Downtime Master</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Buddy Downtime Master</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >

			  <div class="input-field col s12 m12">
					<select id="txt_cm_id" name="txt_cm_id" required>
					<option value="NA">---Select---</option>
					<?php
						$sqlBy ='select * from new_client_master inner join client_master  on new_client_master.client_name = client_master.client_id order by client_master.client_name '; 
						$myDB=new MysqliDb();
						$resultBy=$myDB->query($sqlBy);
						if(count($resultBy) > 0)
						{													
							foreach($resultBy as $key=>$value)
							{							
								echo '<option value="'.$value['cm_id'].'"  >'.$value['client_name'].' | '.$value['process'].' | '.$value['sub_process'].'</option>';
							}
						}
					?>
					</select>
					<label for="txt_cm_id" class="active-drop-down active">Process</label>
					</div>

					<div class="input-field col s6 m6">
					<select id="txt_min_time" name="txt_min_time">
					    <option>00:00:00</option>
						<option>00:30:00</option>
						<option>01:00:00</option>
						<option>01:30:00</option>
						<option>02:00:00</option>
						<option>02:30:00</option>
						<option>03:00:00</option>
						<option>03:30:00</option>
						<option>04:00:00</option>
						<option>04:30:00</option>
						<option>05:00:00</option>
						<option>05:30:00</option>
						<option>06:00:00</option>
						<option>06:30:00</option>
						<option>07:00:00</option>
						<option>07:30:00</option>
						<option>08:00:00</option>
					</select>
					<label for="txt_min_time" class="active-drop-down active">Min Time</label>
					</div>

					<div class="input-field col s6 m6">
					<select id="txt_max_time" name="txt_max_time">
					    <option>00:00:00</option>
						<option>00:30:00</option>
						<option>01:00:00</option>
						<option>01:30:00</option>
						<option>02:00:00</option>
						<option>02:30:00</option>
						<option>03:00:00</option>
						<option>03:30:00</option>
						<option>04:00:00</option>
						<option>04:30:00</option>
						<option>05:00:00</option>
						<option>05:30:00</option>
						<option>06:00:00</option>
						<option>06:30:00</option>
						<option>07:00:00</option>
						<option>07:30:00</option>
						<option>08:00:00</option>
					</select>	
					<label for="txt_max_time" class="active-drop-down active">Max Time</label>
					</div>

					<div class="input-field col s12 m12 right-align">
					    <input type="hidden" id="txtEditID" name="txtEditID" />
					    <button type="submit" name="btn_df_Save" id="btn_df_Save" class="btn waves-effect waves-green">Save</button>
					    <button type="submit" name="btn_df_Edit" id="btn_df_Edit" class="btn waves-effect waves-green hidden">Update</button>
					    <button type="button" name="btn_df_Can" id="btn_df_Can" class="btn waves-effect modal-action modal-close waves-red close-btn hidden">Cancel</button>
					</div>
			    
			    <div id="pnlTable">
			    <?php 
					
					$sqlConnect = 'SELECT * FROM buddy_dtmatrix inner join new_client_master on new_client_master.cm_id = buddy_dtmatrix.cm_id inner join client_master  on new_client_master.client_name = client_master.client_id';
					$myDB=new MysqliDb();
					$result=$myDB->query($sqlConnect);
					if(count($result) > 0){?>
						<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
						 <div class=""  >																											                                     <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						        	<th class="hidden">Process</th>
						            <th>Process</th>
						            <th>Min Time</th>						            
						            <th>Max Time</th>
						            <th>Manage</th>
						        </tr>
						    </thead>
						    <tbody>					        
					       <?php
						        foreach($result as $key=>$value)
						        {
									echo '<tr>';							
									echo '<td class="id hidden">'.$value['ID'].'</td>';	
									echo '<td class="cm_id" data="'.$value['cm_id'].'">'.$value['client_name'].' | '.$value['process'].' | '.$value['sub_process'].'</td>';
									echo '<td class="client_time_min">'.$value['Min_Time'].'</td>';	
									echo '<td class="client_time_max">'.$value['Max_Time'].'</td>';
									echo '<td>
									<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="'.$value['cm_id'].'"   data-position="left" data-tooltip="Edit">ohrm_edit</i>
									<i class="material-icons delete_item imgBtn imgBtnDelete tooltipped" onclick="javascirpt:return ApplicationDataDelete(this);" id="'.$value['ID'].'" data-position="right" data-tooltip="Delete">ohrm_delete</i>
									</td>';
									echo '</tr>';
							   }	
							?>			       
						    </tbody>
						</table>
					  </div>
					</div>
					<?php }  ?>

                </div>
    
<!--Reprot / Data Table End -->	       
	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>

<script>
$(document).ready(function(){
$("#txt_total_time").val("00:00:00");
$('#btn_df_Can').on('click', function(){
    $('#txtEditID').val('');	        
    $('#txt_cm_id').val('NA');
    $('#txt_client_training').val('NO');
    $('#txt_min_time').val('00:00:00');
    $('#txt_max_time').val('00:00:00');
    $('#txt_total_time').val('00:00:00');
    $('#txt_ojt_days').val('0');
    $('#txt_training_days').val('0');
    $("select[name^='txt_day_']").each(function()
    {
    	$(this).val("00:00:00");
    });
    $('#btn_df_Save').removeClass('hidden');
    $('#btn_df_Edit').addClass('hidden');
    $('#btn_df_Can').addClass('hidden');
});
});	
function EditData(el)
{		
var tr = $(el).closest('tr');
var id = tr.find('.id').text();
var cm_id = tr.find('.cm_id').attr('data');


$('#txtEditID').val(id);
$('#txt_cm_id').val(cm_id);
 
	       
$('#txt_min_time').val(tr.find('.client_time_min').text());
$('#txt_max_time').val(tr.find('.client_time_max').text());


$("select[name^='txt_day_']").each(function(){
var temp_id = $(this).attr('name');
temp_id = temp_id.match(/\d+/)[0]; 
$(this).val(tr.find('.ojt_day_'+temp_id).text());

});

$('#btn_df_Save').addClass('hidden');
$('#btn_df_Edit').removeClass('hidden');
$('#btn_df_Can').removeClass('hidden');
$('select').formSelect();
}
function ApplicationDataDelete(el)
{
var currentUrl = window.location.href;
var Cnfm=confirm("Do You Want To Delete This ");
if(Cnfm)
{
	var xmlhttp;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var Resp=xmlhttp.responseText;			
			if(Resp.trim() == 'done')
			{
				toastr.success('Your action to Deleting request is successful.');
				$(el).closest("tr").remove();
			}
			else
			{
				toastr.error(Resp);	
			}
			
			
			/*window.location.href = currentUrl;*/
		}
	}
	xmlhttp.open("GET", "../Controller/delete_buddy_downtime_master.php?ID=" + el.id, true);
	xmlhttp.send();
}
}

    // This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
    
	$('#btn_df_Save,#btn_df_Edit').on('click', function(){
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
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>