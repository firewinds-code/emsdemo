<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
class PHPLog_Action
{
	public function __construct($_User_ActionTo, $__Action_Page, $__Action_Desc)
	{
		function get_client_ip()
		{
			$ipaddress = '';
			if (getenv('HTTP_CLIENT_IP'))
				$ipaddress = getenv('HTTP_CLIENT_IP');
			else if (getenv('HTTP_X_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			else if (getenv('HTTP_X_FORWARDED'))
				$ipaddress = getenv('HTTP_X_FORWARDED');
			else if (getenv('HTTP_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
			else if (getenv('HTTP_FORWARDED'))
				$ipaddress = getenv('HTTP_FORWARDED');
			else if (getenv('REMOTE_ADDR'))
				$ipaddress = getenv('REMOTE_ADDR');
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}
		$log  = "System IP: " . get_client_ip() . ' - ' . date("F j, Y, g:i:s a") . "," .
			"Action: " . $__Action_Page . "," .
			"User: " . $_User_ActionTo . "," .
			"Description: " . $__Action_Desc . "," . PHP_EOL;
		//Save string to log, use FILE_APPEND to append.
		file_put_contents(ROOT_PATH . '/Log/log_' . date("j.n.Y") . '.csv', $log, FILE_APPEND);
	}
}
