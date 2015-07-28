<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" constent="text/html; charset=UTF-8" />
    <title>論文のアップロード</title>
</head>

<body>
    <h1>論文のアップロード</h1>
    <form action="logout.php" method="POST"><input type="submit" value="ホーム"></form>
    <a href="upload.html"><input type="button" value="続けてアップロード"></a>
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
		  exit;
		}
		
		// ファイルのアップロード
		// ファイル名の取り出し
		$localFilename = $_FILES['upfile']['name'];
		//一時ファイル名の取り出し 
		$temp = $_FILES['upfile']['tmp_name'];
		// 保存先のディレクトリ
		$dir = 'PaperFiles/';

		// pdfファイルがアップロードされたか判定
		if (move_uploaded_file($temp, $dir . $localFilename)) {
	 		echo '<p>アップロード完了:' . h($localFilename) . '</p>';
		}else{
			echo '<p>アップロード失敗:' . h($localFilename) . '</p>';
		}

		function h($string){
			return htmlspecialchars($string, ENT_QUOTES);
		}

		// bibtex用IDの取得
		$bibtexID = date("YmdHis");

		// txtに書き込む項目の変数準備
		$genre = htmlspecialchars($_POST['genre']);	//学会発表(0)，論文誌(1)，学位論文(2)の分類
		$title = htmlspecialchars($_POST['title']); //論文のタイトル
		$author = htmlspecialchars($_POST['author']); //著者名
		$journal = htmlspecialchars($_POST['journal']); //学会発表：学会名，論文誌：論文誌名
		$location = htmlspecialchars($_POST['location']); //国内(Japan)か海外(Outside)か
		$form = htmlspecialchars($_POST['form']); //発表形態
		$degree = htmlspecialchars($_POST['degree']); //学位
		$volume = htmlspecialchars($_POST['volume']); //volume
		$number = htmlspecialchars($_POST['number']); //number
		$pages_s = htmlspecialchars($_POST['pages_s']); //ページ始まり
		$pages_e = htmlspecialchars($_POST['pages_e']); //ページ終わり
		$year = htmlspecialchars($_POST['year']); //年
		$month = htmlspecialchars($_POST['month']); //月
		$keyword = htmlspecialchars($_POST['keyword']); //論文を示すキーワード
		$category = htmlspecialchars($_POST['category']); //研究分野カテゴリ
		$upfile = htmlspecialchars($_POST['upfile']); //アップロードしたpdf名

		// 入力した項目をデータベースに保存する
		$filename = "database.txt";
		chmod($filename, 0766);
		$fp = fopen($filename, "a");

		if ($genre == 0) {
		// 学会発表の場合
			echo "以下の論文をアップロードしました。<br/> 
			論文タイトル：{$title} <br/> 
			著者：{$author} <br/> 
			学会名：{$journal}, {$location} <br/>
			発表形態：{$form} <br/>
			Volume：{$volume} <br/>
			Number：{$number} <br/>
			pages：{$pages_s} - {$pages_e} <br/>
			発表年月：{$year} 年 {$month} 月 <br/>
			キーワード：{$keyword} <br/>
			分野：{$category} <br/>
			ファイル名：{$localFilename}";
			fwrite($fp, "$"."dataArray[] = array(\n"."'genre'"."=>"."'".$genre."',\n"."'bibtexID'"."=>"."'".$bibtexID."',\n"."'title'"."=>"."'".$title."',\n"."'author'"."=>"."'".$author."',\n".	"'journal'"."=>"."'".$journal."',\n"."'location'"."=>"."'".$location."',\n"."'form'"."=>"."'".$form."',\n"."'volume'"."=>"."'".$volume."',\n"."'number'"."=>"."'".$number."',\n"."'pages_s'"."=>"."'".$pages_s."',\n"."'pages_e'"."=>"."'".$pages_e."',\n"."'year'"."=>"."'".$year."',\n"."'month'"."=>"."'".$month."',\n"."'keyword'"."=>"."'".$keyword."',\n"."'category'"."=>"."'".$category."',\n"."'filename'"."=>"."'".$localFilename."');\n");
		}elseif ($genre == 1) {
		// 論文誌の場合
			echo "以下の論文をアップロードしました。<br/> 
			論文タイトル：{$title} <br/> 
			著者：{$author} <br/> 
			論文誌：{$journal}, {$location} <br/>
			Volume：{$volume} <br/>
			Number：{$number} <br/>
			pages：{$pages_s} - {$pages_e} <br/>
			発行年月：{$year} 年 {$month} 月 <br/>
			キーワード：{$keyword} <br/>
			分野：{$category} <br/>
			ファイル名：{$localFilename}";
			fwrite($fp, "$"."dataArray[] = array(\n"."'genre'"."=>"."'".$genre."',\n"."'bibtexID'"."=>"."'".$bibtexID."',\n"."'title'"."=>"."'".$title."',\n"."'author'"."=>"."'".$author."',\n".	"'journal'"."=>"."'".$journal."',\n"."'location'"."=>"."'".$location."',\n"."'volume'"."=>"."'".$volume."',\n"."'number'"."=>"."'".$number."',\n"."'pages_s'"."=>"."'".$pages_s."',\n"."'pages_s'"."=>"."'".$pages_s."',\n"."'year'"."=>"."'".$year."',\n"."'month'"."=>"."'".$month."',\n"."'keyword'"."=>"."'".$keyword."',\n"."'category'"."=>"."'".$category."',\n"."'filename'"."=>"."'".$localFilename."');\n");
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
			fwrite($fp, "$"."dataArray[] = array(\n"."'genre'"."=>"."'".$genre."',\n"."'bibtexID'"."=>"."'".$bibtexID."',\n"."'title'"."=>"."'".$title."',\n"."'author'"."=>"."'".$author."',\n".	"'degree'"."=>"."'".$degree."',\n"."'year'"."=>"."'".$year."',\n"."'keyword'"."=>"."'".$keyword."',\n"."'category'"."=>"."'".$category."',\n"."'filename'"."=>"."'".$localFilename."');\n");
		}

		fclose($fp);

		// What's New 用ファイルに設定した件数以上書き込まれていたら古い書き込みを削除する
		$WNfilename ="WhatsNew.txt";
		chmod($WNfilename, 0766);
		$file   = file($WNfilename); // ファイルの中身を一行ずつ配列に格納
		$fileCount = count($file);
		$settingNum = 9;	//What's New!に表示したい件数 - 1 の数字を指定
		if ($fileCount > $settingNum){
			unset($file[0]);
			file_put_contents($WNfilename, $file);
		};
		//What's New 用ファイルに新しく追加した論文のタイトルと日時を書き込む
		$WNfp = fopen($WNfilename, "a");
		fwrite($WNfp, date('Y年m月d日')."\t".$title."\n");
		fclose($WNfp);
	?>

</body>

</html>