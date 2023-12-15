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
	
	/*$myDB = new MysqliDb();
	$result_of = $myDB->query('select OnFloor from status_table where EmployeeID = "'.$_SESSION['__user_logid'].'"');
	if(!empty($result_of[0]['OnFloor']))
	{
		$iTime_in = new DateTime($result_of[0]['OnFloor']);
		$iTime_out =new DateTime();
		$interval = $iTime_in->diff($iTime_out);
		if($interval->format("%a") > 15)
		{
			echo "<div>You not have permission to open this page.Please don't try it again else a illegal attempt is raised by your ID.</div>";
			exit();
		}
	}*/
	
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
	exit();
}

$order_len= 5;
function dt_diff($d1 ,$d2)
{
	$datetime1 = new DateTime($d1);
	$datetime2 = new DateTime($d2);
	$difference = $datetime1->diff($datetime2);
	return($difference->d);
}
  $btnSave ='hidden';
  $btnAdd = '';
  $AccountHeadName= $AccountHead='';
  $SiteHead='CE03070003';
  $SiteHeadName='SACHIN SIWACH';
  $alert_msg ='';
  
  $readonly_ah =' readonly="true" ';
  $readonly_sh =' readonly="true" ';
 
  (isset($_POST['txt_srch_DateFrom']))?$date_srch_from =$_POST['txt_srch_DateFrom'] : $date_srch_from= date('Y-m-01');
  (isset($_POST['txt_srch_DateTo']))?$date_srch_to =$_POST['txt_srch_DateTo'] : $date_srch_to= date('Y-m-t');
  
   $str= "select account_head, EmployeeName from new_client_master inner join personal_details on EmployeeID = account_head where cm_id =( select cm_id from employee_map where EmployeeID='".$_SESSION['__user_logid']."')";
 	$myDB=new MysqliDb();	
	$result=$myDB->query($str);
	$error=$myDB->getLastError();
  	if(empty($error))
  	{
		foreach($result as $key => $value)
		{
			$AccountHead = $value['account_head'];
			$AccountHeadName = $value['EmployeeName'];
		}
	}
	
	 $str= "select ReportTo,personal_details.EmployeeName from employee_map inner join new_client_master on new_client_master.cm_id = employee_map.cm_id inner join status_table on employee_map.EmployeeID = status_table.EmployeeID left outer join personal_details on personal_details.EmployeeID = status_table.ReportTo where employee_map.EmployeeID = '".$_SESSION['__user_logid']."' and new_client_master.account_head = '".$_SESSION['__user_logid']."' ";
 	$myDB=new MysqliDb();	
	$result=$myDB->query($str);
	$error=$myDB->getLastError();
	if(empty($error))
	{
		foreach($result as $key => $value)
		{
			$AccountHead = $value['ReportTo'];
			$AccountHeadName = $value['EmployeeName'];
		}
	}
  	
	
  	if($_SESSION['__user_logid'] == $AccountHead)
  	{
		$readonly_ah='';
	}
	
	if($_SESSION['__user_logid'] == $SiteHead)
  	{
		$readonly_sh='';
	}
	
	if(isset($_POST['btn_Leave_Add']))
	{
				$EmployeeID = $_SESSION['__user_logid'];
                $Name = $_SESSION['__user_Name'];
                $Exception = $_POST['txt_Request'];
                $EmployeeComment = $_POST['txt_Comment'];
                $DateFrom = $_POST['txt_DateFrom'];
                $DateTo = $_POST['txt_DateTo'];
                $MngrStatusID = "Pending";
                $HeadStatusID = "Pending";

                if ($_POST['txt_Request'] == "Roster Change" || $_POST['txt_Request'] == "Shift Change")
                {
                    $ShiftIn = $_POST['txt_ShiftIn'];
                    $ShiftOut = $_POST['txt_ShiftOut'];
                    $IssueType = "NA";
                    $CurrAtt = "NA";
                    $UpdateAtt = "NA";
                    $LeaveType = "NA";
                }
                else if ($_POST['txt_Request'] == "Biometric issue")
                {
                    $ShiftIn = "NA";
                    $ShiftOut = "NA";
                    $LeaveType = "NA";
                    $IssueType = 'Biomertic Issue';
                    $CurrAtt = $_POST['txt_curatnd'];
                    $UpdateAtt = $_POST['txt_updateatnd'];
                }
                else if ($_POST['txt_Request'] == "Back Dated Leave")
                {
                    $ShiftIn = "NA";
                    $ShiftOut = "NA";
                    $LeaveType = $_POST['txt_LeaveType'];
                    $IssueType = "NA";
                    $CurrAtt = "NA";
                    $UpdateAtt = "NA";
                }
                else if ($_POST['txt_Request'] == "Working on Holiday" || $_POST['txt_Request'] == "Working on WeekOff" || $_POST['txt_Request'] == "Working on Leave")
                {
                    $ShiftIn = $_POST['txt_ShiftIn'];
                    $ShiftOut = $_POST['txt_ShiftOut'];
                    $LeaveType = "NA";
                    $IssueType = "NA";
                    $CurrAtt = "NA";
                    $UpdateAtt = "P";
                }
                else
                {
                    $ShiftIn = "NA";
                    $ShiftOut = "NA";
                    $IssueType = "NA";
                    $CurrAtt = "NA";
                    $UpdateAtt = "NA";
                    $LeaveType = "NA";
                }
                
            $myDB = new MysqliDb();
            $result_kh = $myDB->query('select EmployeeID from exception where EmployeeID = "'.$EmployeeID.'" and DateFrom = "'.$DateFrom.'" and DateTo = "'.$DateTo.'" and Exception = "'.$Exception.'"');
            if(count($result_kh) > 0 & $result_kh)
            {
				echo "<script>$(function(){ toastr.info('You already applied this request'); }); </script>";
			}
			else
			{
				
				$myDB = new MysqliDb();
	            $sqlInsertException = 'call sp_InsertException("'.$EmployeeID.'","'.$Name.'","'.$Exception.'","'.$EmployeeComment.'","'.$DateFrom.'","'.$DateTo.'","'.$MngrStatusID.'","'.$HeadStatusID.'","'.$IssueType.'","'.$CurrAtt.'","'.$UpdateAtt.'","'.$ShiftIn.'","'.$ShiftOut.'","'.$LeaveType.'","web-nJUilis166")';
				$flag = $myDB->query($sqlInsertException);
				$error = $myDB->getLastError();
				if(empty($error))
				{
					echo "<script>$(function(){ toastr.success('Request Submitted and Sent To'".$_POST['txtApprovedBy']."); }); </script>";
				}
				else
				{
					echo "<script>$(function(){ toastr.error('Request Not Submitted :'".$error."); }); </script>";
				}
			}		
	}
	
$search = '';
if(isset($_POST['txt_search']))
{
	$search =$_POST['txt_search'];
}
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
?>

