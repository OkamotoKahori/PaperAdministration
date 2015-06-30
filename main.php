<?php

// // 画像のアップロード処理
// // アップロードが正常に行われたかチェック
// if ( $_FILES["upfile"]["error"] == UPLOAD_ERR_OK )
// {
//     // アップロード先とファイル名を付与
//     $upload_file = "./PaperFiles/" . $_FILES["upfile"]["name"] ;
     
//     // アップロードしたファイルを指定のパスへ移動
//     if ( move_uploaded_file( $_FILES["upfile"]["tmp_name"], $upload_file ) )
//     {
//         // パーミッションを変更
//         // Read and write for owner, read for everybody
//         chmod($upload_file, 0644);
//     } 
// } 

// if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
//   if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "./PaperFiles/" . $_FILES["upfile"]["name"])) {
//     chmod("./PaperFiles/" . $_FILES["upfile"]["name"], 0644);
//     echo $_FILES["upfile"]["name"] . "をアップロードしました。";
//   } else {
//     echo "ファイルをアップロードできません。";
//   }
// } else {
//   echo "ファイルが選択されていません。";
// }


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
echo "以下の論文をアップロードしました。<br/> 
論文タイトル：{$title} <br/> 
著者：{$author} <br/> 
学会名：{$society}, {$select} <br/>
発表年：{$year} <br/>
キーワード：{$keyword} <br/>
分野：{$category}";
fwrite($fp, $title.",".$author.",".$society.",".$select.",".$year.",".$keyword.",".$category);
fwrite($fp,"\n");
fclose($fp);

?>