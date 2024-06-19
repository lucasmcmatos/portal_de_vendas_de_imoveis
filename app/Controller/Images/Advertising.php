<?php

namespace App\Controller\Images;

require_once(__DIR__ . "/../../Utils/Image.php");
require_once(__DIR__ . "/../../Http/Response.php");

use App\Utils\Image;
use App\Http\Response;

class Advertising {

    public static function getAdvertisingImage($relativeImagePath) {

        $relativeImagePath = "advertisings/$relativeImagePath";
        
        list($imageContent, $imageMimeType) = Image::getImage($relativeImagePath);

        return new Response(200, $imageContent, $imageMimeType);
    }
}



