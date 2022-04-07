<?php
class Account {
    private $username;
    private $password;
    private $email;
    private $firstName;
    private $lastName;

    public function __construct() {
        $this->db = DbConnHandler::getConnection();
    }
}
?>