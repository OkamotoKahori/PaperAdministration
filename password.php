<?php
// パスワードの認証
$user = htmlspecialchars($_POST['userID'], ENT_QUOTES);
$pass = htmlspecialchars($_POST['password'], ENT_QUOTES);

// 判定
if (($user == 'hikimember') && ($pass == 'mitsunori2009')){
	$login = 'OK';
}else{
	$login = 'Error';
}

// 移動
if ($login == 'OK'){
	header('Location: upload.html');
}else{
	header('Location: password.html');
}
?>