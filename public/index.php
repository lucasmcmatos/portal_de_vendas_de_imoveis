<?php


require_once(__DIR__ . "/../app/Http/Request.php");
require_once(__DIR__ . "/../app/Http/Response.php");
require_once(__DIR__ . "/../app/Http/Router.php");


require_once(__DIR__ . "/../app/Http/Middleware/Queue.php");
require_once(__DIR__ . "/../app/Http/Middleware/Maintenance.php");
require_once(__DIR__ . "/../app/Http/Middleware/RequireLogout.php");
require_once(__DIR__ . "/../app/Http/Middleware/RequireLogin.php");
require_once(__DIR__ . "/../app/Http/Middleware/RequireAdmin.php");


require_once(__DIR__ . "/../app/Core/Database.php");
require_once(__DIR__ . "/../app/Utils/View.php");


require_once(__DIR__ . "/../app/Controller/Pages/Register.php");
require_once(__DIR__ . "/../app/Controller/Pages/Login.php");
require_once(__DIR__ . "/../app/Controller/Pages/RegisterUser.php");
require_once(__DIR__ . "/../app/Controller/Pages/NotFound.php");
require_once(__DIR__ . "/../app/Controller/Pages/Settings.php");
require_once(__DIR__ . "/../app/Controller/Pages/News.php");
require_once(__DIR__ . "/../app/Controller/Pages/RegisterNews.php");
require_once(__DIR__ . "/../app/Controller/Pages/Preview.php");


require_once(__DIR__ . "/../app/Controller/Api/User.php");
require_once(__DIR__ . "/../app/Controller/Api/News.php");


require_once(__DIR__ . "/../app/Controller/Images/User.php");
require_once(__DIR__ . "/../app/Controller/Images/News.php");





use \App\Http\Response;
use \App\Http\Router;

use \App\Http\Middleware\Queue as MiddlewareQueue;

use \App\Core\Database;
use \App\Utils\View;


use \App\Controller\Pages\Register;
use \App\Controller\Pages\Login;
use \App\Controller\Pages\RegisterUser;
use \App\Controller\Pages\NotFound;
use \App\Controller\Pages\Settings;
use \App\Controller\Pages\News;


use \App\Controller\Api\User as UserApi;
use \App\Controller\Api\News as NewsApi;


use \App\Controller\Images\User as UserImage;
use \App\Controller\Images\News as NewsImage;



define("URL", "http://localhost");



View::init([
    "URL" => URL,
]);



//Mudar para variáveis de ambiente
Database::config("localhost", "imobile_on", "root", "Batatafrita0132@");



$objRouter = new Router(URL);




$objRouter->get("/register",  [
    "middlewares" => [
        "requireLogout",
    ],
    function () {
    return new Response(200, Register::render());
}]);


 







$objRouter->get("/",  [
    "middlewares" => [
        "requireLogout",
    ],
    function () {
    return new Response(200, Login::render());
}]);


$objRouter->post("/",  [
    function ($request){
    return new Response(200, Login::setLogin($request), "application/json");
}]);

$objRouter->get("/logout",  [
    function ($request){
    return new Response(200, Login::setLogout($request));
}]);


// $objRouter->get("/home",  [    
//     "middlewares" => [
//         "requireLogin",
//     ],
//     function (){
//     return new Response(200, Home::render());
// }]);


$objRouter->get("/register-user",  [
    "middlewares" => [
        "requireLogin",
        "requireAdmin",
    ],
    function (){
    return new Response(200, RegisterUser::render());
}]);



$objRouter->get("/settings",  [
    "middlewares" => [
        "requireLogin",
    ],
    function (){
    return new Response(200, Settings::render());
}]);






// API SECTION




$objRouter->post("/api/register",  [
    "middlewares" => [
        "requireLogout",
    ],
    function ($request){
        return new Response(200, UserApi::register($request), "application/json");
}]);

// USER
// $objRouter->post("/api/user/register",  [
//     "middlewares" => [
//         "requireLogin",
//         "requireAdmin",
//     ],
//     function ($request){
//     return new Response(200, UserApi::register($request), "application/json");
// }]);



