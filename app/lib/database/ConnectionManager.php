<?php
namespace SocialNetwork\app\lib\database;

use SocialNetwork\app\lib\Config;

/*
 * ConnectionManager
 *
 * credits to Andrei Serdeliuc
 * via https://gist.github.com/extraordinaire/4135119
 */

class ConnectionManager
{
    protected $pdo;
    protected $driver_options;
    
    protected static $_instance = null;
    private $connction;
    
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    protected function __construct()
    {
        $this->connction=$this->connect();
    }
    public function getConnection()
    {
        return $this->connction;
    }
  
    public function __call($name, array $arguments)
    {
        try {
            $this->connection()->query("SHOW STATUS;")->execute();
        } catch (\PDOException $e) {
            if ($e->getCode() != 'HY000' || !stristr($e->getMessage(), 'server has gone away')) {
                throw $e;
            }
            $this->reconnect();
        }
        return call_user_func_array(array($this->connection(), $name), $arguments);
    }
    
    protected function connection()
    {
        return $this->pdo instanceof \PDO ? $this->pdo : $this->connect();
    }
    public function connect()
    {
        $this->pdo = new \PDO(
                Config::get('db_dsn') . ";dbname=" . Config::get('db_name'),
                Config::get('db_user'),
                Config::get('db_pass'),
                [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]
        );
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $this->pdo;
    }
    public function reconnect()
    {
        $this->pdo = null;
        return $this->connect();
    }
}
