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


require_once(__DIR__ . "/../app/Controller/Pages/Home.php");
require_once(__DIR__ . "/../app/Controller/Pages/Register.php");
require_once(__DIR__ . "/../app/Controller/Pages/Login.php");
require_once(__DIR__ . "/../app/Controller/Pages/ForgotPassword.php");
require_once(__DIR__ . "/../app/Controller/Pages/PlatformAdvertising.php");
require_once(__DIR__ . "/../app/Controller/Pages/OpenAdvertising.php");
require_once(__DIR__ . "/../app/Controller/Pages/MyAdvertisings.php");
require_once(__DIR__ . "/../app/Controller/Pages/FavoritesAdvertising.php");
require_once(__DIR__ . "/../app/Controller/Pages/NotFound.php");




// require_once(__DIR__ . "/../app/Controller/Pages/RegisterUser.php");

// require_once(__DIR__ . "/../app/Controller/Pages/Settings.php");
// require_once(__DIR__ . "/../app/Controller/Pages/News.php");
// require_once(__DIR__ . "/../app/Controller/Pages/RegisterNews.php");
// require_once(__DIR__ . "/../app/Controller/Pages/Preview.php");


require_once(__DIR__ . "/../app/Controller/Api/User.php");
require_once(__DIR__ . "/../app/Controller/Api/Advertising.php");
require_once(__DIR__ . "/../app/Controller/Api/State.php");
require_once(__DIR__ . "/../app/Controller/Api/Municipality.php");
require_once(__DIR__ . "/../app/Controller/Api/BusinessType.php");
require_once(__DIR__ . "/../app/Controller/Api/PropertyType.php");
require_once(__DIR__ . "/../app/Controller/Api/SolarIncidence.php");

// require_once(__DIR__ . "/../app/Controller/Api/News.php");


// require_once(__DIR__ . "/../app/Controller/Images/User.php");
// require_once(__DIR__ . "/../app/Controller/Images/News.php");


require_once(__DIR__ . "/../app/Controller/Images/Advertising.php");





use \App\Http\Response;
use \App\Http\Router;

use \App\Http\Middleware\Queue as MiddlewareQueue;

use \App\Core\Database;
use \App\Utils\View;

use \App\Controller\Pages\Home;
use \App\Controller\Pages\Register;
use \App\Controller\Pages\Login;
use \App\Controller\Pages\ForgotPassword;
use \App\Controller\Pages\PlatformAdvertising;
use App\Controller\Pages\OpenAdvertising;
use App\Controller\Pages\MyAdvertisings;
use App\Controller\Pages\FavoritesAdvertising;
use \App\Controller\Pages\NotFound;


use \App\Controller\Api\User as UserApi;
use \App\Controller\Api\Advertising as AdvertisingApi;
use \App\Controller\Api\State as StateApi;
use \App\Controller\Api\Municipality as MunicipalityApi;
use \App\Controller\Api\BusinessType as BusinessTypeApi;
use \App\Controller\Api\PropertyType as PropertyTypeApi;
use \App\Controller\Api\SolarIncidence as SolarIncidenceApi;
use App\Model\Entity\BusinessType;
use App\Model\Entity\PropertyType;

// use \App\Controller\Api\News as NewsApi;
// use \App\Controller\Images\User as UserImage;
use \App\Controller\Images\Advertising as AdvertisingImage;



define("URL", "http://localhost");



View::init([
    "URL" => URL,
]);



//Mudar para variáveis de ambiente
Database::config("localhost", "imobile_on", "root", "Batatafrita0132@");



$objRouter = new Router(URL);





//----------------------------------- Required Logout ---------------------------------//

$objRouter->get("/",  [
    "middlewares" => [
        "requireLogout",
    ],
    function () {
        return new Response(200, Home::render());
    }
]);


$objRouter->get("/register",  [
    "middlewares" => [
        "requireLogout",
    ],
    function () {
        return new Response(200, Register::render());
    }
]);




$objRouter->get("/login",  [
    "middlewares" => [
        "requireLogout",
    ],
    function () {
        return new Response(200, Login::render());
    }
]);



$objRouter->get("/forgot-password",  [
    "middlewares" => [
        "requireLogout",
    ],
    function () {
        return new Response(200, ForgotPassword::render());
    }
]);



