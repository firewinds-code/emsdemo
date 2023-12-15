<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('upload_max_filesize', '5M');
ini_set('post_max_size', '5M');
//ini_set('max_input_time', 300);
//ini_set('max_execution_time', 300);
date_default_timezone_set('Asia/Kolkata');
		$columnNames =array("EmployeeID","EmployeeName","Gender","EmpStatus","DOJ","Client","Process","Subprocess","Function","Date"," RosterIn","RosterOut","BioIn.","BioOut","Downtime","APR","Attendance");
		$modulename="hybrid_file_creation2";
		$myDB=new MysqliDb();
		$myDB->query("Insert into scheduler set modulename='".$modulename."',type='Start' ");
		$myDB=new MysqliDb();
		$chk_task=$myDB->query('call proc_get_brada(75001,75000)');										 
		$rg_status='';
		if(count($chk_task) > 0 && $chk_task)
		{  
			$myDB=new MysqliDb();
			$myDB->query("Insert into scheduler set modulename='".$modulename."',type='Processing' ");
			echo	$fileName =date('Y-m-d').'.csv';
			echo "<br>";
			$fp = fopen('hybrid_report/'.$fileName, 'a');	
			foreach($chk_task as $key=>$value)
			{
				$row1=array($value['EmployeeID'],$value['EmployeeName'],$value['Gender'],$value['emp_status'],$value['DOJ'],$value['clientname'],$value['Process'],$value['sub_process'],$value['function'],$value['Date'],$value['RosterIn'],$value['RosterOut'],$value['BioIn'],$value['BioOut'],$value['Downtime'],$value['APR'],$value['Attendance']);
				fputcsv($fp, $row1);	
			}
			$myDB=new MysqliDb();
			$myDB->query("Insert into scheduler set modulename='".$modulename."',type='End' ");
		}

	echo "filesize=".$file_size = filesize('hybrid_report/'.$fileName);


?>