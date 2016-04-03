<?php
namespace app\lib\database;
use app\lib\Config;

/**
 * Class DBMySQLi
 */
class DBMySQLi extends DBHandler
{


    /**
     * @throws \Exception
     */
    public function initDB()
    {

        try {
            $this->dbh = new \mysqli(
                Config::get('db_host'),
                Config::get('db_user'),
                Config::get('db_pass'),
                Config::get('db_name')
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
