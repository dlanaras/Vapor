<?php

use phpDocumentor\Reflection\Types\Boolean;

require_once("DbConnHandler.php");
require_once("Repository.php");
require_once("../../models/Account.class.php");
require_once("SessionManager.php");

class AccountRepository implements RepositoryInterface
{
    protected $db;

    public function __construct()
    {
        $this->db = DbConnHandler::getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT password, Id, username, firstName, lastName, isAdmin, biography, Email, isBanned FROM account_tbl";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $accounts = [];
        foreach ($result as $row) {
            $account = new Account($row["password"], $row["Id"], $row["username"], $row["firstName"], $row["lastName"], $row["isAdmin"], $row["biography"], $row["Email"], $row["isBanned"]);
            $accounts[] = $account;
        }
        return $accounts;
    }

    public function getById($accountId)
    {
        $sql = "SELECT * FROM account_tbl WHERE Id = :accountId;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":accountId", $accountId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $account = new Account($result["password"], $result['account_tbl.Id'], $result['username'], $result['firstName'], $result['lastName'], $result['isAdmin'], $result['biography'], $result["Email"], $result["isBanned"]);

        return $account;
    }

    public function getGamesWithAccountId($accountId)
    {
        $sql = "SELECT * FROM account_tbl WHERE Id = :accountId;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":accountId", $accountId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $account = new Account($result["password"], $result['Id'], $result['username'], $result['firstName'], $result['lastName'], $result['isAdmin'], $result['biography'], $result["Email"], $result["isBanned"]);

        $sql = "CALL GetGamesFromAccount(:accountId)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":accountId", $accountId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $games = [];
        foreach ($result as $row) {
            $game = new Game($row["Id"], $row["name"], $row["price"], $row["thumbnail"], $row["description"], $row["releaseDate"], $row["isDisabled"], $row["downloadLink"]);
            $games[] = $game;
        }

        $account->games = $games;

        return $account;
    }

    public function getByUserName($userName): Account
    {
        $sql = "SELECT * FROM account_tbl WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":username", $userName);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $account = new Account($result["password"], $result['Id'], $result['username'], $result['firstName'], $result['lastName'], $result['isAdmin'], $result['biography'], $result["Email"], $result["isBanned"]);

        return $account;
    }

    public function add($account)
    {
        $hashedPass = password_hash($account->password, PASSWORD_ARGON2I, ['memory_cost' => 1024, 'time_cost' => 2, 'threads' => 2]);
        $sql = "INSERT INTO account_tbl (password, username, firstName, lastName, isAdmin, Email, biography, isBanned) VALUES (:password, :username, :firstName, :lastName, :isAdmin, :email, '', false)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":username", $account->userName);
        $stmt->bindValue(":firstName", $account->firstName);
        $stmt->bindValue(":lastName", $account->lastName);
        $stmt->bindValue(":email", $account->email);
        $stmt->bindValue(":isAdmin", (int) $account->isAdmin);
        $stmt->bindValue(":password", $hashedPass);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function update($account)
    {
        $sql = "UPDATE account_tbl SET username = :username, firstName = :firstName, lastName = :lastName, biography = :biography, Email = :email WHERE Id = :accountId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":firstName", $account->firstName);
        $stmt->bindValue(":email", $account->email);
        $stmt->bindValue(":username", $account->userName);
        $stmt->bindValue(":lastName", $account->lastName);
        $stmt->bindValue(":accountId", $account->accountId);
        $stmt->bindValue(":biography", $account->biography);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function updateWithPassword($account)
    {
        $hashedPass = password_hash($account->password, PASSWORD_ARGON2I, ['memory_cost' => 1024, 'time_cost' => 2, 'threads' => 2]);
        $sql = "UPDATE account_tbl SET username = :username, firstName = :firstName, lastName = :lastName, biography = :biography, Email = :email, password = :password WHERE Id = :accountId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":firstName", $account->firstName);
        $stmt->bindValue(":email", $account->email);
        $stmt->bindValue(":username", $account->userName);
        $stmt->bindValue(":lastName", $account->lastName);
        $stmt->bindValue(":accountId", $account->accountId);
        $stmt->bindValue(":biography", $account->biography);
        $stmt->bindValue(":password", $hashedPass);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function setAdmin($accountId)
    {
        $sql = "UPDATE account_tbl SET isAdmin = 1 WHERE Id = :accountId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":accountId", $accountId);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function ban($accountId)
    { //We are really against cheaters, so every ban is not reversable.
        $sql = "UPDATE account_tbl SET isBanned = 1 WHERE Id = :accountId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":accountId", $accountId);

        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function changePassword($pass)
    {
        $hashedPass = password_hash($pass, PASSWORD_ARGON2I, ['memory_cost' => 1024, 'time_cost' => 2, 'threads' => 2]);

        $stmt = $this->db->prepare("UPDATE account_tbl SET password = :pass WHERE username = :name");
        $stmt->bindValue(":pass", $hashedPass);
        $stmt->bindValue(":name", $_SESSION["username"]);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function addGameToAccountLibrary($gameId, $accountId)
    {
        $sql = "INSERT INTO gamesPerAccount_tbl (account_Id, game_Id) VALUES (:accountId, :gameId)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":accountId", $accountId);
        $stmt->bindValue(":gameId", $gameId);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function addAchievementToAccount($achievementId, $accountId)
    {
        $sql = "INSERT INTO achievementsPerAccount_tbl (account_Id, achievement_Id) VALUES (:accountId, :achievementId)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":accountId", $accountId);
        $stmt->bindValue(":achievementId", $achievementId);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function getCompletedAchievementsOfAccount($accountId): array
    {
        $sql = "CALL GetAchievementsFromAccount(:accountId)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":accountId", $accountId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $achievements = [];
        foreach ($result as $row) {
            $achievement = new Achievement($row["Id"], $row["name"], $row["description"], $row["isDisabled"], $row["thumbnail"],  $row["game_Id"]);
            $achievements[] = $achievement;
        }

        return $achievements;
    }

    public function isGameAddedToAccount($gameId, $accountId): bool
    {
        $sql = "SELECT account_Id, game_Id FROM gamesPerAccount_tbl WHERE account_Id = :accountId AND game_Id = :gameId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":accountId", $accountId);
        $stmt->bindValue(":gameId", $gameId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (!empty($result["account_Id"])) && (!empty($result["game_Id"]));
    }

    public function isAchievementAddedToAccount($achievementId, $accountId): bool
    {
        $sql = "SELECT account_Id, achievement_Id FROM achievementsPerAccount_tbl WHERE account_Id = :accountId AND achievement_Id = :achievementId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":accountId", $accountId);
        $stmt->bindValue(":achievementId", $achievementId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (!empty($result["account_Id"])) && (!empty($result["achievement_Id"]));
    }
}
