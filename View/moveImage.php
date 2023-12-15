<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
ini_set('display_errors',1);
//error_reporting(-1);
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Move Files</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Move Files</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >


   
<?php

					$target_dir = ROOT_PATH.'Upload/';
					//$query="select EmployeeID from whole_dump_emp_data where emp_status='InActive' order by DOJ desc limit 0,5 ";
					
					//$myDB=new mysql();
				//	$result=mysql_query($query);
					/*function moveInactiveImage($imageid){
						$mesg="";
						if(file_exists(ROOT_PATH.'Images/'.$imageid))
						{
							$file1=ROOT_PATH.'Images/'.$imageid;
							$file2= ROOT_PATH.'Images_Inactive/'.$imageid;
							if (!copy($file1, $file2)) {
								   $mesg.= "failed to copy $imageid...\n";
							}else{
								@unlink(ROOT_PATH.'ImagesInactive/'.$imageid);
							}
						}else{
							$mesg.= 'Not found ='.URL.'Images/'.$imageid.'<br>';
							//echo "<br>";
						}
						return $mesg;
					}
					*/
				/*	$dir=ROOT_PATH."Upload";
					$dateLimit = date('Y-m-01',strtotime('-3 months')); 
						if (is_dir($dir)) {
							//echo "yess";
						    if ($dh = opendir($dir)) {
						        while (($file = readdir($dh)) !== false) {
						           // echo "filename: .".$file."<br />";
						           	 $filedate=date("Y-m-d", filemtime($dir.'/'.$file));
						             if( $filedate<$dateLimit){
						             	$file1=$dir.'/'.$file;
										$file2= ROOT_PATH.'Upload_Old/'.$file;
										if (!copy($file1, $file2)) {
											    echo "failed to copy $file...\n";
										}else{
											//@unlink(ROOT_PATH.'Upload/'.file);
											echo "delete the file";
										}
						             	
									 }
						        }
						        closedir($dh);
						       
						    }
						}
					while($data_array=mysql_fetch_array($result))
					{
						$imageid=$data_array['EmployeeID'].'.jpg';
						//echo function moveInactiveImage($imageid);
						echo ROOT_PATH.'Images/'.$imageid;
						echo "<br>";
						
						echo "<br>";
						if(file_exists(ROOT_PATH.'Images/'.$imageid))
						{
							?>
							<img src="<?php echo URL.'Images/'.$imageid;?>" height='100' width='100'>
							<?php
							$file1=ROOT_PATH.'Images/'.$imageid;
							$file2= ROOT_PATH.'Images_Inactive/'.$imageid;
							if (!copy($file1, $file2)) {
								    echo "failed to copy $imageid...\n";
							}else{
								//@unlink(ROOT_PATH.'Images/'.$imageid);
							}
						}else{
							echo 'Not found ='.URL.'Images/'.$imageid;
							echo "<br>";
						}
						*/
						
						
						/////for DOC directory
						$myDB=new MysqliDb();
					//	echo "SELECT doc_type,doc_file FROM doc_details  where doc_file!='' limit 5";
						$select_doc_query=$myDB->query("SELECT doc_type,doc_file FROM doc_details  where doc_file!=''");
						if(count($select_doc_query)>0){
							foreach($select_doc_query as $doc_array)
							{
							
								$doc_file=$doc_array['doc_file'];
								$doc_type=$doc_array['doc_type'];
								if(file_exists(ROOT_PATH.'Docs/'.$doc_file))
								{
									
									$docfilepath='';
									if($doc_type=="Proof of Identity"){
										$docfilepath="IdentityProof/";
									}else
									if($doc_type=="Proof of Address"){
										$docfilepath="AddressProof/";
									}
									else
									if($doc_type=="FnF"){
										$docfilepath="FnF/";
									}
									else
									if($doc_type=="Apology Letter"){
										$docfilepath="ApologyLetter/";
									}
									else
									if($doc_type=="Warning Letter"){
										$docfilepath="WarningLetter/";
									}
									else
									if($doc_type=="Other"){
										$docfilepath="Other/";
									}
									
									$file1=ROOT_PATH.'Docs/'.$doc_file;
									$file2= ROOT_PATH.'Docs/'.$docfilepath.$doc_file;
									if (!copy($file1, $file2)) {
										    echo "failed to copy doc file $doc_file...\n";
									}else{
					//@unlink(ROOT_PATH.'Docs/'.$imageid);
							unlink($_SERVER['DOCUMENT_ROOT']."/ems/Docs/".$doc_file);
									}
								}else{
									echo 'doc file Not found ='.URL.'Docs/'.$doc_file;
									echo "<br>";
								}
							}
						}
					
					
					
					
					
					
					
					
					
					//Select from 'Edu and Exp  Files' into  Experience directory
					//echo "SELECT file FROM experince_details  where file!='' limit 5";
					//echo "<br>";
						/*$myDB=new MysqliDb();
						$select_doc_query=$myDB->query("SELECT file FROM experince_details where file!='' limit 5  ");
						if(count($select_doc_query)>0){
							foreach($select_doc_query as $edoc_array)
							{
								
							
								$edoc_file=$edoc_array['file'];
								//unlink($_SERVER['DOCUMENT_ROOT']."/ems/Experience/".$edoc_file);
								if(file_exists(ROOT_PATH.'Edu and Exp  Files/'.$edoc_file))
								{
									
									
									echo $file1=ROOT_PATH.'Edu and Exp  Files/'.$edoc_file;
									$file2= ROOT_PATH.'Experience/'.$edoc_file;
									if (!copy($file1, $file2)) {
										    echo "failed to copy Edu doc file $edoc_file...\n";
									}else{
										//unlink($_SERVER['DOCUMENT_ROOT']."/ems/Edu and Exp  Files/".$edoc_file);
									}
								}else{
									echo "<br>";
									echo 'Edu doc file Not found ='.URL.'Edu and Exp  Files/'.$edoc_file;
									echo "<br>";
								}
							}
						}*/
						
					
					
			
					
						
				
				?>
			    
				</div>
			
			  
			 
		</div>
	</div>

	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>  

<?php include(ROOT_PATH.'AppCode/footer.mpt'); 

?>       




<script>
	$(document).ready(function(){
		
	});
	</script>	
