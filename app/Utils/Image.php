<?php 

namespace App\Utils;

use \Exception;

class Image {

    public static function getImage($image) {

        $imagePath = __DIR__ . "/../../resources/images/$image";

        if (!file_exists($imagePath)) {
            throw new Exception("Imagem não encontrada", 404);
        }

        //Obtém o MIME type da imagem
        $imageMimeType = mime_content_type($imagePath);

        //MIME types permitidos
        $allowedMimeTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'];

        //Verifica se o MIME type é permitido
        if (!in_array($imageMimeType, $allowedMimeTypes)) {
            throw new Exception("Formato de imagem não suportado", 415);
        }

        $imageContent = file_get_contents($imagePath);

        return [$imageContent, $imageMimeType];
    }
}





