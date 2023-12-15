<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';

// Global variable used in Page Cycle
$alert_msg ='';
// Trigger Button-Save Click Event and Perform DB Action

// Trigger Button-Edit Click Event and Perform DB Action
if(isset($_POST['btn_submit']))
{	
	$type=(isset($_POST['txt_type'])? $_POST['txt_type'] : null);
	
	if(isset($_POST['cb']))
	{
		$checked_arr = $_POST['cb'];
		$count_check = count($checked_arr);
		$cm_id=(isset($_POST['hiddensubprocess'])? $_POST['hiddensubprocess'] : null);
		$batch_id=(isset($_POST['txt_batch'])? $_POST['txt_batch'] : null);
		$createBy=$_SESSION['__user_logid'];
		if($count_check>0)
		{	
					
			foreach($_POST['cb'] as $val)
			{
				$empID=$val;
				if($type == 'Reject')
				{
					$Insert='insert into batch_mapping_log(IntID,batch_id,action,CreatedBy,CreatedOn,ModifiedBy,mode) values("'.$empID.'",(select batch_id from batch_mapping where IntID="'.$empID.'" order by id desc limit 1),"'.$type.'",(select CreatedBy from batch_mapping where IntID="'.$empID.'" order by id desc limit 1),(select CreatedOn from batch_mapping where IntID="'.$empID.'" order by id desc limit 1),"'.$createBy.'",(select mode from batch_mapping where IntID="'.$empID.'" order by id desc limit 1));';
					
					$myDB=new MysqliDb();
				    $myDB->rawQuery($Insert);
				    $mysql_error = $myDB->getLastError();
					if(empty($mysql_error))
					{
						$msg1 = '';
						$Body = '';
						$delete='delete from batch_mapping where IntID= "'.$empID.'"';
						$myDB=new MysqliDb();
					    $myDB->rawQuery($delete);
					    $mysql_error = $myDB->getLastError();
					    
					    $api=INTERVIEW_URL."getContact.php?intid=".$empID;
					   //echo $api;
					    $curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, $api);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_HEADER, false);
						$data = curl_exec($curl);	
						$data_array=json_decode($data);	
						$emailid=$name=$gender='';
						if(count($data_array)>0)
						{
							
							
					        foreach($data_array as $key=>$value)
					        {
					        	$emailid = $value->emailid;
					        	$name = $value->EmployeeName;
					        	$gender = strtolower($value->gender);
					        	
								if ($gender == 'female') {
						            $gender = 'Ms';
						        } else if ($gender == 'male'){
						            $gender = 'Mr';
						        }
						        else
						        {
									$gender = '';
								}
					        	
					        }
					     }
						
					    $msg1 ="Hi ".$gender." ".$name. " (".$empID."),";
						$msg1=$msg1." <br><br>  Your appointment considered as null and void due to not joining within 3 days of offering date of joining.";
						
						$mail = new PHPMailer;
						$mail->isSMTP();// Set mailer to use SMTP
						$mail->Host = EMAIL_HOST; 
						$mail->SMTPAuth = EMAIL_AUTH;
						$mail->Username = EMAIL_USER;   
						$mail->Password = EMAIL_PASS;                        
						$mail->SMTPSecure = EMAIL_SMTPSecure;
						$mail->Port = EMAIL_PORT; 
						/*$mail->SMTPOptions = array(
					    'ssl' => array(
					        'verify_peer' => false,
					        'verify_peer_name' => false,
					        'allow_self_signed' => true
					    )
					);*/
    
						$mail->setFrom(EMAIL_FROM, 'Offer Rejected for '.$empID);
						//$mail->AddAddress($emailAddress);
						$mail->AddAddress($emailid);
						$mail->addBCC('md.masood@cogenteservices.com');
						//$mail->Subject = $Subject_;
						$mail->Subject = 'Offer null and void';
				        
				     
				       	$Body .=$msg1."<br><br>Thanks EMS Team";
				        $mail->isHTML(true);     
				        $mail->Body = $Body;
				     	if(!$mail->send())
				        {
				        	$emailStatus='Mailer Error: ' . $mail->ErrorInfo;
				           
				        }
				        else
				        {
				            $emailStatus='Mail Send successfully.';
				        }
						
						//$myDB=new MysqliDb();
			 	 		//$sms_status = $myDB->rawQuery('insert into ncns_sms set employeeid="'.$empid.'", smsstatus="'.addslashes($response).'",sms_text="'.addslashes($msg).'",EmailAddress="'.addslashes($emailAddress).'",emailStatus="'.addslashes($emailStatus).'", createdBy="'.$_SESSION['__user_logid'].'"');
							    
					}
				
				}
				
				else if($type == 'Delink')
				{
					$update='insert into batch_mapping_log(IntID,batch_id,action,CreatedBy,CreatedOn,ModifiedBy,mode) values("'.$empID.'",(select batch_id from batch_mapping where IntID="'.$empID.'" order by id desc limit 1),"'.$type.'",(select CreatedBy from batch_mapping where IntID="'.$empID.'" order by id desc limit 1),(select CreatedOn from batch_mapping where IntID="'.$empID.'" order by id desc limit 1),"'.$createBy.'",(select mode from batch_mapping where IntID="'.$empID.'" order by id desc limit 1));';
					
					$myDB=new MysqliDb();
				    $myDB->rawQuery($update);
				    $mysql_error = $myDB->getLastError();
					if(empty($mysql_error))
					{
						$delete='delete from batch_mapping where IntID= "'.$empID.'"';
						$myDB=new MysqliDb();
					    $myDB->rawQuery($delete);
					    $mysql_error = $myDB->getLastError();
					    
					    $api=INTERVIEW_URL."updatehrdate.php?empid=".$empID;
					   //echo $api;
					    $curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, $api);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_HEADER, false);
						$data = curl_exec($curl);	
						$data_array=json_decode($data);	
											    
					}
				
				}
			}
			echo "<script>$(function(){ toastr.success('Action Done Successfully'); }); </script>";
			
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Record not updated, no Employee selected.'); }); </script>";
			
		}
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Record not updated, no Employee selected.'); }); </script>";
	}	
				
}
?>
<script>
	$(document).ready(function(){
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
				        "paging": false,
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
						        }
						        /*,'copy'*/,
						        'pageLength'
						        
						    ]
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


