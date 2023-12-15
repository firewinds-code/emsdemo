<?php
// Server Config file

use LDAP\Result;

require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$gender = $hrname = '';
$clean_u_login = clean($_SESSION['__user_logid']);


$EmployeeID = strtoupper($clean_u_login);
$sqlquery = "select EmpFlag, HRID,t2.EmpName as HRName, gender from induction_master t1 join EmpID_Name t2 on t1.HRID=t2.EmpID where t1.EmpID=? and EmpFlag=1";
$selectQ = $conn->prepare($sqlquery);
$selectQ->bind_param("s", $EmployeeID);
$selectQ->execute();
$results = $selectQ->get_result();
$result = $results->fetch_row();
// $result = $myDB->query($sqlquery);
if ($results->num_rows > 0) {
    $gender = $result[3];
    $hrname = $result[2];
}

function getans($id)
{
    $myDB = new MysqliDb();
    $conn = $myDB->dbConnect();
    $sqlgetans = "select ans from induction_questionaire where id=?";
    $stmgetans = $conn->prepare($sqlgetans);
    $stmgetans->bind_param("i", $id);
    $stmgetans->execute();
    $getans_res = $stmgetans->get_result();
    $result = $getans_res->fetch_row();
    if ($getans_res->num_rows > 0) {
        $getans_res = $result[0];
    } else {
        $getans_res = 0;
    }
    return $getans_res;
}
// echo getans(28);
function callquesoptions($id)
{
    $myDB = new MysqliDb();
    $conn = $myDB->dbConnect();
    $sqlget_opt = "select opt1,opt2,opt3,opt4 from induction_questionaire where id=?";
    $stmget_opt = $conn->prepare($sqlget_opt);
    $stmget_opt->bind_param("i", $id);
    $stmget_opt->execute();
    $getopt_res = $stmget_opt->get_result();
    $result = $getopt_res->fetch_row();
    return $result;
    // return (print_r($result));
}

