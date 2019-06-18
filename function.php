<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale1">
    <title>機能一覧 - カレンダー</title>
</head>

<body>
<?php

session_start();
require('dbconnect.php');

// ログイン状態のチェック
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}
?>
<h1>機能一覧</h1>
<table width="400" border="0" cellspacing="0">
  <tr>
    <td><a href="index.php">トップページ</a></td>
  </tr>
  <tr>
    <td><a href="schedule.php">日別スケジュール一覧</a></td>
  </tr>
  <tr>
    <td><a href="schedule_list.php">今日以降のスケジュール一覧</a></td>
  </tr>
  <tr>
    <td><a href="schedule/addschedule.php">スケジュール追加</a></td>
  </tr>
  <tr>
    <td><a href="reg/addreg.php">受け取りカレンダー追加</a></td>
  </tr>
  <tr>
    <td><a href="reg/delreg.php">受け取りカレンダー削除</a></td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <?php
    //教職員IDでログインしているときのみ表示
      $sql = sprintf('SELECT Team FROM users WHERE ID = "%d"',(int)$_SESSION['user']);
      $result = mysqli_query($db, $sql) or die(mysqli_error($db));
      $Team = mysqli_fetch_assoc($result);
      if((int)$Team['Team'] == 0): ?>
  <tr>
    <td><a href="user/adduser.php">ユーザー追加</a></td>
  </tr>
  <tr>
    <td><a href="team/addteam.php">チーム追加</a></td>
  </tr>
  <tr>
    <td><a href="cal/addcal.php">新規カレンダー追加</a></td>
  </tr>
  <?php endif; ?>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td><a href="readme.html">ヘルプページ</a></td>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td><a href="logout.php">ログアウト</a></td>
  </tr>
</table>
</body>
</HTML>
