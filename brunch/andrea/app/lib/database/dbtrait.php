<?php

/**
 * trait to load the current databasehandler
 * base on the config settings
 *
 *
 * @author j
 *
 */
trait DBTrait
{
    /**
     * for pseudo singelton
     *
     * @var array $container
     */
    static protected $container;


    /**
     * only the database handling object
     *
     * @var mysqli pdo
     */
    public $dbh;


    /**
     * complete object
     */
    public $dbobject;


    protected function load_database_handler()
    {
        if (self::$container) {

            $this->dbobject = self::$container['dbobject'];
            $this->dbh = self::$container['dbh'];
            return true;
        }


        switch (Config::get('database_handler')) {
            case 'mysqli' :
                $this->dbobject = new DBMySQLi();
                $this->dbh = $this->dbobject->dbh;
                break;
            case 'pdo' :
            default :
                $this->dbobject = new DBPDO();
                $this->dbh = $this->dbobject->dbh;
                break;
        }

        self::$container['dbh'] = $this->dbobject->dbh;
        self::$container['dbobject'] = $this->dbobject;

        return true;
    }


    protected function unload_database_handler()
    {
        $this->dbobject = null;
        $this->dbh = null;
    }
}