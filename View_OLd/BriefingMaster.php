<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
if (isset($_SESSION)) {
	if (clean($_SESSION["__login_type"]) == "Briefing") {
	} else {
		if (!((clean($_SESSION['__status_th']) != 'No'  && clean($_SESSION['__status_th']) == clean($_SESSION['__user_logid'])) || (clean($_SESSION['__status_qh']) != 'No'  && clean($_SESSION['__status_qh']) == clean($_SESSION['__user_logid'])) || (clean($_SESSION['__status_oh']) != 'No'  && clean($_SESSION['__status_oh']) == clean($_SESSION['__user_logid'])) || (clean($_SESSION['__status_ah']) != 'No'  && clean($_SESSION['__status_ah']) == clean($_SESSION['__user_logid'])) || clean($_SESSION["__user_type"]) == 'ADMINISTRATOR')) {

			$location = URL . 'Login';
			echo "<script>location.href='" . $location . "'</script>";
			//header("Location: $location");
		}
		$user_logid = clean($_SESSION['__user_logid']);
		if (!isset($user_logid)) {
			$location = URL . 'Login';
			header("Location: $location");
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$fromdate = $ack_status = $read1 = $total_question_num = $view_for = $briefingId = $client_name = "";
$clientID = $process = $subprocess = $bheading = $remark1 = $remark2 = $remark3 = '';
$classvarr = "'.byID'";
$searchBy = '';
$question_num = "";
$createdBy = "";
$myDB = "";
$userlogID = clean($_SESSION['__user_logid']);
if (isset($userlogID) && $userlogID != "") {
	if (isset($_GET['delid']) && $_GET['delid'] != "") {
		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();
		$delid = cleanUserInput($_GET['delid']);
		$query = "DELETE from brf_briefing where id=?";
		$stmts = $conn->prepare($query);
		$stmts->bind_param("i", $delid);
		$stmts->execute();
		$delete_query_acknowledge = $stmts->get_result();

		$deletequery = "DELETE from brf_question where BriefingId=?";
		$del = $conn->prepare($deletequery);
		$del->bind_param("i", $delid);
		$del->execute();
		$delete_query = $del->get_result();

		$deletequery = "DELETE from brf_acknowledge where BriefingId=?";
		$del = $conn->prepare($deletequery);
		$del->bind_param("i", $delid);
		$del->execute();
		$delete_query = $del->get_result();

		$deletequery = "DELETE from brf_quiz_attempted where BriefingId=?";
		$del = $conn->prepare($deletequery);
		$del->bind_param("i", $delid);
		$del->execute();
		$delete_query = $del->get_result();
		if ($delete_query) {
			$mymsg = '<span class="text-danger"><b>Briefing Deleted Successfully<b></span>';
		}
	}
	$quiz = "";
	$option1_1 = '';
	$option1_2 = '';
	$option1_3 = '';
	$option1_4 = '';
	$option2_1 = '';
	$option2_2 = '';
	$option2_3 = '';
	$option2_4 = '';
	$option3_1 = '';
	$option3_2 = '';
	$option3_3 = '';
	$option3_4 = '';
	$option4_1 = '';
	$option4_2 = '';
	$option4_3 = '';
	$option4_4 = '';
	$option5_1 = '';
	$option5_2 = '';
	$option5_3 = '';
	$option5_4 = '';
	$option6_1 = '';
	$option6_2 = '';
	$option6_3 = '';
	$option6_4 = '';
	$option7_1 = '';
	$option7_2 = '';
	$option7_3 = '';
	$option7_4 = '';
	$option8_1 = '';
	$option8_2 = '';
	$option8_3 = '';
	$option8_4 = '';
	$option9_1 = '';
	$option9_2 = '';
	$option9_3 = '';
	$option9_4 = '';
	$option10_1 = '';
	$option10_2 = '';
	$option10_3 = '';
	$option10_4 = '';
	$answer1 = '';
	$answer2 = '';
	$answer3 = '';
	$answer4 = '';
	$answer5 = '';
	$answer6 = '';
	$answer7 = '';
	$answer8 = '';
	$answer9 = '';
	$answer10 = '';
	$question1 = '';
	$question2 = '';
	$question3 = '';
	$question4 = '';
	$question5 = '';
	$question6 = '';
	$question7 = '';
	$question8 = '';
	$question9 = '';
	$question10 = '';
	$file_name = "";
	$tempfile = "";
	$addbriefing = isset($_POST['addbriefing']);
	if ($addbriefing) {
		if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
			// echo "<pre>";
			// print_r($_POST);
			// die;
			$createdBy = clean($_SESSION['__user_logid']);
			$clientID = cleanUserInput($_POST['client_id']);
			$subprocess_id = cleanUserInput($_POST['subprocess_id']);
			$from_date = cleanUserInput($_POST['from_date']);
			$view_for = cleanUserInput($_POST['view_for']);
			$cm_id = cleanUserInput($_POST['cm_id']);
			$remark3 = "";
			$bheading = addslashes(trim($_POST['bheading']));
			$remark1 = addslashes($_POST['remark1']);
			$remark2 = addslashes($_POST['remark2']);

			//print_r($_FILES);
			if (isset($_FILES['upload_file']['name']) && $_FILES['upload_file']['name'] != "") {

				$fsize = $_FILES['upload_file']['size'];


				if ($fsize > 0 && $fsize <= 2097152) {
					"file name=" . $file_name = $_FILES['upload_file']['name'];
					//$tempfile=$_FILES['upload_file']['temp_name'];
					$target_dir = ROOT_PATH . 'briefingDoc/';
					$file_name = time() . '-' . basename($_FILES["upload_file"]["name"]);
					$target_file = $target_dir . $file_name;

					if (!move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file)) {
						$file_name = "";
						//  $mymsg.='<span class="text-danger"><b>Sorry, file not uploaded because file is not moving on server<b></span>';     
						echo "<script>$(function(){ toastr.error('Sorry, file not uploaded because file is not moving on server'); }); </script>";
					}
				} elseif ($fsize == 0  || $fsize > 2097152) {
					//echo 'size is greater than 2 mb';
					$file_name = "";
					//$mymsg.='<span class="text-danger"><b>Sorry, file not uploaded because its size is greater than 2 MB <b></span><br>';	
					echo "<script>$(function(){ toastr.error('Sorry, file not uploaded because its size is greater than 2 MB'); }); </script>";
				}
			}
			// print_r($_POST);
			$question_num = 0;
			$myDB = new MysqliDb();
			$quiz = cleanUserInput($_POST['quiz']);
			$Tquiz = 0;
			$question_num = isset($_POST['question_num']);
			if ($question_num) {
				$question_num = cleanUserInput($_POST['question_num']);
			}

			$questions = isset($_POST['question']);
			if ($questions) {

				$question = cleanUserInput($_POST['question']);
				$answer = cleanUserInput($_POST['answer']);
				$answer1 = $answer[0];
				$question1 = $question[0];
				$Tquiz = 0;
				if ($answer1 == "" || $question1 == "") {
					$quiz = 'No';
					$question_num = "0";
				} else {


					for ($Q = 0; $Q < $question_num; $Q++) {
						if ($question[$Q] != "" && $answer[$Q] != "") {
							$Tquiz++;
						}
					}
					$question_num = $Tquiz;
				}
			}
			if ($question_num == "") {
				$question_num = 0;
			}

			if ($clientID != "NA"  && $bheading != "" && $remark1 != "" && $from_date != "" && $createdBy != "") {
				// $select_query = $myDB->rawQuery("select id from brf_briefing where cm_id='" . $cm_id . "'  and heading='" . $bheading . "' and remark1='" . $remark1 . "'");
				$select_queryQry = "select id from brf_briefing where cm_id=? and heading=? and remark1=?";
				$stmt = $conn->prepare($select_queryQry);
				$stmt->bind_param("iss", $cm_id, $bheading, $remark1);
				$stmt->execute();
				$select_query = $stmt->get_result();
				// print_r($select_query);
				if ($select_query && $select_query->num_rows > 0) {
					//$mymsg .='<span class="text-danger"><b>Duplicate entry not allowed.<b></span>';
					echo "<script>$(function(){ toastr.error('Duplicate entry not allowed'); }); </script>";
				} else {
					$insertQuery = "call brf_AddBriefing('" . $cm_id . "','" . $createdBy . "','" . $from_date . "','" . $quiz . "','" . $question_num . "','" . addslashes($file_name) . "','" . $view_for . "','" . $bheading . "','" . $remark1 . "','" . $remark2 . "','" . $remark3 . "')";

					//call brf_AddBriefing('47','CE10091236','2017-11-13 16:56','No','','','All','xbc','xcxcv','','') 
					$myDB = new MysqliDb();
					$resultBy = $myDB->rawQuery($insertQuery);
					$my_error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if ($rowCount > 0) {
						$myDB = new MysqliDb();
						$id_array = $myDB->rawQuery("select max(id) as id from brf_briefing ");

						$briefingId = $id_array[0]['id'];
					}

					if ($quiz == 'Yes' && $briefingId != "") {
						$question = ($_POST['question']);
						$answer = cleanUserInput($_POST['answer']);
						if ($question_num != count($question)) {
							//	$mymsg.='<span class="text-danger"><b>Question Number should be equal to  Question <b></span>';
							echo "<script>$(function(){ toastr.error('Question Number should be equal to  Question'); }); </script>";
						} else {
							if (isset($question[0])) {
								$option1_1 = addslashes($_POST['option1_1']);
								$option1_2 = addslashes($_POST['option1_2']);
								$option1_3 = addslashes($_POST['option1_3']);
								$option1_4 = addslashes($_POST['option1_4']);
								$answer1 = $answer[0];
								$question1 = addslashes($question[0]);
								if ($option1_1 == "" || $option1_2 == "" || $option1_3 == "" || $option1_4 == "" || $question1 == "" || $answer1 == "") {
									echo "<script>$(function(){ toastr.error('Question Number should be equal to  Question'); }); </script>";
									//$mymsg.='<span class="text-danger"><b>Question Number should be equal to  Question<b></span>';
								} else {
									$myDB = new MysqliDb();
									$addQuestion1 = "call brf_AddQuestion('" . $briefingId . "','" . $question1 . "','" . $option1_1 . "','" . $option1_2 . "','" . $option1_3 . "','" . $option1_4 . "','" . $answer1 . "')";
									$q1 = $myDB->rawQuery($addQuestion1);
								}
							}
							if (isset($question[1])) {
								$option2_1 = addslashes($_POST['option2_1']);
								$option2_2 = addslashes($_POST['option2_2']);
								$option2_3 = addslashes($_POST['option2_3']);
								$option2_4 = addslashes($_POST['option2_4']);
								$answer2 = addslashes($answer[1]);
								$question2 = addslashes($question[1]);
								if ($option2_1 == "" || $option2_2 == "" || $option2_3 == "" || $option2_4 == "" || $question2 == "" || $answer2 == "") {
									//$mymsg.='<span class="text-danger"><b>Question2, Options and Answer2 should not be empty <b></span>';
									echo "<script>$(function(){ toastr.error('Question2, Options and Answer2 should not be empty'); }); </script>";
								} else {
									$myDB = new MysqliDb();
									$addQuestion2 = "call brf_AddQuestion('" . $briefingId . "','" . $question2 . "','" . $option2_1 . "','" . $option2_2 . "','" . $option2_3 . "','" . $option2_4 . "','" . $answer2 . "')";
									$q1 = $myDB->rawQuery($addQuestion2);
								}
							}
							if (isset($question[2])) {
								$option3_1 = addslashes($_POST['option3_1']);
								$option3_2 = addslashes($_POST['option3_2']);
								$option3_3 = addslashes($_POST['option3_3']);
								$option3_4 = addslashes($_POST['option3_4']);
								$answer3 = $answer[2];
								$question3 = addslashes($question[2]);
								if ($option3_1 == "" || $option3_2 == "" || $option3_3 == "" || $option3_4 == "" || $question3 == "" || $answer3 == "") {
									//$mymsg.='<span class="text-danger"><b>Question3, Options and Answer3 should not be empty <b></span>';
									echo "<script>$(function(){ toastr.error('Question3, Options and Answer3 should not be empty'); }); </script>";
								} else {
									$myDB = new MysqliDb();
									$addQuestion3 = "call brf_AddQuestion('" . $briefingId . "','" . $question3 . "','" . $option3_1 . "','" . $option3_2 . "','" . $option3_3 . "','" . $option3_4 . "','" . $answer3 . "')";
									$q1 = $myDB->rawQuery($addQuestion3);
								}
							}
							if (isset($question[3])) {
								$option4_1 = addslashes($_POST['option4_1']);
								$option4_2 = addslashes($_POST['option4_2']);
								$option4_3 = addslashes($_POST['option4_3']);
								$option4_4 = addslashes($_POST['option4_4']);
								$answer4 = $answer[3];
								$question4 = addslashes($question[3]);
								if ($option4_1 == "" || $option4_2 == "" || $option4_3 == "" || $option4_4 == "" || $question4 == "" || $answer4 == "") {
									//$mymsg.='<span class="text-danger"><b>Question4, Options and Answer4 should not be empty <b></span>';
									echo "<script>$(function(){ toastr.error('Question4, Options and Answer4 should not be empty'); }); </script>";
								} else {
									$myDB = new MysqliDb();
									$addQuestion4 = "call brf_AddQuestion('" . $briefingId . "','" . $question4 . "','" . $option4_1 . "','" . $option4_2 . "','" . $option4_3 . "','" . $option4_4 . "','" . $answer4 . "')";
									$q1 = $myDB->rawQuery($addQuestion4);
								}
							}
							if (isset($question[4])) {
								$option5_1 = addslashes($_POST['option5_1']);
								$option5_2 = addslashes($_POST['option5_2']);
								$option5_3 = addslashes($_POST['option5_3']);
								$option5_4 = addslashes($_POST['option5_4']);
								$answer5 = $answer[4];
								$question5 = addslashes($question[4]);
								if ($option5_1 == "" || $option5_2 == "" || $option5_3 == "" || $option5_4 == "" || $question5 == "" || $answer5 == "") {
									//$mymsg.='<span class="text-danger"><b>Question5, Options and Answer5 should not be empty <b></span>';
									echo "<script>$(function(){ toastr.error('Question5, Options and Answer5 should not be empty '); }); </script>";
								} else {
									$myDB = new MysqliDb();
									$addQuestion5 = "call brf_AddQuestion('" . $briefingId . "','" . $question5 . "','" . $option5_1 . "','" . $option5_2 . "','" . $option5_3 . "','" . $option5_4 . "','" . $answer5 . "')";

									$q1 = $myDB->rawQuery($addQuestion5);
								}
							}
							if (isset($question[5])) {
								$option6_1 = addslashes($_POST['option6_1']);
								$option6_2 = addslashes($_POST['option6_2']);
								$option6_3 = addslashes($_POST['option6_3']);
								$option6_4 = addslashes($_POST['option6_4']);
								$answer6 = $answer[5];
								$question6 = addslashes($question[5]);
								if ($option6_1 == "" || $option6_2 == "" || $option6_3 == "" || $option6_4 == "" || $question6 == "" || $answer6 == "") {
									//$mymsg.='<span class="text-danger"><b>Question6, Options and Answer6 should not be empty <b></span>';
									echo "<script>$(function(){ toastr.error('Question6, Options and Answer6  should not be empty '); }); </script>";
								} else {
									$myDB = new MysqliDb();
									$addQuestion6 = "call brf_AddQuestion('" . $briefingId . "','" . $question6 . "','" . $option6_1 . "','" . $option6_2 . "','" . $option6_3 . "','" . $option6_4 . "','" . $answer6 . "')";

									$q1 = $myDB->rawQuery($addQuestion6);
								}
							}
							if (isset($question[6])) {
								$option7_1 = addslashes($_POST['option7_1']);
								$option7_2 = addslashes($_POST['option7_2']);
								$option7_3 = addslashes($_POST['option7_3']);
								$option7_4 = addslashes($_POST['option7_4']);
								$answer7 = $answer[6];
								$question7 = addslashes($question[6]);
								if ($option7_1 == "" || $option7_2 == "" || $option7_3 == "" || $option7_4 == "" || $question7 == "" || $answer7 == "") {
									//$mymsg.='<span class="text-danger"><b>Question7, Options and Answer7 should not be empty <b></span>';
									echo "<script>$(function(){ toastr.error('Question7, Options and Answer7  should not be empty '); }); </script>";
								} else {
									$myDB = new MysqliDb();
									$addQuestion7 = "call brf_AddQuestion('" . $briefingId . "','" . $question7 . "','" . $option7_1 . "','" . $option7_2 . "','" . $option7_3 . "','" . $option7_4 . "','" . $answer7 . "')";

									$q1 = $myDB->rawQuery($addQuestion7);
								}
							}
							if (isset($question[7])) {
								$option8_1 = addslashes($_POST['option8_1']);
								$option8_2 = addslashes($_POST['option8_2']);
								$option8_3 = addslashes($_POST['option8_3']);
								$option8_4 = addslashes($_POST['option8_4']);
								$answer8 = $answer[7];
								$question8 = addslashes($question[7]);
								if ($option8_1 == "" || $option8_2 == "" || $option8_3 == "" || $option8_4 == "" || $question8 == "" || $answer8 == "") {
									//$mymsg.='<span class="text-danger"><b>Question8, Options and Answer8 should not be empty <b></span>';
									echo "<script>$(function(){ toastr.error('Question8, Options and Answer8  should not be empty '); }); </script>";
								} else {
									$myDB = new MysqliDb();
									$addQuestion8 = "call brf_AddQuestion('" . $briefingId . "','" . $question8 . "','" . $option8_1 . "','" . $option8_2 . "','" . $option8_3 . "','" . $option8_4 . "','" . $answer8 . "')";

									$q1 = $myDB->rawQuery($addQuestion8);
								}
							}
							if (isset($question[8])) {
								$option9_1 = addslashes($_POST['option9_1']);
								$option9_2 = addslashes($_POST['option9_2']);
								$option9_3 = addslashes($_POST['option9_3']);
								$option9_4 = addslashes($_POST['option9_4']);
								$answer9 = $answer[8];
								$question9 = addslashes($question[8]);
								if ($option9_1 == "" || $option9_2 == "" || $option9_3 == "" || $option9_4 == "" || $question9 == "" || $answer9 == "") {
									//$mymsg.='<span class="text-danger"><b>Question9, Options and Answer9 should not be empty <b></span>';
									echo "<script>$(function(){ toastr.error('Question9, Options and Answer9  should not be empty '); }); </script>";
								} else {
									$myDB = new MysqliDb();
									$addQuestion9 = "call brf_AddQuestion('" . $briefingId . "','" . $question9 . "','" . $option9_1 . "','" . $option9_2 . "','" . $option9_3 . "','" . $option9_4 . "','" . $answer9 . "')";

									$q1 = $myDB->rawQuery($addQuestion9);
								}
							}
							if (isset($question[9])) {
								$option10_1 = addslashes($_POST['option10_1']);
								$option10_2 = addslashes($_POST['option10_2']);
								$option10_3 = addslashes($_POST['option10_3']);
								$option10_4 = addslashes($_POST['option10_4']);
								$answer10 = addslashes($answer[9]);
								$question10 = addslashes($question[9]);
								if ($option10_1 == "" || $option10_2 == "" || $option10_3 == "" || $option10_4 == "" || $question10 == "" || $answer10 == "") {
									//$mymsg.='<span class="text-danger"><b>Question10, Options and Answer10 should not be empty <b></span>';
									echo "<script>$(function(){ toastr.error('Question10, Options and Answer10 should not be empty  '); }); </script>";
								} else {
									$myDB = new MysqliDb();
									$addQuestion10 = "call brf_AddQuestion('" . $briefingId . "','" . $question10 . "','" . $option10_1 . "','" . $option10_2 . "','" . $option10_3 . "','" . $option10_4 . "','" . $answer10 . "')";
									$q1 = $myDB->rawQuery($addQuestion10);
								}
							}
						}
					}
					//$mymsg .='<span class="text-danger"><b>Briefing Added Successfully<b></span>';	
					echo "<script>$(function(){ toastr.success('Briefing Added Successfully '); }); </script>";
				}
			}
		}
	}

	if (isset($_POST['savebriefing']) && $_POST['id'] != "") { // print_r($_POST);
		if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
			$briefingId = "";
			$ack_id = cleanUserInput($_POST['ack_id']);
			$briefingId = cleanUserInput($_POST['id']);
			$createdBy = clean($_SESSION['__user_logid']);
			$clientID = cleanUserInput($_POST['client_id']);
			$cm_id = cleanUserInput($_POST['cm_id']);
			$enable_status = 0;
			if (isset($_POST['enable_status'])) {
				$enable_status = cleanUserInput($_POST['enable_status']);
			}
			$remark3 = "";
			$from_date = cleanUserInput($_POST['from_date']);
			$view_for = cleanUserInput($_POST['view_for']);
			$bheading = addslashes(trim($_POST['bheading']));
			$remark1 = addslashes(trim($_POST['remark1']));
			$remark2 = addslashes(trim($_POST['remark2']));
			if (isset($_POST['remark3'])) {
				$remark3 = addslashes(trim($_POST['remark3']));
			}
			if ($ack_id == "") {
				if (isset($_FILES['upload_file']['name']) && $_FILES['upload_file']['name'] != "") {
					$fsize = $_FILES['upload_file']['size'];
					if ($fsize > 0 && $fsize <= 2097152) {
						$file_name = $_FILES['upload_file']['name'];
						//$tempfile=$_FILES['upload_file']['temp_name'];
						$target_dir = ROOT_PATH . 'briefingDoc/';
						$file_name = time() . '-' . basename($_FILES["upload_file"]["name"]);
						$target_file = $target_dir . $file_name;

						if (!move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file)) {
							//$mymsg.='<span class="text-danger"><b>Sorry, file not uploaded because file is not moving on server<b></span>'; 
							echo "<script>$(function(){ toastr.error('Sorry, file not uploaded because file is not moving on server'); }); </script>";
							$file_name = "";
						} else {
							$hidden_file_name = cleanUserInput($_POST['hidden_filename']);
							//	echo "file move successfully";
							if ($hidden_file_name != "" && file_exists($target_dir . $hidden_file_name)) {
								//unlink(ROOT_PATH.'briefingDoc\\'.$hidden_file_name);
								@unlink($_SERVER['DOCUMENT_ROOT'] . "/ems/briefingDoc/" . $hidden_file_name);
							}
						}
					} elseif ($fsize == 0 || $fsize > 2097152) {
						//echo 'size is greater than 2 mb';
						$file_name = "";
						//$mymsg.='<span class="text-danger"><b>Sorry, file not uploaded because its size is greater than 2 MB <b></span><br>';	
						echo "<script>$(function(){ toastr.error('Sorry, file not uploaded because its size is greater than 2 MB'); }); </script>";
						//exit;

					}
				} else {
					$file_name = $_POST['hidden_filename'];
				}
				$quiz = cleanUserInput($_POST['quiz']);
				$myDB = new MysqliDb();
				if ($clientID != "NA"  && $bheading != "" && $remark1 != "" && $from_date != "" && $createdBy != "") {
					$updateQuery = "call brf_UpdateBriefing('" . $briefingId . "','" . $cm_id . "','" . $enable_status . "','" . $createdBy . "','" . $from_date . "','" . $quiz . "','" . addslashes($file_name) . "','" . $view_for . "','" . $bheading . "','" . $remark1 . "','" . $remark2 . "','" . $remark3 . "')";

					if ($quiz == 'Yes') {

						$question = cleanUserInput($_POST['question']);
						$answer = cleanUserInput($_POST['answer']);
						$question_id = cleanUserInput($_POST['question_id']);

						if (isset($question[0])) {
							$option1_1 = addslashes($_POST['option1_1']);
							$option1_2 = addslashes($_POST['option1_2']);
							$option1_3 = addslashes($_POST['option1_3']);
							$option1_4 = addslashes($_POST['option1_4']);
							$answer1 = addslashes($answer[0]);
							$question1 = addslashes($question[0]);
							$question_id1 = addslashes($question_id[0]);
							if ($option1_1 == "" || $option1_2 == "" || $option1_3 == "" || $option1_4 == "" || $question1 == "" || $answer1 == "") {
								//$mymsg.='<span class="text-danger"><b>Question1, Options and Answer1 should not be empty <b></span>';
								echo "<script>$(function(){ toastr.error('Question1, Options and Answer1 should not be empty'); }); </script>";
							} else {
								if ($briefingId != "") {
									$myDB = new MysqliDb();
									$addQuestion1 = "call brf_UpdateQuestion('" . $briefingId . "','" . $question_id1 . "','" . $question1 . "','" . $option1_1 . "','" . $option1_2 . "','" . $option1_3 . "','" . $option1_4 . "','" . $answer1 . "')";
									$q1 = $myDB->rawQuery($addQuestion1);
								} else {
									echo "<script>$(function(){ toastr.error('Briefing ID not found'); }); </script>";
								}
							}
						}
						if (isset($question[1])) {
							$option2_1 = addslashes($_POST['option2_1']);
							$option2_2 = addslashes($_POST['option2_2']);
							$option2_3 = addslashes($_POST['option2_3']);
							$option2_4 = addslashes($_POST['option2_4']);
							$answer2 = addslashes($answer[1]);
							$question2 = addslashes($question[1]);
							$question_id2 = addslashes($question_id[1]);
							if ($option2_1 == "" || $option2_2 == "" || $option2_3 == "" || $option2_4 == "" || $question2 == "" || $answer2 == "") {
								//$mymsg.='<span class="text-danger"><b>Question2, Options and Answer2 should not be empty <b></span>';
								echo "<script>$(function(){ toastr.error('Question2, Options and Answer2 should not be empty'); }); </script>";
							} else {
								$myDB = new MysqliDb();
								$addQuestion2 = "call brf_UpdateQuestion('" . $briefingId . "','" . $question_id2 . "','" . $question2 . "','" . $option2_1 . "','" . $option2_2 . "','" . $option2_3 . "','" . $option2_4 . "','" . $answer2 . "')";
								//echo "<br>";
								$q1 = $myDB->rawQuery($addQuestion2);
							}
						}
						if (isset($question[2])) {
							$option3_1 = addslashes($_POST['option3_1']);
							$option3_2 = addslashes($_POST['option3_2']);
							$option3_3 = addslashes($_POST['option3_3']);
							$option3_4 = addslashes($_POST['option3_4']);
							$answer3 = addslashes($answer[2]);
							$question3 = addslashes($question[2]);
							$question_id3 = addslashes($question_id[2]);
							if ($option3_1 == "" || $option3_2 == "" || $option3_3 == "" || $option3_4 == "" || $question3 == "" || $answer3 == "") {
								//$mymsg.='<span class="text-danger"><b>Question3, Options and Answer3 should not be empty <b></span>';
								echo "<script>$(function(){ toastr.error('Question3, Options and Answer3 should not be empty'); }); </script>";
							} else {
								$myDB = new MysqliDb();
								$addQuestion3 = "call brf_UpdateQuestion('" . $briefingId . "','" . $question_id3 . "','" . $question3 . "','" . $option3_1 . "','" . $option3_2 . "','" . $option3_3 . "','" . $option3_4 . "','" . $answer3 . "')";

								$q1 = $myDB->rawQuery($addQuestion3);
							}
						}
						if (isset($question[3])) {
							$option4_1 = addslashes($_POST['option4_1']);
							$option4_2 = addslashes($_POST['option4_2']);
							$option4_3 = addslashes($_POST['option4_3']);
							$option4_4 = addslashes($_POST['option4_4']);
							$answer4 = addslashes($answer[3]);
							$question4 = addslashes($question[3]);
							$question_id4 = addslashes($question_id[3]);
							if ($option4_1 == "" || $option4_2 == "" || $option4_3 == "" || $option4_4 == "" || $question4 == "" || $answer4 == "") {
								//$mymsg.='<span class="text-danger"><b>Question4, Options and Answer4 should not be empty <b></span>';
								echo "<script>$(function(){ toastr.error('Question4, Options and Answer4 should not be empty'); }); </script>";
							} else {
								$myDB = new MysqliDb();
								$addQuestion4 = "call brf_UpdateQuestion('" . $briefingId . "','" . $question_id4 . "','" . $question4 . "','" . $option4_1 . "','" . $option4_2 . "','" . $option4_3 . "','" . $option4_4 . "','" . $answer4 . "')";

								$q1 = $myDB->rawQuery($addQuestion4);
							}
						}
						if (isset($question[4])) {
							$option5_1 = addslashes($_POST['option5_1']);
							$option5_2 = addslashes($_POST['option5_2']);
							$option5_3 = addslashes($_POST['option5_3']);
							$option5_4 = addslashes($_POST['option5_4']);
							$answer5 = addslashes($answer[4]);
							$question5 = addslashes($question[4]);
							$question_id5 = addslashes($question_id[4]);
							if ($option5_1 == "" || $option5_2 == "" || $option5_3 == "" || $option5_4 == "" || $question5 == "" || $answer5 == "") {
								//$mymsg.='<span class="text-danger"><b>Question5, Options and Answer5 should not be empty <b></span>';
								echo "<script>$(function(){ toastr.error('Question5, Options and Answer5 should not be empty'); }); </script>";
							} else {
								$myDB = new MysqliDb();
								$addQuestion5 = "call brf_UpdateQuestion('" . $briefingId . "','" . $question_id5 . "','" . $question5 . "','" . $option5_1 . "','" . $option5_2 . "','" . $option5_3 . "','" . $option5_4 . "','" . $answer5 . "')";
								$q1 = $myDB->rawQuery($addQuestion5);
							}
						}
						if (isset($question[5])) {
							$option6_1 = addslashes($_POST['option6_1']);
							$option6_2 = addslashes($_POST['option6_2']);
							$option6_3 = addslashes($_POST['option6_3']);
							$option6_4 = addslashes($_POST['option6_4']);
							$answer6 = addslashes($answer[5]);
							$question6 = addslashes($question[5]);
							$question_id6 = addslashes($question_id[5]);
							if ($option6_1 == "" || $option6_2 == "" || $option6_3 == "" || $option6_4 == "" || $question6 == "" || $answer6 == "") {
								//$mymsg.='<span class="text-danger"><b>Question6, Options and Answer6 should not be empty <b></span>';
								echo "<script>$(function(){ toastr.error('Question6, Options and Answer6 should not be empty'); }); </script>";
							} else {
								$myDB = new MysqliDb();
								$addQuestion6 = "call brf_UpdateQuestion('" . $briefingId . "','" . $question_id6 . "','" . $question6 . "','" . $option6_1 . "','" . $option6_2 . "','" . $option6_3 . "','" . $option6_4 . "','" . $answer6 . "')";
								$q1 = $myDB->rawQuery($addQuestion6);
							}
						}
						if (isset($question[6])) {
							$option7_1 = addslashes($_POST['option7_1']);
							$option7_2 = addslashes($_POST['option7_2']);
							$option7_3 = addslashes($_POST['option7_3']);
							$option7_4 = addslashes($_POST['option7_4']);
							$answer7 = addslashes($answer[6]);
							$question7 = addslashes($question[6]);
							$question_id7 = addslashes($question_id[6]);
							if ($option7_1 == "" || $option7_2 == "" || $option7_3 == "" || $option7_4 == "" || $question7 == "" || $answer7 == "") {
								//$mymsg.='<span class="text-danger"><b>Question7, Options and Answer7 should not be empty <b></span>';
								echo "<script>$(function(){ toastr.error('Question7, Options and Answer7 should not be empty'); }); </script>";
							} else {
								$myDB = new MysqliDb();
								$addQuestion7 = "call brf_UpdateQuestion('" . $briefingId . "','" . $question_id7 . "','" . $question7 . "','" . $option7_1 . "','" . $option7_2 . "','" . $option7_3 . "','" . $option7_4 . "','" . $answer7 . "')";
								$q1 = $myDB->rawQuery($addQuestion7);
							}
						}
						if (isset($question[7])) {
							$option8_1 = addslashes($_POST['option8_1']);
							$option8_2 = addslashes($_POST['option8_2']);
							$option8_3 = addslashes($_POST['option8_3']);
							$option8_4 = addslashes($_POST['option8_4']);
							$answer8 = addslashes($answer[7]);
							$question8 = addslashes($question[7]);
							$question_id8 = addslashes($question_id[7]);
							if ($option8_1 == "" || $option8_2 == "" || $option8_3 == "" || $option8_4 == "" || $question8 == "" || $answer8 == "") {
								//$mymsg.='<span class="text-danger"><b>Question8, Options and Answer8 should not be empty <b></span>';
								echo "<script>$(function(){ toastr.error('Question8, Options and Answer8 should not be empty'); }); </script>";
							} else {
								$myDB = new MysqliDb();
								$addQuestion8 = "call brf_UpdateQuestion('" . $briefingId . "','" . $question_id8 . "','" . $question8 . "','" . $option8_1 . "','" . $option8_2 . "','" . $option8_3 . "','" . $option8_4 . "','" . $answer8 . "')";
								$q1 = $myDB->rawQuery($addQuestion8);
							}
						}
						if (isset($question[8])) {
							$option9_1 = addslashes($_POST['option9_1']);
							$option9_2 = addslashes($_POST['option9_2']);
							$option9_3 = addslashes($_POST['option9_3']);
							$option9_4 = addslashes($_POST['option9_4']);
							$answer9 = addslashes($answer[8]);
							$question9 = addslashes($question[8]);
							$question_id9 = ($question_id[8]);
							if ($option9_1 == "" || $option9_2 == "" || $option9_3 == "" || $option9_4 == "" || $question9 == "" || $answer9 == "") {
								//$mymsg.='<span class="text-danger"><b>Question9, Options and Answer9 should not be empty <b></span>';
								echo "<script>$(function(){ toastr.error('Question9, Options and Answer9 should not be empty'); }); </script>";
							} else {
								$myDB = new MysqliDb();
								$addQuestion9 = "call brf_UpdateQuestion('" . $briefingId . "','" . $question_id9 . "','" . $question9 . "','" . $option9_1 . "','" . $option9_2 . "','" . $option9_3 . "','" . $option9_4 . "','" . $answer9 . "')";
								$q1 = $myDB->rawQuery($addQuestion9);
							}
						}
						if (isset($question[9])) {
							$option10_1 = addslashes($_POST['option10_1']);
							$option10_2 = addslashes($_POST['option10_2']);
							$option10_3 = addslashes($_POST['option10_3']);
							$option10_4 = addslashes($_POST['option10_4']);
							$answer10 = addslashes($answer[9]);
							$question10 = addslashes($question[9]);
							$question_id10 = addslashes($question_id[9]);
							if ($option10_1 == "" || $option10_2 == "" || $option10_3 == "" || $option10_4 == "" || $question10 == "" || $answer10 == "") {
								//$mymsg.='<span class="text-danger"><b>Question10, Options and Answer10 should not be empty <b></span>';
								echo "<script>$(function(){ toastr.error('Question10, Options and Answer10 should not be empty'); }); </script>";
							} else {
								$myDB = new MysqliDb();
								$addQuestion10 = "call brf_UpdateQuestion('" . $briefingId . "','" . $question_id10 . "','" . $question10 . "','" . $option10_1 . "','" . $option10_2 . "','" . $option10_3 . "','" . $option10_4 . "','" . $answer10 . "')";
								$q1 = $myDB->rawQuery($addQuestion10);
							}
						}
					}
				}
				$resultBy = $myDB->rawQuery($updateQuery);
				//$mymsg.='<span class="text-danger"><b>Briefing Updated Successfully<b></span>';
				echo "<script>$(function(){ toastr.success('Briefing Updated Successfully'); }); </script>";
			} else {
				$myDB = new MysqliDb();
				$query = "update brf_briefing set EnableStatus='" . $enable_status . "' where id='" . $briefingId . "' ";
				$resultBy = $myDB->rawQuery($query);
				//	$mymsg.='<span class="text-danger"><b>Briefing Updated Successfully<b></span>';
				echo "<script>$(function(){ toastr.success('Briefing Updated Successfully'); }); </script>";
			}
		}
	}
}
?>
<script>
	$(document).ready(function() {
		var dateToday = new Date();
		$('#from_date').datetimepicker({
			format: 'Y-m-d H:i',
			step: 30,
			minDate: dateToday
		});
		$('#from_date2').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
		$('#to_date').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 10,
			scrollX: '100%',
			scrollCollapse: true,
			"columnDefs": [{
					"targets": [6],
					"visible": false,
					"searchable": false
				},
				{
					"targets": [7],
					"visible": false,
					"searchable": false
				},
				{
					"targets": [8],
					"visible": false,
					"searchable": false
				}
			],
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [


				{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}, 'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});


		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');
		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');

	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Briefing Master </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Manage Briefing Master </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php

				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php
				$file_name = "";
				$read = "";
				$briefingId = "";
				$clientname = "";
				$bheading = "";
				$remark1 = '';
				$remark2 = '';
				$remark3 = '';
				$cm_id = "";
				$enable_status = "";
				$numdisabled = "";
				if (isset($_GET['id']) and $_GET['id'] != "") {
					$briefingId = cleanUserInput($_GET['id']);
					$numdisabled = "disabled";
					if (clean($_SESSION['__user_logid']) == 'CE03070003') {
						// $sqlConnect2 = 'select  brf_briefing.*,brf_acknowledge.EmployeeID,brf_acknowledge.id as ack_id  from brf_briefing   left outer JOIN brf_acknowledge  ON  brf_briefing.id=brf_acknowledge.BriefingId where brf_briefing.id="' . $briefingId . '" group by brf_briefing.id ';
						$sqlConnect2 = 'select  brf_briefing.*,brf_acknowledge.EmployeeID,brf_acknowledge.id as ack_id  from brf_briefing   left outer JOIN brf_acknowledge  ON  brf_briefing.id=brf_acknowledge.BriefingId where brf_briefing.id=? group by brf_briefing.id ';
						$stmt = $conn->prepare($sqlConnect2);
						$stmt->bind_param("i", $briefingId);
						$stmt->execute();
					} else {
						// $sqlConnect2 = 'select  brf_briefing.*,brf_acknowledge.EmployeeID,brf_acknowledge.id as ack_id  from brf_briefing   left outer JOIN brf_acknowledge  ON  brf_briefing.id=brf_acknowledge.BriefingId where brf_briefing.id="' . $briefingId . '"  and  brf_briefing.CreatedBy="' . $_SESSION['__user_logid'] . '" group by brf_briefing.id ';
						$usrID = clean($_SESSION['__user_logid']);
						$sqlConnect2 = 'select  brf_briefing.*,brf_acknowledge.EmployeeID,brf_acknowledge.id as ack_id  from brf_briefing   left outer JOIN brf_acknowledge  ON  brf_briefing.id=brf_acknowledge.BriefingId where brf_briefing.id=?  and  brf_briefing.CreatedBy=? group by brf_briefing.id ';
						$stmt = $conn->prepare($sqlConnect2);
						$stmt->bind_param("is", $briefingId, $usrID);
						$stmt->execute();
					}

					$read1 = $read = "";
					$result2 = $stmt->get_result();
					// $myDB = new MysqliDb();
					// $result2 = $myDB->rawQuery($sqlConnect2);
					foreach ($result2 as $key => $val) {
						//$clientname=$val['client_name'];
						$bheading = stripslashes($val['heading']);
						$remark1 = stripslashes($val['remark1']);
						$remark2 = stripslashes($val['remark2']);
						$remark3 = stripslashes($val['remark3']);
						$enable_status = $val['EnableStatus'];
						$cm_id = $val['cm_id'];
						$fromdate = $val['fromdate'];
						$quiz = $val['quiz'];
						$total_question_num = $val['TotalQuestionNum'];
						if ($total_question_num > 0) {
							// $select_question = "Select * from brf_question where BriefingID='" . $briefingId . "'";
							// $myDB = new MysqliDb();
							// $result = $myDB->rawQuery($select_question);
							// $my_error = $myDB->getLastError();
							$select_question = "Select * from brf_question where BriefingID=?";
							$stmt = $conn->prepare($sqlConnect2);
							$stmt->bind_param("i", $briefingId);
							$stmt->execute();
							$result = $stmt->get_result();
							$total_question_num = $result->num_rows;
						}
						$file_name = stripslashes($val['uploaded_file']);

						$view_for = $val['view_for'];
						if ($quiz == 'Yes') {
							$read = "disabled ";
						} else {
							$read1 = "disabled ";
						}
						$ack_id = $val['ack_id'];
					}
				}

				$write_disable = "";
				if ($briefingId != "" && $ack_id != "") {
					$ack_status = "display:none";
					$write_disable = 'disabled';
				} ?>

				<div class="input-field col s6 m6 ">
					<?php
					if (clean($_SESSION["__login_type"]) == "Briefing") {
						$sqlBy = 'call  get_client_master_briefing("' . clean($_SESSION['__user_client_ID']) . '")';
					} else {
						$sqlBy = 'call  get_client_master("' . clean($_SESSION['__user_logid']) . '")';
					}
					$myDB = new MysqliDb();
					$resultBy = $myDB->rawQuery($sqlBy);
					///print_r($resultBy);
					?>
					<select name="cm_id" id="clientID" title="Select Client Option" <?php echo $write_disable; ?>>
						<option value="NA">---Select Client---</option>
						<?php
						if ($resultBy) {

							$selected = '';
							foreach ($resultBy as $key => $value) {
								if ($cm_id == $value['cm_id']) {
									$selected = 'selected';
								} else {
									$selected = '';
								}
								echo '<option id="' . $value['client_id'] . '_' . $value['sub_process'] . '" value="' . $value['cm_id'] . '" ' . $selected . '>' . $value['Client_info'] . '</option>';
							}
						}
						?>

					</select>
					<label for="clientID" class="active-drop-down active">Client </label>
				</div>

				<div class="input-field col s6 m6 ">
					<input type='text' name="bheading" id="bheading" <?php echo $write_disable; ?> value="<?php echo $bheading; ?>">
					<label for="bheading">Heading </label>
				</div>


				<!--<div style='<?php echo $ack_status; ?>'>-->

				<div class="input-field col s6 m6 ">
					<textarea name="remark1" id="remark1" class="materialize-textarea" title="Remark1" maxlength="1000" <?php echo $write_disable; ?>><?php echo strip_tags($remark1); ?></textarea>
					<label for="remark1">Remark1</label>
				</div>

				<div class="input-field col s6 m6 ">
					<textarea name="remark2" id="remark2" class="materialize-textarea" title="Remark2" maxlength="1000" <?php echo $write_disable; ?>><?php echo strip_tags($remark2); ?></textarea>
					<label for="remark2">Remark2</label>
				</div>
				<div class="input-field col s6 m6 ">
					<input type='text' name="from_date" id="from_date" <?php echo $write_disable; ?> value="<?php echo $fromdate; ?>" />
					<label for="from_date">Start Date & Time</label>
					<!---->
				</div>
				<input type="hidden" name='curdate' id='curdate' value='<?php echo date('Y-m-d H:i:s'); ?>'>
				<div class="input-field col s6 m6 ">
					<select name="view_for" id="view_for" <?php echo $write_disable; ?>>
						<option value='All' <?php if ($view_for == 'All') {
												echo 'selected';
											} ?>>All</option>
						<option value='onFloor' <?php if ($view_for == 'onFloor') {
													echo 'selected';
												} ?>>On Floor ( All )</option>
						<option value='Support' <?php if ($view_for == 'Support') {
													echo 'selected';
												} ?>>On Floor( Support )</option>
						<option value='CSA' <?php if ($view_for == 'CSA') {
												echo 'selected';
											} ?>>On Floor( CSA )</option>
					</select>
					<label for="view_for" class="active-drop-down active">View For</label>
				</div>
				<?php if ($file_name != "" && file_exists(ROOT_PATH . 'briefingDoc/' . $file_name)) { ?>
					<div class="input-field col s6 m6 ">


						<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" target='_blank' href="<?php echo URL . 'briefingDoc/' . $file_name; ?>" data-position="bottom" data-tooltip="Download File"><i class="material-icons">File Download</i></a>
						<!--  <label for="downloadid">Download File: <a  target='_blank' href="<?php echo URL . 'briefingDoc/' . $file_name; ?>">Download :(<?php echo $file_name; ?> )</a></label>-->
					</div>
				<?php
				} ?>
				<?php if ($quiz == 'Yes') { ?>
					<div class="input-field col s6 m6 ">

						<!--<button ><a  target='_blank' href="<?php echo "autofile_bQuiz.php?id=" . $briefingId; ?>" > Quiz Download</a></button>-->

						<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="<?php echo "autofile_bQuiz.php?id=" . $briefingId; ?>" data-position="bottom" data-tooltip="Quiz Download"><i class="material-icons">file_download</i></a>

					</div>
				<?php
				}
				?>
				<div style='<?php echo $ack_status; ?>'>
					<?php if ($briefingId == "" && $file_name == "") { ?>
						<div class="file-field input-field col  s6 m6  ">
							<div class="btn"><span>Upload File</span>
								<input type="file" name="upload_file" id="upload_file" style="text-indent: -99999em;">
								<br>
								<span class="file-size-text">Accepts up to 2MB</span>
							</div>
						</div>
					<?php } else { ?>

						<div class="file-field input-field col  s6 m6  ">

							<div class="btn"><span>Upload File</span>
								<input type="file" name="upload_file" id="upload_file" style="text-indent: -99999em;">
								<br>
								<span class="file-size-text">Accepts up to 2MB</span>
								<input type='hidden' name='hidden_filename' value='<?php echo $file_name; ?>'>
							</div>
						</div>
					<?php } ?>

					<div class="input-field col s6 m6 ">
						<input type="radio" id="quiz_yes" name="quiz" value="Yes" <?php if ($quiz == 'Yes') {
																						echo 'checked';
																					} ?> <?php echo $read1; ?> />
						<label for="quiz_yes">Quiz Yes</label>
					</div>
					<div class="input-field col s6 m6 ">
						<input type="radio" id="quiz_no" name="quiz" value="No" <?php echo $read; ?> <?php if ($quiz == 'No') {
																											echo 'checked';
																										}
																										if ($quiz == "") {
																											echo 'checked';
																										} ?> />
						<label for="quiz_no">Quiz No</label>
					</div>

					<div class="input-field col s12 m12 " id="question_id">

						<div class="form-group">
							<select name="question_num" id="question_num" <?php echo 	$numdisabled; ?>>
								<option value="">Select Number of Question</option>
								<?php
								for ($i = 1; $i <= 10; $i++) { ?>
									<option value="<?php echo $i; ?>" <?php if ($total_question_num == $i) {
																			echo "selected";
																		} ?>><?php echo $i; ?></option>
								<?php
								}
								?>

							</select>
							<label for="question_num" class="active-drop-down active">Number of Question</label>
						</div>
					</div>
				</div>
				<?php
				if ($briefingId != "") { ?>
					<div class="input-field col s6 m6 ">
						<input type='checkbox' title="Enable Briefing" name='enable_status' id='enable_status' <?php if ($enable_status == 1) {
																													echo 'checked';
																												}  ?> value='1' style='cursor:pointer;'>
						<label for="enable_status">Enable</label>
					</div>
					<input type='hidden' name='id' value='<?php echo $briefingId; ?>'>
					<input type='hidden' name='ack_id' value='<?php echo $ack_id; ?>'>
				<?php	}
				?>
				<div id='parent_div' style='<?php echo $ack_status; ?>'>
					<?php
					if ($total_question_num != "") {
						$myDB = new MysqliDb();
						$resultQ = $myDB->rawQuery($select_question);
						//echo "total quize=".$myDB->getRowsNum($select_question);
						$l = 1;
						foreach ($resultQ as $key => $val) {
							$question_id = $val['QuestionID'];
							$question = $val['Question'];
							$optionA = $val['Option1'];
							$optionB = $val['Option2'];
							$optionC = $val['Option3'];
							$optionD = $val['Option4'];
							$answer = $val['Answer'];
					?>
							<div class="input-field col s12 m12 ">
								<input type='text' name='question[]' id='question<?php echo $l; ?>' maxlength='255' value='<?php echo $question; ?>'>
								<input type='hidden' name='question_id[]' value='<?php echo $question_id; ?>'>
								<label for='question<?php echo $l; ?>' class='active'> Question <?php echo $l; ?></label>
							</div>

							<div class="input-field col s6 m6 ">
								<input type='text' name='option<?php echo $l; ?>_1' id='option<?php echo $l; ?>_1' placeholder='Option A' maxlength='255' value="<?php echo $optionA; ?>">
								<label for='option<?php echo $l; ?>_1' class='active'>Option A</label>
							</div>
							<div class="input-field col s6 m6 ">
								<input type='text' name='option<?php echo $l; ?>_2' id='option<?php echo $l; ?>_2' placeholder='Option B' maxlength='255' value="<?php echo $optionB; ?>">
								<label for='option<?php echo $l; ?>_2' class='active'>Option B</label>
							</div>
							<div class="input-field col s6 m6 ">
								<input type='text' name='option<?php echo $l; ?>_3' id='option<?php echo $l; ?>_3' placeholder='Option C' maxlength='255' value="<?php echo $optionC; ?>">
								<label for='option<?php echo $l; ?>_3' class='active'>Option C</label>
							</div>
							<div class="input-field col s6 m6 ">
								<input type='text' name='option<?php echo $l; ?>_4' id='option<?php echo $l; ?>_4' placeholder='Option D' maxlength='255' value="<?php echo $optionD; ?>" <label for='option<?php echo $l; ?>_4' class='active'>Option D</label>
							</div>
							<div class='input-field col s6 m6 '>
								<select name='answer[]' id='answer<?php echo $l; ?>'>
									<option value=''>Select</option>
									<option value='A' <?php if ($answer == 'A') {
															echo 'selected';
														} ?>>A</option>
									<option value='B' <?php if ($answer == 'B') {
															echo 'selected';
														} ?>>B</option>
									<option value='C' <?php if ($answer == 'C') {
															echo 'selected';
														} ?>>C</option>
									<option value='D' <?php if ($answer == 'D') {
															echo 'selected';
														} ?>>D</option>
								</select>
								<label for="answer<?php echo $l; ?>" class="active-drop-down active">Answer</label>
							</div>
							<div class='bborder'>&nbsp;</div>
					<?php
							$l++;
						}
					} ?>
				</div>


				<div class="input-field col s12 m12 right-align">
					<input type='hidden' name='client_id' id='client_id'>
					<input type='hidden' name='subprocess_id' id='subprocess_id'>

					<!-- <a href="<?php echo URL; ?>View/briefing_master.php" >-->
					<?php if ($briefingId != "") { ?>
						<button type="submit" name="savebriefing" id="savebriefing" class="btn waves-effect waves-green">Save</button>
						<button type="button" value="Cancel" name="btnCan" id="btnCancel" class="btn waves-effect waves-red close-btn " onclick="location.href='BriefingMaster.php'">Cancel</button>
					<?php } else { ?>
						<button type="submit" value="Save" name="addbriefing" id="addbriefing" class="btn waves-effect waves-green">Submit</button>
					<?php	} ?>
				</div>
				<?php
				function getAckBriefing($bid, $table)
				{
					$myDB = new MysqliDb();
					$conn = $myDB->dbConnect();
					$string = "SELECT id   FROM $table where BriefingId='" . $bid . "'";
					$stmts = $conn->prepare($string);
					$stmts->bind_param("i", $bid);
					$stmts->execute();
					$ack_query = $stmts->get_result();

					if ($table == 'brf_quiz_attempted') {
						$string = "SELECT distinct EmployeeID AS total_count  FROM $table where BriefingId=? ";
						$stmts = $conn->prepare($string);
						$stmts->bind_param("i", $bid);
						$stmts->execute();
						$ack_query = $stmts->get_result();
					}

					// $ack_query = $myDB->rawQuery($string);
					if ($ack_query) {
						return ($ack_query->num_rows);
					} else
						return 0;
				}
				$todate = "";
				$from_date2 = "";
				if (isset($_GET['from_date2'], $_GET['to_date']) and $_GET['from_date2'] != "" and $_GET['to_date'] != "") {
					$todate = cleanUserInput($_GET['to_date']);
					$from_date2 = cleanUserInput($_GET['from_date2']);
				} else {
					$todate = date('Y-m-d');
					$from_date2 = date('Y-m-d');
				}
				// if (($_SESSION["__login_type"]) == "Briefing") {
				// 	$sqlConnect = "select a.*,b.client_name AS Client  ,c.username,d.process,d.sub_process   from brf_briefing a INNER JOIN login_demo c ON  a.CreatedBy=c.userid inner join new_client_master d ON a.cm_id=d.cm_id  INNER JOIN client_master b  ON  b.client_id=d.client_name";
				// } else {
				// 	$sqlConnect = "select a.*,b.client_name AS Client  ,c.EmployeeName,d.process,d.sub_process   from brf_briefing a INNER JOIN personal_details c ON  a.CreatedBy=c.EmployeeID inner join new_client_master d ON a.cm_id=d.cm_id  INNER JOIN client_master b  ON  b.client_id=d.client_name";
				// }

				// $sqlConnect .= " where cast(a.CreatedOn as date) between '" . $from_date2 . "' and '" . $todate . "'  ";
				// if ($_SESSION['__user_logid'] !== 'CE03070003') {
				// 	$sqlConnect .= " and a.CreatedBy='" . $_SESSION['__user_logid'] . "'";
				// }
				// $sqlConnect .= " group by a.id order by a.id desc";

				// $myDB = new MysqliDb();
				// $result = $myDB->rawQuery($sqlConnect);
				// $error = $myDB->getLastError();
				// $rowCount = $myDB->count;
				$uId = clean($_SESSION['__user_logid']);
				if ($uId != 'CE03070003') {
					$sqlConnect = "select a.*,b.client_name AS Client,c.EmployeeName,d.process,d.sub_process   from brf_briefing a INNER JOIN personal_details c ON  a.CreatedBy=c.EmployeeID inner join new_client_master d ON a.cm_id=d.cm_id  INNER JOIN client_master b  ON  b.client_id=d.client_name where cast(a.CreatedOn as date) between ? and ? and a.CreatedBy=? group by a.id order by a.id desc";
					// $sqlConnect .= " and a.CreatedBy='" . $uId . "'";
					// $sqlConnect .= " and a.CreatedBy=?";
					// $sqlConnect .= " group by a.id order by a.id desc";
					$st = $conn->prepare($sqlConnect);
					$st->bind_param("sss", $from_date2, $todate, $uId);
					$st->execute();
				} else {
					$sqlConnect = "select a.*,b.client_name AS Client,c.EmployeeName,d.process,d.sub_process   from brf_briefing a INNER JOIN personal_details c ON  a.CreatedBy=c.EmployeeID inner join new_client_master d ON a.cm_id=d.cm_id  INNER JOIN client_master b  ON  b.client_id=d.client_name where cast(a.CreatedOn as date) between ? and ? group by a.id order by a.id desc";
					// $sqlConnect .= " group by a.id order by a.id desc";
					$st = $conn->prepare($sqlConnect);
					$st->bind_param("ss", $from_date2, $todate);
					$st->execute();
				}

				$result = $st->get_result();
				$rowCount = $result->num_rows;
				?>

				<div class='input-field col s6 m6'>
					<?php $from_date2 = cleanUserInput($_GET['from_date2']);  ?>
					<input type='text' name='from_date2' id='from_date2' <?php if (isset($from_date2)) { ?> value='<?php echo $from_date2; ?>' <?php } else { ?> value='<?php echo date('Y-m-d'); ?>' <?php } ?>>
					<label for="from_date2" class='active'>From Date</label>
				</div>
				<div class='input-field col s6 m6'>
					<?php $to_date = cleanUserInput($_GET['to_date']);  ?>
					<input type='text' name='to_date' id='to_date' <?php if (isset($to_date)) { ?> value='<?php echo $to_date; ?>' <?php } else { ?> value='<?php echo date('Y-m-d'); ?>' <?php } ?>>
					<label for='to_date' class='active'>To Date</label>
				</div>
				<div class='input-field col s12 m12 right-align'>
					<button type="button" value="Go" name="send" id="send" class="btn waves-effect waves-green">Search</button>
				</div>


				<div class="had-container pull-left row card dataTableInline" id="tbl_div">
					<div class="">

						<?php
						if ($result && $rowCount > 0) { ?>
							<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th> Srl.No.</th>
										<th> Client</th>
										<th> Process </th>
										<th> SubProcess </th>
										<th> Heading </th>
										<th> Start Date & Time </th>
										<th> Remark1 </th>
										<th> Remark2 </th>

										<th> CreatedOn </th>
										<th> Created By </th>
										<th> Enable </th>
										<th> UploadedFile </th>
										<th> Edit </th>
										<th> ACK Report </th>
										<th> View Briefing </th>
										<?php if ($_SESSION['__user_logid'] == 'CE03070003') {
											echo  "<th> Delete </th>	";
										} ?>

										<th>Quiz </th>
										<th>Acknowledged </th>
										<th>Attempted </th>

									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									foreach ($result as $key => $value) {

										echo '<tr>';
										echo '<td class="client_name">' . $i . '</td>';
										echo '<td class="client_name">' . $value['Client'] . '</td>';
										echo '<td class="process">' . $value['process'] . '</td>';
										echo '<td class="subprocess" >' . $value['sub_process'] . '</td>';
										echo '<td class="heading" >' . stripslashes($value['heading']) . '</td>';
										echo '<td class="heading" >' . $value['fromdate'] . '</td>';
										echo '<td class="remark1 " >' . strip_tags($value['remark1']) . '</td>';
										echo '<td class="remark2 " >' . strip_tags($value['remark2']) . '</td>';

										echo '<td class="created_on" >' . $value['CreatedOn'] . '</td>';
										echo '<td class="created_by" >' . $value['EmployeeName'] . '</td>';
										if ($value['EnableStatus'] == 1) {
											$checked = "checked";
											$enable_status = 'Yes';
										} else {
											$checked = "";
											$enable_status = 'No';
										}
										$bid = $value['id'];
										$acknowledged = 0;
										$attempted = 0;
										$acknowledged = getAckBriefing($bid, 'brf_acknowledge');
										$attempted = getAckBriefing($bid, 'brf_quiz_attempted');

									?>
										<td class="enable_status"><?php echo $enable_status; ?></td>
										<td class="created_by"><?php if ($value['uploaded_file'] != "") {
																	echo "Yes";
																} ?></td>
										<td class="edit"><a href="BriefingMaster.php?id=<?php echo $value['id']; ?>"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a></td>
										<td class="edit blue-text"><a href="briefingAcknowledgeReport.php?id=<?php echo $value['id']; ?>">View Report</td>
										<td class="view green-text"><a href="briefingMasterView.php?id=<?php echo $value['id']; ?>">View Briefing</td>
										<?php if (clean($_SESSION['__user_logid']) == 'CE03070003') { ?>
											<td class="edit"><a onclick="return confirm('Do you want to detete it?');" href="BriefingMaster.php?delid=<?php echo $value['id']; ?>"><img class="imgBtn imgBtnEdit editClass" src="../Style/images/users_delete.png" /></a></td>

									<?php
										}

										echo '<td class="created_by" >' . $value['quiz'] . '</td>';
										echo '<td class="created_by" >' . $acknowledged . '</td>';
										echo '<td class="created_by" >' . $attempted . '</td>';
										echo '</tr>';
										$i++;
									}
									?>
								</tbody>
							</table>
					</div>
				</div>
			<?php
						} else {
							echo "<script>$(function(){ toastr.info('Briefing Not Found .'); }); </script>";
						}

			?>



			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	$(document).ready(function() {
		if ($('#quiz_yes').is(':checked')) {
			$("#question_id").show();

		} else {
			$("#question_id").hide();
		}

		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		}
		$('#quiz_yes').on('click', function() {
			if ($('#quiz_yes').is(':checked')) {
				$("#question_id").show();
			} else {
				$("#question_id").hide();

			}
		})
		$('#quiz_no').on('click', function() {
			if ($('#quiz_no').is(':checked')) {
				$("#question_id").hide();
				$("#parent_div").html('');
				$('#question_num').val('');
			}
		})
		$('#addbriefing, #savebriefing').click(function() {
			//alert('aaa');
			var validate = 0;
			var alert_msg = '';

			var fileExtension = ['jpeg', 'jpg', 'png', 'pdf'];
			if ($('#upload_file').val() != "") {
				if ($.inArray($('#upload_file').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
					alert("File formats are allowed : " + fileExtension.join(', ') + " only");
					$('#upload_file').closest('div').addClass('has-error');
					validate = 1;
					alert_msg += '<li>File format not allowed  </li>';

				}
			}

			$('#clientID').closest('div').removeClass('has-error');
			if ($('#clientID').val() == 'NA') {
				$('#clientID').closest('div').addClass('has-error');
				validate = 1;
				alert_msg += '<li>Client can not be Empty  </li>';

			} else {
				var client_ID = $("#clientID  option:selected").attr("id");
				client_array = client_ID.split('_');
				$('#client_id').val(client_array[0]);
				$('#subprocess_id').val(client_array[1]);
			}
			if ($('#bheading').val().trim() == '') {
				$('#bheading').closest('div').addClass('has-error');
				validate = 1;
				alert_msg += '<li>Heading can not be Empty</li>';

			}
			if ($('#remark1').val().trim() == '') {
				$('#remark1').closest('div').addClass('has-error');
				validate = 1;
				alert_msg += '<li>Remark1 can not be Empty</li>';

			}

			if ($('#from_date').val().trim() == '') {
				$('#from_date').closest('div').addClass('has-error');
				validate = 1;
				alert_msg += '<li>From date can not be Empty</li>';

			} else {
				var fromdate = $('#from_date').val();
				var curdate = $('#curdate').val();

				if (fromdate < curdate) {
					$('#from_date').closest('div').addClass('has-error');
					validate = 1;
					alert_msg += '<li>Start time should not less then current time</li>';
				}

			}
			if ($('#quiz_yes').is(':checked')) {
				var num = $('#question_num').val();
				if (num < 1) {
					$('#question_num').closest('div').addClass('has-error');
					validate = 1;
					alert_msg += '<li>Please select no. of question</li>';
				}
			}
			if ($('#quiz_yes').is(':checked')) {
				var num = $('#question_num').val();
				for (i = 1; i <= num; i++) {
					var question = $('#question' + i).val().trim();
					var option1 = $('#option' + i + '_1').val().trim();
					var option2 = $('#option' + i + '_2').val().trim();
					var option3 = $('#option' + i + '_3').val().trim();
					var option4 = $('#option' + i + '_4').val().trim();
					var answer = $('#answer' + i).val();
					if (question == "") {
						validate = 1;
						alert_msg += '<li>Question Num ' + i + ' should not blank</li>';
					}
					if (option1 == "") {
						validate = 1;
						alert_msg += '<li>Option' + i + ' A should not blank</li>';
					}
					if (option2 == "") {
						validate = 1;
						alert_msg += '<li>Option' + i + ' B should not blank</li>';
					}
					if (option3 == "") {
						validate = 1;
						alert_msg += '<li>Option' + i + ' C should not blank</li>';
					}
					if (option4 == "") {
						validate = 1;
						alert_msg += '<li>Option' + i + ' D should not blank</li>';
					}
					if (answer == "") {
						validate = 1;
						alert_msg += '<li>Answer' + i + ' should not blank</li>';
					}
				}
			}

			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
		      		$('#alert_message').show().attr("class","SlideInRight animated");
		      		$('#alert_message').delay(5000).fadeOut("slow");
					return false;
					*/
				$(function() {
					toastr.error(alert_msg)
				});
				return false;
			}
		});

		$('#div_error').removeClass('hidden');
		//var j=2;
		$('#question_num').on('change', function() {
			$("#parent_div").html('');
			var num = $('#question_num').val();
			for (j = 0; j < num; j++) {
				var i = j + 1;
				appendText = "<div class='input-field col s12 m12'><input type='text'  name='question[]' id='question" + i + "' class='form-control qa'  maxlength='255' ><label for='question" + i + "' clas='active'>Question " + i + "</label></div><div class='input-field col s6 m6'><input type='text'  name='option" + i + "_1' id='option" + i + "_1'  placeholder='Option A' maxlength='255' > <label for='option" + i + "_1' clas='active' >Option A</label></div><div class='input-field col s6 m6'><input type='text'  name='option" + i + "_2' id='option" + i + "_2'  placeholder='Option B' maxlength='255' > <label for='option" + i + "_2' clas='active'>Option B</label></div> <div class='input-field col s6 m6'><input type='text'  name='option" + i + "_3' id='option" + i + "_3'  placeholder='Option C' maxlength='255' ><label for='option" + i + "_3' clas='active'>Option C</label></div> <div class='input-field col s6 m6'><input type='text'  name='option" + i + "_4' id='option" + i + "_4'  placeholder='Option D' maxlength='255' ><label for='option" + i + "_4' clas='active'>Option D</label> </div> <div class='input-field col s12 m12'><select name='answer[]' id='answer" + i + "'><option value=''>Select</option><option value='A'>A</option><option value='B'>B</option><option value='C'>C</option><option value='D'>D</option></select><label for='answer" + i + "' class='active-drop-down active'>Answer </label></div>";
				$("#parent_div").append(appendText);
				$('select').formSelect();
			}
		});
		$('#send').click(function() {
			var fromdate2 = $('#from_date2').val();
			//alert(fromdate2);
			var to_date = $('#to_date').val();
			if (fromdate2 != "" && to_date != "") {
				document.location.href = "BriefingMaster.php?from_date2=" + fromdate2 + "&to_date=" + to_date;
			} else {
				alert_msg = 'Please select  from date and to date';
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
      		$('#alert_message').show().attr("class","SlideInRight animated");
      		$('#alert_message').delay(5000).fadeOut("slow");
			return false;*/
				$(function() {
					toastr.error(alert_msg)
				});
				return false;

			}
		})
	});
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>