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
?>    

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" constent="text/html; charset=UTF-8" />
    <title>論文のアップロード</title>
    <script type="text/javascript" src="lib/jquery-2.1.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="main.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <form action="logout.php" method="POST">
    <input type="submit" value="戻る">
    </form>
    <h1>論文のアップロード</h1>
    <form action="uploaded.php" method="post" enctype="multipart/form-data" accept-charset="utf-8">
        <button type="button" class="btn btn-lg" id="Conference">学会発表</button>
        <button type="button" class="btn btn-lg" id="Journal">論文誌</button>
        <button type="button" class="btn btn-lg" id="Thesis">学位論文</button>
        <div id="main"></div>
        <!-- アップロード論文の種類の選択 -->
        <script type="text/javascript">
        // 学会発表を選択した時
        $('#Conference').on('click', function() {
            $('#main').empty();
            $('#main').append(
                '<input type="hidden" name="genre" value="0">'+
                '<p>論文タイトル</p>' +
                '<input type="text" name="title">' +
                '<p>著者名</p>' +
                '<input type="text" name="author">' +
                '<p>学会名</p>' +
                '<input type="text" name="journal">' +
                '<!-- 国内か国外を選択できるボタン -->' +
                '<input type="radio" name="location" value="Japan" checked>国内' +
                '<input type="radio" name="location" value="Outside">国外' +
                '<p>発表形式</p>' +
                '<input type="radio" name="form" value="0" checked>口頭発表' +
                '<input type="radio" name="form" value="1">ポスター発表' +
                '<input type="radio" name="form" value="2">口頭発表＋ポスター発表' +
                '<p>Vol.</p>' +
                '<input type="text" name="volume">' +
                '<p>No.</p>' +
                '<input type="text" name="number">' +
                '<p>pages</p>' +
                '<input type="text" name="pages_s"> 〜 <input type="text" name="pages_e">' +
                '<p>発表年月</p>' +
                '<input type="text" name="year">年<input type="text" name="month">月' +
                '<p>キーワード</p>' +
                '<input type="text" name="keyword">' +
                '<p>分野</p>' +
                '<select name="category">' +
                '    <option value="Real">実世界</option>' +
                '    <option value="Communication">コミュニケーション</option>' +
                '    <option value="Gimmick">仕掛け学</option>' +
                '    <option value="InformationCompiled">情報編纂</option>' +
                '    <option value="Comic">コミック工学</option>' +
                '    <option value="Onomatopoeia">オノマトペ</option>' +
                '</select>' +
                '<p>論文ファイル</p>' +
                '<input type="file" name="upfile" value="">' +
                '<p>' +
                '<input type="submit" value="送信">' +
                '</p>');

        });
        //論文誌を選択した時 
        $('#Journal').on('click', function() {
            $('#main').empty();
            $('#main').append(
                '<input type="hidden" name="genre" value="1">'+
                '<p>論文タイトル</p>' +
                '<input type="text" name="title">' +
                '<p>著者名</p>' +
                '<input type="text" name="author">' +
                '<p>論文誌名</p>' +
                '<input type="text" name="journal">' +
                '<!-- 国内か国外を選択できるボタン -->' +
                '<input type="radio" name="location" value="Japan" checked>国内' +
                '<input type="radio" name="location" value="Outside">国外' +
                '<p>Vol.</p>' +
                '<input type="text" name="volume">' +
                '<p>No.</p>' +
                '<input type="text" name="number">' +
                '<p>page</p>' +
                '<input type="text" name="pages_s"> 〜 <input type="text" name="pages_e">' +
                '<p>発行年月</p>' +
                '<input type="text" name="year">年<input type="text" name="month">月' +                '<p>キーワード</p>' +
                '<input type="text" name="keyword">' +
                '<p>分野</p>' +
                '<select name="category">' +
                '    <option value="Real">実世界</option>' +
                '    <option value="Communication">コミュニケーション</option>' +
                '    <option value="Gimmick">仕掛け学</option>' +
                '    <option value="InformationCompiled">情報編纂</option>' +
                '    <option value="Comic">コミック工学</option>' +
                '    <option value="Onomatopoeia">オノマトペ</option>' +
                '</select>' +
                '<p>論文ファイル</p>' +
                '<input type="file" name="upfile" value="">' +
                '<p>' +
                '<input type="submit" value="送信">' +
                '</p>');

        });
        //学位論文を選択した時    
        $('#Thesis').on('click', function() {
            $('#main').empty();
            $('#main').append(
                '<input type="hidden" name="genre" value="2">'+
                '<p>論文タイトル</p>' +
                '<input type="text" name="title">' +
                '<p>著者名</p>' +
                '<input type="text" name="author">' +
                '<p>学位の選択</p>' +
                '<input type="radio" name="degree" value="Bachelor" checked>学士' +
                '<input type="radio" name="degree" value="Master">修士' +
                '<input type="radio" name="degree" value="PhD">博士' +
                '<p>卒業年度</p>' +
                '<p>西暦<input type="text" name="year">年度</p>' +
                '<p>キーワード</p>' +
                '<input type="text" name="keyword">' +
                '<p>分野</p>' +
                '<select name="category">' +
                '    <option value="Real">実世界</option>' +
                '    <option value="Communication">コミュニケーション</option>' +
                '    <option value="Gimmick">仕掛け学</option>' +
                '    <option value="InformationCompiled">情報編纂</option>' +
                '   <option value="Comic">コミック工学</option>' +
                '    <option value="Onomatopoeia">オノマトペ</option>' +
                '</select>' +
                '<p>論文ファイル</p>' +
                '<input type="file" name="upfile" value="">' +
                '<p>' +
                '    <input type="submit" value="送信"></p>');

        });
        </script>
    </form>
</body>

</html>
