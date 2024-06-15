<?php

namespace App\Controller\Api;

require_once(__DIR__."/../../Model/Entity/News.php");
require_once(__DIR__."/../../Session/Login.php");
require_once(__DIR__."/../../Core/NewsValidation.php");

use \Exception;
use \DateTime;
use \App\Model\Entity\News as NewsEntity;
use \App\Core\NewsValidation;
use \App\Session\Login;

class News {


    public static function getAll() {

        try {
            
            $result = NewsEntity::getAll();

            if (!$result["success"]){
                return  json_encode([
                    "success" => false,
                    "message" => "Não foi possível carregar as notícias. Tente novamente mais tarde."
                ]);
            }
    
    
            $newsList = $result["value"];
    
    
            if($newsList->rowCount() == 0) {
                return json_encode([
                    "success" => true, 
                    "data" => [],
                    "message" => "Não há notícias cadastradas no momento."
                ]);
            }
    
    
            $news = [];
            
            while($objNews = $newsList->fetchObject(NewsEntity::class)){
    
                $schedule = isset($objNews->schedule) ? (new \DateTime($objNews->schedule))->format("d/m/Y H:i:s") : "";
    
                $arrayNews = [
                    "id" => $objNews->id ?? "",
                    "photo" => (isset($objNews->photo) && boolval($objNews->photo)) ? "/image/news/" . $objNews->photo : "",
                    "title" => $objNews->title ?? "",
                    "author" => $objNews->author ?? "",
                    "schedule" =>  $schedule,
                    "featured" => (isset($objNews->featured) && boolval($objNews->featured)) ? true : false,
                    "featuredButtonClass" => "fa-" . ((isset($objNews->featured) && boolval($objNews->featured)) ? "solid" : "regular"),
                    "featuredButtonTitle" => (isset($objNews->featured) && boolval($objNews->featured)) ? "Remover dos destaques" : "Adicionar aos destaques",
                    "visible" => (isset($objNews->visible) && boolval($objNews->visible)) ? true : false,
                    "visibleButtonClass" => "fa-" . ((isset($objNews->visible) && boolval($objNews->visible)) ? "eye" : "eye-slash"),
                    "visibleButtonTitle" => (isset($objNews->visible) && boolval($objNews->visible)) ? "Ocultar" : "Tornar visível",
                ];
    
                array_push($news, $arrayNews);
            }
    
            return json_encode([
                "success" => true,
                "data" =>  $news,
            ]); 

        } catch (Exception $e) {

            return json_encode([
                "success" => false,
                "message" => "Não foi possível carregar as notícias. Tente novamente mais tarde.",
            ]); 
        }
        
    }



    public static function getAllAvailable() {

        try {

            $result = NewsEntity::getAll();

            if (!$result["success"]){
                return json_encode([
                    "success" => false,
                    "message" => "Não foi possível carregar as notícias. Tente novamente mais tarde.",
                ]);
            }


            $newsList = $result["value"];


            if($newsList->rowCount() == 0) {
                return json_encode([
                    "success" => true,
                    "data" => [],
                    "message" => "Não há notícias cadastradas no momento."
                ]);
            }


            $publishedNews = [];
        
            while($objNews = $newsList->fetchObject(NewsEntity::class)){
                 //show only published news
                if (strtotime($objNews->schedule) <= time() && $objNews->visible) {

                    $date = isset($objNews->schedule) ? (new \DateTime($objNews->schedule))->format("d/m/Y H:i:s") : "";

                    $arrayNews = [
                        "id" => $objNews->id ?? "",
                        "image" => (isset($objNews->photo) && boolval($objNews->photo)) ? "/image/news/" . $objNews->photo : "",
                        "title" => $objNews->title ?? "",
                        "summary" => $objNews->summary ?? "",
                        "category" => "Notícias",
                        "date" => $date
                    ];
                    array_push($publishedNews, $arrayNews);
                }
            }

            return json_encode([
                "success" => true,
                "data" =>  $publishedNews,
            ]);

        } catch (Exception $e) {
            return json_encode([
                "success" => false,
                "message" => "Não foi possível carregar as notícias. Tente novamente mais tarde.",
            ]);
        }
    
    }



