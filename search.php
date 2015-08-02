<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" constent="text/html; charset=UTF-8" />
    <script type="text/javascript" src="lib/jquery-2.1.1.min.js"></script>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link href="css/search.css" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>PHP入門</title>
</head>

<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="/PaperAdministration/">
                        論文管理システム
                    </a>
                <li>
                    <a href="all_paper.html">すべて</a>
                </li>
                <div class="space"></div>
                <form action="search.php" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                    <p>
                        <font color="white">絞り込み検索</font>
                    </p>
                    <input type="text" name="query" size="20">
                    <p>
                        <input type="radio" name="refine" value="s_author" checked>
                        <font color="white">著者</font>
                    </p>
                    <p>
                        <input type="radio" name="refine" value="s_keyword">
                        <font color="white">キーワード</font>
                    </p>
                    <p>
                        <font color="white">分野の選択</font>
                    </p>
                    <select name="category">
                        <option value="all">すべて</option>
                        <option value="Real">実世界</option>
                        <option value="Communication">コミュニケーション</option>
                        <option value="Gimmick">仕掛け学</option>
                        <option value="InformationCompiled">情報編纂</option>
                        <option value="Comic">コミック工学</option>
                        <option value="Onomatopoeia">オノマトペ</option>
                    </select>
                    <input type="submit" name="r_submit" value="検索">
                </form>
                <li>
                    <a href="index.html">Topへ戻る</a>
                </li>
                <div class="space"></div>
                <li>
                    <a href="password.html">論文のアップロード</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
        <!-- ここに検索結果が表示されます -->
<?php
//echo "phpの読み込みはできています"."<br />";
$filename = "database.txt";
$data = file_get_contents($filename);
//入力された情報を取得
$query_value = htmlspecialchars($_POST['query']);
//echo $query_value;
//if(empty($query_value)){echo "empty";}else{echo "Hello";}
$refine = htmlspecialchars($_POST['refine']);
$query_category = htmlspecialchars($_POST['category']);
//htmlから渡された値をphpで使えるように変更
if($refine == 's_author'){
    $query_key = 'author';
}elseif($refine == 's_keyword'){
    $query_key = 'keyword';
}else{
    $query_key = 'other';
}
//databaseの内容を改行で区切る
$dataSet = explode("\n", $data);
//database.texに含まれるデータの個数を数える
$dataCount = substr_count($data, "array");
//一人前ずつのデータを取り出します
foreach ($dataSet as $data) {
    //文字列をphpのソースとして読めるようにしています
    eval('$paper = '.$data.';');
    //テキストボックスに入力されているかどうかを判断
    if(empty($query_value)){
        //入力されていなければ，速攻で検索結果を表示
        //どのカテゴリが選択されているかを判断
        if($query_category == "all"){
            result($paper);
        }elseif($paper['category'] == $query_category){
            result($paper);
        }else{
            $not = notPaper($not,$dataCount);
        }
    }else{
        if($query_category == "all"){
            $not = search($paper,$query_key,$query_value,$not,$dataCount);
        }elseif($paper['category'] == $query_category){
            $not = search($paper,$query_key,$query_value,$not,$dataCount);
        }else{
            $not = notPaper($not,$dataCount);
        }
    }
}
//入力された文字列でデータベース内を検索
function search($paper,$query_key,$query_value,$not,$dataCount){
    if(strpos($paper[$query_key], $query_value) === FALSE){
        $not = notPaper($not,$dataCount);
        return($not);
    }else{
        //検索結果を表示する
        result($paper);
    }
}
//データベース内に論文があるかをチェック
function notPaper($not,$dataCount){
    //データベース内に論文があるかをチェック
    $not++;
    if($not >= $dataCount){
        echo '<div class="container-fluid">
            <h1>お探しの論文はみつかりませんでした</h1>
            </div>';
    }
    return($not);
}
//検索結果を表示するための関数
function result($paper){
    //発表形態と研究分野の表示を変更
    $location = locationTransform($paper['location']);
    $form = formTransform($paper['form']);
    $category = categoryTransform($paper['category']);
    //ジャンルに問わず表示する内容（前半）
    echo '<div class="container-fluid">
        <p>論文タイトル：</p>
        <h3>'.$paper['title'].'</h3>
        <p>著者名：'.$paper['author'].'</p>';
    if($paper['genre']==2){
        //修論・卒論の表示
        echo '<p>発表年：'.$paper['year'].'</p>';
    }else{
        //学会発表・国際会議の表示
        if($paper['genre']==0){
            echo '<p>学会名：'.$paper['journal'].'</p>';
        }else{
            echo '<p>論文誌名：'.$paper['journal'].'</p>';
        }
        echo '<p>場所：'.$location.'</p>
            <p>発表形態：'.$form.'</p>
            <p>学位：'.$paper['degree'].'</p>
            <p>Vol.：'.$paper['volume'].'</p>
            <p>No.：'.$paper['number'].'</p>
            <p>pp.：'.$paper['pages_s']."-".$paper['pages_e'].'</p>
            <p>発表年：'.$paper['year'].'</p>
            <p>発表月：'.$paper['month'].'</p>';
    }
    //ジャンルに問わず表示する内容（後半）
    echo '<p>キーワード：'.$paper['keyword'].'</p>
        <p>研究分野：'.$category.'</p>
        <p>PDFファイル：'.$paper['filename'].'</p>
        </div>';
}
//発表場所を日本語表記に変換する
function locationTransform($location){
    if($location == "Japan"){
        $location = "国内";
    }else{
        $location = "国外";
    }
    return($location);
}
//発表形式を日本語表記に変換する
function formTransform($form){
    if($form == 0){
        $form = "口頭発表";
    }elseif($form == 1){
        $form = "ポスター発表";
    }else{
        $form = "口頭発表＋ポスター発表";
    }
    return($form);
}
//カテゴリを日本語表記に変換する関数
function categoryTransform($category){
    if($category == "Real"){
        $category = "実世界";
    }elseif($category == "Communication"){
        $category = "コミュニケーション";
    }elseif($category == "Gimmick"){
        $category = "仕掛学";
    }elseif($category == "InformationCompiled"){
        $category = "情報編纂";
    }elseif($category == "Comic"){
        $category = "コミック工学";
    }elseif($category == "Onomatopoeia"){
        $category = "オノマトペ";
    }else{
        $category = "all";
    }
    return($category);
}
?>
        <!-- ここに検索結果が表示されます -->
        </div>
    </div>
    <!-- /#page-content-wrapper -->
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>
</body>
</html>