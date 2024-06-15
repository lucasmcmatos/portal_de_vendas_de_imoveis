<?php

namespace App\Controller\Api;

require_once(__DIR__ . "/../../Model/Entity/User.php");
require_once(__DIR__ . "/../../Core/UserValidation.php");
require_once(__DIR__ . "/../../Session/Login.php");

use \Exception;
use \DateTime;
use \App\Model\Entity\User as UserEntity;
use \App\Core\UserValidation;
use \App\Session\Login as SessionLogin;



class User {

    
    public static function register($request){

        try {
             
            $formFields = $request->getPostVars();


            $formFields["cpf"] = preg_replace("/[^0-9]/", "", $formFields["cpf"] ?? "");


            $validation = UserValidation::validateForm([
                "firstName" => $formFields["firstName"],
                "lastName" => $formFields["lastName"],
                "email" => $formFields["email"],
                "birthDate" => $formFields["birthDate"],
                "password" => $formFields["password"],
                "cpf" => $formFields["cpf"],
                "phone" => $formFields["phone"],
                "instagram" => $formFields["instagram"],
                "whatsapp" => $formFields["whatsapp"]
            ]);


            if (!$validation["success"]) {
                return json_encode($validation);
            }


            $objUser = new UserEntity();
            $objUser->firstName = ($formFields["firstName"] ?? "");
            $objUser->lastName = ($formFields["lastName"] ?? "");
            $objUser->email = $formFields["email"] ?? "";
            $objUser->birthDate = $formFields["birthDate"] ?? "";
            $objUser->password = password_hash($formFields["password"] ?? "", PASSWORD_ARGON2ID);
            $objUser->cpf = $formFields["cpf"] ?? "";
            $objUser->phone = $formFields["phone"] ?? "";
            $objUser->instagram = $formFields["instagram"] ?? "";
            $objUser->whatsapp = $formFields["whatsapp"] ?? "";
            $objUser->registerDate = (new DateTime())->format("Y-m-d H:i:s");
            $objUser->photo = "undraw-profile.svg";
            $objUser->privilege = 0;


            $success = $objUser->insert();

            if (!$success) {
                return json_encode([
                    "success" => false,
                    "message" => "Erro na conexão. Tente novamente."
                ]);
            }

            return json_encode([
                "success" => true,
                "message" => "Conta criada com sucesso!",
            ]);
        } catch (Exception $e) {
            return json_encode([
                "success" => false, 
                "message" => "Algo inesperado ocorreu. Tente novamente."
            ]);
        }
    }



    public static function login($request) {
        try {
            $postVars = $request->getPostVars();
        
            $identifier = $postVars["identifier"] ?? "";
            $password = $postVars["password"] ?? "";
    
            $result = UserEntity::getUserByIdentifier($identifier);
    
            if (!$result["success"]) {
                return json_encode([
                    "success" => false, 
                    "message" => "Erro na conexão. Tente novamente."]);
            }
      
    
            $userObjectsList = $result["value"];
    
    
            if ($userObjectsList->rowCount() == 0) {
                return [
                    "success" => false,
                    "message"  =>  "Email e/ou senha inválidos."
                ];
            }
    
    
            $userObject = $userObjectsList->fetchObject(UserEntity::class);
     
            if(!password_verify($password, $userObject->password)) {
                return json_encode([
                    "success" => false, 
                    "message" => "Email e/ou senha inválidos."
                ]);
            }
    
           SessionLogin::login($userObject);
    
            return json_encode([
                "success" => true, 
                "message" => "Usuário autenticado!"
            ]);

        } catch(Exception $e) {
            return json_encode([
                "success" => false, 
                "message" => "Algo inesperado ocorreu. Tente novamente."
            ]);
        }


    }



    public static function get($request, $id){
        try {
            $id = intval($id);

            $result = UserEntity::getUserById($id);

            if (!$result["success"]) {
                return [
                    "success" => false,
                    "message" => "Erro na conexão. Tente novamente",
                ];
            }

            $objUser = $result["value"];

            if (!$objUser) {
                return [
                    "success" => false,
                    "message" => "Usuário não encontrado."
                ];
            }

            return ["success" => true, "data" => $objUser];
        } catch (Exception $e) {
            return ["success" => false, "message" => "Algo inesperado ocorreu. Tente novamente."];
        }
    }


