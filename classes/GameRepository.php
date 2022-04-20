<?php
require_once("DbConnHandler.php");
require_once("Repository.php");
require_once("../models/Game.class.php");

class GameRepository implements RepositoryInterface
{
    protected $db;

    public function __construct()
    {
        $this->db = DbConnHandler::getConnection();
    }

    public function getAll() : array
    {
        $sql = "SELECT * FROM game_tbl";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $games = [];
        foreach ($result as $row) {
            $game = new Game($row["idgame_tbl"], $row["name"], $row["description"], $row["price"], $row["releaseDate"], $row["isActive"], $row["isDisabled"]);
            $games[] = $game;
        }
        return $games;
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

    public function disable($gameId) { 

    }
}
