<?php



class DB {

    private $serverName; 
    // = "SERVER-DS-2016\SQLEXPRESS02"
    
    private $connectionOptions
    //  = [
    // "Database" => "Medicarehsw",
    // "Uid" => "sa",
    // "PWD" => "",
    // "CharacterSet" => "UTF-8"
    // ]
    ;

    private $host;
    private $database;
    private $databasepflege;
    private $username;
    private $password;


    // private $pdo;
    // private $stmt;
    // private $typ;



    public function __construct()
    {

        $configFile = __DIR__ . '/../config/config.json';

        if (!file_exists($configFile)) {
            die("Error: Database configuration file not found.");
        }

        $configData = file_get_contents($configFile);
        $config = json_decode($configData, true);

        if (!$config) {
            die("Error: Invalid database configuration file.");
        }

        // Ensure all required fields are present
        if (!isset($config['host'], $config['databaseVerwaltung'], $config['databasePflege'], $config['username'], $config['password'])) {
            die("Error: Missing database configuration parameters.");
        }

        $this->serverName = $config['host'];
  
        $this->database = $config['databaseVerwaltung'];
        $this->username = $config['username'];
        $this->password = $config['password'];

        $this->connectionOptions = [
        
        "Database" => $this->database,
        "Uid" =>  $this->username,
        "PWD" => $this->password,
        "CharacterSet" => "UTF-8"
        ];


        // Try to connect to the database
        try {
            $this->conn = sqlsrv_connect($this->serverName, $this->connectionOptions);
            if ($this->conn === false) {
               
                die(json_encode(["error" => "Database connection failed", "details" => sqlsrv_errors()]));
            } 
        } catch (Exception $e) {
            
            die(json_encode(["error" => "Database connection error", "details" => $e->getMessage()]));
        }


        // try {

            


        //     // Set the DSN (Data Source Name) for SQL Server
        //     $dsn = "sqlsrv:Server={$this->host};Database={$this->database}";

        //     // Create a PDO instance (this will throw an exception if the connection fails)
        //     $this->pdo = new PDO($dsn, $this->username, $this->password);
        //     $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //     $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // } catch (PDOException $e) {
        //     // If the connection fails, display an error message
        //     die("Database connection failed: " . $e->getMessage());
        // }
    }

    private $conn;

    public function connect($serverName = null, $connectionOptions = null) {

        $this->conn=null;

        if ($serverName!=null) {
            $this->serverName = $serverName;
        }
        if ($connectionOptions != null) {
            $this->connectionOptions = $connectionOptions;
        }

        try {
            $this->conn = sqlsrv_connect($this->serverName, $this->connectionOptions);
            if ($this->conn === false) {
                return false;
                die(json_encode(["error" => "Database connection failed", "details" => sqlsrv_errors()]));
            } 
        } catch (Exception $e) {
            return false;
            die(json_encode(["error" => "Database connection error", "details" => $e->getMessage()]));
        }
        return $this->conn;
}


}
?>
