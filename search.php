<?php
//echo "phpの読み込みはできています"."<br />";
$filename = "database.txt";
$data = file_get_contents($filename);
//var_dump($data);
//入力された情報
$query_value = htmlspecialchars($_POST['query']);
$refine = htmlspecialchars($_POST['refine']);
$query_category = htmlspecialchars($_POST['category']);
if($refine == 's_author'){
    $query_key = 'author';
}elseif($refine == 's_keyword'){
    $query_key = 'keyword';
}else{
    $query_key = 'other';
}

//database.texに含まれるデータの個数を数える
$datalength = substr_count($data, "paper");

//echo "これから'paper[] => 'で切断していきます"."<br />";
//$dataArray[0]が空になりますので，[1]から使ってください
$cut = '$paper[] => ';
$dataSet = explode($cut, $data);

//一人前ずつのデータを取り出します
//echo "これから配列を取り出します"."<br />";
foreach ($dataSet as $data) {
    //文字列をphpのソースとして読めるようにしています
    eval('$paperArray = '.$data.';');

    //入力された文字列でデータベース内を検索
    if(strpos($paperArray[$query_key], $query_value) === FALSE){
        $notPaper++;
    }else{
        $lap = result($paperArray,$query_value,$lap);
    }
    //論文が見つからないとき
    if($notPaper==$datalength){
        echo "お探しの論文はみつかりませんでした";
    }
}

/*
$Array = array("red"=>"赤","green"=>"緑","blue"=>"青");
$r = "緑";
$key = array_search($r, $Array);
echo $key;
*/

function result($paper,$query,$lap){
    if($paper['title']!=$lap){
        echo "<br />「{$query}」でお探しの論文が見つかりました<br/>
        bibtexID：{$paper['bibtexID']} <br/>
        論文タイトル：{$paper['title']} <br/>
        著者名：{$paper['author']} <br/>
        発表年：{$paper['year']} <br/>
        キーワード：{$paper['keyword']} <br/>
        分野：{$paper['category']}<br/>";
    }else{
        echo "<br />「{$query}」でお探しの論文は上記のいずれかの論文であると思われますです<br/>";
    }
    return($paper['title']);
}

?>
