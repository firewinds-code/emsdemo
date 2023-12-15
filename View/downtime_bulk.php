<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

$btnSave='';

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
$order_len =15;
if(isset($_POST['order_text']))
{
	//echo $_POST['order_text'];
	switch($_POST['order_text'])
	{
		case '10 rows':
		{
			//echo $_POST['order_text'];
			$order_len=10;
			break;
		}
		case '25 rows':
		{
			//echo $_POST['order_text'];
			$order_len=25;
			break;
		}
		case '50 rows':
		{
			$order_len=50;
			break;
		}
		case '50 rows':
		{
			$order_len=50;
			break;
		}
		case 'Show all':
		{
			$order_len=2500;
			break;
		}
		case '2500':
		{
			$order_len=2500;
			break;
		}
		default:
		{
			$order_len=5;
			break;
		}
		
	}
}

if(isset($_POST['btn_Leave_Save']))
{
	if(!empty($_POST['check_val_up']))
	{
		if(!empty($_POST['txtSupervisorApproval']) && !empty($_POST['txt_common_comment']) && ($_POST['txtSupervisorApproval'] == 'Approve' || $_POST['txtSupervisorApproval'] == 'Decline'))
		{
			foreach($_POST['check_val_up'] as $data_id)
			{
				if(intval($data_id) && $data_id > 0)
				{
					$myDB = new MysqliDb();
					$updateStatus = $myDB->query("update downtime set FAStatus='".$_POST['txtSupervisorApproval']."',ModifiedBy='".$_SESSION['__user_Name']."',ModifiedOn=now() where ID=".$data_id);
					//echo "update downtime set FAStatus='".$_POST['txtSupervisorApproval']."',ModifiedBy='".$_SESSION['__user_Name']."',ModifiedOn=now() where ID=".$data_id;
					echo '<br />';
					$error_bdt = $myDB->getLastError();
					$myDB = new MysqliDb();
					$updateStatus = $myDB->query("INSERT INTO `dtcomments`(`DTID`,`CreatedBy`,`CreatedOn`,`Comments`) VALUES('".$data_id."','".$_SESSION['__user_logid']."',now(),'".addslashes($_POST['txt_common_comment'])."')");
					
					
					echo '<br />';
					$error_bdt .= $myDB->getLastError();
					if(empty($error_bdt))
					{
						echo "<script>$(function(){ toastr.success('Data saved Successfully'); }); </script>";
					}
					else
					{
						echo "<script>$(function(){ toastr.error('Data not saved' '".$error_bdt."'); }); </script>";
					}
					//update downtime set RTStatus=p_RTStatus,ModifiedBy=p_ModifiedBy,ModifiedOn=now() where ID=p_ID;

					//INSERT INTO `dtcomments`(`DTID`,`CreatedBy`,`CreatedOn`,`Comments`) VALUES(p_ID,p_ModifiedByID,now(),p_EmpComment);
				}
				
			}
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Comment and Supervisor Approval should not be empty or pending.'); }); </script>";
		}	
	}
	else
	{
		echo "<script>$(function(){ toastr.error('No item found in checklist.'); }); </script>";
	}
	
	
}
?>

