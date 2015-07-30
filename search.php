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
                <div class="space"></div>
                <li>
                    <a href="password.html">論文のアップロード</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <!--
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div id="bibtexID"></div>
                <div id="title"></div>
                <div id="author"></div>
                <div id="year"></div>
                <div id="keyword"></div>
                <div id="category"></div>
            </div>
        </div>
        -->
        <!-- /#page-content-wrapper -->


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
        $lap = result_2($paperArray,$query_value,$lap);
    }
    //論文が見つからないとき
    if($notPaper==$datalength+1){
        print('<h1>お探しの論文はみつかりませんでした</h1>');
    }
}
/*
$Array = array("red"=>"赤","green"=>"緑","blue"=>"青");
$r = "緑";
$key = array_search($r, $Array);
echo $key;
*/
function result_2($paper,$query,$lap){
    if($paper['title']!=$lap){
        print('<div id="page-content-wrapper">');
        print('<div class="container-fluid">');
        print('<h1>'.$paper['bibtexID'].'</h1>');
        print('<h1>'.$paper['title'].'</h1>');
        print('<h1>'.$paper['author'].'</h1>');
        print('<h1>'.$paper['year'].'</h1>');
        print('<h1>'.$paper['keyword'].'</h1>');
        print('<h1>'.$paper['category'].'</h1>');
            //    <h1>遷移</h1>
            //    <h1>したくない</h1>
        print('</div>');
        print('</div>');
        /*
        echo "<br />「{$query}」でお探しの論文が見つかりました<br/>
        bibtexID：{$paper['bibtexID']} <br/>
        論文タイトル：{$paper['title']} <br/>
        著者名：{$paper['author']} <br/>
        発表年：{$paper['year']} <br/>
        キーワード：{$paper['keyword']} <br/>
        分野：{$paper['category']}<br/>";
        */
    }else{
        echo "<br />「{$query}」でお探しの論文は上記のいずれかの論文であると思われますです<br/>";
    }
    return($paper['title']);
}
?>

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
