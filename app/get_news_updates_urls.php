<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);

$ImageList=array();
$result['msg']='';

if(isset($Data['appkey']) && $Data['appkey']=='getUrl' )
{
	

	//For Testing In Local
	/*  $dirPath = __DIR__.'/../Images/';
	 $imageBaseUrl ='http://192.168.24.76/ems/branches/Images/';*/
	
	//For Server
	$dirPath =  __DIR__.'/../IndexEditPage/appimg/';
    $imageBaseUrl = 'https://demo.cogentlab.com/erpm/IndexEditPage/appimg/';
	
	//exit;
	//reading the Folder To get The Images.
	
	foreach (array_filter(glob( $dirPath.'*'), 'is_file') as $file)
	{
		
		array_push($ImageList, $imageBaseUrl.basename($file));
		
    // Do something with $file
	}
	 
	 
	 //Validating The Images list
	 if(count($ImageList) > 0){
	 	$result['status']=1;
		$result['msg']='Found Images.';
		$result['imageList']=$ImageList;
	 }else{
	 	$result['status']=0;
		$result['msg']='Data not found.';
					
	 }
	
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
		
}
echo  json_encode($result);

?>

