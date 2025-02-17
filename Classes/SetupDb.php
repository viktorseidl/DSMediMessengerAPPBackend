<?php

class SetupDb
{
    private $host;
    private $database;
    private $databaseV;
    private $databaseP;
    private $username;
    private $password;
    private $pdo;
    private $stmt;
    private $conn;

    public function __construct($host, $dbname, $dbnamepflege, $user, $password)
    {
        if (!isset($host, $dbname, $user)) {
            die("Error: Missing database configuration parameters.");
        }
        $this->host = $host;
        $this->database = 'master';
        $this->databaseV = $dbname;
        $this->databaseP = $dbnamepflege;
        $this->username = $user;
        $this->password = $password;
    }
    public function checkDBCredentials(): bool|string
    {
        // Connection options
        $connectionOptions = [
            "Database" => $this->database,
            "UID" => $this->username,
            "PWD" => $this->password
        ];

        // Try to connect to the database using sqlsrv
        $this->conn = sqlsrv_connect($this->host, $connectionOptions);

        if ($this->conn === false) {
            return "NO CONNECTION";
        }

        $vexists = (strlen($this->databaseV) > 0) ? $this->checkIfDatabaseVPExists($this->databaseV) : false;
        $pexists = (strlen($this->databaseP) > 0) ? $this->checkIfDatabaseVPExists($this->databaseP) : false;

        if ($vexists) {
            if ((strlen($this->databaseP) > 0) && (!$pexists)) {
                return "NO PFLEGE CONNECTION";
            }
            if ($this->createConfigJson([
                "host" => $this->host,
                "master" => $this->database,
                "databaseVerwaltung" => $this->databaseV,
                "databasePflege" => $this->databaseP,
                "username" => $this->username,
                "password" => $this->password
            ])) {
                return true;
            } else {
                return "FILE CREATION FAILED";
            }
        } else {
            return "NO CONNECTION";
        }
    }

    public function createConfigJson($data): bool|string
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        $filePath = __DIR__ . "/../Config/config.json";
        
        return file_put_contents($filePath, $jsonData) !== false ? true : 'FILE CREATION FAILED';
    }

    public function checkIfDatabaseVPExists($name): bool
    {
        $sql = "SELECT 1 AS DatabaseExists FROM sys.databases WHERE name = ?";
        $params = [$name];
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        
        if ($stmt === false) {
            return false;
        }
        
        $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return
        !empty($result);
    }


    public function checkforConfig($dbtype = null)
    {
       
        $configFile = BASE_DIR . '/Config/config.json';
        if (file_exists($configFile)) {
            $configData = file_get_contents($configFile);
            $config = json_decode($configData, true);
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