<?php
session_start();

require_once('./inc/queryResult.php');
require_once('./inc/lib.php');

//テストデータ
// $_SESSION["room_id"]="room_10";

$res = new QueryResult();
$user_ary = null;

$categry_ary0_wk = null;
$categry_ary1_wk = null;

$categry_ary0 = array();
$categry_ary1 = array();

$histry_all_ary = array();

$histry_ary = null;

$diff_money_0 = 0;
$diff_money_1 = 0;

//user_id取得
$userid_ary = null;
$get_userid_sql ="SELECT user_id FROM user_tbl WHERE room_id=?";
$get_userid_ary = array($_SESSION["room_id"]);
$res = QueryPreSqlFnc($get_userid_sql,$get_userid_ary);
//取得値チェック
if($res->get_err_flg() === 0){
  $userid_ary = $res->get_result_ary();
}else{
  $error=$res->get_common_err();
}
//user_idを変数へ退避
$user1 = $userid_ary[0]["user_id"];
$user2 = $userid_ary[1]["user_id"];



//ユーザー別の[合計金額][実施回数]を取得用SQL
//（引数：　対象月開始日,対象月終了日,room_id[SESSION])
// (返り値： ユーザーID,合計金額,実施回数)
$get_money_sql ="SELECT C.nickname,SUM(B.money) money_sum,COUNT(history_id) histry_count
                  FROM history_tbl A
                  LEFT OUTER JOIN category_tbl B ON A.category_id = B.category_id
                  LEFT OUTER JOIN user_tbl C ON A.user_id = C.user_id
                  WHERE A.insert_date BETWEEN ? AND ?
                  AND A.room_id = ?
                  GROUP BY A.room_id,A.user_id";

$get_category_sql="SELECT A.category_name,A.category_id,A.money, COUNT(history_id) count_id FROM category_tbl A
                    LEFT OUTER JOIN
                    (SELECT * FROM history_tbl
                    WHERE user_id=?
                    AND room_id=?
                    AND insert_date BETWEEN ? AND ?) B
                    ON A.category_id = B.category_id
                    WHERE A.room_id=?
                    GROUP BY A.category_id";

$get_history_sql="SELECT A.history_id, C.nickname, B.category_name, B.money, A.insert_date FROM history_tbl A
                    LEFT OUTER JOIN category_tbl B ON A.category_id =B.category_id
                    LEFT OUTER JOIN user_tbl C ON A.user_id = C.user_id
                    WHERE B.room_id = ?
                    ORDER BY A.history_id";

$get_money_ary = array('20000101','29991231',$_SESSION["room_id"]);

$get_category_ary0 = array($user1,$_SESSION["room_id"],'20000101','29991231',$_SESSION["room_id"]);
$get_category_ary1 = array($user2,$_SESSION["room_id"],'20000101','29991231',$_SESSION["room_id"]);

$get_history_ary =array($_SESSION["room_id"]);


//ユーザー別の[合計金額][実施回数]を取得
$res = QueryPreSqlFnc($get_money_sql,$get_money_ary);

//取得値チェック
if($res->get_err_flg() === 0){
  $histry_ary = $res->get_result_ary();
}else{
  $error=$res->get_common_err();
}

//合計金額の差分計算
if($histry_ary[0]["money_sum"] != $histry_ary[1]["money_sum"]){
  if($histry_ary[0]["money_sum"] > $histry_ary[1]["money_sum"]){
    $diff_money_0 = $histry_ary[0]["money_sum"] - $histry_ary[1]["money_sum"];
  }else{
    $diff_money_1 = $histry_ary[1]["money_sum"] - $histry_ary[0]["money_sum"];
  }
}

//合計金額の割合計算
$parcent_0 = round(($histry_ary[0]["money_sum"] / ($histry_ary[0]["money_sum"] + $histry_ary[1]["money_sum"])) * 100);
$parcent_1 = 100 - $parcent_0;


//カテゴリー別の[単金][実施回数]を取得(ユーザー０)
$res = new QueryResult();
$res = QueryPreSqlFnc($get_category_sql,$get_category_ary0);

if($res->get_err_flg() === 0){
  $categry_ary0_wk = $res->get_result_ary();

  foreach ($categry_ary0_wk as $key => $value) {
    //連想配列[カテゴリID => 該当カテゴリのレコード取得情報]の形式に詰め替え
    $categry_ary0[$value["category_id"]] = $value;
  }

}else{
  $error=$res->get_common_err();
}

