<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale1">
    <title>新規カレンダー追加 - カレンダー</title>
</head>

<body>
<?php

session_start();
require('../dbconnect.php');

//ログイン状態確認
if (!isset($_SESSION["user"])) {
  header("Location: ../login.php");
  exit;
}

$calName = '';

if(!empty($_POST)){
  //エラー項目の確認
  if($_POST['calName'] == ''){
    $error['calName'] = 'blank';
  }else $calName = $_POST['calName'];

  if($_POST['edit'] == ''){
    $error['edit'] = 'blank';
  }

  if($_POST['pass'] == ''){
    $error['pass'] = 'blank';
  }else{
    //パスワード一致
    $sql = sprintf('SELECT Pass FROM users WHERE ID = "%d"',(int)$_SESSION['user']);
    $result = mysqli_query($db,$sql);
    $pass = mysqli_fetch_assoc($result);
    if(sha1($_POST['pass']) != $pass['Pass']){
      $error['pass'] = 'cant';
    }
  }

  if(empty($error)){
    $_SESSION['cal'] = $_POST;
    header('Location: addcalcheck.php');
    exit();
  }
}

?>

<form action="" method="post" enctype="multipart/form-data">
  <dl>
    <dt>追加カレンダー<font color="red">　必須</font></dt>
    <dd>
      <input type="text" name="calName" size="20" maxlength="20"  value="<?php echo htmlspecialchars($calName, ENT_QUOTES, 'UTF-8'); ?>">
      <?php if(!empty($error['calName']) && $error['calName'] == 'blank'): ?>
        <p><font color="red">* カレンダー名を入力してください</font></p>
      <?php endif; ?>
    </dd>
    <dt>編集可
    <dd>
      <SELECT name="edit">
        <OPTION value="">▼選択してください</OPTION>
        <OPTION value="0">可能</OPTION>
        <OPTION value="1">不可能</OPTION>
      </SELECT>
      <?php if(!empty($error['edit']) && $error['edit'] == 'blank'): ?>
        <p><font color="red">* 選択してください</font></p>
      <?php endif; ?>
    </dd>
    <dt>Your Password<font color="red">　必須</font></dt>
    <dd>
      <input type="password" name="pass" size="10" maxlength="20">
      <?php if(!empty($error['pass']) && $error['pass'] == 'blank'): ?>
        <p><font color="red">* パスワードを入力してください</font></p>
      <?php endif; ?>
      <?php if(!empty($error['pass']) && $error['pass'] == 'cant'): ?>
        <p><font color="red">* パスワードが一致していません</font></p>
      <?php endif; ?>
    </dd>
  </dl>
  <div><input type="submit" value="登録"></div>
  </form>
<br />
<a href="#" onclick="javascript:window.history.back(-1);return false;"><=戻る</a>

</body>
</HTML>
