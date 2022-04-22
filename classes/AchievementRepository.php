<?php
require_once("DbConnHandler.php");
require_once("Repository.php");
require_once("../../models/Achievement.class.php");

class AchievementRepository implements RepositoryInterface
{
    protected $db;

    public function __construct()
    {
        $this->db = DbConnHandler::getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM Achievement_tbl";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $achievements = [];
        foreach ($result as $row) {
            $achievement = new Achievement($row["Id"], $row["name"], $row["description"], $row["isDisabled"], $row["thumbnail"], $row["game_Id"]);
            $achievements[] = $achievement;
        }
        return $achievements;
    }

    public function getAllAchievementsOfGame($gameId): array
    {
        $sql = "SELECT * FROM Achievement_tbl WHERE game_Id = :gameId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":gameId", $gameId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $achievements = [];
        foreach ($result as $row) {
            $achievement = new Achievement($row["Id"], $row["name"], $row["description"], $row["isDisabled"], $row["thumbnail"], $row["game_Id"]);
            $achievements[] = $achievement;
        }
        return $achievements;
    }

    public function getById($gameId)
    {
    }

    public function add($game)
    {
    }

    public function update($game)
    {
    }

    public function disable($gameId)
    {
    }
}
