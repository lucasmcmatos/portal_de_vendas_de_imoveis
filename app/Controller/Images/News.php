<?php

namespace App\Controller\Images;

require_once(__DIR__ . "/../../Utils/Image.php");
require_once(__DIR__ . "/../../Http/Response.php");

use App\Utils\Image;
use App\Http\Response;

class News {

    public static function getNewsImage($imageName) {

        $image = "news/$imageName";
        
        list($imageContent, $imageMimeType) = Image::getImage($image);

        return new Response(200, $imageContent, $imageMimeType);
    }
}



