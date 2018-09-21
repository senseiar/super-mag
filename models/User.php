<?php

class User
{
    public static function register ()
    {

    }

    public static function checkName($name) {
        if(strlen($name) >= 2 ) {
            return true;
        }
        return false;
    }

    public static function checkPassword($password) {
        if(strlen($password) >= 6 ) {
            return true;
        }
        return false;
    }

    public static function checkEmail ($email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
}