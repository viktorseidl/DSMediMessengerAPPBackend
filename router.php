<?php
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //      ROUTER
    //      CHECKS CONNECTOR TOKEN AND ROUTES TO GIVEN PATH
    //      @author Sebastian Schaffrath
    //      
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL);
ini_set('display_errors', 1);

    
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

define('BASE_DIR', __DIR__);  //defined base direction for any called route to use

include_once "./API/ucontroller/messages.php";
include_once "./API/ucontroller/dbcheck.php";
include_once "./API/ucontroller/login.php";
include_once "./Utils/CONNECTIONHEAD.php";

// Get the HTTP method and request body
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"));

// Normalize the path to get only the route
$requestUri = $_SERVER['REQUEST_URI']; // Example: "/MessengerBackend/router.php/messages?:token"
$scriptName = $_SERVER['SCRIPT_NAME']; // Example: "/MessengerBackend/router.php"

// Remove the script name from the URI to get the route and get the token to validate the request
$basePath = str_replace($scriptName, '', $requestUri);

$token = substr($basePath, strpos($basePath, "?")+1);  

$path = trim(substr($basePath,0,strpos($basePath, "?")), "/")  ; // Example: "messages"

$subpath = explode("/",$path);


if(CONNECTIONHEAD::checkConnector($token))
{
    
    // Route handling
    if ($method === "POST" && $subpath[0] === "messages") {
        handleMessages($input, $subpath[1]);
    }elseif ($method === "POST" && $subpath[0] === "login") {
        handleLogin($input, $subpath[1]);
    }elseif ($method === "POST" && $subpath[0] === "dbcheck") {
        handleDBCheck($input, $subpath[1]);

    } else {
        // Return 404 for unknown routes
        http_response_code(404);
        echo json_encode(["error" => "Route not found"]);
    }
} else {
    http_response_code(500);
    echo json_encode(["error" => "Could not establish connection"]);
}

?>
