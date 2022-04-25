<?php
class Achievement {
    public $achievementId;
    public $achievementName;
    public $description;
    public $isDisabled;
    public $thumbnail;
    public $gameId;

    public function __construct($achievementId, $achievementName, $description, $isDisabled, $thumbnail, $gameId) {
        $this->achievementId = $achievementId;
        $this->achievementName = $achievementName;
        $this->description = $description;
        $this->isDisabled = $isDisabled;
        $this->thumbnail = $thumbnail;
        $this->gameId = $gameId;
    }
}
?>