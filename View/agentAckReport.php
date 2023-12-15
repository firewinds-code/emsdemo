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
$clientID=$process=$subprocess=$bheading=$remark1=$remark2=$remark3='';
$classvarr="'.byID'";
$searchBy='';

?>
<script>
	$(document).ready(function(){
		$('#txt_ED_joindate_to').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#txt_ED_joindate_from').datetimepicker({ format:'Y-m-d', timepicker:false});
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
		   	
		  
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
		   	$('.byID').addClass('hidden');
		   	var classvarr=<?php echo $classvarr; ?>;
		   	$(classvarr).removeClass('hidden');
		   
	});
</script>
 <!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Briefing Quiz Report </span>

<!-- Main Div for all Page -->
<div class="pim-container " >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Briefing Quiz Report</h4>	 
<!-- Form container if any -->
	<div class="schema-form-section row" >
	<div id="pnlTable">
			<?php 
					//echo  $sqlConnect = "select a.*,b.heading,b.id,b.quize from tbl_brifing_accknowledge a INNER JOIN tbl_briefing b on a.brifing_id=b.id where a.EmployeeID='".$_SESSION['__user_logid']."' ";
					  $sqlConnect = "select a.*,b.heading,b.id,b.quiz from brf_acknowledge a INNER JOIN brf_briefing b on a.BriefingId=b.id where a.EmployeeID='".$_SESSION['__user_logid']."' order by a.id desc";
					//echo $sqlConnect;
					$myDB=new MysqliDb();
					$result=$myDB->rawQuery($sqlConnect);
					$error=$myDB->getLastError();
					$rowCount = $myDB->count;
					
					if($rowCount>0){?>
						
			   			<div class="had-container pull-left row card dataTableInline"  id="tbl_div" >
							<div class=""  >																														
						   <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">			
						   	<thead>
						         <tr align="center">
						        	<th style="vertical-align:top;text-align:center;" >S. No.</th>
						        	<th style="vertical-align:top;text-align:center;">Briefing</th>
						            <th style="vertical-align:top;text-align:center;">Acknowledge Date & Time</th>
						            <th style="vertical-align:top;text-align:center;">Quiz Available</th>
						            <th style="vertical-align:top;text-align:center;">Quiz Attemted</th>
						            <th style="vertical-align:top;text-align:center;">Quiz Attemted Date & Time</th>
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       $i=1;
					        foreach($result as $key=>$value){
					        	$attempt_quiz="";
					        	$bf_id=$value['id'];
					        	$select_attempted="select AttemptedDate from brf_quiz_attempted where BriefingID='".$bf_id."' and EmployeeID='".$_SESSION['__user_logid']."'";
								$myDB=new MysqliDb();
								$Quizresult=$myDB->rawQuery($select_attempted);
								$error=$myDB->getLastError();
								$rowCount = $myDB->count;
								$date_time="";
								if($rowCount>0){
									$attempt='Yes';
									//$DataArray=$myDB->rawQuery($Quizresult);
									$date_time=$Quizresult[0]['AttemptedDate'];
									
								}else{
									$attempt='No';
								}
							
								echo '<tr style="vertical-align:top;">';	
								echo '<td class="client_name" style="vertical-align:top;text-align:center;" >'.$i.'</td>';						
								echo '<td class="client_name" style="vertical-align:top;">'.$value['heading'].'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.$value['AcknowledgeDate'].'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >';
								if($value['quiz']!=""){ echo $value['quiz']; } else { echo 'No';}
								echo'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.$attempt.'</td>';;
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.$date_time.'</td>';;
								
								
					
							echo '</tr>';
							$i++;
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
							
							 echo "<script>$(function(){ toastr.error('Quiz Not Found :: <code >".$error."</code> .') }); </script>";
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
	$(document).ready(function(){
		$('#alert_msg_close').click(function(){
			$('#alert_message').hide();
		});
		if($('#alert_msg').text()=='')
		{
			$('#alert_message').hide();
		}

		$('#div_error').removeClass('hidden');

		
	});
	var newwindow;
function createPop(accid,bfid)
{   
 newwindow=window.open('briefingQuiz.php?bfid='+bfid+'&accid='+accid+'&user=agent','Quiz Report','width=600,height=600,toolbar=0,menubar=0,location=0');  
 if (window.focus) {newwindow.focus()}
}
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>