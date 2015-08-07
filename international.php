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
$paperArray = MakePaperArray();
//$paperArrayから表示する論文だけを取り出す

//ここから下は表示するページごとに変更する//
foreach ($paperArray as $paper) {
    if($paper['genre'] == 0 && $paper['location'] == 'Japan'){
        $dataArray[] = $paper;
    }
}
//ソートしたいカテゴリ（key）を指定
$categoryKey = 'journal';
//ここから上は表示するページごとに変更する//

//第２引数で指定したkeyごとにソート(このkeyがコンテンツの見出しになる)
$dataSortArray = CategorySort($dataArray,$categoryKey);
//見出しごとの論文の数を数える
$dataCountArray = CategoryCount($dataSortArray,$categoryKey);
//見出しを配列に入れる
$dataKeyArray = array_keys($dataCountArray);
//見出しがいくつあるのかを数える（見出しが入っている配列のデータの個数を調べる）
$dataNum = count($dataKeyArray);
//smoothplayするための左の黒い部分の記述
$countEnd = 0;
for($count = 0; $count < $dataNum; $count++){
    $midashi = $dataKeyArray[$count];
    $smoothNum = $count+1;
    echo ' <li><a href="#smoothplay'.$smoothNum.'">'.$midashi.'</a></li>';
}
//htmlタグの表示
echo '<li><a href="yearsort.php">年代順</a></li>
    <li><a href="password.html">論文のアップロード</a></li>
    </ul>
</div>
<!-- /#sidebar-wrapper -->
<!-- Page Content -->
<div id="page-content-wrapper">
<div class="container-fluid">';
//右の白い部分（メインの部分）に論文を学会ごとに表示する
$countEnd = 0;
for($count = 0; $count < $dataNum; $count++){
    $midashi = $dataKeyArray[$count];
    $smoothNum = $count+1;
    echo ' <h1><div id="smoothplay'.$smoothNum.'">'.$midashi.'</div></h1>';
    for ($count2 = $countEnd; $count2 < $countEnd+$dataCountArray[$midashi]; $count2++) {
        //result($dataSortArray[$count2]);
        //見出し１つ分の論文だけが入っている配列を作る
        $yearSortArray[] = $dataSortArray[$count2];
    }
    //見出し１つ分の論文だけが入っている配列を年代順にソート
    $yearSortArray = CategorySort($yearSortArray,'year');
    //見出し１つ分の論文だけが入っている配列を年代順にソートした配列を年代順に表示
    foreach ($yearSortArray as $paper) {
        Result($paper);
    }
    $yearSortArray = array();
    $countEnd = $dataCountArray[$midashi];
}
//database.txtの内容を検索できる形式に変換する関数
function MakePaperArray(){
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
function CategorySort($dataArray,$category){
    //echo "<br />CategorySort";
    foreach ($dataArray as $key => $value) {
        $categoryKey[$key] = $value[$category];
    }
    array_multisort($categoryKey ,SORT_DESC, $dataArray);
    //var_dump($dataArray);
    return($dataArray);
}
//見出し以下の個数をカウント
function CategoryCount($paperArray,$category){
    //echo "<br />CategoryCount";
    foreach ($paperArray as $key => $value) {
        $categoryKey[$key] = $value[$category];
    }
    $dataCountArray = array_count_values($categoryKey);
    //var_dump($dataCount);
    return($dataCountArray);
}
//検索結果を表示する関数
function Result($paper){
    //echo "<br />result";
    //発表場所，発表形式，カテゴリを日本語表記に変換
    $paper = Transform($paper);
    //ジャンルに問わず表示する内容（前半）
    echo '<div class="paper-fluid">
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
