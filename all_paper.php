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
                <li>
                    <a href="#smoothplay1">国内発表</a>
                </li>
                <li>
                    <a href="#smoothplay2">国際会議</a>
                </li>
                <li>
                    <a href="#smoothplay3">論文誌</a>
                </li>
                <li>
                    <a href="#smoothplay4">学位論文</a>
                </li>
                <li>
                    <a href="#smoothplay5">受賞</a>
                </li>
                <li>
                    <a href="#smoothplay6">報道</a>
                </li>
                <li>
                    <a href="password.html">論文のアップロード</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
<?php
//database.txtの内容を検索できる形式に変換
$paperArray = makePaperArray();
//var_dump($paperArray);
//$dataArray = array();
foreach ($paperArray as $paper) {
    if($paper['genre'] == 0){
        if($paper['location'] == "Japan"){
            $paperJapan[] = $paper;
        }elseif($paper['location'] == "Outside"){
            $paperOutside[] = $paper;
        }
    }elseif($paper['genre'] == 1){
        $paper1[] = $paper;
    }elseif($paper['genre'] == 2){
        $paper2[] = $paper;
    }else{

    }
}
Contents($paperJapan,1,'国内発表');
Contents($paperOutside,2,'国際会議');
Contents($paper1,3,'論文誌');
Contents($paper2,4,'学位論文');
echo '<h1><div id="smoothplay5">受賞</div></h1>';
echo '<h1><div id="smoothplay6">報道</div></h1>';
//表示する
function Contents($paperArray,$num,$tag){
    echo '<h1><div id="smoothplay'.$num.'">'.$tag.'</div></h1>';
    foreach ($paperArray as $paper) {
        result($paper);
    }
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
//検索結果を表示する関数
function result($paper,$tag){
    //echo "<br />result";
    //発表場所，発表形式，カテゴリを日本語表記に変換
    $paper = Transform($paper);
    //ジャンルに問わず表示する内容（前半）
    echo '<p>論文タイトル：</p>
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
        <p>PDFファイル：'.$paper['filename'].'</p>';
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
