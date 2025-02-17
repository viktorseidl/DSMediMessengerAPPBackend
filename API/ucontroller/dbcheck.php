<?php
require_once BASE_DIR."/Classes/db.php"; // Include the database connection

include_once BASE_DIR."/Classes/ucontroller/LOGIN.php";
require_once BASE_DIR."/Classes/CheckDb.php";
include_once BASE_DIR."/Utils/CONNECTIONHEAD.php";           
require BASE_DIR."/Classes/SetupDb.php";

function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}
function handleDBCheck($input, $case) {


    $data = $input->E[0];


    switch ($case) {
        case '1':   
          
            $dbname = sanitizeInput($data->dbname ?? '');
            $host = sanitizeInput($data->host ?? '');
            $dbnamepflege = sanitizeInput($data->dbnamepflege ?? '');
            $user = sanitizeInput($data->user ?? '');
            $password = sanitizeInput($data->pass ?? '');
            $result = false;
            $Setup = new SetupDb($host, $dbname, $dbnamepflege, $user, $password);
            $result = $Setup->checkDBCredentials();
           
        break;
        case '2':

            
            $Check = new CheckDb();
            $result = $Check->checkforConfig($data->dbtype);




            break;
        default:

            break;

        }

      
   
    
        // Return the messages
 
        if($result!=false) {
        http_response_code(200);
        echo json_encode(["success" => true, "data" => $result, "XRFC"=> CONNECTIONHEAD::getRandomConnector()]);
        }else{
        http_response_code(200);
        echo json_encode(["success" => true, "data" => [], "XRFC"=> CONNECTIONHEAD::getRandomConnector()]);

        }
  

}



?>
