<?php

namespace App\Controller\Api;


require_once(__DIR__ . "/../../Model/Entity/Advertising.php");
require_once(__DIR__ . "/../../Model/Entity/Favorite.php");
require_once(__DIR__ . "/../../Model/Entity/Address.php");
require_once(__DIR__ . "/../../Model/Entity/Property.php");

require_once(__DIR__ . "/../../Core/AddressValidation.php");

require_once(__DIR__ . "/../../Session/Login.php");


use \Exception;
use \DateTime;
use \PDO;
use \App\Model\Entity\User as UserEntity;
use \App\Model\Entity\Advertising as AdvertisingEntity;
use \App\Model\Entity\Favorite as FavoriteEntity;
use \App\Model\Entity\Address as AddressEntity;
use \App\Model\Entity\Property as PropertyEntity;


use \App\Core\AddressValidation;


use \App\Session\Login as SessionLogin;



class Advertising
{

    public static function getPlatformAdvertisingsCardInformation()
    {

        try {

            $result = AdvertisingEntity::getPlatformAdvertisingsCardInformation();

            if (!$result["success"]) {
                return  json_encode([
                    "success" => false,
                    "message" => "Não foi possível carregar os anúncios. Tente novamente mais tarde."
                ]);
            }


            $advertisingsObjectsList = $result["value"];



            if ($advertisingsObjectsList->rowCount() == 0) {
                return json_encode([
                    "success" => true,
                    "data" => [],
                    "message" => "Não há anúncios cadastrados no momento."
                ]);
            }



            $userIsLogged = SessionLogin::isLogged();

            $favorites = [];

            if ($userIsLogged) {

                $userId = SessionLogin::getLoggedUserInfo()["id"];

                $result = FavoriteEntity::getByUser($userId);


                if (!$result["success"]) {
                    return  json_encode([
                        "success" => false,
                        "message" => "Não foi possível carregar os anúncios. Tente novamente mais tarde."
                    ]);
                }


                $favoriteObjectsList = $result["value"];


                if ($favoriteObjectsList->rowCount() > 0) {
                    while ($favoriteObject = $favoriteObjectsList->fetchObject(FavoriteEntity::class)) {
                        array_push($favorites, $favoriteObject->advertising);
                    }
                }
            }





            $advertisings = [];


            while ($advertisingObject = $advertisingsObjectsList->fetch(PDO::FETCH_ASSOC)) {

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


                $AdvertisingsArray = [
                    "id" => $advertisingObject["id"] ?? "",
                    "image" => ($advertisingObject["imagePath"] ?? "") . "/imagem_1.jpeg",
                    "title" =>  $advertisingObject["title"] ?? "",
                    "businessValue" => number_format(($advertisingObject["businessValue"] ?? 0), 2, ',', '.'),
                    "description" => $advertisingObject["description"] ?? "",
                    "address" =>  implode(", ", $address) . ".",
                    "isLogged" => $userIsLogged ? true : false,
                    "favorite" => $userIsLogged ? in_array($advertisingObject["id"] ?? "", $favorites) : false
                ];


                array_push($advertisings, $AdvertisingsArray);
            }


            return json_encode([
                "success" => true,
                "data" =>  $advertisings,
            ]);
        } catch (Exception $e) {

            return json_encode([
                "success" => false,
                "message" => "Não foi possível carregar as notícias. Tente novamente mais tarde.",
            ]);
        }
    }




    

