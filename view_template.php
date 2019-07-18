<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<!-- 外部スタイルシート宣言 -->
<?php include("./inc/style.php") ?>

<!-- 外部スクリプト宣言 -->
<?php include("./inc/script.php"); ?>

<title>HouseHold （<!-- 画面名を入力してください -->）</title>

<!-- スタイル編集用 -->
<style type="text/css"></style>
</head>

<body class="drawer drawer--left">

<script type="text/javascript">
    $(function(){
      //ヘッダー共通処理（ドロワー）
      $('.drawer').drawer();
  });
</script>

<!-- 共通header部 -->
<?php include("./inc/header.php"); ?>

<!-- Main部 (画面固有処理を移行に実装してください-->

</body>
</html>