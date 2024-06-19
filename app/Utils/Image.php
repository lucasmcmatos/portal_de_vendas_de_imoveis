<?php 

namespace App\Utils;

use \Exception;

class Image {

    public static function getImage($relativeImagePath) {

        $completeImagePath = __DIR__ . "/../../resources/images/$relativeImagePath";

        if (!file_exists($completeImagePath)) {
            throw new Exception("Imagem não encontrada", 404);
        }

        //Obtém o MIME type da imagem
        $imageMimeType = mime_content_type($completeImagePath);

        //MIME types permitidos
        $allowedMimeTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'];

        //Verifica se o MIME type é permitido
        if (!in_array($imageMimeType, $allowedMimeTypes)) {
            throw new Exception("Formato de imagem não suportado", 415);
        }

        $imageContent = file_get_contents($completeImagePath);

        return [$imageContent, $imageMimeType];
    }
}





