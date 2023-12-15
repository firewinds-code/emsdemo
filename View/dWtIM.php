<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

  $btnSave ='hidden';
  $btnAdd = '';
  $AccountHeadName= $AccountHead='';
  $SiteHead='';
  $SiteHeadName='';
  $alert_msg ='';
  
  $readonly_ah =' readonly="true" ';
  $readonly_sh =' readonly="true" ';
 
  (isset($_POST['txt_srch_DateFrom']))?$date_srch_from =$_POST['txt_srch_DateFrom'] : $date_srch_from= date('Y-m-01');
  (isset($_POST['txt_srch_DateTo']))?$date_srch_to =$_POST['txt_srch_DateTo'] : $date_srch_to= date('Y-m-t');

  
    $str= "select ReportsTo,EmployeeName from downtimereqid1 inner join personal_details on EmployeeID = ReportsTo where process ='".$_SESSION['__user_process']."' and SubProcess ='".$_SESSION['__user_subprocess']."' limit 1";
 	$myDB=new MysqliDb();	
	$result=$myDB->query($str);
	$error=$myDB->getLastError();
  	if(empty($error))
  	{
		foreach($result as $key => $value)
		{
			$AccountHead = $value['ReportsTo'];
			$AccountHeadName = $value['EmployeeName'];
		}
	}
  	
	function calcDTTime($dt,$empID)
    {
        $DTStart = ""; $DTEnd = ""; $ADT="";
        $sql = "call sp_GetRoasterDataByDate('".$empID."','".$dt."')";
        
        $myDB = new MysqliDb();
        $roster_Data = $myDB->query($sql);
        $roster ='';
        $error=$myDB->getLastError();
  	    if(empty($error))
        {
			$roster = $roster_Data[0]['Shift'];
		}
        
            if ($roster[0] == "0" || $roster[0] == "1" ||  $roster[0] == "2"||$roster[0] == "4" || $roster[0] == "4" || $roster[0] == "5" || $roster[0] == "6" || $roster[0] == "7" || $roster[0] == "8" || $roster[0] == "9")
			{
                $rin =trim(substr($roster ,0,strpos($roster,'-')));
				$rout =trim(substr($roster ,strpos($roster,'-')+1,(strlen($roster)- (strpos($roster,'-')+1))));
                $i_rosterIN = date('Y-m-d H:i:s',strtotime($dt.' '.$rin));
                
				$i_rin_tmp = date('H:i:s',strtotime($rin));
				$i_rout_tmp = date('H:i:s',strtotime($rout));
				$i_rosterOUT ='';
				if($i_rin_tmp > $i_rout_tmp)
				{
					$i_rosterOUT = date('Y-m-d H:i:s',strtotime($dt.' '.$rout));			
					$i_rosterOUT = date('Y-m-d H:i:s',strtotime($i_rosterOUT.' +1 days'));
				}
				else
				{
					$i_rosterOUT = date('Y-m-d H:i:s',strtotime($dt.' '.$rout));
				}
				
               	$DTStart = date('Y-m-d H:i:s',strtotime($_POST['txt_DateFrom']));
                $DTEnd =  date('Y-m-d H:i:s',strtotime($_POST['txt_DateTo']));
               	
                $ADT  = get_inshift_time($i_rosterIN,$i_rosterOUT,$DTStart,$DTEnd);
            }
            else if($roster == 'WO-WO')
            {
				$valT1=date('Y-m-d H:i:s', $DTStart);
				$valT2=date('Y-m-d H:i:s', $DTEnd);
					
				// Conver create date as new DateTime object
					
            	$st = new DateTime($valT1);
				$et = new DateTime($valT2);
					
				
				$diff_tt = date_diff($st,$et);
				$alt_tt = $diff_tt->format('%H:%I');
				
			    $ADT = $alt_tt;	
			}
            
        return array($DTStart,$DTEnd,$ADT);
    }	
	function get_inshift_time($r1,$r2,$b1,$b2,$type)
	{
		$tbin = new DateTime($b1);
		$tbout = new DateTime($b2);
		$trin = new DateTime($r1);
		$trout = new DateTime($r2);
		
		
		if ($tbin <= $trin && $tbout >= $trout)
	    {
	        $tt = $trout->diff($trin);
	    }

	    else if ($tbin <= $trin && $tbout <= $trout)
	    {
	        $tt = $tbout->diff($trin);
	        
	    }

	    else if ($tbin >= $trin && $tbout <= $trout)
	    {
	        $tt = $tbout->diff($tbin);
	    }

	    else if ($tbin >= $trin && $tbout >= $trout)
	    {
	        $tt = $trout->diff($tbin);
	    }
		
		return date('H:i',strtotime($tt->format('%H:%i:%s')));
		
	}
	
	if(isset($_POST['btn_Leave_Add']))
	{
			$EmpID = $_SESSION['__user_logid'];
            $Process = $_SESSION['__user_process'];
            $RequestType = $_POST['txt_Request_text'];
            $LoginDateTime = $_POST['txt_LoginDate'];
            $EmployeeComment = $_POST['txt_Comment'];
            $FAID = $_POST['txt_Request'];
            $Status = "Pending";
            $ReportsTo = $_POST['hiddenReprtingToID'];
            $RTStatus = "Pending";
			$res = calcDTTime( $_POST['txt_LoginDate'],$EmpID);
			
			$DT_Start = date('Y-m-d H:i:s',strtotime($_POST['txt_DateFrom']));
    		$DT_End =  date('Y-m-d H:i:s',strtotime($_POST['txt_DateTo']));
           
           	if(!empty($ReportsTo))
           	{
				if(!empty($res[2]) && $DT_Start < $DT_End)
				{
					$DateFrom = $res[0];
		            $DateTo = $res[1];
		            $TotalDT = $res[2];
					if(strtotime($TotalDT) > strtotime('00:00') && $RequestType != 'NA')
					{
						$myDB = new MysqliDb();					
			            $sqlInsertDT = 'call sp_InsertDTReq("'.$EmpID.'","'.$Process.'","'.$DateFrom.'","'.$DateTo.'","'.$TotalDT.'","'.$FAID.'","'.$LoginDateTime.'","'.$EmployeeComment.'","'.$FAID.'","'.$Status.'","'.$ReportsTo.'","'.$RTStatus.'","'.$RequestType.'")';
						$flag = $myDB->query($sqlInsertDT);
						$error = $myDB->getLastError();
						if(empty($error))
						{
							$myDB = new MysqliDb();
							$reqName_byID='';
							$ReqName = $myDB->query('call get_empNamebyID("'.$FAID.'")');
							if(count($ReqName)>0)
							{
								foreach($ReqName[0] as $Key=>$val)
					        	{
					        		foreach($val as $k=>$v)
					        		{
										$reqName_byID = $v;
													
									}
								}
							}
							echo "<script>$(function(){ toastr.success(' Request Saved and Sended To' ".$reqName_byID."); }); </script>";
						}
						else
						{
							echo "<script>$(function(){ toastr.error('Request Not Saved' ".$error."); }); </script>";
						}
					}
					else
					{
						echo "<script>$(function(){ toastr.error('Request Not Saved Wrong request or time value'); }); </script>";
					}
				}
				else
				{
					echo "<script>$(function(){ toastr.error('Wrong Downtime value,From Datetime should not be greater then To Datetime and make sure your roster is available.'); }); </script>";
				}
			}
          	else
			{
					echo "<script>$(function(){ toastr.error('Account Head not found by process and subprocess.'); }); </script>";		
			}		
	}
	
	if(isset($_POST['btn_Leave_Save']))
	{
		$len_check = 0;
		/*if(isset($_POST['check_val_up']) && $_POST['txt_Request'] == 'NA' && $_POST['txt_DateFrom'] == '' && $_POST['txt_DateTo'] == '' && $_POST['txtEmpID'] == '' )
		{
			if($_POST['txt_Request'] == 'NA' && $_POST['txt_DateFrom'] == '' && $_POST['txt_DateTo'] == '' && $_POST['txtEmpID'] == '' )
			{
				$len_check = 1;				
			}
			else
			{
				$len_check = 2;			
			}
			
		}
		else if($_POST['txt_Request'] != 'NA' && $_POST['txt_DateFrom'] != '' && $_POST['txt_DateTo'] != '' && $_POST['txtEmpID'] != '')
		{
			$len_check = 0;
		}
		else
		{
			$len_check = 2;		
		}*/
		
		if($_POST['txtID'] > 0 && $len_check === 0 )
		{
		
			$ExpID = $_POST['txtID'];
			$EmpID = $_POST['txtEmpID'];
            $Process = $_SESSION['__user_process'];            
            $RequestType = $_POST['txt_Request_text'];
            $LoginDateTime = $_POST['hiddenLoginDate'];
            $EmployeeComment = $_POST['txt_common_comment'];
            $FAID = $_POST['txt_Request'];
            $Status = "Pending";
            $ReportsTo = $_POST['hiddenReprtingToID'];
            $RTStatus = "Pending";
			$res = calcDTTime($LoginDateTime,$EmpID);
			$billable = '';
			if(strlen($res[0]) > 0)
			{
				$DateFrom = $res[0];
		        $DateTo = $res[1];
		        $TotalDT = $res[2];
				$MngrStatusID = $_POST['txtSupervisorApproval'];
	            $HeadStatusID = $_POST['txtSiteHeadApproval'];
	            $billable=$_POST['txtHeadType'];
	           			
	            $myDB = new MysqliDb();           
	            $sqlUpdatereq = 'call UpdateDTRequest("'.$ExpID.'","'.$DateFrom.'","'.$DateTo.'","'.$TotalDT.'","'.$FAID.'","'.$EmployeeComment.'","'.$FAID.'","'.$MngrStatusID.'","","'.$HeadStatusID.'","","'.$_SESSION['__user_Name'].'","'.$_SESSION['__user_logid'].'","'.$RequestType.'","'.$billable.'")';
				$flag = $myDB->query($sqlUpdatereq);
				$error = $myDB->getLastError();
				if(empty($error))
				{
							
					if($ReportsTo == $_SESSION['__user_logid'] && $HeadStatusID != 'Pending')
					{
						echo "<script>$(function(){ toastr.success('Request Saved and Closed.'); }); </script>";
						if($ReportsTo == $_SESSION['__user_logid'] && $HeadStatusID == 'Approve')
						{
							//UpdateDT($EmpID,$res);
								$url='';
								//$url = URL.'View/calcAtnd_for_empid.php?empid='.$EmpID.'&month='.date('m',strtotime($DateFrom)).'&year='.date('Y',strtotime($DateFrom));
								$iTime_in = new DateTime($DateFrom);
								$iTime_out =new DateTime();
								$interval = $iTime_in->diff($iTime_out);
								if($interval->format("%a") <= 10)
								{
									$url = URL.'View/calcRange.php?empid='.$EmpID.'&type=one&from='.date('Y-m-d',strtotime($DateFrom));

								}
								else
								{
									$url = URL.'View/calcRange.php?empid='.$EmpID.'&type=one';

								}

								$curl = curl_init();
								curl_setopt($curl, CURLOPT_URL, $url);
								curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($curl, CURLOPT_HEADER, false);
								$data = curl_exec($curl);
								curl_close($curl);
						}
					}
					else if($FAID == $_SESSION['__user_logid'] && $MngrStatusID != 'Approve')
					{
						echo "<script>$(function(){ toastr.success('Request Saved and Sended To.'".$_POST['txtApprovedBy']."); }); </script>";
					}
					else if($FAID == $_SESSION['__user_logid'] && $MngrStatusID == 'Approve')
					{
						echo "<script>$(function(){ toastr.success('Request Saved and Sended To.'".$_POST['txtApprovedBy_sh']."); }); </script>";
					}
					else
					{
						echo "<script>$(function(){ toastr.success('Request Saved and Sended To.'".$_POST['txtApprovedBy']."); }); </script>";
					}
					
				}
				else
				{
					echo "<script>$(function(){ toastr.error('Request Not Saved.'".$error."); }); </script>";
				}
			
			}
			else
			{
				echo "<script>$(function(){ toastr.error('Roster Not Avalable'); }); </script>";
			}
			
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Request Not Saved :Try Again.'); }); </script>";
		}		
	}