<script>
$(document).ready(function(){
	function eventFired_order(el)
	{
	//alert($('#order_text').val());
	$('#order_text').val($('.dt-button.active>span').text());
	//alert($('#order_text').val()+','+$('.dt-button.active>span').text());
	}
	$('#myTable').DataTable({
		        dom: 'Bfrtip',
		        lengthMenu: [
		            [ 5,10, 25, 50, -1 ],
		            ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
		        ],
		        "iDisplayLength": $('#order_text').val(),
		         buttons: [
				          
				        /*{
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
				        },'copy',*/'pageLength'
				        
				    ],
				    "bProcessing" : true,
					"bDestroy" : true,
					"bAutoWidth" : true,
					"iDisplayLength": 25,
					"sScrollX" : "100%",
					"bScrollCollapse" : true,
					"bLengthChange" : false,
					"fnDrawCallback":function() {
						$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
					}
					
		       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		    }).search($('#txt_search').val()).draw().on( 'order.dt',  function () { eventFired_order(); } );
	$('.buttons-copy').attr('id','buttons_copy');
	$('.buttons-csv').attr('id','buttons_csv');
	$('.buttons-excel').attr('id','buttons_excel');
	$('.buttons-pdf').attr('id','buttons_pdf');
	$('.buttons-print').attr('id','buttons_print');
	$('.buttons-page-length').attr('id','buttons_page_length');
	$('input[type="search"]').change(function () {
	    // alert($('#ctl00_ContentPlaceHolder1_txt_search').val());
	    $('#ctl00_ContentPlaceHolder1_txt_search').val($('input[type="search"]').val());
	    $('#ctl00_ContentPlaceHolder1_lblmsg2').text("Search Data  :: " + $('input[type="search"]').val());
	    $('#ctl00_ContentPlaceHolder1_GridView1 input[type="checkbox"]').prop("checked", false);

	});
	$('input[type="search"]').blur(function () {
	    // alert($('#ctl00_ContentPlaceHolder1_txt_search').val());
	    $('#txt_search').val($('input[type="search"]').val());
	   
	});
	$('input[type="search"]').keyup(function () {
	    // alert($('#ctl00_ContentPlaceHolder1_txt_search').val());
	    $('#txt_search').val($('input[type="search"]').val());
	   
	});
	/*$('dt-button-collection.dt-button').click(function(){
	var data_label = $(this).children('span').text();
	alert(data_label);
	});*/
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Raise Request</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Raise Request</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	
	        <div id="leftmenu" class="container drawer drawer--left"></div>
			<div class="col s12 m12" id="app_link"></div>
	
			<input type="hidden" name="txt_cur_empid" id="txt_cur_empid"  value="<?php echo $_SESSION['__user_logid'];?>"/>
			<input type="hidden" name="txtID" id="txtID"  value=""/>
			<input type="hidden" name="txt_search" id="txt_search"  value="<?php echo $search; ?>"/>
			<input type="hidden" id="order_text" name="order_text" value="<?php echo $order_len?>" />
		
				<div class="input-field col s6 m6" >
				 <input type="text" readonly="true" id="txtEmpName" name="txtEmpName" value="<?php echo $_SESSION['__user_Name'];?>"/>
				 <input type="hidden" readonly="true" id="txtEmpName1" name="txtEmpName1" value="<?php echo $_SESSION['__user_Name'];?>"/> 
				 <label for="txtEmpName"> Employee Name</label>
			    </div>
			    
			    <div class="input-field col s6 m6">
					<input type="text" readonly="true" id="txtEmpID" name="txtEmpID" value="<?php echo $_SESSION['__user_logid'];?>"/>
					<input type="hidden" readonly="true" id="txtEmpID1" name="txtEmpID1" value="<?php echo $_SESSION['__user_logid'];?>"/>
					<label for=""> Employee ID</label>
			    </div>
					
			    <div class="input-field col s6 m6 clsIDHome">
			      <select id="txt_Request"  name="txt_Request"  >
	      				<option Selected="True" Value="NA">---Select---</option>
	                    <option>Back Dated Leave</option>
	                    <option>Shift Change</option>
	                    <option>Biometric issue</option>
	                    <!--<option>Working on Holiday</option>-->
	                    <option>Working on WeekOff</option>
	                    <option>Working on Leave</option>
			      </select>
			      <label for="txt_Request" class="active-drop-down active">Raise Request</label>
			    </div>
			    
			    <div class="input-field col s6 m6 clsIDHome">
			      <input type="text" readonly="true" id="txt_DateFrom" name="txt_DateFrom" placeholder="Date From"/>
			    </div>
		    
			    <div class="input-field col s6 m6 clsIDHome">
			      <input type="text" readonly="true" id="txt_DateTo" name="txt_DateTo" placeholder="Date To" />
			    </div>
		    
				<div class="input-field col s6 m6 hidden" id="backleave">
			      <select id="txt_LeaveType" name="txt_LeaveType"  >
	      				<option Selected="True" Value="NA">---Select---</option>
	                    <option>Leave</option>
	                    <!--<option>Half Day</option>-->
			      </select>
			      <label for="txt_LeaveType" class="active-drop-down active"> Leave Type</label>
			    </div>
			    
				<div class="input-field col s6 m6 hidden" id="shif_div1">
				
				  <select id="txt_ShiftIn" name="txt_ShiftIn"  >
						<option Selected="True" Value="NA">---Select---</option>
				        <option>0:00</option>
				        <option>0:30</option>
				        <option>1:00</option>
				        <option>1:30</option>
				        <option>2:00</option>
				        <option>2:30</option>
				        <option>3:00</option>
				        <option>3:30</option>
				        <option>4:00</option>
				        <option>4:30</option>
				        <option>5:00</option>
				        <option>5:30</option>
				        <option>6:00</option>
				        <option>6:30</option>
				        <option>7:00</option>
				        <option>7:30</option>
				        <option>8:00</option>
				        <option>8:30</option>
				        <option>9:00</option>
				        <option>9:30</option>
				        <option>10:00</option>
				        <option>10:30</option>
				        <option>11:00</option>
				        <option>11:30</option>
				        <option>12:00</option>
				        <option>12:30</option>
				        <option>13:00</option>
				        <option>13:30</option>
				        <option>14:00</option>
				        <option>14:30</option>
				        <option>15:00</option>
				        <option>15:30</option>
				        <option>16:00</option>
				        <option>16:30</option>
				        <option>17:00</option>
				        <option>17:30</option>
				        <option>18:00</option>
				        <option>18:30</option>
				        <option>19:00</option>
				        <option>19:30</option>
				        <option>20:00</option>
				        <option>20:30</option>
				        <option>21:00</option>
				        <option>21:30</option>
				        <option>22:00</option>
				        <option>22:30</option>
				        <option>23:00</option>
				        <option>23:30</option>
				        <!--<option>WO</option>-->
				</select>	
				  <label for="txt_ShiftIn" class="active-drop-down active">Shift IN</label>
				</div>

				<div class="input-field col s6 m6 hidden" id="shif_div2">
				 <select id="txt_ShiftOut" name="txt_ShiftOut">
				        <option Selected="True" Value="NA">---Select---</option>
				        <option>0:00</option>
				        <option>0:30</option>
				        <option>1:00</option>
				        <option>1:30</option>
				        <option>2:00</option>
				        <option>2:30</option>
				        <option>3:00</option>
				        <option>3:30</option>
				        <option>4:00</option>
				        <option>4:30</option>
				        <option>5:00</option>
				        <option>5:30</option>
				        <option>6:00</option>
				        <option>6:30</option>
				        <option>7:00</option>
				        <option>7:30</option>
				        <option>8:00</option>
				        <option>8:30</option>
				        <option>9:00</option>
				        <option>9:30</option>
				        <option>10:00</option>
				        <option>10:30</option>
				        <option>11:00</option>
				        <option>11:30</option>
				        <option>12:00</option>
				        <option>12:30</option>
				        <option>13:00</option>
				        <option>13:30</option>
				        <option>14:00</option>
				        <option>14:30</option>
				        <option>15:00</option>
				        <option>15:30</option>
				        <option>16:00</option>
				        <option>16:30</option>
				        <option>17:00</option>
				        <option>17:30</option>
				        <option>18:00</option>
				        <option>18:30</option>
				        <option>19:00</option>
				        <option>19:30</option>
				        <option>20:00</option>
				        <option>20:30</option>
				        <option>21:00</option>
				        <option>21:30</option>
				        <option>22:00</option>
				        <option>22:30</option>
				        <option>23:00</option>
				        <option>23:30</option>
				        <!--<option>WO</option>-->
				</select>
				 <label for="txt_ShiftOut" class="active-drop-down active">Shift Out</label>
				</div>
				
				<div class="input-field col s6 m6 hidden" id="attendance_div1">
			      <select id="txt_curatnd"  name="txt_curatnd"  >
      				<option Selected="True" Value="NA">---Select---</option>
                    <option>A</option>
                    <?php 		
					$myDB=new MysqliDb();
					$rst = $myDB->query('select type_ from roster_temp where EmployeeID = "'.$_SESSION['__user_logid'].'" and DateOn ="'.date('Y-m-d',time()).'" order by id desc limit 1');
					if(count($rst) > 0)
					{
																	
						if(intval($rst[0]['type_']) != 3)
						{
							?>
							<option>H</option>
        					<option>HWP</option>
        					<!--<option>L</option>-->
        					<!--<option>LWP</option>-->
							<?php
						}
					}
					?>
			      </select>
			      <label for="txt_curatnd" class="active-drop-down active">Current Attendance</label>
			    </div>
			    
			    <div class="input-field col s6 m6 hidden" id="attendance_div2">
			      <select id="txt_updateatnd"  name="txt_updateatnd"  >
	      				<option Selected="True" Value="NA">---Select---</option>
			      </select>
			      <label for="txt_updateatnd" class="active-drop-down active"> Need To Update</label>
			    </div>
			    
				<div class="input-field col s12 m12" id="user_div">
			      	<textarea id="txt_Comment" name="txt_Comment" class="materialize-textarea"></textarea>
			        <label for="txt_Comment">Enter Comment</label>
				</div>
				
				<div class="col s12 m12 hidden" id="super_div_hr">
			   	<hr />
			    </div>
			    
				<div class="input-field col s6 m6 hidden" id="super_div1">
			      <select id="txtSupervisorApproval" name="txtSupervisorApproval" <?php echo $readonly_ah;?> >
			      	<option>Pending</option>
			      	<option>Approve</option>
			      	<option>Decline</option>
			      </select>
			      <label for="txtSupervisorApproval" class="active-drop-down active"> Supervisor Approval</label>
			    </div>
			    
			    <div class="input-field col s6 m6 hidden" id="super_div2">
			      <input type="text" id="txtApprovedBy" name="txtApprovedBy" readonly="true" value="<?php echo $AccountHeadName; ?>" />
			      <input type="hidden" id="txtApprovedByID" name="txtApprovedByID" value="<?php echo $AccountHead; ?>" />
			      <label for="txtApprovedBy"> Approved By</label>
			    </div>
			    
			    <div id="super_div" class="input-field col s12 m12 hidden">
			      	<textarea id="txt_Comment_sp" name="txt_Comment_sp" <?php echo $readonly_ah;?> class="materialize-textarea"></textarea>
			      	<label for="txt_Comment_sp"> Enter Comment</label>
				</div>
				
				<div class="col s12 m12 hidden" id="sitehead_hr"><hr /></div>
				
				<div class="input-field col s6 m6 hidden" id="sitehead_div1">
			      <select id="txtSiteHeadApproval" name="txtSiteHeadApproval" <?php echo $readonly_sh;?> >
			        <option>Pending</option>
			      	<option>Approve</option>
			      	<option>Decline</option>
			      </select>
			      <label for="txtSiteHeadApproval" class="active-drop-down active"> Site Head Approval</label>
			    </div>
			    
				<div class="input-field col s6 m6 hidden" id="sitehead_div2">
				 <input type="text" id="txtApprovedBy_sh"  name="txtApprovedBy_sh" readonly="true" value="<?php echo $SiteHeadName; ?>"/>
				 <input type="hidden" id="txtApprovedBy_shID" name="txtApprovedBy_shID" readonly="true" value="<?php echo $SiteHead; ?>"/>
				 <label for="txtApprovedBy_sh"> Approved By</label>
				</div>
			    
			    <div id="sitehead_div" class="input-field col s12 m12 hidden">
			      	<textarea id="txt_Comment_sh" name="txt_Comment_sh" <?php echo $readonly_sh;?> class="materialize-textarea"></textarea>
			      	<label for="txt_Comment_sh"> Enter Comment</label>
				</div>
		
	<div id="comment_box" class="hidden">
		<h4>Comment Box </h4>
		<div>
		<div id="commentSection" style="margin: 1px;">
			<h3 style="border-bottom: 2px solid #7b1510;box-shadow: 2px 3px 5px -1px lightgray;border-right: 2px solid #ce0606;background: #e04a0e;text-align: left;font-size: 1.2em;outline: none;padding: 5px 30px;margin:0px 0px;color: white;">Comments Section</h3>
			<div class="col s12 m12" id="comment_container" style="margin: 0px;">	
			</div>	
		</div>
			<div class="col s12 m12" >
		      <label for="txt_srch_DateFrom">Enter Comment </label>
		      <textarea id="txt_common_comment" name="txt_common_comment" class="materialize-textarea"></textarea>
		      
		    </div>
		</div>
	</div>
	
	<div class="input-field col s12 m12 right-align">
	     <button type="submit" name="btn_Leave_Add" id="btn_Leave_Add" class="btn waves-effect waves-green <?php echo $btnAdd;?>"> Raise Request</button>

	</div>

<?php 
$roster_type_tempp=9;
$myDB=new MysqliDb();
$rst = $myDB->query('select type_ from roster_temp where EmployeeID = "'.$_SESSION['__user_logid'].'" and DateOn ="'.date('Y-m-d',time()).'" order by id desc limit 1');
$mysql_error = $myDB->getLastError();
if(count($rst) > 0 && $rst)
{
	//var_dump($rst);
	if(intval($rst[0]['type_']) == 2)
	{
		$roster_type_tempp = '11';
	}
	else if(intval($rst[0]['type_']) == 3)
	{
		$roster_type_tempp = '5';
	}
	else
	{
		$roster_type_tempp = '9';
	}
}
?>
<input  type="hidden" value="<?php echo $roster_type_tempp;?>" name="txtShifDiff" id="txtShifDiff"/>
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
	
	$('input[type="text"]').click(function(){
		$(this).removeClass('has-error');	       
	});
	
	$('select,textarea').click(function(){
		$(this).removeClass('has-error');	       
	});
	
	
	$('#btn_Leave_Add,#btn_Leave_Save').click(function(){
		var validate=0;
		var alert_msg='';	
		$('#txt_DateFrom').removeAttr('disabled');
		$('#txt_DateTo').removeAttr('disabled');	       
		$('#txt_Request').removeClass('has-error');	
		$('#txt_DateFrom').removeClass('has-error');	
		$('#txt_DateTo').removeClass('has-error');	
		$('#txt_Comment').removeClass('has-error');

		$('#txt_LeaveType').removeClass('has-error');	
		$('#txt_ShiftIn').removeClass('has-error');	
		$('#txt_ShiftOut').removeClass('has-error');	

		$('#txt_curatnd').removeClass('has-error');	
		$('#txt_updateatnd').removeClass('has-error');	
		if($(this).attr('id') == 'btn_Leave_Save')
		{

		if($('#txtID').val()=='')
		{					
			validate=1;
			alert_msg+='<li> Request ID can not be Empty ,Please Select First </li>';
		}
		}
		if($('#txt_Request').val()=='NA')
		{
			$('#txt_Request').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
			validate=1;
			if($('#stxt_Request').size() == 0)
			{
			   $('<span id="stxt_Request" class="help-block">Request Exception can not be Empty.</span>').insertAfter('#txt_Request');
			}
		}
			else
			{
				if($('#txt_Request').val() == 'Back Dated Leave')
				{
				if($('#txt_LeaveType').val()=='NA')
			    {
			    	$('#txt_LeaveType').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
					validate=1;
					if($('#stxt_LeaveType').size() == 0)
					{
					   $('<span id="stxt_LeaveType" class="help-block">LeaveType can not be Empty.</span>').insertAfter('#txt_LeaveType');
					}
			    	
				}
					
				}
				else if($('#txt_Request').val() == 'Roster Change' || $('#txt_Request').val() == 'Shift Change' || $('#txt_Request').val() == 'Working on WeekOff' || $('#txt_Request').val() == 'Working on Leave')
				{
					if($('#txt_ShiftIn').val()=='NA')
				    {
						$('#txt_ShiftIn').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
						validate=1;
						if($('#stxt_ShiftIn').size() == 0)
						{
						   $('<span id="stxt_ShiftIn" class="help-block">Shift IN can not be Empty.</span>').insertAfter('#txt_ShiftIn');
						}
					}
					
				}
				else if($('#txt_Request').val() == 'Biometric issue')
				{
					if($('#txt_curatnd').val()=='NA' || $('#txt_curatnd').val()==null || $('#txt_curatnd').val()== undefined || $('#txt_curatnd').val()== '')
				    {
						$('#txt_curatnd').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
						validate=1;
						if($('#stxt_curatnd').size() == 0)
						{
						   $('<span id="stxt_curatnd" class="help-block">Current Attendance can not be Empty.</span>').insertAfter('#txt_curatnd');
						}
						
					}
					if($('#txt_updateatnd').val()=='NA'  || $('#txt_updateatnd').val()==null || $('#txt_updateatnd').val()== undefined || $('#txt_curatnd').val()== '')
				    {
						$('#txt_updateatnd').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
						validate=1;
						if($('#stxt_updateatnd').size() == 0)
						{
						   $('<span id="stxt_updateatnd" class="help-block">Updated Attendance can not be Empty.</span>').insertAfter('#txt_updateatnd');
						}
					}
				}
			}
		if($('#txt_DateFrom').val()=='')
		{
			$('#txt_DateFrom').addClass('has-error');
			validate=1;
			if($('#stxt_DateFrom').size() == 0)
			{
			   $('<span id="stxt_DateFrom" class="help-block">DateFrom can not be Empt.</span>').insertAfter('#txt_DateFrom');
			}
		}
		if($('#txt_DateTo').val()=='')
		{
			$('#txt_DateTo').addClass('has-error');
			validate=1;
			if($('#stxt_DateTo').size() == 0)
			{
			   $('<span id="stxt_DateTo" class="help-block">DateTo can not be Empty.</span>').insertAfter('#txt_DateTo');
			}
			
		}
		if(Date.parse($('#txt_DateTo').val()) < Date.parse($('#txt_DateFrom').val()))
		{
			$('#txt_DateFrom').addClass('has-error');
			$('#txt_DateTo').addClass('has-error');
			validate=1;
			$('<span id="stxt_DateFrom" class="help-block">DateTo can not be less then DateFrom Empty .</span>').insertAfter('#txt_DateFrom');
			$('<span id="stxt_DateTo" class="help-block">DateTo can not be less then DateFrom Empty .</span>').insertAfter('#txt_DateTo');
			
		}
		if($('input.check_val_:checked').length > 0)
		{
			validate = 0;
			alert_msg ='';
		}
		if($(this).attr('id') != 'btn_Leave_Save')
		{
			if($('#txt_Comment').val()=='')
			{
				$('#txt_Comment').addClass('has-error');
				validate=1;
				if($('#stxt_Comment').size() == 0)
				{
				   $('<span id="stxt_Comment" class="help-block">Comment can not be Empty.</span>').insertAfter('#txt_Comment');
				}
			}
		}
		else
		{
			if($('#txt_common_comment').val()=='')
			{
				$('#txt_common_comment').addClass('has-error');
				$('#comment_box,#commentSection').accordion({
				      		  collapsible: true,
						      heightStyle: "content" ,
						      active : 0
				    });
				validate=1;
				if($('#stxt_common_comment').size() == 0)
				{
				   $('<span id="stxt_common_comment" class="help-block">Comment can not be Empty.</span>').insertAfter('#txt_common_comment');
				}
			}
		}
		if(validate==1)
		{
			return false;
		}
	});
	
	
	$( "form" ).submit(function(){
	var validate=0;
	var alert_msg='';
	var $btn = $(document.activeElement);
	if($btn.is("#btn_srch_submit"))
	{
	   return true;
	}
	$('#txt_Request').removeClass('has-error');	
	$('#txt_DateFrom').removeClass('has-error');	
	$('#txt_DateTo').removeClass('has-error');	
	$('#txt_Comment').removeClass('has-error');

	$('#txt_LeaveType').removeClass('has-error');	
	$('#txt_ShiftIn').removeClass('has-error');	
	$('#txt_ShiftOut').removeClass('has-error');	

	$('#txt_curatnd').removeClass('has-error');	
	$('#txt_updateatnd').removeClass('has-error');	
	if($(this).attr('id') == 'btn_Leave_Save')
	{

	if($('#txtID').val()=='')
	{					
		validate=1;
		alert_msg+='<li> Request ID can not be Empty ,Please Select First </li>';
	}
	}
	if($('#txt_Request').val()=='NA')
	{
		$('#txt_Request').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
		validate=1;
		if($('#stxt_Request').size() == 0)
		{
		   $('<span id="stxt_Request" class="help-block">Request Exception can not be Empty.</span>').insertAfter('#txt_Request');
		}
	}
	else
	{
	if($('#txt_Request').val() == 'Back Dated Leave')
	{
		if($('#txt_LeaveType').val()=='NA')
	    {
			$('#txt_LeaveType').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
			validate=1;
			if($('#stxt_LeaveType').size() == 0)
			{
			   $('<span id="stxt_LeaveType" class="help-block">LeaveType can not be Empty.</span>').insertAfter('#txt_LeaveType');
			}
		}
		
	}
	else if($('#txt_Request').val() == 'Roster Change' || $('#txt_Request').val() == 'Shift Change'  || $('#txt_Request').val() == 'Working on WeekOff' || $('#txt_Request').val() == 'Working on Leave')
	{
		if($('#txt_ShiftIn').val()=='NA')
	    {
			$('#txt_ShiftIn').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
			validate=1;
			if($('#stxt_ShiftIn').size() == 0)
			{
			   $('<span id="stxt_ShiftIn" class="help-block">Shift IN can not be Empty.</span>').insertAfter('#txt_ShiftIn');
			}
		}
		if($('#txt_ShiftOut').val()=='NA')
	    {
			$('#txt_ShiftOut').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
			validate=1;
			if($('#stxt_ShiftOut').size() == 0)
			{
			   $('<span id="stxt_ShiftOut" class="help-block">Shift Out can not be Empty.</span>').insertAfter('#txt_ShiftOut');
			}
		}
	}
	else if($('#txt_Request').val() == 'Biometric issue')
	{
		if($('#txt_curatnd').val()=='NA' || $('#txt_curatnd').val()==null || $('#txt_curatnd').val()== undefined || $('#txt_curatnd').val()== '')
	    {
			$('#txt_curatnd').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
			validate=1;
			if($('#stxt_curatnd').size() == 0)
			{
			   $('<span id="stxt_curatnd" class="help-block">Current Attendance can not be Empty.</span>').insertAfter('#txt_curatnd');
			}

		}
		if($('#txt_updateatnd').val()=='NA'  || $('#txt_updateatnd').val()==null || $('#txt_updateatnd').val()== undefined || $('#txt_curatnd').val()== '')
	    {
			$('#txt_updateatnd').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
			validate=1;
			if($('#stxt_updateatnd').size() == 0)
			{
			   $('<span id="stxt_updateatnd" class="help-block">Updated Attendance can not be Empty.</span>').insertAfter('#txt_updateatnd');
			}
		}
	}
	}
	if($('#txt_DateFrom').val()=='')
	{
		$('#txt_DateFrom').addClass('has-error');
		validate=1;
		if($('#stxt_DateFrom').size() == 0)
			{
			   $('<span id="stxt_DateFrom" class="help-block">DateFrom can not be Empty.</span>').insertAfter('#txt_DateFrom');
			}
	}
	if($('#txt_DateTo').val()=='')
	{
		$('#txt_DateTo').addClass('has-error');
		validate=1;
		if($('#stxt_DateTo').size() == 0)
			{
			   $('<span id="stxt_DateTo" class="help-block">DateTo can not be Empty.</span>').insertAfter('#txt_DateTo');
			}
	}

	if(Date.parse($('#txt_DateTo').val()) < Date.parse($('#txt_DateFrom').val()))
	{
		$('#txt_DateFrom').addClass('has-error');
		$('#txt_DateTo').addClass('has-error');
		validate=1;
		$('<span id="stxt_DateFrom" class="help-block">DateTo can not be less then DateFrom Empty.</span>').insertAfter('#txt_DateFrom');
		$('<span id="stxt_DateTo" class="help-block">DateTo can not be less then DateFrom Empty.</span>').insertAfter('#txt_DateTo');
	}
	if($('input.check_val_:checked').length > 0)
	{
		validate = 0;
		alert_msg ='';
	}

	if(validate==1)
	{		      		
		return false;
	}
	else
	{
		$('#btn_Leave_Add,#btn_Leave_Save').addClass('hidden');
	}		
	});
	
$('#search_field,#comment_box,#commentSection').accordion({
  collapsible: true,
  heightStyle: "content" 
});
	
$('#txt_ShiftIn').change(function(){
	
	$.ajax({url: "../Controller/getRosterType.php?EmpID="+$('#txtEmpID').val() + '&Date='+$('#txt_DateFrom').val(), success: function(result21){	
					                          
	    if(result21 == 2)
		{
			$('#txtShifDiff').val('11');
		} 
		else if(result21 == 3)
		{
			$('#txtShifDiff').val('5');
		}             
		else
		{
			$('#txtShifDiff').val('9');
		}
		$('select').formSelect();
	}
	});
	if($("#txt_Request").val() == 'Roster Change' || $("#txt_Request").val() == 'Shift Change')
	{
	 $.ajax({url: "../Controller/getRosterData.php?EmpID="+$('#txtEmpID').val() + '&Date='+$('#txt_DateFrom').val(), success: function(result){
	 	var d2 = new Date($('#txt_DateFrom').val()+' '+result);
		var d1 = new Date($('#txt_DateFrom').val()+' '+$('#txt_ShiftIn').val());
	 	 //alert($('#txt_DateFrom').val());

		 var seconds =  Math.abs((d2- d1)/1000);
	 	if(parseInt(seconds) < 7200 || $('#txt_DateFrom').val() == '')
	 	{
	 		alert('Requested Shift should have difference of two hour from roster shift');
			//$('#txt_ShiftIn').val(result);
			$('#txt_ShiftIn').val('NA');
			$('#txt_ShiftOut').val('NA');
			
		}
		    if($('#txt_ShiftIn').val() == "WO")
	        {
	            $("#txt_ShiftOut").val("WO");
	            //ddlShiftOut.Enabled = false;
	        }
	        else if($("#txt_ShiftIn option:selected").index() == 0)
	        {
	            $("#txt_ShiftOut option:selected").index(0);
	        }

	        else if ($("#txt_ShiftIn option:selected").index() != 0)
	        {
	        	if($('#txt_ShiftIn').val() =="00:00")
	        	{
					$('#txt_ShiftIn').val("00:01");
				}
				
	            var time = $('#txt_ShiftIn').val();
				var startTime = new Date();
				var parts = time.match(/(\d+):(\d+)/);
				if (parts) {
				    var hours = parseInt(parts[1]),
				        minutes = parseInt(parts[2])
				  
				    startTime.setHours(hours, minutes, 0, 0);
				}
				
				startTime.setHours(startTime.getHours() + parseInt($('#txtShifDiff').val()), startTime.getMinutes(),0,0);
				
				var minute ='00';
				if(startTime.getMinutes() < 10)
				{
					minute = '0' +startTime.getMinutes();
				} 
				else
				{
					minute =startTime.getMinutes();
				}
				//alert(startTime.getHours() + ':' + minute);
			    $('#txt_ShiftOut').val(startTime.getHours() + ':' + minute);
			    
			    
	        }
	        $('select').formSelect();
	 }});

	}
	else
	{
	if($('#txt_ShiftIn').val() == "WO")
	{
	    $("#txt_ShiftOut").val("WO");
	    //ddlShiftOut.Enabled = false;
	}
	else if($("#txt_ShiftIn option:selected").index() == 0)
	{
	    $("#txt_ShiftOut option:selected").index(0);
	}

	else if ($("#txt_ShiftIn option:selected").index() != 0)
	{
	if($('#txt_ShiftIn').val() =="00:00")
	{
			$('#txt_ShiftIn').val("00:01");
		}
		
	    var time = $('#txt_ShiftIn').val();
		var startTime = new Date();
		var parts = time.match(/(\d+):(\d+)/);
		if (parts) {
		    var hours = parseInt(parts[1]),
		        minutes = parseInt(parts[2])
		  
		    startTime.setHours(hours, minutes, 0, 0);
		}
		
		startTime.setHours(startTime.getHours() + parseInt($('#txtShifDiff').val()), startTime.getMinutes(),0,0);
		
		var minute ='00';
		if(startTime.getMinutes() < 10)
		{
			minute = '0' +startTime.getMinutes();
		} 
		else
		{
			minute =startTime.getMinutes();
		}
		//alert(startTime.getHours() + ':' + minute);
	    $('#txt_ShiftOut').val(startTime.getHours() + ':' + minute);
	    
	    
	}
	}

});

$('#txt_Request').change(function(){
	$("#app_link").html('');
	$('#backleave').addClass('hidden');
	$('#shif_div1').addClass('hidden');
	$('#shif_div2').addClass('hidden');
	$('#attendance_div1').addClass('hidden');
	$('#attendance_div2').addClass('hidden');

	$('#super_div_hr').addClass('hidden');
	$('#super_div1').addClass('hidden');
	$('#super_div2').addClass('hidden');
	$('#super_div').addClass('hidden');
	$('#sitehead_hr').addClass('hidden');
	$('#sitehead_div1').addClass('hidden');
	$('#sitehead_div2').addClass('hidden');
	$('#sitehead_div').addClass('hidden');	

	$('#txt_DateFrom').val('');
	$('#txt_DateTo').val('');
	$('#txt_LeaveType').val('NA');
	$('#txt_ShiftIn').val('NA');
	$('#txt_ShiftOut').val('NA');
	$('#txt_curatnd').val('NA');
	$('#txt_updateatnd').val('NA');
	$('#txt_Comment').val('');
	$('#txt_Comment_sh').val('');
	$('#txt_Comment_sp').val('');


	$('#user_div').removeClass('hidden');
	$('#txtEmpName').val($('#txtEmpName1').val()); 
	$('#txtEmpID').val($('#txtEmpID1').val());
	$('#txt_DateFrom').attr('disabled','true');
	$('#txt_DateTo').attr('disabled','true');


	if($('#txtID').val() != '')
	{
		$('#user_div').addClass('hidden');
		$('#sitehead_div').addClass('hidden');
		$('#super_div').addClass('hidden');
	}
	if($(this).val() == 'Back Dated Leave')
	{
		$('#backleave').removeClass('hidden');
		
	}
	else if($(this).val() == 'Roster Change' || $(this).val() == 'Shift Change' ||$(this).val() == 'Working on WeekOff' || $(this).val() == 'Working on Holiday' ||$(this).val() == 'Working on Leave' )
	{
		$('#shif_div1').removeClass('hidden');
		$('#shif_div2').removeClass('hidden');
	}
	else if($(this).val() == 'Biometric issue')
	{
		$('#attendance_div1').removeClass('hidden');
		$('#attendance_div2').removeClass('hidden');
	}
	$("#txt_DateFrom").removeAttr('disabled');
	$('#txt_DateTo,#txt_DateFrom').datepicker({minDate: '-2D',maxDate: '-1D',dateFormat:'yy-mm-dd',onSelect: function (dateStr) 
		{
			

			$("#txt_DateTo").val($("#txt_DateFrom").val());
		  	$("#txt_DateTo").attr('disabled',true);
		}
	});
});
		
$('#txt_curatnd').change(function(){
	$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option>');
	if($(this).val() == 'H')
	{
		$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>P</option>');
	}
	else if($(this).val() == 'L')
	{
		$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>H</option><option>P</option>');
	}
	else if($(this).val() == 'LWP')
	{
		$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>H</option><option>P</option>');
	}
	else if($(this).val() == 'A')
	{
		<?php 
										
			$myDB=new MysqliDb();
			$rst = $myDB->query('select type_ from roster_temp where EmployeeID = "'.$_SESSION['__user_logid'].'" and DateOn ="'.date('Y-m-d',time()).'" order by id desc limit 1');
			if(count($rst) > 0)
			{
															
				if(intval($rst[0]['type_']) != 3)
				{
					?>
					$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>H</option><option>P</option>');
					<?php
				}
				else
				{
				?>
				$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>P</option>');
				<?php
				}
				
			}else
			{
				?>
				$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>P</option>');
				<?php
			}
		?>
		
		
	}
	else if($(this).val() == 'HWP')
	{
		$('#txt_updateatnd').empty().append('<option value="NA">---Select---</option><option>P</option>');
	}
	
});
getCalforsrch();

});
function getCalforsrch() {

var currentTime = new Date();
var minDate = '-2M';
var maxDate = new Date(currentTime.getFullYear(), currentTime.getMonth() + 2, 0); // one day before next month
var firstDay = new Date(currentTime.getFullYear(), currentTime.getMonth(), 1);
var lastDay = new Date(currentTime.getFullYear(), currentTime.getMonth() + 1, 0);
function getFormattedDate(date) {
var year = date.getFullYear();
var month = (1 + date.getMonth()).toString();
month = month.length > 1 ? month : '0' + month;
var day = date.getDate().toString();
day = day.length > 1 ? day : '0' + day;
return month + '/' + day + '/' + year;

}

$("#txt_srch_DateTo").datepicker({
minDate: minDate,
maxDate: maxDate,
dateFormat:'yy-mm-dd',

onSelect: function (dateStr) {
          var max = $(this).datepicker('getDate'); // Get selected date
          var start = $("#txt_srch_DateFrom").datepicker("getDate");
          var end = $("#txt_srch_DateTo").datepicker("getDate");
          
          if (start != null ) {
              var days = (end - start) / (1000 * 60 * 60 * 24);
              //$("#ctl00_ContentPlaceHolder1_txtdays").val(days + 1);

              

              if (days < 0) {

                  alert("To Date should be greater then From Date");
                  $("#txt_srch_DateTo").val('');
                  return false;
              }
          }
          else {
              alert("Select From Date First...");
              $("#txt_srch_DateTo").val('');
          }

      }
});
$("#txt_srch_DateFrom").datepicker({
minDate: minDate,
maxDate: maxDate,
dateFormat:'yy-mm-dd'
});
}

