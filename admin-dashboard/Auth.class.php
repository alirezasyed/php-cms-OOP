<?php
namespace AdminDashboard;

require_once realpath(dirname(__FILE__) . "/DataBase.php");
use DataBase\DataBase;

class Auth
{

    public function login()
    {
        require_once realpath(dirname(__FILE__) . "/../template/auth/login.php");
    }

    public function checkLogin($request)
    {
        if (empty($request['email']) || empty($request['password'])) {

            $this->redirectBack();

        } else if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {

            $this->redirectBack();
        }
        
        else {

            $db = new DataBase();

            $user = $db->select("SELECT * FROM `users` WHERE (`email` = ?); ", [$request['email']])->fetch();

            if ($user != null) {

                if(password_verify($request['password'] , $user['password'])) {
                    $_SESSION['user'] = $user['id'];
    
                    $this->redirect('admin');
                }
                else {
    
                    $this->redirectBack();
                }
            }

            else {

                $this->redirectBack();
            }
        }
    }

    public function register()
    {
        require_once realpath(dirname(__FILE__) . "/../template/auth/register.php");
    }


    protected function redirect($url)
    {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
        header("Location: " . $protocol . $_SERVER['HTTP_HOST'] . "/cms-php/" . $url);
    }

    protected function redirectBack()
    {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}