    public static function delete($request){

        try {
            $postVars = $request->getBodyVars();

            $objUser = new UserEntity();
            $objUser->id = $postVars["id"];

            $result = $objUser->deleteUser();
            if (!$result) {
                return [
                    "success" => false,
                    "message" => "Erro na conexão. Tente novamente."
                ];
            }

            return ["success" => true, "message" => "Usuário excluído com sucesso!"];
        } catch (Exception $e) {
            return ["success" => false, "message" => "Algo inesperado ocorreu. Tente novamente."];
        }
    }


    public static function editFields($request){

        try {
            $bodyVars = $request->getPostVars();
            $userId = $bodyVars["userId"];
            $photoFile = $_FILES["photo"]["tmp_name"] ?? "";

            $result = UserEntity::getUserById($userId);

            if (!$result["success"]) {
                return [
                    "success" => false,
                    "message" => "Erro na conexão. Tente novamente.",
                ];
            }

            $objUser = $result["value"];

            $formFields = [
                "fullname" => $bodyVars["fullname"],
                "username" => $bodyVars["username"],
                "email" => $bodyVars["email"],
                "cpf" =>  preg_replace("/[^0-9]/", "", $bodyVars["cpf"])
            ];

            // Getting just the changed fields
            $changedFields = [];
            foreach ($formFields as $field => $value) {
                if ($objUser->$field != $value) {
                    $changedFields[$field] = $value;
                }
            }

            if (!empty($photoFile)) {
                $changedFields["photo"] = $photoFile;
            }


            // Validation 
            $result = UserValidation::validateForm($changedFields);
            if (!$result["success"]) {
                return $result;
            }

            //  Save File Photo
            if ($photoPath = self::saveImage($photoFile)) {
                $changedFields["photo"] = $photoPath;
            }

            if (count($changedFields) == 0) {
                return [
                    "success" => false,
                    "message" => "Nenhuma nova informação foi fornecida."
                ];
            }


            $result = UserEntity::updateFields($userId, $changedFields);

            if (!$result["success"]) {
                return [
                    "success" => false,
                    "message" => "Erro na conexão. Tente novamente.",
                ];
            }

            return ["success" => true, "message" => "Usuário editado com sucesso!"];
        } catch (Exception $e) {
            return ["success" => false, "message" => "Algo inesperado ocorreu. Tente novamente."];
        }
    }


   /* public static function editPassword($request){

        try {

            $bodyVars = $request->getBodyVars();
            $userId = $bodyVars["userId"];
            $crrPass = $bodyVars["crrPass"];
            $newPass = $bodyVars["newPass"];

            $result = UserEntity::getUserById($userId);

            if (!$result["success"]) {
                return [
                    "success" => false,
                    "message" => "Erro na conexão. Tente novamente.",
                ];
            }

            $objUser = $result["value"];

            if (!password_verify($crrPass, $objUser->pass)) {
                return ["success" => false, "message" => "Senha atual incorreta."];
            }

            // Validate Password
            $valid = UserValidation::validateForm(["new-password" => $newPass]);
            if ($valid["success"] == false) {
                return $valid;
            }

            $newPass = password_hash($newPass, PASSWORD_ARGON2ID);
            $result = UserEntity::updatePassword($userId, $newPass);

            if (!$result["success"]) {
                return [
                    "success" => false,
                    "message" => "Erro na conexão. Tente novamente.",
                ];
            }

            SessionLogin::logout();

            return ["success" => true, "message" => "Senha alterada com sucesso!"];
        } catch (Exception $e) {
            return ["success" => false, "message" => "Algo inesperado ocorreu. Tente novamente."];
        }
}*/



    public static function setRandomPassword($request){

        try {

            $bodyVars = $request->getBodyVars();
            $userId = $bodyVars["userId"];

            $random_password = self::str_rand(30);
            $result = UserEntity::updatePassword($userId, $random_password);

            if (!$result["success"]) {
                return [
                    "success" => false,
                    "message" => "Erro na conexão. Tente novamente.",
                ];
            }

            return [
                "success" => true,
                "message" => "Senha gerada com sucesso!",
                "data" => ["password" => $random_password],
            ];
        } catch (Exception $e) {
            return ["success" => false, "message" => "Algo inesperado ocorreu. Tente novamente."];
        }
    }



