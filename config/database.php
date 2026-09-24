<?php
/**
 * Harvestly Core Database Connection Config
 * Uses MySQLi with Prepared Statements for security and performance.
 */

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'harvestly');
define('DB_PORT', 3306);

function getDBConnection() {
    static $conn = null;
    if ($conn === null) {
        mysqli_report(MYSQLI_REPORT_OFF);
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        if ($conn->connect_error) {
            http_response_code(503);
            die("Harvestly is temporarily unavailable. Please ensure MySQL is running and try again.");
        }
        $conn->set_charset("utf8mb4");
    }
    return $conn;
}