function bindate(el) {
    //alert(el);
  $('#txt_DateFrom').datepicker("destroy");   
  $('#txt_DateTo').datepicker("destroy");   
  $('#txt_DateFrom').prop('disabled',false);
  $('#txt_DateTo').prop('disabled', false);
  $('#txt_DateFrom').attr('readonly', 'true'); 
  $('#txt_DateTo').attr('readonly', 'true');
  var txtDays ='';
  //$('#ctl00_ContentPlaceHolder1_txtsdate').removeAttr('disabled');
  //$('#ctl00_ContentPlaceHolder1_txtedate').removeAttr('disabled');
  
//              $("#ctl00_ContentPlaceHolder1_txtsdate").val()='';
//              $("#ctl00_ContentPlaceHolder1_txtedate").val()='';
  var minDate='-0D';
  var maxDate='+0D';
  if (el == 'Back Dated Leave' || el == 'Biometric issue')
  {
      var dt = new Date();
      
      if (dt.getDate() > 2) {
          //alert(date.getMonth()+'/'+date.getDate()+'/'+date.getYear());
          //alert(dt);
          var mm = dt.getMonth();
          mm = mm + 1;
          
          minDate = '0'+ mm + '/' + '1' + '/' + dt.getFullYear();
          maxDate = '-1D';
         // alert(minDate);
          

      }
      else {
          minDate='-2D';
          maxDate = '-1D';
      }

  }
  else if (el == 'Working on Holiday' || el == 'Working on WeekOff' ) {
      var dt = new Date();

      if (dt.getDate() > 2)
      {
          var mm = dt.getMonth();
          mm = mm + 1;

          minDate = '0' + mm + '/' + '1' + '/' + dt.getFullYear();
          maxDate = '-0D';
      }
      else 
      {
          minDate = '-2D';
          maxDate = '-0D';
      }
  }
  else if(el=='Roster Change')
  {
        minDate='+1D';
        maxDate = '+10D';
        
  }
  else if(el=='Shift Change')
  {
        minDate='+1D';
        maxDate = '-0D';
       
  }

  else if(el == 'NA' || el == 0) {
    $('#txt_DateFrom').attr('disabled','true');
    $('#txt_DateTo').attr('disabled', 'true');
   
    
  }
  if(el=='Shift Change')
  {
        minDate='+1D';
        maxDate = '-0D';
       
  }
  else
  {
  	var minDate='+1D';
  	var maxDate='-1D';

  }
 

  $('#txt_DateTo').datepicker({
      
      minDate: minDate,
      //maxDate: '+0Y+2M',
      maxDate: maxDate,
  dateFormat:'yy-mm-dd',
      
      onSelect: function (dateStr) {
          var max = $('#txt_DateTo').datepicker('getDate'); // Get selected date
          var start = $("#txt_DateFrom").datepicker("getDate");
          var end = $("#txt_DateTo").datepicker("getDate");
          
          if (start != null ) {
              var days = (end - start) / (1000 * 60 * 60 * 24);
             txtDays =days + 1;

              

              if (days < 0) {

                  alert("To Date should be greater then From Date");
                  $("#txt_DateTo").val('');
                  return false;
              }
          }
          else {
              alert("Select From Date First...");
              $("#txt_DateTo").val('');
          }
		  
      }
  });
  
  $('#txt_DateFrom').datepicker({
      minDate: minDate,
      maxDate: maxDate,
	  dateFormat:'yy-mm-dd'
  });
  
}