if (isset($_POST['btnSave'])) {
    // echo "<pre>";
    // print_r($_POST);
    // die;
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
        $count1 = 0;
        $count2 = 0;

        if ($_POST['ques_opt_' . $_POST['ansid'][0]] == getans($_POST['ansid'][0])) {
            $count1 += 1;
        } else {
            $count2 += 1;
        }
        if ($_POST['ques_opt_' . $_POST['ansid'][1]] == getans($_POST['ansid'][1])) {
            $count1 += 1;
        } else {
            $count2 += 1;
        }
        if ($_POST['ques_opt_' . $_POST['ansid'][2]] == getans($_POST['ansid'][2])) {
            $count1 += 1;
        } else {
            $count2 += 1;
        }
        if ($_POST['ques_opt_' . $_POST['ansid'][3]] == getans($_POST['ansid'][3])) {
            $count1 += 1;
        } else {
            $count2 += 1;
        }
        if ($_POST['ques_opt_' . $_POST['ansid'][4]] == getans($_POST['ansid'][4])) {
            $count1 += 1;
        } else {
            $count2 += 1;
        }
        if ($_POST['ques_opt_' . $_POST['ansid'][5]] == getans($_POST['ansid'][5])) {
            $count1 += 1;
        } else {
            $count2 += 1;
        }
        if ($_POST['ques_opt_' . $_POST['ansid'][6]] == getans($_POST['ansid'][6])) {
            $count1 += 1;
        } else {
            $count2 += 1;
        }
        if ($_POST['ques_opt_' . $_POST['ansid'][7]] == getans($_POST['ansid'][7])) {
            $count1 += 1;
        } else {
            $count2 += 1;
        }
        if ($_POST['ques_opt_' . $_POST['ansid'][8]] == getans($_POST['ansid'][8])) {
            $count1 += 1;
        } else {
            $count2 += 1;
        }
        if ($_POST['ques_opt_' . $_POST['ansid'][9]] == getans($_POST['ansid'][9])) {
            $count1 += 1;
        } else {
            $count2 += 1;
        }
        // print_r($_POST['ansid']);
        // die;
        $empid = cleanUserInput($_POST['empID']);
        $remarks = cleanUserInput($_POST['txt_Comment']);
        $rating = cleanUserInput($_POST['rating']);
        $rating_ques1 = cleanUserInput($_POST['rating_ques1']);
        $rating_ans1 = cleanUserInput($_POST['rating_ans1']);
        $rating_ques2 = cleanUserInput($_POST['rating_ques2']);
        $rating_ans2 = cleanUserInput($_POST['rating_ans2']);
        $rating_ques3 = cleanUserInput($_POST['rating_ques3']);
        $rating_ans3 = cleanUserInput($_POST['rating_ans3']);

        $reqdiv_30 = $_POST['reqdiv_'];

        for ($i = 0; $i < 10; $i++) {
            $quid = $_POST['ansid'][$i];
            $reqQ = $_POST['reqdiv_' . $quid];
            $corr_ans = getans($quid);
            $giv_ans = $_POST['ques_opt_' . $quid];
            $res = callquesoptions($quid);
            $opt1 = ($res[0]);
            $opt2 = ($res[1]);
            $opt3 = ($res[2]);
            $opt4 = ($res[3]);
            // echo $query = "insert into induction_rowdata (Empid, ques, corr_ans, giv_ans, opt1, opt2, opt3, opt4) value('".$empid."', '".$reqQ."', '".$corr_ans."', '".$giv_ans."', '".$opt1."', '".$opt2."', '".$opt3."', '".$opt4."')";die;
            $query = "insert into induction_rowdata (Empid, ques, corr_ans, giv_ans, opt1, opt2, opt3, opt4) value(?,?,?,?,?,?,?,?)";

            $insert = $conn->prepare($query);
            $insert->bind_param("ssssssss", $empid, $reqQ, $corr_ans, $giv_ans, $opt1, $opt2, $opt3, $opt4);
            $insert->execute();
            $result = $insert->get_result();
        }
        // print_r($_POST);
        // die;
        if ($empid != "" && $remarks != "") {
            $remarks = addslashes($remarks);
            $query1 = "update induction_master set EmpFlag=2, Emp_Ack=now(), Emp_Comment=?, rating=?, correct=?, incorrect=?, rating_ques1=?, rating_ans1=?, rating_ques2=?, rating_ans2=?, rating_ques3=?, rating_ans3=?   where EmpID=? ";
            $insert1 = $conn->prepare($query1);
            $insert1->bind_param("siiisisisis", $remarks, $rating, $count1, $count2, $rating_ques1, $rating_ans1, $rating_ques2, $rating_ans2, $rating_ques3, $rating_ans3, $empid);
            $insert1->execute();
            $result = $insert1->get_result();
            // echo "<script>location.href='index.php'; </script>";
            echo "<script>$(function(){toastr.success('Acknowledged')})</script>";
            $location = URL . 'View/index.php';
            echo "<script>location.href='" . $location . "'</script>";
            header("Location: $location");
        }
    }
}
$remark = $empname = $empid = $searchBy = $msg = '';
$classvarr = "'.byID'";

