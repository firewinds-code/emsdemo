<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$result['msg']='';
$EmployeeID=$EmployeeName=$Designation=$Client=$Process=$SubProcess=$CandidateName=$CandidateNumber=$CandidateAddress=$Remark=$CandidateLevel=$ID=$Agreed='';

if(isset($Data['appkey']) && $Data['appkey']=='ref_reg')
{
			 $createdby=$EmployeeID=$Data['EmployeeID'];	
			 $EmployeeName=$Data['EmployeeName'];	
			 $Designation=$Data['Designation'];	
			 $Client=$Data['Client'];	
		     $Process=$Data['Process'];
		     $SubProcess=$Data['SubProcess'];
		     $CandidateName=$Data['CandidateName'];
		     $CandidateNumber=$Data['CandidateNumber'];
		     $CandidateLevel='CSA';
		     $ID=0;
			 $Agreed = 'Yes';
			 $Insert='call sp_chkmobile("'.$CandidateNumber.'")';
			 $myDB=new MysqliDb();
			 $result=$myDB->rawQuery($Insert);
			 $mysql_error = $myDB->getLastError();
				
				if(count($result) > 0 && $result)
				{
					$result['status']=0;
					$result['msg']="Already referenced.";
				}
				else
				{
					$sqlConnect = 'call sp_getRefID()';
				$myDB=new MysqliDb();
				$result=$myDB->rawQuery($sqlConnect);
				$mysql_error = $myDB->getLastError();
				if(count($result) > 0 && $result)
				{
					$ID=$result[0]['ID'];
				}	
			 $QueryInsert ='INSERT INTO tbl_reference_reg_detail(`EmployeeID`,`EmployeeName`,`Designation`,`Client`,`Process`,`SubProcess`,`CandidateName`,`CandidateNumber`,`CandidateAddress`,`Remark`,`CandidateLevel`,`createdby`,`RefID`,`Agreed`,`source`) VALUES("'.$EmployeeID.'","'.$EmployeeName.'","'.$Designation.'","'.$Client.'","'.$Process.'","'.$SubProcess.'","'.$CandidateName.'","'.$CandidateNumber.'","'.$CandidateAddress.'","'.$Remark.'","'.$CandidateLevel.'","'.$createdby.'","'.$ID.'","'.$Agreed.'","App");';
			 $response =$myDB->rawQuery($QueryInsert);
			 $mysql_error=$myDB->getLastError();
			 $result=array();
			if (empty($mysql_error))
				{
					$result['status']=1;
					$result['msg']="Refrence Registered Successfully.";
				}
	 
	       else
	           {
					$result['status']=0;
					$result['msg']='Reference not registered. Some error occurred...';
			  }
				}
			 
			 
				
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
}
echo  json_encode($result);
