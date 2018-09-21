<?php 

class UserController
{
    public function actionRegister()
    {
        $name = '';
        $email = '';
        $password = '';

        if (isset ($_POST['submit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = false;

            if (User::checkName($name)) {
                echo '<br>$name oк!';
            } else { 
                $errors[] = '' ;
            }
            if (User::checkName($email)) {
                echo '<br>$name oк!';
            } else { 
                $errors[] = '';
            }
            if (User::checkName($password)) {
                echo '<br>$name oк!';
            } else { 
                $errors[] = '';
            }
        }
        
        
        require_once(ROOT. '/views/user/register.php');

        return true;
    }
}