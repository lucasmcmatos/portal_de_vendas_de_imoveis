<?php


header("Content-type: application/json; charset=UTF-8");

file_put_contents("teste.txt", json_encode(["post" => $_POST]));


print json_encode([
    "url"=> "http://localhost/testeckeditor/teste.png"
]);

exit();