$objRouter->get("/platform-advertising-out",  [
    "middlewares" => [
        "requireLogout",
    ],
    function ($request) {
        return new Response(200, PlatformAdvertising::render($request));
    }
]);




$objRouter->get("/open-advertising-out/{id}",  [
    "middlewares" => [
        "requireLogout",
    ],
    function ($request, $id) {
        return new Response(200, OpenAdvertising::render($request, $id));
    }
]);






//----------------------------------- Required Login -----------------------------------//

$objRouter->get("/home",  [
    "middlewares" => [
        "requireLogin",
    ],
    function () {
        return new Response(200, Home::render());
    }
]);


$objRouter->get("/platform-advertising",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request) {
        return new Response(200, PlatformAdvertising::render($request));
    }
]);



$objRouter->get("/open-advertising/{id}",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request, $id) {
        return new Response(200, OpenAdvertising::render($request, $id));
    }
]);



$objRouter->get("/my-advertisings",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request) {
        return new Response(200, MyAdvertisings::render($request));
    }
]);




$objRouter->get("/favorites",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request) {
        return new Response(200, FavoritesAdvertising::render($request));
    }
]);






$objRouter->get("/logout",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request) {
        return new Response(200, Login::setLogout($request));
    }
]);















// $objRouter->get("/",  [
//     "middlewares" => [
//         "requireLogout",
//     ],
//     function () {
//     return new Response(200, Login::render());
// }]);


// $objRouter->post("/",  [
//     function ($request){
//     return new Response(200, Login::setLogin($request), "application/json");
// }]);

// $objRouter->get("/logout",  [
//     function ($request){
//     return new Response(200, Login::setLogout($request));
// }]);


// $objRouter->get("/home",  [    
//     "middlewares" => [
//         "requireLogin",
//     ],
//     function (){
//     return new Response(200, Home::render());
// }]);


// $objRouter->get("/register-user",  [
//     "middlewares" => [
//         "requireLogin",
//         "requireAdmin",
//     ],
//     function (){
//     return new Response(200, RegisterUser::render());
// }]);



// $objRouter->get("/settings",  [
//     "middlewares" => [
//         "requireLogin",
//     ],
//     function (){
//     return new Response(200, Settings::render());
// }]);






// API SECTION


$objRouter->post("/api/register",  [
    "middlewares" => [
        "requireLogout",
    ],
    function ($request) {
        return new Response(200, UserApi::register($request), "application/json");
    }
]);


$objRouter->post("/api/login",  [
    "middlewares" => [
        "requireLogout",
    ],
    function ($request) {
        return new Response(200, UserApi::login($request), "application/json");
    }
]);



$objRouter->get("/api/platform-advertisings-cards",  [
    function ($request) {
        return new Response(200, AdvertisingApi::getPlatformAdvertisingsCardInformation($request), "application/json");
    }
]);



$objRouter->post("/api/register-advertising",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request) {
        return new Response(200, AdvertisingApi::register($request));
    }
]);



$objRouter->get("/api/states",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request) {
        return new Response(200, StateApi::get($request));
    }
]);




$objRouter->get("/api/municipalities/{state}",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request, $state) {
        return new Response(200, MunicipalityApi::getByState($request, $state));
    }
]);



$objRouter->get("/api/property-types",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request) {
        return new Response(200, PropertyTypeApi::get($request));
    }
]);




$objRouter->get("/api/solar-incidences",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request) {
        return new Response(200, SolarIncidenceApi::get($request));
    }
]);




$objRouter->get("/api/business-types",  [
    "middlewares" => [
        "requireLogin",
    ],
    function ($request) {
        return new Response(200, BusinessTypeApi::get($request));
    }
]);






// USER
// $objRouter->post("/api/user/register",  [
//     "middlewares" => [
//         "requireLogin",
//         "requireAdmin",
//     ],
//     function ($request){
//     return new Response(200, UserApi::register($request), "application/json");
// }]);



// $objRouter->post("/api/user/delete",  [
//     "middlewares" => [
//         "requireLogin",
//         "requireAdmin",
//     ],
//     function ($request) {
//         return new Response(200, UserApi::delete($request), "application/json");
//     }
// ]);



// $objRouter->post("/api/user/password", [
//     "middlewares" => [
//         "requireLogin"
//     ],
//     function ($request) {
//         return new Response(200, UserApi::editPassword($request), "application/json");
//     }
// ]);



