<?php

class LoginUser extends Connection
{
    private $table = 'tbl_users';
    public $pk = 'user_id';
    public $name = 'user_fullname';

    public function login()
    {

        $username = $this->inputs['username'];
        $password = $this->inputs['password'];

        $result = $this->select($this->table, "*", "username = '$username' AND password = md5('$password')");
        $row = $result->fetch_assoc();

        if ($row) {
            $_SESSION['status'] = "in";
            $_SESSION["user_fullname"] = $row['user_fullname'];
            $_SESSION["username"] = $row['username'];
            $_SESSION["user_category"] = $row['user_category'];
            $_SESSION['user']['id'] = $row['user_id'];
            $res = 1;
        } else {
            $res = 0;
        }

        // return $row[$this->name];

        return $res;
    }
    public function logout()
    {
        session_destroy();
        return 1;
    }
}
