<?php
namespace App\Controller\Pages;

require_once(__DIR__."/../../Model/Entity/User.php");
require_once(__DIR__."/../../Utils/View.php");
require_once(__DIR__."/../../Core/UserValidation.php");
require_once(__DIR__."/Page.php");

use \App\Utils\View;
use \App\Model\Entity\User;
use \App\Core\UserValidation;

class RegisterUser extends Page {

    
    private static function renderUsers() {
        $content = "";
        $result = User::getUsers();

        if (!$result["success"]){
            return '';
        }

        $userList = $result["value"];

        while($objUser = $userList->fetchObject(User::class)) {
            // Show just normal users
            if ($objUser->privilege == 1) continue;

            $content .= View::render("pages/register-user/user-item", [
                "id" => $objUser->id,
                "fullname" => $objUser->fullname,
                "email" => $objUser->email,
                "cpf" => $objUser->cpf,
                "photo" => $objUser->photo, 
            ]);
        }

        return $content;
    }

    private static function getUserForm() {
        return View::render("pages/register-user/user-form");
    }

    private static function getEditForm() {
        return View::render("pages/register-user/edit-form");
    }

    public static function render() {
        $content = View::render("pages/register-user", [
            "userList" => self::renderUsers(),
            "userForm" => self::getUserForm(),
            "editForm" => self::getEditForm(),
        ]);
        return parent::renderPage("SGC - Cadastro de Usuário", $content);
    }

}