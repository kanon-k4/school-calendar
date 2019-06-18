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
require('../dbconnect.php');

// ログイン状態のチェック
if (!isset($_SESSION["user"])) {
  header("Location: .../login.php");
  exit;
}

if(!isset($_SESSION['schedule'])){
	header('Location: addschedule.php');
	exit();
}

if(isset($_POST)){
    //登録処理をする
        $sql = sprintf('INSERT INTO schedule (No, Year, Month, Day, Sh, Sm, Eh, Em, Title, Cal_No, Author_ID, Place, Memo) SELECT Max(No)+1, "%d", "%d", "%d", "%d", "%d", "%d", "%d", "%s", "%d", "%d", "%s", "%s" FROM schedule',
            (int)($_SESSION['schedule']['year']),
            (int)($_SESSION['schedule']['month']),
            (int)($_SESSION['schedule']['day']),
            (int)($_SESSION['schedule']['startH']),
            (int)($_SESSION['schedule']['startM']),
            (int)($_SESSION['schedule']['endH']),
            (int)($_SESSION['schedule']['endM']),
            mysqli_real_escape_string($db, $_SESSION['schedule']['title']),
            (int)($_SESSION['schedule']['calNo']),
            (int)($_SESSION['user']),
            mysqli_real_escape_string($db, $_SESSION['schedule']['place']),
            mysqli_real_escape_string($db, $_SESSION['schedule']['memo'])
        );
        mysqli_query($db, $sql) or die(mysqli_error($db));
        unset($_SESSION['schedule']);

        header('Location: addschedulecomp.php');
        exit();
}
?>

</body>
</HTML>
