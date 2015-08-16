function Ajax(pageTitle){
    console.log("Start_Ajax.js");
    //What'sNewを表示する
    if(pageTitle == 'WhatsNew'){
        $(".WhatsNew p").append("<h1>What's new!</h1>");
        $(".WhatsNew p").append("<div>以下の論文がアップロードされました</div>");
        $.ajax({
            url: 'WhatsNew.txt',
            success: function(data) {
                var data_array = data.split(/\r\n|\r|\n/); //改行コードで分割
                var len = data_array.length;
                for (var i = len - 1; i >= 0; i--) {
                    console.log(data_array[i]);
                    $(".WhatsNew p").append(data_array[i] + "<br />");

                }
            }
        });
    }else{
        //論文を表示させている部分を空にする
        $(".WhatsNew p").empty();
        $("div.container-fluid").empty();
        //サーチとソートに必要な変数を取得する
        console.log(pageTitle);
        var query = document.search.query.value;
        var refine = document.search.refine.value;
        var category = document.search.category.value;
        var limit = document.LimitTheSearch.limit.value;
        console.log(query);
        console.log(refine);
        console.log(category);
        console.log(limit);
        //ajaxでThisPageContent.phpから表示する論文が入った配列を取得
        $.ajax({
            type: "POST",
            url: "Search.php",
            data: {pageTitle:pageTitle,
                queryValue:query,
                queryRefine:refine,
                queryCategory:category,
                selectLimit:limit
            },
            success: function(dataArray) {
                //console.log(dataArray);
                console.log("GetData");
                $("div.container-fluid").append(dataArray);
            }
        });
    }
    console.log("End_Ajax.js");
};