<?php
  $room_id = $_POST['room_id'];

  //DB検索
  $pdo = new PDO("mysql:dbname=household", "root");
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
