<?php

class Config
{
    /**
     * config file suffix
     *
     * @var string
     */
    const CONFIG_SUFFIX = '.cfg';

    /**
     * @var string
     */
    const CONFIG_PATH = "app/config/";

    /**
     * @return array
     */
    public static function loadConfig()
    {
        $mainCfg = parse_ini_file(self::CONFIG_PATH . "main.cfg");

        // override main config with url specific config
        if ($_SERVER['HTTP_HOST'])
        {
            $subcfg = $_SERVER['HTTP_HOST'] . self::CONFIG_SUFFIX;

            if (file_exists(self::CONFIG_PATH . $subcfg)) {
                $mainCfg = array_merge($mainCfg, (array) parse_ini_file(self::CONFIG_PATH . $subcfg));
            }
        }

        return $mainCfg;
    }

    /**
     * get wrapper
     *
     * @param $var
     * @return mixed
     */
    public static function get($var)
    {
        static $cfg;

        if (!$cfg) {
            $cfg = Config::loadConfig();
        }

        return isset($cfg[$var]) ? $cfg[$var] : null;
    }


}