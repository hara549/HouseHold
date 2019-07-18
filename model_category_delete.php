<?php
  $room_id     = $_POST['room_id'];
  $category_id = $_POST['category_id'];

  //DB更新
  $pdo = new PDO("mysql:dbname=household", "root");

  $st = $pdo->prepare("DELETE FROM category_tbl WHERE category_id = :category_id");
  $st->bindValue(':category_id', $category_id, PDO::PARAM_INT);
  $st->execute();


  //更新後DBを再度全検索
  $st = $pdo->prepare("SELECT * FROM category_tbl WHERE room_id=?");
  $st->execute(array($room_id));

  //htmlタグ生成
  $result = "";
  while($row = $st->fetch(PDO::FETCH_ASSOC)){
    $result .= '<div class="box category" id="' .$row['category_id']. '">' .$row['category_name']. '</div>';
  }
  $result .='<div class="box add_category"><i class="far fa-plus-square"></i>  追加</div>';

  header('Content-type: application/json');
  echo json_encode($result);
?>
