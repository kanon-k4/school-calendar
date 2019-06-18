<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale1">
    <title>日付別一覧 - カレンダー</title>
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

//初期変数設定
$ym_now = date("Ymd");
$year = substr($ym_now, 0, 4);
$month = substr($ym_now, 4, 2);
$day = substr($ym_now, 6, 2);
//アドレスから変数取得
if(isset($_GET['year'])) {
    $year = $_GET['year'];
}
if(isset($_GET['month'])) {
    $month = $_GET['month'];
}
if(isset($_GET['day'])) {
    $day = $_GET['day'];
}
if(checkdate($month, $day, $year) == false){
  print("指定された日付は存在しません\n");
  exit;
}
print ("$year 年 $month 月 $day 日 のスケジュール\n");

?>

<table width="1000" border="1" id="list">
  <tr>
    <td width="40" align="center" valign="middle">No</td>
    <td width="200" align="center" valign="middle">タイトル</td>
    <td width="80" align="center" valign="middle">日付</td>
    <td width="100" align="center" valign="middle">開始時間</td>
    <td width="100" align="center" valign="middle">終了時間</td>
    <td width="100" align="center" valign="middle">場所</td>
    <td width="80" align="center" valign="middle">作成者</td>
    <td width="100" align="center" valign="middle">カレンダー</td>
    <td width="100" align="center" valign="middle">Memo</td>
    <td width="100" align="center" valign="middle">編集</td>
  </tr>
  <?php
      //その日の一覧を時間でソートして表示
      $sql = sprintf('SELECT X.No,X.Title,X.Year,X.Month,X.Day,X.Sh,X.Sm,X.Eh,X.Em,X.Place,Y.Name,Z.Cal_Name,X.Memo,Z.Edit,Z.Author_ID
                      FROM schedule X, users Y, cal_list Z
                      WHERE X.Cal_No IN (SELECT Cal_No FROM reg_list WHERE ID = "%d") 
                      AND X.Year = "%d" 
                      AND X.Month = "%d" 
                      AND X.Day = "%d" 
                      AND X.Author_ID = Y.ID 
                      AND X.Cal_No = Z.Cal_No 
                      ORDER BY X.Sh, X.Sm',
                      (int)($_SESSION['user']),
                      (int)$year,
                      (int)$month,
                      (int)$day);
      $record = mysqli_query($db, $sql) or die(mysqli_error($db));
      $i = 0;
      while ($row = mysqli_fetch_assoc($record)) {
          $html = sprintf('<tr><td>%d</td> <td>%s</td> <td>%d年%d月%d日</td> <td>%d時%d分</td> <td>%d時%d分</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td>',
                           $row['No'],
                           $row['Title'],
                           $row['Year'],
                           $row['Month'],
                           $row['Day'],
                           $row['Sh'],
                           $row['Sm'],
                           $row['Eh'],
                           $row['Em'],
                           $row['Place'],
                           $row['Name'],
                           $row['Cal_Name'],
                           $row['Memo']
                         );
          print($html);
          print("<td>");
          //編集権限の確認
          if($row['Edit'] == 1){
              if($row['Author_ID'] == $_SESSION['user']){
                  $html = sprintf('<a href="delschedule.php?No=%d">削除</a>',$row['No']);
                  print($html);
              }
          }else{
              $html = sprintf('<a href="delschedule.php?No=%d">削除</a>',$row['No']);
              print($html);
          }
          print("</td></tr>");
          $i++;
      }
  ?>
</table>
<?php if($i == 0): ?>
    <p>スケジュールがありません</p>
<?php endif; ?>
<br />
<a href="#" onclick="javascript:window.history.back(-1);return false;"><=戻る</a>

</body>
</HTML>
