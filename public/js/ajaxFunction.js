// $(document).ready(function(){

const { find } = require("lodash");

//     window.deleteProduct = function(id){
//         if(confirm('本当に削除しますか？')){
//             $.ajax({
//                 type:'DELETE', //getかpostにする。deleteでもいいけど前述の二つ以外は全ブラウザでサポートされていない。
//                 url:'/products/' + id,
//                 dataType:'json',
//                 headers:{'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
//                 success:function(data){
//                     console.log('削除に成功しました。')
//                     // window.location.href = "/products";
//                     $('#product_' + id).hide();
//                     // alert(data.message); // 成功メッセージの表示
//                 },
//                 error:function(data){
//                     console.log('Error:' , data);
//                     alert(data.responseJSON.error); // エラーメッセージの表示
//                 }
//             });
//         }
//     }
// });





// $.ajaxSetup({
//     headers:{'X-CSRF-TOKEN' :'{{csrf_token()}}'}
// });

$(function(){

    console.log("OK");

    //削除機能非表示
    $('.btn-danger').on('click',function(e){
        e.preventDefault();
        console.log("click");
        var deleteConfirm = confirm('削除しますか？');

        if(deleteConfirm == true){
            var clickEle = $(this);
            var productId = clickEle.data('id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:'/destroy/' + productId,
                dataType: 'json',
                data:{'_method':'delete'},

                success:function(data){
                    console.log('削除に成功しました。')
                    // window.location.href = "/products";
                  let clickParent = clickEle.parents('tr');
                  clickParent.remove();
                    // alert(data.message); // 成功メッセージの表示
                }
            });
            
        }
    });

    //検索機能非表示
    $('#search_form').on('submit', function(event){
        event.preventDefault();

        console.log('検索');
        var keyword = $('#keyword').val();


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'GET',
            url:'products/search',
            dataType:'html',
            data:{
                '_token': $('meta[name="csrf-token"]').attr('content'),
            'keyword': keyword
        },
            // data:{'_method':'search'},
            success:function(data){

                console.log('成功');
              let newtable = $(data).find('#result_table');

              $('#result_table').html(newtable);
            },
            error:function(jqXHR, textStatus, errorThrown) {
                console.log('Error: ' + errorThrown);
            }

        });


    });

});



    function displaySearchResults(products){

        var table = $('#result_table');
    table.empty();

    // products をテーブルに追加
    $.each(products, function(index, product) {

        console.log('追加');

        var row = $('<tr>');
        row.append($('<td>').text(product.id));
        row.append($('<td>').text(product.img_path));
        row.append($('<td>').text(product.product_name));
        row.append($('<td>').text(product.price));
        row.append($('<td>').text(product.stock));
        row.append($('<td>').text(product.company_name));

        table.append(row);



        // htmlを書く？
        // // var html = 
        // <tr>
        //     <td>${product.id}</td>
        // </tr>
        

    });

}
 
   


















// $.ajax({
//     type:'POST',
//     url:'/products/' +productId,
// }).done(function(results){

// })








// $(function () {

//     $('#search_btn').on('click', function () {

        

//         $.ajax({
//             type: 'GET', //HTTPリクエストメソッドを指定。
//             url: '/search', //リクエスト先を送信する先のURL
//             async: true, //非同期通信フラグ、初期値:true , falseだと同期通信になる
//             dataType: 'json', //サーバーからレスポンスされるデータの型を指定。返ってくるデータのMIMEタイプとの整合性をとる
//             timeout: 10000, //タイムアウト時間をミリ秒で指定
//             data: {
//                 id: 1,
//                 name: 'brisk'
//             } //サーバーに送信する値。オブジェクトが指定された場合、クエリー文字列に変換されてGETリクエストとして付加される
//         })
//             .done(function (data) {
//                 //通信が成功した時の処理
//             })
//             .fail(function () {
//                 //通信が失敗した時の処理
//             })
//             .always(function () {
//                 //通信が完了した時の処理
//             })



//     })




// });