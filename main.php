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
fwrite($fp, "abc");
fclose($fp);

?>