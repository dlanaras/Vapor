<?php
include_once("DbConnHandler.php");
session_start();

class SessionManager {
    static function register($name, $pass, $firstName, $lastName, $email) {
        $hashedPass = password_hash($pass, PASSWORD_ARGON2I, ['memory_cost' => 1024, 'time_cost' => 2, 'threads' => 2]);
        $db = DbConnHandler::getConnection();
        $stmt = $db->prepare("INSERT INTO account_tbl(username, password, isAdmin, firstName, lastName, Email, isBanned, biography) VALUES(:name, :pass, false, :firstName, :lastName, :email, false, '')");
        $stmt->bindValue(":name", $name);
        $stmt->bindValue(":pass", $hashedPass);
        $stmt->bindValue(":firstName", $firstName);
        $stmt->bindValue(":lastName", $lastName);
        $stmt->bindValue(":email", $email);
        try {
            $stmt->execute();
            $_SESSION["username"] = $name;

            $stmt = $db->prepare("SELECT Id FROM account_tbl WHERE username = :name");
            $stmt->bindValue(":name", $name);
            $stmt->execute();
            $stmt->bindColumn("Id", $accountId);

            $_SESSION["Id"] = $accountId;
        } catch(Exception $e) {
            echo $e;
        }
    }

    static function login($name, $pass) {
        $db = DbConnHandler::getConnection();
            $stmt = $db->prepare("SELECT Id, username, password, isBanned FROM account_tbl WHERE username = :name");
            $stmt->bindValue(":name", $name);

            try {
                $stmt->execute();
                $stmt->bindColumn('username', $userName);
                $stmt->bindColumn('password', $userPass);
                $stmt->bindColumn('isBanned', $isBanned);
                $stmt->bindColumn('Id', $accountId);

                while($stmt->fetch(PDO::FETCH_BOUND)) {
                        if($name === $userName && password_verify($pass, $userPass)) {

                            if($isBanned) {
                                SessionManager::redir("../views/cheaters.php");
                            }

                            $_SESSION["username"] = $userName;
                            $_SESSION["Id"] = $accountId;
                            break;
                        }
                    }
                } catch(Exception $e) {
                    echo $e;
                }
    }

    static function isLoggedIn() : bool {

        if(isset($_SESSION["username"]) && $_SESSION["username"] !== "") {
            $db = DbConnHandler::getConnection();
            $stmt = $db->prepare("SELECT username FROM account_tbl WHERE username = :name");
            $stmt->bindValue(":name", $_SESSION["username"]);
            try {

                $stmt->execute();
                $stmt->bindColumn('username', $userName);
                
                while($stmt->fetch(PDO::FETCH_BOUND)) {
                    return $userName === $_SESSION["username"];
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
        echo "<script>window.location.href = '$url';</script>";
        header("location: $url");
        die;
    }

    static function isInCrud() : bool {    
        return str_contains(strtolower($_SERVER['REQUEST_URI']), "crud");
    }

    static function isAwayFromHome() : bool {
        return !str_contains(strtolower($_SERVER['REQUEST_URI']), "index");
    }

    static function isAdmin() : bool {
        if(isset($_SESSION["username"]) && $_SESSION["username"] !== "") {
            $db = DbConnHandler::getConnection();
            $stmt = $db->prepare("SELECT isAdmin FROM account_tbl WHERE username = :name");
            $stmt->bindValue(":name", $_SESSION["username"]);
            try {

                $stmt->execute();
                $stmt->bindColumn('isAdmin', $isAdmin);
                
                while($stmt->fetch(PDO::FETCH_BOUND)) {
                    return $isAdmin;
                }
            } catch(Exception $e) {
                echo $e;
                return false;
            }
        }
        return false;
    }



    static function resetPassword($email) {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $pass = implode($pass);

        $password = password_hash($pass, PASSWORD_ARGON2I, ['memory_cost' => 1024, 'time_cost' => 2, 'threads' => 2]);

        $db = DbConnHandler::getConnection();
        $stmt = $db->prepare("UPDATE account_tbl SET password = :password WHERE Email = :email");
        $stmt->bindValue(":password", $password);
        $stmt->bindValue(":email", $email);

        try {
            $stmt->execute();
            mail($email, "Reset Password Vapor", "Use this password to login and then we strongly recommend you change it $pass");
        } catch(Exception $e) {
            echo $e;
        }
    }
}
?>