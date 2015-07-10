<?php
// パスワードの認証
$user = htmlspecialchars($_POST['userID'], ENT_QUOTES);
$pass = htmlspecialchars($_POST['password'], ENT_QUOTES);

	// 判定
	if (($user == 'test') && ($pass == 'test')){
		$login = 'OK';
	}else{
		$login = 'Error';
	}

	// 移動
	if ($login == 'OK'){
		header('Location: upload.html');
	}else{
		echo '<script type="text/javascript">';
    	echo "<!--\n";
    	echo 'alert("ユーザIDかパスワードが間違っています");' ."\n";
   		 // メッセージボックスでOKを押したら入力フォームへ戻る
    	echo 'location.href = "password.html"';
    	echo '// -->';
    	echo '</script>';
		}
?>