function binddate1(eldate)
{
  $('#txt_DateFrom').attr('readonly', 'true');
  $('#txt_DateTo').attr('readonly', 'true');
  $('#txt_DateFrom').datepicker("destroy");   
  $('#txt_DateTo').datepicker("destroy"); 
  var availableDates = eldate.split(',');
  //alert($.inArray('6/26/2016', availableDates));
  
  function available(date) {
  	 
      var d = date.getDate();
      var m = (date.getMonth() + 1);
      var y = date.getFullYear();
      if(d <= 9 )
      {
	  	d = '0'+d;
	  }
	  if(m <= 9 )
      {
	  	m = '0'+ m;
	  }
      dmy =  y + '-' + m + '-' + d;
     
      if ($.inArray(dmy, availableDates) != -1) {
          return [true, "", "Available"];
      } else {
          return [false, "", "unAvailable"];
      }
  }
  
   $('#txt_DateTo,#txt_DateFrom').datepicker({ beforeShowDay: available,
          minDate: '-30D',
          maxDate: '+7D',
   		  dateFormat:'yy-mm-dd',
   		  onSelect: function (dateStr) {
   		  	
   		  		$.ajax({url: "../Controller/getRosterType.php?EmpID="+$('#txtEmpID').val() + '&Date='+dateStr, success: function(result){
	                          
		            if(result == 2)
					{
						$('#txtShifDiff').val('11');
					}              
					else
					{
						$('#txtShifDiff').val('9');
					}             
		        }});
		          if($('#txt_Request').val() == 'Biometric issue' || $('#txt_Request').val() == 'Working on Leave')
	//|| $('#txt_Request').val() == 'Shift Change' || $('#txt_Request').val() == 'Working on WeekOff'//
	              {
				  	 $("#txt_DateTo").attr('disabled',true);
				  	 $("#txt_DateTo").val($("#txt_DateFrom").val());
				  }
				  else
				  {
				  	$("#txt_DateTo").removeAttr('disabled');
				  }
   		  }
      });
}
	
