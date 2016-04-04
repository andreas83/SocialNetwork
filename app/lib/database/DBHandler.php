<?php
namespace SocialNetwork\app\lib\database;


/**
 * Abstract DBHandler Class
 *
 * @author j
 */
abstract class DBHandler implements DBInterface
{

    /**
     * database handler based on selected extension
     *
     * @var \PDO
     */
    public $dbh;


    /**
     * constructor initializes the default database connection
     */
    public function __construct()
    {
        set_exception_handler(array(
            __CLASS__,
            'fallback_handler'
        ));
        restore_exception_handler();

        $this->initDB();
    }


    /**
     * fallback Exception handler
     *
     * @param \Exception $exception
     */
    public function fallback_handler(\Exception $exception)
    {
        die('Uncaught exception: ' . $exception->getMessage());
    }


    /**
     * abstract initialization of the database handler
     */
    abstract public function initDB();
}

