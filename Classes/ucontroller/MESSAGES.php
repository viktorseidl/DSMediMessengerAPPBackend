<?php
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //      MESSAGES - CLASS TO HANDLE MESSAGES AND EVERYTHING RELATED
    //      @author Sebastian Schaffrath
    //      
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////

class MESSAGES {
    private $conn;



    public function _getReceivedMessages($connection = null, $userName = null)
    {
        $this->conn = $connection;
        $data = [];
        $sql = "SELECT EMail.ID,EMail.Datum,EMail.Grund_ID,EMail.Betreff,EMail.Nachricht,EMail.Sender,EMail.Empfänger,EMail.Erledigt,EMail.Wichtig,EMail.Anhang, EMail.gelöscht FROM EMail WHERE Empfänger=? AND Datum <= GETDATE() AND gelöscht IS NULL";
        $params = array(array($userName, SQLSRV_PARAM_IN));
        
        $stmt = sqlsrv_prepare($this->conn, $sql, $params);
        if ($stmt === false) {
            return false;

        }
        
        // Ausführen der vorbereiteten Abfrage
        if (sqlsrv_execute($stmt) === false) {
            return false;

        }
        
        $nameList = $this->fetchFullNames($this->conn);
        
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $row["Sender"] = trim($row["Sender"]);
                $row["Empfänger"] = trim($row["Empfänger"]);
                $row["Betreff"] = trim($row["Betreff"]);

            $row["anhaenge"] = "";
            if($row["Anhang"]!= 0) {
                $row["anhaenge"] =   $this->_getAnhangMetaData($this->conn,$row["Anhang"]);
            }

            $row["fullName"] = "";
           
            $row["Datum"] = $row["Datum"]->format(DateTime::ATOM); 
           
            foreach ($nameList as $element) {

                
                if ($element["Name"] == $row["Sender"])
                {
                    $row["fullName"] = $element["Mitarbeitername"];
                    break;
                }
            }

           
            $data[] = $row;
            
            
        };    
        sqlsrv_close($this->conn);
        
