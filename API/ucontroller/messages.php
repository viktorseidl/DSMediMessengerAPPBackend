<?php
require_once BASE_DIR."/Classes/db.php"; // Include the database connection

include_once BASE_DIR."/Classes/ucontroller/MESSAGES.php";
include_once BASE_DIR."/Utils/CONNECTIONHEAD.php";           


function handleMessages($input, $case) {
    $DB = new DB();
    $conn= $DB->connect();

    $Result = [];
    $Admin = new MESSAGES();

    if($conn)
    {

        
        switch ($case) {
        case '1':               // Nachrichten auslesen, die an eine Person gehen
          
            $userName = $input->E[0]->user;
            $Result = $Admin->_getReceivedMessages($conn, $userName);
      
            break;
            
        case '2':               // Nachricht einfügen
                
            $userName = $input->E[0]->user;
            $messageObj = $input->E[1];
            $Result = $Admin->_sendMessage($conn, $messageObj,$userName);
                
            break;
        case '3':               // Nachrichten auslesen, die von einer Person gesendet wurden

            $userName = $input->E[0]->user;
            $Result = $Admin->_getSentMessages($conn, $userName);
            
            break;
        case '4':               // Anhänge runterladen
                
            $anhangID = $input->E[0]->anhangID;
            $anhangPos = $input->E[0]->anhangPos;

            $Result = $Admin->_getFullAnhangData($conn, $anhangID, $anhangPos);
           
            
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