    public static function saveImage($tmpImg){
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




    private static function str_rand(int $length = 64){
        $length = ($length < 4) ? 4 : $length;
        return bin2hex(random_bytes(($length - ($length % 2)) / 2));
    }


    //Refazer
    private static function sendEmail($email, $newPassword){

        //PREPARANDO OS DADOS A SEREM ENVIADOS
        $htmlData = "<!doctype html><html lang='pt-br'><head><meta charset='UTF-8'><style>";
        $htmlData .= "body{height: 100vh; width: 90vw; display: flex; flex-direction: column; align-items: flex-start; justify-content: center; row-gap: 40px; font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;} ";
        $htmlData .= "h1{font-size: 1.1em; font-weight: 600;} ";
        $htmlData .= "h2{font-size: 1em; font-weight: 200; border-bottom: 1px solid #000000; padding-bottom: 5px; width: 75%;} ";
        $htmlData .= "h3{font-size: 1em; font-weight: 200;} ";
        $htmlData .= "p{width: 70%; display: flex; align-items: center; height: 100%; font-size: 1.2vw; font-weight: 200; background-color: #e4e4e4; padding: 8px; box-sizing: border-box; border-radius: 8px;} ";
        $htmlData .= "strong{width: 30%; display: flex; align-items: center; height: 100%; font-size: 1.2vw; font-weight: 600; background-color: #036496; color: #ffffff; padding: 8px; box-sizing: border-box; border-radius: 8px;} ";
        $htmlData .= "a{font-size: 1em; font-weight: 200;} ";
        $htmlData .= ".linhaTabela{width: 100%; height: 12.5%; display: flex; flex-direction: row; column-gap: 8px; align-items: center; justify-content: space-between;} ";
        $htmlData .= "table{border-collapse: separate; text-indent: initial; border-spacing: 8px;} ";
        $htmlData .= "#tabela{width: 100%; display: table-row-group; vertical-align: middle; border-color: inherit;} ";
        $htmlData .= ".tdMenor{width: 30%; background-color: #036496; color: #ffffff; display: table-cell;  vertical-align: inherit; border-radius: 8px; padding: 8px; font-size: 0.9em;} ";
        $htmlData .= ".tdMaior{width: 70%; background-color: #e4e4e4; display: table-cell; vertical-align: inherit; border-radius: 8px; padding: 8px; font-size: 0.9em} ";
        $htmlData .= "tr{display: table-row; vertical-align: inherit; border-color: inherit;} ";
        $htmlData .= "</style></head><body>";
        $htmlData .= "<h1>SGC - SITE DE COMPRAS - SENHA TEMPORÁRIA</h1><h3>Inscrição realizada com sucesso!</h3><h2>INFORMAÇÕES REGISTRADAS</h2>";
        $htmlData .= "<table id='tabela'><tr><td class='tdMenor'>Senha temporária</td><td class='tdMaior'>$newPassword</td></tr></table>";
        $htmlData .= "<h3>Esta mensagem é automática, portanto não é necessário que seja respondida.</h3>";
        $htmlData .= "<h1>Governo do Estado do Maranhão - <a href='https://www.ma.gov.br/'>www.ma.gov.br</a></h1></body></html>";

        $dataEmail = '{
            "destinatarios": ["' . $email . '"],
            "assunto": "SENHA TEMPORÁRIA - SGC SITE DE COMPRAS",
            "corpo": "' . $htmlData . '"
            }';



        //ENVIO DO EMAIL
        //Inicia CURL
        $ch = curl_init();

        //Em false, para impedir que a CURL verifique o certificado.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        //Matriz cabeçalho HTTP
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Authorization:Basic YXBwLnNlbG8uZGl2ZXJzaWRhZGU6JDJhJDEyJEFra1Q5QVhmZnRLTDNQdGdvZTA4Q3V0ODN1T25pNVJZNUh5Ui56REUvZHM3ZlFzZmVqZ05P'
        ));

        //Acessa a URL de envio de email
        curl_setopt($ch, CURLOPT_URL, 'https://ext.api.email.seati.ma.gov.br/api/mensagens/enviar');

        //Envia dados POST
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataEmail);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //Captura retorno JSON
        $feedbackEmail = curl_exec($ch);

        //Encerra CURL
        curl_close($ch);

        $sentEmail = strpos($feedbackEmail, "Mensagem enviada com sucesso");


        if ($sentEmail) {
            return true;
        }
        return false;
    }
}
