<?php 
class QueryResult
{
    //検索結果
    private $result_ary = null;

    //エラーコメント
    private $common_err = null;

    //エラーフラグ（０：正常　１：異常）
    private $err_flg = 0;
    

    public function get_result_ary()
    {
        return $this->result_ary;
    }

    public function set_result_ary($result_ary)
    {
        $this->result_ary = $result_ary;
        return true;
    }

    public function get_common_err()
    {
        return $this->common_err;
    }

    public function set_common_err($common_err)
    {
        $this->common_err = $common_err;
        return true;
    }

    public function get_err_flg()
    {
        return $this->err_flg;
    }

    public function set_err_flg($err_flg)
    {
        $this->err_flg = $err_flg;
        return true;
    }
}
?>