<?php
session_start();

if (isset($_SESSION['UsrNm']) && (trim($_SESSION['UsrNm'])) && (strlen($_SESSION['UsrNm']) <= 15)) {
    if ((substr($_SESSION['UsrNm'], 0, 2) == 'CE') || (substr($_SESSION['UsrNm'], 0, 2) == 'MU')) {
        $UsrNm = clean($_SESSION['UsrNm']);
    }
}
echo  $UsrNm;
if ($_SESSION) {
    if ($UsrNm != '' || $UsrNm == NULL) {
        header("location:../index.php");
    } else {
        header("location:../LogOut.php");
    }
} else {
    header("location:../LogOut.php");
}
