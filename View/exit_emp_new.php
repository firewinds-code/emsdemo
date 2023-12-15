<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
{
	$EmpName=(isset($_POST['txt_exit_empname'])? $_POST['txt_exit_empname'] : null);
	$dol=(isset($_POST['txt_exit_dtLeave'])? $_POST['txt_exit_dtLeave'] : null);
	$rsnofleaving = (isset($_POST['txt_exit_rsnleave'])? $_POST['txt_exit_rsnleave'] : null);
	$hrcmt = (isset($_POST['txt_exit_hrcmt'])? $_POST['txt_exit_hrcmt'] : null);
	$optcmt =(isset($_POST['txt_exit_opcmt'])? $_POST['txt_exit_opcmt'] : null);
	if(strlen($rsnofleaving)<50){
		$alert_msg='<span class="text-success"><b>Message :</b> Reason For Leave should be 50 characters </span>';	
		exit;
		break;
	}
}
else
{
	$EmpName=$dol=$rsnofleaving=$hrcmt=$optcmt='';	
}


$alert_msg=$EmployeeID=$showbutton='';
$disposition='NA';
if(isset($_POST['btn_Exit_Edit']))
{
	$_empid=(isset($_POST['txt_exit_empid'])? $_POST['txt_exit_empid'] : null);
	$_rsnleave=(isset($_POST['txt_exit_rsnleave'])? $_POST['txt_exit_rsnleave'] : null);
	$_dol=(isset($_POST['txt_exit_dtLeave'])? $_POST['txt_exit_dtLeave'] : null);
	$_hrcmt=(isset($_POST['txt_exit_hrcmt'])? $_POST['txt_exit_hrcmt'] : null);
	$_opscmt=(isset($_POST['txt_exit_opcmt'])? $_POST['txt_exit_opcmt'] : null);
	$_disposition=$_POST['txt_disposition'];
	if(strlen($_rsnleave)<50)
	{
		echo "<script>$(function(){ toastr.info('Reason For Leave should be 50 characters'); }); </script>";	
	}else
	{
	$createBy=$_SESSION['__user_logid'];
	$Insert='INSERT INTO exit_emp(EmployeeID,dol,rsnofleaving,hrcmt,optcmt,createdby,disposition)VALUES("'.$_empid.'","'.$_dol.'","'.$_rsnleave.'","'.$_hrcmt.'","'.$_opscmt.'","'.$createBy.'","'.$_disposition.'")';
	$myDB=new MysqliDb();
	$result = $myDB->query($Insert);
	$mysql_error=$myDB->getLastError();
	$rowCount= $myDB->count;
	$mailler_msg='';
	if(empty($mysql_error))
	{
			$myDB=new MysqliDb();
			$result1 = $myDB->query("UPDATE employee_map SET emp_status='InActive' WHERE EmployeeID = '".$_empid."'");
			$mysql_error1 = $myDB->getLastError();
			if(empty($mysql_error1))
			{
				$EmployeeID=$_empid;
			
				if(substr($_empid,0,2)=='CE')
				{
					
						$myDB=new MysqliDb();
						$pagename='DeleteEmail';
						$select_email_array=$myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."'");	
						$myDB=new MysqliDb();
						$getDetails=$myDB->query("select a.EmployeeName,a.Process,a.sub_process,a.clientname,a.designation,a.dept_name,b.ofc_emailid from whole_dump_emp_data a INNER Join contact_details b on a.EmployeeID=b.EmployeeID  where a.EmployeeID='".$EmployeeID."'");
						if(count($getDetails)>0)
						{
							$mail = new PHPMailer;
							$mail->isSMTP();
							$mail->Host = 'mail.cogenteservices.in';  // Specify main and backup SMTP servers
							$mail->SMTPAuth = true;                               // Enable SMTP authentication
							$mail->Username = 'ems@cogenteservices.in';                 // SMTP username
							$mail->Password = '987654321';                           // SMTP password
							$mail->SMTPSecure = 'TLS';
							$mail->Port = 25;
							$mail->setFrom('ems@cogenteservices.in', 'EMS:Cogent Grievance System');
							//$mail->AddAddress('rinku.kumari@cogenteservices.in');
							if(count($select_email_array) > 0 && $select_email_array)
							{
								foreach($select_email_array as $Key=>$val)
					        	{
					        		$email_address = $val['email_address'];
									
									if($email_address!=""){
										$mail->AddAddress($email_address);
									}
									$cc_email=$val['ccemail'];
									
									if($cc_email!=""){
										$mail->addCC($cc_email);
									}			
								}
													
							}		
						
							$mail->Subject = 'Email ID Deletion Request['.date('d M,Y',time()).']';
							$mail->isHTML(true);	
							
					        $Body ="Hello sir,<br>Please remove / delete bellow listed Employee ID(s) <br><br>
					        <table border='1'>";
					        $Body .="<tr><td><b>Employee ID</b></td><td><b>Employee Name</b></td><td><b>Official EmailID</b></td><td><b>Client</b></td><td><b>Process</b></td><td><b>Sub-process</b></td><td><b>Designation</b></td><td><b>Department</b></td></tr>";
					       $Body.="<tr><td>".$EmployeeID."</td><td>".$getDetails[0]['EmployeeName']."</td><td>".$getDetails[0]['ofc_emailid']."</td><td>".$getDetails[0]['clientname']."</td><td>".$getDetails[0]['Process']."</td><td>".$getDetails[0]['sub_process']."</td><td>".$getDetails[0]['designation']."</td><td>".$getDetails[0]['dept_name']."</td></tr>";
					       
					     	$Body .="</table><br><br>Thanks EMS Team";
							$mail->Body = $Body;
						
							if(!$mail->send())
						 	{
						 		$mailler_msg= 'Mailer Error:'. $mail->ErrorInfo;
						  	} 
							else
							 {
							    
							   $mailler_msg=   'and Email Id(s) deletion request raised.';
							  
							 }
						}
				}
				echo "<script>$(function(){ toastr.success('Employee InActive Successfully ".$mailler_msg."'); }); </script>";
				$showbutton =' hidden';
			}
			else
			{
				echo "<script>$(function(){ toastr.error('Record not updated or Employee already InActive.'); }); </script>";
			}
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Record not updated ".$mysql_error." '); }); </script>";		
	}
	}
}
if(isset($_REQUEST['empid'])&& $EmployeeID=='')
{
	$EmployeeID=$_REQUEST['empid'];
	$getDetails='call get_exitemp("'.$EmployeeID.'")';
	$myDB=new MysqliDb();
	$result_all=$myDB->query($getDetails);
	foreach($result_all as $key=>$value)
	{
		$EmpName=$value['EmployeeName'];
		$dol=$value['dol'];
		$rsnofleaving = $value['rsnofleaving'];
		$hrcmt = $value['hrcmt'];
		$optcmt =$value['optcmt'];
		$disposition =$value['disposition'];
	 }
	
	$getDetails='call get_personal("'.$EmployeeID.'")';
	$myDB=new MysqliDb();
	$result_all=$myDB->query($getDetails);
	if($result_all)
	{
		
		
	}else
	{
	   echo "<script>$(function(){ toastr.error('Wrong Employee To Search.') }); window.location='".URL."'</script>";
	}
	
}
elseif(isset($_POST['EmployeeID']) && $_POST['EmployeeID']!='')
{
	$EmployeeID=$_POST['EmployeeID'];
}

