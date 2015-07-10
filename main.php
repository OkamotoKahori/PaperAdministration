<?php
// ファイルのアップロード
// if(isset($_FILES['upfile'])){
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
// }



function h($string){
	return htmlspecialchars($string, ENT_QUOTES);
}

// 入力した項目を保存する
$filename = "database.txt";
chmod($filename, 0766);
$fp = fopen($filename, "a");
// txtに書き込む項目
$title = htmlspecialchars($_POST['title']);
$author = htmlspecialchars($_POST['author']);
$society = htmlspecialchars($_POST['society']);
$select = htmlspecialchars($_POST['select']);
$year = htmlspecialchars($_POST['year']);
$keyword = htmlspecialchars($_POST['keyword']);
$category = htmlspecialchars($_POST['category']);
$upfile = htmlspecialchars($_POST['upfile']);

echo "以下の論文をアップロードしました。<br/> 
論文タイトル：{$title} <br/> 
著者：{$author} <br/> 
学会名：{$society}, {$select} <br/>
発表年：{$year} <br/>
キーワード：{$keyword} <br/>
分野：{$category} <br/>
ファイル名：{$localFilename}";
fwrite($fp, $title.",".$author.",".$society.",".$select.",".$year.",".$keyword.",".$category.",".$localFilename."\n");
fclose($fp);

?>