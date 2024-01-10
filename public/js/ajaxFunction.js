
// const { find } = require("lodash");

// const { functionsIn } = require("lodash");


$(function () {

    console.log("OK");

    //削除機能非同期

    function setDeleteButtonEvent() {
        $('.btn-danger').on('click', function (e) {
            e.preventDefault();
            console.log("click");
            var deleteConfirm = confirm('削除しますか？');

            if (deleteConfirm == true) {
                var clickEle = $(this);
                var productId = clickEle.data('id');
                var deleteForm = $('#deleteForm-' + productId); // deleteForm の取得
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '/products/' + productId,
                    dataType: 'json',
                    data: { '_method': 'delete' },

                    success: function (data) {
                        console.log('削除に成功しました。')

                        var deletedRow = deleteForm.closest('tr');
                        deletedRow.remove();

                    }
                });

            }
        });
    }

    //検索機能非同期
    $('#search_form').on('submit', function (e) {
        e.preventDefault();

        console.log('検索');
        var keyword = $('#keyword').val();
        var company_name = $('select[name="company_name"] option:selected').val();
        var price_upper = $('#price_upper').val();
        var price_lower = $('#price_lower').val();
        var stock_upper = $('#stock_upper').val();
        var stock_lower = $('#stock_lower').val();

        console.log('メーカー', company_name);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            type: 'GET',
            url: 'search',
            dataType: 'json',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'keyword': keyword,
                'company_name': company_name,  // 'company_name' フィールドを追加
                'price_upper': price_upper,    // 他の入力フィールドについても同様に追加
                'price_lower': price_lower,
                'stock_upper': stock_upper,
                'stock_lower': stock_lower,
                'ajax': true
            },
            // data:{'_method':'search'},
            success: function (data) {

                console.log('成功');
                console.log('テスト', data.products);
                displaySearchResults(data.products);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error: ' + errorThrown);
                console.log("ajax通信に失敗しました");
                console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                console.log("textStatus     : " + textStatus);    // タイムアウト、パースエラー
                console.log("errorThrown    : " + errorThrown.message); // 例外情報
                // console.log("URL            : " + url);
            }

        });


    });

    function displaySearchResults(products) {
        var table = $('#result_table');

        console.log(table.length, '画像');

        table.empty();

        // products をテーブルに追加
        $.each(products, function (index, product) {
            console.log('追加');


            var imgPath = assetPath + '/' + product.img_path;

            var company_name = product.company ? product.company.company_name : '';


            console.log('追加前img', imgPath);
            console.log('追加前メーカー', company_name);
            console.log(product);
            console.log(products);

            // HTML文字列を直接追加
            var htmlString = '<tr>' +
                '<td>' + product.id + '</td>' +
                '<td><img src="' + imgPath + '" alt=""></td>' +
                '<td>' + product.product_name + '</td>' +
                '<td>' + product.price + '円</td>' +
                '<td>' + product.stock + '</td>' +
                '<td>' + company_name + '</td>' +
                '<td><a href="/products/' + product.id + '">詳細</a></td>' +
                '<td>' +
                '<form id="deleteForm-' + product.id + '" action="/products/' + product.id + '" method="POST"> <input type="hidden" name="_method" value="DELETE">' +
                '<button type="submit" class="btn btn-danger" data-id="' + product.id + '">削除</button>' +
                '</form>' +
                '</td>' +
                '</tr>';

            // console.log('生成したHTML:', htmlString);

            console.log('追加後メーカー', company_name);

            table.append(htmlString);

            setDeleteButtonEvent();

            console.log('表示')
            // console.log(imgPath);
            // console.log(table);
        });



        // // ソート機能の初期化処理
        // initializeSort();

       


       

        // // カラムの現在のソート方向を取得する関数
       


    }



    // function handleSortClick(column) {
    //     var currentDirection = getSortDirection(column);
    //     var newDirection = (currentDirection === 'asc') ? 'desc' : 'asc';
    //     console.log('スタート');
    //     // リクエストを送信して非同期でソート結果を取得
    //     $.ajax({
    //         type: 'GET',
    //         url: 'search',
    //         dataType: 'json',
    //         data: {
    //             '_token': $('meta[name="csrf-token"]').attr('content'),
    //             'sortColumn': column,
    //             'sortDirection': newDirection,
    //             // 他の検索条件も必要に応じて追加
    //         },
    //         success: function (data) {
    //             displaySearchResults(data.products);
    //         },
    //         error: function (jqXHR, textStatus, errorThrown) {
    //             console.log('Error: ' + errorThrown);
    //             // エラー処理
    //         }
    //     });
    // }

    // $('.sortable').on('click', function (event) {
    //     event.preventDefault(); // デフォルトのイベントをキャンセル

    //     console.log('クリックした');
    //     var columnName = $(this).data('column'); // データ属性からカラム名を取得

    //     console.log(columnName);
    //     (() => {
    //         handleSortClick(columnName);
    //     })();
    //     console.log('クリック後');
    // });

    // function getSortDirection(column) {
    //     var sortHeader = $('#sort_' + column);
    //     if (sortHeader.hasClass('asc')) {
    //         return 'asc';
    //     } else if (sortHeader.hasClass('desc')) {
    //         return 'desc';
    //     } else {
    //         return ''; // ソートされていない場合
    //     }
    // }


    // function getInitialSortColumn() {
    //     return 'id';
    // }

    // function getInitialSortOrder() {
    //     return 'desc';
    // }

    // function initializeSort() {
    //     var initialSortColumn = getInitialSortColumn();
    //     var initialSortOrder = getInitialSortOrder();



    //     if (initialSortColumn && initialSortOrder) {
    //         var sortHeader = $('#sort_' + initialSortColumn);
    //         sortHeader.addClass(initialSortOrder);
    //         sortHeader.find('a').attr('href', sortHeader.find('a').attr('href') + '&direction=' + initialSortOrder);
    //     }


    // }

});