    public static function getFeaturedAvailable() {

        try {

            $result = NewsEntity::getFeatured();

            if (!$result["success"]){
                return json_encode([
                    "success" => false,
                    "message" => "Não foi possível carregar os destaques. Tente novamente mais tarde.",
                ]);
            }

            $newsList = $result["value"];

            if($newsList->rowCount() == 0) {
                return json_encode([
                    "success" => true,
                    "data" => [],
                    "message" => "Não há destaques cadastrados no momento."
                ]);
            }


            $publishedFeaturedNews = [];
        
            while($objNews = $newsList->fetchObject(NewsEntity::class)){
                 //show only published and featured news
                if (strtotime($objNews->schedule) <= time() && $objNews->visible) {

                    $date = isset($objNews->schedule) ? (new \DateTime($objNews->schedule))->format("d/m/Y H:i:s") : "";

                    $arrayNews = [
                        "id" => $objNews->id ?? "",
                        "image" => (isset($objNews->photo) && boolval($objNews->photo)) ? "/image/news/" . $objNews->photo : "",
                        "title" => $objNews->title ?? "",
                        "summary" => $objNews->summary ?? "",
                        "category" => "Notícias",
                        "date" => $date
                    ];

                    array_push($publishedFeaturedNews,$arrayNews);
                }
            }

            return json_encode([
                "success" => true,
                "data" =>  $publishedFeaturedNews,
            ]);

        } catch (Exception $e) {
            return json_encode([
                "success" => false,
                "message" => "Não foi possível carregar os destaques. Tente novamente mais tarde.",
            ]);
        }
    
    }



    public static function getNoFeaturedAvailable() {

        try {

            $result = NewsEntity::getNoFeatured();

            if (!$result["success"]){
                return json_encode([
                    "success" => false,
                    "message" => "Não foi possível carregar as notícias. Tente novamente mais tarde.",
                ]);
            }

            
            $newsList = $result["value"];


            if($newsList->rowCount() == 0) {
                return json_encode([
                    "success" => true,
                    "data" => [],
                    "message" => "Não há notícias cadastradas no momento."
                ]);
            }


            $publishedNews = [];
        
            while($objNews = $newsList->fetchObject(NewsEntity::class)){
                 //show only published and no featured news
                if (strtotime($objNews->schedule) <= time() && $objNews->visible) {

                    $date = isset($objNews->schedule) ? (new \DateTime($objNews->schedule))->format("d/m/Y H:i:s") : "";

                    $arrayNews = [
                        "id" => $objNews->id ?? "",
                        "image" => (isset($objNews->photo) && boolval($objNews->photo)) ? "/image/news/" . $objNews->photo : "",
                        "title" => $objNews->title ?? "",
                        "summary" => $objNews->summary ?? "",
                        "category" => "Notícias",
                        "date" => $date
                    ];

                    array_push($publishedNews, $arrayNews);
                }
            }

            return json_encode([
                "success" => true,
                "data" =>  $publishedNews,
            ]);

        } catch (Exception $e) {
                return json_encode([
                    "success" => false,
                    "message" => "Não foi possível carregar as notícias. Tente novamente mais tarde.",
                ]);
        }

    }



    public static function getById($id) {
        try {

            $result = NewsEntity::getById($id);


            if (!$result["success"]){
                return json_encode([
                    "success" => false,
                    "message" => "Não foi possível carregar a notícia. Tente novamente mais tarde.",
                ]);
            }


            $news = $result["value"];


            if ($news->rowCount() == 0) {
                return [
                    "success" => false,
                    "message"  =>  "Notícia não encontrada.",
                ];
            }


            $objNews = $news->fetchObject(NewsEntity::class);


            $date = (isset($objNews->date) && boolval($objNews->date)) ? new DateTime($objNews->date) : "";
            $schedule = (isset($objNews->schedule) && boolval($objNews->schedule)) ? new DateTime($objNews->date) : "";

            if($date == "" || $schedule == ""){
                return json_encode([
                    "success" => false,
                    "message" => "Não foi possível acessar os dados da notícia. Tente novamente."
                ]);
            }
    
    
            if($schedule > $date){
                return [
                    "success" => false, 
                    "message" => "A <strong>data de agendamento</strong> não pode ser anterior à data atual."
                ];
            }


            $scheduleEqualDate = $schedule->format("Y-m-d H:i:s") === $date->format("Y-m-d H:i:s");
            $scheduleLessThanNow = $schedule < (new DateTime());


            return json_encode([
                "success" => true,
                "data" => [
                    "id" => $objNews->id ?? "",
                    "photo" => (isset($objNews->photo) && boolval($objNews->photo)) ? "/image/news/" . $objNews->photo : "",
                    "title" => $objNews->title ?? "",
                    "body" => $objNews->body ?? "",
                    "summary" => $objNews->summary ?? "",
                    "featured" => $objNews->featured ? true : false,
                    "scheduleCheckBox" => ($scheduleEqualDate || $scheduleLessThanNow) ? false : true,
                    "scheduleContainer" => ($scheduleEqualDate || $scheduleLessThanNow) ? "none" : "flex",
                    "schedule" => ($scheduleEqualDate || $scheduleLessThanNow) ? "" : (new DateTime($objNews->schedule))->format("Y-m-d\TH:i")
                ]
            ]);

        } catch (Exception $e) {
            return json_encode([
                "success" => false,
                "message" => "Algo inesperado ocorreu. Tente novamente.",
            ]);
        }
    }



