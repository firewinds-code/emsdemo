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
// "<br>";
$clientID=$process=$subprocess=$bheading=$remark1=$remark2=$remark3='';
$classvarr="'.byID'";
$searchBy='';

?>
<script>
	$(document).ready(function(){
		$('#from_date').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#to_date').datetimepicker({ format:'Y-m-d', timepicker:false});
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
<span id="PageTittle_span" class="hidden">Briefing : Dashboard </span>

<!-- Main Div for all Page -->
<div class="pim-container " >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Briefing : Dashboard </h4>	
	 
<!-- Form container if any -->
	<div class="schema-form-section row" >
				<div  class='input-field col s6 m6' >
					<input type='text' name='from_date' id='from_date' <?php if(isset($_POST['from_date'])){ ?> value="<?php echo $_POST['from_date'];?>" <?php } ?>  />
					
					 <label for='from_date' class="active">From Date</label>
				</div>
				<div  class='input-field col s6 m6' > 
					<input type='text'  name='to_date' id='to_date' <?php if(isset($_POST['to_date'])){ ?> value="<?php echo $_POST['to_date'];?>" <?php } ?> />
					<label for='to_date' class='active'>From Date</label>
					
				</div>
				<div  class='input-field col s12 m12 right-align' > 
					<button type="submit"  value="Go" name="send" id="send" class="btn waves-effect waves-green  ">Go</button>
				</div>
			<?php 

				 function getEmpName($empID)
				 {
			    	$myDB=new MysqliDb();
			     	$select_empname_query=$myDB->rawQuery("SELECT EmployeeName from personal_details where EmployeeID='".$empID."'");
			     	foreach($select_empname_query as $select_empname_query_val)
					{
			     	return $select_empname_query_val['EmployeeName'];
					}
			    }
			    $sqlConnect="";
				if(isset($_POST['from_date'],$_POST['to_date']) and $_POST['from_date']!="" ){
					$from_date=$_POST['from_date'];
					$to_date=$_POST['to_date'];
					$sqlConnect="SELECT distinct  h.EmployeeID ,h.BriefingId,N.fromdate, h.EmployeeName,h.designation,h.status,h.ReportTo,h.Qa_ops,h.clientname,h.Process,h.sub_process,N.heading,N.cm_id,N.CreatedBy,N.CreatedOn,N.id,N.view_for,N.quiz  FROM brf_briefingfor h INNER JOIN brf_briefing N ON N.id=h.BriefingId
left outer join bioinout a on a.EmpID=h.EmployeeID and cast(N.fromdate as date)=cast(DateOn as date)
 where cast(N.fromdate as date) between '".$from_date."' and '".$to_date."' and   cast(DateOn as date) between '".$from_date."' and '".$to_date."'  and h.status in(4,5,6)";
					$myDB=new MysqliDb();
					$result=$myDB->query($sqlConnect);
					$error=$myDB->getLastError();
					$rowCount = $myDB->count;
					if($rowCount>0){?>
						
			   			 <div class="had-container pull-left row card dataTableInline"  id="tbl_div" >
							<div class=""  >																														
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						         <tr>
						        	<th>Employee ID </th>
									<th>Employee Name</th>
									<th>Designation</th>
									<th style="vertical-align:top;text-align:center;" >Report To(OPS)</th>
									<th style="vertical-align:top;text-align:center;" >Report To(Q.A)</th>
									 <th style="vertical-align:top;text-align:center;" >Client</th> 
									 <th style="vertical-align:top;text-align:center;" >Process</th> 
						            <th style="vertical-align:top;text-align:center;" >Sub Process</th> 
						          	<th style="vertical-align:top;text-align:center;" >EMP Status</th>
						            <th style="vertical-align:top;text-align:center;" >View For</th> 
						            <th style="vertical-align:top;text-align:center;" >Date</th>
						            <th style="vertical-align:top;text-align:center;" >Attendance</th>
						            <th style="vertical-align:top;text-align:center;" >Applicable</th>
						          	<th style="vertical-align:top;text-align:center;" >Attend Briefing</th>
						            <th style="vertical-align:top;text-align:center;" >Briefing Name</th>
						            <th style="vertical-align:top;text-align:center;" >Briefing Date</th>
						            <th style="vertical-align:top;text-align:center;" >Briefing Response Date</th>
						            <th style="vertical-align:top;text-align:center;" >Quiz Applicable</th>
						            <th style="vertical-align:top;text-align:center;" >Quiz Status</th>
						            <th style="vertical-align:top;text-align:center;" >Quiz Date</th>
						            <th style="vertical-align:top;text-align:center;" >Quiz Response Date</th> 
						            <th style="vertical-align:top;text-align:center;" >Quiz Score</th> 
						           
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					       $i=1;
					       $quizStatus="";
					        foreach($result as $key=>$value){
					        	$AttendB="";
					        	$applicable="NA";
					        	$quizStatus="No";
								$bf_id="";
								$QuizDate="";
								$AttemptedDate="";
								$correctAverage="";
								$AcknowledgeDate="";
								$attendence="NA";
								$quizApplicable='NA';
								$empStatus='NA';
								$empid="";
								$fromDate=$value['fromdate'];
								$fromdate=date('Y-m-d',strtotime($value['fromdate']));
								if($value['BriefingId']!=""){
									$bf_id=$value['BriefingId'];
									$empid=$value['EmployeeID'];	
								}
								if($fromDate!=""){
								$date=date('d',strtotime($fromDate));
								$month=date('m',strtotime($fromDate));
								$year=date('Y',strtotime($fromDate));
								
								if(substr($date,0,1)==0){
								 	$date=str_replace('0','',$date);
								}
									
	$att_query="select  D".$date."  from calc_atnd_master  where  month='".$month."' and Year ='".$year."' and EmployeeID='".$value['EmployeeID']."'  and ( D".$date." like 'P%'  || D".$date."='H' || D".$date." ='HWP' )";
											
									$myDB=new MysqliDb();
									$att_array=$myDB->rawQuery($att_query);
									$error=$myDB->getLastError();
									$rowCount = $myDB->count;
									if($rowCount>0){
										foreach($att_array as $data_array)
										{
											$attendence=$data_array['D'.$date];
										}
									$ackdate=' ';
									$ack_query="select AcknowledgeDate from brf_acknowledge where  EmployeeID='".$empid."'  and BriefingId='".$bf_id."'";
									$myDB=new MysqliDb();
									$ack_array=$myDB->rawQuery($ack_query);
									$error=$myDB->getLastError();
									$rowCount = $myDB->count;
									if($rowCount>0){
										
										foreach($ack_array as $ack_array_val)
										{
											$AcknowledgeDate=$ack_array_val['AcknowledgeDate'];
											$ackdate=date('Y-m-d',strtotime($ack_array_val['AcknowledgeDate']));
											if($fromdate==$ackdate)
											{
												$AttendB='Yes';
											}
											else
											{
												$AttendB='No';
											}
										}
									}
									else{
										$AttendB='No';
										$AcknowledgeDate="";
									}
									
									if(($value['status']=='6' || $value['status']=='5' || $value['status']=='4' ) && ($value['designation']=='CSA' || $value['designation']=='Senior CSA')){
										if($value['view_for']=='All' || $value['view_for']=='onFloor' || $value['view_for']=='CSA' )
										{
											$applicable="Yes";
											//$quizApplicable='Yes';	
										}else{
											$applicable="NA";
											$quizApplicable='NA';
										}		
									}
									elseif(($value['status']=='6' || $value['status']=='5' || $value['status']=='4' ) && ($value['designation']!='CSA' || $value['designation']!='Senior CSA')){
										if($value['view_for']=='All' || $value['view_for']=='onFloor' ||  $value['view_for']=='Support' )
										{			
											$applicable="Yes";	
										}else{
											$applicable="NA";
											$quizApplicable='NA';
										}	
									}
							
								  	/*if($value['status']!='6'){	
								  		if($value['view_for']=='All' || $value['view_for']=='Training' )
								  		{
											$applicable="Yes";
											
										}else{
											$applicable="NA";
										}
								  	}*/
								  	if($value['status']=='6'){
										$empStatus='On Floor';
									}else
									if($value['status']=='4' || $value['status']=='5'){
										$empStatus='OJT';
									}
									else{
										$empStatus='NA';
										$applicable="NA";
											$quizApplicable='NA';
									}
								$AttemptedDate="";	
								$quizAtemptedDate="";	
								$quizStatus='No';	
								$bf_id=$value['id'];
								$empid=$value['EmployeeID'];
								$myDB=new MysqliDb();
								$select_question="select * from brf_question where BriefingID='".$bf_id."'";
								$myDB=new MysqliDb();
								$Qresult=$myDB->rawQuery($select_question);
								$error=$myDB->getLastError();
								$rowCount = $myDB->count;
								$correct=0;
								$correctAverage="";
								if($rowCount>0)
								{
									$tq=count($Qresult);
									$quizApplicable='Yes';
									$QuizDate=$value['fromdate'];
										
									if($bf_id!="" && $empid!="")
									{
									$AttemptedDate="";	
									$quizAtemptedDate="";
									$myDB=new MysqliDb();
									//echo "	SELECT b.Answer,a.BriefingID,a.QuestionID ,b.AttemptedDate,a.Answer AS CorrectAns,b.EmployeeID FROM brf_question a INNER JOIN brf_quiz_attempted b ON a.QuestionID=b.QuestionId where a.BriefingId='".$bf_id."' and b.EmployeeId='".$empid."'";
									$select_attempted=$myDB->rawQuery("	SELECT b.Answer,a.BriefingID,a.QuestionID ,b.AttemptedDate,a.Answer AS CorrectAns,b.EmployeeID FROM brf_question a INNER JOIN brf_quiz_attempted b ON a.QuestionID=b.QuestionId where a.BriefingId='".$bf_id."' and b.EmployeeId='".$empid."'");
									$error=$myDB->getLastError();
									$rowCount = $myDB->count;
						        	$c=0;
						        	if($rowCount>0){
						        		
						        		$AttemptedDate="";
											
					        			$user_ans="";
					        			$ans="";
					        			$correct=0;
					        			$InCorrect=0;
										//for($l=1;$l<=$tq;$l++)
										//{ 
											//$qarray=mysql_fetch_array($select_attempted);
										foreach($select_attempted as $select_attempted_val)
										{
											$user_ans=strtoupper($select_attempted_val['Answer']);
											$ans=strtoupper($select_attempted_val['CorrectAns']);
											if($user_ans==$ans){
												$correct++;
											}else{	
												$InCorrect++;
											}
											$c++;
											$AttemptedDate=$select_attempted[0]['AttemptedDate'];
							        		$quizAtemptedDate=date('Y-m-d',strtotime($AttemptedDate));
							        		if($quizAtemptedDate==$fromdate)
							        		{	
												$quizStatus="Yes";	
											}
											else
											{
												$quizStatus="No";	
											}
										}	
										//}
										if($tq>0){
											$correctAverage=$correct.' / '.$c;	
										}else{
											$correctAverage="";
										}
								}else{
									//$quizApplicable='NA';
									$quizStatus="No";
									$QuizDate="";
									$AttemptedDate="";	
								}				
												
							}else{
								$AttemptedDate="";		
							}
						}else{
							$quizStatus="No";	
							$quizApplicable='NA';
							$QuizDate="";
					 	 	$correctAverage="";
					 	 	$AttemptedDate="";
						}			
								/* When Employee Status has been Changed then Following Condition will be applicable */		
								if($applicable=='NA'){
									$quizApplicable='NA';
									$AttendB='No';
									$quizStatus='No';
								}
								
								echo '<tr style="vertical-align:top;">';							
								echo '<td class="client_name" style="vertical-align:top;">'.$value['EmployeeID'].'</td>';
								echo '<td class="process" style="vertical-align:top;" >'.$value['EmployeeName'].'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.$value['designation'].'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.$value['ReportTo'].'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.$value['Qa_ops'].'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.$value['clientname'].'</td>';
								echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >'.$value['Process'].'</td>';
								echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >'.$value['sub_process'].'</td>';
								echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >'.$empStatus.'</td>';
								echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >'.$value['view_for'].'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.date('Y-m-d',strtotime($value['fromdate'])).'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.$attendence.'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.$applicable.'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.$AttendB.'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.$value['heading'].'</td>';
								echo '<td class="subprocess" style="vertical-align:top;text-align:center;"  >'.$value['fromdate'].'</td>';
								echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >'.$AcknowledgeDate.'</td>';
								echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >'.$quizApplicable.'</td>';
								echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >'.$quizStatus.'</td>';
								echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >'.$QuizDate.'</td>';
								echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >'.$AttemptedDate.'</td>';
								echo '<td class="subprocess"  style="vertical-align:top;text-align:center;" >'.$correctAverage.'</td>';
							echo '</tr>';
							$i++;
								}			
									}		 
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
							//echo '<div id="div_error" class="slideInDown animated hidden">DATA NOT Found :: <code >'.$error.'</code> </div>';
							echo "<script>$(function(){ toastr.error('Data Not Found.:: <code >".$error."</code>'); }); </script>";
						}
					}	
					?>
				</div>	  
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div> 
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>