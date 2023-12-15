<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

$sql='call sp_get_rptspl_roster_forclient("'.$_REQUEST['ID'].'")';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	if(count($result)>0 && $result ){
		
				$table='<table id="myTable1" class="data"><thead><tr>';
				
				$table .='<th>EmployeeID</th>';
				$table .='<th>EmployeeName</th>';
				$table .='<th>Designation</th>';
				$table .='<th>client_name</th>';
				$table .='<th>Process</th>';
				$table .='<th>sub_process</th>';
				$table .='<th>Adherence</th>';				
				$table .='<th>Non_adherence</th>';
				$table .='<th>Grandtotal</th>';
				$table .='<th>Per_adherence</th>';
				$table .='<th>Per_Nadherence</th></tr></thead><tbody>';
  
				foreach($result as $key=>$value)
				{
					$table .='<tr><td >'.strtoupper($value['EmployeeID']).'</td>';
					$table .='<td>'.$value['EmployeeName'].'</td>';
					$table .='<td>'.$value['designation'].'</td>';
					$table .='<td>'.$value['client_name'].'</td>';					
					$table .='<td>'.$value['Process'].'</td>';
					$table .='<td>'.$value['sub_process'].'</td>';
					$table .='<td>'.$value['Adhare'].'</td>';
					$table .='<td>'.$value['Non Adhare'].'</td>';	
					$table .='<td>'.$value['Grandtotal'].'</td>';
					$table .='<td>'.$value['Per_Adhare'].'</td>';
					$table .='<td>'.$value['Per_NAdhare'].'</td></tr>';
					
				}
				$table .='</tbody></table>';
				echo $table;
		
	}
	else
	{
		echo 'No Data Found ... ';
		
	}
?>

