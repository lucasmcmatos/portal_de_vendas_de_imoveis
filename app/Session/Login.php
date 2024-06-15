<?php
namespace App\Session;

use Exception;

class Login {


    private static function init() {
        
        if(session_status() !== PHP_SESSION_ACTIVE){

            self::my_session_start();
            self::my_session_regenerate_id();
        }
    }


    private static function my_session_start() {
        $sessionName = sha1("imobile_on" . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        session_name($sessionName);
        session_start();

        if (isset($_SESSION['destroyed'])) {

            unset($_SESSION["user"]);
            setcookie($sessionName, "", time() - 3600, "/");
            throw new Exception("Sessão expirada", 403);
       }

    }



    private static function my_session_regenerate_id() {

        $sessionName = sha1("imobile_on" . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

        $sessionAttributes = $_SESSION;

        if (isset($_SESSION["user"])) unset($_SESSION["user"]);

        $_SESSION["destroyed"] = time();

        $newSessionId = session_create_id();

        session_commit();
        setcookie($sessionName, "", time() - 3600, "/");

        session_id($newSessionId);
        ini_set("session.use_strict_mode", 0);
        session_name($sessionName);
        session_start();

        $_SESSION = $sessionAttributes;

        session_commit();
        ini_set("session.use_strict_mode", 1);
        session_name($sessionName);
        session_start();

    }


    public static function login($userObject) {
        self::init();

        $_SESSION["user"] = [
            "id" => $userObject->id ?? "",
            "firstName" => $userObject->firstName ?? "",
            "lastName" => $userObject->lastName ?? "",
            "email" => $userObject->email ?? "",
            "cpf" => $userObject->cpf ?? "",
            "photo" => $userObject->photo ?? "",
            "privilege" => $userObject->privilege ?? "",
        ];
    }



    public static function getLoggedUserInfo() {
        self::init();
        return $_SESSION["user"] ?? "";
    }



    public static function isAdmin() {
        self::init();
        return self::isLogged() && $_SESSION["user"]["privilege"] == 2;
    }



    public static function isMode() {
        self::init();
        return self::isLogged() && $_SESSION["user"]["privilege"] >= 1;
    }



    public static function isCommonUser() {
        self::init();
        return self::isLogged() && $_SESSION["user"]["privilege"] >= 0 ;
    }




    public static function logout() {

        $sessionName = sha1("imobile_on" . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

        self::init();
        unset($_SESSION["user"]);
        $_SESSION['destroyed'] = time();
        setcookie($sessionName, "", time() - 3600, "/");

    }



    public static function isLogged() {
        self::init();
        return isset($_SESSION["user"]["id"]) && !empty($_SESSION["user"]["id"]);
    }
}