function EditData(el)
{
$('#comment_box').addClass('hidden');
$('#btn_Leave_Add').addClass('hidden');
$('#btn_Leave_Save').addClass('hidden');
$item=$(el);
$.ajax({url: "../Controller/getComment.php?ID="+$item.attr("Data-ID"), success: function(result){
                     
if(result != '')
{

	$('#comment_box').removeClass('hidden');
	$('#comment_container').empty().append(result);
	$('#comment_box,#commentSection').accordion({
      collapsible: true,
      heightStyle: "content" 
    });
}                             
}});
$('#user_div').addClass('hidden');
$('#txtID').val($item.attr("Data-ID"));
$.ajax({url: "../Controller/getDataForRequest.php?ID="+$item.attr("Data-ID"), success: function(result){
                     
if(result != '')
{

	var Data  = result.split('|$|');
	
    
    var Exception = Data[2];
    var DateFrom =Data[3];
    var DateTo =Data[4];
    var EmployeeID =Data[1];
    var EmployeeName =Data[10];
    var AccountHead =Data[16];
    var AccountHeadName = Data[17];
    var CenterHead ='CE03070003';
    var CenterHeadName ='SACHIN SIWACH';
    var MgnrStatus =Data[5];
    var HeadStatus =Data[6];
    var StatusHead = Data[12];
    var LeaveType =Data[23];
    var ShiftIn =Data[21];
    var ShiftOut =Data[22];
    var CurrentAtnd =Data[19];
    var UpdateAtnd =Data[20];
    var Mobile   = Data[14]+' / '+Data[15];
    var ReportTo = Data[24];
    if(Exception != '')
    {
    	
		$('.clsIDHome').removeClass('hidden');
    	
		$('#txt_Request').val(Exception).attr('readonly','true');//.trigger('change');
		$('#backleave').addClass('hidden');
		$('#shif_div1').addClass('hidden');
		$('#shif_div2').addClass('hidden');
		$('#attendance_div1').addClass('hidden');
		$('#attendance_div2').addClass('hidden');
		
		$('#super_div_hr').addClass('hidden');
		$('#super_div1').addClass('hidden');
		$('#super_div2').addClass('hidden');
		$('#super_div').addClass('hidden');
		$('#sitehead_hr').addClass('hidden');
		$('#sitehead_div1').addClass('hidden');
		$('#sitehead_div2').addClass('hidden');
		$('#sitehead_div').addClass('hidden');	
		
		$('#txt_DateFrom').val('');
		$('#txt_DateTo').val('');
		$('#txt_LeaveType').val('NA');
		$('#txt_ShiftIn').val('NA');
		$('#txt_ShiftOut').val('NA');
		$('#txt_curatnd').val('NA');
		$('#txt_updateatnd').val('NA');
		$('#txt_Comment').val('');
		$('#txt_Comment_sh').val('');
		$('#txt_Comment_sp').val('');
		
		
		$('#user_div').removeClass('hidden');
		$('#txt_DateFrom').attr('disabled','true');
		$('#txt_DateTo').attr('disabled','true');
		
		
		if($('#txtID').val() != '')
		{
			$('#user_div').addClass('hidden');
			$('#sitehead_div').addClass('hidden');
			$('#super_div').addClass('hidden');
		}
		if(Exception == 'Back Dated Leave')
		{
			$('#backleave').removeClass('hidden');
			
		}
		else if(Exception == 'Roster Change' || Exception == 'Shift Change')
		{
			$('#shif_div1').removeClass('hidden');
			$('#shif_div2').removeClass('hidden');
		}
		else if(Exception == 'Biometric issue')
		{
			$('#attendance_div1').removeClass('hidden');
			$('#attendance_div2').removeClass('hidden');
		}
		$('#txtEmpID').val(EmployeeID);
		$('#super_div_hr').removeClass('hidden');
		$('#super_div1').removeClass('hidden');
		$('#super_div2').removeClass('hidden');
		$('#super_div').addClass('hidden');
		$('#sitehead_hr').removeClass('hidden');
		$('#sitehead_div1').removeClass('hidden');
		$('#sitehead_div2').removeClass('hidden');
		$('#sitehead_div').addClass('hidden');	
		
		$('#user_div').addClass('hidden');
		var DatFrom = DateFrom.split(' ');
		var DatTo = DateTo.split(' ');
		$('#txt_DateFrom').val(DatFrom[0]);
		$('#txt_DateTo').val(DatTo[0]);
		
		if(Exception == 'Back Dated Leave')
		{
			$('#backleave').removeClass('hidden');
			$('#txt_LeaveType').val(LeaveType);
			if(MgnrStatus != 'Pending')
			{
				$('#txt_LeaveType').attr('readonly','true');
			}
			else
			{
				$('#txt_LeaveType').removeAttr('readonly');
			}
		}
		else if(Exception == 'Roster Change' || Exception == 'Shift Change'||Exception == 'Working on WeekOff' || Exception == 'Working on Holiday' ||Exception == 'Working on Leave' )
		{
			$('#shif_div1').removeClass('hidden');
			$('#shif_div2').removeClass('hidden');
			$('#txt_ShiftIn').val(ShiftIn);
			$('#txt_ShiftOut').val(ShiftOut);
			if(MgnrStatus != 'Pending')
			{
				$('#txt_ShiftIn').attr('readonly','true');
			}
			else
			{
				$('#txt_ShiftIn').removeAttr('readonly');
				
			}
			
		}
		else if(Exception == 'Biometric issue')
		{
			$('#attendance_div1').removeClass('hidden');
			$('#attendance_div2').removeClass('hidden');
			$('#txt_curatnd').val(CurrentAtnd).trigger('change');
		    $('#txt_updateatnd').val(UpdateAtnd);
		    
		    if(MgnrStatus != 'Pending')
			{
				
				$('#txt_curatnd').attr('readonly','true');
		    	$('#txt_updateatnd').attr('readonly','true');
			}
			else
			{
				$('#txt_curatnd').removeAttr('readonly');
		    	$('#txt_updateatnd').removeAttr('readonly');
			}
		}
		
		
		if($('#txtEmpID').val() == $('#txtEmpID1').val() && $('#txtEmpID').val()!=$('#txtApprovedByID').val() )
		{
			
			if(MgnrStatus == 'Pending')
			{
				$('#super_div_hr').addClass('hidden');
				$('#super_div1').addClass('hidden');
				$('#super_div2').addClass('hidden');
				$('#super_div').addClass('hidden');
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');	
				$('#txt_Request').removeAttr('readonly','true');
				$('#txt_DateFrom').removeAttr('disabled','true');
				$('#txt_DateTo').removeAttr('disabled','true');
				
				$('#txtApprovedBy').val(AccountHeadName);
				$('#txtSupervisorApproval').val(MgnrStatus);							
				$('#txtSiteHeadApproval').val(HeadStatus);
				$('#btn_Leave_Save').removeClass('hidden');
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
			}
			else if(MgnrStatus != 'Pending' && HeadStatus =='Pending' && CenterHead == <?php echo '"'.$_SESSION['__user_logid'].'"';?>)
			{
				$("#app_link").html('<a href="view_BioMetric_one.php?p_EmpID='+EmployeeID+'&date='+DatFrom[0]+'" target="_blank"> Check Biometric and Roster</a>');
				$('#txt_Request').attr('readonly','true');
				$('#txt_DateFrom').attr('disabled','true');
				$('#txt_DateTo').attr('disabled','true');
				$('#txtApprovedBy').val(AccountHeadName);
				$('#txtSupervisorApproval').val(MgnrStatus);							
				$('#txtSiteHeadApproval').val(HeadStatus);
				$('#btn_Leave_Save').removeClass('hidden');
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
				
			}
			else if(MgnrStatus != 'Pending' && HeadStatus =='Pending')
			{
				$("#app_link").html('<a href="view_BioMetric_one.php?p_EmpID='+EmployeeID+'&date='+DatFrom[0]+'" target="_blank"> Check Biometric and Roster</a>');
				$('#txt_Request').attr('readonly','true');
				$('#txt_DateFrom').attr('disabled','true');
				$('#txt_DateTo').attr('disabled','true');
				$('#txtApprovedBy').val(AccountHeadName);
				$('#txtSupervisorApproval').val(MgnrStatus);							
				$('#txtSiteHeadApproval').val(HeadStatus);
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
			}
			else if(MgnrStatus != 'Pending' && HeadStatus =='Pending')
			{
				$("#app_link").html('<a href="view_BioMetric_one.php?p_EmpID='+EmployeeID+'&date='+DatFrom[0]+'" target="_blank"> Check Biometric and Roster</a>');
				$('#txt_Request').attr('readonly','true');
				$('#txt_DateFrom').attr('disabled','true');
				$('#txt_DateTo').attr('disabled','true');
				$('#txtApprovedBy').val(AccountHeadName);
				$('#txtSupervisorApproval').val(MgnrStatus);							
				$('#txtSiteHeadApproval').val(HeadStatus);
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
			}
			else if(MgnrStatus != 'Pending' && HeadStatus !='Pending' && AccountHead == EmployeeID)
			{
				$("#app_link").html('<a href="view_BioMetric_one.php?p_EmpID='+EmployeeID+'&date='+DatFrom[0]+'" target="_blank"> Check Biometric and Roster</a>');
				$('#txt_Request').attr('readonly','true');
				$('#txt_DateFrom').attr('disabled','true');
				$('#txt_DateTo').attr('disabled','true');
				$('#txtApprovedBy').val(StatusHead);
				$('#txtApprovedByID').val(ReportTo);
				$('#txtSupervisorApproval').val(MgnrStatus);							
				$('#txtSiteHeadApproval').val(HeadStatus);
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
			}
			else if(MgnrStatus != 'Pending' && HeadStatus !='Pending')
			{
				$("#app_link").html('<a href="view_BioMetric_one.php?p_EmpID='+EmployeeID+'&date='+DatFrom[0]+'" target="_blank"> Check Biometric and Roster</a>');
				$('#txt_Request').attr('readonly','true');
				$('#txt_DateFrom').attr('disabled','true');
				$('#txt_DateTo').attr('disabled','true');
				$('#txtApprovedBy').val(AccountHeadName);
				$('#txtSupervisorApproval').val(MgnrStatus);							
				$('#txtSiteHeadApproval').val(HeadStatus);
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
			}
			
			
		}
		else
		{
			
			$('#txtEmpName').val(EmployeeName+'  ( CAll :- '+ Mobile +')' ).css('min-width','70%');
			if(MgnrStatus == 'Pending' && ((AccountHead ==<?php echo '"'.$_SESSION['__user_logid'].'"';?> && AccountHead != $('#txtEmpID').val()) ||( AccountHead == $('#txtEmpID').val() && ReportTo == <?php echo '"'.$_SESSION['__user_logid'].'"';?>)))
			{
				$("#app_link").html('<a href="view_BioMetric_one.php?p_EmpID='+EmployeeID+'&date='+DatFrom[0]+'" target="_blank"> Check Biometric and Roster</a>');
				
				$('#super_div_hr').removeClass('hidden');
				$('#super_div1').removeClass('hidden');
				$('#super_div2').removeClass('hidden');
				$('#super_div').addClass('hidden');
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');	
				$('#txtApprovedBy').val(StatusHead);
				$('#txtApprovedByID').val(ReportTo);	
				$('#txtSupervisorApproval').val(MgnrStatus).removeAttr('readonly');							
				$('#txtSiteHeadApproval').val(HeadStatus);
				$('#btn_Leave_Save').removeClass('hidden');
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
			}
			else if(MgnrStatus == 'Pending' && (AccountHead ==<?php echo '"'.$_SESSION['__user_logid'].'"';?> && AccountHead == $('#txtEmpID').val()))
			{
				$('#super_div_hr').addClass('hidden');
				$('#super_div1').addClass('hidden');
				$('#super_div2').addClass('hidden');
				$('#super_div').addClass('hidden');
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');	
				$('#txt_Request').removeAttr('readonly','true');
				$('#txt_DateFrom').removeAttr('disabled','true');
				$('#txt_DateTo').removeAttr('disabled','true');
				//alert(StatusHead);
				$('#txtApprovedBy').val(StatusHead);
				$('#txtApprovedByID').val(ReportTo);						
				$('#txtSiteHeadApproval').val(HeadStatus);
				$('#btn_Leave_Save').removeClass('hidden');
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
			}
			else if(MgnrStatus == 'Pending' )
			{
				$("#app_link").html('<a href="view_BioMetric_one.php?p_EmpID='+EmployeeID+'&date='+DatFrom[0]+'" target="_blank"> Check Biometric and Roster</a>');
				$('#super_div_hr').removeClass('hidden');
				$('#super_div1').removeClass('hidden');
				$('#super_div2').removeClass('hidden');
				$('#super_div').addClass('hidden');
				$('#super_div_hr').removeClass('hidden');
				$('#super_div1').removeClass('hidden');
				$('#super_div2').removeClass('hidden');
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
				$('#txt_Request').attr('readonly','true');
				$('#txt_DateFrom').attr('disabled','true');
				$('#txt_DateTo').attr('disabled','true');
				$('#txtApprovedBy').val(AccountHeadName);
				$('#txtSupervisorApproval').val(MgnrStatus);							
				$('#txtSiteHeadApproval').val(HeadStatus);
				$('#btn_Leave_Save').removeClass('hidden');
			}
			else if(MgnrStatus != 'Pending' && AccountHead ==<?php echo '"'.$_SESSION['__user_logid'].'"';?>)
			{
				$("#app_link").html('<a href="view_BioMetric_one.php?p_EmpID='+EmployeeID+'&date='+DatFrom[0]+'" target="_blank"> Check Biometric and Roster</a>');
				$('#super_div_hr').removeClass('hidden');
				$('#super_div1').removeClass('hidden');
				$('#super_div2').removeClass('hidden');
				$('#super_div').addClass('hidden');
				$('#super_div_hr').removeClass('hidden');
				$('#super_div1').removeClass('hidden');
				$('#super_div2').removeClass('hidden');
				$('#super_div').addClass('hidden');							
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
				$('#txt_Request').attr('readonly','true');
				$('#txt_DateFrom').attr('disabled','true');
				$('#txtSupervisorApproval').attr('readonly','true');							
				$('#txt_DateTo').attr('disabled','true');
				$('#txtApprovedBy').val(AccountHeadName);
				$('#txtSupervisorApproval').val(MgnrStatus);							
				$('#txtSiteHeadApproval').val(HeadStatus);
				$('#btn_Leave_Save').removeClass('hidden');
			}
			else if(MgnrStatus != 'Pending' && HeadStatus =='Pending' && AccountHead == EmployeeID)
			{
				$("#app_link").html('<a href="view_BioMetric_one.php?p_EmpID='+EmployeeID+'&date='+DatFrom[0]+'" target="_blank"> Check Biometric and Roster</a>');
				$('#super_div_hr').removeClass('hidden');
				$('#super_div1').removeClass('hidden');
				$('#super_div2').removeClass('hidden');
				$('#super_div').addClass('hidden');							
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');	
				$('#txt_Request').attr('readonly','true');
				$('#txt_DateFrom').attr('disabled','true');
				$('#txt_DateTo').attr('disabled','true');
				$('#txtApprovedBy').val(StatusHead);
				$('#txtApprovedByID').val(ReportTo);	
				$('#txtSupervisorApproval').val(MgnrStatus);
				$('#txtSiteHeadApproval').val(HeadStatus);	
				if($('#txtEmpID1').val() == CenterHead)				
				{
					$('#txtSiteHeadApproval').removeAttr('readonly');
					$('#btn_Leave_Save').removeClass('hidden');
				}		
				
			}
			else if(MgnrStatus != 'Pending' && HeadStatus =='Pending')
			{
				$("#app_link").html('<a href="view_BioMetric_one.php?p_EmpID='+EmployeeID+'&date='+DatFrom[0]+'" target="_blank"> Check Biometric and Roster</a>');
				$('#super_div_hr').removeClass('hidden');
				$('#super_div1').removeClass('hidden');
				$('#super_div2').removeClass('hidden');
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').removeClass('hidden');
				$('#sitehead_div1').removeClass('hidden');
				$('#sitehead_div2').removeClass('hidden');
				$('#sitehead_div').addClass('hidden');	
				$('#txt_Request').attr('readonly','true');
				$('#txt_DateFrom').attr('disabled','true');
				$('#txt_DateTo').attr('disabled','true');
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
				$('#txtApprovedBy').val(AccountHeadName);
				$('#txtSupervisorApproval').val(MgnrStatus);
				$('#txtSiteHeadApproval').val(HeadStatus);	
				if($('#txtEmpID1').val() == CenterHead)				
				{
					$('#txtSiteHeadApproval').removeAttr('readonly');
					$('#btn_Leave_Save').removeClass('hidden');
				}		
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
			}
			else if(MgnrStatus != 'Pending' && HeadStatus !='Pending' && AccountHead == EmployeeID)
			{
				$("#app_link").html('<a href="view_BioMetric_one.php?p_EmpID='+EmployeeID+'&date='+DatFrom[0]+'" target="_blank"> Check Biometric and Roster</a>');
				$('#super_div_hr').removeClass('hidden');
				$('#super_div1').removeClass('hidden');
				$('#super_div2').removeClass('hidden');
				$('#super_div').addClass('hidden');
				$('#super_div_hr').removeClass('hidden');
				$('#super_div1').removeClass('hidden');
				$('#super_div2').removeClass('hidden');
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').removeClass('hidden');
				$('#sitehead_div1').removeClass('hidden');
				$('#sitehead_div2').removeClass('hidden');
				$('#sitehead_div').addClass('hidden');
				$('#txt_Request').attr('readonly','true');
				$('#txt_DateFrom').attr('disabled','true');
				$('#txt_DateTo').attr('disabled','true');
				$('#txtApprovedBy').val(StatusHead);
				$('#txtApprovedByID').val(ReportTo);
				$('#txtSupervisorApproval').val(MgnrStatus);							
				$('#txtSiteHeadApproval').val(HeadStatus);
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
			}
			else if(MgnrStatus != 'Pending' && HeadStatus !='Pending')
			{
				$("#app_link").html('<a href="view_BioMetric_one.php?p_EmpID='+EmployeeID+'&date='+DatFrom[0]+'" target="_blank"> Check Biometric and Roster</a>');
				$('#super_div_hr').removeClass('hidden');
				$('#super_div1').removeClass('hidden');
				$('#super_div2').removeClass('hidden');
				$('#super_div').addClass('hidden');
				$('#super_div_hr').removeClass('hidden');
				$('#super_div1').removeClass('hidden');
				$('#super_div2').removeClass('hidden');
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').removeClass('hidden');
				$('#sitehead_div1').removeClass('hidden');
				$('#sitehead_div2').removeClass('hidden');
				$('#sitehead_div').addClass('hidden');
				$('#txt_Request').attr('readonly','true');
				$('#txt_DateFrom').attr('disabled','true');
				$('#txt_DateTo').attr('disabled','true');
				$('#txtApprovedBy').val(AccountHeadName);
				$('#txtSupervisorApproval').val(MgnrStatus);							
				$('#txtSiteHeadApproval').val(HeadStatus);
				$('#super_div').addClass('hidden');							
				$('#sitehead_hr').addClass('hidden');
				$('#sitehead_div1').addClass('hidden');
				$('#sitehead_div2').addClass('hidden');
				$('#sitehead_div').addClass('hidden');
			 }
		  }
		
			if(MgnrStatus == 'Approve')
			{
				//$('#comment_box').addClass('hidden');
				$('#btn_Leave_Add').addClass('hidden');
				$('#btn_Leave_Save').addClass('hidden');
			}
	   }
	}                             
	}});
	$(".check_val_").prop('checked', false);
	$("#chkAll").prop('checked',false);
	$('#txt_common_comment').focus();
}

