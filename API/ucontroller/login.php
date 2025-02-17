<?php
require_once BASE_DIR."/Classes/db.php"; // Include the database connection

include_once BASE_DIR."/Classes/ucontroller/LOGIN.php";
include_once BASE_DIR."/Utils/CONNECTIONHEAD.php";           



function handleLogin($input, $case) {


    $DB = new DB();
    $conn= $DB->connect();

    $Result = [];
  

    if($conn)
    {

        
        switch ($case) {
        case '1':               // Nachrichten auslesen, die an eine Person gehen

            $data = $input->E[0];
            $dbtype = sanitizeInput($data->dbtype ?? '');
            $user = sanitizeInput($data->user ?? '');
            $password = sanitizeInput($data->pass ?? '');
            $Admin = new LOGIN();
            $Result = $Admin->checkLoginData($dbtype, $conn, $user, $password);
            break;
            
        case '2':               // Nachricht einfügen
                
           
                
            break;
        case '3':               // Nachrichten auslesen, die von einer Person gesendet wurden

          
            
            break;
        case '4':               // Anhänge runterladen
        
            # code...
            break;
        case '5':
            # code...
            break;
        case '6':
            # code...
            break;        
            default:
            # No hacking no cryy...
            break;
        }
    
    
        // Return the messages
 
        if($Result!=false) {
        http_response_code(200);
        echo json_encode(["success" => true, "data" => $Result, "XRFC"=> CONNECTIONHEAD::getRandomConnector()]);
        }else{
        http_response_code(200);
        echo json_encode(["success" => true, "data" => [], "XRFC"=> CONNECTIONHEAD::getRandomConnector()]);

        }
    }else{
        http_response_code(500);
        throw new Exception("Error Processing Request", 1);
    }

}



?>
