<?php
/**
 *
 */
class adminModel extends Model
{
    public function login($name, $password)
    {
        $where = ['login' => trim($name)];
        $admin = $this->selectOne($where);
        if ($admin && $admin['password'] != md5($password)) {
            return false;
        }
        return $admin;
    }
}
