<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$refid = clean($_REQUEST['id']);
//$sql = "SELECT * from referral_dispo where ref_id='" . $refid . "'";

$sql = "SELECT * from referral_dispo where ref_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $refid);
if (!$stmt) {
	echo "failed to run";
	die;
}
$stmt->execute();
$query = $stmt->get_result();
$count = $query->num_rows;


//$query = $myDB->query($sql);
// if ($myDB->count > 0) {

if ($count > 0) {
	foreach ($query as $val) {
?>
		<tr>
			<td><?php echo $val['disposition']; ?></td>
			<td><?php echo $val['remarks']; ?></td>
			<td><?php echo $val['created_by']; ?></td>
			<td><?php echo $val['created_on']; ?></td>
		</tr>
<?php
	}
}
?>