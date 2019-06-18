<?php
//DBに接続する
$db = mysqli_connect('localhost', 'portal', 'portal', 'portal') or die(mysqli_connect_error());
mysqli_set_charset($db, 'utf8');

//DB切断
function closeDB(){
    $close_flag = mysqli_close($link);
    
    if ($close_flag){
        print('<p>切断に成功しました。</p>');
    }
}

?>
</body>
