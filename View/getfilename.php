<?php 
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
if ($handle = opendir('../checklist_pdf')) {
$i=1;
    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != "..") {

           // echo $i."_  $entry";
            //echo "<br>";
		 $myDB->query("insert into docfiles(docnmae,filename) values('checklist_pdf','".$entry."');");
		 $i++;
        }
    }
echo $i;
    closedir($handle);
}
?>