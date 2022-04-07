<?php
class DbConnHandler {
    static function getConnection() {

        $dbHost = "localhost";
        $dbName = "DB_Vapor";
        $dbUser = "pma";
        $dbPass = "pmapass";

        try {
            $db = new PDO("mysql:host=$dbHost;dbname=$dbName", "$dbUser", "$dbPass", [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            return $db;
        } catch(Exception $e) {
            echo "error with creating DB-Connection: $e";
        }
    }
}

?>