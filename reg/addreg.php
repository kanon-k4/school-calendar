<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale1">
    <title>新規受け取りカレンダー - カレンダー</title>
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

if(!empty($_POST)){
  //エラー項目の確認
  if($_POST['addCal'] == ''){
    $error['addCal'] = 'blank';
  }

  if(empty($error)){
    $_SESSION['reg'] = $_POST;
    header('Location: addregcheck.php');
    exit();
  }
}

?>

<form action="" method="post" enctype="multipart/form-data">
  <dl>
    <dt>新規受け取りカレンダー<font color="red">　必須</font></dt>
    <dd>
      <SELECT name="addCal">
        <OPTION value="">▼選択してください</OPTION>
      <?php 
          //公開カレンダーから追加していないものを表示
          $sql = sprintf('SELECT * FROM cal_list WHERE Share = 0 AND Cal_No NOT IN (SELECT Cal_No FROM reg_list WHERE ID = "%d")',(int)($_SESSION['user']));
          $record = mysqli_query($db, $sql) or die(mysqli_error($db));
          while ($row = mysqli_fetch_assoc($record)) {
              $html = sprintf('<OPTION value="%d">%d %s</OPTION>', $row['Cal_No'], $row['Cal_No'], $row['Cal_Name']);
              print($html);
          }
      ?>
      </SELECT>
      <?php if(!empty($error['addCal']) && $error['addCal'] == 'blank'): ?>
        <p><font color="red">* 追加するカレンダーを選択してください</font></p>
      <?php endif; ?>
    </dd>
  </dl>
  <div><input type="submit" value="登録"></div>
  </form>
<br />
<a href="#" onclick="javascript:window.history.back(-1);return false;"><=戻る</a>

</body>
</HTML>