    public static function register($request) {

        try {

            $formFields = $request->getPostVars();
    
            $photoTmpName = $_FILES["photo"]["tmp_name"] ?? "";
    
            $loggedUserInfo = Login::getLoggedUserInfo();
            $formFields["author"] = empty($loggedUserInfo) ? $loggedUserInfo : $loggedUserInfo["username"];
    
    
            if(!empty($photoTmpName)) {
               $formFields["photo"] = $photoTmpName;
            }

            $currentDate = new DateTime();
    
            $validation = NewsValidation::validateForm($formFields);
    
            if ($validation["success"] == false) {
                return json_encode($validation);
            }
    
            $objNews = new NewsEntity();
            $objNews->title = $formFields["title"];
            $objNews->body = $formFields["body"];
            $objNews->summary = $formFields["summary"];


            $objNews->date = $currentDate->format("Y-m-d H:i:s"); 

            $objNews->author = $formFields["author"];

            $objNews->schedule = empty($formFields["schedule"]) ? 
                $currentDate->format("Y-m-d H:i:s"):
                (new DateTime($formFields["schedule"]))->format("Y-m-d H:i:s");
    
            $objNews->featured = $formFields["featured"] == "true" ? 1 : 0;
            $objNews->visible = 1;
    
    
            $photoPath = self::saveImage($photoTmpName, __DIR__ . "/../../../resources/images/news");
    
    
            $objNews->photo = $photoPath == "" ? "news-default.png" : $photoPath;
    
            $result = $objNews->insert();
    
            if (!$result["success"]) {
                return json_encode([
                    "success" => false, 
                    "message" => "Erro na conexão. Tente novamente."
                 ]);
            }
    
            return json_encode([
                "success" => true, 
                "message" => "Notícia cadastrada com sucesso!", 
            ]);

        } catch (Exception $e) {

            return json_encode([
                "success" => false, 
                "message" => "Algo inesperado ocorreu. Tente novamente.", 
            ]);

        }
    }



    public static function edit($request) {
        try {

            var_dump($request->getPostVars());
            exit;


            if(!empty($photoTmpName)) {
               $formFields["photo"] = $photoTmpName;
            }

            $formFields = $request->getPostVars();

            $newsId = $formFields["newsId"];

            $photoTmpName = $_FILES["photo"]["tmp_name"] ?? "";

            $changedFields["title"] =  $formFields["title"];
            $changedFields["summary"] =  $formFields["summary"];
            $changedFields["body"] =  $formFields["body"];
            $changedFields["featured"] =  $formFields["featured"];
            $changedFields["schedule"] =  $formFields["schedule"];


            if (!empty($photoTmpName)) {
                $changedFields["photo"] = $photoTmpName;
            }

            // Validate
            $validation = NewsValidation::validateForm($changedFields);

            if(!$validation["success"]) {
                return json_encode($validation);
            }
            
            $changedFields["featured"] = $changedFields["featured"] == "true" ? 1 : 0;
            $changedFields["schedule"] = $changedFields["schedule"] ?? date("Y-m-d H:i:s");


            if ($photoPath = self::saveImage($photoTmpName, __DIR__ . "/../../../resources/images/news")) {
                $changedFields["photo"] = $photoPath;
            }

            $result = NewsEntity::updateFields($newsId, $changedFields);

            if (!$result["success"]) {
                return json_encode([
                    "success" => false,
                    "message" => "Erro na conexão. Tente novamente.",
                ]);
            }

            return json_encode(["success" => true, "message" => "Alterações efetuadas com sucesso!"]);

        } catch (Exception $err) {
            return json_encode([
                "success" => false,
                "message" => "Algo inesperado ocorreu. Tente novamente."
            ]);
        }
    }



