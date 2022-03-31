<?php
include_once("./DbConnector.php");
session_start();

class SessionManager {
    static function register($name, $hashedPass) {

        $db = DbConnHandler::getConnection();
        $stmt = $db->prepare("INSERT INTO usertest(user, password) VALUES(:name, :pass)");
        $stmt->bindValue(":name", $name);
        $stmt->bindValue(":pass", $hashedPass);
        try {
            echo $name . $hashedPass;
            $stmt->execute();

            $_SESSION["username"] = $name;
            $_SESSION["id"] = $hashedPass;
        } catch(Exception $e) {
            echo $e;
        }
    }

    static function login($name, $hashedPass) {
        $db = DbConnHandler::getConnection();
            $stmt = $db->prepare("SELECT user, password FROM usertest WHERE user = :name AND password = :pass");
            $stmt->bindValue(":name", $name);
            $stmt->bindValue(":pass", $hashedPass);
            try {
                $stmt->execute();
                $stmt->bindColumn('user', $userName);
                $stmt->bindColumn('password', $userPass);
                echo $userName . $userPass . "TEST";

                    while($stmt->fetch(PDO::FETCH_BOUND)) {
                        if($name === $userName && $hashedPass === $userPass) {

                            $_SESSION["username"] = $name;
                            $_SESSION["id"] = $hashedPass;
                            return true;
                        }
                    }
                } catch(Exception $e) {
                    echo $e;
                    return false;
                }
    }

    static function isLoggedIn() : bool {
        if(isset($_SESSION["username"], $_SESSION["id"]) && $_SESSION["username"] !== "" && $_SESSION["id"] !== "") {
            $db = DbConnHandler::getConnection();
            $stmt = $db->prepare("SELECT user, password FROM usertest WHERE user = :name AND password = :pass");
            $stmt->bindValue(":name", $_SESSION["username"]);
            $stmt->bindValue(":pass", $_SESSION["id"]);
            try {
                $stmt->execute();
                $stmt->bindColumn('user', $userName);
                $stmt->bindColumn('password', $userPass);

                    while($stmt->fetch(PDO::FETCH_BOUND)) {
                        if($_SESSION["username"] === $userName && $_SESSION["id"] === $userPass) {
                            return true;
                        }
                    }
                } catch(Exception $e) {
                    echo $e;
                    return false;
                }
        }
        return false;
    }

    static function logout() {
        session_unset();
        session_destroy();
        SessionManager::redir("./login.php");
    }

    static function redir($url) {
        header("location: $url");
        die;
    }
}
?>