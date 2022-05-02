<?php

class DbConnHandler {
    static function getConnection() {
        $configArray = require __DIR__ . "/../config.php";

        $dbHost = $configArray['host'];
        $dbName = $configArray['name'];
        $dbUser = $configArray['username'];
        $dbPass = $configArray['pass'];

        try {
            $db = new PDO("mysql:host=$dbHost;dbname=$dbName", "$dbUser", "$dbPass", [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            return $db;
        } catch(Exception $e) {
            echo "error with creating DB-Connection: $e";
        }
    }
}

?>