<?php

namespace App\Utils;

class View {

    private static $vars = [];

    public static function init($vars = []) {
        self::$vars = $vars;
    }


    private static function getContent($view) {
        $filePath = __DIR__ . "/../../resources/$view.html";
        return file_exists($filePath) ? file_get_contents($filePath) : "";
    }

    
    public static function render($view, $vars = []) {
        //View Content
        $content = self::getContent($view);

        //Merge global variables with pages variables
        $vars = array_merge(self::$vars, $vars);

        $keys = array_keys($vars);
        
        $keys = array_map(function ($key) {
            return "{{".$key."}}";
        }, $keys);

        return str_replace($keys, array_values($vars), $content);
    }
}