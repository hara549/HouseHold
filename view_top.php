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
  position: relative;
}

.slick-slide:after {
	position: absolute;
	display: block;
	content: "";
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
  background: linear-gradient(-180deg, rgba(255, 255, 255, 0) 70%, #fff 100%);

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

.menus {
  width: 100%;
  text-align: center;
  position: absolute;
  top: 700px;
  left: 0px;
}
.menu {
  display: inline-block;
  width: 300px;
  height: 270px;
  background-color: #fff;
  box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.3);
  margin: 0px 20px;
  font-size: 30px;
  opacity: 0.9;
  cursor: pointer;
  border-radius: 10px;
}
.menu:hover {
  opacity: 1.0;
}
@media (max-width: 1170px) {
  .menus {
    display: none;
  }
}


</style>
</head>

<body class="drawer drawer--left">

<!-- 共通ヘッダー -->
<?php include("./inc/header.php"); ?>

<div class="container">
  <!-- メイン部 -->
  <div id="main-top">
      <p>今月の目標</p>
      <input type="text" class="wf-mplus1p" id="goal-top" maxlength="20" value="JEEP ラングラーを買う">
  </div>

  <!-- slicker -->
  <!-- TODO：画像はDBからURLを取得し、動的に表示する -->
  <section class="slicker-top slider">
    <p><img class="slicker-image" src="./img/top_1.JPG"></p>
    <p><img class="slicker-image" src="./img/top_2.JPG"></p>
    <p><img class="slicker-image" src="./img/top_3.JPG"></p>
  </section>

  <!-- menus -->
  <div class="menus">
    <div class="menu" id="inquery">
      <p>照会</p>
      <i class="fas fa-chart-pie" style="font-size:50px; color:#1a73e8;"></i>
    </div>
    <div class="menu" id="category">
      <p>カテゴリ登録</p>
      <i class="fas fa-broom" style="font-size:50px; color:#1a73e8;"></i>
    </div>
    <div class="menu" id="createroom">
      <p>ルーム作成</p>
      <i class="fas fa-user-plus" style="font-size:50px; color:#1a73e8;"></i>
    </div>
  </div>
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

      //ページ遷移
      $('#inquery').on('click', function(){
        window.location.href = "./view_inquery.php";
      });
      $('#category').on('click', function(){
        window.location.href = "./view_category.php";
      });
      $('#createroom').on('click', function(){
        window.location.href = "./view_createroom.php";
      });
  });
</script>

</body>
</html>
