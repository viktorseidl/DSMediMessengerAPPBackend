<?php

class Login
{

    private string $user;
    private string $passmd5;
    private string $dbtype;
    private string $dbnameV;
    private bool $hasPflege;
    private string $dbnameP;

    private $conn;

    public function __construct()
    {
       
        $configFile = BASE_DIR . '/Config/config.json';
        if (file_exists($configFile)) {
            $configData = file_get_contents($configFile);
            $config = json_decode($configData, true);
            $this->dbnameV = $config['databaseVerwaltung'];
            $this->dbnameP = $config['databasePflege'];
            $this->hasPflege = strlen($this->dbnameP) > 0;
        } else {
            die("Error: Database configuration not found.");
        }
    }
    // public function loginByApplikation(): mixed
    // {
    //     $result = $this->checkCredentialsOnTyp();
    //     return $result;

    // }

    public function checkLoginData($dbtype = null, $conn=null, $user=null, $password=null): mixed
    {
        $this->dbtype = $dbtype;
        $this->conn = $conn;
        $this->user = $user;
        $this->passmd5 = $password;

        if ($this->checkIfDatabasePflegeExists() && $dbtype == "P") {
            
            $result = $this->checkCredentialsOnTyp('pflege');
            if ($result != false) {
                return $result;
            } else {
                $result = $this->checkCredentialsOnTyp('verwaltung');
                if ($result != false) {
                    return $result;
                } else {
                    return false;
                }
            }
        } else {
            
            $result = $this->checkCredentialsOnTyp('verwaltung');
            if ($result != false) {
                return $result;
            } else {
                return false;
            }
        }
    }


    public function checkCredentialsOnTyp($typ = null): mixed
    {
        $params = [
            $this->user,
            $this->passmd5
        ];
        $sql = '';
        
        
            if (($typ == null && $this->dbtype == "pflege") || ($typ == "pflege")) {

                $sql = "SELECT distinct		
                    (Anwender) as [Name],
					Kennwort,
					Gruppe,
					'P' as usertypeVP, 
					(SELECT TRIM(Name2) + ' ' + TRIM(Name1) FROM ".$this->dbnameV.".[dbo].[Mitarbeiter] M WHERE MitarbeiterId = M.id) as Mitarbeitername
					FROM ".$this->dbnameP.".dbo.BerechtigungAnwender BerechtigungAnwender
					WHERE (gelöscht is null or gelöscht = 0 ) AND LOWER(Anwender) = LOWER(?) AND Kennwort=? AND ([deaktiviert]=0 OR deaktiviert IS NULL)
					AND MitarbeiterID not in ( SELECT M.id FROM Mitarbeiter M WHERE BeendigungDatum is not NULL )
					ORDER BY Anwender";
                
                
                
                
            //     "SELECT DISTINCT TOP 1 
            //     BA.Anwender as Name, BA.Kennwort, BA.Gruppe, 'P' as usertypeVP,(LTRIM(RTRIM(M.Name2)) + ' ' + LTRIM(RTRIM(M.Name1))) AS Mitarbeitername 
            //     FROM " . $this->dbnameP . ".[dbo].[BerechtigungAnwender] as BA 
            // LEFT JOIN " . $this->dbnameV . ".[dbo].[Mitarbeiter] as M ON BA.MitarbeiterID = M.ID WHERE BA.gelöscht != 0 AND M.BeendigungDatum IS NULL AND LOWER(BA.Anwender) = LOWER(?) AND BA.Kennwort = ? AND [deaktiviert] = 0;";
} else {
    $sql = "SELECT distinct [Anwender], ([Anwender]) as Mitarbeitername, NULL as Gruppe, [Kennwort], 'V' as usertypeVP FROM " . $this->dbnameV . ".[dbo].[BerechtigungAnwender] WHERE LOWER(Anwender) = LOWER(?) AND [Kennwort] = ?  AND (gelöscht is null or gelöscht = 0 ) AND (deaktiviert= 0 OR deaktiviert IS NULL);";
}


// Execute the query
$params = [$this->user, $this->passmd5]; 
$stmt = sqlsrv_query($this->conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true)); // Handle errors properly in production
}


// Fetch result
$result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    return $result ?: false;
}

    // public function checkIfDatabasePflegeExists(): bool
    // {
    //     $sql = "SELECT 1 AS DatabaseExists FROM sys.databases WHERE name = :dbname";

    //     $params = [
    //         ':dbname' => 'MedicarePflegehsw'
    //     ];
    //     $result = $this->pdo->query($sql, $params);
    //     if (!empty($result)) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function checkIfDatabasePflegeExists(): bool
{
    $sql = "SELECT 1 FROM sys.databases WHERE name = ?";
    $params = [$this->dbnameP];

    $stmt = sqlsrv_query($this->conn, $sql, $params);

    // if ($stmt === false) {
    //     die(print_r(sqlsrv_errors(), true)); // Handle error appropriately in production
    // }

    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) !== null;
}
}

?>