$quesql = "SELECT id, ques, opt1, opt2, opt3, opt4, ans, created_at FROM induction_questionaire ORDER BY rand() LIMIT 10";
$stmques = $conn->prepare($quesql);
$stmques->execute();
$resque = $stmques->get_result();
// $row = $resque->fetch_row();
$row = $resque->fetch_assoc();
// echo "<pre>";
// print_r($row);
?>
<style>
    .padding {
        padding: 10rem !important;
        margin-left: 200px
    }

    /*Rating start*/

    .br-theme-bars-pill .br-widget {
        white-space: nowrap;
    }

    .br-theme-bars-pill .br-widget a {
        padding: 7px 15px;
        background-color: #bef5e8;
        color: #50E3C2;
        text-decoration: none;
        font-size: 13px;
        line-height: 3;
        text-align: center;
        font-weight: 400;
    }

    .br-theme-bars-pill .br-widget a:first-child {
        -webkit-border-top-left-radius: 999px;
        -webkit-border-bottom-left-radius: 999px;
        -moz-border-radius-topleft: 999px;
        -moz-border-radius-bottomleft: 999px;
        border-top-left-radius: 999px;
        border-bottom-left-radius: 999px;
    }

    .br-theme-bars-pill .br-widget a:last-child {
        -webkit-border-top-right-radius: 999px;
        -webkit-border-bottom-right-radius: 999px;
        -moz-border-radius-topright: 999px;
        -moz-border-radius-bottomright: 999px;
        border-top-right-radius: 999px;
        border-bottom-right-radius: 999px;
    }

    .br-theme-bars-pill .br-widget a.br-active,
    .br-theme-bars-pill .br-widget a.br-selected {
        background-color: #50E3C2;
        color: white;
    }

    .br-theme-bars-pill .br-readonly a {
        cursor: default;
    }

    .br-theme-bars-pill .br-readonly a.br-active,
    .br-theme-bars-pill .br-readonly a.br-selected {
        background-color: #7cead1;
    }

    @media print {
        .br-theme-bars-pill .br-widget a {
            border: 1px solid #b3b3b3;
            border-left: none;
            background: white;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .br-theme-bars-pill .br-widget a.br-active,
        .br-theme-bars-pill .br-widget a.br-selected {
            border: 1px solid black;
            border-left: none;
            background: white;
            color: black;
        }

        .br-theme-bars-pill .br-widget a:first-child {
            border-left: 1px solid black;
        }
    }

    /*Rating End*/

    .box-example-1to10 .br-wrapper {
        width: 210px;
        position: absolute;
        margin: 0px 0 0 -105px;
        left: 50%
    }

    .box-example-movie .br-wrapper {
        width: 250px;
        position: absolute;
        margin: 0px 0 0 -125px;
        left: 50%
    }

    .box-example-square .br-wrapper {
        width: 190px;
        position: absolute;
        margin: 0px 0 0 -95px;
        left: 50%
    }

    .box-example-pill .br-wrapper {
        width: 232px;
        position: absolute;
        margin: 0px 0 0 -116px;
        left: 50%
    }

    .box-example-reversed .br-wrapper {
        padding-top: 1.3em;
        width: 356px;
        position: absolute;
        margin: 0px 0 0 -178px;
        left: 50%
    }

    .box-example-horizontal .br-wrapper {
        width: 120px;
        position: absolute;
        margin: 0px 0 0 -60px;
        left: 50%
    }

    .star-ratings h1 {
        font-size: 1.5em;
        line-height: 2;
        margin-top: 3em;
        color: #757575
    }

    .star-ratings p {
        margin-bottom: 3em;
        line-height: 1.2
    }

    .star-ratings h1,
    .star-ratings p {
        text-align: center
    }

    .star-ratings .stars {
        width: 120px;
        text-align: center;
        margin: auto;
        padding: 0 95px
    }

    .star-ratings .stars .title {
        font-size: 14px;
        color: #cccccc;
        line-height: 3
    }

    .star-ratings .stars select {
        width: 120px;
        font-size: 16px
    }

    .star-ratings .stars-example-fontawesome,
    .star-ratings .stars-example-css,
    .star-ratings .stars-example-bootstrap {
        float: left
    }

    .star-ratings .stars-example-fontawesome-o {
        width: 200px
    }

    .star-ratings .stars-example-fontawesome-o select {
        width: 200px
    }

    .start-ratings-main {
        margin-bottom: 3em
    }

    .box {
        width: 100%;
        float: left;
        margin: 1em 0
    }

    .box .box-header {
        text-align: center;
        font-weight: 400;
        padding: .5em 0
    }

    .box .box-body {
        padding-top: 2em;
        height: 85px;
        position: relative
    }

    .box select {
        width: 120px;
        margin: 10px auto 0 auto;
        display: block;
        font-size: 16px
    }

    .box-large .box-body {
        padding-top: 2em;
        height: 120px
    }

    .box-orange .box-header {
        background-color: #edb867;
        color: white
    }

    .box-orange .box-body {
        background-color: white;
        border: 2px solid #f5d8ab;
        border-top: 0
    }

    .box-green .box-header {
        background-color: #50e3c2;
        color: white
    }

    .box-green .box-body {
        background-color: white;
        border: 2px solid #92eed9;
        border-top: 0
    }

    .box-blue .box-header {
        background-color: #4278f5;
        color: white
    }

    .box-blue .box-body {
        background-color: white;
        border: 2px solid #8bacf9;
        border-top: 0
    }

    @media print {
        .star-ratings h1 {
            color: black
        }

        .star-ratings .stars .title {
            color: black
        }

        .box-orange .box-header,
        .box-green .box-header,
        .box-blue .box-header {
            background-color: transparent;
            color: black
        }

        .box-orange .box-body,
        .box-green .box-body,
        .box-blue .box-body {
            background-color: transparent;
            border: none
        }
    }
</style>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Induction Acknowledge</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <div class="schema-form-section row">
                <?php
                $_SESSION["token"] = csrfToken();
                ?>
                <div class="row">
                    <p colspan='2' style="text-align: Center;    background-color: #19AEC4;color: white;font-size: 1rem;"><u><b>
                                WARM WELCOME TO THE COGENT FAMILY </b></u></p>
                    <!-- <h4 class="text-center"><b> WARM WELCOME TO THE COGENT FAMILY </b></h4> -->
                    <?php if (strtolower($gender) == 'female') { ?>
                        <p><b>Your Induction training program has been successfully completed by Ms. <?php echo $hrname ?>
                                .</b> </p>
                    <?php } else { ?>
                        <p><b>Your Induction training program has been successfully completed by Mr. <?php echo $hrname ?> .
                            </b></p>
                    <?php  } ?>

                    <div id="questionaire_div">

                        <h4 style="margin-left: 0.8rem;">Please Answer Some Multiple Type Questions</h4>
                        <form method="post">
                            <!-- <form action="" method="post"> -->
                            <div class="quizdiv">

                                <ol>
                                    <?php
                                    $i = 1;
                                    foreach ($resque as $val) {
                                        // print_r($val);
                                    ?>
                                        <li style="margin: 15px 0;">
                                            <label class="reqdiv_<?php echo $val['id']; ?>" for="" style="font-size: 0.9rem; font-weight: bold;"><?php echo  "Q" . $i . "-" . $val['ques'] ?></label>
                                            <!-- <input type="text" class="reqdiv_<?php echo $val['id']; ?>" value="<?php echo "Q" . $i . "-" . $val['ques'] ?>" disabled> -->
                                            <input type="hidden" name="reqdiv_<?php echo $val['id']; ?>" class="reqdiv_<?php echo $val['id']; ?>" value="<?php echo $val['ques'] ?>">
                                            <!-- <h6 class="reqdiv_<?php echo $val['id']; ?>"><b><?php echo "Q" . $i . "-" . $val['ques'] ?></b></h6> -->

                                            <input class="optA" type="hidden" name="ansid[]" id="ansid" value="<?php echo $val['id']; ?>">
                                            <div style="margin: 5px 0px 0px;">
                                                <input class="optA1<?php echo $val['id']; ?>" type="radio" name="ques_opt_<?php echo $val['id']; ?>" id="ques_opt-A<?php echo $val['id']; ?>" value="<?php echo $val['opt1'] ?>" />
                                                <label style="font-size: 0.9rem;" for="ques_opt-A<?php echo $val['id']; ?>">A)
                                                    <?php echo $val['opt1'] ?> </label>
                                            </div>

                                            <div>
                                                <input class="optA" type="radio" name="ques_opt_<?php echo $val['id']; ?>" id="ques_opt-B<?php echo $val['id']; ?>" value="<?php echo $val['opt2'] ?>" />
                                                <label style="font-size: 0.9rem;" for="ques_opt-B<?php echo $val['id']; ?>">B)
                                                    <?php echo $val['opt2'] ?></label>
                                            </div>

                                            <?php if (($val['opt3'] && $val['opt4']) != "") { ?>

                                                <div>
                                                    <input class="optA" type="radio" name="ques_opt_<?php echo $val['id']; ?>" id="ques_opt-C<?php echo $val['id']; ?>" value="<?php echo $val['opt3'] ?>" />
                                                    <label style="font-size: 0.9rem;" for="ques_opt-C<?php echo $val['id']; ?>">C)
                                                        <?php echo $val['opt3'] ?></label>
                                                </div>

                                                <div>
                                                    <input class="optA" type="radio" name="ques_opt_<?php echo $val['id']; ?>" id="ques_opt-D<?php echo $val['id']; ?>" value="<?php echo $val['opt4'] ?>" />
                                                    <label style="font-size: 0.9rem;" for="ques_opt-D<?php echo $val['id']; ?>">D)
                                                        <?php echo $val['opt4'] ?></label>
                                                </div>
                                            <?php } ?>
                                            <span id="req_<?php echo $val['id']; ?>" class="help-block req_<?php echo $val['id']; ?>"></span>

                                        </li>



                                    <?php $i++;
                                    } ?>
                                    <br />
                                    <!-- <a class="btn btn-warning" id="next">Next</a>
								<a class="btn btn-warning" id="prev">Prev</a> -->
                                </ol>
                            </div>
                            <hr>
                            <div class="input-field col s12 m12 right-align">
                                <button type="button" name="btnQues" id="btnQues" class="btn waves-effect waves-green">Submit</button>
                            </div>
                    </div>


                    <div id="ackdiv">
                        <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
                        <?php
                        $clean_name = clean($_SESSION['__user_Name']);
                        ?>

                        <ol>
                            <li style="margin: 15px 0;">
                                <label class="rating_ques1" for="" style="font-size: 0.9rem;">Q1-
                                    Did you receive sufficient information about the company's rules, regulations, and
                                    policies during the induction?</label>
                                <input type="hidden" name="rating_ques1" class="rating_ques1" value="Did you receive sufficient information about the company's rules, regulations, and
                                    policies during the induction?">
                                <br />

                                <div style="margin-top: 0.8rem;">
                                    <input type="radio" id="likes_q1" name="rating_ans1" value="10" class="ropt1">
                                    <label class="active" id="labelID" for="likes_q1"><i class="fa fa-thumbs-up fa-lg" style="color:green; margin-top: 0.2rem"></i></label>
                                </div>
                                <div>
                                    <input type="radio" id="dislikes_q1" name="rating_ans1" value="1" class="ropt2">
                                    <label class="active" id="labelID" for="dislikes_q1" style="color:red; margin-top: -2.2rem;margin-left: 5rem;"><i class="fa fa-thumbs-down fa-lg" style="margin-top: 0.3rem"></i></label>
                                </div>
                            </li>

                            <li style="margin: 15px 0;">
                                <label class="rating_ques2" for="" style="font-size: 0.9rem;">Q2-
                                    Was the language easy to understand during your induction session?</label>
                                <input type="hidden" name="rating_ques2" class="rating_ques2" value="Was the language easy to understand during your induction session">
                                <br />

                                <div style="margin-top: 0.8rem;">
                                    <input type="radio" id="likes_q2" name="rating_ans2" value="10" class="ropt1">
                                    <label class="active" id="labelID" for="likes_q2"><i class="fa fa-thumbs-up fa-lg" style="color:green; margin-top: 0.2rem"></i></label>
                                </div>

                                <div>
                                    <input type="radio" id="dislikes_q2" name="rating_ans2" value="1" class="ropt2">
                                    <label class="active" id="labelID" for="dislikes_q2" style="color:red; margin-top: -2.2rem;margin-left: 5rem;"><i class="fa fa-thumbs-down fa-lg" style="margin-top: 0.3rem"></i></label>
                                </div>
                            </li>

                            <li style="margin: 15px 0;">
                                <label class="rating_ques3" for="" style="font-size: 0.9rem;">Q3-
                                    Were you satisfied with the induction session?</label>
                                <input type="hidden" name="rating_ques3" class="rating_ques3" value="Were you satisfied with the induction session?">
                                <br />

                                <div style="margin-top: 0.8rem;">
                                    <input type="radio" id="likes_q3" name="rating_ans3" value="10" class="ropt1">
                                    <label class="active" id="labelID" for="likes_q3"><i class="fa fa-thumbs-up fa-lg" style="color:green; margin-top: 0.2rem"></i></label>
                                </div>

                                <div>
                                    <input type="radio" id="dislikes_q3" name="rating_ans3" value="1" class="ropt2">
                                    <label class="active" id="labelID" for="dislikes_q3" style="color:red; margin-top: -2.2rem;margin-left: 5rem;"><i class="fa fa-thumbs-down fa-lg" style="margin-top: 0.3rem"></i></label>
                                </div>
                            </li>

                            <br />
                        </ol>

                        <h6><b>How would you rate the induction session?</b></h6>
                        <div class="star-ratings" style="margin-left: -62rem;">
                            <div class="stars">
                                <select id="rating" name="rating" autocomplete="off" class="hidden" style="background-color: #19AEC4;display: none;">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                        </div>

                        <!-- <h6><b>How would you rate the induction session?</b></h6>
                        <div style="margin-top: 1rem;">
                            <select id="rating" name="rating" autocomplete="off" class="hidden" style="background-color: #19AEC4;">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div> -->

                        <p><textarea id="txt_Comment" class="materialize-textarea" name="txt_Comment" maxlength="200" placeholder="Please Share Your Feedback To Improve"></textarea>
                        </p>

                        <input type='hidden' name='empname' id="empname" value="<?php echo $clean_name; ?>">
                        <input type='hidden' name='empID' id="empID" value="<?php echo $clean_u_login; ?>">
                        <div class="input-field col s12 m12 right-align">
                            <button type="submit" name="btnSave" id="btnSave1" class="btn waves-effect waves-green">Submit</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!--Form container End -->

        </div>
        <!--Main Div for all Page End -->
    </div>
    <!--Content Div for all Page End -->
