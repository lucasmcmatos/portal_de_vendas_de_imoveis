<?php
namespace App\Controller\Pages;

require_once(__DIR__."/../../Utils/View.php");
require_once(__DIR__."/../../Session/Login.php");
require_once(__DIR__."/../../Model/Entity/User.php");

use \App\Utils\View;
use \App\Session\Login;
use \App\Model\Entity\User as UserEntity;

class Page {


    private static function getSideBar() {

        $userItems = Login::isCommonUser() ? View::render("components/sidebar/user-items") : "";

        $adminItems = Login::isAdmin() ? View::render("components/sidebar/admin-items") : "";

        $modeItems = Login::isMode() ? View::render("components/sidebar/mode-items", [
            "adminItems" => $adminItems
        ]) : "";


        return View::render("components/sidebar/index", [
            "userItems" => $userItems,
            "modeItems" => $modeItems,
            "adminItems" => $adminItems
        ]);
    }



    private static function getTopBar() {

        if(Login::isLogged()){

            $userInfo = Login::getLoggedUserInfo(); 

            $result = UserEntity::getUserById($userInfo["id"]);

            $noLoggedSuccess = (!$result["success"] || ($userObjectsList = $result["value"])->rowCount() == 0);

            if($noLoggedSuccess){
                $userFirstName = "";
                $userPhoto = "";
            } else {
                $userObject = $userObjectsList->fetchObject(UserEntity::class);
                $userFirstName = $userObject->firstName ?? "";
                $userPhoto = $userObject->photo ?? "";
            }

            $topbarRight = View::render("components/topbar/logged", [
                "userFirstName" => $userFirstName,
                "userPhoto" => $userPhoto
            ]);
        } else {

            $topbarRight = View::render("components/topbar/no-logged");
        }


        return View::render("components/topbar/index", [
            "topbarRight" => $topbarRight
        ]);

    }


    public static function renderPage($title, $content) {
        return View::render("pages/index", [
            "title" => $title,
            "content" => $content,
            "sidebar" => self::getSideBar(),
            "topbar" => self::getTopBar(),
        ]);
    }
}