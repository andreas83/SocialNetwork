<?php

/**
 * Description of pdodb
 *
 * @author lissi
 */
class DBPDO extends DBHandler
{

    /**
     * (non-PHPdoc)
     *
     * @see DBHandler::initDB()
     */
    public function initDB()
    {

        try {
            $this->dbh = new PDO(Config::get('db_dsn') . ";dbname=" . Config::get('db_name'), Config::get('db_user'), Config::get('db_pass'), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