</div>

<script>
    $(document).ready(function() {
        var a = null;
        $('#rating').change(function() {
            a = $(this).val();
        });
        $("#ackdiv").hide();

        $("#btnQues").click(function() {
            <?php foreach ($resque as $val) { ?>
                if ($('input[name="ques_opt_<?php echo $val['id']; ?>"]:checked').length == 0) {
                    $('#req_<?php echo $val['id']; ?>').text('Please Answer Questions.');
                    //$('.req_<?php echo $val['id']; ?>').scrollTop();
                    var scrollPos = $(".optA1<?php echo $val['id']; ?>").offset().top;
                    $(window).scrollTop(scrollPos);
                    return false;
                } else {
                    $('#req_<?php echo $val['id']; ?>').text('');
                }
            <?php  } ?>
            $("#ackdiv").show();
            $("#questionaire_div").hide();
        })

        $('.caret').css('display', 'none');

        $('#btnSave1').click(function() {
            validate = 0;
            alert_msg = '';
            var address = $('#txt_Comment').val().replace(/^\s+|\s+$/g);

            if ($('input[name="rating_ans1"]:checked').length == 0) {
                $(function() {
                    toastr.error('Question 1 is Required');
                });
                validate = 1;
                return false;
            }

            if ($('input[name="rating_ans2"]:checked').length === 0) {
                $(function() {
                    toastr.error('Question 2 is Required');
                });
                validate = 1;
                return false;
            }

            if ($('input[name="rating_ans3"]:checked').length === 0) {
                $(function() {
                    toastr.error('Question 3 is Required');
                });
                validate = 1;
                return false;
            }

            if (a === null) {
                $(function() {
                    toastr.error('Rating is Required');
                });
                validate = 1;
                return false;
            }

            // if (validate === 0) {
            //     var selectedRating = $('#rating').val();
            //     alert(selectedRating)
            //     $('<input>').attr({
            //         type: 'hidden',
            //         id: 'selectedRating',
            //         name: 'selectedRating',
            //         value: selectedRating,
            //     }).appendTo('form');
            //     $('form').submit();
            // } else {
            //     alert(validate);
            // }

            if (address == "") {
                $('#txt_Comment').focus();
                $(function() {
                    toastr.error('Remarks should not be empty');
                });
                return false;
            }
        });
        $('.fadeIn').removeAttr('id', 'rmenu');
    });
</script>
<script src="../Script/bootstrap2.min.js"></script>
<script src="../Script/jquery.barrating.min.js"></script>

<style>
    .disablediv {
        pointer-events: none;
        opacity: 70% !important;
    }
</style>

<script>
    $(function() {
        $(".dropdown-trigger").hide();

        function ratingEnable() {
            $('#rating').barrating('show', {
                theme: 'bars-pill',
                initialRating: 'A',
                showValues: true,
                showSelectedRating: false,
                allowEmpty: true,
                required: true,
                emptyValue: '-- no rating selected --',
                // onSelect: function(value, text) {
                //     // alert('Selected rating: ' + value);
                // }
            });


        }

        ratingEnable();

    });
</script>