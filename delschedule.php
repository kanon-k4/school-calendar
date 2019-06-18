<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale1">
    <title>スケジュール削除 - カレンダー</title>
</head>

<body>
<?php

session_start();
require('dbconnect.php');

//削除用関数
function del($db,$No){
    $sql2 = sprintf('DELETE FROM schedule WHERE No = "%d"',$No);
    $record2 = mysqli_query($db, $sql2) or die(mysqli_error($db));
    if($record2 = true){
        print("削除が完了しました");
    }else{
        print("削除に失敗しました");
    }
    
}

// ログイン状態のチェック
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}
//アドレスから変数取得
if(isset($_GET['No'])) {
    $No = $_GET['No'];
}
//NOが存在しない場合
if(!isset($No)){
    print("Error!!!");
    exit();
}

//削除できるかの確認。権限があれば削除関数を呼び出す
$sql = sprintf('SELECT Y.Edit, Y.Author_ID
                FROM schedule X, cal_list Y, reg_list Z
                WHERE X.No = "%d"
                AND X.Cal_No = Y.Cal_No
                AND X.Cal_No = Z.Cal_No
                AND Z.ID = "%d"',
                (int)$No,
                (int)$_SESSION['user']);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
if($row = mysqli_fetch_assoc($record)){
    if($row['Edit'] == 1){
        if($row['Author_ID'] == $_SESSION['user']){
            del($db,$No);
        }else{
            print("Error!!!");
        }
    }else{
        del($db,$No);
    }
}else{
    print("Error!!!");
}

?>
<br />
<a href="#" onclick="javascript:window.history.back(-1);return false;"><=戻る</a>

</body>
</HTML>
