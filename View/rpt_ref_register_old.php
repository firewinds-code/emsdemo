<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
require_once(__dir__.'/../Config/DBConfig_interview_array.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
// Global variable used in Page Cycle
$value=$counEmployee=$countProcess=$countClient=$countSubproc=$thisPage=0;
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
		$referer = $alert_msg = "";
		$thisPage = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if (isset($_SERVER['HTTP_REFERER']))
		{
		    $referer = $_SERVER['HTTP_REFERER'];
		}
		if($referer == $thisPage)
		{
		    $isPostBack = true;
		} 
		if($isPostBack && isset($_POST))
		{
			$date_To = $_POST['txt_dateTo'];
			$date_From =$_POST['txt_dateFrom'];
		}
		else
		{
			$date_To = date('Y-m-d',time()); 
			$date_From= date('Y-m-d',time()); 
		}
		
	}
	/* if($_SESSION['__user_type']=='ADMINISTRATOR' || $_SESSION['__user_type']=='HR' || $_SESSION['__user_type']=='CENTRAL MIS' || $_SESSION['__user_logid'] == 'CE10091236')
		{
			die("access denied ! It seems like you try for a wrong action.");
			exit();
		}*/
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}
?>

<script>
	$(function(){
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
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
						        
						    ],
						    "bProcessing" : true,
							"bDestroy" : true,
							"bAutoWidth" : true,
							"iDisplayLength": 25,
							"sScrollX" : "100%",
							"bScrollCollapse" : true,
							"bLengthChange" : false,
							"fnDrawCallback":function() 
							{
								$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
							}
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

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Reference Registration Reports</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Reference Registration Reports</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	


				
	<div class="input-field col s12 m12" id="rpt_container">
		<div class="input-field col s5 m5">
			<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From;?>"/>
		</div>
		<div class="input-field col s5 m5">
			<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To;?>"/>
		</div>
		<div class="input-field col s2 m2">	
			<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
			<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
		</div>
		
	</div>
<!--Form element model popup End-->
<!--Reprot / Data Table start -->			
	
		<div id="pnlTable">
		<?php
			if(isset($_POST['btn_view']))
			{

				$myDB=new MysqliDb();
				//$query_Select='call sp_rpt_refscheme("'.$date_From.'","'.$date_To.'")';
				//$query_Select='call sp_rpt_refscheme("'.$date_From.'","'.$date_To.'")';
				
				$query_Select = "select r.RefID,r.createdon,r.CandidateName, r.CandidateNumber,r.EmployeeID ,r.Process from tbl_reference_reg_detail r  where cast(r.createdon as date) between '".$date_From."' and '".$date_To."'";
											
				$result=$myDB->query($query_Select);
				$my_error= $myDB->getLastError();		
				$mysql_error = $myDB->getLastError();
				if(count($result) > 0 && $result){
			
			
			$table='<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
			$table .='<th>EmployeeID</th><th>Process</th><th>createdon</th><th>CandidateName</th><th>CandidateNumber</th><th>Walkin(Y/N)</th><th>Walkin Date</th><th>Interview Cleared(Y/N)</th><th>Process Selected</th><th>Joining Date</th><th>Joined Y/N</th><th>Candidate EmployeeID</th><th>Salary CTC</th><th>Current Active (Y/N)</th><th>Total Amount</th><th>1st Pay Amount</th><th>1st Pay Date</th><th>2nd Pay Amount</th><th>2nd Pay Date</th></tr></thead><tbody>	';
			
		   				        
		       foreach($result as $key=>$value)
		        {
				
				$table .='<tr>';
				
				$RefID = $value['RefID'];
				$CandidateNumber = $value['CandidateNumber'];
				$EmployeeID = $value['EmployeeID'];
				$CreatedOn = $value['createdon'];
				
				$table .='<td>'.$value['EmployeeID'].'</td>';
				$table .='<td>'.$value['Process'].'</td>';
				$table .='<td>'.$value['createdon'].'</td>';
				$table .='<td>'.$value['CandidateName'].'</td>';
				$table .='<td>'.$value['CandidateNumber'].'</td>';
				
				$sqlConnect = "select t1.id, t2.INTID,t2.`Walkin (Y/N)`,ifnull (t2.`Walkin Date`,'NA') as `Walkin Date` from (select EmployeeID,max(id) as id from interview_ems.int_exit_info group by EmployeeID order by id desc) t1 join (select ic.EmployeeID as INTID,ic.mobile, case when ic.EmployeeID is null then 'No' else 'Yes' end as 'Walkin (Y/N)',ic.createdon as `Walkin Date` from  interview_ems.int_contact_details ic) t2 on t1.EmployeeID = t2.INTID where cast(t2.`Walkin Date` as date) >date_sub(cast(now() as date), interval 70 day) and t2.mobile='".$CandidateNumber."'";
				
				$ID='';
				$INTID='';
				$WalkinDate='';
				$myDB=new MysqliDb($db_int_config_i);
				$result2=$myDB->rawQuery($sqlConnect);
				$mysql_error = $myDB->getLastError();
				if(count($result2) > 0 && $result2)
				{
					$table .='<td>'.$result2[0]['Walkin (Y/N)'].'</td>';
					$table .='<td>'.$result2[0]['Walkin Date'].'</td>';
					$INTID = $result2[0]['INTID'];
					$WalkinDate = $result2[0]['Walkin Date'];
					$ID = $result2[0]['id'];
				}
				else
				{
					$table .='<td>No</td>';
					$table .='<td>NA</td>';
				}
				
				$sqlConnect = "select `Interview clered (Y/N)`,ifnull(`Prcess Seleceted`,'NA')  as `Prcess Seleceted`,case when (`Joining Date` is null or `Joining Date`='0000-00-00') then 'NA' else `Joining Date` end as `Joining Date`,Process1 from (select case when ie.`HR Status` ='OK for Joining' then 'Yes' else 'No' end as 'Interview clered (Y/N)',ie.ProcessName as 'Prcess Seleceted',cast(ie.date_of_joining as date) as 'Joining Date',ie.Process as Process1 from interview_ems.int_exit_info ie where id='".$ID."')t";
								
				$DOJ='';
				$Process1='';
				$myDB=new MysqliDb($db_int_config_i);
				$result1=$myDB->rawQuery($sqlConnect);
				$mysql_error = $myDB->getLastError();
				if(count($result1) > 0 && $result1)
				{
					$table .='<td>'.$result1[0]['Interview clered (Y/N)'].'</td>';
					$table .='<td>'.$result1[0]['Prcess Seleceted'].'</td>';
					$table .='<td>'.$result1[0]['Joining Date'].'</td>';
					
					$DOJ = $result1[0]['Joining Date'];
					$Process1 = $result1[0]['Process1'];
				}
				else
				{
					$table .='<td>No</td>';
					$table .='<td>NA</td>';
					$table .='<td>NA</td>';
				}
				
							
			$sqlConnect = "select EmployeeID,case when INTID is null then 'No' else 'Yes' end as 'Joined Y/N', ctc as 'Salary CTC', cm_id, case when emp_status='Active' then 'Yes' else 'No' end as 'Current Active (Y/N)' from (select p.EmployeeID,s.ctc,e.emp_status,e.cm_id,p.INTID,p.createdon from personal_details p inner join salary_details s on s.EmployeeID=p.EmployeeID inner join employee_map e on e.EmployeeID=p.EmployeeID where emp_status='Active' and cast(p.createdon as date)between '".$date_From."' and '".$date_To."')t where INTID='".$INTID."' and cm_id='".$Process1."'";
				
				$Joined='';
				$cmid='';
				$myDB=new MysqliDb();
				$result2=$myDB->rawQuery($sqlConnect);
				$mysql_error = $myDB->getLastError();
				if(count($result2) > 0 && $result2)
				{
					$table .='<td>'.$result2[0]['Joined Y/N'].'</td>';
					$table .='<td>'.$result2[0]['EmployeeID'].'</td>';
					$table .='<td>'.$result2[0]['Salary CTC'].'</td>';
					$table .='<td>'.$result2[0]['Current Active (Y/N)'].'</td>';
					$Joined = $result2[0]['Joined Y/N'];
					$cmid = $result2[0]['cm_id'];
				}
				else
				{
					$table .='<td>No</td>';
					$table .='<td>NA</td>';
					$table .='<td>NA</td>';
					$table .='<td>No</td>';
				}
				
				
				$sqlConnect = "select amount,1st_pay,2nd_pay,window_month from ref_amount_master where ID='".$RefID."' and cm_id='".$cmid."' ";
				$amount='';
				$fpay='';
				$spay='';
				$window_month ='';
				$fpayDate='';
				$spayDate='';
				
				$myDB=new MysqliDb();
				$result3=$myDB->rawQuery($sqlConnect);
				$mysql_error = $myDB->getLastError();
				if(count($result3) > 0 && $result3)
				{
					$amount = $result3[0]['amount'];
					$fpay = $result3[0]['1st_pay'];
					$spay = $result3[0]['2nd_pay'];
					$window_month = $result3[0]['window_month'];
					
					// Calculate Amount and Pay date
					
					$sqlConnect = "select des_id from whole_details_peremp where EmployeeID='".$EmployeeID."'";
					$des_id ='';
					$myDB=new MysqliDb();
					$result4=$myDB->rawQuery($sqlConnect);
					$mysql_error = $myDB->getLastError();
					if(count($result4) > 0 && $result4)
					{
						$des_id = $result4[0]['des_id'];
					}
					
					$datediff = strtotime($WalkinDate) - strtotime($CreatedOn);
					
					if($Joined =='' or ($des_id=='5' or $des_id=='7' or $des_id=='8' or $des_id=='10' or $des_id=='13' or $des_id=='14' or $des_id=='15' or $des_id=='16') or (round($datediff / (60 * 60 * 24)) <0 and round($datediff / (60 * 60 * 24)) >14) or $amount == '')
					{
						$amount='NA';
					}
					
					if($Joined =='' or ($des_id=='5' or $des_id=='7' or $des_id=='8' or $des_id=='10' or $des_id=='13' or $des_id=='14' or $des_id=='15' or $des_id=='16') or (round($datediff / (60 * 60 * 24)) <0 and round($datediff / (60 * 60 * 24)) >14) or $fpay == '')
					{
						$fpay='NA';
					}
					
					 $window_month = intval($window_month) + intval(1); 
					
					if($Joined =='' or ($des_id=='5' or $des_id=='7' or $des_id=='8' or $des_id=='10' or $des_id=='13' or $des_id=='14' or $des_id=='15' or $des_id=='16') or (round($datediff / (60 * 60 * 24)) <0 and round($datediff / (60 * 60 * 24)) >14) or $fpay == '')
					{
						$fpayDate = 'NA';
					}
					else
					{
						$fpayDate = date('Y-m-15', strtotime($DOJ. '+'.$window_month .' month'));
					}
					
					if($Joined =='' or ($des_id=='5' or $des_id=='7' or $des_id=='8' or $des_id=='10' or $des_id=='13' or $des_id=='14' or $des_id=='15' or $des_id=='16') or (round($datediff / (60 * 60 * 24)) <0 and round($datediff / (60 * 60 * 24)) >14) or $spay == '')
					{
						$spay='NA';
					}
					
					
					if($Joined =='' or $spay =='' or $spay =='0' or ($des_id=='5' or $des_id=='7' or $des_id=='8' or $des_id=='10' or $des_id=='13' or $des_id=='14' or $des_id=='15' or $des_id=='16') or (round($datediff / (60 * 60 * 24)) <0 and round($datediff / (60 * 60 * 24)) >14) or $spay == '')
					{
						$spayDate = 'NA';
					}
					else
					{
						$window_month = intval($window_month) + intval(1);
						$spayDate = date('Y-m-15', strtotime($DOJ. '+'.$window_month .' month'));
					}
				
				}
				else
				{
					$amount = 'NA';
					$fpay = 'NA';
					$spay = 'NA';
					$fpayDate = 'NA';
					$spayDate = 'NA';
				}
				
				
				
				
				$table .='<td>'.$amount.'</td>';
				$table .='<td>'.$fpay.'</td>';
				$table .='<td>'.$fpayDate.'</td>';
				$table .='<td>'.$spay.'</td>';
				$table .='<td>'.$spayDate.'</td>';
				
				$table .='</tr>';
				
			
				//echo '</tr>'; 
				}	
				
				$table .='</tbody></table>';	
				echo $table;
				
				}
				}
				?>		       
		    <!--</tbody>
	  </table>-->
		
		 
	</div>
          
<!--Reprot / Data Table End -->	       
	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>		
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
