<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

$sql='call sp_getLogin_Report_byID("'.$_REQUEST['ID'].'","'.$_REQUEST['DateFrom'].'","'.$_REQUEST['DateTo'].'")';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error= $myDB->getLastError();
	if(count($result)>0 && $result ){
		
				$table='<table id="myTable1" class="data"><thead><tr>';
				
				$table .='<th>EmployeeID</th>';
				$table .='<th>EmployeeName</th>';
				$table .='<th>Login Time</th>';
				$table .='<th>AccountHead</th>';
				$table .='<th>Designation</th>';
				$table .='<th>Process</th>';
				$table .='<th>sub_process</th></tr></thead><tbody>';
  
				foreach($result as $key=>$value)
				{
					$table .='<tr><td >'.strtoupper($value['EmployeeID']).'</td>';
					$table .='<td>'.$value['EmployeeName'].'</td>';
					$table .='<td>'.$value['createdon'].'</td>';
					$table .='<td>'.$value['AccountHead'].'</td>';
					$table .='<td>'.$value['designation'].'</td>';					
					$table .='<td>'.$value['Process'].'</td>';
					$table .='<td>'.$value['sub_process'].'</td></tr>';
					
				}
				$table .='</tbody></table>';
				echo $table;
		
	}
	else
	{
		echo 'No Data Found ... ';
		
	}
?>

