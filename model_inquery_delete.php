<?php
require_once('./inc/queryResult.php');
require_once('./inc/lib.php');

    $del_history_sql="DELETE FROM history_tbl WHERE history_id = :history_id";

    $get_history_sql="SELECT A.history_id, C.nickname, B.category_name, B.money, A.insert_date FROM history_tbl A
                    LEFT OUTER JOIN category_tbl B ON A.category_id =B.category_id
                    LEFT OUTER JOIN user_tbl C ON A.user_id = c.user_id
                    WHERE B.room_id = ?
                    ORDER BY A.history_id";

    $history_id = $_POST['history_id'];
    $room_id = $_POST['room_id'];

    $histry_all_ary = array();

    $get_history_ary =array($room_id);

    //DB更新
    $pdo = new PDO("mysql:dbname=household", "root");

    $st = $pdo->prepare($del_history_sql);
    $st->bindValue(':history_id', $history_id, PDO::PARAM_INT);
    $st->execute();


    //更新後DBを再度全検索
    $res = new QueryResult();
    $res = QueryPreSqlFnc($get_history_sql,$get_history_ary);
    
    if($res->get_err_flg() === 0){
      $histry_all_ary_wk = $res->get_result_ary();
    
      foreach ($histry_all_ary_wk as $key => $value) {
       array_push($histry_all_ary, $value);
      }
    }else{
      $error=$res->get_common_err();
    }

    //htmlタグ生成
    $result = "";

    $result .= '<table id="detail-table">';
    $result .= '<tr>';
    $result .= '<th>ユーザー名</th>';
    $result .= '<th>カテゴリ</th>';
    $result .= '<th>単金</th>';
    $result .= '<th>登録日時</th>';
    $result .= '<th></th>';
    $result .= '</tr>';

    foreach($histry_all_ary as $key => $value){
        $result .=  '<tr>';
        $result .=  '<td class="CENTER">'.$value["nickname"].'</td>';
        $result .=  '<td class="CENTER">'.$value["category_name"].'</td>';
        $result .=  '<td class="CENTER">'.$value["money"].'</td>';
        $result .=  '<td class="CENTER">'.$value["insert_date"].'</td>';
        $result .=  '<td class="CENTER">';
        $result .=  '<a href="#" onclick="OnDELClick('.$value["history_id"].');" class="btn-flat-bottom-border">';
        $result .=  '<span>DELETE</span>';
        $result .=  '</a>';
        $result .=  '</td>';
        $result .=  '</tr>';
      }
    header('Content-type: application/json');
    echo json_encode($result);
?>