        return $data;
        
    
        
    }   
    

        public function fetchFullNames($conn) {
   
     
            $sql =  "SELECT distinct (Anwender) as [Name], Gruppe, (SELECT TRIM(Name2) + ' ' + TRIM(Name1) FROM Mitarbeiter M WHERE MitarbeiterId = M.id) as Mitarbeitername 
            FROM [MedicarePflegehsw].dbo.BerechtigungAnwender BerechtigungAnwender
            WHERE (gelöscht is null or gelöscht = 0 )
            AND MitarbeiterID not in ( SELECT M.id FROM Mitarbeiter M WHERE BeendigungDatum is not NULL )
            ORDER BY Anwender";    
        
        
            $stmt = sqlsrv_prepare($conn, $sql);
         
             if ($stmt === false) {
                 return false;
                 //die(print_r(sqlsrv_errors(), true));
             }     
             // Ausführen der vorbereiteten Abfrage
             if (sqlsrv_execute($stmt) === false) {
                return false; 
                 //die(print_r(sqlsrv_errors(), true));
             }    
             while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                 $data[] = $row;
             }    
         
        
             return $data;
        }     

        
        public function _getAnhangMetaData($connection = null, $anhangID = null ) {
    
            $this->conn = $connection;
            
    
            $sql="SELECT EMail_Anhang.ID,EMail_Anhang.Pos,EMail_Anhang.Name as name FROM EMail_Anhang WHERE EMail_Anhang.ID=?";
            $params = array([$anhangID, SQLSRV_PARAM_IN]);
            $stmt = sqlsrv_prepare($this->conn, $sql, $params);
        
        
            if ($stmt === false) {
                return false;
            }
            
            // Ausführen der vorbereiteten Abfrage
            if (sqlsrv_execute($stmt) === false) {
                return false;
            }
    
       
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    
                $data[] = $row;
    
            };
            return $data;
        
         }
    

     public function _getSentMessages($connection = null, $userName = null)
     {
         $this->conn = $connection;
         $data = [];
         $sql = "SELECT EMail.ID,EMail.Datum,EMail.Grund_ID,EMail.Betreff,EMail.Nachricht,EMail.Sender,EMail.Empfänger,EMail.Erledigt,EMail.Wichtig,EMail.Anhang, EMail.gelöscht FROM EMail WHERE Sender=?  AND Datum <= GETDATE() AND gelöscht IS NULL";
         $params = array(array($userName, SQLSRV_PARAM_IN));
         
         $stmt = sqlsrv_prepare($this->conn, $sql, $params);
         if ($stmt === false) {
             return false;
 
         }
         
         // Ausführen der vorbereiteten Abfrage
         if (sqlsrv_execute($stmt) === false) {
             return false;
 
         }
         
         $nameList = $this->fetchFullNames($this->conn);
         
         while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $row["Sender"] = trim($row["Sender"]);
                $row["Empfänger"] = trim($row["Empfänger"]);
                $row["Betreff"] = trim($row["Betreff"]);
 
             
             $row["anhaenge"] = "";
             if($row["Anhang"]!= 0) {
                 $row["anhaenge"] =   $this->_getAnhangMetaData($this->conn,$row["Anhang"]);
             }
 
             $row["fullName"] = "";
            
             $row["Datum"] = $row["Datum"]->format(DateTime::ATOM); 
            
             foreach ($nameList as $element) {
 
                 if ($element["Name"] == $row["Empfänger"])
                 {
                     $row["fullName"] = $element["Mitarbeitername"];
                     break;
                 }
             }
 
            
             $data[] = $row;
             
             
         };    
         sqlsrv_close($this->conn);
         
         return $data;
         
     
         
     }   
     

     public function compress($base64String) {
        $inputData = base64_decode($base64String);
        $compressedData = gzencode($inputData, 6);
        $originalLength = strlen($inputData);
        $gZipBuffer = pack('V', $originalLength) . $compressedData;
        return base64_encode($gZipBuffer);

     }

     function decompress($base64String) {
        // Decode the base64 string into binary data
        $compressedBuffer = base64_decode($base64String, true);
        if ($compressedBuffer === false) {
            throw new Exception("Invalid base64 input");
        }
    
        // Extract the original length (first 4 bytes in little-endian format)
        $originalLength = unpack('V', substr($compressedBuffer, 0, 4))[1];
    
        // Extract the compressed data (everything after the first 4 bytes)
        $compressedData = substr($compressedBuffer, 4);
    
        // Decompress the data
        $decompressedData = gzdecode($compressedData);
        if ($decompressedData === false) {
            throw new Exception("Decompression failed");
        }
    
        // Convert the decompressed binary data back to a Base64 string
        return base64_encode($decompressedData);
    }




     public function _sendMessage($connection = null, $messageObj = null, $userName = null) {
        $this->conn = $connection; 
        $data = [];


        if(count($messageObj->anhangArr)>0) {

            
            

            $anhangid = false;
            $IDsql = "Select MAX(ID) AS ID FROM EMail_Anhang";
            $IDstmt = sqlsrv_prepare($this->conn, $IDsql);

            if ($IDstmt === false) {
                return false;
            }
        
            // Ausführen der vorbereiteten Abfrage
            if (sqlsrv_execute($IDstmt) === false) {
                return false;
            }
            
            while ($row = sqlsrv_fetch_array($IDstmt, SQLSRV_FETCH_ASSOC)) {
                
                $anhangid = $row["ID"];
                
                $anhangid++;
            };

            if($anhangid==false)
            {
                return "ID FAILED";
            }

            $compressedString="";
            $values = [];
    
        
            $anhangSQL = "INSERT INTO EMail_Anhang (ID, Pos, Mail, Name) VALUES (?, ?, ?, ?)";
            $pos = 0;
            $anhangparams = [];
       

            foreach($messageObj->anhangArr as $obj) {

                $compressedString = $this->compress($obj->base64);

                $anhangparams = [
                    $anhangid,   
                    $pos,        
                    $compressedString, // Mail content (base64)
                    $obj->name   // Attachment name
                ];

                //return $compressedString;
            
                
                $anhangStmt = sqlsrv_prepare($this->conn, $anhangSQL, $anhangparams);
                
              
                
                if ($anhangStmt === false) {
                    // return false;
                    return false;
                }    
                if (!sqlsrv_execute($anhangStmt)) {
                    return false;
                } 
                $pos++;
            }
        
        }
        else 
        {
            $anhangid = 0;
        }

        //return substr($compressedString, 0,40);

        $sql = "INSERT INTO EMail (Datum,Grund_ID,Betreff,Nachricht,Empfänger,Sender,Erledigt,Wichtig,Anhang)  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $date = date('d-m-y H:i:s'); // Format: DD-MM-YYYY HH:MM:SS
       
    
        $params = [
            $date,                                  // Datum
            $messageObj->grund,                     // Grund_ID
            trim($messageObj->bezeichnung),         // Betreff
            $messageObj->nachricht,                 // Nachricht
            trim($messageObj->empfaenger),          // Empfänger
            trim($messageObj->sender),              // Sender
            0,                                      // Erledigt 
            $messageObj->dringlichkeit,             // Wichtig
            $anhangid                               // Anhang
        ];    
    
        $stmt = sqlsrv_prepare($this->conn, $sql, $params);
    
    
        if ($stmt === false) {
            return false;
            // return(print_r(sqlsrv_errors(), true));
        }    
        
        // Ausführen der vorbereiteten Abfrage
        if (sqlsrv_execute($stmt) === false) {
            sqlsrv_close($this->conn);
            // return(print_r(sqlsrv_errors(), true));
            return false;
        }    
        else {
            sqlsrv_close($this->conn);
            return true;
        }    
    
     }   


 


     public function _getFullAnhangData($connection = null, $anhangID = null, $anhangPos = null ) {

        $this->conn = $connection;
        $data =[];

     

        $sql="SELECT EMail_Anhang.Mail,EMail_Anhang.Name FROM EMail_Anhang WHERE EMail_Anhang.ID=? AND EMail_Anhang.Pos=?";
        $params = array($anhangID, $anhangPos, [SQLSRV_PARAM_IN]);
        $stmt = sqlsrv_prepare($this->conn, $sql, $params);
        
        if ($stmt === false) {
            return false;
        }
        
        // Ausführen der vorbereiteten Abfrage
        if (sqlsrv_execute($stmt) === false) {
            return false;
        }
   
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

            $data = $row;
            $data["Mail"] = $this->decompress($data["Mail"]);
        };

        sqlsrv_close($this->conn);
        return $data;
    
     }

    


        



     


















}
























?>