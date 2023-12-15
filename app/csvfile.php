<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response = array();
$myDB =  new MysqliDb();
if(isset($Data) && count($Data) > 0 )
	{
             if(isset($Data['appkey']) && $Data['appkey']=='CSV/EXCEL')
		        {
		        	 $type=$Data['type'];
		        	 $emp_id=$Data['EmployeeID'];
	                 $query = 'call GetDTRequestDetails1("'.$emp_id.'")';
                     $res =$myDB->query($query);				
				    if($res)
				      {
					     $list=$res;
					     $fileName=$emp_id."_".rand();
                         if($type == 'csv')
                         {
                         	  $filePath ='../PublicApp/'.$fileName.'.csv';
                              $fp = fopen($filePath, 'w+');
                               $header=FALSE;
						 	 foreach ($list as $fields) 
                               { 
                               if (!$header)
								        { 
								        fputcsv($fp,array_keys($fields));
								        fputcsv($fp, $fields); 
								            $header=TRUE;
								        }
								        else
								        {
											fputcsv($fp, $fields); 
										}
                               }
                               $response['filename']=$fileName.'.csv'; 
                               $response['msg']="CSV File Created.";
					           $response['status']=1;
                                fclose($fp);
						 }
						 else if($type == 'excel')
						 {
						 	$data=$res;
						 	 $filePath ='../PublicApp/'.$fileName.'.xls';
						 	to_xls($data, $filePath);
						 	 $response['filename']=$fileName.'.xls'; 
						 $response['msg']="EXCEL File Created.";
					     $response['status']=1;
						 }
                       
				      }
				   else
				      {
					     $response['msg']="Don't have any request.";
					     $response['status']=2;
				       }
			   }
			  else
			   {
					$response['msg']="Appkey is not found.";
					$response['status']=0;
			   }
	   }
	else
		{
				$response['msg']="Data not found.";
				$response['status']=0;
		}
echo  json_encode($response);

function to_xls($data, $filename){
$fp = fopen($filename, "w+");
$str = pack(str_repeat("s", 6), 0x809, 0x8, 0x0, 0x10, 0x0, 0x0); // s | v
fwrite($fp, $str);
if (is_array($data) && !empty($data)){
    $row = 0;
    foreach (array_values($data) as $_data){
        if (is_array($_data) && !empty($_data)){
            if ($row == 0){
                foreach (array_keys($_data) as $col => $val){
                    _xlsWriteCell($row, $col, $val, $fp);
                }
                $row++;
            }
            foreach (array_values($_data) as $col => $val){
                _xlsWriteCell($row, $col, $val, $fp);
            }
            $row++;
        }
    }
}
$str = pack(str_repeat("s", 2), 0x0A, 0x00);
fwrite($fp, $str);
fclose($fp);
}


function _xlsWriteCell($row, $col, $val, $fp){
if (is_float($val) || is_int($val)){
    $str  = pack(str_repeat("s", 5), 0x203, 14, $row, $col, 0x0);
    $str .= pack("d", $val);
} else {
    $l    = strlen($val);
    $str  = pack(str_repeat("s", 6), 0x204, 8 + $l, $row, $col, 0x0, $l);
    $str .= $val;
}
fwrite($fp, $str);
}
?>

