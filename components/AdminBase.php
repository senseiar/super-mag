<?php

abstract class AdminBase
{

    public static function checkAdmin()
    {
        $userId = User::checkLogged();

        $user = User::getUserById($userId);

        if($user['role'] == 'admin') {
            return true;
        } else {
            exit("Доступ запрещен");
        }

    }
    
}