<?php
namespace App\Model\Entity;
require_once(__DIR__."/../../Core/Database.php");

use \App\Core\Database;
use Exception;
use PDOException;

class News {
    public $id;
    public $title;
    public $body;
    public $summary;
    public $photo;
    public $featured;
    public $date;
    public $schedule;
    public $author;
    public $visible;




    public function insert() {
        try {
            $success = (new Database("news"))->insert([
                "title" => $this->title,
                "body" => $this->body,
                "summary" => $this->summary,
                "photo" => $this->photo,
                "featured" => $this->featured,
                "date" => $this->date,
                "schedule" => $this->schedule,
                "author" => $this->author,
                "visible" => $this->visible,
            ]);

            return [
                "success" => $success,
                "message" => ""
            ];
        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }


    public function delete() {
        try {
            $success = (new Database("news"))->delete("id=?", [$this->id]);

            return [
                "success" => $success,
                "message" => ""
            ];
        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }


    static public function getAll() {

        try {
            if($objNews = (new Database("news"))->select(["*"], "", [], "ORDER BY schedule DESC")) {

                return [
                    "success" => true,
                    "message" => "",
                    "value" => $objNews,
                ];
            }

            return [
                "success" => false,
                "message" => "Falha ao encontrar notícias.",
            ];

        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    static public function getFeatured() {

        try {
            if($objNews = (new Database("news"))->select(["*"], "featured=?", [1], "ORDER BY schedule DESC")) {
                return [
                    "success" => true,
                    "message" => "",
                    "value" => $objNews,
                ];
            }

            return [
                "success" => false,
                "message" => "Falha ao encontrar notícias.",
            ];

        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    static public function getNoFeatured() {

        try {
            if($objNews = (new Database("news"))->select(["*"], "featured=?", [0], "ORDER BY schedule DESC")) {
                return [
                    "success" => true,
                    "message" => "",
                    "value" => $objNews,
                ];
            }

            return [
                "success" => false,
                "message" => "Falha ao encontrar notícias.",
            ];

        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    static public function getById($id) {
        try {
            if ($objNews = (new Database("news"))
                ->select(["*"], "id=?", [$id])) {
                
                return [
                    "success" => true,
                    "value" => $objNews,
                    "message"  =>  "",
                ];
            }

            return [
                "success" => false,
                "message"  =>  "Falha ao encontrar notícia.",
            ];

        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    public static function updateFields($id, $columns) {

        try {
            $result = (new Database("news"))->update($id, $columns);
            return [
                "success" => $result,
                "message" => "",
            ];
        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    public static function isUnique($field, $value) {

        try {
            if($result = (new Database("news"))->select(["*"], "$field=?", [$value])) {
                return [
                    "success" => true,
                    "value" => $result->rowCount() == 0,
                    "message" => "",
                ];
            }

            return [
                "success" => false,
                "message" => "",
            ];
        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }
}