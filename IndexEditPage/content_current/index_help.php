<?php
// Server Config file
require_once(__dir__ . '/../../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  /* Style the tab */
  .tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #19aec4;
  }

  /* Style the buttons inside the tab */
  .tab button {
    background-color: #19aec4;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
    color: #fff;
  }


  /* Change background color of buttons on hover */
  .tab button:hover {
    background-color: #ddd;
  }

  /* Create an active/current tablink class */
  .tab button.active {
    background-color: #ccc;
  }

  /* Style the tab content */
  .tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
  }
</style>


<!-- <h2>Tabs</h2>
<p>Click on the buttons inside the tabbed menu:</p> -->

<?php

$b = 0;
$r = 0;
$d = 0;
$userCmId = clean($_SESSION['__cm_id']);

// $myDB = new MysqliDb();
$sqlSelect = "select id, cmid, process_name, banner_type, platform, file_name, created_date from ipp_details where  banner_type = 'Banner' and platform='WEB' and ( cmid = ?  OR cmid = 'All' ) ;";
$selectQ = $conn->prepare($sqlSelect);
$selectQ->bind_param("i", $userCmId);
$selectQ->execute();
$resultByB = $selectQ->get_result();
// $resultByB = $myDB->rawQuery($sqlSelect);
// $mysql_error = $myDB->getLastError();

if (($resultByB) && $resultByB->num_rows > 0) {
  $b = 1;
}




// $myDB = new MysqliDb();
$sqlSelect = "select id, cmid, process_name, banner_type, platform, file_name, created_date from ipp_details where banner_type = 'Recognition' and platform='WEB' and ( cmid = ?  OR cmid = 'All' ) ;";
$selectQ = $conn->prepare($sqlSelect);
$selectQ->bind_param("i", $userCmId);
$selectQ->execute();
$resultByR = $selectQ->get_result();
// $resultByR = $myDB->rawQuery($sqlSelect);
// $mysql_error = $myDB->getLastError();

if (($resultByR) && $resultByR->num_rows > 0) {
  $r = 1;
}



// $myDB = new MysqliDb();
$sqlSelect = "select id, cmid, process_name, banner_type, platform, file_name, created_date from ipp_details where banner_type = 'Discount' and platform='WEB' and ( cmid = ?  OR cmid = 'All' ) ;";
$selectQ = $conn->prepare($sqlSelect);
$selectQ->bind_param("i", $userCmId);
$selectQ->execute();
$resultBy = $selectQ->get_result();
// $resultBy = $myDB->rawQuery($sqlSelect);
// $mysql_error = $myDB->getLastError();

if (($resultBy) && $resultBy->num_rows > 0) {
  $d = 1;
}

?>
<div class="tab">

  <?php
  //$bb = ""
  if ($b == 1) { ?>
    <button class="tablinks" style="color: " onclick="openCity(event, 'Banner')">Communication</button>;
  <?php  } ?>
  <?php if ($d == 1) { ?>
    <button class="tablinks" onclick="openCity(event, 'EmployeeDiscount')">Offer</button>
  <?php  } ?>
  <?php if ($r == 1) { ?>
    <button class="tablinks" onclick="openCity(event, 'EmployeeRecognition')">Rewards & Recognition</button>
  <?php  } ?>

</div>


<?php
if ($b == 1)
  echo '<div id="Banner" class="tabcontent"><iframe src="../IndexEditPage/content_current/banner_crossover.php" title="description"  width="100%" height="550px" scrolling="no" frameBorder="0"></iframe></div>';


if ($d == 1) echo '<div id="EmployeeDiscount" class="tabcontent"><iframe src="../IndexEditPage/content_current/emp_dis_crossover.php" title="description"  width="100%" height="550px" scrolling="no" frameBorder="0" ></iframe></div>';

if ($r == 1)
  echo '<div id="EmployeeRecognition" class="tabcontent"><iframe src="../IndexEditPage/content_current/emp_recog_crossover.php" title="description"  width="100%" height="550px" scrolling="no" frameBorder="0" ></iframe></div>';
?>

<script>
  $(document).ready(function() {
    openCity(event, 'Banner');
  });

  function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace("active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += "active";
  }
</script>