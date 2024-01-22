
$(function () {

    console.log("OK");

    //削除機能非同期

    $("#sort").tablesorter();

    setDeleteButtonEvent();

    console.log("検索前の削除");

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

            success: function (data) {

                console.log('成功');
                console.log('テスト', data.products);
                displaySearchResults(data.products);
                setDeleteButtonEvent();

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

            // assetPath の最後にスラッシュが含まれている場合は削除する
            assetPath = assetPath.replace(/\/$/, '');

            var imgPath = assetPath + '/' + product.img_path;

            console.log('アセットパス', assetPath);

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



            console.log('追加後メーカー', company_name);

            table.append(htmlString);

            table.trigger('update');

            // ソートの初期化
            table.trigger('sortReset');

            // 表示前に再度 tablesorter 適用
            table.tablesorter();

        
        });

        console.log('表示前');

        setDeleteButtonEvent();

        console.log('表示')

    }


});