    public static function register($request){

       try {

        $formFields = $request->getPostVars();


        $addressValidation = AddressValidation::validateForm([
            "municipality" => $formFields["register-municipality"],
            "street" => $formFields["register-street"],
            "number" => $formFields["register-number"],
            "neighborhood" => $formFields["register-neighborhood"],
            "complement" => $formFields["register-complement"],
            "ZIPcode" => $formFields["register-ZIP-code"],
        ]);


        if (!$addressValidation["success"]) {
            return json_encode($addressValidation);
        }




        $addressObject = new AddressEntity();

        $addressObject->municipality = $formFields["register-municipality"];
        $addressObject->street = $formFields["register-street"];
        $addressObject->number = $formFields["register-number"];
        $addressObject->neighborhood = $formFields["register-neighborhood"];
        $addressObject->complement = $formFields["register-complement"];
        $addressObject->ZIPcode = $formFields["register-ZIP-code"];


        $result = $addressObject->insert();


        if ($result["success"] == false) {
            return json_encode([
                "success" => false,
                "message" => "Erro na conexão. Tente novamente."
            ]);
        }


        $addressConnection = $result["pdo"];


        $addressId = $addressConnection->lastInsertId();






        if (!isset($formFields["register-property-type"])) {
            return json_encode([
                "success" => false,
                "message" => "Selecione <strong>o tipo da propriedade</strong>."
            ]);
        }


        if (!isset($formFields["register-solar-incidence"])) {
            return json_encode([
                "success" => false,
                "message" => "Selecione a <strong>incidência solar</strong> da propriedade."
            ]);
        }




        $propertyObject = new PropertyEntity();

        $propertyObject->builtUpArea = floatval($formFields["register-built-up-area"]);
        $propertyObject->bathNumber = intval($formFields["register-bath-number"]);
        $propertyObject->roomNumber = intval($formFields["register-room-number"]);
        $propertyObject->parkingSpaces = intval($formFields["register-parking-spaces"]);
        $propertyObject->propertyType = intval($formFields["register-property-type"]);
        $propertyObject->solarIncidence = intval($formFields["register-solar-incidence"]);
        $propertyObject->furnished = isset($formFields["register-furnished"]) ? 1 : 0;
        $propertyObject->pool = isset($formFields["register-pool"]) ? 1 : 0;
        $propertyObject->barbecue = isset($formFields["register-barbecue"]) ? 1 : 0;
        $propertyObject->sportsCourt = isset($formFields["register-sports_court"]) ? 1 : 0;
        $propertyObject->address =  $addressId;



        $result = $propertyObject->insert();


        if ($result["success"] == false) {
            return json_encode([
                "success" => false,
                "message" => "Erro na conexão. Tente novamente."
            ]);
        }


        $propertyConnection = $result["pdo"];

        $propertyId = $propertyConnection->lastInsertId();



        if (SessionLogin::isLogged()) {
            $userId = SessionLogin::getLoggedUserInfo()["id"];
        } else {
            return json_encode([
                "success" => false,
                "message" => "Erro na conexão. Tente novamente."
            ]);
        }



        if (!isset($formFields["register-business-type"])) {
            return json_encode([
                "success" => false,
                "message" => "Selecione o <strong>tipo de negociação</strong>."
            ]);
        }




        $imagePath = self::str_rand(32);


        $uploadDir = __DIR__ . "/../../../resources/images/advertisings/";
        $newFolderName = $imagePath;
        $newFolderPath = $uploadDir . $newFolderName . '/';



        // Cria a nova pasta, se não existir
        if (!file_exists($newFolderPath)) {
            mkdir($newFolderPath, 0777, true);
        }


        $fileCount = count($_FILES['advertisings-images']['name']);


        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = $_FILES['advertisings-images']['name'][$i];
            $fileTmpName = $_FILES['advertisings-images']['tmp_name'][$i];
            $fileSize = $_FILES['advertisings-images']['size'][$i];
            $fileError = $_FILES['advertisings-images']['error'][$i];
            $fileType = $_FILES['advertisings-images']['type'][$i];



            // Verifica se não houve erros no upload
            if ($fileError === UPLOAD_ERR_OK) {
                $uploadFile = $newFolderPath . basename($fileName);

                if (!move_uploaded_file($fileTmpName, $uploadFile)) {
                    return json_encode([
                        "success" => false,
                        "message" => "Falha ao salvar arquivos."
                    ]);
                }
            } else {
                return json_encode([
                    "success" => false,
                    "message" => "Falha ao carregar os arquivos."
                ]);
            }
        }


        $adversitingObject = new AdvertisingEntity();

        $adversitingObject->user = $userId;
        $adversitingObject->property = $propertyId;
        $adversitingObject->title = $formFields["register-title"];
        $adversitingObject->description = $formFields["register-description"];
        $adversitingObject->imagePath = $imagePath;
        $adversitingObject->businessValue = floatval($formFields["register-business-value"]);
        $adversitingObject->publiDate = (new DateTime())->format("Y-m-d H:i:s");
        $adversitingObject->businessType = intval($formFields["register-business-type"]);
        $adversitingObject->businessStatus = 1;

        $result = $adversitingObject->insert();


        if ($result["success"] == false) {
            return json_encode([
                "success" => false,
                "message" => "Erro na conexão. Tente novamente."
            ]);
        }


      } catch (Exception $e) {
          return json_encode([
              "success" => false,
              "message" => "Algo inesperado ocorreu. Tente novamente."
          ]);
      }
    }



    public static function saveImage($tmpImg)
    {
        try {
            if ($tmpImg == "") {
                return "";
            }

            $randomFilename = self::str_rand(16);

            $imgPath = __DIR__ . "/../../public/profile/" . $randomFilename;
            move_uploaded_file($tmpImg, $imgPath);

            return $randomFilename;
        } catch (Exception $err) {
            return "";
        }
    }




    private static function str_rand(int $length = 64)
    {
        $length = ($length < 4) ? 4 : $length;
        return bin2hex(random_bytes(($length - ($length % 2)) / 2));
    }
}
