<?php
	// 正規ルートでページに遷移したか確認
	session_start();
	echo session_name(),'=',session_id(),"<br />\n";
	// ログイン状態のチェック
	if (isset($_SESSION["USERID"])) {
	  header("Location: upload.html");
	  exit;
	}else{
		echo '<script type = "text/javascript">';
    	echo "<!--\n";
    	echo 'alert("パスワード認証を行ってください");' ."\n";
   		// メッセージボックスでOKを押したら入力フォームへ戻る
    	echo 'location.href = "password.html"';
    	echo '// -->';
    	echo '</script>';
	}
