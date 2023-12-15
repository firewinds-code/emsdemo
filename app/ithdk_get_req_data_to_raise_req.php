<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
//table empid,Name, dateon status(0/1) //page add/remove/edit(update status as 0/1)


///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
$clientList=array();

$priortyList=array('Low','Medium' , 'High');


////////Categor and IssueType////////////////////
///////////////////////////////////////////////////////////////////////
$internalIssuList = array('Voice Issue','Reports Issue' , 'NGUCC Not working','CCSP Not Working','CRM Not working','Power Issue','Other');
$ExternalIssuList = array('PRI Issue','ILL Issue','MPLS Issue','P2P Link Issue','Other' );

$categoryNewInternalList['category'] = "Internal";
$categoryNewInternalList['issueType'] = $internalIssuList;

$categoryNewExternalList['category'] = "External";
$categoryNewExternalList['issueType'] = $ExternalIssuList;
$categoryList = array($categoryNewInternalList ,$categoryNewExternalList  ) ;

///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$locationList=array('Noida-Cogent C121','Noida-Cogent C100','Noida-Cogent A61','Bangalore Gopalna' ,'Bangalore Hebbal', 'Vadodara', 'Mangalore Raj Tower', 'Mangalore Fortune', 'Meerut', 'Bareilly');

//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
$result['msg']='';

if(isset($Data['appkey']) && $Data['appkey']=='getItHelpReqData' )
{
	
			$myDB=new MysqliDb();
		 $selectReq="SELECT distinct c.client_name FROM ems.new_client_master nc inner join client_master c on nc.client_name=c.client_id left join client_status_master cm on nc.cm_id=cm.cm_id where cm.cm_id is null";	
		$clientResult=$myDB->rawQuery($selectReq);
			
			
			//Creating Clients Array.
			foreach ($clientResult as $value) {
			  array_push($clientList,$value['client_name']);
			}
			
			$result['status']=1;
			$result['msg']='Data Found';
			$result['client']=$clientList;
			$result['categoryAndIssue']=$categoryList;
			
			$result['priortyList']=$priortyList;
			
			$result['location']=$locationList;
		
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
		
}
echo  json_encode($result);

?>

