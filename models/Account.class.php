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

    public function __construct($accountId, $userName, $firstName, $lastName, $isAdmin, $biography, $email, $isBanned) {
        $this->accountId = $accountId;
        $this->userName = $userName;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->isAdmin = $isAdmin;
        $this->biography = $biography;
        $this->email = $email;
        $this->isBanned = $isBanned;
    }


    //kill pc
    
}
?>