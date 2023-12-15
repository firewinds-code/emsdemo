<?php
require_once(__dir__.'/../Config/init.php');
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(CLS.'MysqliDb.php');

//print_r($_SESSION);
//$page = $_SERVER['PHP_SELF'];
$page = $_SERVER['REQUEST_URI'];
 $sec = "3";
 header("Refresh: $sec; url=$page");
// echo "Watch the page reload itself in 5 second!";


$myDB = new MysqliDb();
$receiver=  $_GET['receiver'] ;
$sender= $_GET['sender'] ;
    $result = $myDB->query("select * from chatlog where receiver='".$_GET['receiver']."' and sender='".$_GET['sender']."' order by  chatdt desc");
	
	//$chatlog = $result[0]['chatlog'];
   // $chatdt = $result[0]['chatdt'];


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


<body>

    <div class="container">            
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title" style="text-align:center;float:none"> Chating With <?php echo $sender;?> </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table">                        
                        <tbody>
						<?php
							foreach($result as $row)
								  {?>
                            <tr>
								  <td><?php echo $row['chatlog'];?></td>
								   <td><?php echo $row['sender'] . "<br>" . $row['chatdt'];?></td>
                            </tr>
							<?php }
								  ?>
                        </tbody>
                    </table>
					<input name="chattxt" >
                </div>
                <!-- /.card-body -->
            </div>        
    </div>
</body>