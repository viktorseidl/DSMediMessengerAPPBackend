<?php
require_once './Classes/db.php';

if ($conn) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed: ";
    print_r(sqlsrv_errors());
}
?>
