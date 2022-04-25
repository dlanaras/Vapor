<?php
class Account {
    public $accountId;
    public $userName;
    public $email;
    public $firstName;
    public $lastName;
    public $isAdmin;
    public $biography;
    public $isBanned;
    public $password;
    public $games;
    public $completedAchievements;

    public function __construct ($password, $accountId, $userName, $firstName, $lastName, $isAdmin, $biography, $email, $isBanned)
    {
        $this->password = $password;
        $this->accountId = $accountId;
        $this->userName = $userName;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->isAdmin = $isAdmin;
        $this->biography = $biography;
        $this->email = $email;
        $this->isBanned = $isBanned;
    }
}
?>