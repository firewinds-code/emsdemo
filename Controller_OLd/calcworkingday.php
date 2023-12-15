<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');

$EmpID = clean($_REQUEST['EmpID']);
$Date1 = clean($_REQUEST['Date1']);
$Date2 = clean($_REQUEST['Date2']);
$day = "";

if (isset($_REQUEST)) {
	$myDB = new MysqliDb();
	$flag = $myDB->query('call totalday_excWO("' . $EmpID . '","' . $Date1 . '","' . $Date2 . '")');

	if (count($flag) > 0) {
		$day = $flag[0]['day'];
	}
}

echo $day;
/*$sql='call remove_leave('.$_REQUEST['ID'].')';
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sql);
	$row_count = $myDB->count;
	$mysql_error=$myDB->getLastError();
	
	if(empty($mysql_error))
		{
			
		
		echo "Done|Row Deleted Successfully";
		
		
		 
	}
	else
	{
		echo "No|Row Not Deleted Try Again :".$mysql_error;
	}*/
