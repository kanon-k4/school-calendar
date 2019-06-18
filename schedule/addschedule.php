<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale1">
    <title>新規スケジュール - カレンダー</title>
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

//初期変数設定
$year = '';
$month = '';
$day = '';
$title = '';
$startH = '';
$startM = '';
$endH = '';
$endM = '';
$place = '';
$memo = '';

if(!empty($_POST)){
  //エラー項目の確認
  if($_POST['year'] == '' || $_POST['month'] == '' || $_POST['day'] == ''){
    $error['date'] = 'blank';
  }else if( is_numeric($_POST['year']) == false || is_numeric($_POST['month']) == false || is_numeric($_POST['day']) == false){
      $error['date'] = 'cantC';
  }else{
    if( checkdate($_POST['month'], $_POST['day'], $_POST['year']) == false ){
      $error['date'] = 'not';
    }else{
      $ym_now = date("Ymd");
      $year2 = substr($ym_now, 0, 4);
      $month2 = substr($ym_now, 4, 2);
      $day2 = substr($ym_now, 6, 2);
      if( $_POST['year'] < $year2){
         if ( $_POST['month'] < $month2 ){
           if( $_POST['day'] < $day2 ){
             $error['date'] = 'out';
           }
         }
      }
    }
  }

  if($_POST['title'] == ''){
    $error['title'] = 'blank';
  }else $title = $_POST['title'];

  if($_POST['calNo'] == ''){
    $error['calNo'] = 'blank';
  }else $calNo = $_POST['calNo'];

  if($_POST['startH'] == '' || $_POST['startH'] == ''){
    $error['start'] = 'blank';
  }else if( is_numeric($_POST['startH']) == false || is_numeric($_POST['startM']) == false ){
    $error['start'] = 'cantC';
  }else if( ((int)$_POST['startH'] < 0 || (int)$_POST['startH'] >= 24) || ((int)$_POST['startM'] < 0 || (int)$_POST['startM'] >= 60) ){
    $error['start'] = 'not';
  }

  if($_POST['endH'] == '' || $_POST['endM'] == ''){
    $error['end'] = 'blank';
  }else if( is_numeric($_POST['endH']) == false || is_numeric($_POST['endM']) == false ){
    $error['end'] = 'cantC';
  }else if( ((int)$_POST['endH'] < 0 || (int)$_POST['endH'] >= 24) || ((int)$_POST['endM'] < 0 || $_POST['startM'] >= 60) ){
    $error['end'] = 'not';
  }else if(empty($error['start'])){
    $start = (int)$_POST['startH']*60+(int)$_POST['startM'];
    $end = (int)$_POST['endH']*60+(int)$_POST['endM'];
    if( $start > $end ) {
      $error['end'] = 'out';
    }
  }
//変数設定
  $year = $_POST['year'];
  $month = $_POST['month'];
  $day = $_POST['day'];
  $title = $_POST['title'];
  $startH = $_POST['startH'];
  $startM = $_POST['startM'];
  $endH = $_POST['endH'];
  $endM = $_POST['endM'];
  $place = $_POST['place'];
  $memo = $_POST['memo'];

  if(empty($error)){
    $_SESSION['schedule'] = $_POST;
    header('Location: addschedulecheck.php');
    exit();
  }
}

?>

