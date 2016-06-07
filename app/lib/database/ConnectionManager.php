<?php
namespace SocialNetwork\app\lib\database;


class ConnectionManager 
{
    private $dsn;
    private $username;
    private $password;
    private $options;
    private $pdo;
 
    public function __construct($dsn, $username, $password, $options) {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
    }
 
    public function getConnection() {
        if ($this->pdo === null) {
            $this->reconnect();
        }
        return $this->pdo;
    }
 
    public function reconnect() {
        $this->pdo = new \PDO($this->dsn, $this->username, $this->password,
            $this->options);
    }
}
