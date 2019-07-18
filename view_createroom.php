<?php
  session_start();

  //作成ボタン押下時に実行
  if (isset($_POST['create_room'])) {

    //初期化
    $error[]    = array();
    $common_err = "";
    $error_flg  = "";

    //必須チェック
    if (empty($_POST['room_id'])) {
      $error['room_id'] = "ルーム名を入力してください";
      $error_flg = 1;
    }
    if (empty($_POST['password'])) {
      $error['password'] = "パスワードを入力してください";
      $error_flg = 1;
    }
    if (empty($_POST['nickname1'])) {
      $error['nickname1'] = "ユーザー名を入力してください";
      $error_flg = 1;
    }
    if (empty($_POST['nickname2'])) {
      $error['nickname2'] = "ユーザー名を入力してください";
      $error_flg = 1;
    }

    //重複レコードチェック
    if (!$error_flg) {
      try {
        $pdo = new PDO("mysql:dbname=household", "root");
        $st = $pdo->prepare("SELECT * FROM room_tbl WHERE room_id=? ");
        $st->execute(array($_POST['room_id']));
        $row = $st->fetch(PDO::FETCH_ASSOC);

        if (isset($row['room_id'])) {
          $error['exist'] = "このルーム名は登録済みです";
          $error_flg = 1;

        }
      } catch (PDOException $e) {
        $common_err = 'データベースエラーです。管理者へ連絡してください。';
        $error_flg = 1;
      }
    }

    //エラーなしの場合、入力情報をDBへ登録する
    if (empty($error_flg)) {
      //更新日を取得
      $create_date = date("Ymd");

      try {
        //DB接続
        $pdo = new PDO("mysql:dbname=household", "root");
        //room_tbl更新
        $st = $pdo->prepare("INSERT INTO room_tbl(room_id,password,create_date,delete_flg)  VALUES (?,?,?,0) ");
        $st->execute(array($_POST['room_id'],$_POST['password'],$create_date));
        $_SESSION['room_id']  = $_POST['room_id'];
        $_SESSION['password'] = $_POST['password'];

        //user_tbl更新(user_idはautoincrement)
        $st = $pdo->prepare("INSERT INTO user_tbl(room_id,nickname,create_date)
                                  VALUES (:room_id,:nickname1,:create_date),(:room_id,:nickname2,:create_date) ");
        $st->bindValue(':room_id', $_POST['room_id'], PDO::PARAM_STR);
        $st->bindValue(':nickname1', $_POST['nickname1'], PDO::PARAM_STR);
        $st->bindValue(':nickname2', $_POST['nickname2'], PDO::PARAM_STR);
        $st->bindValue(':create_date', $create_date, PDO::PARAM_INT);
        $st->execute();
        $_SESSION['nickname1'] = $_POST['nickname1'];
        $_SESSION['nickname2'] = $_POST['nickname2'];

        //ページ遷移
        require 'view_top.php';
        exit();

      } catch (PDOException $e) {
        $common_err = 'データベースエラー(user_tbl)です。管理者へ連絡してください。';
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

<title>HouseHold （ルーム作成）</title>

<!-- スタイル編集用 -->
<style type="text/css">
  .create_room {
    width: 400px;
    margin: 0 auto;
    padding: 10px 10px;
    text-align: center;
    background-color: rgb(237, 240, 235);
    box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.3);
  }

  .create_room_form input {
    width: 300px;
    height: 40px;
    margin-bottom: 5px;
  }
</style>
</head>

<body class="drawer drawer--left">
  <!-- 共通header部 -->
  <?php include("./inc/header.php"); ?>

  <!-- Main部 (画面固有処理を移行に実装してください-->
  <div class="create_room">
    <div class="error">
      <?php if (!empty($common_err)) { ?>
          <p class="error"><?= htmlspecialchars($common_err, ENT_QUOTES, 'UTF-8') ?></p>
       <?php } ?>
    </div>
    <form class="create_room_form" action="view_createroom.php" method="post">
      <!-- ルーム名 -->
      <p>ルーム作成</p>
      <?php if (!empty($error['room_id'])) { ?>
          <p class="error"><?= htmlspecialchars($error['room_id'], ENT_QUOTES, 'UTF-8') ?></p>
       <?php } ?>
       <?php if (!empty($error['exist'])) { ?>
           <p class="error"><?= htmlspecialchars($error['exist'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php } ?>
      <input type="text" name="room_id" placeholder="ルーム名"></br>

      <!-- パスワード -->
      <?php if (!empty($error['password'])) { ?>
          <p class="error"><?= htmlspecialchars($error['password'], ENT_QUOTES, 'UTF-8') ?></p>
       <?php } ?>
      <input type="password" name="password" placeholder="パスワード" autocomplete="off"></br>

      <!-- ユーザー名１ -->
      <p>ユーザー登録</p>
      <?php if (!empty($error['nickname1'])) { ?>
          <p class="error"><?= htmlspecialchars($error['nickname1'], ENT_QUOTES, 'UTF-8') ?></p>
       <?php } ?>
      <input type="text" name="nickname1" placeholder="ユーザー名"></br>

      <!-- ユーザー名２ -->
      <?php if (!empty($error['nickname2'])) { ?>
          <p class="error"><?= htmlspecialchars($error['nickname2'], ENT_QUOTES, 'UTF-8') ?></p>
       <?php } ?>
      <input type="text" name="nickname2" placeholder="ユーザー名"></br></br>

      <button class="button" type="submit" name="create_room">作成</button>
    </form>
  </div>

  <script type="text/javascript">
      $(function(){
        //ヘッダー共通処理（ドロワー）
        $('.drawer').drawer();
    });
  </script>
</body>
</html>
