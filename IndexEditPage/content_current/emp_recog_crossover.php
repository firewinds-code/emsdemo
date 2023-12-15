<?php
// Server Config file
require_once(__dir__ . '/../../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
?>

<!DOCTYPE html>


<html>

<head>
	<script src="<?php echo SCRIPT . 'jquery.js'; ?>"></script>
	<link href="../../FileContainer/crosscover-1.0.2/dist/css/crosscover.min.css" rel="stylesheet">
	<script src="../../FileContainer/crosscover-1.0.2/dist/js/crosscover.min.js" charset="utf-8">
	</script>
</head>
<style>
	h2 {
		display: block;
		font-size: 1.5em;
	}

	.crosscover-item>img {
		display: block;
		width: 100%;
		height: 100%;
	}

	.crosscover {
		background-color: #fff;
	}

	.crosscover-item.is-active {
		z-index: 0 !important;
	}
</style>

<body>




	<!--  <h3>Tokyo</h3>
  <p>Tokyo is the capital of Japan.</p> -->

	<section class="crousal" style="height: 500px !important;">
		<div class="crosscover" style="background-position: 100% 100%;background-size: 100% 100%;margin-top: 10px;">
			<div class="crosscover-list">
				<?php

				/* $dirPath =  __DIR__.'/../emp_recognition_web/';

				 foreach (array_filter(glob( $dirPath.'*'), 'is_file') as $file)
				{
				//	echo basename($file);
					//	array_push($ImageList, $imageBaseUrl.basename($file));
					$fileExt = strtolower(pathinfo(basename($file),PATHINFO_EXTENSION));
					//if(basename($file) != 'index_help.php' && basename($file) != 'index_help.html' && basename($file) != //'index_help_one.html')
					if($fileExt == 'png' || $fileExt == 'jpg' || $fileExt == 'jpeg' )
					{
					
					  $dd =  basename($file);
					  echo '<div class="crosscover-item" style="background-size: 100% 100%;background-color: #fff;"><img src="../emp_recognition_web/'.$dd.'" alt="image2"/> </div>';  
									
						
					}

				}   */

				$userCmId = clean($_SESSION['__cm_id']);

				// $myDB = new MysqliDb();
				$sqlSelect = "select id, cmid, process_name, banner_type, platform, file_name, created_date from ipp_details where banner_type = 'Recognition' and platform='WEB' and ( cmid = ?  OR cmid = 'All' );";
				$selectQ = $conn->prepare($sqlSelect);
				$selectQ->bind_param("i", $userCmId);
				$selectQ->execute();
				$resultBy = $selectQ->get_result();
				// $resultBy = $myDB->rawQuery($sqlSelect);
				// $mysql_error = $myDB->getLastError();

				if (($resultBy) && $resultBy->num_rows > 0) {
					foreach ($resultBy as $fileRow) {
						echo '<div class="crosscover-item" style="background-size: 100% 100%;background-color: #fff;"><img src="../emp_recognition_web/' . $fileRow['file_name'] . '" alt="image2"/> </div>';
					}
				}



				?>
			</div>
		</div>
		<script>
			$(".crosscover").crosscover({
				interval: 10000,
				//controller: true,
				dotNav: true,
				autoPlay: true,
				inClass: 'lightSpeedIn',
				outClass: 'lightSpeedOut',

			});
		</script>
	</section>
</body>

</html>