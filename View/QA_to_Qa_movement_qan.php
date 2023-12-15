<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
$process = 'NA';
$alert_msg = $thisPage= "";
if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
		exit();
	}
	else
	{
		$isPostBack = false;
		$referer = "";
		$thisPage = REQUEST_SCHEME."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];	
		
		if (isset($_SERVER['HTTP_REFERER'])){
		    $referer = $_SERVER['HTTP_REFERER'];
		}

		if($referer == $thisPage){
		    $isPostBack = true;
		} 
		
		if($isPostBack && isset($_POST))
		{
			
			if(isset($_POST['txt_process'])){
				$process = $_POST['txt_process'];
			}
			
		}
		
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
	exit();
}
if(isset($_POST['btn_approve']))
{
	if(count($_POST['cb']) > 0 && $_POST['cb'])
	{
		$insert_flag = 0;
		$mySQLError_log = '';
		foreach($_POST['cb'] as $EmployeeID)
		{
			
			$myDB=new MysqliDb();
			$checkEmployee = $myDB->rawQuery("SELECT * FROM tbl_qa_to_qa_movement where EmployeeID ='".$EmployeeID."' and Status = 2;");
			$mySQLError = $myDB->getLastError();
			$rowCount = $myDB->count;
			if($rowCount>0 && $checkEmployee)
			{
				
				$update_ar = array(
					"Status" => 4,
					"Remark" =>$checkEmployee[0]['Remark'].$_SESSION['__user_Name'].'>'.$_SESSION['__user_logid'].'>'.date('Y-m-d H:i:s').'>'.$_POST['txt_remark_qa2qa'].'|',
					"modifiedon" => date('Y-m-d H:i:s'),
					"ModifiedBy" => $_SESSION['__user_logid']
				);
				$myDB=new MysqliDb();
				$myDB->where("EmployeeID",$EmployeeID)->where("Status","2","=","AND");
				$flag = $myDB->update("tbl_qa_to_qa_movement",$update_ar);
				$mySQLError = $myDB->getLastError();
				$rowCount = $myDB->count;
				if(empty($mySQLError))
				{
					
				}
				else
				{
					$insert_flag++;
					$mySQLError_log .= $EmployeeID.' not Updates. Error :'.$mySQLError;
				}
			}
			else
			{
				$insert_flag++;
				$mySQLError_log .= $EmployeeID.' not Updates. Error : This Employee is not in this QH queue';
			} 
			
		}
		if($insert_flag == 0 )
		{
			//$alert_msg = '<span class="text-success">ALL Employee has been Updates.</span>';
			echo "<script>$(function(){ toastr.success('ALL Employee has been Updates.'); }); </script>";
		}
		else
		{
			
		//	$alert_msg = $mySQLError_log;
			echo "<script>$(function(){ toastr.error(' ".$mySQLError_log."'); }); </script>";
		}
		//tbl_qa_to_qa_movement
	}
	else
	{
		echo "<script>$(function(){ toastr.error('>No Employee selected...'); }); </script>";
		
	}
	
}
elseif(isset($_POST['btn_reject']))
{
	if(count($_POST['cb']) > 0 && $_POST['cb'])
	{
		$insert_flag = 0;
		$mySQLError_log = '';
		foreach($_POST['cb'] as $EmployeeID)
		{
			
			$myDB=new MysqliDb();
			$checkEmployee = $myDB->rawQuery("SELECT * FROM tbl_qa_to_qa_movement where EmployeeID ='".$EmployeeID."' and Status = 2;");
			if(count($checkEmployee)>0 && $checkEmployee)
			{
				
				$update_ar = array(							
					"Status" => 5,
					"Remark" =>$checkEmployee[0]['Remark'].$_SESSION['__user_Name'].'>'.$_SESSION['__user_logid'].'>'.date('Y-m-d H:i:s').'>'.$_POST['txt_remark_qa2qa'].'|',
					"modifiedon" => date('Y-m-d H:i:s'),
					"ModifiedBy" => $_SESSION['__user_logid']
				);
				$myDB->where("EmployeeID",$EmployeeID)->where("Status","2","=","AND");
				$myDB=new MysqliDb();
				$flag = $myDB->update("tbl_qa_to_qa_movement",$update_ar);
				$mySQLError = $myDB->getLastError();
				if(empty($mySQLError))
				{
					
				}
				else
				{
					$insert_flag++;
					$mySQLError_log .=$EmployeeID.' not Updates. Error :'.$mySQLError;
				}
			}
			else
			{
				$insert_flag++;
				$mySQLError_log .= $EmployeeID.' not Updates. Error : This Employee is not in this QH queue';
			} 
			
		}
		if($insert_flag == 0 )
		{
			//$alert_msg = '<span class="text-success">ALL Employee has been Updates.</span>';
			echo "<script>$(function(){ toastr.success('ALL Employee has been Updates.'); }); </script>";
		}
		else
		{
			
			//$alert_msg = $mySQLError_log;
			echo "<script>$(function(){ toastr.error(' ".$mySQLError_log."'); }); </script>";
		}
		//tbl_qa_to_qa_movement
	}
	else
	{
		//$alert_msg = '<span class="text-warning">No Employee selected...</span>';
		echo "<script>$(function(){ toastr.error('>No Employee selected...'); }); </script>";
		
	}
	
}
?>
<script>
	$(function(){
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
				        lengthMenu: [
				            [ 5,10,15, 25, 50, -1 ],
				            ['5 rows','10 rows','15 rows', '25 rows', '50 rows', 'Show all' ]
				        ],
				         "pageLength": 5,
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
							"sScrollY" : "350",
							"bScrollCollapse" : true,
							"bLengthChange" : false,
							"fnDrawCallback":function() {
								
								$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
							}
									
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
<span id="PageTittle_span" class="hidden">Employee Movement QA to QA </span>

<!-- Main Div for all Page -->
<div class="pim-container " >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Employee Movement QA to QA New QA</h4>	
	 
<!-- Form container if any -->
	<div class="schema-form-section row" >
		<div class="input-field col s10 m10">
		<?php
		 	$sqlBy ="select distinct Process from tbl_qa_to_qa_movement where NewQA ='".$_SESSION['__user_logid']."'"; 
				$myDB=new MysqliDb();
				$resultBy=$myDB->rawQuery($sqlBy);
				$error=$myDB->getLastError();
				$rowCount = $myDB->count;
				
										
		?>
			      <select id="txt_process" name="txt_process" >
		            	<option value="NA">---Select---</option>
		            	<?php	
						if($resultBy && $rowCount>0){													
							foreach($resultBy as $key=>$value){
									if($process == ($value['Process']))	
									{
										echo '<option selected>'.$value['Process'].'</option>';
									}
									else
									{
										echo '<option>'.$value['Process'].'</option>';
									}												
								
							}

						}				
				      	?>
		            </select>
		            <label for="txt_process" class="active-drop-down active">Process</label>
		            <input  type="hidden" name="proc_handler" value="<?php echo $process;?>"/>
		        </div>
		        <div class="input-field col s2 m2 right-align">
					<button type="submit" value="Check" name="btn_check" id="btn_check"  onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green">Check</button>
				</div>    
			
			<div >
			
			<?php 
				if(!empty($process) && $process != 'NA')
				{
					
					//$query = "SELECT EmployeeID, EmployeeName, cm_id, Process, sub_process,qh, clientname, designation, Qa_ops FROM whole_details_peremp where Qa_ops = '".$_SESSION['__user_logid']."' and status  = 6;"
					$query = "select * from (SELECT wh.EmployeeID, wh.EmployeeName, pd.EmployeeName `Current QA`,date_format(MovementOn,'%d %M,%Y') as `Movement Date`, designation as `Designation` , clientname as `Client Name`, wh.Process, sub_process as `Sub Process`,tql.Remark  FROM whole_details_peremp wh inner join tbl_qa_to_qa_movement tql on tql.EmployeeID = wh.EmployeeID and wh.Status = 6 and tql.NewQA = '".$_SESSION['__user_logid']."' and tql.Process = '".$process."' left outer join personal_details pd on pd.EmployeeID = tql.CreatedBy where tql.Status = 2) t1";
					
					$myDB=new MysqliDb();
					$rst_qa2qa = $myDB->rawQuery($query);
					$mysql_error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if($rowCount> 0 && $query)
					{
						?>
						
					<div  class="hidden" id="div_btn">
						<div class="input-field col s12 m12 ">
							<textarea name="txt_remark_qa2qa" id="txt_remark_qa2qa" class="materialize-textarea" ></textarea>
							<label for="txt_remark_qa2qa" class="active-drop-down active">Remark</label>
						</div>
						<div class="input-field col s12 m12 right-align ">
							<button type="submit"  value="Approve" name="btn_approve" id="btn_approve"  onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green ">Submit</button>
							<button type="submit"   value="Reject" name="btn_reject" id="btn_reject"  onclick="return confirm('Are you sure you want to reject this selection?');" class="btn waves-effect waves-red btn-close ">Reject</button>
						</div>
					</div>
						<?php
						$table='<div class="had-container pull-left row card dataTableInline"  id="tbl_div" >
								<div class=""  >																																									<table id="myTable1" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead><tr>';
						
						foreach($rst_qa2qa[0] as $key_kl => $value_kl)
						{
							if($key_kl == 'Remark')
							{
								
							}
							elseif($key_kl == 'EmployeeID')
							{
								$table .='<th ><input type="checkbox" id="cbAll" name="cbAll" value="ALL"><label for="cbAll" >EmployeeID</label></th>';
							}
							else
							{
								$table .='<th >'.$key_kl.'</th>';
							}
								
						}
						$table .='</tr>';
					    $table .='</thead><tbody>';
					    $row_index = 0;
					    foreach($rst_qa2qa as $key => $value)
						{

							$table .='<tr>';
							foreach($value as $key_kl => $value_kl)
							{
								if($key_kl == 'Remark')
								{
									
								}
								elseif($key_kl == 'EmployeeID')
								{
									$table .= '<td><input type="checkbox" id="cb'.$value_kl.'" class="cb_child" name="cb[]" value="'.$value_kl.'"><label for="cb'.$value_kl.'" style="color: #059977;font-size: 14px;font-weight: bold;}">'.$value_kl.'</td>';
								}
								else
								{
									$table .= '<td>'.$value_kl.'</td>';
								}		
								
							}
							
							$table .='</tr>';
						}		
						$table .='</tbody></table></div></div>';
						echo $table;
						?>
						<!--<div class="col-sm-12">-->
						<div class="input-field col s12 m12 ">
							<?php 
							
							if(count($rst_qa2qa) > 0 && $rst_qa2qa)
							{
								foreach($rst_qa2qa as $comment)
								{
									$rmk = $comment['Remark'];
									$rmk = explode("|",$rmk);
									echo '<div style="border: 1px solid #9e9d9d;float: left;padding: 10px;width: 100%;border-radius:3px;margin-bottom:2px;"><span style="border: 1px solid #659265;width: 100%;float: left;padding: 5px;border-radius: 3px;background-color: #d2f3d2;color: green;"> Remarks For : <b>'.$comment['EmployeeName'].'</b></span>';
										//echo "<script>$(function(){ toastr.success(' Remarks For : <b>".$comment['EmployeeName']."'); }); </script>";
									
									array_pop($rmk);

									foreach($rmk as $com)
									{
										echo '<div>';
										$e_com = explode(">",$com);										
										echo '<span >'.$e_com[0].' <b>['.$e_com[1].']</b> <kbd>'.$e_com[2].'</kbd><span style="padding: 5px;color: black;">'.$e_com[3].'</span></kbd></span>';
										echo '</div>';
										
									}
									echo '</div>';
								}
							}
							?>
						</div>
						<?php
					}
					else
					{
						echo "<script>$(function(){ toastr.error('No Employee found for this Process or you may be have wrong Process selection..'); }); </script>";
					}
					
				}
				elseif(isset($_POST['btn_check']))
				{
					echo "<script>$(function(){ toastr.error('No Employee found for this Process or you may be have wrong Process selection..'); }); </script>";
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
	$(function(){
		$("#cbAll").change(function () {
		    $("input:checkbox").prop('checked', $(this).prop("checked"));
		});
		$("input:checkbox").change(function(){
			
			if($('input.cb_child:checkbox:checked').length>0)
			{
				if ($('input.cb_child:checkbox:checked').length ==$('input.cb_child:checkbox').length) {
					
			        $("#cbAll").prop("checked",true);
			    }
			    else
			    {
					$("#cbAll").prop("checked",false);
				}
				$("#div_btn").removeClass('hidden');
			}
			else
			{
				$("#cbAll").prop("checked",false);
				$("#div_btn").addClass('hidden');
			}
		});
		$("#btn_approve").on("click",function(){
			   var validate=0;
		       var alert_msg='';
			    $('#txt_new_qa').closest('div').removeClass('has-error');
			    if($('#txt_new_qa').val()==''||$('#txt_new_qa').val()=='NA')
		        {
					$('#txt_new_qa').closest('div').addClass('has-error');
					validate=1;
					alert_msg+='<li> New QA Should not be empty  </li>';
				}
				if(validate==1)
		      	{		      		
		      		/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
		      		$('#alert_message').show().attr("class","SlideInRight animated");
		      		$('#alert_message').delay(5000).fadeOut("slow");
					return false;
					*/
					$(function(){ toastr.error(alert_msg) });
					return false;
				}
		});
	});
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>