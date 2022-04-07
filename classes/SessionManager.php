<?php
include_once("DbConnHandler.php");
session_start();

class SessionManager {
    static function register($name, $pass, $firstName, $lastName, $email) {
        $hashedPass = password_hash($pass, PASSWORD_ARGON2I, ['memory_cost' => 1024, 'time_cost' => 2, 'threads' => 2]);
        $db = DbConnHandler::getConnection();
        $stmt = $db->prepare("INSERT INTO account_tbl(username, password, isAdmin, firstName, lastName, Email) VALUES(:name, :pass, false, :firstName, :lastName, :email)");
        $stmt->bindValue(":name", $name);
        $stmt->bindValue(":pass", $hashedPass);
        $stmt->bindValue(":firstName", $firstName);
        $stmt->bindValue(":lastName", $lastName);
        $stmt->bindValue(":email", $email);
        try {
            $stmt->execute();
            $_SESSION["username"] = $name;
        } catch(Exception $e) {
            echo $e;
        }
    }

    static function login($name, $pass) {
        $db = DbConnHandler::getConnection();
            $stmt = $db->prepare("SELECT username, password FROM account_tbl WHERE username = :name");
            $stmt->bindValue(":name", $name);

            try {
                $stmt->execute();
                $stmt->bindColumn('username', $userName);
                $stmt->bindColumn('password', $userPass);

                while($stmt->fetch(PDO::FETCH_BOUND)) {

                        if($name === $userName && password_verify($pass, $userPass)) {
                            $_SESSION["username"] = $userName;
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
}
?>