?>

<script>
	$(document).ready(function(){
		var usrtype=<?php echo "'".$_SESSION["__user_type"]."'"; ?>;
		var usrtype_tmp =<?php echo "'".$_SESSION["__ut_temp_check"]."'"; ?>;
		if((usrtype === 'ADMINISTRATOR' && usrtype_tmp == 'ADMINISTRATOR') || (usrtype === 'CENTRAL MIS' && usrtype_tmp == 'COMPLIANCE'))
		{
			
		}
		else
		{
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled','true');
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
		}
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
				        scrollY: 195,				        
				        scrollCollapse: true,
				        lengthMenu: [
				            [ 5,10, 25, 50, -1 ],
				            ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
				        ],
				         buttons: [
						          
						        {
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
						        'print',
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
						        },'copy','pageLength'
						        
						    ]
				       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
				    });
		   	
		   	$('.buttons-copy').attr('id','buttons_copy');
		   	$('.buttons-csv').attr('id','buttons_csv');
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-pdf').attr('id','buttons_pdf');
		   	$('.buttons-print').attr('id','buttons_print');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
		   	$('#txt_exit_dtLeave').datetimepicker({ format:'Y-m-d', timepicker:false});
	});
</script>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Employee Exit</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Employee Exit</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	    <?php 
			if($EmployeeID==''&&empty($EmployeeID))
			{
				echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
				exit();
			}
		?>
		<div class="fixed-action-btn " style="top: 119px;right: 35px;height: 100px;">
		<a class="btn-floating btn-large">
		<i class="large material-icons">menu</i>
		</a>
		<ul style="width: 1000px;height: 100px;top:50px;">
		<li><a href="<?php echo URL.'View/wt_doc?empid='.$EmployeeID;?>" class="btn-floating blue darken-1 tooltipped" 
  			data-position="bottom" data-tooltip="Warning Docs"><i class="material-icons">Warning Docs</i></a></li>
		  <li><a href="<?php echo URL.'View/empsave?empid='.$EmployeeID;?>" class="btn-floating green darken-1 tooltipped" data-position="bottom" data-tooltip="Profile Details"><i class="material-icons">account_box</i></a></li>
		  <li><a href="<?php echo URL.'View/education?empid='.$EmployeeID;?>" class="btn-floating red darken-1 tooltipped" data-position="bottom" data-tooltip="Education Details"><i class="material-icons">school</i></a></li>
		  <li><a href="<?php echo URL.'View/experience?empid='.$EmployeeID;?>" class="btn-floating grey darken-1 tooltipped" data-position="bottom" data-tooltip="Experience Details"><i class="material-icons">work</i></a></li>
		  <li><a href="<?php echo URL.'View/contact?empid='.$EmployeeID;?>" class="btn-floating blue lighten-1 tooltipped" data-position="bottom" data-tooltip="Contact Details"><i class="material-icons">contact_phone</i></a></li>
		  <li><a href="<?php echo URL.'View/address?empid='.$EmployeeID;?>" class="btn-floating yellow darken-1 tooltipped" data-position="bottom" data-tooltip="Address Details"><i class="material-icons">location_on</i></a></li>
		  <li><a href="<?php echo URL.'View/bankdetails?empid='.$EmployeeID;?>" class="btn-floating red lighten-1 tooltipped" data-position="bottom" data-tooltip="Bank Details"><i class="material-icons">account_balance</i></a></li>
		  <li><a href="<?php echo URL.'View/salemp?empid='.$EmployeeID;?>" class="btn-floating green lighten-1 tooltipped" data-position="bottom" data-tooltip="Payroll Details"><i class="material-icons">payment</i></a></li>
		  <li><a href="<?php echo URL.'View/mapemp?empid='.$EmployeeID;?>" class="btn-floating blue darken-2 tooltipped" data-position="bottom" data-tooltip="Map Employee Details"><i class="material-icons">streetview</i></a></li>
		  <li><a href="<?php echo URL.'View/exitemp?empid='.$EmployeeID;?>" class="btn-floating red darken-3 tooltipped" data-position="bottom" data-tooltip="Exit Employee Manage"><i class="material-icons">exit_to_app</i></a></li>
		  <script>
		  	$(document).ready(function(){
			    $('.fixed-action-btn').floatingActionButton({
				    direction: 'left',
				    hoverEnabled: true
				  });
				  $(".tooltipped").tooltip();  
			});
		  </script>
		</ul>
		</div>


		<div class="input-field col s6 m6">
		<input class="" id="txt_exit_empname" name="txt_exit_empname" readonly="true" value="<?php echo $EmpName;?>"/>
		</div>

		<div class="input-field col s6 m6">
		<div><input class="" id="txt_exit_empid" name="txt_exit_empid" readonly="true" value="<?php echo $EmployeeID;?>"/></div> 
		</div>

		<div class="input-field col s6 m6">

		<?php 
		if($dol !=''&&$dol!=null)
		{
				$dol = explode(' ',$dol);
				$dol = $dol[0];	
			}
		?>
			<input type="text" id="txt_exit_dtLeave"  value="<?php echo $dol;?>" name="txt_exit_dtLeave" required/>				                                      
			<label for="txt_exit_dtLeave">Date of leaving</label>
		</div>


		<div class="input-field col s6 m6">
			<select id="txt_disposition" name="txt_disposition" required>
				<option value='NA' <?php if($disposition=='NA' || $disposition==''){ echo "selected";} ?> >Select</option>
				<option value='RES' <?php if($disposition=='RES'){ echo "selected";} ?> >RES</option>
				<option value='ABSC' <?php if($disposition=='ABSC'){ echo "selected";} ?> >ABSC</option>
				<option value='IR'  <?php if($disposition=='IR'){ echo "selected";} ?>>IR</option>
				<option value='TER'  <?php if($disposition=='TER'){ echo "selected";} ?> >TER</option>
				<option value='DCR'  <?php if($disposition=='DCR'){ echo "selected";} ?>>DCR</option>
				<option value='TRFR'  <?php if($disposition=='TRFR'){ echo "selected";} ?> >TRFR</option>
			</select> 
			<label for="txt_disposition" class="active-drop-down active">Disposition</label>
		</div>
		
		<div class="input-field col s12 m12" >
		    <textarea id="txt_exit_rsnleave" name="txt_exit_rsnleave" class="materialize-textarea" required><?php echo $rsnofleaving;?></textarea>				            
		    <label for="txt_exit_rsnleave">Reason of leaving</label>
		</div>

		<div class="input-field col s12 m12">
			<textarea  id="txt_exit_hrcmt" name="txt_exit_hrcmt" class="materialize-textarea" required><?php echo $hrcmt;?></textarea>
			<label for="txt_exit_hrcmt">HR Comments</label>
		</div>

		<div class="input-field col s12 m12">
			<textarea  id="txt_exit_opcmt" name="txt_exit_opcmt" class="materialize-textarea" required><?php echo $optcmt;?></textarea>
			<label for="txt_exit_opcmt">OPS Comments</label>
		</div>


