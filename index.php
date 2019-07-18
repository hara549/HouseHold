<?php
  session_start();

  //ログイン済みの場合、top画面へ遷移する
  if (isset($_SESSION['room_id'])) {
    require 'view_top.php';
    exit();
  }

  //ログインボタン押下時、処理を実行する
  if (isset($_POST['login'])) {

    $error = "";

    //room_id、password入力チェック
    if (empty($_POST['room_id'])) {
      $error .= 'ルームIDを入力してください</br>';
    }
    if (empty($_POST['password'])) {
      $error .= 'パスワードを入力してください</br>';
    }

    if (!empty($_POST['room_id']) && !empty($_POST['password'])) {
      try {
        //postされたroom_id、passwordでDBを検索
        $pdo = new PDO("mysql:dbname=household", "root");
        $st = $pdo->prepare("SELECT * FROM room_tbl WHERE room_id=? AND password=? ");
        $st->execute(array($_POST['room_id'],$_POST['password']));
        $row = $st->fetch(PDO::FETCH_ASSOC);

        //対象レコードが存在するかチェック
        if (!isset($row['room_id'])) {
          $error = 'ルームIDまたはパスワードが誤っています';
        } else {
          //ログイン成功時、top画面へ遷移
          $_SESSION['room_id'] = $_POST['room_id'];
          require 'view_top.php';
          exit();
        }
      } catch (PDOException $e) {
        $error = 'データベースエラーです。管理者へ連絡してください。';
      }
    }
  }
 ?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<!-- 外部スタイルシート宣言 -->
<?php include("./inc/style.php") ?>

<!-- 外部スクリプト宣言 -->
<?php include("./inc/script.php"); ?>

<title>HouseHold （ログイン）</title>

<!-- スタイル編集用 -->
<style type="text/css">
  .login {
    width: 400px;
    margin: 0 auto;
    padding: 10px 10px;
    text-align: center;
    background-color: rgb(237, 240, 235);
    box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.3);
  }

  .login_form input {
    width: 300px;
    height: 40px;
    margin-bottom: 5px;
  }
</style>
</head>

<body class="drawer drawer--left">
  <!-- 共通header部 -->
  <?php include("./inc/header.php"); ?>

  <!-- Main部 (画面固有処理を以降に実装してください-->
  <div class="login">
    <h1 class="title">ログイン</h1>
    <div class="error">
      <?php
        if (isset($error)) {
          echo $error;
        }
      ?>
    </div>
    <form class="login_form" action="index.php" method="post">
      <input type="text" name="room_id" placeholder="ルーム名"></br>
      <input type="password" name="password" placeholder="パスワード" autocomplete="off"></br>
      <button class="button" type="submit" name="login">ログイン</button></br>
    </form>
    <p>パスワードを忘れた場合は<a href="#">こちら</a></p>
  </div>

  <script type="text/javascript">
      $(function(){
        //ヘッダー共通処理（ドロワー）
        $('.drawer').drawer();
    });
  </script>
</body>
</html>
