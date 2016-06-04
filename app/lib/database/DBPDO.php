<?php
namespace SocialNetwork\app\lib\database;
use SocialNetwork\app\lib\Config;

/**
 * Description of pdodb
 *
 * @author lissi
 */
class DBPDO extends DBHandler
{
    /**
     * @throws \Exception
     */
    public function initDB()
    {

        try {
            $this->connect();
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    
    public function __call($name, array $arguments)
    {
        try {
            $this->connection()->query("SHOW STATUS;")->execute();
        } catch(\PDOException $e) {
            if($e->getCode() != 'HY000' || !stristr($e->getMessage(), 'server has gone away')) {
                throw $e;
            }
            $this->reconnect();
        }
        return call_user_func_array(array($this->connection(), $name), $arguments);
    }
    protected function connection()
    {
        return $this->dbh instanceof \PDO ? $this->dbh : $this->connect();
    }
    public function connect()
    {
        $this->dbh = new \PDO(
                Config::get('db_dsn') . ";dbname=" . Config::get('db_name'),
                Config::get('db_user'),
                Config::get('db_pass'),
                [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]
            );
        $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $this->dbh;
    }
    public function reconnect()
    {
        $this->dbh = null;
        return $this->connect();
    }
    
    
    
}
