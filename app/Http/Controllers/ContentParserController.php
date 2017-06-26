<?php

namespace App\Http\Controllers;

use App\Content;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;


class ContentParserController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      
    }

    public function wwwHandler($url, $filename){
        
        //switch
        
        
        //github
        //wikipedia
        //youtube
        //vimeo
        //twitter
        //amazon
        
        //general
        $this->wwwOpenGraphParser($url, $filenamse);
        
    }
    
    public function wwwOpenGraphParser($url, $filenamse)
    {
        $data = array("type" => "www", "url"=>$url);
        
        $dom = new \DOMDocument;
        @$dom->loadHTML(file_get_contents($filename));
        foreach ($dom->getElementsByTagName('meta') as $tag) {
            if ($tag->getAttribute('property') === 'og:image') {
                $data['og_img'] = $tag->getAttribute('content');
            }
            if ($tag->getAttribute('property') === 'og:title') {
                $data['og_title'] = $tag->getAttribute('content');
            }
            if ($tag->getAttribute('property') === 'og:description') {
                $data['og_description'] = $tag->getAttribute('content');
            }
            if($tag->getAttribute('name') == 'description')
            {
              $data['alt_description']= $tag->getAttribute('content');
            }
        }
        //fallback (while no opengraph tags exist, we use title tag, and meta description)
        if(!isset($data['og_title'])){
             $data['og_title'] =  $dom->getElementsByTagName('title')->item(0)->textContent;
        }
        if(!isset($data['og_description']) && isset($data['alt_description']) && !empty($data['alt_description'])){
             $data['og_description'] =  $data['alt_description'];
        }
        if(!isset($data['og_img']))
        {
            $base=$dom->getElementsByTagName('base');
            if($base->length>0)
                $base =  $dom->getElementsByTagName('base')->item(0)->attributes->getNamedItem("href")->value;
            else
                $base=$url;
            if($dom->getElementsByTagName('img')->length>0)
                $imgSrc=$dom->getElementsByTagName('img')->item(0)->attributes->getNamedItem("src")->value;
            if(substr($imgSrc, 0, 4)=="http")
                $data['og_img'] = $imgSrc;
            else
                $data['og_img'] = $base.$imgSrc;
        }
        return $data;
    }




    public function get(Request $request)
    {
        if($request->has("url"))
        {
            $regex='_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS';
                
            preg_match_all($regex,  $request->get("url"), $result);
            if(isset($result[0][0]))
            {
                $filename=$this->download($result[0][0]);
                $mime = mime_content_type( $filename);
                
                switch ($mime):
                    case "text/html":
                        var_dump($this->wwwHandler($filename, $request->get("url")));
                    break;
                    case "image/jpeg": 
                    case "image/png": 
                    case "image/gif":
                        $this->imgHandler();
                    break;
                    case "video/webm":
                    case "video/mp4":
                        $this->videoHandler();
                    break;
                    case "audio/mpeg":
                    case "audio/mp3":
                    case "audio/mpeg3":
                        $this->videoHandler();
                    break;
                
                    default: 
                        $this->genericHanlder();
                    break;
                
                endswitch;
            }
        }

    }
    
    /**
     * download content from given $url
     *
     * @param string $url
     * @return string $filename
     */
    function download($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, '');
        $rawdata = curl_exec($ch);
        curl_close($ch);
        $path_parts = pathinfo($url);
        $filename = hash("sha512", $url);
        if(isset($path_parts['extension'])) {
            $filename.=".".$path_parts['extension'];
        }
        
        $storage_path = storage_path($filename);
        $fp = fopen($storage_path, 'w');
        fwrite($fp, $rawdata);
        return $storage_path;
    }

}