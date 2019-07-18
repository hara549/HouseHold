<?php
  $category_id = $_POST['category_id'];

  //DB検索
  $pdo = new PDO("mysql:dbname=household", "root");
  $st = $pdo->prepare("SELECT * FROM category_tbl WHERE category_id=?");
  $st->execute(array($category_id));
  $row = $st->fetch(PDO::FETCH_ASSOC);

  // 検索結果を配列にセット
  $category[] = array(
      'category_id'   => $row['category_id'],
      'room_id'       => $row['room_id'],
      'category_name' => $row['category_name'],
      'money'         => $row['money']
  );

  header('Content-type: application/json');
  echo json_encode($category);
?>
