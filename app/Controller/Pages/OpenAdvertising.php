<?php

namespace App\Controller\Pages;


require_once(__DIR__ . "/../../Model/Entity/Advertising.php");
require_once(__DIR__ . "/../../Model/Entity/Favorite.php");
require_once(__DIR__ . "/../../Session/Login.php");


require_once(__DIR__ . "/../../Utils/View.php");
require_once(__DIR__ . "/Page.php");


use \Exception;
use \DateTime;
use \PDO;


use \App\Model\Entity\Favorite as FavoriteEntity;
use \App\Model\Entity\Advertising as AdvertisingEntity;
use \App\Session\Login as SessionLogin;


use \App\Utils\View;


class OpenAdvertising extends Page{

    public static function render($request, $id)
    {

        $args = self::getOpenAdvertisingInformationById($id);

        $content = count($args) > 0 ?
            View::render("pages/open-advertising/index",  $args) :
            View::render("pages/open-advertising/error-message",  $args);

        return parent::renderPage("Informações do Anúncio", $content);
    }








    public static function getOpenAdvertisingInformationById($id){

        try {

            $args = [];

            $result = AdvertisingEntity::getOpenAdvertisingInformationsById($id);



            if (!$result["success"]) {
                return $args;
            }


            $advertisingsObjectsList = $result["value"];



            if ($advertisingsObjectsList->rowCount() == 0) {
                return $args;
            }


            $userIsLogged = SessionLogin::isLogged();

            $favorites = [];

            if ($userIsLogged) {

                $userId = SessionLogin::getLoggedUserInfo()["id"];

                $result = FavoriteEntity::getByUser($userId);


                if (!$result["success"]) {
                    return  $args;
                }


                $favoriteObjectsList = $result["value"];


                if ($favoriteObjectsList->rowCount() > 0) {
                    while ($favoriteObject = $favoriteObjectsList->fetchObject(FavoriteEntity::class)) {
                        array_push($favorites, $favoriteObject->advertising);
                    }
                }
            }



            $advertisingObject = $advertisingsObjectsList->fetch(PDO::FETCH_ASSOC);


            $address = [];

            $street = $advertisingObject["street"] ?? "";
            $number = $advertisingObject["number"] ?? "";
            $municipality = $advertisingObject["municipality"] ?? "";
            $state = $advertisingObject["state"] ?? "";



            if (!empty($street)) {
                array_push($address, $street);
            }

            if (!empty($number)) {
                array_push($address, $number);
            }

            if (!empty($municipality)) {
                array_push($address, $municipality);
            }

            if (!empty($state)) {
                array_push($address, $state);
            }


            $contactInformations =  $userIsLogged ? View::render("pages/open-advertising/contact-informations", [
                "phone" => $advertisingObject["phone"] ?? "",
                "whatsapp" => $advertisingObject["whatsapp"] ?? "",
                "instagram" => $advertisingObject["instagram"] ?? "",
                "email" => $advertisingObject["email"] ?? "",
            ]) : "";



            $args = [
                "imagePath" => $advertisingObject["imagePath"] ?? "",
                "title" =>  $advertisingObject["title"] ?? "",
                "businessValue" => number_format(($advertisingObject["businessValue"] ?? 0), 2, ',', '.'),
                "description" => $advertisingObject["description"] ?? "",
                "businessType" => $advertisingObject["businessTypeName"] ?? "",
                "publiDate" => (new DateTime($advertisingObject["publiDate"]))->format("d/m/Y") ?? "",
                "address" =>  implode(", ", $address) . ".",
                "name" => ($advertisingObject["firstName"] ?? "") . " " . ($advertisingObject["lastName"] ?? ""),
                "builtUpArea" => $advertisingObject["builtUpArea"] ?? "",
                "bathNumber" => $advertisingObject["bathNumber"] ?? "",
                "roomNumber" => $advertisingObject["roomNumber"] ?? "",
                "parkingSpaces" => $advertisingObject["parkingSpaces"] ?? "",
                "propertyTypeName" => $advertisingObject["propertyTypeName"] ?? "",
                "solarIncidence" => $advertisingObject["incidence"] ?? "",
                "furnished" => ($advertisingObject["furnished"] ?? "") ? "Mobiliado" : "Não mobiliado",
                "pool" => ($advertisingObject["pool"] ?? "") ? "Possui" : "Não possui",
                "barbecue" => ($advertisingObject["barbecue"] ?? "") ? "Possui" : "Não possui",
                "sportsCourt" => ($advertisingObject["sportsCourt"] ?? "") ? "Possui" : "Não possui",
                "contactInformations" => $contactInformations,
                "favoriteVisitorScript" => $userIsLogged ? "" : View::render("pages/open-advertising/favorite-visitor-script"),
                "favoriteButtonForm" => $userIsLogged ? View::render("pages/open-advertising/favorite-button-form", [
                    "checked" => in_array($advertisingObject["advertisingId"] ?? "", $favorites) ? "checked" : ""
                ]) : "",
                "isLogged" =>  $userIsLogged ? "true" : "false",
                "advertisingId" =>  $advertisingObject["advertisingId"] ?? "",
            ];

            return $args;
        } catch (Exception $e) {
            return [];
        }
    }
}
