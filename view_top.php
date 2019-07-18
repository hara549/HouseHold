<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<!-- 外部スタイルシート宣言 -->
<?php include("./inc/style.php") ?>

<!-- 外部スクリプト宣言 -->
<?php include("./inc/script.php"); ?>

<title>HouseHold （トップ）</title>

<!-- スタイル編集用（最終的には共通スタイルシートへ移動） -->
<style type="text/css">
#main-top{
    text-align: center;
}
/* スローガン関連 */
#goal-top{
    text-align: center;
    width: 80%;
    height:60px;
    font-size:36px;
    font: bold;
}

/* SLICK関連 */

.slider {
  width: 100%;
  margin: 10px auto;
}

.slick-slide img {
width: 100%;
}

.slider p {
  font-size: 2rem;
  font-weight: bold;
  line-height: 100px;
  color: #666;
  margin: 10px;
  text-align: center;
  background-color: #e0e0e0;
}

/* 矢印 */
.prev-arrow,
.next-arrow {
    position: absolute;
    top: 50%;
    margin: 0;
    padding: 0;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
    cursor: pointer;
    font-weight: bold;
    z-index: 1;
    font-size: 60px;
    height: 70px;
    line-height: normal;
    color: white;
    width: 26px;
    content: "\f12b";
}

.prev-arrow {
  left: 50px;
}

.next-arrow {
  right: 50px;
}

.slick-prev:before,
.slick-next:before {
  color: #000 !important;
}

</style>
</head>

<body class="drawer drawer--left">

<!-- 共通ヘッダー -->
<?php include("./inc/header.php"); ?>


<!-- slicker -->
<!-- TODO：画像はDBからURLを取得し、動的に表示する -->
<section class="slicker-top slider">
  <p><img class="slicker-image" src="./img/top_1.JPG"></p>
  <p><img class="slicker-image" src="./img/top_2.JPG"></p>
  <p><img class="slicker-image" src="./img/top_3.JPG"></p>
</section>
    
<!-- メイン部 -->
<div id="main-top">
    <p>今月の目標</p>
    <input type="text" class="wf-mplus1p" id="goal-top" maxlength="20" value="JEEP ラングラーを買う">
</div>
<script type="text/javascript">
    $(function(){
      //ヘッダー共通処理（ドロワー）
      $('.drawer').drawer();

      $(".slicker-top").slick({
          dots: true, //点の表示
          autoplay: true, //自動切り替え
          autoplaySpeed: 5000, //自動切り替えにかかる時間
          centerMode: true, //要素を中央へ
          adaptiveHeight:true,
          centerPadding: '0%',
          prevArrow: '<span class="prev-arrow">&lang;</span>',
          nextArrow: '<span class="next-arrow">&rang;</span>',
      });
  });
</script>

</body>
</html>
