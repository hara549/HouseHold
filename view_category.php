<?php
  session_start();
?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<!-- 外部スタイルシート宣言 -->
<?php include("./inc/style.php") ?>

<!-- 外部スクリプト宣言 -->
<?php include("./inc/script.php"); ?>

<title>HouseHold （家事カテゴリ登録）</title>

<!-- スタイル編集用 -->
<style type="text/css">
  .categorys {
    width: 100%;
  }

  .box {
    text-align: center;
    width: 100px;
    height: 83px;
    border-radius: 8px;
    margin: 10px 10px;
    box-sizing: border-box;
    display: inline-block;
  }
  .box:hover {
    opacity: 0.8;
  }
  .box:active {
    box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.3);
    border-bottom: none;
    position: relative;
    top: 5px;
  }

  .category {
    color: #fff;
    background-image: linear-gradient(#457bf5 0%, #49bff7 100%);
    border-bottom: solid 5px #5e7fca;
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    cursor: pointer;
  }

  .add_category {
    color: #6e6a6a;
    background-image: linear-gradient(#e7f545 0%, #e4f784 100%);
    border-bottom: solid 5px #c1ae09;
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    cursor: pointer;
  }

  .modal_wrapper {
    display: none;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.6);
    z-index: 100;
  }

  .modal {
    position: absolute;
    top: 20%;
    left: 30%;
    background-color: rgb(237, 240, 235);
    padding: 20px 0px;
    border-radius: 10px;
    width: 450px;
    height: auto;
    text-align: center;
}

.modal input {
  width: 300px;
  height: 40px;
  margin-bottom: 5px;
}

.close_modal {
  position: absolute;
  top: 5px;
  right: 15px;
  color: rgb(143, 137, 138);
  font-size: 10px;
  cursor: pointer;
}
/* Chrome、Safari */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
}
/* Firefox、IE */
input[type="number"] {
  -moz-appearance: textfield;
}
#delete {
  background-color: rgb(247, 110, 63);
}


</style>
</head>

<body class="drawer drawer--left">
  <!-- 共通header部 -->
  <?php include("./inc/header.php"); ?>

  <!-- Main部 (画面固有処理を移行に実装してください-->
  <div class="container">
    <div class="error"></div>
    <h1 class="title">家事カテゴリ登録</h1>
    <div class="categorys">
      <!-- category_tblから取得して表示 -->
      <div class="result"></div>
    </div>
  </div>
  <div class="modal_wrapper">
    <div class="modal">
      <div class="close_modal"><i class="fa fa-2x fa-times"></i></div>
      <p>家事カテゴリ編集</p>
      <input id="category_name" type="text" value="" placeholder="家事カテゴリ名"></br>
      <input id="money" type="number" value="" placeholder="単金"></br>
      <div class="result_button"></div>
      <input id="category_id" type="hidden" name="category_id" value="">
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(function(){
      //ヘッダー共通処理（ドロワー）
      $('.drawer').drawer();

      var room_id = <?php echo json_encode($_SESSION["room_id"]) ?>;

      //初期表示
      $.ajax({
        url:'model_category_all.php',
        type:'POST',
        data:{
          'room_id': room_id
        }
      })
      .done((data) => {
        $('.result').html(data);
      })
      .fail((data) => {
        $('.error').html("画面表示エラーです。管理者へ連絡してください。");
      })
      .always((data) => {
        console.log(data);
      });

      //登録済み家事カテゴリクリック時
      $('.result').on('click','.category', function(){
        $('.result_button').html('<div class="button" id="update">更新</div><div class="button" id="delete">削除</div>');
        $('.modal_wrapper').fadeIn();

        //選択したカテゴリidを取得
        var category_id = $(this).attr('id');
        //画面隠し領域にカテゴリidをセット
        $('#category_id').val(category_id);

        //Ajax通信でDBの内容をvalueへ設定する
        $.ajax({
          url:'model_category_show.php',
          type:'POST',
          data:{
            'category_id':category_id
          }
        })
        .done((data) => {
          $('#category_name').val(data[0].category_name);
          $('#money').val(data[0].money);
        })
        .fail((data) => {
          $('.error').html("データの取得でエラーが発生しました。管理者へ連絡してください。");
          $('.modal_wrapper').fadeOut();
        })
        .always((data) => {
          console.log(data);
        });
      });


      //家事カテゴリ追加ボタン押下時
      $('.result').on('click', '.add_category', function(){
        $('.result_button').html('<div class="button" id="insert">追加</div>');
        $('#category_name').val('');
        $('#money').val('');
        $('.modal_wrapper').fadeIn();
      });

      // データ追加ボタン押下時
      $('.result_button').on('click','#insert', function(){

        var room_id = <?php echo json_encode($_SESSION["room_id"]); ?>;

        $.ajax({
          url:'model_category_insert.php',
          // url:'model_test.php',
          type:'POST',
          data:{
            'room_id':room_id,
            'category_name':$('#category_name').val(),
            'money':$('#money').val()
          }
        })
        //Ajaxリクエスト成功時
        .done((data) => {
          $('.result').html(data);
          $('.modal_wrapper').fadeOut();
        })
        //Ajaxリクエスト失敗時
        .fail((data) => {
          $('.error').html("データの更新でエラーが発生しました。管理者へ連絡してください。");
          $('.modal_wrapper').fadeOut();
        })
        .always((data) => {
          console.log(data);
        });
      });

      // データ更新ボタン押下時
      $('.result_button').on('click','#update', function(){

        //room_idを取得
        var room_id = <?php echo json_encode($_SESSION["room_id"]); ?>;

        $.ajax({
          url:'model_category_update.php',
          type:'POST',
          data:{
            'room_id':room_id,
            'category_id':$('#category_id').val(),
            'category_name':$('#category_name').val(),
            'money':$('#money').val()
          }
        })
        //Ajaxリクエスト成功時
        .done((data) => {
          $('.result').html(data);
          $('.modal_wrapper').fadeOut();
        })
        //Ajaxリクエスト失敗時
        .fail((data) => {
          $('.error').html("データの更新でエラーが発生しました。管理者へ連絡してください。");
          $('.modal_wrapper').fadeOut();
        })
        .always((data) => {
          console.log(data);
        });
      });

      //データ削除ボタン押下時
      $('.result_button').on('click','#delete', function(){

        //room_idを取得
        var room_id = <?php echo json_encode($_SESSION["room_id"]); ?>;

        $.ajax({
          url:'model_category_delete.php',
          type:'POST',
          data:{
            'room_id':room_id,
            'category_id':$('#category_id').val(),
          }
        })
        //Ajaxリクエスト成功時
        .done((data) => {
          $('.result').html(data);
          $('.modal_wrapper').fadeOut();
        })
        //Ajaxリクエスト失敗時
        .fail((data) => {
          $('.error').html("データの更新でエラーが発生しました。管理者へ連絡してください。");
          $('.modal_wrapper').fadeOut();
        })
        .always((data) => {
          console.log(data);
        });
      });

      //モーダルウィンドウ非表示
      $('.close_modal').click(function(){
        $('.modal_wrapper').fadeOut();
      });
    });
  </script>
</body>
</html>
