<?php

class Logs extends Connection
{
    private $table = 'tbl_logs';
    public $pk = 'log_id';

    public function show()
    { 
        $user_id = $_SESSION['user']['id'];
        if($_SESSION['user_category'] == "A"){
            $param = "";
        }else{
            $param = "user_id='$user_id'";
        }
        
        $rows = array();
        $result = $this->select($this->table, '*', $param);
        $Users = new Users();
        while ($row = $result->fetch_assoc()) {
            $row['user'] = $Users->fullname($row['user_id']);
            $rows[] = $row;
        }
        return $rows;
    }

}
