<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

$EmployeeID=$_REQUEST['EmpID'];
//$EmployeeID='AE022026095';
$flag=$flag1=$flag2=$flag3=0;
$myDB = new MysqliDb();
		$result_ac_validate = $myDB->query("SELECT  distinct(edu_name),edu_file  FROM education_details where EmployeeID= '".$EmployeeID."'  and    edu_name in('10th','12th') "); 
		if(count($result_ac_validate )>=2)
		{
			if(file_exists((ROOT_PATH.'Education/'.$result_ac_validate [0]['edu_file'])) && file_exists((ROOT_PATH.'Education/'.$result_ac_validate [1]['edu_file'])) )
			{
				$flag=1;
				
			}else{
				if(file_exists((ROOT_PATH.'Edu/'.$result_ac_validate [0]['edu_file'])) && file_exists((ROOT_PATH.'Edu/'.$result_ac_validate [1]['edu_file'])) )
				{
					$flag=1;
				}else{
					$flag=0;
				}
				
			}
		}
	$myDB = new MysqliDb();
	$doc_validate = $myDB->query("select doc_type,doc_stype,doc_file from doc_details where EmployeeID= '".$EmployeeID."'   and doc_stype in('Salary Proof','Interview Form','Curriculum Vitae','Undertaking' ,'Aadhar Card')");	
	$myDB = new MysqliDb();
	$exp_array= $myDB->query("select distinct(exp_type) from experince_details where EmployeeID=  '".$EmployeeID."'  and exp_type='Experienced' ");
	/*echo "exprience=".count($exp_array);
	echo "<br>";
	//echo $exp_array[0]['exp_type'];
	//if(isset($exp_array[0]['exp_type']!=""))
	die;*/
	if(count($exp_array)>0)
	{
		if(count($doc_validate )>=5)
		{
			foreach($doc_validate as $value){
				if($value['doc_stype']!='Aadhar Card'){
					if(file_exists(ROOT_PATH."Docs/Other/".$value['doc_file'])){
						$flag1=1;
					}else{
						if($value['doc_stype']=='Curriculum Vitae'){
							if(file_exists(ROOT_PATH."Resume/".$value['doc_file'])){
								$flag1=1;
							}else{
								$flag1=0;
							 	break;
							}
						}else{
							$flag1=0;
						 	break;
						}
						
					}
				}else
				{
					$doctypefilepath='';
					if($value['doc_stype']=='Aadhar Card'){
						 $doctypefilepath =$value['doc_type'];
					}
					if($doctypefilepath=='Proof of Identity'){
						if(file_exists(ROOT_PATH."Docs/IdentityProof/".$value['doc_file'])){
							$flag2=1;
						}else{
							$flag2=0;
							 break;
						}
					}else
					if($doctypefilepath=='Proof of Address')
					{
						if(file_exists(ROOT_PATH."Docs/AddressProof/".$value['doc_file'])){
							$flag2=1;
						}else{
							$flag2=0;
							 break;
						}
					}
					
				}
				
				
			}
			
			
		}
	
	}else{
		//echo "doc validate=".count($doc_validate );
		//die;
		if(count($doc_validate )>=4)
		{
			foreach($doc_validate as $value){
				if($value['doc_stype']!='Aadhar Card'){
					if(file_exists(ROOT_PATH."Docs/Other/".$value['doc_file'])){
						$flag1=1;
					}else{
						
						if($value['doc_stype']=='Curriculum Vitae'){
							if(file_exists(ROOT_PATH."Resume/".$value['doc_file'])){
								$flag1=1;
							}else{
								$flag1=0;
							 	break;
							}
						}else{
							$flag1=0;
						 	break;
						}

					}
				}else
				{
					$doctypefilepath='';
					if($value['doc_stype']=='Aadhar Card'){
						 $doctypefilepath =$value['doc_type'];
					}
					if($doctypefilepath=='Proof of Identity'){
						if(file_exists(ROOT_PATH."Docs/IdentityProof/".$value['doc_file'])){
							$flag2=1;
						}else{
							$flag2=0;
							 break;
						}
					}else
					if($doctypefilepath=='Proof of Address')
					{
						if(file_exists(ROOT_PATH."Docs/AddressProof/".$value['doc_file'])){
							$flag2=1;
						}else{
							$flag2=0;
							 break;
						}
					}
					
				}
				
				
			}
			
			
		}
	}
		
		if($flag==1 and $flag1==1 and $flag2==1){
			echo 1;
		}else{
			echo 0;
		}
		
		
	//echo 1;
?>

