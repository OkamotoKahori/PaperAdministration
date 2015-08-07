<?php

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" constent="text/html; charset=UTF-8" />
    <script type="text/javascript" src="lib/jquery-2.1.1.min.js"></script>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/simple-sidebar.css" rel="stylesheet">
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
                </li>
<?php
//database.txtの内容を検索できる形式に変換
$paperArray = makePaperArray();
//分類したいカテゴリを指定
$class1 = 'location';
$judge1 = 'Japan';
$class2 = 'genre';
$judge2 = 2;
//表示する論文だけを取り出す
foreach ($paperArray as $paper) {
    if($paper[$class2] == $judge2){
        $nationalArray[] = $paper;
    }
}
//年代順にソート
$sortArray = categorySort($nationalArray,'year');
//学会ごとの論文の数を数える
$dataCount = categoryCount($sortArray,'year');
//学会名を配列に入れる
$dataKey = array_keys($dataCount);
//学会名の配列にいくつデータがあるのかを調べる
$dataNum = count($dataKey);
//smoothplayするための左の黒い部分の記述
$countEnd = 0;
for($count = 0; $count < $dataNum; $count++){
    $key = $dataKey[$count];
    $smoothNum = $count+1;
    echo ' <li><a href="#smoothplay'.$smoothNum.'">'.$key.'</a></li>';
}
//htmlタグの表示
echo '<li><a href="password.html">論文のアップロード</a></li>
    </ul>
</div>
<!-- /#sidebar-wrapper -->
<!-- Page Content -->
<div id="page-content-wrapper">
    <div class="container-fluid">';
//左の白い部分（メインの部分）に論文を学会ごとに表示する
$countEnd = 0;
for($count = 0; $count < $dataNum; $count++){
    $key = $dataKey[$count];
    $smoothNum = $count+1;
    echo ' <h1><div id="smoothplay'.$smoothNum.'">'.$key.'</div></h1>';
    for ($count2 = $countEnd; $count2 < $countEnd+$dataCount[$key]; $count2++) {
        $key2 = $dataCount[$key];
        result($sortArray[$count2]);
    }
    $countEnd = $dataCount[$key];
}
//database.txtの内容を検索できる形式に変換する関数
function makePaperArray(){
    //echo "<br />makeData";
    //database.txtの内容を$databaseに格納
    $database = file_get_contents("database.txt");
    //database.txtの内容を改行で区切る
    $dataArray = explode("\n", $database);
    //一人前ずつのデータを取り出します
    foreach ($dataArray as $data) {
        //文字列をphpのソースとして読めるようにする
        eval('$paperArray[] = '.$data.';');
    }
    return($paperArray);
}
//降順にソート
function categorySort($paperArray,$category){
    //echo "<br />categorySort";
    foreach ($paperArray as $key => $value) {
        $categoryKey[$key] = $value[$category];
    }
    array_multisort($categoryKey ,SORT_DESC, $paperArray);
    //var_dump($paperArray);
    return($paperArray);
}
//見出し以下の個数をカウント
function categoryCount($paperArray,$category){
    //echo "<br />categoryCount";
    foreach ($paperArray as $key => $value) {
        $categoryKey[$key] = $value[$category];
    }
    $dataCount = array_count_values($categoryKey);
    //var_dump($dataCount);
    return($dataCount);
}
//検索結果を表示する関数
function result($paper){
    //echo "<br />result";
    //発表場所，発表形式，カテゴリを日本語表記に変換
    $paper = Transform($paper);
    //ジャンルに問わず表示する内容（前半）
    echo '<div class="container-fluid">
        <p>論文タイトル：</p>
        <h3>'.$paper['title'].'</h3>
        <p>著者名：'.$paper['author'].'</p>
        <p>学位：'.$paper['degree'].'</p>';
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
        echo '<p>場所：'.$paper['location'].'</p>
            <p>発表形態：'.$paper['form'].'</p>
            <p>学位：'.$paper['degree'].'</p>
            <p>Vol.：'.$paper['volume'].'</p>
            <p>No.：'.$paper['number'].'</p>
            <p>pp.：'.$paper['pages_s']."-".$paper['pages_e'].'</p>
            <p>発表年：'.$paper['year'].'</p>
            <p>発表月：'.$paper['month'].'</p>';
    }
    //ジャンルに問わず表示する内容（後半）
    echo '<p>キーワード：'.$paper['keyword'].'</p>
        <p>研究分野：'.$paper['category'].'</p>
        <p>PDFファイル：'.$paper['filename'].'</p>
        </div>';
}
//発表場所，発表形式，学位，カテゴリを日本語表記に変換する関数
function Transform($paper){
    //echo "<br />Transform";
    //学位を日本語表記に変換する
    if($paper['degree'] == "Bachelor"){
        $paper['degree'] = "学士";
    }elseif($paper['degree'] == "Master"){
        $paper['degree'] = "修士";
    }elseif($paper['degree'] == "Doctor"){
        $paper['degree'] = "博士";
    }else{
        $paper['degree'] = "---";
    }
    //発表場所を日本語表記に変換する
    if($paper['location'] == "Japan"){
        $paper['location'] = "国内";
    }else{
        $paper['location'] = "国外";
    }
    //発表形式を日本語表記に変換する
    if($paper['form'] == 0){
        $paper['form'] = "口頭発表";
    }elseif($paper['form'] == 1){
        $paper['form'] = "ポスター発表";
    }else{
        $paper['form'] = "口頭発表＋ポスター発表";
    }
    //カテゴリを日本語表記に変換する関数
    if($paper['category'] == "Real"){
        $paper['category'] = "実世界";
    }elseif($paper['category'] == "Communication"){
        $paper['category'] = "コミュニケーション";
    }elseif($paper['category'] == "Gimmick"){
        $paper['category'] = "仕掛学";
    }elseif($paper['category'] == "InformationCompiled"){
        $paper['category'] = "情報編纂";
    }elseif($paper['category'] == "Comic"){
        $paper['category'] = "コミック工学";
    }elseif($paper['category'] == "Onomatopoeia"){
        $paper['category'] = "オノマトペ";
    }else{
        $paper['category'] = "all";
    }
    return($paper);
}
?>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
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