function DeleteReq(el)
{
	if(confirm("Do you Want to Delete Request"))
	{
		$item=$(el);
		$.ajax({url: "../Controller/deleteRequest.php?ID="+$item.attr("Data-ID"), success: function(result){
            var data=result.split('|');
			$('#alert_msg').html('<ul class="text-danger">'+data[1]+'</ul>');
      		$('#alert_message').show().attr("class","SlideInRight animated");
      		$('#alert_message').delay(10000).fadeOut("slow");
		    if(data[0]=='Done')
            {         
	      		$item.closest('td').parent('tr').remove(); 
			}                       
	    }});
	}    
}

function checkItem_All(el)
{
$('#comment_container').empty().append('');
$(".check_val_").prop('checked', $(el).prop('checked'));
if($('input.check_val_:checked').length == $('input.check_val_').length){
	$('#txt_Request').val('NA').trigger('change');
	$('#comment_box').removeClass('hidden');
	$('#btn_Leave_Add').addClass('hidden');
	$('#btn_Leave_Save').addClass('hidden');
	$('#user_div').addClass('hidden');
	$('#txtID').val('');			
	$('#txtSiteHeadApproval').removeAttr('readonly');
	$('#btn_Leave_Save').removeClass('hidden');
	$('.clsIDHome').addClass('hidden');			
	$('#sitehead_div1').removeClass('hidden');
	$('#sitehead_div2').removeClass('hidden');
	$('#sitehead_div').addClass('hidden');
	$('#txtEmpID').val('');
	$('#txtEmpName').val('');
}
else
{
	$('#txt_Request').val('NA').trigger('change');
	$('#comment_box').addClass('hidden');
	$('#btn_Leave_Add').removeClass('hidden');
	$('#btn_Leave_Save').addClass('hidden');
	
	$('#txtID').val('');			
	$('#txtSiteHeadApproval').addClass('readonly');
	$('#btn_Leave_Save').addClass('hidden');
	$('.clsIDHome').removeClass('hidden');			
	$('#sitehead_div1').addClass('hidden');
	$('#sitehead_div2').addClass('hidden');
	$('#sitehead_div').addClass('hidden');
	$('#txtEmpID').val($('#txtEmpID1').val());
	$('#txtEmpName').val($('#txtEmpName1').val());
}


}

