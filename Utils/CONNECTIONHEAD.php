<?php
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //      CONNECTOR CLASS - CONNECTOR TOKEN
    //      CHECKS AND CREATES CONNECTOR TOKENS
    //      @author Fernando Seidl, Sebastian Schaffrath
    //      
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
class CONNECTIONHEAD{
    /////////////////// -----------------------------------------> OBJECT PROPERTIES
    private static $Array=array("11P3ZgsIQlNPgdPAbJ1a1nFUsEFKMu9zHI3Av4c5LBTgTKr9K","Pz7MQ2SY4Lg6NwTrJLjS8vF1ucZsJDlmJJPZvwk7MfUG95bP6t","e5X1NzBmAwCMDqzMWDedouMwQ27yhvAoTe0MXnaUcMTNcxvhh","qJYavI3TYUJxqaFaZpsS7p09z6pfcD6PuLB4kBcX1BlmyMS","qErZUMmluNXt0nVfsBbryeOt2d0LwydE5xbyT4svD2jiEkAfpI","9TLDPJUAIcB47WvewLHlMxXzu7vJkfJD3rBTHxzHpPBsNBdVgU","WCbzOYQv0R0cwwc1zPbEtBb1wPMc9nCSkzm0GJ1pkfvBU3ktv","4hKvNBvza76kPaEi3D2r1yNG9yz067BR6FU3adY61kzT2Vke","5uOFFY86n33oJ1cefL1swJNRCEKzxgOwhEQsuqRHKrKMazIi9","yfLGZVhxL8HWJZ2AGkKaeu0tmD27hda1TAvQOhP3iDJvhN1sap","FP8UuCSRvu0W9hcTQ7IIw5sAS3nNfOs2Pwl4X7UEjVOO8sYl3R","U0RSJsbropyohmn3XMfPpBpRIYb2Er0ThqnNp9Tqu3YEqu67","6eGodglXbF2bCIUFw3jF9ofbBxGJCwyvTGYer5UGWIjxzPdhN","f1ovpEmYrKqdzkCgznqbFfaJIcFFtopKr5Kq9152rqkMjwD6","u2yiprYU8gLze0GBy1QMANkhd0NHZ4s3IAro9okTYKHvZrIWA","RfcpieaUWloHNONoQL00Hlkzdp3M4pcgk9gpdPCv97Wo3uDaho","sHDa9k7SNRKCVbSZYmpPWqDHE2CFQyKIi0IMcRMYKMFn0hrMn0","TMPlGySHvmc8045qPCPikyV8WdQDEf5tN6H3VrCskvjAwQIzg","Pln7yWpSa2DnPMdY5NImuNzNS23OhtTMhZsgWG1Qg0SdlLTRE","uEB7imy86zAIxunTbESCeellQOsQUIM6ovvbg6RDIS2EuGd1NH","7ei5ikrlwutXKKUlVel6JSipGIdtoSbJfKL0JwUmeIhvVTbFxq","k6xiK9Z0XsWFYbcxsdVSujLLTzaFVXjEKXsTPYtUYUKyu6b6rg","FTL0zR4jwqlH9bliTamX4rrxqOWC7uhkOIRJd6SZdR9vzESYJ","fsUqJS1wcSEJybCAVPSqKJh3d4UggRkyRBetUocyGiow2iGIc","rHK0PK9cAEwOVwROAL1tVQCoExdLkpnw59jcloTMu6dZ76eFhp","kj2rgo7zxAFUol9DCvqnQ5swrXPO4ACq5OedXo3rrNZuHgQaB","75BbrdemDECREXDfi13tbO6POAHit4MTsQsPTHGqYdvtLbmVmS","CGWQbJhtqkY3951EXvs73BJxRCJ7Xmb5i1bPiZulkRXW5D6VWn","wD8HpCr98brwekywdQekr1P4TiA6uPGvbfmfsRasNl5QvSwETP","3OEO9RD3uo4SkoCKvPdGjPOR3u8iy8qb3Blt17UpwrRbN41gF","Y4IPXLtz4MGYOyzViZIQksEiKXo9K4Fj0reDGN7Uo88lrlvcmj","EEzySHHwrTlPehgXT6b6Ay7SYzEY8bDJp3J2G4wWpqZq3WtX","heS7lsjcWCFFcbfIwOupfn2mZQRrAzh7SZp7NEgJlDORnP1A","o04c3nyLvinXX9wHs0JPLKUv24rezxY7ETwS1l3Dj9SPu8A1Ie","nD6pSGIcNtUx0CBIdSyWPfLKXoGBM7x5lAlDqDKRh2EoqKn6N4","BJ2Aa7HDKWBeA6WrK90K8C23WzScYUwdVIXE3WS6mZZatuqQrV","CyomVVOM7vP0SBbY1D9oAI1mxpxOc0oIaKu2zs3SbzipjpJBZ4","v9rhtf5OdcUPO2mZEu8dSRdnTItqmq9NnnlBem6StgnkWtCemJ","cju9EfClTnIlFt79ivxZGnk0vT1UyTt4Kv0prkerfOZmPojxc","REtW9tJsqiXmLny6JBvOWeTXv5ZcPhtlKHJV0IAFmGQTHnZTl","vjMim5BJDtaNOCKQ6dpYdNdTAHnikzNmF1bzHQXtA54X0hibq","TbBZGgTLUtnVnH6NLgfJ4O6BZjpxNrHdTza9GejSJGHTwLOh7z","R8EToW9ysG6VQfYfm1RKgoJVzBrWT94GQKZ5oi0Oh4aZhv68g6","o5PImXwYh5BwEaTHj0HeohTwWOM66AoVjhIqwvtLgXrrCuAS","fGs2c7nyzvBo0CxSP78egrJvp1db5C6F5capad9y6QUNxR33tw","aAG6hlnFC9VLDtEpM6oGBycGPqKUq1IAxyUyHuM8c1swkOSfN3","LK1DY8In5DTTLm9HvLVL7DHeZVBfQxC52Z9AR5QPoFkO5Zdn","Z70HGwto6jpN6WIzAESGctTZR2sGELOHxGkLOFLuqLtLcUbEW","Le8XE4lA3SXMEzmLALHauESJKr913nzdRJQ5YP6KsenpLekkmR","AbIs4EQsUkNJBwBXXPIgONfH6elyGxXdVt1OEVDmHfiKRBYvZE","bPyJcu3LRxsMJ9DroAKawttJ2RPvdY58eHBXxugRiODhFJpS0W","UFBnHHqHz4cf5JCr91cnl2oZESL7pX13s04tMIszfsI4V16wr","PIsk5TY3tRHNouASUWIMEogcXRfIR8qM4eQMKh61piF9Umf1","FJ1LKfDX2OonPl4M5m9hnXAASDegWtFrZB01zIlq3Qgrcs5lrs","5PnLHMBm0bDn1UzxB7DwQtbCfuCGOgodJNOYwvafqGsJW9L9X","LvSNfYWPC4ZrezuKGSIJ5HVYgR1anfH6jr5qrGhpf2FrV4H7","1o4hp3GrG6AJUeHvrENt39h4VuXnapflksSDlWgvijoj5SX3X","CayIADiaeI0uOIbMI6gGwB7MOzJPRunZ3uHUKX2fu8e3IGudru","O81G8G1bmF3RMJak2Xjuaa6kwA7BzAFsGiN6t28AkgWEtzEutR","JHlxsx1Oek87jF6wtHYnFedFOrJcwkuPPt5CttiFgTLVc9k","MtFl8SRlAfcLEXFT0eGchF1RgBtfBrOWavPwZXjBMHt8DS5koA","9bdLAlPFphzGjr9YsnisJV5aMFipWZX6i5RIXxbUz9XJ3dVXN","ACTTh9Vi6GZi2PoG3dPItO2fcGI5Y8oDsEx10Swr7cenBD4g","sUW7AhozpZnRi7j76HOXpelo6fjpgzJdtPUd30roH4VU0vii5");        
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //      ---> STATIC METHOD
    //      CHECK CONNECTOR OF REQUEST HEADER
    //
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function checkConnector($string=null){        
        for($i=0;$i<count(self::$Array);$i++){
            if(preg_match("(".$string.")",self::$Array[$i])){
              return true;
            }
        }
        return false;
    }    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //      ---> STATIC METHOD
    //      GET CONNECTOR FOR RESPONSE HEADER
    //
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function getRandomConnector(){
        $rIndex = random_int(0,51);        
        return self::$Array[$rIndex];
    }
}

?>