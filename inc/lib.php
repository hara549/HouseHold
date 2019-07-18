<?php
/**
 * SQL発行用関数（埋め込み変数有）
 * 第一引数: SQL文字列
 * 第二引数：埋め込み変数（配列[文字列]）
 * 
 * 返り値：　詳細はQueryResultクラスを参照
 *
 * @param [type] $sql
 * @param [type] $ary
 * @return QueryResult
 */
function QueryPreSqlFnc($sql,$ary):QueryResult{
    //返却用クラス変数
    $_res = new QueryResult();

    try {
        //ローカル変数
        $_result_ary =array();

        //TODO: DB名を定数化
        $pdo = new PDO("mysql:dbname=household;charset=utf8;", "root");
        //SQL作成
        $st = $pdo->prepare($sql);
        $st->execute($ary);
        //SQLの発行
        while($result = $st->fetch(PDO::FETCH_ASSOC)){
            array_push($_result_ary,$result);
        }
        //データ取得結果および実行結果を格納
        $_res->set_result_ary($_result_ary);
        $_res->set_err_flg(0);

    } catch (PDOException $e) {
        //エラー情報を格納
        $_res->set_err_flg(1);
        $_res->set_common_err('データベースエラーです。管理者へ連絡してください。');
    }
    return $_res;
}

/**
 * SQL発行用関数（DELETE）
 * 第一引数: SQL文字列
 * 第二引数：埋め込み変数（配列[文字列]）
 * 
 * 返り値：　詳細はQueryResultクラスを参照
 *
 * @param [type] $sql
 * @param [type] $ary
 * @return QueryResult
 */
function QueryDel($sql,$sql_ck,$ary):QueryResult{
    //返却用クラス変数
    $_res = new QueryResult();

    try {
        //ローカル変数
        $_result_ary =array();

        //TODO: DB名を定数化
        $pdo = new PDO("mysql:dbname=household;charset=utf8;", "root");
        $st = $pdo->prepare($sql);
        $st->bindValue($ary);
        $st->execute();
      
      
        //更新後DBを再度全検索
        $st = $pdo->prepare("SELECT * FROM category_tbl WHERE room_id=?");
        $st->execute(array($room_id));

    } catch (PDOException $e) {
        //エラー情報を格納
        $_res->set_err_flg(1);
        $_res->set_common_err('データベースエラーです。管理者へ連絡してください。');
    }
    return $_res;
}
?>
