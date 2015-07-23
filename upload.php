<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" constent="text/html; charset=UTF-8" />
    <title>論文のアップロード</title>
</head>

<body>
    <h1>論文のアップロード</h1>

	<?php
		// デバッグ用
		// ini_set("display_errors", On);
		// error_reporting(E_ALL);
		// 正規ルートでページに遷移したか確認
		session_start();

		// ログイン状態のチェック
		if (!isset($_SESSION["USERID"])) {
			echo '<script type = "text/javascript">';
	    	echo "<!--\n";
	    	echo 'alert("パスワードを認証してください");' ."\n";
	   		// メッセージボックスでOKを押したら入力フォームへ戻る
	    	echo 'location.href = "password.html"';
	    	echo '// -->';
	    	echo '</script>';
		    session_destroy();//セッション破棄
		  exit;
		}
		
		// ファイルのアップロード
		// ファイル名の取り出し
		$localFilename = $_FILES['upfile']['name'];
		//一時ファイル名の取り出し 
		$temp = $_FILES['upfile']['tmp_name'];
		// 保存先のディレクトリ
		$dir = 'PaperFiles/';


		if (move_uploaded_file($temp, $dir . $localFilename)) {
	 		echo '<p>アップロードされたファイルです:' . h($localFilename) . '</p>';
		}else{
			echo '<p>アップロードされていないファイルです:' . h($localFilename) . '</p>';
		}

		function h($string){
			return htmlspecialchars($string, ENT_QUOTES);
		}

		// 入力した項目を保存する
		$filename = "database.txt";
		chmod($filename, 0766);
		$fp = fopen($filename, "a");

		// txtに書き込む項目
		$genre = htmlspecialchars($_POST['genre']);
		$title = htmlspecialchars($_POST['title']);
		$author = htmlspecialchars($_POST['author']);
		$journal = htmlspecialchars($_POST['journal']);
		$select = htmlspecialchars($_POST['select']);
		$degree = htmlspecialchars($_POST['degree']);
		$volume = htmlspecialchars($_POST['volume']);
		$number = htmlspecialchars($_POST['number']);
		$page = htmlspecialchars($_POST['page']);
		$year = htmlspecialchars($_POST['year']);
		$keyword = htmlspecialchars($_POST['keyword']);
		$category = htmlspecialchars($_POST['category']);
		$upfile = htmlspecialchars($_POST['upfile']);

		// たぶんこの辺条件分岐
		// 編集する必要あり

		if ($genre == 0) {
		// 学会発表の場合
			echo "以下の論文をアップロードしました。<br/> 
			論文タイトル：{$title} <br/> 
			著者：{$author} <br/> 
			学会名：{$journal}, {$select} <br/>
			Volume：{$volume} <br/>
			Number：{$number} <br/>
			page：{$page} <br/>
			発表年：{$year} <br/>
			キーワード：{$keyword} <br/>
			分野：{$category} <br/>
			ファイル名：{$localFilename}";
			fwrite($fp, $genre.",".$title.",".$author.",".$journal.",".$select.",".$volume.",".$number.",".$page.",".$year.",".$keyword.",".$category.",".$localFilename."\n");
		}elseif ($genre == 1) {
		// 論文誌の場合
			echo "以下の論文をアップロードしました。<br/> 
			論文タイトル：{$title} <br/> 
			著者：{$author} <br/> 
			論文誌：{$journal}, {$select} <br/>
			Volume：{$volume} <br/>
			Number：{$number} <br/>
			page：{$page} <br/>
			発表年：{$year} <br/>
			キーワード：{$keyword} <br/>
			分野：{$category} <br/>
			ファイル名：{$localFilename}";
			fwrite($fp, $genre.",".$title.",".$author.",".$journal.",".$select.",".$volume.",".$number.",".$page.",".$year.",".$keyword.",".$category.",".$localFilename."\n");
		}else{
		// 学位論文の場合
			echo "以下の論文をアップロードしました。<br/> 
			論文タイトル：{$title} <br/> 
			著者：{$author} <br/> 
			学位：{$degree} <br/>
			卒業年度：{$year} 年度<br/>
			キーワード：{$keyword} <br/>
			分野：{$category} <br/>
			ファイル名：{$localFilename}";
			// 編集する必要あり
			fwrite($fp, $genre.",".$title.",".$author.",".$degree.",".$year.",".$keyword.",".$category.",".$localFilename."\n");
		}

		fclose($fp);

		// What's New 用ファイルに設定した件数以上書き込まれていたら古い書き込みを削除する
		$file   = file('WhatsNew.txt');
		$fileCount = count($file);
		$settingNum = 4;	//What's New!に表示したい件数 - 1 の数字を指定
		if ($fileCount > $settingNum){
			unset($file[0]);
			file_put_contents('WhatsNew.txt', $file);
		};

		//What's New 用ファイルに新しく追加した論文のタイトルと時刻を書き込む
		$WNfilename = "WhatsNew.txt";
		chmod($WNfilename, 0766);
		$WNfp = fopen($WNfilename, "a");
		fwrite($WNfp, date('Y年m月d日')."\t".$title."\n");
		fclose($WNfp);
	?>

</body>

</html>