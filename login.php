<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale1">
    <title>ログイン - カレンダー</title>
</head>
<body>
<?php
require('dbconnect.php');
session_start();

// ログイン状態のチェック
if (!empty($_SESSION["user"])) {
  header("Location: index.php");
  exit;
}

if(!empty($_POST)){
  //ログインの処理
  if($_POST['id'] != '' && $_POST['pass'] != ''){
    $sql = sprintf('SELECT * FROM users WHERE id="%d" AND pass="%s"',
      (int)($_POST['id']),
      mysqli_real_escape_string($db, sha1($_POST['pass']))
    );
    $record = mysqli_query($db, $sql) or die(mysqli_error($db));
    if($table = mysqli_fetch_assoc($record)){
      $sql = sprintf('SELECT Name FROM users WHERE id="%d"',(int)($_POST['id']));
      $result = mysqli_query($db, $sql) or die(mysqli_error($db));
      $Name = mysqli_fetch_assoc($result);
      //ログイン成功
      session_regenerate_id(true);
      $_SESSION['user'] = $_POST['id'];
      $_SESSION['UserName'] = $Name['Name'];
      header('Location: index.php');
      exit();
    }else{
      $error['login'] = 'failed';
    }
  }else{
    $error['login'] = 'blank';
  }
}
?>
    <p>ログインしてください。</p>
    <form action="" method="post">
        <dl>
            <dt>ユーザーID</dt>
            <dd>
                <input type="text" name="id" size="35" maxlength="255">
                <?php if(!empty($error['login']) && $error['login'] == 'blank'): ?>
                    <p><font color="red">* ユーザーIDとパスワードをご記入ください</font></p>
                <?php endif; ?>
                <?php if(!empty($error['login']) && $error['login'] == 'failed'): ?>
                    <p><font color="red">* ログインに失敗しました。正しくご記入ください。</font></p>
                <?php endif; ?>
            </dd>
            <dt>パスワード</dt>
            <dd>
                <input type="password" name="pass" size="35" maxlength="255">
            </dd>
        </dl>
        <input type="submit" value="ログイン">
    </form>
  </body>

</HTML>
