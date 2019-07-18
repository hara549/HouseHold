<?php
  $room_id       = $_POST['room_id'];
  $category_name = $_POST['category_name'];
  $money         = $_POST['money'];

  //DB更新
  $pdo = new PDO("mysql:dbname=household", "root");

  $st = $pdo->prepare("INSERT INTO category_tbl(room_id,category_name,money)
                            VALUES (:room_id,:category_name,:money)");
  $st->bindValue(':room_id', $room_id, PDO::PARAM_STR);
  $st->bindValue(':category_name', $category_name, PDO::PARAM_STR);
  $st->bindValue(':money', $money, PDO::PARAM_INT);
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
