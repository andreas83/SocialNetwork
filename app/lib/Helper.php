<?php
namespace SocialNetwork\app\lib;
use SocialNetwork\app\model\User;

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
        if(isset($_REQUEST['api_key']))
        {
            
            $user=new User;
            $res=$user->getUserbyAPIKey($_REQUEST['api_key']);
            if(count($res)>0)
                return true;
            else 
                return false;
        }
        return (isset($_SESSION['login']) ? true : false);
    }
    
    /**
     * useally userID is stored in $_SESSION
     * but when request comes from api, there is only the api_key
     */
    public static function getUserID(){
        if(isset($_REQUEST['api_key']) && !empty($_REQUEST['api_key']))
        {
            $user=new User;
            $res=$user->getUserbyAPIKey($_REQUEST['api_key']);
            return $res['0']->id;
        }
        
        return (isset($_SESSION['login']) ? $_SESSION['login'] : "1") ;
    }
      
    public static function getUserSettings()
    {
        if(!Helper::isUser())
            return false;
        
        $user= new User;
        $user=$user->get($_SESSION['login']);
        $settings=  json_decode($user->settings);
        
        return $settings;
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
