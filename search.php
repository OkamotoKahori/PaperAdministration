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
                    <a href="all_paper.php">すべて</a>
                </li>
<?php
//database.txtの内容を検索できる形式に変換
$paperArray = MakePaperArray();
//ここから下は表示するページごとに変更する//
//入力された情報を取得
$query = htmlspecialchars($_POST['query']);
$refine = htmlspecialchars($_POST['refine']);
$category = htmlspecialchars($_POST['category']);
//database.texに含まれるデータの個数(arrayの数)を数える
$dataCount = count($paperArray);
//検索して，表示する論文だけを入れた配列を作り，ソート関数に渡す
//クエリが入力されているかどうかを判断
if(empty($query)){
    //クエリが入力されていない場合
    //カテゴリの指定があるかどうかを判断
    foreach ($paperArray as $paper) {
        if($category == "all"){
            //全ての論文を表示する
            //Result($paper);
            $dataArray[] = $paper;
        }elseif($category == $paper['category']){
            //指定されたカテゴリの論文を表示する
            //Result($paper);
            $dataArray[] = $paper;
        }else{
            //指定されたカテゴリの論文がなかった場合
            //$not = NotPaper($not,$dataCount);
            $not++;
        }
    }
}else{
    //クエリが入力されている場合
    //クエリの種類（ラジオボタン）がどれかを判断
    if($refine == 's_author'){
        //著者の場合
        $targetKey = 'author';
    }elseif($refine == 's_keyword'){
        //キーワードの場合
        $targetKey = 'keyword';
    }else{
        //すべての場合
        $targetKey = 'free';
    }
    //検索する
    foreach ($paperArray as $paper) {
        if($targetKey == "free"){
            //すべての情報を１つの文字列にする
            $targetStrings = $paper['title'].$paper['author'].$paper['year'].$paper['journal'].$paper['location'].$paper['form'].$paper['keyword'];
            //$not = StringSearch($targetStrings,$query,$paper,$not,$dataCount);
        }else{
            if($category == "all"){
                $targetStrings = $paper[$targetKey];
                //$not = StringSearch($targetStrings,$query,$paper,$not,$dataCount);
            }elseif($category == $paper['category']){
                $targetStrings = $paper[$targetKey];
                //$not = StringSearch($paper[$targetKey],$query,$paper,$not,$dataCount);
            }else{
                //$not = NotPaper($not,$dataCount);
                $not++;
            }
        }
        $paperResult = StringSearch($targetStrings,$query,$paper);
        if($paperResult == 'Paper'){
            $dataArray[] = $paper;
        }else{
            $not++;
        }
    }
}
/*
//ソートするための配列をつくる
$dataArraySet = array($Array0,$Array1,$Array2,$Array3);
for($i=0;$i<4;$i++){
    if(isset($dataArraySet[$i])){
        $dataArray = array_merge($dataArray,$dataArraySet[$i]);
    }
}
*/
//ソートしたいカテゴリ（key）を指定
$categoryKey = 'year';
//ここから上は表示するページごとに変更する//

//第２引数で指定したkeyごとにソート(このkeyがコンテンツの見出しになる)
$dataSortArray = CategorySort($dataArray,$categoryKey);
//$dataSortArray = $dataArray;
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
echo '<div class="space"></div>
                <form action="search.php" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                    <li>
                        <font color="white">絞り込み検索</font>
                    </li>
                    <input type="text" name="query" size="20">
                    <li>
                        <input type="radio" name="refine" value="s_author" checked>
                        <font color="white">著者</font>
                    </li>
                    <li>
                        <input type="radio" name="refine" value="s_keyword">
                        <font color="white">キーワード</font>
                    </li>
                    <li>
                        <input type="radio" name="refine" value="s_all">
                        <font color="white">すべて</font>
                    </li>
                    <li>
                        <font color="white">分野の選択</font>
                    </li>
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
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
        <!-- ここに検索結果が表示されます -->';
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
    $yearSortArray = CategorySort($yearSortArray,'genre');
    //見出し１つ分の論文だけが入っている配列を年代順にソートした配列を年代順に表示
    foreach ($yearSortArray as $paper) {
        Result($paper);
    }
    $yearSortArray = array();
    $countEnd = $countEnd + $dataCountArray[$midashi];
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
//入力された文字列で$paper内の著者名orキーワードを検索する関数
function StringSearch($targetStrings,$query,$paper,$not,$dataCount){
    //echo "<br />stringSearch";
    if(strpos($targetStrings, $query) === FALSE){
        //$not = NotPaper($not,$dataCount);
        //return($not);
        return('notPaper');
    }else{
        //検索結果を表示する
        //Result($paper);
        return('Paper');
    }
}
//データベース内に論文があるかをチェックする関数
function NotPaper($not,$dataCount){
    //echo "<br />notPaper";
    //echo "<br />".$not."-".$dataCount;
    //データベース内に論文があるかをチェック
    //$not++;
    if($not >= $dataCount){
        echo '<div class="container-fluid">
            <h1>お探しの論文はみつかりませんでした</h1>
            </div>';
    }
    return($not);
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