<script>
	$(document).ready(function(){
		function eventFired_order(el)
		{
			//alert($('#order_text').val());
			$('#order_text').val($('.dt-button.active>span').text());
			//alert($('#order_text').val()+','+$('.dt-button.active>span').text());
		}
		$('#order_text').val($('.dt-button.active>span').text());
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
				        lengthMenu: [
				            [ 5,10, 25, 50, -1 ],
				            ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
				        ],
				        "iDisplayLength":<?php echo $order_len; ?>,
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
							
							"bAutoWidth" : true,
							"iDisplayLength": 25,
							"sScrollX" : "100%",
							"bScrollCollapse" : true,
							"bLengthChange" : false,
							"fnDrawCallback":function() {
								
								$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
							}
							
				       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
				    })
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
<span id="PageTittle_span" class="hidden">Bulk Manage Downtime Request</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Bulk Manage Downtime Request</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	
	
	
		
		<div class="col s12 m12">
			
					<div class="input-field col s12 m12" id="super_div1">
					      <select id="txtSupervisorApproval" name="txtSupervisorApproval">
						      	<option>Pending</option>
						      	<option>Approve</option>
						      	<option>Decline</option>
					      </select>
					      <label for="txtSupervisorApproval" class="active-drop-down active">Supervisor Approval</label>
					</div>
					
					<div class="input-field col s12 m12" >
					      <textarea id="txt_common_comment"  name="txt_common_comment" class="materialize-textarea"></textarea> 
					      <label for="txt_srch_DateFrom">Enter Comment</label>    
					</div>
					
					<div class="input-field col s12 m12 right-align">
						<button type="submit" name="btn_Leave_Save" id="btn_Leave_Save" class="btn waves-effect waves-green <?php echo $btnSave;?>"> Update Request</button>
			  		</div>	
		
	
		<div id="pnlTable">
			    <?php 
			    $sqlConnect = 'SELECT dt.ID,dt.EmpID,dt.Process,t1.EmployeeName,dt.DTFrom as `From`,dt.DTTo as `To`,dt.TotalDT as `Total DownTime`,dt.ReqTo,dt.LoginDate,dt.EmpComment , dt.FAID, t2.EmployeeName FAN, dt.FAStatus , dt.FAComment ,dt.RTStatus ,dt.RTComment,dt.CreatedOn,dt.RTID,t3.EmployeeName as ReportsTo,dt.Request_type,dt.IT_ticketid FROM emp_details   t1 INNER JOIN  downtime dt ON  dt.EmpID=t1.EmployeeID left outer join emp_details t2 on t2.EmployeeID = dt.FAID left outer join emp_details t3 on t3.EmployeeID = dt.RTID where (dt.FAID="'.$_SESSION['__user_logid'].'" && dt.FAStatus=\'Pending\' ) and dt.Request_type ="IT" and CAST(dt.LoginDate as date)>=CAST(NOW() - interval 1 month as date) order by dt.CreatedOn asc;'; 
						$myDB=new MysqliDb();
						$result=$myDB->query($sqlConnect);
						$error=$myDB->getLastError();
						if(empty($error)){?>
						
			   			 	<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
							  <div class=""  >
							  <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							    <thead>
						        <tr>
						        
						        	<th>
						        	<div class="col s1 m1">
									        <input name="check_ALL_up" id="chkAll" value="ALL" type="checkbox" onclick="checkItem_All(this);">
									        <label for="chkAll"></label>
									      </div> 
						        	</th>
						        	
						            <th>EmployeeID</th>						           
						            <th>EmployeeName</th>
						            <th>Process</th>	
						            <th>From</th>
						            <th>To</th>					            
						            <th>Downtime</th>
						            <th>Request To</th>
						            <th>Login Date</th>
						            <th>FA EmployeeID</th>
						            <th>FA Name</th>
						            <th>FA Status</th>			            
						            <th>IT Ticket Id</th>
						            <th>ReportsTo</th>	
						            <th>CreatedOn</th>			            
						            
						        </tr>
						    </thead>
						    <tbody>					        
						       <?php
						        $td_counter=0;
						        foreach($result as $key=>$value){
								echo '<tr>';
								$td_counter ++;
								/*if ($_SESSION['__user_logid'] == "CE03070003" )
								{
									echo '<td class="tbl__ID_for_check"><input type="checkbox" class="check_val_" style="    margin-left: 40%;" name="check_val_up[]" id="chkitem_'.$td_counter.'" value="'.$value['ID'].'" onclick="checkAll();" /></td>';
								}*/
								echo '<td>
								
								<div class="col s1 m1">
							        <input type="checkbox" name="check_val_up[]" id="chkitem_'.$td_counter.'" class="check_val_" value="'.$value['ID'].'" onclick="checkAll();">
							        <label for="chkitem_'.$td_counter.'"></label>
							      </div> 
								</td>';
					     		        
								echo '<td class="tbl__EmployeeID">'.$value['EmpID'].'</a></td>';
								echo '<td class="tbl__EmployeeName">'.$value['EmployeeName'].'</td>';					
								echo '<td class="tbl__Process">'.$value['Process'].'</td>';					
								echo '<td class="tbl__DTFrom">'.$value['From'].'</td>';	
								echo '<td class="tbl__DTTo">'.$value['To'].'</td>';	
								echo '<td class="tbl__TotalDT">'.$value['Total DownTime'].'</td>';					
								echo '<td class="tbl__ReqTo">'.$value['ReqTo'].'</td>';	
								echo '<td class="tbl__LoginDate">'.$value['LoginDate'].'</td>';							
								echo '<td class="tbl__FAID">'.$value['FAID'].'</td>';	
								echo '<td class="tbl__FAN">'.$value['FAN'].'</td>';	
								echo '<td class="tbl__FAN">'.$value['FAN'].'</td>';	
								echo '<td class="tbl__FAStatus">'.$value['IT_ticketid'].'</td>';	
								echo '<td class="tbl__ReportsTo">'.$value['ReportsTo'].'</td>';
								echo '<td class="tbl__CreatedOn">'.$value['CreatedOn'].'</td>';	
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
							echo "<script>$(function(){ toastr.success('No  Data Found' '".$error."'); }); </script>";
						} 
						?>
		</div>	
		
	
 
<script>
	function checkItem_All(el)
	{
		$(".check_val_").prop('checked', $(el).prop('checked'));
			
	}
	function checkAll(){
		if($(this).prop('checked'))
		{
			$(this).prop('checked',false);	
		}
		else
		{
			$(this).prop('checked',true);
		}
		
		if($('input.check_val_:checked').length == $('input.check_val_').length){
        	
            
        }else{
            $("#chkAll").prop('checked', false);
            
        }
        
    }
</script>

</div>  	 
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
