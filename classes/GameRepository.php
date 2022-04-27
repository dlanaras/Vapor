<?php
require_once("DbConnHandler.php");
require_once("Repository.php");
require_once("../../models/Game.class.php");

class GameRepository implements RepositoryInterface
{
    protected $db;

    public function __construct()
    {
        $this->db = DbConnHandler::getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM game_tbl";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $games = [];
        foreach ($result as $row) {
            $game = new Game($row["Id"], $row["name"], $row["price"], $row["thumbnail"], $row["description"], $row["releaseDate"], $row["isDisabled"], $row["downloadLink"]);
            $games[] = $game;
        }
        return $games;
    }

    public function getById($gameId)
    {
        $sql = "SELECT * FROM game_tbl WHERE Id = :gameId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":gameId", $gameId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $game = new Game($result["Id"], $result["name"], $result["price"], $result["thumbnail"], $result["description"], $result["releaseDate"], $result["isDisabled"], $result["downloadLink"]);

        return $game;
    }

    public function add($game)
    {
        $sql = "INSERT INTO game_tbl (name, price, thumbnail, description, releaseDate, downloadLink, isDisabled) VALUES(:gameName, :price, :thumbnail, :description, :releaseDate, :downloadLink, 0)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":gameName", $game->gameName);
        $stmt->bindValue(":price", $game->price);
        $stmt->bindValue(":thumbnail", $game->thumbnail);
        $stmt->bindValue(":description", $game->description);
        $stmt->bindValue(":releaseDate", $game->releaseDate);
        $stmt->bindValue(":downloadLink", $game->downloadLink);

        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function update($game)
    {
        $sql = "UPDATE game_tbl SET name = :gameName, price = :price, thumbnail = :thumbnail, description = :description, releaseDate = :releaseDate, downloadLink = :downloadLink WHERE Id = :gameId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":gameName", $game->gameName);
        $stmt->bindValue(":price", $game->price);
        $stmt->bindValue(":thumbnail", $game->thumbnail);
        $stmt->bindValue(":description", $game->description);
        $stmt->bindValue(":releaseDate", $game->releaseDate);
        $stmt->bindValue(":downloadLink", $game->downloadLink);
        $stmt->bindValue(":gameId", $game->gameId);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function disable($gameId)
    {
        $sql = "UPDATE game_tbl SET isDisabled = 1 WHERE Id = :gameId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":gameId", $gameId);

        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function getAchievements($gameId)
    {
        $sql = "CALL GetAchievementsFromGame(:gameId)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":gameId", $gameId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $achievements = [];
        foreach ($result as $row) {
            $achievement = new Achievement($row["Id"], $row["name"], $row["description"], $row["isDisabled"], $row["thumbnail"],  $row["game_Id"]);
            $achievements[] = $achievement;
        }

        return $achievements;
    }

    public function getMapOfGameNamesAndIds()
    {
        $sql = "SELECT Id, name FROM game_tbl";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $gameNamesToIds = array();
        foreach ($result as $row) {
            $gameNamesToIds[$row["Id"]] = $row["name"];
        }

        return $gameNamesToIds;
    }
}