// $objRouter->post("/api/user/random-password", [
//     "middlewares" => [
//         "requireLogin",
//         "requireAdmin",
//     ],
//     function ($request) {
//         return new Response(200, UserApi::setRandomPassword($request), "application/json");
//     }
// ]);



// $objRouter->post("/api/user/general", [
//     "middlewares" => [],
//     function ($request) {
//         return new Response(200, UserApi::editFields($request), "application/json");
//     }
// ]);



// $objRouter->get("/api/user/{id}", [
//     function ($request, $id) {
//         return new Response(200, UserApi::get($request, $id), "application/json");
//     }
// ]);










// ------------------------------------------- NEWS SECTION --------------------------------------------//


//------------------------- News Pages -------------------------//


// //Render News Page
// $objRouter->get("/news",  [
//     // "middlewares" => [
//     //     "requireLogin",
//     // ],
//     function (){
//     return new Response(200, News::render());
// }]);






// //-------------------------- News Api --------------------------//


// //----------------- Extern ----------------//

// //Get All Available News
// $objRouter->get("/api/all-available-news",  [
//     function ($request){
//     return new Response(200, NewsApi::getAllAvailable($request), "application/json");
// }]);


// //Get Featured Available News
// $objRouter->get("/api/featured-available-news",  [
//     function ($request){
//     return new Response(200, NewsApi::getFeaturedAvailable($request), "application/json");
// }]);



// //Get No Featured Available News
// $objRouter->get("/api/no-featured-available-news",  [
//     function ($request){
//     return new Response(200, NewsApi::getNoFeaturedAvailable($request), "application/json");
// }]);



// //----------------- Intern ----------------//

// //Get All News 
// $objRouter->get("/api/all-news",  [
//     "middlewares" => [
//         "requireLogin",
//     ],
//     function ($request){
//     return new Response(200, NewsApi::getAll($request), "application/json");
// }]);



// //Register News
// $objRouter->post("/api/news/register",  [
//     "middlewares" => [
//         "requireLogin",
//     ],
//     function ($request){
//     return new Response(200, NewsApi::register($request), "application/json");
// }]);


// //Edit News
// $objRouter->post("/api/news/edit", [
//     "middlewares" => [
//         "requireLogin",
//     ],
//     function ($request){
//     return new Response(200, NewsApi::edit($request), "application/json");
// }]);



// //Delete News
// $objRouter->post("/api/news/delete", [
//     "middlewares" => [
//         "requireLogin",
//     ],
//     function ($request){
//     return new Response(200, NewsApi::delete($request), "application/json");
// }]);



// //Setup Featured News
// $objRouter->post("/api/news/featured", [
//     "middlewares" => [
//         "requireLogin",
//     ],
//     function ($request){
//     return new Response(200, NewsApi::setFeatured($request), "application/json");
// }]);



// //Setup Visible News
// $objRouter->post("/api/news/visible", [
//     "middlewares" => [
//         "requireLogin",
//     ],
//     function ($request){
//     return new Response(200, NewsApi::setVisible($request), "application/json");
// }]);



// //Upload Image from CKEditor
// $objRouter->post("/api/fileuploader",  [
//     "middlewares" => [
//         "requireLogin",
//     ],
//     function ($request){
//     return new Response(200, NewsApi::uploadImage(), "application/json");
// }]);



// //Get News By Id
// $objRouter->get("/api/news/{id}",  [
//     "middlewares" => [
//         "requireLogin",
//     ],
//     function ($request, $id){
//     return new Response(200, NewsApi::getById($id), "application/json");
// }]);




// //-------------------------- News Image --------------------------//

// //Get News Image
// $objRouter->get("/image/news/{imageName}", [
//     function ($request, $imageName) {
//         return NewsImage::getNewsImage($imageName);
//     }
// ]);






// //-------------------------- Advertising Image --------------------------//



// Get News Image
 $objRouter->get("/image/advertising/{relativeImagePath}", [
     function ($relativeImagePath) {
        return AdvertisingImage::getAdvertisingImage($relativeImagePath);
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


if ($response->getCode() == 404) {
    $notFoundRes = new Response(404, NotFound::render());
    $notFoundRes->sendResponse();
    exit;
}

$response->sendResponse();
