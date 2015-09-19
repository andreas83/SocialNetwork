<?php

/**
 * default DB Interface
 *
 * @author j
 *
 */
Interface DBInterface
{


    /**
     * default contructor loads the database handler
     */
    public function __construct();


    /**
     * fall back exception handler
     *
     * @param Exception $exception
     */
    public function fallback_handler(Exception $exception);


    /**
     * initDB initializes the different database handler
     */
    public function initDB();
}
