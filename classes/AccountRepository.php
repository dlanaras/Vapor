<?php
require_once("DbConnHandler.php");
require_once("Repository.php");
require_once("../models/Account.class.php");

class AccountRepository implements RepositoryInterface
{
    protected $db;

    public function __construct()
    {
        $this->db = DbConnHandler::getConnection();
    }

    public function getAll() : array
    {
        $sql = "SELECT * FROM account_tbl";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $accounts = [];
        foreach ($result as $row) {
            $account = new Account($row["idaccount_tbl"], $row["username"], $row["firstName"], $row["lastName"], $row["isAdmin"], $row["biography"], $row["Email"], $row["isBanned"]);
            $accounts[] = $account;
        }
        return $accounts;
    }

    public function getById($accountId)
    {
        $sql = "SELECT * FROM account_tbl WHERE idaccount_tbl = :accountId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":accountId", $accountId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $account = new Account($result['idaccount_tbl'], $result['username'], $result['firstName'], $result['lastName'], $result['isAdmin'], $result['biography'], $result["Email"], $result["isBanned"]);

        return $account;
    }

    public function add($account)
    {
        $sql = "INSERT INTO account_tbl (username, firstName, lastName, isAdmin, Email, biography) VALUES (:username, :firstName, :lastName, :isAdmin, :email, '')";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":username", $account->username);
        $stmt->bindValue(":firstName", $account->firstName);
        $stmt->bindValue(":lastName", $account->lastName);
        $stmt->bindValue(":email", $account->email);
        $stmt->bindValue(":isAdmin", $account->isAdmin);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function update($account)
    {
        $sql = "UPDATE account_tbl SET username = :username, firstName = :firstName, lastName = :lastName, biography = :biography WHERE idaccount_tbl = :accountId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":username", $account->username);
        $stmt->bindValue(":firstName", $account->firstName);
        $stmt->bindValue(":lastName", $account->lastName);
        $stmt->bindValue(":accountId", $account->accountId);
        $stmt->bindValue(":biography", $account->biography);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function trySetAdmin($accountId) {
        if($this->isAdmin()) {
            $sql = "UPDATE account_tbl SET isAdmin = 1 WHERE idaccount_tbl = :accountId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":accountId", $accountId);
            try {
                $stmt->execute();
            } catch (Exception $e) {
                echo $e;
            }
        }
    }

    public function isAdmin() : bool {
        if(isset($_SESSION["username"]) && $_SESSION["username"] !== "") {
            $stmt = $this->db->prepare("SELECT isAdmin FROM account_tbl WHERE username = :name");
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

    public function disable($accountId) { //We are really against cheaters, so every ban is not reversable.
        $sql = "UPDATE account_tbl SET isBanned = 1 WHERE idaccount_tbl = :accountId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":accountId", $accountId);

        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }
    
    public function changePassword($pass) {
        $hashedPass = password_hash($pass, PASSWORD_ARGON2I, ['memory_cost' => 1024, 'time_cost' => 2, 'threads' => 2]);

        $stmt = $this->db->prepare("UPDATE account_tbl SET password = :pass WHERE username = :name");
        $stmt->bindValue(":pass", $hashedPass);
        $stmt->bindValue(":name", $_SESSION["username"]);
        try {
            $stmt->execute();
        } catch(Exception $e) {
            echo $e;
        }
    }
}

?>