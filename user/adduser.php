<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale1">
    <title>新規ユーザ作成 - カレンダー</title>
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
$id = '';
$name = '';
$pass = '';
$team = '';

if(!empty($_POST)){
  //エラー項目の確認
  if($_POST['id'] == ''){
    $error['id'] = 'blank';
  }else{
    $id = $_POST['id'];
    if(is_numeric($_POST['id']) == true){
        //IDかぶりチェック
        $sql = sprintf('SELECT ID FROM users WHERE ID=%d',(int)$_POST['id']);
        $result = mysqli_query($db,$sql);
        if(!$result){
            $error['id'] = 'cantC';
        }else{
            $row = mysqli_fetch_assoc($result);
            if($row['ID'] == $_POST['id']){
                $error['id'] = 'found';
            }
        }
    }else $error['id'] = 'cantC';
  }
  if($_POST['name'] == ''){
    $error['name'] = 'blank';
  }else $name = $_POST['name'];
  if(strlen($_POST['pass']) < 4){
    $error['pass'] = 'length';
  }
  if($_POST['pass'] == ''){
    $error['pass'] = 'blank';
  }else $pass = $_POST['pass'];
  if($_POST['pass'] != $_POST['pass2']){
    $error['pass2'] = 'match';
  }
  if($_POST['team'] == ''){
    $error['team'] = 'blank';
  }else $team = $_POST['team'];

  if(empty($error)){
    $_SESSION['join'] = $_POST;
    header('Location: addusercheck.php');
    exit();
  }
}

?>

<form action="" method="post" enctype="multipart/form-data">
  <dl>
    <dt>ユーザーID<font color="red">　必須</font></dt>
    <dd>
      <input type="text" name="id" size="10" maxlength="10" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
      <?php if(!empty($error['id']) && $error['id'] == 'blank'): ?>
        <p><font color="red">* ユーザーIDを入力してください</font></p>
      <?php endif; ?>
      <?php if(!empty($error['id']) && $error['id'] == 'cantC'): ?>
        <p><font color="red">* 使用できない文字が含まれています</font></p>
      <?php endif; ?>
      <?php if(!empty($error['id']) && $error['id'] == 'found'): ?>
        <p><font color="red">* 既に使用されているユーザーIDです</font></p>
      <?php endif; ?>
    </dd>
    <dt>氏名<font color="red">　必須</font></dt>
    <dd>
      <input type="text" name="name" size="35" maxlength="20" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
      <?php if(!empty($error['name']) && $error['name'] == 'blank'): ?>
        <p><font color="red">* 氏名を入力してください</font></p>
      <?php endif; ?>
    </dd>
    <dt>パスワード<font color="red">　必須</font></dt>
    <dd>
      <input type="password" name="pass" size="10" maxlength="20" value="<?php echo htmlspecialchars($pass, ENT_QUOTES, 'UTF-8'); ?>">
      <?php if(!empty($error['pass']) && $error['pass'] == 'blank'): ?>
        <p><font color="red">* パスワードを入力してください</font></p>
      <?php endif; ?>
      <?php if(!empty($error['pass']) && $error['pass'] == 'length'): ?>
        <p><font color="red">* パスワードは４文字以上で入力してください</font></p>
      <?php endif; ?>
    </dd>
    <dt>Re-Type<font color="red">　必須</font></dt>
    <dd>
      <input type="password" name="pass2" size="10" maxlength="20">
      <?php if(!empty($error['pass2']) && $error['pass2'] == 'match'): ?>
        <p><font color="red">* パスワードが一致しません</font></p>
      <?php endif; ?>
    </dd>
    <dt>所属チーム<font color="red">　必須</font></dt>
    <dd>
      <SELECT name="team">
        <OPTION value="">▼選択してください</OPTION>
      <?php 
          //チーム一覧を取得(ID「1」の時は教職員を表示しない)
          $sql = sprintf('SELECT * FROM team');
          $record = mysqli_query($db, $sql) or die(mysqli_error($db));
          while ($row = mysqli_fetch_assoc($record)) {
              if($row['T_ID'] == 0){
                  if($_SESSION['user'] != 1){
                      $html = sprintf('<OPTION value="%d">%d %s</OPTION>', $row['T_ID'], $row['T_ID'], $row['T_Name']);
                      print($html);
                  }
              }else{
                  $html = sprintf('<OPTION value="%d">%d %s</OPTION>', $row['T_ID'], $row['T_ID'], $row['T_Name']);
                  print($html);
              }
          }
      ?>
      </SELECT>
      <?php if(!empty($error['team']) && $error['team'] == 'blank'): ?>
        <p><font color="red">* 所属チームを選択してください</font></p>
      <?php endif; ?>
    </dd>
  </dl>
  <div><input type="submit" value="登録"></div>
  </form>
<br />
<a href="#" onclick="javascript:window.history.back(-1);return false;"><=戻る</a>

</body>
</HTML>
