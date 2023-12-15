<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'MysqliDb.php');
$user_logid = clean($_SESSION['__user_logid']);
if (isset($user_logid) && $user_logid != "") {
	function get_client_ip_ref()
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

	$ip = get_client_ip_ref();
	$ip1 = filter_var($ip, FILTER_VALIDATE_IP);

	$EmployeeID = clean($_SESSION['__user_logid']);
	$Query = "Select EmployeeID,token from login_history where EmployeeID=?  order by ID desc limit 1";
	//and token='".$token."'
	$rtoken = '';
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	$SelecT = $conn->prepare($Query);
	$SelecT->bind_param("s", $EmployeeID);
	$SelecT->execute();
	$response = $SelecT->get_result();
	$responses = $response->fetch_row();
	// $response = $myDB->query($Query);
	// $mysql_error = $myDB->getLastError();
	if ($response && $response->num_rows > 0) {
		$rtoken = clean($responses[1]);
	}

	// $db = cleanUserInput($_GET['db']);
	if (cleanUserInput($_GET['db']) == 'dashboard') {

		//echo "<script>location.href='http://172.105.61.198/web/index.php?r=dashboard/delivery&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip1."&url=delivery'</script>";
		echo "<script>location.href='https://analytics.cogentlab.com/web/index.php?r=dashboard/delivery&employee_id=" . $EmployeeID . "&token=" . $rtoken . "&source=web&sysip=" . $ip1 . "&url=delivery'</script>";
		// echo "<script>location.href='$url'</script>";

		exit();
	} else
 	if (cleanUserInput($_GET['db']) == 'dashboard2') {
		//echo "<script> location.href='http://172.105.61.198/web/index.php?r=dashboard2/quality&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip1."&url=quality'</script>";
		echo "<script> location.href='https://analytics.cogentlab.com/web/index.php?r=dashboard2/quality&employee_id=" . $EmployeeID . "&token=" . $rtoken . "&source=web&sysip=" . $ip1 . "&url=quality'</script>";
		exit();
	} else
 	if (cleanUserInput($_GET['db']) == 'quality') {
		//echo "<script> location.href='http://172.105.61.198/web/index.php?r=/quality/report-designation&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip1."&url=quality-report-designation'</script>";
		echo "<script> location.href='https://analytics.cogentlab.com/web/index.php?r=/quality/report-designation&employee_id=" . $EmployeeID . "&token=" . $rtoken . "&source=web&sysip=" . $ip1 . "&url=quality-report-designation'</script>";

		exit();
	} else
 	if (cleanUserInput($_GET['db']) == 'panindia') {
		//echo "<script> location.href='http://172.105.61.198/web/index.php?r=dashboard/delivery-aggregation&employee_id=".$_SESSION['__user_logid']."&token=".$rtoken."&source=web&sysip=".$ip1."&url=delivery-aggregation'</script>";
		echo "<script> location.href='https://analytics.cogentlab.com/web/index.php?r=dashboard/delivery-aggregation&employee_id=" . $EmployeeID . "&token=" . $rtoken . "&source=web&sysip=" . $ip1 . "&url=delivery-aggregation'</script>";

		exit();
	} else {
		echo "parameter not valid";
	}
} else {
	header("location: index.php");
	exit();
}
