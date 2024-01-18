
// const { find } = require("lodash");

// const { functionsIn } = require("lodash");

$(function () {

    console.log("OK");

    //削除機能非同期

    setDeleteButtonEvent();
    console.log("検索前の削除");

    initializeSort();

    function setDeleteButtonEvent() {

        console.log("セット");

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
    $('#search_form').off('click').on('submit', function (e) {
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
                'company_name': company_name,
                'price_upper': price_upper,
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
                setDeleteButtonEvent();
                initializeSort();
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



    function initializeSort() {
        // ソート可能な要素に対してSortableを適用
        console.log('初期化');
        $('#result_table').sortable({
            items: 'tr',  // ソート対象の要素を指定
            axis: 'y',    // Y軸方向にソート
            handle: '.sortable-handle',  // ドラッグハンドルの要素を指定
            update: function (event, ui) {
                // ソート完了時の処理
                var sortedIds = $('#result_table').sortable('toArray');
                console.log('ソート完了', sortedIds);

                // ソートの順番をサーバーに送信するためのAjaxリクエストを実行
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type: 'POST',
                    url: '/products/sort',
                    dataType: 'json',
                    data: {
                        sortedIds: sortedIds,
                    },
                    success: function (data) {
                        console.log('ソート成功', data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('ソートエラー', errorThrown);
                    }
                });
            }
        });

        $('.sortable').on('click', function () {
            console.log('ソートセット');
            var column = $(this).attr('id').replace('sort_', '');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                type: 'GET',
                url: '/products/sort/' + column,
                dataType: 'json',
                success: function (data) {
                    displaySearchResults(data.products);
                    setDeleteButtonEvent();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error: ' + errorThrown);
                    console.log("ajax通信に失敗しました");
                    console.log("jqXHR          : " + jqXHR.status);
                    console.log("textStatus     : " + textStatus);
                    console.log("errorThrown    : " + errorThrown.message);
                }
            });


        });
        console.log('ソート完了')
    }


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

            // console.log(imgPath);
            // console.log(table);
        });

        console.log('表示前');

        setDeleteButtonEvent();

        console.log('表示')

    }


});
