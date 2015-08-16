<?php
//Topページでクリックした項目を取得
$pageTitle =  htmlspecialchars($_POST['pageTitle']);
//database.txtの内容を検索できる形式に変換
$paperArray = MAKE_PAPER_ARRAY();

//ページごとに表示する論文を$dataArrayに入れる
//入力された情報を取得
$query = htmlspecialchars($_POST['queryValue']);
$refine = htmlspecialchars($_POST['queryRefine']);
$category = htmlspecialchars($_POST['queryCategory']);
//入力された情報を使って検索する
$paperArray = PAPER_SEARCH($paperArray,$pageTitle,$query,$refine,$category);
//検索結果をソートし，日本語表記に変換する
$limit =  htmlspecialchars($_POST['selectLimit']);
$dataArray = PAPER_SORT($paperArray,$limit);

//ソート・変換された$dataArrayを表示する
foreach ($dataArray as $index => $papers) {
    echo ' <h1>'.$index.'</h1>';
    $paperArray = $papers;
    foreach ($paperArray as $paper) {
        RESULT($paper);
    }
}

//database.txtの内容を検索できる形式に変換する関数
function MAKE_PAPER_ARRAY(){
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
//検索する関数
function PAPER_SEARCH($paperArray,$pageTitle,$query,$refine,$category){
    $dataArray = array();
    if($pageTitle != 'search'){
        foreach ($paperArray as $paper) {
            if($paper['genre']==0){
                if($paper['location']=='Japan'){
                    $Array0[] = $paper;
                }else{
                    $Array1[] = $paper;
                }
            }elseif($paper['genre']==1){
                $Array2[] = $paper;
            }elseif($paper['genre']==2){
                $Array3[] = $paper;
            }else{
                //ない場合
            }
        }
        if($pageTitle == 'all_paper'){
            //all_paperの場合はすべての論文を使う
            $ArraySet = array($Array0,$Array1,$Array2,$Array3);
            for($i=0;$i<4;$i++){
                if(isset($ArraySet[$i])){
                    $dataArray = array_merge($dataArray,$ArraySet[$i]);
                }
            }
        }elseif($pageTitle == 'national'){
            $dataArray = $Array0;
        }elseif($pageTitle == 'international'){
            $dataArray = $Array1;
        }elseif($pageTitle == 'journal'){
            $dataArray = $Array2;
        }elseif($pageTitle == 'tesis'){
            $dataArray = $Array3;
        }else{
            //ない場合
            //$dataArray = array();
        }
    }else{
        //クエリが入力されているかどうかを判断
        if(empty($query)){
            //クエリが入力されていない場合
            //カテゴリの指定があるかどうかを判断
            foreach ($paperArray as $paper) {
                if($category == "all"){
                    //全ての論文を表示する
                    $dataArray[] = $paper;
                }elseif($category == $paper['category']){
                    //指定されたカテゴリの論文を表示する
                    $dataArray[] = $paper;
                }else{
                    //指定されたカテゴリの論文がなかった場合
                    //$not++;
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
                }else{
                    if($category == "all"){
                        $targetStrings = $paper[$targetKey];
                    }elseif($category == $paper['category']){
                        $targetStrings = $paper[$targetKey];
                    }else{
                        //$not++;
                    }
                }
                //入力された文字列で$paper内の著者名orキーワードを検索する
                if(strpos($targetStrings, $query) === FALSE){
                    //$not++;
                }else{
                    $dataArray[] = $paper;
                }
            }
        }
    }
    return($dataArray);
}
//並び替える関数
function PAPER_SORT($paperArray,$limit){
    //なにでソートしたいかを判断
    if($limit == 'Conference'){
        if($paperArray[0]['genre']==2){
            $sortKey = 'degree';
        }else{
            $sortKey = 'journal';
        }
    }elseif($limit == 'Author'){
        $sortKey = 'author';
    }elseif($limit == 'Year'){
        $sortKey = 'year';
    }elseif($limit == 'Category'){
        $sortKey = 'category';
    }else{
       $sortKey = 'genre';
    }
    //表示する論文の配列を作る
    //見出しごとの論文の数を数える
    foreach ($paperArray as $paper) {
        $sortKeyArray[] = $paper[$sortKey];
    }
    $indexCountArray = array_count_values($sortKeyArray);
    //見出しの配列をつくる
    $indexArray = array_keys($indexCountArray);
    //見出しがいくつあるのかを数える（見出しが入っている配列のデータの個数を調べる）
    $indexLength = count($indexArray);
    //見出しを含む論文を取り出す
    for ($i=0; $i < $indexLength; $i++) {
        $index = $indexArray[$i];
        $indexNumArray[$index] = array_keys($sortKeyArray,$index);
    }
    //連想配列を作る
    for($i = 0; $i < $indexLength; $i++){
        //見出しを入れる
        $index = $indexArray[$i];
        for ($j = 0; $j < $indexCountArray[$index]; $j++) {
            //さっき入れた見出しをキーとする論文の配列をいれる
            $num = $indexNumArray[$index][$j];
            //データを日本語表記に変更して配列に入れなおす
            $trans_paper = PAPER_TRANSFORM($paperArray[$num]);
            $dataArray[$index][$j] = $trans_paper;
        }
    }
    //ソートする
    krsort($dataArray);
    //main.jsにソート・変換されたdataArrayを渡す
    return($dataArray);
}
//発表場所，発表形式，学位，カテゴリを日本語表記に変換する関数
function PAPER_TRANSFORM($paper){
    //echo "<br />Transform";
    //ジャンルを日本語表記に変換する
    if($paper['genre'] == 0){
        if($paper['location'] == 'Japan'){
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
//検索結果を表示する関数
function RESULT($paper){
    //echo "<br />result";
    //ジャンルに問わず表示する内容（前半）
    echo '<div class="paper-fluid">
        <p>論文タイトル：</p>
        <h3>'.$paper['title'].'</h3>
        <p>著者名：'.$paper['author'].'</p>
        <p>学位：'.$paper['degree'].'</p>';
    if($paper['genre']=='学位論文'){
        //修論・卒論の表示
        echo '<p>発表年：'.$paper['year'].'</p>';
    }else{
        //学会発表・国際会議の表示
        if($paper['genre']=='国内発表' || $paper['genre']=='国際会議'){
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
?>