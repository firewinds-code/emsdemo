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
 	function get_Bio_EmpID($org_ID)
    {
			$ID = null;            
            if (strlen($org_ID) == 10)
            {
                $ID = substr($org_ID,2,8);
                $ID = "9".$ID;
            }
            else if (strlen($org_ID) >= 11)
            {
                $ID = substr($org_ID,strlen($org_ID) -5 ,5);
                $ID = "9".$ID;
            }
            return($ID);
	}
	
	if(isset($_POST['btn_getit']))
	{
		$myDB=new MysqliDb();
		$rstls  = $myDB->rawQuery('call manage_alert("'.$_SESSION['__user_logid'].'")');
		if($rstls)
		{
			$location= URL.''; 
		    header("Location: $location");
		}
	}
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Alert : Attendance </span>

<!-- Main Div for all Page -->
<div class="pim-container " >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Alert : Attendance </h4>	
	 
<!-- Form container if any -->
	<div class="schema-form-section row" >
	<?php
					
					$myDB=new MysqliDb();
					$rstl = $myDB->query('select * from calc_atnd_master where EmployeeID ="'.$_SESSION['__user_logid'].'" and month="'.intval(date('m',time())).'" and year="'.intval(date('Y',time())).'"');
					$myDb1 = new  MysqliDb();
	                $apr= $myDb1->query('select * from hours_hlp where EmployeeID ="'.$_SESSION['__user_logid'].'" and month="'.intval(date('m',time())).'" and year="'.intval(date('Y',time())).'"');
	                $myDb2 = new  MysqliDb();
	                $atnd_status= $myDb2->query('select * from atnd_alert_master where EmployeeID ="'.$_SESSION['__user_logid'].'"');
	                
					if(count($rstl) > 0  && $rstl)
					{
						echo '<table class="data datatable"  id="myTable" style="width:100%;margin-top: 10px;margin-bottom: 10px;"><thead><tr>'.'<th>Date</th>'.'<th>Attendance</th>'.'<th>Roster In</th>'.'<th>Roster Out</th>'.'<th>Biometric In</th>'.'<th>Biometric Out</th>'.'<th>APR</th>'.'<th>Remark </th>'.'</tr></thead><tbody>';
						foreach($rstl as $key=>$val)
						{
							if($atnd_status && !empty($atnd_status[0]['alerton']))
							{
								$date1 = new DateTime($atnd_status[0]['alerton']);
								$date_now = new DateTime();
								$diff = $date_now->diff($date1);
								if(intval($diff->format('%m'))  > 1)
								{
									$begin = new DateTime(date('Y-m-d',strtotime('-2 days')));
								}
								else
								{
									$begin = new DateTime($atnd_status[0]['alerton']);
								}
 								
							}
							else
							{
								$begin = new DateTime(date('Y-m-d',strtotime('-2 days')));
							}
							
							$end   = new DateTime();
					 		
					 		for($i = $begin; $begin <= $end; $i->modify('+1 day'))
				            {
				            	
	                            $col = "D".intval($i->format('d'));
	                            /*$trin_temp  = new DateTime($i->format('Y-m-d').' '.$rst_bioinout[0]['InTime']);
								$trout_temp = new DateTime($i->format('Y-m-d').' '.$rst_bioinout[0]['OutTime']);
	
                    			if ($trout_temp<$trin_temp)$trout_temp->add(new DateInterval('P1D'));
								$diff_roster = date_diff($trin_temp,$trout_temp);
								$att_roster = $diff_roster->format('%H:%I');*/
	                            if($val[$col] == 'A' || $val[$col] == 'HWP') /*|| ($val['calc_atnd_master'][$col] == '-' && $i->format('Y-m-d') != date('Y-m-d',time()))|| ($val['calc_atnd_master'][$col] == '' && $i->format('Y-m-d') != date('Y-m-d',time())))*/
	                            {
	                            	echo '<tr>';
	                            	$myDb1 = new  MysqliDb();
	                            	$rst_roster = $myDB->query('select * from roster_temp where EmployeeID ="'.$_SESSION['__user_logid'].'" and DateOn="'.$i->format('Y-n-j').'"');
	                            	$myDb1 = new  MysqliDb();
	                            	$rst_bioinout= $myDB->query('select * from bioinout where EmployeeID ="'.get_Bio_EmpID($_SESSION['__user_logid']).'" and DateOn="'.$i->format('Y-m-d').'"');
	                            	
	                            	
	                            	$comment = '';
	                            	if($val[$col] == 'A')
	                            	{
	                            		if($val[$col] == 'A' && $rst_bioinout[0]['InTime'] != '' && $rst_bioinout[0]['OutTime'] != '' && ($apr[0][$col] =='' || $apr[0][$col] =='00:00' || (($apr[0][$col] < '04:30' && $rst_roster[0]['type_'] == 1) || ($apr[0][$col] < '05:00' && $rst_roster[0]['type_'] == 2))))
	                            		{
											$comment ='APR not completed';
										}
										else if($val['calc_atnd_master'][$col] == 'A' && $rst_bioinout[0]['InTime'] == '' && $rst_bioinout[0]['OutTime'] == '' && ($apr[0][$col] =='' || $apr[0][$col] =='00:00' || (($apr[0][$col] < '04:30' && $rst_roster[0]['type_'] == 1) || ($apr[0][$col] < '05:00' && $rst_roster[0]['type_'] == 2))))
		                            	{
											$comment ='Apply Backdated Leave';
										}
										else if($val['calc_atnd_master'][$col] == 'A' && $rst_bioinout[0]['InTime'] == '' && $rst_bioinout[0]['OutTime'] == '' && ((($apr[0][$col] >= '04:30' && $rst_roster[0]['type_'] == 1) || ($apr[0][$col] >= '05:00' && $rst_roster[0]['type_'] == 2)) || (($apr[0][$col] < '08:00' && $rst_roster[0]['type_'] == 1) || ($apr[0][$col] < '09:45' && $rst_roster[0]['type_'] == 2)|| ($apr[0][$col] < '04:30' && $rst_roster[0]['type_'] == 3))))
		                            	{
											$comment ='Apply Biometric Issue for A to HWP';
										}
										else if($val['calc_atnd_master'][$col] == 'A' && $rst_bioinout[0]['InTime'] == '' && $rst_bioinout[0]['OutTime'] == '' && ((($apr[0][$col] >= '08:00' && $rst_roster[0]['type_'] == 1) || ($apr[0][$col] >= '09:45' && $rst_roster[0]['type_'] == 2)|| ($apr[0][$col] >= '04:30' && $rst_roster[0]['type_'] == 3))))
		                            	{
											$comment ='Apply Biometric Issue for A to P';
										}
										else if($val['calc_atnd_master'][$col] == 'A' && ($rst_bioinout[0]['InTime'] == '' || $rst_bioinout[0]['OutTime'] == '') && (($apr[0][$col] >= '04:30' && $rst_roster[0]['type_'] == 1) || ($apr[0][$col] >= '05:00' && $rst_roster[0]['type_'] == 2)))
		                            	{
											$comment ='Apply Biometric Issue for A to H';
										}
										else if($val['calc_atnd_master'][$col] == 'A' && ($rst_bioinout[0]['InTime'] == '' || $rst_bioinout[0]['OutTime'] == '') && ((($apr[0][$col] < '04:30' && $rst_roster[0]['type_'] == 1) || ($apr[0][$col] < '05:00' && $rst_roster[0]['type_'] == 2))))
		                            	{
											$comment ='Apply Backdated Leave';
										}
										else
										{
											$comment ='Apply Backdated Leave';
										}
									}
									elseif($val['calc_atnd_master'][$col] == 'HWP')
									{
										
										if($val['calc_atnd_master'][$col] == 'HWP' && $rst_bioinout[0]['InTime'] != '' && $rst_bioinout[0]['OutTime'] != '' && (($apr[0][$col] >= '04:30' && $rst_roster[0]['type_'] == 1) || ($apr[0][$col] >= '05:00' && $rst_roster[0]['type_'] == 2)))
	                            		{
	                            			$tbin_temp  = new DateTime($i->format('Y-m-d').' '.$rst_bioinout[0]['InTime']);
											$tbout_temp = new DateTime($i->format('Y-m-d').' '.$rst_bioinout[0]['OutTime']);
				
	                            			if ($tbout_temp<$tbin_temp)$tbout_temp->add(new DateInterval('P1D'));
											$diff_bio = date_diff($tbin_temp,$tbout_temp);
											$att_bio = $diff_bio->format('%H:%I');
											
											$trin_temp  = new DateTime($i->format('Y-m-d').' '.$rst_roster[0]['InTime']);
											$trout_temp = new DateTime($i->format('Y-m-d').' '.$rst_roster[0]['OutTime']);
				
	                            			if ($trout_temp<$trin_temp)$trout_temp->add(new DateInterval('P1D'));
											$diff_ris = date_diff($trin_temp,$trout_temp);
											$att_roster = $diff_ris->format('%H:%I');
											
	                            			if($att_bio >= $att_roster && (($apr[0][$col] >= '08:00' && $rst_roster[0]['type_'] == 1) || ($apr[0][$col] >= '09:45' && $rst_roster[0]['type_'] == 2)))
											{
												$comment ='Shift Not Adhere';
											}
											elseif((($apr[0][$col] < '08:00' && $rst_roster[0]['type_'] == 1) || ($apr[0][$col] < '09:45' && $rst_roster[0]['type_'] == 2)) && $att_bio >= $att_roster)
											{
												$comment ='APR not completed';
											}
											elseif($att_bio <= $att_roster)
											{
												$comment ='Biometric hour not complete';
											}
											else
											{
												$comment ='Shift Not Adhere';
											}
											
											
											
										}
										elseif($val['calc_atnd_master'][$col] == 'HWP' && ($rst_bioinout[0]['InTime'] == '' || $rst_bioinout[0]['OutTime'] == '' ))
	                            		{
	                            			$comment ='One Entry Miss';
	                            			
											
										}
										elseif($val['calc_atnd_master'][$col] == 'HWP' && ($rst_bioinout[0]['InTime'] != '' && $rst_bioinout[0]['OutTime'] != '' ) && (($apr[0][$col] >= '04:30' && $rst_roster[0]['type_'] == 1) || ($apr[0][$col] >= '05:00' && $rst_roster[0]['type_'] == 2)))
	                            		{
	                            			$comment ='APR Issue';
											
										}
										else
										{
											$comment ='Shift Not Adhere';
										}
										
										
									}
									elseif($val['calc_atnd_master'][$col] == '-' || $val['calc_atnd_master'][$col] == '' )
									{
										$comment ='No Calculation';
									}
	                            	
									echo '<td>'.$i->format('jS F,Y').'</td>'.'<td>'.$val['calc_atnd_master'][$col].'</td>'.'<td>'.$rst_roster[0]['InTime'].'</td><td>'.$rst_roster[0]['OutTime'].'</td>'.'<td>'.$rst_bioinout[0]['InTime'].'</td>'.'<td>'.$rst_bioinout[0]['OutTime'].'</td><td>'.(empty($apr[0][$col])?'00:00':$apr[0][$col]).'</td><td>';
									
									
									if($comment == 'Apply Backdated Leave' || trim($comment) == 'Shift Not Adhere')
									{
										echo '<a href="'.URL.'/View/nJUiIis.php'.'" target="_blank">'.$comment.'</a>';
									}
									elseif(strpos($comment,'Apply Biometric Issue') !== false)
									{
										echo '<a href="'.URL.'/View/nJUiIis.php'.'" target="_blank">'.$comment.'</a>';
									}
									elseif($comment == 'APR not completed')
									{
										echo '<a href="'.URL.'/View/dWtIM.php'.'" target="_blank">'.$comment.'</a>';
									}
									else
									{
										echo  $comment;
									}
									echo '</td>';
									echo '</tr>';
								}
	                    			
	                            
				            }
				            
						}
						echo '</tbody></table>';
						
					}
					else
					{
						echo '<p class="text-success">Your calculation is not calculated yet for this month.</p>';	
					}
				//}
			
			?>
		
		
		<div class="input-field col s12 m12 row hidden" id="accordian">
		<h3>Back Dated Leave</h3>
		<div>
			<p class="header_para">Instruction :</p>
				<p>Can apply a Back Dated Leave from Exception Page if you want to apply Leave after taken.</p>
		</div>
		<h3>Biometric issue</h3>
		<div>
			<p class="header_para">Instruction :</p>
				<p>Can apply a Biometric Issue from Exception Page if your biometric punch is not exists.</p>
		</div>
		<h3>Shift Not Adhere</h3>
		<div>
			<p class="header_para">Instruction :</p>
				<p>You not adhere your shift according to your rostered shift,You can change your shift from Exception page (Shift Change for same day, Roster Change for Current roster week except same day) .</p>
		</div>
		<h3>No Calculation</h3>
		<div>
			<p class="header_para">Instruction :</p>
				<p>Can ask to your Supervisor or Account Head for the issue.Check may be your roster is not exists there.</p>
		</div>
		<h3>Other Help</h3>
		<div>
			<h4>Shift Change</h4>
			<div>
				<p class="header_para">Instruction :</p>
				<p>Can apply if you want ot change your shift for same day. But that have 2 hour stay (means if you want to change your shift then that will be 2 hour difference between your rostered shift</p>
			</div>
			<h4>Roster Change</h4>
			<div>
				<p class="header_para">Instruction :</p>
				<p>Can apply if you want ot change your shift for current Roster Week. But that have 2 hour stay (means if you want to change your shift then that will be 2 hour difference between your rostered shift</p>
			</div>
			<h4>Working on WeekOff</h4>
			<div><p class="header_para">Instruction :</p>
				<p>Can apply if you work on your Rostered Week Off and also there a shift Tab to select your shift for the same day.</p></div>
			<h4>Working on Leave</h4>
			<div><p class="header_para">Instruction :</p>
				<p>Can apply if you work on your Applied Leave and also there a shift Tab to select your shift for the same day.</p></div>
			
		</div>
			
		</div>
		<div class="input-field col s12 m12 right-align" >
				<button type="submit"  id="btn_getit"  name="btn_getit"  class="btn waves-effect waves-green">Got It !</button>
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
		
		$('#accordian').accordion({collapsible: true,heightStyle: "content",active: false
});	
		if(($('#myTable tbody tr').length  == 1 && $('#myTable tbody tr:first-child td').length == 0) || $('#myTable tbody tr').length  == 0)
		{
			$('#myTable tbody').html('<tr><td colspan="7" class="text-center">No issue  found in <b>Attendance</b></td></tr>');
			$('#btn_getit').click();
			//window.location = <?php echo "'".URL."'"; ?>;
		}
	});
	
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>