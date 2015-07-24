<?php
function h($string){
    return htmlspecialchars($string, ENT_QUOTES);
}

// 入力された項目を検索する
$filename = "./database.txt";
chmod($filename, 0766);
//$fp = fopen($filename, "r");
// どの項目で検索されたか判断する
$title = htmlspecialchars($_POST['title']);
$author = htmlspecialchars($_POST['author']);
$society = htmlspecialchars($_POST['society']);
$select = htmlspecialchars($_POST['select']);
$year = htmlspecialchars($_POST['year']);
$keyword = htmlspecialchars($_POST['keyword']);
$category = htmlspecialchars($_POST['category']);
$upfile = htmlspecialchars($_POST['upfile']);
$query = array("title" => $title, "author" => $author, "society" => $society, "select" => $select, "year" => $year, "keyword" => $keyword, "category" => $category);
//print($title.",".$author.",".$society.",".$select.",".$year.",".$keyword.",".$category."<br />");

//データベースファイルを読み込んで，連想配列にする
$data = file_get_contents($filename);
$paperArray = explode("\n", $data);
foreach ($paperArray as $paper) {
    $value = explode(",", $paper);
    $paper = array("title" => $value[0], "author" => $value[1], "society" => $value[2], "select" => $value[3], "year" => $value[4], "keyword" => $value[5], "category" => $value[6]);
    //入力された項目と同じ要素をもつ配列を探す
    foreach ($paper as $koumoku => $value) {
        foreach ($query as $key => $value) {
            if($paper[$koumoku] == $query[$key]){
            //すでに表示されている検索結果を表示しないようにする
            $lap = result($paper,$query[$key],$lap);
            }
        }
    }
}

function result($paper,$query,$lap){
    if($paper['title']!=$lap){
        echo "<br />「{$query}」でお探しの論文が見つかりました<br/>
        論文タイトル：{$paper['title']} <br/>
        著者：{$paper['author']} <br/>
        学会名：{$paper['society']}, {$paper['select']} <br/>
        発表年：{$paper['year']} <br/>
        キーワード：{$paper['keyword']} <br/>
        分野：{$paper['category']}<br/>";
    }else{
        echo "<br />「{$query}」でお探しの論文は上記のいずれかの論文であると思われますです<br/>";
    }
    return($paper['title']);
}
?>