$objRouter->post("/api/user/delete",  [
    "middlewares" => [
        "requireLogin",
        "requireAdmin",
    ],
    function ($request){
    return new Response(200, UserApi::delete($request), "application/json");
}]);



$objRouter->post("/api/user/password", [
    "middlewares" => [
        "requireLogin"
    ],
    function ($request) {
        return new Response(200, UserApi::editPassword($request), "application/json");
    }
]);



$objRouter->post("/api/user/random-password", [
    "middlewares" => [
        "requireLogin",
        "requireAdmin",
    ],
    function ($request) {
        return new Response(200, UserApi::setRandomPassword($request), "application/json");
    }
]);



$objRouter->post("/api/user/general", [
    "middlewares" => [

    ],
    function ($request) {
        return new Response(200, UserApi::editFields($request), "application/json");
    }
]);



$objRouter->get("/api/user/{id}", [
    function ($request, $id) {
        return new Response(200, UserApi::get($request, $id), "application/json");
    }
]);










// ------------------------------------------- NEWS SECTION --------------------------------------------//


//------------------------- News Pages -------------------------//


//Render News Page
$objRouter->get("/news",  [
    // "middlewares" => [
    //     "requireLogin",
    // ],
    function (){
    return new Response(200, News::render());
}]);






//-------------------------- News Api --------------------------//


//----------------- Extern ----------------//

//Get All Available News
$objRouter->get("/api/all-available-news",  [
    function ($request){
    return new Response(200, NewsApi::getAllAvailable($request), "application/json");
}]);


//Get Featured Available News
$objRouter->get("/api/featured-available-news",  [
    function ($request){
    return new Response(200, NewsApi::getFeaturedAvailable($request), "application/json");
}]);



//Get No Featured Available News
$objRouter->get("/api/no-featured-available-news",  [
    function ($request){
    return new Response(200, NewsApi::getNoFeaturedAvailable($request), "application/json");
}]);



//----------------- Intern ----------------//

//Get All News 
$objRouter->get("/api/all-news",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request){
    return new Response(200, NewsApi::getAll($request), "application/json");
}]);



//Register News
$objRouter->post("/api/news/register",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request){
    return new Response(200, NewsApi::register($request), "application/json");
}]);


//Edit News
$objRouter->post("/api/news/edit", [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request){
    return new Response(200, NewsApi::edit($request), "application/json");
}]);



//Delete News
$objRouter->post("/api/news/delete", [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request){
    return new Response(200, NewsApi::delete($request), "application/json");
}]);



//Setup Featured News
$objRouter->post("/api/news/featured", [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request){
    return new Response(200, NewsApi::setFeatured($request), "application/json");
}]);



//Setup Visible News
$objRouter->post("/api/news/visible", [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request){
    return new Response(200, NewsApi::setVisible($request), "application/json");
}]);



//Upload Image from CKEditor
$objRouter->post("/api/fileuploader",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request){
    return new Response(200, NewsApi::uploadImage(), "application/json");
}]);



//Get News By Id
$objRouter->get("/api/news/{id}",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request, $id){
    return new Response(200, NewsApi::getById($id), "application/json");
}]);




//-------------------------- News Image --------------------------//

//Get News Image
$objRouter->get("/image/news/{imageName}", [
    function ($request, $imageName) {
        return NewsImage::getNewsImage($imageName);
    }
]);
















// MIDDLEWARE MAPPING
MiddlewareQueue::setMap([
    "maintenance" => \App\Http\Middleware\Maintenance::class,
    "requireLogout" => \App\Http\Middleware\RequireLogout::class,
    "requireLogin" => \App\Http\Middleware\RequireLogin::class,
    "requireAdmin" => \App\Http\Middleware\RequireAdmin::class,
]);


MiddlewareQueue::setDefault([]);



$response = $objRouter->run();


if ($response->getCode() == 404)  {
    $notFoundRes = new Response(404, NotFound::render());
    $notFoundRes->sendResponse();
    exit;
}

$response->sendResponse();