<div id="content" class="content" >
<span id="PageTittle_span" class="hidden">De-link and Reject offer</span>

	<div class="pim-container row" id="div_main" >
		<div class="form-div">
		<input type="hidden" id="hiddenclient" name="hiddenclient" />
		<input type="hidden" id="hiddensubprocess" name="hiddensubprocess" />
		<input type="hidden" id="hiddensubprocessid" name="hiddensubprocessid" />
		
			<h4>De-link and Reject offer</h4>			
			 <div class="schema-form-section row" >
			   
				      				      
				      <div class="input-field col s12 m12" id="rpt_container">
				      
				      <div id="divtype">
				      <div class="input-field col s4 m4">
				            <select id="txt_type" name="txt_type">
				            	<option value="NA">----Select----</option>	
						      	<option value="Reject">Reject Offer</option>	
						      	<option value="Delink">De Link From Batch</option>	
				            </select>
				            
		            		<label for="txt_type" class="active-drop-down active">Action *</label>
			   		 	</div>
			   		 	
			   		 	<div class="input-field col s8 m8 getdetails right-align">
					     
					    	<input type="hidden" class="form-control hidden" id="hid_Department_ID"  name="hid_Department_ID"/>
						    <button type="submit" name="btn_submit" id="btn_submit" class="btn waves-effect waves-green" style="width: 70%;">Submit Action</button>
						    
					    </div>
			   		  </div>				   		 	
					    
					    <div class="input-field col s8 m8 getdetails right-align" id="divbtn">
					     
					    	<input type="hidden" class="form-control hidden" id="hid_Department_ID"  name="hid_Department_ID"/>
						    <button type="submit" name="btn_getDetails" id="btn_getDetails" class="btn waves-effect waves-green" style="width: 70%;">Click for Non - Assigned Candidate</button>
						    
					    </div>
				      
				   </div>
			    
			  
			    <?php 
			    if(isset($_POST['btn_getDetails']))
			    {
			    	
						
					$myDB=new MysqliDb();
					
			     	$sqlBy ='select t1.INTID,t5.location,t4.process,t4.sub_process,t3.batch_no from batch_mapping t1 left outer join personal_details t2 on t1.IntID=t2.INTID join batch_master t3 on t1.batch_id=t3.BacthID join new_client_master t4 on t3.cm_id=t4.cm_id join location_master t5 on t4.location=t5.id where t2.INTID is null'; 
					$myDB=new MysqliDb();
					$resultBy=$myDB->rawQuery($sqlBy);
					$mysql_error = $myDB->getLastError();
			     	
				
		    	
		    						 
			        
					if(count($resultBy)>0)
					{													
							
						?>
						
			   			 <div class="flow-x-scroll" style="margin-top: 10px;width: 100%;padding: 15px; overflow: scroll; height: 400px;">
						  															
						  <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						            <th>
							            <input type="checkbox" id="cbAll" name="cbAll" value="ALL">
							            <label for="cbAll">INT ID</label>
						            </th>
						            <th class="hidden">INT ID</th>
						            <th>Location</th>
						            <th>Process</th>
						            <th>Sub Process</th>
						            <th>Batch No</th>
						            						            
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       $count=0;
					        foreach($resultBy as $key=>$value){
					        	$count++;
							echo '<tr>';							
							echo '<td class="EmpId"><input type="checkbox" id="cb'.$count.'" class="cb_child" name="cb[]" value="'.$value['INTID'].'"><label for="cb'.$count.'" style="color: #059977;font-size: 14px;font-weight: bold;}">'.$value['INTID'].'</label></td>';
							echo '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="'.$value['INTID'].'">'.$value['INTID'].'</a></td>';
							echo '<td class="location">'.$value['location'].'</td>';					
							echo '<td class="process">'.$value['process'].'</td>';					
							echo '<td class="sub_process">'.$value['sub_process'].'</td>';					
							echo '<td class="batch_no">'.$value['batch_no'].'</td>';					
													
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
							echo "<script>$(function(){ toastr.info('No Employee Found.'); }); </script>";
						}   
				}
					?>
				
			
			
			
			</div> 
		</div>
	</div>    
<!--Content Div for all Page End -->  
</div>

<script>
	
$(document).ready(function(){
	//Model Assigned and initiation code on document load
	$('#divtype').addClass('hidden');
	
	// This code for cancel button trigger click and also for model close
        
    
    
    // This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
    
	    
    $('#btn_submit').on('click', function(){
    	 var validate=0;
    	 //alert($('#txt_batch').val());
    	if($('#txt_type').val()=='NA')
        {
								
			validate=1;
			$('#txt_type').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
        	if($('#spantxt_type').size() == 0)
			{
	            $('<span id="spantxt_type" class="help-block">*</span>').insertAfter('#txt_type');
	        }
		}
		
		if(validate==1)
      	{		      		
      		
			return false;
		} 
		
    
    });	
    	
});


	$("#cbAll").change(function () {
		    $("input.cb_child:checkbox").prop('checked', $(this).prop("checked"));
		    $('#divbtn').addClass('hidden');
		    if($("input.cb_child:checkbox:checked").length>0)
		    {
				$('#divtype').removeClass('hidden');
			}
			else
			{
				$('#divtype').addClass('hidden')
			}
		    $(".schema-form-section input,.schema-form-section textarea").each(function(index, element)
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
			$('select').formSelect();
		});
    
    $("input:checkbox").click(function(){
    	$('#divbtn').addClass('hidden');
			if($('input:checkbox:checked').length>0)
			{
				$('#divtype').removeClass('hidden');
				
				
				//alert('1');
				/*checklistdata();
				$('#div_date_1').removeClass('hidden');
				$('#div_duration_1').removeClass('hidden');
				$('#txt_Date_crt_1').removeClass('hidden');*/
			}
			else
			{
				$('#divtype').addClass('hidden')
				$('#txt_type').val('NA');
			}
			$('select').formSelect();
		});
	
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>