//カテゴリー別の[単金][実施回数]を取得(ユーザー１)
$res = new QueryResult();
$res = QueryPreSqlFnc($get_category_sql,$get_category_ary1);

if($res->get_err_flg() === 0){
  $categry_ary1_wk = $res->get_result_ary();

  foreach ($categry_ary1_wk as $key => $value) {
    //連想配列[カテゴリID => 該当カテゴリのレコード取得情報]の形式に詰め替え
    $categry_ary1[$value["category_id"]] = $value;
  }
}else{
  $error=$res->get_common_err();
}

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

if(count($categry_ary0) != count($categry_ary1)){
  echo "OK";
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


<title>HouseHold （照会）</title>

<!-- スタイル編集用 -->
<style type="text/css">
h2 {
  margin-top: 80px;
  border-bottom: solid 3px #76a7fa;
  position: relative;
}

h2:after {
  position: absolute;
  content: " ";
  display: block;
  border-bottom: solid 3px #fbcb43;
  bottom: -3px;
  width: 30%;
}

.inquery-result-graph{
    /* display: table;
    width: 100%; */
}
.slide .slick-slide {
	opacity: 1;
	transition: 0.5s;
}
.slide .slick-current {
	opacity: 1;
}
.inquery-result-graph-all > table{
  margin-bottom: 10px;
}

.inquery-result-graph-all > table{
  width: 100%;
}
#all-graph-td{
  width: 40%;
}
#all-detail-td{
  width: 60%;
}

.diff-money{
  color: blue;
}

#detail-table{
  width: 100%;
  text-align: center;
}


#detail-table{
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

#detail-table tr{
  border-bottom: solid 1px #eee;
  cursor: pointer;
  height: 50px;
}
#detail-table > td{
  text-align: center;
  width: 25%;
  padding: 15px 0;
}

#detail-table >th{
  text-align: center;
  width: 25%;
  padding: 15px 0;
}

#detail-table td.icon{
  background-size: 35px;
  background-position: left 5px center;
  background-repeat: no-repeat;
  padding-left: 30px;
}

.fa-female{
  color: red;
  margin-right: 10px;
}

.fa-male{
  color: blue;
  margin-right: 10px;
}

.btn-flat-bottom-border {
  position: relative;
  display: inline-block;
  font-weight: bold;
  padding: 7px 10px 10px 10px;
  text-decoration: none;
  color: #FFF;
  background: #b3b3b3;
  transition: .4s;
  margin: 0px 30px;
}

.btn-flat-bottom-border > span {
  border-bottom: solid 2px #fff;
}

.btn-flat-bottom-border:hover {
  background: #ed9097;
}

.graph-title{
  display:block;
  text-align: center;
}

.include-header{
  background:#fff;
  box-shadow:0 2px 8px rgba(30,30,80,.3);
  left:0;
  line-height:1;
  position:fixed;
  top:0;
  width:100%;
  z-index:24;
  height: 60px;
  opacity: 0.7;
}
</style>
</head>

<body class="drawer drawer--left">
<!-- グラフ作成ライブラリ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>

<!-- 共通header部 -->
<?php include("./inc/header.php"); ?>

