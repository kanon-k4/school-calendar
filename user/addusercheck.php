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
  header("Location: ../login.php");
  exit;
}

if(!isset($_SESSION['join'])){
	header('Location: adduser.php');
	exit();
}

if(isset($_POST)){
    //登録処理をする
        $sql = sprintf('INSERT INTO users SET ID="%d",Name="%s", Pass="%s", Team="%d"',
            (int)($_SESSION['join']['id']),
            mysqli_real_escape_string($db, $_SESSION['join']['name']),
            mysqli_real_escape_string($db, sha1($_SESSION['join']['pass'])),
            (int)($_SESSION['join']['team'])
        );
        mysqli_query($db, $sql) or die(mysqli_error($db));
    //個人用カレンダーの作成
        $sql = sprintf('INSERT INTO cal_list (Cal_No, Cal_Name,Author_ID,Share) SELECT Count(*), "%s 個人用", "%d", "1" FROM cal_list',
                        mysqli_real_escape_string($db, $_SESSION['join']['name']),
                        (int)($_SESSION['join']['id']));
        mysqli_query($db, $sql) or die(mysqli_error($db));
    //個人用カレンダーの受け取り設定
        $sql = sprintf('INSERT INTO reg_list (ID, Cal_No) SELECT "%d", Cal_No FROM cal_list WHERE Cal_Name = "%s 個人用"',
                        (int)($_SESSION['join']['id']),
                        mysqli_real_escape_string($db, $_SESSION['join']['name']));
        mysqli_query($db, $sql) or die(mysqli_error($db));

        unset($_SESSION['join']);


        if($_SESSION['user'] == 1){
            header('Location: ../logout.php');
            exit;
        }
        header('Location: addusercomp.php');
        exit();
}
?>

</body>
</HTML>