<form action="" method="post" enctype="multipart/form-data">
  <dl>
    <dt><font color="Red">*は必須</font></dt>
    <dt>日付(年・月・日)*</dt>
    <dd>
      <input type="text" name="year" size="4" maxlength="4" value="<?php echo htmlspecialchars($year, ENT_QUOTES, 'UTF-8'); ?>">年
      <input type="text" name="month" size="2" maxlength="2" value="<?php echo htmlspecialchars($month, ENT_QUOTES, 'UTF-8'); ?>">月
      <input type="text" name="day" size="2" maxlength="2" value="<?php echo htmlspecialchars($day, ENT_QUOTES, 'UTF-8'); ?>">日
      <?php if(!empty($error['date']) && $error['date'] == 'blank'): ?>
        <p><font color="red">* 日付を入力してください</font></p>
      <?php endif; ?>
      <?php if(!empty($error['date']) && $error['date'] == 'cantC'): ?>
        <p><font color="red">* 使用できない文字です</font></p>
      <?php endif; ?>
      <?php if(!empty($error['date']) && $error['date'] == 'not'): ?>
        <p><font color="red">* 無効な日付です</font></p>
      <?php endif; ?>
      <?php if(!empty($error['date']) && $error['date'] == 'out'): ?>
        <p><font color="red">* 無効な日付です</font></p>
      <?php endif; ?>
    </dd>
    <dt>タイトル*</dt>
    <dd>
      <input type="text" name="title" size="30" maxlength="100" value="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
      <?php if(!empty($error['title']) && $error['title'] == 'blank'): ?>
        <p><font color="red">* タイトルを設定してください</font></p>
      <?php endif; ?>
    </dd>
    <dt>追加先カレンダー*(先に<a href="../reg/addreg.php">こちら</a>から受け取りカレンダーを設定してください)</dt>
    <dd>
      <SELECT name="calNo">
        <OPTION value="">▼選択してください</OPTION>
        <?php 
            //受け取り設定しているカレンダーを取得
            $sql = sprintf('SELECT * FROM cal_list WHERE Cal_No IN (SELECT Cal_No FROM reg_list WHERE ID = %d) ORDER BY Cal_No',(int)($_SESSION['user']));
            $record = mysqli_query($db, $sql) or die(mysqli_error($db));
            while ($row = mysqli_fetch_assoc($record)) {
                //編集権限の確認
                if($row['Edit'] == 1){
                    if($row['Author_ID'] == $_SESSION['user']){
                        $html = sprintf('<OPTION value="%d">%d %s</OPTION>', $row['Cal_No'], $row['Cal_No'], $row['Cal_Name']);
                        print($html);
                    }
                }else{
                    $html = sprintf('<OPTION value="%d">%d %s</OPTION>', $row['Cal_No'], $row['Cal_No'], $row['Cal_Name']);
                    print($html);
                }
            }
        ?>
      </SELECT>
      <?php if(!empty($error['calNo']) && $error['calNo'] == 'blank'): ?>
        <p><font color="red">* 追加先カレンダーを選択してください</font></p>
      <?php endif; ?>
    </dd>
    <dt>開始時間*</dt>
    <dd>
      <input type="text" name="startH" size="2" maxlength="2" value="<?php echo htmlspecialchars($startH, ENT_QUOTES, 'UTF-8'); ?>">時
      <input type="text" name="startM" size="2" maxlength="2" value="<?php echo htmlspecialchars($startM, ENT_QUOTES, 'UTF-8'); ?>">分
      <?php if(!empty($error['start']) && $error['start'] == 'blank'): ?>
        <p><font color="red">* 開始時間を設定してください</font></p>
      <?php endif; ?>
      <?php if(!empty($error['start']) && $error['start'] == 'cantC'): ?>
        <p><font color="red">* 使用できない文字が含まれています</font></p>
      <?php endif; ?>
      <?php if(!empty($error['start']) && $error['start'] == 'not'): ?>
        <p><font color="red">* 無効な時間です</font></p>
      <?php endif; ?>
    </dd>
    <dt>終了時間*</dt>
    <dd>
      <input type="text" name="endH" size="2" maxlength="2" value="<?php echo htmlspecialchars($endH, ENT_QUOTES, 'UTF-8'); ?>">時
      <input type="text" name="endM" size="2" maxlength="2" value="<?php echo htmlspecialchars($endM, ENT_QUOTES, 'UTF-8'); ?>">分
      <?php if(!empty($error['end']) && $error['end'] == 'blank'): ?>
        <p><font color="red">* 終了時間を設定してください</font></p>
      <?php endif; ?>
      <?php if(!empty($error['end']) && $error['end'] == 'cantC'): ?>
        <p><font color="red">* 使用できない文字が含まれています</font></p>
      <?php endif; ?>
      <?php if(!empty($error['end']) && $error['end'] == 'not'): ?>
        <p><font color="red">* 無効な時間です</font></p>
      <?php endif; ?>
      <?php if(!empty($error['end']) && $error['end'] == 'out'): ?>
        <p><font color="red">* 開始時間より前に設定できません</font></p>
      <?php endif; ?>
    </dd>
    <dt>場所</dt>
    <dd>
      <input type="text" name="place" size="20" maxlength="100" value="<?php echo htmlspecialchars($place, ENT_QUOTES, 'UTF-8'); ?>">
    </dd>
    <dt>Memo</dt>
    <dd>
      <input type="text" name="memo" size="20" maxlength="100" value="<?php echo htmlspecialchars($memo, ENT_QUOTES, 'UTF-8'); ?>">
    </dt>
  </dl>
  <div><input type="submit" value="登録"></div>
  </form>
<br />
<a href="#" onclick="javascript:window.history.back(-1);return false;"><=戻る</a>

</body>
</HTML>