<!-- Main部 (画面固有処理を移行に実装してください-->
<div class="container">
  <!-- 家事結果集計グラフ（合計） -->
  <h2>家事結果集計グラフ（合計）</h2>
  <div class="inquery-result-graph-all">
    <table id="chart-table">
      <tr>
        <td id="all-graph-td"><canvas id="chart-all"></canvas></td>
        <td id="all-detail-td">
          <table id="detail-table">
          <tr>
            <th></th>
            <th><i class="fas fa-male"></i><?php echo $histry_ary[0]["nickname"]?></th>
            <th><i class="fas fa-female"></i><?php echo $histry_ary[1]["nickname"]?></th>
          </tr>
          <tr>
            <th>実施回数</th>
            <td><?php echo $histry_ary[0]["histry_count"]?></td>
            <td><?php echo $histry_ary[1]["histry_count"]?></td>
          </tr>
          <tr>
            <th>合計金額</th>
            <td>￥<?php echo $histry_ary[0]["money_sum"]?></td>
            <td>￥<?php echo $histry_ary[1]["money_sum"]?></td>
          </tr>
          <tr id="diff-tr">
            <th>差分金額</th>
            <td class="diff-money">￥<?php echo $diff_money_0?></td>
            <td class="diff-money">￥<?php echo $diff_money_1?></td>
          </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>


  <!-- 家事結果集計グラフ（詳細） -->
  <h2>家事結果集計グラフ（詳細）</h2>
  <section class="inquery-result-graph slider">
  <?php
    foreach ($categry_ary0 as $key => $value) {

      if($value["count_id"] == 0 && $categry_ary1[$key]["count_id"] == 0){
        continue;
      }

      echo '<p class="graph-container">';
      echo '<span class="graph-title">'.$value["category_name"].'</span>';
      echo '<canvas id="chart-'.$key.'"></canvas>';
      echo "</p>";
    }
  ?>
  </section>

  <!-- 家事実績照会部 -->
  <h2>実績照会</h2>
  <div id="inquery-history">
    <table id="detail-table">
      <tr>
        <th>ユーザー名</th>
        <th>カテゴリ</th>
        <th>単金</th>
        <th>登録日時</th>
        <th></th>
      </tr>
  <?php
    foreach($histry_all_ary as $key => $value){
      echo '<tr>';
      echo '<td class="CENTER">'.$value["nickname"].'</td>';
      echo '<td class="CENTER">'.$value["category_name"].'</td>';
      echo '<td class="CENTER">'.$value["money"].'</td>';
      echo '<td class="CENTER">'.$value["insert_date"].'</td>';
      echo '<td class="CENTER">';
      echo '<a href="#" onclick="OnDELClick('.$value["history_id"].');" class="btn-flat-bottom-border">';
      echo '<span>DELETE</span>';
      echo '</a>';
      echo '</td>';
      echo '</tr>';
    }
  ?>

    </table>
  </div>
</div>

<script type="text/javascript">
    $(function(){
      //ヘッダー共通処理（ドロワー）
      $('.drawer').drawer();

      $(".inquery-result-graph").slick({
          dots: true, //点の表示
          autoplay: false, //自動切り替え
          autoplaySpeed: 5000, //自動切り替えにかかる時間
          adaptiveHeight:true,
          slidesToShow:4,
          infinite: false,
          initialSlide:1,
      });
  });

  function OnDELClick(history_id){
    var room_id = <?php echo json_encode($_SESSION["room_id"]); ?>;

    $.ajax({
      url:'model_inquery_delete.php',
      type:'POST',
      data:{
        'history_id': history_id,
        'room_id':room_id
      }
    })
    .done((data) => {
      $("#inquery-history").html(data);
    })
    .fail((data) => {
      alert("ng");
    })
    .always((data) => {
      console.log(data);
    });

  }
  //グラフの作成メソッド(Chart.js)
  function create_graph(id,data){
    var ctx = document.getElementById(id).getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'doughnut',
      data: data,
      options: {
        legend: {
            display: true,

            labels: {
                display: false,
                fontColor: 'rgb(255, 99, 132)'
            }
          }
      }
    });
  }

<?php

  //処理概要：　カテゴリIDをキーとしてDBから取得した比較[ 元 ]と比較[ 対象 ]のデータをもとに
  //           グラフ作成に使用するスクリプトの生成を実施する
  //DB取得データ：　KEY：カテゴリID
  //             　VAL：カテゴリID, カテゴリ名, ユーザーID, 単金, カテゴリ別実施回数
  reset($categry_ary0);
  foreach ($categry_ary0 as $key => $value) {

    $count0_wk = $value["count_id"];
    $count1_wk = $categry_ary1[$key]["count_id"];

    if($count0_wk == 0 && $count1_wk == 0){
      continue;
    }

    echo 'var data_'.$key.' = {';
    echo 'labels: ["'.$histry_ary[0]["nickname"].'", "'.$histry_ary[1]["nickname"].'"],';
    echo 'datasets: [{';
    echo 'backgroundColor: [';
    echo '"#fbcb43",';
    echo '"#ff5722",';
    echo '],';
    echo 'data: ['.$count0_wk.', '.$count1_wk.']';
    echo '}]';
    echo '};';

    echo 'create_graph("chart-'.$key.'",data_'.$key.');';
  }

?>

var data_2 = {
    labels: ["<?php echo $histry_ary[0]["nickname"]?>", "<?php echo $histry_ary[1]["nickname"]?>"],
    datasets: [{
      backgroundColor: [
        "#fbcb43",
        "#ff5722",
      ],
      data: [<?php echo $parcent_0 ?>, <?php echo $parcent_1 ?>]
    }]
  };

  create_graph("chart-all",data_2)

</script>
</body>
</html>