<div class="input-field col s12 m12 right-align">
	<button type="submit" name="btn_Exit_Edit" id="btn_Exit_Edit" class="btn waves-effect waves-green <?php echo $showbutton;?>"> Save </button>
	<button type="button" name="btn_Exit_Can" id="btn_Exit_Can" class="btn waves-effect modal-action modal-close waves-red close-btn hidden">Cancle</button>
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
		}
		else
		{
			$('#alert_message').delay(5000).fadeOut("slow");
		}
		 
		     $('#btn_Exit_Can').on('click', function(){
		        
		        $('#txt_exit_empname').val('');
		        $('#txt_exit_empid').val('');
		        $('#txt_exit_dtLeave').val('');		        
		        $('#txt_exit_rsnleave').val('');
		        $('#txt_exit_hrcmt').val('');
		        $('#txt_exit_opcmt').val('');
		        
		        $('#btn_df_Save').removeClass('hidden');
		        $('#btn_Exit_Edit').addClass('hidden');
		        $('#btn_Exit_Can').addClass('hidden');
		           
		    });
		     
// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
    
	$('#btn_Exit_Edit,#btn_df_Save').on('click', function(){
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


	});
	function EditData(el)
	{		
		        var tr = $(el).closest('tr');
		        var EmployeeID = tr.find('.EmployeeID').text();
		        var EmployeeName = tr.find('.EmployeeName').text();
		        
		       $('#txt_exit_empname').val('');
		        $('#txt_exit_empid').val('');
		        $('#txt_exit_dtLeave').val('');		        
		        $('#txt_exit_rsnleave').val('');
		        $('#txt_exit_hrcmt').val('');
		        $('#txt_exit_opcmt').val('');
		        
		        $('#txt_exit_empname').val('');
		        $('#txt_exit_empid').val('');
		        
		        $('#txt_exit_empid').val(EmployeeID);
		        $('#txt_exit_empname').val(EmployeeName);
		        		       
		        $('#btn_df_Save').addClass('hidden');
		        $('#btn_Exit_Edit').removeClass('hidden');
		        $('#btn_Exit_Can').removeClass('hidden');
	}
	
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>