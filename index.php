<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale1">
    <title>トップページ - カレンダー</title>
</head>

<body>
<?php
//セッションスタート
session_start();

// ログイン状態のチェック
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}
if ($_SESSION['user'] == 1){
  header("Location: user/adduser.php");
  exit;
}
?>

<p>ようこそ、<?=htmlspecialchars($_SESSION["UserName"], ENT_QUOTES); ?>さん</p>
<p>&nbsp;&nbsp;<a href="function.php">各種機能</a>&nbsp;&nbsp;<a href="logout.php">ログアウト</a></p>
<?php
//初期変数指定
$ym_now = date("Ym");
$year = substr($ym_now, 0, 4);
$month = substr($ym_now, 4, 2);
//アドレスから変数取得
if(isset($_GET['year'])) {
    $year = $_GET['year'];
}
if(isset($_GET['month'])) {
    $month = $_GET['month'];
}
//1～9月に01月となるのを対処
if($month < 10){
    $month = $month%10;
}
/* cal関数
  与えられたyearとmonthからその月のカレンダーを作成する */
function cal( $year, $month ){
    print "$year 年 $month 月\n";
    //カレンダー出力
    print "<table border=\"1\" id=\"mainCal\">\n";
    print "<tr><th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th></tr>\n";
    $day = 1;
    print "<tr>";
    for( $i=0; $i < date("w", mktime(0, 0, 0, $month, $day, $year)); $i++){
        print "<th></th>";
    }
    while (checkdate($month, $day, $year)) {
        print "<td><a href=\"schedule.php?year=$year&month=$month&day=$day\">$day</a></td>";
        if( date("w", mktime(0, 0, 0, $month, $day, $year)) == 6 ){
            print "</tr>";
            if ( checkdate($month, $day+7, $year) == false ){
                print "<tr>";
            }
        }
        $day++;
    }
    print "</table>\n";
}
/* selectMonth
   月を切り替える */
function selectMonth($year,$month){
    $ym_now = date("Ym");
    $y = substr($ym_now, 0, 4)-1;
    $m = substr($ym_now, 4, 2);

    $html = "<Form name=\"selectMonth\"><SELECT name=\"year\">\n";
    for($i=0; $i<3; $i++){
        if ($y == $year){
            $html .="<OPTION value=\"$y\" selected>$y</OPTION>\n";
        }else{
            $html .="<OPTION value=\"$y\">$y</OPTION>\n";
        }
        $y++;
    }
    $html .="</SELECT>年\n<SELECT name=\"month\">\n";

    for($i=1; $i<=12; $i++){
        if ($i == $month){
            $html .="<OPTION value=\"$i\"selected>$i</OPTION>\n";
        }else{
            $html .="<OPTION value=\"$i\">$i</OPTION>\n";
        }
    }
    $html .="</SELECT>月\n";
    print $html;
    print "<input type=\"button\" value=\"表示\" onclick=\"changePage();\"></Form>\n";
}

//実行
cal($year,$month);
selectMonth($year,$month);
?>

<script>
/* 月切り替えのボタンが押さえれたときの処理 */
function changePage(){
    var year =document.selectMonth.year.value;
    var month =document.selectMonth.month.value;
    var link = "index.php?year="+year+"&month="+month;
    window.location.href = link;
}
</script>
<p><a href="schedule_list.php">今日以降のスケジュール一覧</a><br>
<a href="schedule/addschedule.php">スケジュール追加</a>
</p>
</body>
</HTML>
