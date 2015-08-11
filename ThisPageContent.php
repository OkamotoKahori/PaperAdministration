<?php
//Topページでクリックした項目を取得
$pageTitle = $_POST['pageTitle'];
//echo $pageTitle;
//database.txtの内容を検索できる形式に変換
$paperArray = MakePaperArray();
//$paperArrayから表示する論文だけを取り出す
$dataArray = array();

//ここから下は表示するページごとに変更する//
//ソートしたいカテゴリ（key）を指定
$categoryKey = 'genre';
//ページごとに表示する論文を$dataArrayに入れる
if($pageTitle == 'search'){
    //入力された情報を取得
    $query = htmlspecialchars($_POST['queryValue']);
    $refine = htmlspecialchars($_POST['queryRefine']);
    $category = htmlspecialchars($_POST['queryCategory']);
    //echo $query.$refine.$category;
    //echo "<br />";
    $dataArray = Search($paperArray,$query,$refine,$category);
    //第２引数で指定したkeyごとにソート(このkeyがコンテンツの見出しになる)
    $dataSortArray = CategorySort($dataArray,$categoryKey);
}else{
    //$paperArrayから表示する論文だけを取り出す
    foreach ($paperArray as $paper) {
        $paper = Transform($paper);
        if($paper['genre'] == '国内発表' && $paper['location'] == '国内'){
            $Array0[] = $paper;
        }elseif($paper['genre'] == '国際会議' && $paper['location'] == '国外'){
            $Array1[] = $paper;
        }elseif($paper['genre'] == '論文誌'){
            $Array2[] = $paper;
        }elseif($paper['genre'] == '学位論文'){
            $Array3[] = $paper;
        }
    }
    if($pageTitle == 'national'){
        $dataArray = $Array0;
    }elseif($pageTitle == 'international'){
        $dataArray = $Array1;
    }elseif($pageTitle == 'journal'){
        $dataArray = $Array2;
    }elseif($pageTitle == 'tesis'){
        $dataArray = $Array3;
    }else{
        $dataArraySet = array($Array0,$Array1,$Array2,$Array3);
        for($i=0;$i<4;$i++){
            if(isset($dataArraySet[$i])){
                $dataArray = array_merge($dataArray,$dataArraySet[$i]);
            }
        }
    }
    //第２引数で指定したkeyごとにソート(このkeyがコンテンツの見出しになる)
    $dataSortArray = $dataArray;
}
//ここから上は表示するページごとに変更する//

//見出しごとの論文の数を数える
$dataCountArray = CategoryCount($dataSortArray,$categoryKey);
//見出しを配列に入れる
$dataKeyArray = array_keys($dataCountArray);
//見出しがいくつあるのかを数える（見出しが入っている配列のデータの個数を調べる）
$dataNum = count($dataKeyArray);
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
echo '</div></div>';
}
//検索する関数
function Search($paperArray,$query,$refine,$category){
    //クエリが入力されているかどうかを判断
    if(empty($query)){
        //クエリが入力されていない場合
        //カテゴリの指定があるかどうかを判断
        foreach ($paperArray as $paper) {
            if($category == "all"){
                //全ての論文を表示する
                //Result($paper);
                $paper = Transform($paper);
                $dataArray[] = $paper;
            }elseif($category == $paper['category']){
                //指定されたカテゴリの論文を表示する
                //Result($paper);
                $paper = Transform($paper);
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
            $paper = Transform($paper);
            if($targetKey == "free"){
                //すべての情報を１つの文字列にする
                $paper = Transform($paper);
                $targetStrings = $paper['title'].$paper['author'].$paper['year'].$paper['journal'].$paper['location'].$paper['form'].$paper['keyword'];
            }else{
                if($category == "all"){
                    $targetStrings = $paper[$targetKey];
                }elseif($category == $paper['category']){
                    $targetStrings = $paper[$targetKey];
                }else{
                    $not++;
                }
            }
            $paperResult = StringSearch($targetStrings,$query,$paper);
            if($paperResult == 'Paper'){
                $paper = Transform($paper);
                $dataArray[] = $paper;
            }else{
                $not++;
            }
        }
    }
    return($dataArray);
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
    //ジャンルを日本語表記に変換する
    if($paper['genre'] == 0){
        if($paper['location'] == 'Japan' || $paper['location'] == '国内'){
            $paper['genre'] = "国内発表";
        }else{
            $paper['genre'] = "国際会議";
        }
    }elseif($paper['genre'] == 1){
        $paper['genre'] = "論文誌";
    }elseif($paper['genre'] == 2){
        $paper['genre'] = "学位論文";
    }
    //学位を日本語表記に変換する
    if($paper['degree'] == "Bachelor" || $paper['degree'] == "学士"){
        $paper['degree'] = "学士";
    }elseif($paper['degree'] == "Master" || $paper['degree'] == "修士"){
        $paper['degree'] = "修士";
    }elseif($paper['degree'] == "Doctor" || $paper['degree'] == "博士"){
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