<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');

	$myDB = new MysqliDb();
	$result = $myDB->query('select * from survey_details where EmployeeID="'.$_SESSION['__user_logid'].'"');
	if($result && count($result) > 0)
	{
		$count_dump  = $result[0]['count'] + 1;
		$attempt_dump =$result[0]['attemps'].$count_dump.':'.date("Y-m-d H:i:s",time()).',';
		
		
		$myDB = new MysqliDb();
		$flag = $myDB->query('update `survey_details` set `attemps` = "'.$attempt_dump.'",`count` = "'.$count_dump.'",`modifiedon`="'.date("Y-m-d H:i:s",time()).'",modifiedby = "'.$_SESSION['__user_logid'].'" where `EmployeeID` = "'.$_SESSION['__user_logid'].'"');
		$error = $myDB->getLastError();
		if(empty($error))
		{
			$myDB = new MysqliDb();
			$result_qs = $myDB->query('SELECT * FROM question_survey where EmployeeID="'.$_SESSION['__user_logid'].'" and ques_id="'.$_REQUEST['ques'].'"');
			if($result_qs && count($result_qs) > 0)
			{
				$ops_qs1 = $result_qs[0]['ops1'];
				$ops_qs2 = $result_qs[0]['ops2'];
				$ops_qs3 = $result_qs[0]['ops3'];
				$ops_qs4 = $result_qs[0]['ops4'];
				if($_REQUEST['ops'] == '1')
				{
					$ops_qs1++;
				}
				elseif($_REQUEST['ops'] == '2')
				{
					$ops_qs2++;
				}
				elseif($_REQUEST['ops'] == '3')
				{
					$ops_qs3++;
				}
				elseif($_REQUEST['ops'] == '4')
				{
					$ops_qs4++;
				}
				$myDB = new MysqliDb();
				$flag_qs = $myDB->query('UPDATE question_survey SET `ops1` = '.$ops_qs1.',`ops2` = '.$ops_qs2.',`ops3` = '.$ops_qs3.',`ops4` = '.$ops_qs4.' WHERE  `EmployeeID` = "'.$_SESSION['__user_logid'].'" and `ques_id` = "'.$_REQUEST['ques'].'"');
				$error = $myDB->getLastError();
				if(empty($error))
				{
					 echo 'done';
				}
			}
			else
			{
				$myDB = new MysqliDb();
				$ops_qs1 =$ops_qs2 = $ops_qs3 =$ops_qs4 = 0;
				
				
				if($_REQUEST['ops'] == '1')
				{
					$ops_qs1++;
				}
				elseif($_REQUEST['ops'] == '2')
				{
					$ops_qs2++;
				}
				elseif($_REQUEST['ops'] == '3')
				{
					$ops_qs3++;
				}
				elseif($_REQUEST['ops'] == '4')
				{
					$ops_qs4++;
				}
				
				
				$flag_qs = $myDB->query('INSERT INTO `question_survey` (`EmployeeID`,`ques_id`,`ops1`,`ops2`,`ops3`,`ops4`)VALUES("'.$_SESSION['__user_logid'].'","'.$_REQUEST['ques'].'",'.$ops_qs1.','.$ops_qs2.','.$ops_qs3.','.$ops_qs4.');');
				$error = $myDB->getLastError();
				if(empty($error))
				{
					echo 'done';
				}
			}
		}
		
		
	}
	else
	{
		//INSERT INTO `survey_details` (`EmployeeID`,`attemps`,`count`,`createdby`)VALUES('',now(),1,'CE04146339');	
		$myDB = new MysqliDb();
		
		$flag = $myDB->query('INSERT INTO `survey_details` (`EmployeeID`,`attemps`,`count`,`createdby`)VALUES("'.$_SESSION['__user_logid'].'","1:'.date("Y-m-d H:i:s",time()).',",1,"'.$_SESSION['__user_logid'].'")');
		$error =$myDB->getLastError();
		if(empty($error))
		{
			$myDB = new MysqliDb();
			$ops_qs1 =$ops_qs2 = $ops_qs3 =$ops_qs4 = 0;
			
			
			if($_REQUEST['ops'] == '1')
			{
				$ops_qs1++;
			}
			elseif($_REQUEST['ops'] == '2')
			{
				$ops_qs2++;
			}
			elseif($_REQUEST['ops'] == '3')
			{
				$ops_qs3++;
			}
			elseif($_REQUEST['ops'] == '4')
			{
				$ops_qs4++;
			}
			
			
			$flag_qs = $myDB->query('INSERT INTO `question_survey` (`EmployeeID`,`ques_id`,`ops1`,`ops2`,`ops3`,`ops4`)VALUES("'.$_SESSION['__user_logid'].'","'.$_REQUEST['ques'].'",'.$ops_qs1.','.$ops_qs2.','.$ops_qs3.','.$ops_qs4.');');
			$error =$myDB->getLastError();
			if(empty($error))
			{
				echo 'done';
			}
		}
	}
?>

