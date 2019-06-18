<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale1">
    <title>今日以降一覧 - カレンダー</title>
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

?>
今日以降のスケジュール一覧<br />
<a href="#" onclick="javascript:window.history.back(-1);return false;"><=戻る</a><br />

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
      //今年以降の全てのスケジュールを取得
      $sql = sprintf('SELECT X.No,X.Title,X.Year,X.Month,X.Day,X.Sh,X.Sm,X.Eh,X.Em,X.Place,Y.Name,Z.Cal_Name,X.Memo,Z.Edit,Z.Author_ID
                      FROM schedule X, users Y, cal_list Z
                      WHERE X.Cal_No IN (SELECT Cal_No FROM reg_list WHERE ID = "%d") 
                      AND X.Author_ID = Y.ID 
                      AND X.Cal_No = Z.Cal_No 
                      AND X.Year >= "%d" 
                      ORDER BY X.Year, X.Month, X.Day, X.Sh, X.Sm',
                      (int)($_SESSION['user']),
                      (int)$year,
                      (int)$month); //今月以降を呼び出し
      $record = mysqli_query($db, $sql) or die(mysqli_error($db));
      while ($row = mysqli_fetch_assoc($record)) {
          if($row['Year'] == $year && $row['Month'] < $month){
              //今年で今月以前のものは表示しない
          }else{
              if($row['Month'] == $month && $row['Day'] < $day){
                  //今月で今日以前のものは表示しない
              }else{
                  $html = sprintf('<tr><td>%d</td> <td>%s</td> <td>%d年<br>%d月<br>%d日</td> <td>%d時<br>%d分</td> <td>%d時<br>%d分</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td>',
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
                  //編集権限を確認
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
              }
          }
      }
  ?>
</table>
<br />
<a href="#" onclick="javascript:window.history.back(-1);return false;"><=戻る</a>

</body>
</HTML>
