<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale1">
    <title>受け取りカレンダー削除 - カレンダー</title>
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
  if($_POST['delCal'] == ''){
    $error['addCal'] = 'blank';
  }

  if(empty($error)){
    $_SESSION['reg'] = $_POST;
    header('Location: delregcheck.php');
    exit();
  }
}

?>

<form action="" method="post" enctype="multipart/form-data">
  <dl>
    <dt>受け取り削除カレンダー<font color="red">　必須</font></dt>
    <dd>
      <SELECT name="delCal">
        <OPTION value="">▼選択してください</OPTION>
      <?php 
          //追加しているカレンダー一覧を取得(個人用は不可)
          $sql = sprintf('SELECT X.Cal_No,X.Cal_Name FROM cal_list X, reg_list Y WHERE X.Cal_No = Y.Cal_No AND Y.ID = "%d" AND X.Share = 0 ORDER BY X.Cal_No',(int)$_SESSION['user'] );
          $record = mysqli_query($db, $sql) or die(mysqli_error($db));
          while ($row = mysqli_fetch_assoc($record)) {
              $html = sprintf('<OPTION value="%d">%d %s</OPTION>', $row['Cal_No'], $row['Cal_No'], $row['Cal_Name']);
              print($html);
          }
      ?>
      </SELECT>
      <?php if(!empty($error['delCal']) && $error['delCal'] == 'blank'): ?>
        <p><font color="red">* 受け取り削除するカレンダーを選択してください</font></p>
      <?php endif; ?>
    </dd>
  </dl>
  <div><input type="submit" value="削除"></div>
  </form>
<br />
<a href="#" onclick="javascript:window.history.back(-1);return false;"><=戻る</a>

</body>
</HTML>
