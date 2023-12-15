<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
ini_set('display_errors', '1');
$fid="";
if(isset($_REQUEST['fid'])){
	$fid=$_REQUEST['fid'];
}
$sql="";
if($fid!="")
{
$sql="select designation_master.ID,designation_master.Designation,df_id from df_master inner join designation_master on df_master.des_id=designation_master.ID where function_id='".$fid."' ";
}else{
	 $sql="select designation_master.ID,designation_master.Designation,df_id from df_master inner join designation_master on df_master.des_id=designation_master.ID  ";
}
//echo $sql;	
//echo "<br>";
if( $sql!=""){
	$myDB= new MysqliDb();
	$designation=$myDB->query($sql);
	$rowCount = $myDB->count;
	if($rowCount>0){
		echo json_encode($designation);
	}
}

?>