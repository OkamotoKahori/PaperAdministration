<?php
	// パスワードの認証
	session_start();
	// セッション変数を全て解除する
	$_SESSION = array();
	// エラーメッセージを格納する変数を初期化
	$error_message = "";

	// ログインボタンが押されたかを判定
	// 初めてのアクセスでは認証は行わずエラーメッセージは表示しないように
	if (isset($_POST["login"])) {

		// IDとパスワードを設定する場合は""内を変更
		if ($_POST["userID"] == "test" && $_POST["password"] == "test") {

			// ログインが成功した証をセッションに保存
			$_SESSION["USERID"] = $_POST["userID"];

			// 管理者専用画面へリダイレクト
			// http://{$_SERVER["HTTP_HOST"]}/PaperAdministration/upload.php
			$login_url = "upload.html";
			header("Location: {$login_url}");
			exit;
		}
		echo '<script type = "text/javascript">';
    	echo "<!--\n";
    	echo 'alert("ユーザIDかパスワードが間違っています");' ."\n";
   		// メッセージボックスでOKを押したら入力フォームへ戻る
    	echo 'location.href = "password.html"';
    	echo '// -->';
    	echo '</script>';
    }

?>