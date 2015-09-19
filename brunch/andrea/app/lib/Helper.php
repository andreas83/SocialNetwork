<?php

class Helper
{
    /**
     * Small Helper to include js
     * @param string $params
     * @return string
     */
    public static function js($params)
    {
        return file_get_contents(Config::get('basedir') . "/public/js/" . $params);
    }

    /**
     * Small Helper to include js
     * @param string $params
     * @return string
     */
    public static function jsScript($params)
    {
        return "<script src=\"" . Config::get('address') . "/public/js/" . $params . "\" type=\"text/javascript\"></script>\n\t\t";
    }

    /**
     * Small Helper to include css
     * @param string $params
     * @return string
     */
    public static function css($params)
    {
        return file_get_contents(Config::get('basedir') . "/public/css/" . $params);
    }

    /**
     * Small Helper to include css
     * @param string $params
     * @return string
     */
    public static function cssScript($params)
    {
        return "<link rel=\"stylesheet\" href=\"" . Config::get('address') . "/public/css/" . $params . "\" type=\"text/css\"/>\n\t\t";

    }

    public static function isUser()
    {
        return (isset($_SESSION['login']) ? true : false);
    }

    public static function seoUrl($url)
    {

        $url = str_replace("&amp;", "und", $url);
        $url = str_replace("ö", "oe", $url);
        $url = str_replace("ü", "ue", $url);
        $url = str_replace("ä", "ae", $url);
        $url = urlencode($url);
        $url = str_replace("+", "-", $url);
        return $url;
    }

}