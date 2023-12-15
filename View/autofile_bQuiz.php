<?php  
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
// require_once(__dir__.'/../Config/DBConfig.php');
require_once(CLS.'MysqliDb.php');
//require_once(LIB.'PHPExcel/IOFactory.php');
error_reporting(E_ALL);
ini_set('display_errors', 0);
if(isset($_GET['id']) and $_GET['id']!=''){
	$briefingId=$_GET['id'];
	$myDB=new MysqliDb();
	$rows=$myDB->query("Select * from brf_question where BriefingID='".$briefingId."'");
	$my_error= $myDB->getLastError();
	//echo count($rows);
	if(count($rows)>0){
					// output headers so that the file is downloaded rather than displayed
					header('Content-Type: text/csv; charset=utf-8');
					header('Content-Disposition: attachment; filename=data.csv');

					// create a file pointer connected to the output stream
					$fp = fopen('php://output', 'w');
					echo $my_error;			
					$columnNames = array();
					$row = array();
					//$i=1;
					$fileName = 'briefing_QUIZ.csv';
					foreach($rows[0] as $colName => $val){
						$columnNames[] = $colName;

					}
					fputcsv($fp,$columnNames);
				  	for($i=0;$i<count($rows);$i++){
				  		$row = array();
				  		foreach($rows[$i] as $colName => $val){
							$row[] = $val;
						}
						fputcsv($fp, $row);
					}
					fclose($fp);	    
				}else{
					echo "Data not found";
				}
			//echo "filesize".$file_size = filesize($fileName);		
			}
		 ?>
		 	
