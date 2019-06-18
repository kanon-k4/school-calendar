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

if(!isset($_SESSION['team'])){
	header('Location: addteam.php');
	exit();
}

if(isset($_POST)){
    //登録処理をする
        $sql = sprintf('INSERT INTO team (T_ID,T_Name) SELECT Count(*) , "%s" FROM team',
            mysqli_real_escape_string($db, $_SESSION['team']['team'])
        );
        mysqli_query($db, $sql) or die(mysqli_error($db));
        unset($_SESSION['team']);

        header('Location: addteamcomp.php');
        exit();
}
?>

</body>
</HTML>
