<?php
class Game {
    public $gameId;
    public $gameName;
    public $price;
    public $thumbnail;
    public $description;
    public $releaseDate;
    public $isDisabled;

    public function __construct($gameId, $gameName, $price, $thumbnail, $description, $releaseDate, $isDisabled) {
        $this->gameId = $gameId;
        $this->gameName = $gameName;
        $this->price = $price;
        $this->thumbnail = $thumbnail;
        $this->description = $description;
        $this->releaseDate = $releaseDate;
        $this->isDisabled = $isDisabled;
    }
}
?>