$search = '';
if(isset($_POST['txt_search']))
{
	$search =$_POST['txt_search'];
}

$order_len=5;
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
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Raise Downtime Request</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Raise Downtime Request</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	

			<input type="hidden" name="txt_cur_empid" id="txt_cur_empid"  value="<?php echo $_SESSION['__user_logid'];?>"/>
			<input type="hidden" name="txtID" id="txtID"  value=""/>
			<input type="hidden" name="txt_search" id="txt_search"  value="<?php echo $search; ?>"/>
			<input  type="hidden" id="order_text" name="order_text" value="<?php echo $order_len?>" />
			<input type="hidden" name="txt_Request_text" id="txt_Request_text"  value=""/>
			<input type="hidden" name="hiddenLoginDate" id="hiddenLoginDate"  value=""/>
			<input type="hidden" name="hiddenReprtingToID" id="hiddenReprtingToID"  value="<?php echo $AccountHead; ?>"/>
			<?php 
			$sql_trs = "call sp_GetRoasterDataByDate('".$_SESSION['__user_logid']."','".date('Y-m-d',time())."')";
	        $myDB = new MysqliDb();
	        $data_trs = $myDB->query($sql_trs);
	        $roster_trs ='';
	        if(count($data_trs) > 0)
	        {
				foreach($data_trs[0] as $Key=>$val)
	        	{
	        		foreach($val as $k=>$v)
	        		{
						$roster_trs = $v;
									
					}
				}
			}
			
			?>
			<input type="hidden" name="hiddenRosterValue" id="hiddenRosterValue"  value="<?php echo $roster_trs; ?>"/>
			<div class="input-field col s12 m12" id="app_link"></div>
			
							<div class="input-field col s6 m6 clsIDHome" >
						      <label for=""> Employee Name</label>
						      <input type="text"readonly="true" id="txtEmpName"  name="txtEmpName"  style="border-width: 0px;background: transparent;box-shadow: none;color: black;font-weight: bold;" value="<?php echo $_SESSION['__user_Name'];?>"/>
						      <input type="hidden"readonly="true" id="txtEmpName1"  name="txtEmpName1"  style="border-width: 0px;background: transparent;box-shadow: none;color: black;font-weight: bold;" value="<?php echo $_SESSION['__user_Name'];?>"/>
						    </div>
						    
						    <div class="input-field col s6 m6 clsIDHome">
						      <label for="">Employee ID</label>
						      <input type="text"readonly="true" id="txtEmpID"  name="txtEmpID"  style="border-width: 0px;background: transparent;box-shadow: none;color: black;font-weight: bold;"  value="<?php echo $_SESSION['__user_logid'];?>"/>
						       <input type="hidden"readonly="true" id="txtEmpID1"  name="txtEmpID1"  style="border-width: 0px;background: transparent;box-shadow: none;color: black;font-weight: bold;"  value="<?php echo $_SESSION['__user_logid'];?>"/>
						    </div>
						   
						   <div class="form-group input-field col s12 m12"><hr /></div>
						   
						    <div class="input-field col s6 m6">
						      
						      <select id="txt_Request"  name="txt_Request"  >
				      				<option Selected="True" Value="NA">---Select---</option>
                                    <?php 
                                     $myDB = new MysqliDb();
                                     $rst_req = $myDB ->query('call sp_GetDTReqID1("'.$_SESSION['__user_process'].'","'.$_SESSION["__user_subprocess"].'")');
                                     //echo 'call sp_GetDTReqID1("'.$_SESSION['__user_process'].'")';
                                     if(count($rst_req) > 0)
                                     {
									 	foreach($rst_req as $key => $value)
									 	{
											foreach($value as $k => $v)
											{
												if($v['text'] == "OPS")
												{
													
													
													echo '<option value="'.$v['value'].'">Client Training</option>';
													
													if($_SESSION['__user_status'] == 6)
													{
														echo '<option value="'.$v['value'].'">Floor Support</option>';
														$myDB = new MysqliDb();
														$result_qp  =$myDB->query('select EmployeeID from tbl_nestor where EmployeeID = "'.$_SESSION['__user_logid'].'" limit 1');
														if(!empty($result_qp[0]['EmployeeID']))
														{
															echo '<option value="'.$v['value'].'">Nestor</option>';
														}
														
													}
														
												}
												else if($v['text'] == "Quality")
												{
													if($_SESSION['__user_status'] == 5)
													{
														echo '<option value="'.$v['value'].'">OJT</option>';
													}
														
												}
												elseif($v['text'] == "ER/HR")
												{
													echo '<option value="'.$v['value'].'">ER Activity</option>';
												}
												
												else if($v['text'] == "Training")
												{
													
												}
												else
												{
													echo '<option value="'.$v['value'].'">'.$v['text'].'</option>';
												}
												
											}
											
										}
									 }
                                    ?>
						      </select>	
						      <label for="txt_Request" class="active-drop-down active"> DownTime Reason</label>
						    </div>
						    
						    <div class="input-field col s6 m6">
						      <input type="text" readonly="true" id="txt_LoginDate" name="txt_LoginDate">
						      <label for="txt_LoginDate">Downtime Date</label>
						    </div>
						    
						    <div class="input-field col s6 m6">
						      <input type="text" readonly="true" id="txt_DateFrom" name="txt_DateFrom">
						      <label for="txt_DateFrom">Time From</label>
						    </div>
						    
						    <div class="input-field col s6 m6">
						      <input type="text" readonly="true" id="txt_DateTo" name="txt_DateTo">
						      <label for="txt_DateTo">Time To</label>
						    </div>
						    
							<div id="user_div" class="input-field col s12 m12">
						      	<textarea id="txt_Comment" name="txt_Comment" class="materialize-textarea"></textarea> 
						      	<label for="txt_Comment">Enter Comment</label>
						   
							</div>
							
							<div class="input-field col s12 m12 hidden" id="super_div_hr"><hr /></div>
							
							<div class="input-field col s6 m6 hidden" id="super_div1">
						      <select id="txtSupervisorApproval" name="txtSupervisorApproval" <?php echo $readonly_ah;?> >
						      	<option>Pending</option>
						      	<option>Approve</option>
						      	<option>Decline</option>
						      </select>
						      <label for="txtSupervisorApproval">Supervisor Approval</label>
						    </div>
						    
						    <div class="input-field col s6 m6 hidden" id="super_div2">
						      <input type="text" id="txtApprovedBy" name="txtApprovedBy" readonly="true" value="<?php echo $AccountHeadName; ?>" />
						      <input type="hidden" id="txtApprovedByID"  name="txtApprovedByID" value="<?php echo $AccountHead; ?>" />
						      <label for="txtApprovedBy"> Approved By :</label>
						    </div>
						    
						    <div id="super_div" class="hidden input-field col s12 m12">
								
						      	<textarea class="materialize-textarea" id="txt_Comment_sp" name="txt_Comment_sp" <?php echo $readonly_ah;?>  ></textarea>
						      	<label for="txt_Comment_sp"> Enter Comment</label>
							</div>
							 
							<div class="input-field col s12 m12 hidden" id="sitehead_hr"><hr/></div>
							
							<div class="input-field col s6 m6 hidden" id="sitehead_div1">
						      <select id="txtSiteHeadApproval"  name="txtSiteHeadApproval" <?php echo $readonly_sh;?> >
						        <option>Pending</option>
						      	<option>Approve</option>
						      	<option>Decline</option>
						      </select>
						      <label for="txtSiteHeadApproval"> Head Approval :</label>
						    </div>
						    
						    <div class="input-field col s6 m6 hidden" id="typeHeadDiv">
						      <select id="txtHeadType" name="txtHeadType" <?php echo $readonly_sh;?> >
						        <option value="NA">---Select---</option>
						        <option>Billable</option>
						      	<option>Non Billable</option>
						      </select>
						      <label for="txtHeadType">Type</label>
						    </div>
						    
						    <div class="input-field col s6 m6 hidden" id="sitehead_div2">
						      
						      <input type="text"id="txtApprovedBy_sh"  name="txtApprovedBy_sh" readonly="true" value="<?php echo $SiteHeadName; ?>"/>
						      <input type="hidden"id="txtApprovedBy_shID"  name="txtApprovedBy_shID" readonly="true" value="<?php echo $SiteHead; ?>"/>
						      <label for="txtApprovedBy_sh">Approved By</label>
						    </div>
						    
						    <div id="sitehead_div" class="input-field col s12 m12 hidden">
						      	<textarea class="materialize-textarea" id="txt_Comment_sh" name="txt_Comment_sh" <?php echo $readonly_sh;?>></textarea>
						      	<label for="txt_Comment_sh">Enter Comment</label>
							</div>
						
				
			<div id="comment_box" class="hidden">
				<h4>Comment Box</h4>
				<div>
					<div id="commentSection" style="margin: 1px;">
						<h3 style="border-bottom: 2px solid #7b1510;box-shadow: 2px 3px 5px -1px lightgray;border-right: 2px solid #ce0606;background: #e04a0e;text-align: left;font-size: 1.2em;outline: none;padding: 5px 30px;margin:0px 0px;color: white;">Comments Section</h3>
						<div class="col-sm-12" id="comment_container" style="margin: 0px;">	
						</div>	
					</div>
					<div class="input-field col s12 m12" >
					      <textarea id="txt_common_comment"  name="txt_common_comment" class="materialize-textarea"></textarea>
					      <label for="txt_common_comment">Enter Comment</label>
			        </div>
				</div>
			</div>
			
			 <div class="input-field col s12 m12 right-align">
						    <button type="submit" name="btn_Leave_Add" id="btn_Leave_Add" class="btn waves-effect waves-green <?php echo $btnAdd;?>"> Raise Request</button>
						   
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
		/*$('#txt_DateFrom').wickedpicker({title: 'DownTime From', //hh:mm 24 hour format only, defaults to current time
        twentyFour: true}).val('');
        $('#txt_DateTo').wickedpicker({ title: 'DownTime To', //hh:mm 24 hour format only, defaults to current time
        twentyFour: true}).val('');*/
        $('#txt_DateFrom').datetimepicker({format:'Y-m-d h:i A' , minDate:'-1970/01/03' , maxDate:'-1970/01/02' , step:1,beforeShowDay: disableTabs});
        $('#txt_DateTo').datetimepicker({format:'Y-m-d h:i A'  , minDate:'-1970/01/03' , maxDate:'+1970/01/01' , step:1,beforeShowDay: disableTabs});
        $('#txt_LoginDate').datetimepicker({format:'Y-m-d'  , minDate:'-1970/01/03' , maxDate:'-1970/01/02',timepicker:false,beforeShowDay: disableTabs});
        
		$('#txt_DateFrom').attr('disabled',true);
		$('#txt_DateTo').attr('disabled',true);
		
		$('#btn_Leave_Add').click(function(){
		
			var validate=0;
	       
	        if($(this).attr('id') == 'btn_Leave_Save')
	        {
				if($('#txtID').val()=='')
		        {	
			        $('#txtID').addClass('has-error');
					if($('#stxtID').size() == 0)
						{
						   $('<span id="stxtID" class="help-block">Request ID can not be Empty ,Please Select First.</span>').insertAfter('#txtID');
						}				
					validate=1;
				}
			}
			else if($(this).attr('id') == 'btn_Leave_Add')
			{
				
				if($('#txt_Request option:selected').text()=='Nestor')
				{
					var d2 = new Date($('#txt_DateFrom').val());
					var d1 = new Date($('#txt_DateTo').val());
					var diftime = Math.abs((d2-d1)/1000).toString();
					
					if(parseInt(diftime) < 10800)
					{
						validate=1;
						$('#txt_Request option:selected').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
						if($('#spantxt_Request option').size() == 0)
						{
						   $('<span id="#spantxt_Request option" class="help-block">Downtime should be greater or equal to 3 Hours.</span>').insertAfter('#txt_Request option:selected');
						}
					}
					
				}
				else if($('#txt_Request option:selected').text()=='OJT' || $('#txt_Request option:selected').text()=='Floor Support')
				{
					var d2 = new Date($('#txt_DateFrom').val());
					var d1 = new Date($('#txt_DateTo').val());
					var diftime = Math.abs((d2-d1)/1000).toString();
					
					if(parseInt(diftime) < 7200)
					{
						validate=1;
						$('#txt_Request option:selected').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
						if($('#spantxt_Request option').size() == 0)
						{
						   $('<span id="#spantxt_Request option" class="help-block">Downtime should be greater or equal to 2 Hours.</span>').insertAfter('#txt_Request option:selected');
						}
					}
				}
					
	    				
			}
	        if($('#txt_LoginDate').val()=='')
	        {
				$('#txt_LoginDate').addClass('has-error');
				validate=1;
				if($('#stxt_LoginDate').size() == 0)
					{
					   $('<span id="stxt_LoginDate" class="help-block">Downtime Date should not be Empty.</span>').insertAfter('#txt_LoginDate');
					}	
			}
			if($('#txt_Request').val()=='NA')
	        {
				$('#txt_Request').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
				validate=1;
				if($('#stxt_Request').size() == 0)
					{
					   $('<span id="stxt_Request" class="help-block">Request Exception should not be Empty.</span>').insertAfter('#txt_Request');
					}
			}
			
			if($('#txt_DateFrom').val()=='')
	        {
				$('#txt_DateFrom').addClass('has-error');
				validate=1;
				if($('#stxt_DateFrom').size() == 0)
				{
				   $('<span id="stxt_DateFrom" class="help-block">Time From should not be Empty.</span>').insertAfter('#txt_DateFrom');
				}
			}
			if($('#txt_DateTo').val()=='')
	        {
				$('#txt_DateTo').addClass('has-error');
				validate=1;
				if($('#stxt_DateTo').size() == 0)
				{
				   $('<span id="stxt_DateTo" class="help-block">Time To should not be Empty.</span>').insertAfter('#txt_DateTo');
				}
			}
			
	        if($(this).attr('id') != 'btn_Leave_Save')
	        {
	        	if($('#txt_Comment').val()=='')
		        {
					$('#txt_Comment').addClass('has-error');
					validate=1;
					if($('#stxt_Comment').size() == 0)
					{
					   $('<span id="stxt_Comment" class="help-block">Comment should not be Empty.</span>').insertAfter('#txt_Comment');
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
					   $('<span id="stxt_common_comment" class="help-block">Comment should not be Empty.</span>').insertAfter('#txt_common_comment');
					}
				}
			}
			
			if($('#txtSiteHeadApproval').val()=='Approve')
			{
				if($('#txtHeadType').val()=="NA")
				{
					$('#txtHeadType').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');					
					validate=1;
					if($('#stxtHeadType').size() == 0)
					{
					   $('<span id="stxtHeadType" class="help-block">Billing Type not be Unselected.</span>').insertAfter('#txtHeadType');
					}
				}				
				  
				 
			}
			
	      	if(validate==1)
	      	{	
		        $('#txt_DateFrom').attr('disabled','true');
				$('#txt_DateTo').attr('disabled','true');
				return false;
			}
			
		});
		
		$('#search_field,#comment_box,#commentSection').accordion({
	      collapsible: true,
			      heightStyle: "content" 
	    });
		
		$('#txt_Request').change(function(){
			$("#app_link").html('');
			$('#txt_Request_text').val($('#txt_Request option:selected').text());
			$('#backleave').addClass('hidden');
			$('#shif_div1').addClass('hidden');
			$('#shif_div2').addClass('hidden');
			$('#attendance_div1').addClass('hidden');
			$('#attendance_div2').addClass('hidden');
			$('#hiddenLoginDate').val('');
			$('#super_div_hr').addClass('hidden');
			$('#super_div1').addClass('hidden');
			$('#super_div2').addClass('hidden');
			$('#super_div').addClass('hidden');
			$('#sitehead_hr').addClass('hidden');
			$('#typeHeadDiv').addClass('hidden');
			
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
			if($(this).val() != 'NA')
			{
				$('#txt_DateFrom').removeAttr('disabled');
				$('#txt_DateTo').removeAttr('disabled');
			}
			else
			{
				$('#txt_DateFrom').attr('disabled',true);
				$('#txt_DateTo').attr('disabled',true);
			}
			
			
		});
		$('#txtSiteHeadApproval').change(function(){
			
			if($('#txtApprovedByID').val() == $('#txtApprovedBy_shID').val())
			{
				$('#txtSupervisorApproval').val($('#txtSiteHeadApproval').val());
			}
		});
		
	});
	
	function disableTabs(){
   $('.xdsoft_mounthpicker').html('').css('height',"30px");
}

</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>