<?php

require_once(__dir__.'/../Config/init.php');


date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(CLS.'MysqliDb.php');

//print_r($_SESSION);
?>
<?php

$myDB = new MysqliDb();
    $result = $myDB->query("select location from personal_details where EmployeeID='".$_GET['id']."' ");
    $loc = $result[0]['location'];
$show = 0;



    // $Query = "select * from location where  id='" . $_REQUEST['id'] . "' order by id desc";
    // $result = mysqli_query($conn, $Query);
    // $resultdata = mysqli_fetch_assoc($result);
    if ($loc == 2) {
        $Query = "select * from locations where  location_id = 2 order by id desc";
        $mum = 1;
    } else {
        $Query = "select * from locations where  location_id='" . $loc . "' order by id desc";
        $mum = 0;
    }

    $myDB = new MysqliDb();
    $result = $myDB->query($Query);
    if ($result) {
        $show = 1;

        //print_r($result);
        // exit;
    } else {
        $show = 0;
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POSH SPOC</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
</head>
<!-- /.card -->
<!-- <style>
    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid rgba(0, 0, 0, .125);
        border-radius: 0.25 rem;
    }
</style> -->

<body>

    <div class="container">
        <?php if ($show == 1) { ?>
            <div class="card card-info" style="margin-top:20px">
                <div class="card-header">
                    <h3 class="card-title" style="text-align:center;float:none">POSH SPOC - L1</h3>


                </div>
                <div class="card-body p-0">
                    <?php if ($mum == 0) { ?>
                        <table class="table">

                            <tbody>

                                <tr>
                                    <td>Location</td>

                                    <td><?php echo $result[0]['location'] ?></td>


                                <tr>
                                    <td>Name</td>

                                    <td><?php echo $result[0]['name'] ?></td>

                                <tr>
                                    <td>Designation</td>
                                    <td><?php echo $result[0]['designation'] ?></td>
                                <tr>
                                    <td>Mobile No.</td>
                                    <td><?php echo $result[0]['contact'] ?></td>


                            </tbody>
                        </table>
                    <?php } else { ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Mobile No.</th>
                            </thead>
                            <tbody>
                                <?php foreach ($result as $value) { ?>
                                    <tr>

                                        <td><?php echo $value['location'] ?></td>
                                        <td><?php echo $value['name'] ?></td>
                                        <td><?php echo $value['designation'] ?></td>
                                        <td><?php echo $value['contact'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
                <!-- /.card-body -->
            </div>

            <hr>
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title" style="text-align:center;float:none"> POSH SPOC - L2</h3>


                </div>
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>

                                <th>Designation</th>
                                <th>Mobile No.</th>
                        </thead>
                        <tbody>

                            <tr>

                                <td>Banpreet Kaur</td>




                                <td>General Manager</td>



                                <td>9540045651</td>
                            </tr>

                            <!--<tr>
                                <td>Gayathri Ravishankar</td>




                                <td>General Manager</td>



                                <td>9886135559

                                </td>
                            </tr>-->

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

        <?php } else { ?>


            <!-- hjhjhgj -->
            <div class="row" align="center" style="margin-top:100px;">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_content ">

                            <div class="alert alert-danger" id="msg">Invalid URL</div>
                        </div>
                    </div>
                </div>

            </div>
        <?php } ?>
    </div>

</body>