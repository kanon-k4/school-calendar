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

if(!isset($_SESSION['reg'])){
	header('Location: addreg.php');
	exit();
}

if(isset($_POST)){
    //既に登録されているのか確認
        $sql = sprintf('SELECT Cal_No FROM reg_list WHERE ID=%d',(int)($_SESSION['user']));
        $record = mysqli_query($db, $sql) or die(mysqli_error($db));
        while ($row = mysqli_fetch_assoc($record)) {
            if($_SESSION['reg']['addCal'] == $row['Cal_No']){
                print('既に登録されています。<br><br>');
                print('<a href = addreg.php>前のページに戻る</a>');
                exit;
            }
        }
    //登録処理をする
        $sql = sprintf('INSERT INTO reg_list SET ID="%d",Cal_No="%d"',
            (int)($_SESSION['user']),
            (int)($_SESSION['reg']['addCal'])
        );
        mysqli_query($db, $sql) or die(mysqli_error($db));
        unset($_SESSION['reg']);

        header('Location: addregcomp.php');
        exit();
}
?>
</body>
</HTML>
