<?php 

class CheckDb {

    
    public function checkforConfig($dbtype = null)
    {
       
        $configFile = BASE_DIR . '/Config/config.json';
        if (file_exists($configFile)) {
            $configData = file_get_contents($configFile);
            $config = json_decode($configData, true);
            if(!array_key_exists('databaseVerwaltung', $config) || !array_key_exists('databasePflege', $config) || !array_key_exists('host', $config) || !array_key_exists('username', $config) || !array_key_exists('password', $config))
                {
                    return "malformedConfig";
                }
            $dbnameV = $config['databaseVerwaltung'];
            $dbnameP = $config['databasePflege'];
            
            if(strlen($dbnameV) > 0) {
                if (strlen($dbnameP > 0) || $dbtype != "P") {
                    return true;
                }
                elseif ($dbtype == "P") {
                    return "noPflege";
                }
            } else {
                return "noVerwaltung";
            }
        } else {
            return "noConfig";
        }
    }
    
}



?>