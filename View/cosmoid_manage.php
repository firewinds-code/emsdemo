<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
require_once(LIB.'PHPExcel/IOFactory.php');
// Global variable used in Page Cycle
$value=$counEmployee=$countProcess=$countClient=$countSubproc=0;

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
		$alert_msg="";
		$thisPage = REQUEST_SCHEME."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])){
		    $referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage){
		    $isPostBack = true;
		} 
		
		
		
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}

function coordinates($x)
{
 	return PHPExcel_Cell::stringFromColumnIndex($x);
}

?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Manage Cosmo-ID</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
<h4>Manage Cosmo-ID</h4>				

<!-- Form container if any -->
<div class="schema-form-section row" >

<script>
	$(function(){
		
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
									}
									,'pageLength'
						    ],
						    "bProcessing" : true,
							"bDestroy" : true,
							"bAutoWidth" : "50%",
							"sScrollY" : "192",
							"sScrollX" : "100%",
							"bScrollCollapse" : true,
							"bLengthChange" : false,
							"fnDrawCallback":function() {
								
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


	
	
	<div class="file-field input-field col s6 m6">
						<div class="btn">
							<span>Upload File</span>
							<input type="file" id="fileToUpload" name="fileToUpload" style="text-indent: -99999em;" >
							<br>
							
						</div>
						<div class="file-path-wrapper" >
							 <input class="file-path" type="text" style="">						    
						</div>
						</br>
					 </div>
		<div class="input-field col s6 m6 right-align">
				       <input  type="submit" name="UploadBtn" id="UploadBtn" value="Submit" class="btn waves-effect waves-green"/>
				       
				    </div>

		<?php
		
			$myDB=new MysqliDb();
						
			$chk_task=$myDB->query('select cosmo_ID, empid from cosmo_user_mapping');
			$my_error = $myDB->getLastError();
			if(empty($my_error))			
			{  
				$table='<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
				$table .='<th>Cosmo ID</th>';$table .='<th>Employee ID</th><thead><tbody>';
								    
				foreach($chk_task as $key=>$value)
				{
					
					$table .='<tr><td>'.$value['cosmo_ID'].'</td>';
					$table .='<td>'.$value['empid'].'</td></tr>';
										
				}
				$table .='</tbody></table></div></div>';
				echo $table;
			}
			else
			{
				echo "<script>$(function(){ toastr.error('No Data Found ".$my_error."'); }); </script>";
				
			}
		
			
			
			if(isset($_POST['UploadBtn']))
			{		
				$btnUploadCheck=1;
				$target_dir = ROOT_PATH.'Upload/';
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$uploader = $_SESSION['__user_logid'];
				$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
				$noData_Uploadfor = '';	
				$count =0;
				$mysql_error='';
				$validate = 0;
					// Check file size
					if ($_FILES["fileToUpload"]["size"] > 5000000) 
					{
					    echo "<script>$(function(){ toastr.error('Sorry, your file is too large ".$_FILES["fileToUpload"]["size"]." ') }); </script>";
					    $uploadOk = 0;
					}
					// Allow certain file formats
					if($FileType != "xlsx")
					{
					    echo "<script>$(function(){ toastr.error('Sorry, only XLS and XLSX files are allowed.') }); </script>";
					    $uploadOk = 0;
					}
					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0)
					{
					    echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded.') }); </script>";
					// if everything is ok, try to upload file
					} 
					else
					{
					    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
					    {
					       //echo "<script>$(function(){ toastr.error('The file ".basename( $_FILES["fileToUpload"]["name"])." has been uploaded') }); </script>";
					        $document = PHPExcel_IOFactory::load($target_file);
							// Get the active sheet as an array
							$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
							
							//print_r($activeSheetData.'<br/>');
							 echo "<script>$(function(){ toastr.info('Rows available In Sheet : ".(count($activeSheetData)-1)."') }); </script>";
							 $row_counter=0;
							 $flag=0;
							
				 				$row_counter = 0;
								foreach ($activeSheetData as $row)
				 				{
				 												 	
								 	if($row_counter>0 && !empty($row['A']) && $row['A']!='')
								 	{
								 		$myDB =new MysqliDb();
								 		$ds_cmid = $myDB->query('select cm_id from employee_map where EmployeeID="'.strtoupper($row['B']).'";');
								 		$mysql_error=$myDB->getLastError();
								 		if(empty($mysql_error) && count($ds_cmid)>0)
								 		{
											$cm_id = $ds_cmid[0]['cm_id'];
											if($cm_id=='27' || $cm_id=='58' || $cm_id=='83' || $cm_id=='45' || $cm_id=='46' || $cm_id=='126' || $cm_id=='90')
											{
												
												$myDB =new MysqliDb();
			                           			$ds = $myDB->query('call cosmoid_manage("'.strtoupper($row['A']).'","'.strtoupper($row['B']).'")');
			                            
			                            $mysql_error=$myDB->getLastError();
			                            
			                            if(empty($mysql_error))
			                            {
											$count++;
											
											$sql = 'SELECT distinct cast(LoggedIn as date) as LoggedIn  FROM CCSPAPR_final where Agent_ID="'.strtoupper($row['A']).'" order by LoggedIn;';
											$ds_rawapr = $myDB->rawQuery($sql);
											$mysql_error = $myDB->getLastError();
										   
										    $d2 = date('Y-m-d',strtotime("-20 days"));
										    if(empty($mysql_error) && count($ds_rawapr)>0)
										    {
												for ($i = 0; $i < count($ds_rawapr); $i++)
												{
													
													$d1 = date('Y-m-d',strtotime($ds_rawapr[$i]['LoggedIn']));
													if(strtotime($d1) >= strtotime($d2))
													{
														$url = URL.'View/calc_apr_one.php?empid='.strtoupper($row['B']).'&type=one&date='.date('Y-m-d',strtotime($ds_rawapr[$i]['LoggedIn']));
														$curl = curl_init();
														curl_setopt($curl, CURLOPT_URL, $url);
														curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
														curl_setopt($curl, CURLOPT_HEADER, false);
														$data = curl_exec($curl);
														curl_close($curl);
													}
													
												}
											}
											
											if(strtotime(date('Y-m-d',strtotime($ds_rawapr[0]['LoggedIn']))) >= strtotime($d2))
											{
												$url = URL.'View/calcRange_apr.php?empid='.strtoupper($row['B']).'&type=one&from='.date('Y-m-d',strtotime($ds_rawapr[0]['LoggedIn']));
											}
											else
											{
												$url = URL.'View/calcRange_apr.php?empid='.strtoupper($row['B']).'&type=one&from='.$d2;
											}
											
											$curl = curl_init();
											curl_setopt($curl, CURLOPT_URL, $url);
											curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
											curl_setopt($curl, CURLOPT_HEADER, false);
											$data = curl_exec($curl);
											curl_close($curl);
													
											
											/*$sql = 'select date,apr from cosmo_apr where agentid="'.strtoupper($row['A']).'";';
							//$sql = 'select agentid, employeeid,date,apr from cosmo_apr_temp where employeeid ="AE10189903"';
											$ds_apr = $myDB->rawQuery($sql);
											$mysql_error = $myDB->getLastError();
										    
										    if(empty($mysql_error) && count($ds_apr)>0)
										    {
												for ($i = 0; $i < count($ds_apr); $i++)
												{
													$empid =  strtoupper($row['B']);
													$date = strtotime($ds_apr[$i]['date']);
													$day = 'D'.date('j',$date);
													$month = date('n',$date);
													$year = date('Y',$date);
													$apr = date("h:i", strtotime($ds_apr[$i]['apr']));
													
													$sql = 'call update_final_apr("'.$empid.'", "'.$day.'", "'.$month.'", "'.$year.'", "'.$apr.'")';
													$myDB = new MysqliDb();
													$flag = $myDB->query($sql);
													$error = $myDB->getLastError();
													if(empty($error))
													{
														echo 'APR updated for EmpID : '.$empid. ' on : '.$day.'-'.$month.'-'.$year . '<br />';
													}
													//echo $sql;
													//echo 'EmpID  ::  => '.$empid. '<br />' . $day.$month.$year.$apr;
													
												}
											}*/
										}
										else
										{
											$validate = 1;
											$noData_Uploadfor .= '<p class="msgFile text-danger" style="font-weight:normal">'.$mysql_error.'</p>';
										}
									}
								}
								 		
								 		
			                            
			                            
								    }
									$row_counter++;
								}
							
							
							
							
				            if($count > 0)
				            {
								echo "<script>$(function(){ toastr.success('Total ".$count." Record are Updated Sucessfully.') }); </script>";
							}
							else
							{
								echo "<script>$(function(){ toastr.error('No Data Updated ') }); </script>";
							}
				             
				             
				              
				             if($validate == 1)
				             {
							 	echo '<div class="alert alert-danger"> Following Data Not Uploaded due to given reason ::'.$noData_Uploadfor.'</div><div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"></div></div>';
							 } 
				            
						}
						else
						{
				        	echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file') }); </script>";
				    	}
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
<script>
	$(function(){
		$('#alert_msg_close').click(function(){
			$('#alert_message').hide();
		});
		if($('#alert_msg').text()=='')
		{
			$('#alert_message').hide();
		}
		else
		{
			$('#alert_message').delay(10000).fadeOut("slow");
		}
	});
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>