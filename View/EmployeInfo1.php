<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
//Code Snipt
require(ROOT_PATH.'AppCode/nHead.php');
$EmployeeID=$imsrc=$employeeName='';
$imsrc_ah = $imsrc_rt = $imsrc_oh= $imsrc_qh=$imsrc_th=$imsrc_qa='';
if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
{
	$EmployeeID=$_POST['EmployeeID'];
}
else
{
	$EmployeeID=$_REQUEST['empid'];
}
if($_SESSION['__user_logid'] == $EmployeeID || $_SESSION['__user_logid']=='CE10091236') {

}
else {
	$location= URL.'unknown';
	echo "<script>location.href='.$location.'</script>";
	exit();
}
/*if($_SESSION['__user_logid'] != $EmployeeID && ($_SESSION["__user_type"] == 'ADMINISTRATOR' || $_SESSION["__user_type"] == 'HR'))
{
	
}
else if($_SESSION['__status_ah'] == $_SESSION['__user_logid'] || $_SESSION['__user_logid'] == 'CE04146339')
{
	
}*/

?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Employee Information <?php echo $EmployeeID; ?></span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<style>
	td > .emp_image
	{
		float:left;
	}
	.responsive-table td:nth-child(2n - 1){
		min-width: 35%;
	}
</style>
<div class="form-div">
<h4>Employee Individual Information</h4>	
	<div class="schema-form-section row">
	<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID;?>"/>
	<div id="row" class="row had-container">
		<div class="col s3" id="div_me"  align="center">
		<div class="col s12">
	      <div class="card">
	        <div class="card-image" id="div_picture">
	          <img id="imgToempl" name="imgToempl" class="emp_image" src="../Style/images/agent-icon.png" style="height: 200px;width: 100%;margin-top: 8px;"/>
	          <span class="card-title white-text text-darken-4" style="background: #06060636;"><?php echo $EmployeeID;?></span>
	        </div>
	        
	        <div class="card-content">
	          <p id="empname_h4" ></p>
	        </div>					        
	      </div>
	    </div>
		</div>
		<div class="col s9">
		<ul class="collapsible">
		    <li>
		      <div class="collapsible-header topic"> Personal Details</div>
		      <div class="collapsible-body">
		      <div id="personal_div" class="list-container">
					<?php 
						if(isset($_REQUEST['empid'])||$EmployeeID!='')
							{
								$getDetails='call get_personal("'.$EmployeeID.'")';
								$myDB=new MysqliDb();
								$result=$myDB->rawQuery($getDetails);
								$MysqliError=$myDB->getLastError();
								if(empty($MysqliError))
                                 {
                                 	foreach($result as $key=>$value)
                                 	{
										       $employeeName=$value['EmployeeName'];
										       $DOB=$value['DOB'];
										       $FatherName=$value['FatherName'];
										       $MotherName=$value['MotherName'];
										       $Gender=$value['Gender'];
										       $BloodGroup=$value['BloodGroup'];
										       $MarriageStatus=$value['MarriageStatus'];
										       $Spouse=$value['Spouse'];
										       $MarriageDate=$value['MarriageDate'];
										       $ChildStatus=$value['ChildStatus'];
									}
									echo '<table class="responsive-table"><tr>';									
									echo "<td>Name </td><td><b>".$result[0]['EmployeeName'].'</b></td></tr><tr>';
									//echo "<td>Name </td><td><b>".$employeeName."</b></td></tr><tr>";
									echo "<td>Date Of Birth </td><td><b>".$DOB."</b></td></tr><tr>";
									echo "<td>Father`s Name </td><td><b>".$FatherName."</b></td></tr><tr>";
									echo "<td>Mother`s Name </td><td><b>".$MotherName."</b></td></tr><tr>";
									echo "<td>Gender </td><td><b>".$Gender."</b></td></tr><tr>";
									echo "<td>Blood Group </td><td><b>".$BloodGroup."</b></td></tr><tr>";
									echo "<td>Marital Status </td><td><b>".$MarriageStatus."</b></td></tr><tr>";
									echo "<td>Spouse </td><td><b>".$Spouse."</b></td></tr><tr>";
									echo "<td>Marriage Date </td><td><b>".$MarriageDate."</b></td></tr><tr>";
									echo "<td>Child Status </td><td><b>".$ChildStatus."</b></td>";
									echo '</tr></table>';
									if($value['img']!='')
									{
										$imsrc=URL.'Images/'.$value['img'];
										
									}
									$btnShow=' hidden';
									if($ChildStatus=='Yes')
									{
										
										//$sqlConnect = array('table' => 'child_details','fields' => '*','condition' =>"EmployeeID='".$EmployeeID."'"); 
										$sqlConnect =" select * from child_details where EmployeeID='".$EmployeeID."'"; 
										$myDB=new MysqliDb();
										$result=$myDB->rawQuery($sqlConnect);
										$MysqliError=$myDB->getLastError();
							            if(empty($MysqliError)){?>
											<div class="had-container">
											<table id="myTable1" class="highlight bordered" cellspacing="0" width="100%">
											    <thead>
											        <tr>
											            <th>Child Name</th>
											            <th>Child DOB</th>
											            <th>Child Gender</th>						            
											           
											        </tr>
											    </thead>
										    <tbody>					        
										       <?php
										        foreach($result as $key=>$value){
												echo '<tr>';	
												echo '<td>'.$value['ChildName'].'</td>';
												echo '<td>'.$value['ChildDob'].'</td>';
												echo '<td>'.$value['ChildGender'].'</td>';		
												echo '</tr>';
												}	
											?>			       
									    </tbody>
										</table>
											</div>
										<?php
									 } 
					
									}
								}
								else
								{
									echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
								}
							}
					?>
				</div></div>
		    </li>
		    <li>
		      <div class="collapsible-header topic"> Contact Details</div>
		      <div class="collapsible-body">
		      <div id="contact_div" class="list-container">
					<?php 
						if(isset($_REQUEST['empid'])||$EmployeeID!='')
						{
							$getDetails='call get_contact("'.$EmployeeID.'")';
							$myDB=new MysqliDb();
							$result=$myDB->rawQuery($getDetails);
							$MysqliError=$myDB->getLastError();
							if(empty($MysqliError))
							{
                             	foreach($result as $key=>$value)
                             	{
									echo '<table class="responsive-table"><tr>';									
									echo "<td>Mobile No </td><td><b>".$value['mobile'].'</b></td></tr><tr>';
									echo "<td>Alternate No </td><td><b>".$value['altmobile'].'</b></td></tr><tr>';
									echo "<td>Email ID </td><td><b>".$value['emailid'].'</b></td></tr>';
									echo '</table>';
								}
								$btnShow=' hidden';
							}else
							{
								echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
							}
						}
					?>
				<?php 
					
					//$sqlConnect = array('table' => 'doc_details','fields' => '*','condition' =>"EmployeeID='".$EmployeeID."'"); 
					$sqlConnect = "select * from doc_details where EmployeeID='".$EmployeeID."'"; 
					$myDB=new MysqliDb();
					$result=$myDB->rawQuery($sqlConnect);
					$MysqliError=$myDB->getLastError();
					if(empty($MysqliError)){?>
						<div class="had-container">
						<table id="myTable1" class="highlight bordered" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						           	<th>Document Type</th>						            
						            <th>Document List</th>	
						            <th>Document ID</th>						            
						            <!--<th style="width:100px;">Download </th>-->
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					        foreach($result as $key=>$value){
							echo '<tr>';	
							echo '<td>'.$value['doc_type'].'</td>';
							echo '<td>'.$value['doc_stype'].'</td>';					
							echo '<td>'.$value['dov_value'].'</td>';
							/*echo '<td><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png"  onclick="javascript:return Download(this);"  title="View Data Item" data="'.$value['doc_file'].'" /></td>';*/
							echo '</tr>';
							}	
						?>			       
				    </tbody>
					</table>
						</div>
					<?php
				 } 
				?>
			</div>
			</div>
	    </li>
	    <li>
	      <div class="collapsible-header topic"> Address Details</div>
	      <div class="collapsible-body">
	      <div id="Address_div" class="list-container">
			<?php 
				if(isset($_REQUEST['empid'])|| $EmployeeID!='')
				{
					
					$getDetails='call get_address("'.$EmployeeID.'")';
					$myDB=new MysqliDb();
					$result=$myDB->rawQuery($getDetails);
					$MysqliError=$myDB->getLastError();
					if(empty($MysqliError))
					{
                     	foreach($result as $key=>$value)
                     	{
					       $address=$value['address'];
					       $country=$value['country'];
					       $state=$value['state'];
					       $district=$value['district'];
					       $city=$value['city'];
					       $tehsil=$value['tehsil'];
					       $other=$value['other'];
					       $zip=$value['zip'];
					       
					       $address_p=$value['address_p'];
					       $country_p=$value['country_p'];
					       $state_p=$value['state_p'];
					       $district_p=$value['district_p'];
					       $city_p=$value['city_p'];
					       $tehsil_p=$value['tehsil_p'];
					       $other_p=$value['other_p'];
					       $zip_p=$value['zip_p']; 
						}
						
						echo '<table class="responsive-table"><tr>';
						
						echo '<tr><th colspan="2">Current Address</th></tr>';
						
						
						echo "<td>Address </td><td><b>".$address.'</b></td></tr><tr>';
						echo "<td>Country </td><td><b>".$country.'</b></td></tr><tr>';
						echo "<td>State </td><td><b>".$state.'</b></td></tr><tr>';
						echo "<td>District </td><td><b>".$district.'</b></td></tr><tr>';
						echo "<td>City </td><td><b>".$city.'</b></td></tr><tr>';
						echo "<td>Tehsil </td><td><b>".$tehsil.'</b></td></tr><tr>';
						echo "<td>Landmark   </td><td><b>".$other.'</b></td></tr><tr>';
						echo "<td>Pin Code </td><td><b>".$zip.'</b></td></tr><tr>';
						
						echo '<tr><th  colspan="2">Permanent Address</th></tr>'.'</b></td></tr><tr>';
						
						echo "<td>Address </td><td><b>".$address_p.'</b></td></tr><tr>';
						echo "<td>Country </td><td><b>".$country_p.'</b></td></tr><tr>';
						echo "<td>State </td><td><b>".$state_p.'</b></td></tr><tr>';
						echo "<td>District </td><td><b>".$district_p.'</b></td></tr><tr>';
						echo "<td>City </td><td><b>".$city_p.'</b></td></tr><tr>';
						echo "<td>Tehsil </td><td><b>".$tehsil_p.'</b></td></tr><tr>';
						echo "<td>Landmark  </td><td><b>".$other_p.'</b></td></tr><tr>';
						echo "<td>Pin Code </td><td><b>".$zip_p.'</b></td></tr>';	
						echo '</table><br />';
						
					}else
					{
						echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
					}
				}
			?>
		</div></div>
		    </li>
			<li>
		      <div class="collapsible-header topic"> Education Details</div>
		      <div class="collapsible-body">
		      <div id="Education_div" class="list-container">
					 <?php 
						$myDB=new MysqliDb();
						$myDB->where("EmployeeID",$EmployeeID);
						$result = $myDB->get("education_details");
						
						$MysqliError=$myDB->getLastError();
						if(empty($MysqliError)){?>
							<table id="myTable1" class="highlight bordered" cellspacing="0" width="100%">
							    <thead>
							        <tr>
							            <th>Education Level</th>							            
							            <th>Education Name</th>							            
							            <th>Specialization</th>							            
							            <th>Board/Univercity</th>							            	
							            <th>College</th>	
							            <th>Type</th>	
							            <th>PassingYear</th>	
							            <th class="hidden">Division</th>	
							            <th class="hidden">Percentage</th>
							            <th class="hidden">EduFile</th>		
							           <!-- <th style="width:100px;">Download </th>-->
							        </tr>
							    </thead>
						    <tbody>					        
						       <?php
						        foreach($result as $key=>$value){
								echo '<tr>';	
								echo '<td class="edu_level">'.$value['edu_level'].'</td>';
								echo '<td class="edu_name">'.$value['edu_name'].'</td>';
								echo '<td class="specialization">'.$value['specialization'].'</td>';
								echo '<td class="board">'.$value['board'].'</td>';				
								echo '<td class="college">'.$value['college'].'</td>';
								echo '<td class="edu_type">'.$value['edu_type'].'</td>';
								echo '<td class="passing_year">'.$value['passing_year'].'</td>';
								echo '<td class="division hidden">'.$value['division'].'</td>';
								echo '<td class="percentage hidden">'.$value['percentage'].'</td>';
								echo '<td class="edu_file hidden">'.$value['edu_file'].'</td>';					
								/*echo '<td><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png"  onclick="javascript:return Download(this);"  title="View Data Item" data="'.$value['edu_file'].'" /></td>';*/
							
								echo '</tr>';
								}	
							?>			       
					    </tbody>
						</table><br />
						<?php
					 } else
							{
								echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
							}
					?>
					
				</div>
			</div>		      
		    </li>
			<li>
		      <div class="collapsible-header topic"> Experience Details</div>
		      <div class="collapsible-body">
		      <div id="Experience_div" class="list-container">
					 	<?php 
						
						$myDB=new MysqliDb();
						$myDB->where("EmployeeID",$EmployeeID);
						$result = $myDB->get("experince_details");
						
						$MysqliError=$myDB->getLastError();
						if(empty($MysqliError)){?>
							<table id="myTable1" class="highlight bordered" cellspacing="0" width="100%">
							    <thead>
							        <tr>
							           
							           	<th>Exp Type</th>
							            <th>Organization</th>
							            <th>Location</th>
							            <th>From</th>			
							            <th>To</th>			
							            <th>Designation</th>	
							            <th>Last Drawn CTC(Monthly)</th>	
							            <!--<th>File</th>						            
							            <th style="width:100px;">Manage Doc </th>-->
							        </tr>
							    </thead>
						    <tbody>					        
						       <?php
						        foreach($result as $key=>$value){
								echo '<tr>';
								if($value['exp_type'] == 'Fresher')
								{
									echo '<td class="exp_type" colspan="8">'.$value['exp_type'].'</td>';
								}
								else
								{
									echo '<td class="exp_type">'.$value['exp_type'].'</td>';
									echo '<td class="employer">'.$value['employer'].'</td>';
									echo '<td class="location">'.$value['location'].'</td>';
									echo '<td class="from">'.$value['from'].'</td>';
									echo '<td class="to">'.$value['to'].'</td>';
									echo '<td class="designation">'.$value['designation'].'</td>';
									echo '<td class="discription">'.$value['discription'].'</td>';	
									/*echo '<td class="file">'.$value['file'].'</td>';*/
								}
								
								/*echo '<td><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png" onclick="javascript:return Download(this);"  title="View Data Item" data="'.$value['file'].'" /></td>';*/
								
								echo '</tr>';
								}	
							?>			       
					    </tbody>
						</table><br />
						<?php
					 } else
							{
								echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
							}
					?>
					
				</div>
		      </div>
		    
		    </li>
			<li>
		      <div class="collapsible-header topic"> Employee Map</div>
		      <div class="collapsible-body">
		      <div id="empmap_div" class="list-container">
					<?php 
						if(isset($_REQUEST['empid'])||$EmployeeID!='')
						{
							$getDetails='call get_empmap_forme("'.$EmployeeID.'")';
							$myDB=new MysqliDb();
							$result = $myDB->rawQuery($getDetails);
							$MysqliError=$myDB->getLastError();
							if(empty($MysqliError)){
								
								foreach($result as $key=>$value){
								echo '<table class="highlight bordered" cellspacing="0" width="100%">
								<tr>';	
								echo "<td>Department </td><td><b>".$value['dept_name'];echo '</b></td></tr><tr>';
								echo "<td>Client </td><td><b>".$value['client_name'];echo '</b></td></tr><tr>';
								echo "<td>Process </td><td><b>".$value['process'];echo '</b></td></tr><tr>';
								echo "<td>SubProcess </td><td><b>".$value['sub_process'];echo '</b></td></tr><tr>';
								echo "<td>Designation </td><td><b>".$value['Designation'];echo '</b></td></tr><tr>';
								echo "<td>Date Of Joining </td><td><b>".$value['dateofjoin'];echo '</b></td></tr><tr>';
								echo "<td>Function </td><td><b>".$value['function'];echo '</b></td></tr><tr>';
								//echo "<td>Emp Level </td><td><b>".$value['emp_level'];
								echo '</b></td></tr></table><br />';
								}
							}
							else
							{
								echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
							}
						}
					?>
				</div>
		      </div>
		    
		    </li>
			<li>
		      <div class="collapsible-header topic"> Reporting Map</div>
		      <div class="collapsible-body">
		      <div id="reporting_div" class="list-container">
					<?php 
						if(isset($_REQUEST['empid'])||$EmployeeID!='')
						{
							$getDetails='call get_reporting_map_byempid("'.$EmployeeID.'")';
							$myDB=new MysqliDb();
							$result_all = $myDB->rawQuery($getDetails);
							$MysqliError = $myDB->getLastError();
							if(empty($MysqliError)){
								echo '<table class="responsive-table">';
								echo '<tr>';
								if(!empty($result_all[0]['account_head']))
								{
									$myDB=new MysqliDb();
									$result_ah = $myDB->query('select img,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "'.$result_all[0]['account_head'].'" limit 1');
									if(count($result_ah) > 0 && $result_ah)
									{
										$contact_ah = $result_ah[0]['mobile'];
										$imsrc_ah = $result_ah[0]['img'];
									}
									
								}
								if(!empty($imsrc_ah))
								{
									$imsrc_ah=URL.'Images/'.$imsrc_ah;
									
								}
								echo "<td >Account Head </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>".'<img  id="imgToempl_ah" name="imgToempl_ah" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>'."&nbsp;&nbsp;<b>".$result_all[0]['AH'];
								if(!empty($contact_ah))
								{
									echo '&nbsp;&nbsp;(&nbsp;'.$contact_ah.'&nbsp;)';
								}
								echo '</b></td></tr>';
							
								$srQ='select function_id from employee_map inner join df_master on df_master.df_id = employee_map.df_id where EmployeeID = "'.$EmployeeID.'"';
							    $myDB=new MysqliDb();
							    $funcitonID=$myDB->query($srQ);
							
							
								if($funcitonID[0]['function_id']== 7 || $funcitonID[0]['function_id'] == 8 || $funcitonID[0]['function_id'] == 10)
								{
									echo '<tr>';
									if(!empty($result_all[0]['oh']))
									{
										$myDB=new MysqliDb();
										$result_oh = $myDB->query('select img,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "'.$result_all[0]['oh'].'" limit 1');
										if(count($result_oh) > 0 && $result_oh)
										{
											$contact_oh = $result_oh[0]['mobile'];
											$imsrc_oh = $result_oh[0]['img'];
										}
										
									}
									if(!empty($imsrc_oh))
									{
										$imsrc_oh=URL.'Images/'.$imsrc_oh;
										
									}
									echo "<td>Operation Head </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>".'<img  id="imgToempl_oh" name="imgToempl_oh" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>'."&nbsp;&nbsp;<b>".$result_all[0]['OH'];
									if(!empty($contact_oh))
									{
										echo '&nbsp;&nbsp;(&nbsp;'.$contact_oh.'&nbsp;)';
									}
									
									echo '</b></td></tr>';
									
									
									echo '<tr>';
									if(!empty($result_all[0]['qh']))
									{
										$myDB=new MysqliDb();
										$result_qh = $myDB->query('select img,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "'.$result_all[0]['qh'].'" limit 1');
										if(count($result_qh) > 0 && $result_qh)
										{
											$contact_qh = $result_qh[0]['mobile'];
											$imsrc_qh = $result_qh[0]['img'];
										}
										
									}
									if(!empty($imsrc_qh))
									{
										$imsrc_qh=URL.'Images/'.$imsrc_qh;
										
									}
									echo "<td>Quality Head </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>".'<img  id="imgToempl_qh" name="imgToempl_qh" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>'."&nbsp;&nbsp;<b>".$result_all[0]['QH'];
									if(!empty($contact_qh))
									{
										echo '&nbsp;&nbsp;(&nbsp;'.$contact_qh.'&nbsp;)';
									}
									
									echo '</b></td></tr>';	
									
									
									
									echo '<tr>';
									if(!empty($result_all[0]['th']))
									{
										$myDB=new MysqliDb();
										$result_th = $myDB->query('select img,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "'.$result_all[0]['th'].'" limit 1');
										if(count($result_th) > 0 && $result_th)
										{
											$contact_th = $result_th[0]['mobile'];
											$imsrc_th = $result_th[0]['img'];
										}
										
									}
									if(!empty($imsrc_th))
									{
										$imsrc_th=URL.'Images/'.$imsrc_th;
										
									}
									echo "<td>Training Head </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>".'<img  id="imgToempl_th" name="imgToempl_th" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>'."&nbsp;&nbsp;<b>".$result_all[0]['TH'];
									if(!empty($contact_th))
									{
										echo '&nbsp;&nbsp;(&nbsp;'.$contact_th.'&nbsp;)';
									}
									
									echo '</b></td></tr>';	
									
									
									
									echo '<tr>';//status_table.Qa_ops
									
									if(!empty($result_all[0]['Qa_ops']))
									{
										
										$myDB=new MysqliDb();
										$result_qa = $myDB->query('select img,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "'.$result_all[0]['Qa_ops'].'" limit 1');
										if(count($result_qa) > 0 && $result_qa)
										{
											$contact_qa = $result_qa[0]['mobile'];
											$imsrc_qa = $result_qa[0]['img'];
										}
										
										
									}
									if(!empty($imsrc_qa))
									{
										$imsrc_qa=URL.'Images/'.$imsrc_qa;
										
									}
									echo "<td >Quality Analyst </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>".'<img  id="imgToempl_qa" name="imgToempl_qa" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>'."&nbsp;&nbsp;<b>".$result_all[0]['QA_OPS'];
									if(!empty($contact_qa))
									{
										echo '&nbsp;&nbsp;(&nbsp;'.$contact_qa.'&nbsp;)';
									}
									
									echo '</b></td></tr>';	
								}
								
								
								if(!empty($result_all[0]['ReportTo']))
								{
									$myDB=new MysqliDb();
									$result_rt = $myDB->query('select img,EmployeeName,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID where personal_details.EmployeeID = "'.$result_all[0]['ReportTo'].'" limit 1');
								
									if(count($result_rt) > 0 && $result_rt)
									{
										$contact_rt =$result_rt[0]['mobile'];
										$imsrc_rt = $result_rt[0]['img'];
										$rtname = $result_rt[0]['EmployeeName'];
									}
									
								}
								if(!empty($imsrc_rt))
								{
									$imsrc_rt=URL.'Images/'.$imsrc_rt;
									
								}
								echo "<td >Reporting </td><td  style='padding-left: 5px;vertical-align: middle;line-height: 40px;'>".'<img  id="imgToempl_rt" name="imgToempl_rt" class="emp_image" src="../Style/images/agent-icon.png"  style="height: 40px;width: 40px;border-radius: 10px;"/>'."&nbsp;&nbsp;<b>".$rtname;
								//$result_all[0]['ReportTo']
								
								if(!empty($contact_rt))
								{
									echo '&nbsp;&nbsp;(&nbsp;'.$contact_rt.'&nbsp;)';
								}
								echo '</b></td></tr><tr>';
								
								echo '</b></td></tr></table><br />';
									
								
							}
							else
							{
								echo "<script>$(function(){ toastr.error('No Record exist.'); }); </script>";
							}
						}
					?>
				</div>
		      </div>
		    
		    </li>
		    
		  
		    <li>
		      <div class="collapsible-header topic">Warning Docs</div>
		      <div class="collapsible-body">
		      <div id="Education_div" class="list-container">
					 <?php 
						$myDB=new MysqliDb();						
						//$myDB->where("EmployeeID",$EmployeeID);
						
						$sqlConnect="SELECT i.ah_status,i.ah_subtype,i.ah_Datetime,d.Title,d.Document FROM warning_rth i inner join warning_rth_documents d on i.EmployeeID=d.EmployeeID and i.id=d.DataId where i.EmployeeID='".$EmployeeID."' group by d.DataId;";
						$result=$myDB->query($sqlConnect);
						$MysqliError=$myDB->getLastError();
						if(empty($MysqliError)){?>
							<table id="myTable1" class="highlight bordered" cellspacing="0" width="100%">
							    <thead>
							    <tr><th colspan=2 >Warning and Refer to HR Letter</th><th>Issued</th><th>Document</th>
							           <th>Action</th></tr>
							        <tr><th>Type</th><th>Sub Type</th><th>Date</th><th>Name</th>
							           <th>Download </th></tr>
							    </thead>
						    <tbody>					        
						       <?php
						        foreach($result as $key=>$value){
								echo '<tr>';
								echo '<td class="ah_status">'.$value['ah_status'].'</td>';
								echo '<td class="ah_subtype">'.$value['ah_subtype'].'</td>';
								echo '<td class="ah_Datetime">'.$value['ah_Datetime'].'</td>';
								echo '<td class="Title">'.$value['Title'].'</td>';	
								if($_SESSION['__user_logid']=='CE03070003')
								{
									echo '<td><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png"  
								onclick="javascript:return Download2(this);"  title="Download File" data="'.$value['Document'].'" /></td>';
								}
								else
								{
									echo '<td><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png"  
								title="Download File" data="'.$value['Document'].'" /></td>';
								}
								echo '</tr>';
								}	
							?>			       
					    </tbody>
						</table><br />
						<?php
					 } else
							{
								echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
							}
					?>
					
				</div>
			</div>		      
		    </li>
		    
		    <li>
		      <div class="collapsible-header topic"> Bank,PF & ESIC Details</div>
		      <div class="collapsible-body">
		      <div id="empmap_div" class="list-container">
					<?php 
						if(isset($_REQUEST['empid'])||$EmployeeID!='')
						{
							$getDetails='call get_salarydetails("'.$EmployeeID.'")';
							$myDB=new MysqliDb();
							$result = $myDB->rawQuery($getDetails);
							$MysqliError=$myDB->getLastError();
							if(empty($MysqliError)){
								
								foreach($result as $key=>$value){
								echo '<table class="highlight bordered" cellspacing="0" width="100%">
								<tr>';	
								/*echo "<td>CTC </td><td><b>".$value['ctc'];echo '</b></td></tr><tr>';
								echo "<td>Take Home </td><td><b>".$value['net_takehome'];echo '</b></td></tr><tr>';
								echo "<td>Provident Fund </td><td><b>".$value['pf'];echo '</b></td></tr><tr>';
								echo "<td>ESIC </td><td><b>".$value['esis'];echo '</b></td></tr><tr>';
								echo "<td>PF Number </td><td><b>".$value['pf_account'];echo '</b></td></tr><tr>';*/
								echo "<td>ESIC Number </td><td><b>".$value['esi_no'];echo '</b></td></tr><tr>';
								echo "<td>UAN Number </td><td><b>".$value['uan_no'];echo '</b></td></tr><tr>';
								echo "<td>Bank Name </td><td><b>".$value['BankName'];echo '</b></td></tr><tr>';
								echo "<td>Bank Account Number </td><td><b>".$value['AccountNo'];echo '</b></td></tr><tr>';
								echo "<td>Name as per Bank </td><td><b>".$value['Name'];echo '</b></td></tr><tr>';
								echo "<td>IFSC </td><td><b>".$value['IFSC'];echo '</b></td></tr><tr>';
								//echo "<td>Emp Level </td><td><b>".$value['emp_level'];
								echo '</b></td></tr></table><br />';
								}
							}
							else
							{
								echo "<script>$(function(){ toastr.error('No Data Exist'); }); </script>";
							}
						}
					?>
				</div>
		      </div>
		    
		    </li>
		   
		
		</ul>
		
		</div>
	</div>	
	
	<input type="hidden" name="imgname" id="imgname"  value="<?php echo $imsrc; ?>"/>
	<input type="hidden" name="empnameinput" id="empnameinput"  value="<?php echo $employeeName; ?>"/>
	</div>