function checkAll()
{
$('#comment_container').empty().append('');
	if($('input.check_val_:checked').length == $('input.check_val_').length)
	{

	    
	}
	else
	{
	    $("#chkAll").prop('checked', false);   
	}
	if($('input.check_val_:checked').length > 0)
	{
		$('#txt_Request').val('NA').trigger('change');
		$('#comment_box').removeClass('hidden');
		$('#btn_Leave_Add').addClass('hidden');
		$('#btn_Leave_Save').addClass('hidden');
		$('#user_div').addClass('hidden');
		$('#txtID').val('');			
		$('#txtSiteHeadApproval').removeAttr('readonly');
		$('#btn_Leave_Save').removeClass('hidden');
		$('.clsIDHome').addClass('hidden');			
		$('#sitehead_div1').removeClass('hidden');
		$('#sitehead_div2').removeClass('hidden');
		$('#sitehead_div').addClass('hidden');
		$('#txtEmpID').val('');
		$('#txtEmpName').val('');
	}
	else
	{
		$('#txt_Request').val('NA').trigger('change');
		$('#comment_box').addClass('hidden');
		$('#btn_Leave_Add').removeClass('hidden');
		$('#btn_Leave_Save').addClass('hidden');
		
		$('#txtID').val('');			
		$('#txtSiteHeadApproval').addClass('readonly');
		$('#btn_Leave_Save').addClass('hidden');
		$('.clsIDHome').removeClass('hidden');			
		$('#sitehead_div1').addClass('hidden');
		$('#sitehead_div2').addClass('hidden');
		$('#sitehead_div').addClass('hidden');
		$('.clsIDHome').removeClass('hidden');
		$('#txtEmpID').val($('#txtEmpID1').val());
		$('#txtEmpName').val($('#txtEmpName1').val());
	}
}
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>