    public static function delete($request) {

        try {
            $postVars = $request->getBodyVars();

            $objNews = new NewsEntity();
            $objNews->id = $postVars["id"];

            $result = $objNews->delete();

            if (!$result) {
                return json_encode([
                    "success" => false,
                    "message" => "Erro na conexão. Tente novamente."
                ]);
            }

            return json_encode(["success" => true, "message" => "Notícia excluída com sucesso!"]);
        } catch (Exception $e) {
            return json_encode(["success" => false, "message" => "Algo inesperado ocorreu. Tente novamente."]);
        }
    }



    public static function setFeatured($request) {
        $formFields = $request->getBodyVars();

        $featured = $formFields["featured"];


        $validation = NewsValidation::validateForm([
            "featured" => $featured,
        ]);



        if (!$validation["success"]) {
            return json_encode([
                "success" => false, 
                "message" => "Falha ao " . ($featured == "true" ? "adicionar notícia aos destaques." : "remover notícia dos destaques.") . " Tente novamente."
            ]);
        }

        $result = NewsEntity::updateFields($formFields["newsId"], [
            "featured" => $featured == "true" ? 1 : 0,
        ]);

        if(!$result["success"]){
            return json_encode([
                "success" => false, 
                "message" => "Erro na conexão. Tente novamente."
            ]);
        }


        return json_encode([
            "success" => true, 
            "message" => "Notícia " . ($featured == "true" ? "adicionada aos " : "removida dos ") . "destaques!",
        ]);


    }



    public static function setVisible($request) {
        $formFields = $request->getBodyVars();

        $visible = $formFields["visible"];


        $validation = NewsValidation::validateForm([
            "visible" => $visible,
        ]);



        if (!$validation["success"]) {
            return json_encode([
                "success" => false, 
                "message" => "Falha ao " . ($visible == "true" ? "tornar notícia visível." : "ocultar notícia.") . " Tente novamente."
            ]);
        }

        $result = NewsEntity::updateFields(
            $formFields["newsId"], [
            "visible" => $visible == "true" ? 1 : 0,
        ]);

        if(!$result["success"]){
            return json_encode([
                "success" => false, 
                "message" => "Erro na conexão. Tente novamente."
            ]);
        }


        return json_encode([
            "success" => true, 
            "message" => "Notícia " . ($visible == "true" ? "visibilizada com sucesso!" : "ocultada com sucesso!"),
        ]);


    }



    public static function uploadImage() {
        $tmpImg = $_FILES["upload"]["tmp_name"];

        $valid = NewsValidation::validateForm([
            "photo" => $tmpImg,
        ]);

        if (!$valid) {
            return json_encode([
                "message" => "test",
            ]);
        }

        $filename = self::saveImage(
            $tmpImg,
            __DIR__."/../../../public/profile"
        );

        return json_encode([
            "url" => "http://localhost/profile/".$filename,
        ]);
    }



    private static function saveImage($tmpImg, $path) {
        try {
            if ($tmpImg == "") {
                return "";
            }

            $randomPartName = self::str_rand(64);

            $validTypes = ["image/jpeg" => "jpeg" , "image/png" => "png", "image/webp" => "webp", "image/svg+xml" => "svg"];
            $mimeType = mime_content_type($tmpImg);
            $finalPartName = $validTypes[$mimeType];

            $name = "$randomPartName.$finalPartName";

    
            $imgPath = "$path/$name";
            move_uploaded_file($tmpImg, $imgPath);
    
            return $name;

        } catch (Exception $err) {
            return "";
        }
    }



    private static function str_rand(int $length = 64){ 
        $length = ($length < 4) ? 4 : $length;
        return bin2hex(random_bytes(($length-($length%2))/2));
    } 

}