</div>
</div>
</div>
<script>
	$(function(){
		var employeeName=<?php echo '"'.$employeeName.'"'; ?>;
		$('#empname_h4').text(employeeName);
		var imgsrc=<?php echo '"'.$imsrc.'"'; ?>;
		
		if(imgsrc=="") {
			$('#imgToempl').attr('src','../Style/images/agent-icon.png');
		} 
		else { 
			$('#imgToempl').attr('src',imgsrc);
		}
		
		
		$("#imgToempl").error(function(){
	        $(this).attr('src', '../Style/images/agent-icon.png');
	    });
	    
	    
	    var imgsrc_ah=<?php echo '"'.$imsrc_ah.'"'; ?>;
		
		if(imgsrc_ah=="") {
			$('#imgToempl_ah').attr('src','../Style/images/User-icon.png');
		} 
		else { 
			$('#imgToempl_ah').attr('src',imgsrc_ah);
		}
		
		
		$("#imgToempl_ah").error(function(){
	        $(this).attr('src', '../Style/images/User-icon.png');
	    });
		
		
		var imgsrc_oh=<?php echo '"'.$imsrc_oh.'"'; ?>;
		
		if(imgsrc_oh=="") {
			$('#imgToempl_oh').attr('src','../Style/images/User-icon.png');
		} 
		else { 
			$('#imgToempl_oh').attr('src',imgsrc_oh);
		}
		
		
		$("#imgToempl_oh").error(function(){
	        $(this).attr('src', '../Style/images/User-icon.png');
	    });
	    
	    
	    var imgsrc_qh=<?php echo '"'.$imsrc_qh.'"'; ?>;
		
		if(imgsrc_qh=="") {
			$('#imgToempl_qh').attr('src','../Style/images/User-icon.png');
		} 
		else { 
			$('#imgToempl_qh').attr('src',imgsrc_qh);
		}
		
		
		$("#imgToempl_qh").error(function(){
	        $(this).attr('src', '../Style/images/User-icon.png');
	    });
	    
	    var imgsrc_th=<?php echo '"'.$imsrc_th.'"'; ?>;
		
		if(imgsrc_th=="") {
			$('#imgToempl_th').attr('src','../Style/images/User-icon.png');
		} 
		else { 
			$('#imgToempl_th').attr('src',imgsrc_th);
		}
		
		
		$("#imgToempl_th").error(function(){
	        $(this).attr('src', '../Style/images/User-icon.png');
	    });
	    
	    var imgsrc_qa=<?php echo '"'.$imsrc_qa.'"'; ?>;
		
		if(imgsrc_qa=="") {
			$('#imgToempl_qa').attr('src','../Style/images/User-icon.png');
		} 
		else { 
			$('#imgToempl_qa').attr('src',imgsrc_qa);
		}
		
		
		$("#imgToempl_qa").error(function(){
	        $(this).attr('src', '../Style/images/User-icon.png');
	    });
	    
		var imsrc_rt=<?php echo '"'.$imsrc_rt.'"'; ?>;
		
		if(imsrc_rt=="") {
			$('#imgToempl_rt').attr('src','../Style/images/User-icon.png');
		} 
		else { 
			$('#imgToempl_rt').attr('src',imsrc_rt);
		}
		
		
		$("#imgToempl_rt").error(function(){
	        $(this).attr('src', '../Style/images/User-icon.png');
	    });
		
		
	});
	function Download(el)
	{
		if($(el).attr('data')!='')
				{
					window.open("../OtDocs/"+$(el).attr("data"));
				}
				else
				{
					alert('No File Exist');
				}
	}
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
