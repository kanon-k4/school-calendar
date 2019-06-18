<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale1">
    <title>Error! - カレンダー</title>
</head>

<body>
<?php

session_start();
//ログイン状態確認
if (!isset($_SESSION["user"])) {
  header("Location: ../login.php");
  exit;
}
require('../dbconnect.php');

if(!isset($_SESSION['cal'])){
	header('Location: addcal.php');
	exit();
}

if(isset($_POST)){
    //登録処理をする
        $sql = sprintf('INSERT INTO cal_list (Cal_No, Cal_Name, Author_ID, Edit) SELECT Count(*), "%s", "%d", "%d" FROM cal_list',
            mysqli_real_escape_string($db, $_SESSION['cal']['calName']),
            (int)($_SESSION['user']),
            (int)($_SESSION['cal']['edit'])
        );
        mysqli_query($db, $sql) or die(mysqli_error($db));
    //受け取り設定を行う
        $sql = sprintf('INSERT INTO reg_list (ID, Cal_No) SELECT "%d", Cal_No FROM cal_list WHERE Cal_Name = "%s" AND Author_ID = "%d"',
                        (int)($_SESSION['user']),
                        mysqli_real_escape_string($db, $_SESSION['cal']['calName']),
                        (int)($_SESSION['user']));
        mysqli_query($db, $sql) or die(mysqli_error($db));

        unset($_SESSION['cal']);

        header('Location: addcalcomp.php');
        exit();
}
?>

</body>
</HTML>
