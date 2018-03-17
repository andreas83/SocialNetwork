<?php
namespace SocialNetwork\app\controller;

use SocialNetwork\app\lib\BaseController;
use SocialNetwork\app\lib\Config;
use SocialNetwork\app\lib\Helper;
use SocialNetwork\app\lib\transformer\string\IStringTransformer;
use SocialNetwork\app\lib\transformer\TransformerFactory;
use SocialNetwork\app\model\Content;
use SocialNetwork\app\model\Hashtags;
use SocialNetwork\app\model\Notification;
use SocialNetwork\app\model\User;
use PHPMailer;
use WebSocket\Client;

/**
 * Class DataController
 */
class DataController extends BaseController
{

    function __construct() {

        $content= new Content();
        //get max id for rand min/max
        $res=$content->getNext(Content::maxid, 1, false, false, false, false, false);

        $hashtags= new Hashtags;

        $trending=$hashtags->getTrendingHashtags();
        if(count($trending)==0) {
            $trending=$hashtags->getPopularHashtags();
        }

        $this->assign("popularhashtags", $trending);
        $this->assign("randomhashtags",   $hashtags->getRandomHashtags());

        if (!$res) {
            $this->assign("maxid", 0);
        } else {
            $this->assign("maxid", $res[0]->id);
        }


    }

    function frontend()
    {

        if (Helper::isUser()) {
            $this->stream();
            return;
        }
        elseif(isset($_COOKIE['auth']))
        {
            //try reauth
            $user = new User;
            $res=$user->find(array("auth_cookie"  => $_COOKIE['auth']));

            if(count($res)>0)
            {
                $_SESSION['login']=$res[0]->id;
                $_SESSION['user_settings']=$res[0]->settings;
                $this->stream();
                return;
            }

        }

        $this->assign("title", "Das merken die nie");
        $this->assign("scope", "frontpage register");
        $this->render("main.php");
    }

    /**
     * this method handles all requests arround stream data
     * and returns data as json formted string
     *
     */
    function content()
    {
        $data = new Content;

        $id = (isset($_REQUEST['id']) && $_REQUEST['id'] != 0 ? (int) $_REQUEST['id'] : Content::maxid);
        $show = (isset($_REQUEST['show']) && $_REQUEST['show'] < 100 ? $_REQUEST['show'] : 10);
        $hash= (isset($_REQUEST['hash']) && $_REQUEST['hash'] != "" ? $_REQUEST['hash'] : false);
        $user= (isset($_REQUEST['user']) && $_REQUEST['user'] != "" ? $_REQUEST['user'] : false);

        //check nsfw content
        $settings=  Helper::getUserSettings();
        //while user is not logged in
        if($settings==false)
        {
            $settings=new \stdClass();
            $settings->show_nsfw="false";
        }

        $data = $data->getNext($id, $show, $hash, $user, false, false, $settings->show_nsfw);
        $i = 0;

        foreach ($data as $res) {
            $std[$i] = new \stdClass();
            $std[$i]->stream = new \stdClass();
            $std[$i]->stream->type = "generic";
            if (isset($res->media) && $res->media != 'null') {
                $std[$i]->stream = json_decode($res->media);
                if (isset($std[$i]->stream->type)) {
                    switch ($std[$i]->stream->type) {
                        case 'img':
                            $std[$i]->stream->thumb_url = Config::get('address') . "resize/".$std[$i]->stream->url;
                            $std[$i]->stream->url = Config::get('upload_address') . $std[$i]->stream->url;
                            break;
                        case 'upload':
                            foreach ($std[$i]->stream->files as $fileKey => $file) {
                                $std[$i]->stream->files[$fileKey]->src = Config::get('upload_address').$std[$i]->stream->files[$fileKey]->src;
                            }
                            break;
                        default:
                            break;
                    }
                }
            }

            $std[$i]->stream->date = (int)$res->date;
            $std[$i]->stream->text = $res->data;
            $std[$i]->stream->id = (int)$res->id;
            $std[$i]->stream->comment_cnt = (int)$res->comment_cnt;

            if (isset($res->settings))
            {
                $std[$i]->author = json_decode($res->settings);
                $std[$i]->author->name = $res->name;
                $std[$i]->author->id = (int)$res->user_id;
                if (isset($std[$i]->author->profile_picture) && $std[$i]->author->profile_picture != 'null') {
                    $std[$i]->author->profile_picture = Config::get('upload_address') . $std[$i]->author->profile_picture;
                }
            }
            $i++;
        }


        $this->asJson(isset($std) ? $std : []);
    }


    /**
     * load inital data for public stream
     *
     * @param type $request
     */
    function stream($request=false)
    {

        $this->assign("title", "Social Network - free open and anonym ");
        $this->assign("keyword", "open, anyonm, funny, cat, video, gif, webm, lol, weird, free, open");
        $this->assign("description", "Our main goal is to create a free and open community available to anyone anonymously or not");

        $this->assign("show_share", true);

        $this->render("stream.php");
    }

    /**
     * loads inital data for /<username>
     * @param type $request
     */
    function get_user($request)
    {
        $request['user']= urldecode($request['user']);
        $user = new User;
        $res=$user->find(array("name"=> str_replace(".", " ", $request['user'])));
        if(empty($res))
        {

            $this->getResponse()
                ->addStatusCode(404)
                ->executeHeaders();

            $data= new Content;
            $this->assign("stream", $data->getNext(false, 1 , "cat", false, "img", "order by rand()"));
            $this->render("404.php");
            return true;
        }

        $this->assign("user", $request['user']);
        $this->assign("title", "Stream from ".str_replace(".", " ", $request['user'] ));
        $this->addHeader('<meta property="og:url" content="'. Config::get("address").''.$request['user'].'"/>');
        $this->addHeader('<meta property="og:title" content="Stream from '.str_replace(".", " ", $request['user'] ).'"/>');
        $this->addHeader('<meta property="og:type" content="website" />');
        $this->render("stream.php");
    }


    /**
     * load inital data for /hash/
     * and saves hashtag popularity
     *
     * @param type $request
     */
    function get_hash($request){
        $hashdb= new Hashtags;
        $res=$hashdb->find(array("hashtag"=>$request['hash']));
        if(count($res)>0)
        {
            $res[0]->pop+=1;
            $res[0]->save();
        }else{
            $hashdb->hashtag= $request['hash'];
            $hashdb->pop=1;
            $hashdb->save();
        }

        $this->addHeader('<meta property="og:url" content="'.Config::get("address").'hash/'.$request['hash'].'"/>');
        $this->addHeader('<meta property="og:title" content="Posts about #'.$request['hash'].'"/>');
        $this->addHeader('<meta property="og:type" content="website" />');

        $this->assign("show_share", false);
        $this->assign("hash", $request['hash']);
        $this->assign("title", "#".$request['hash'] );
        $this->render("stream.php");
    }

    /**
     * loads inital data for permalink
     * like meta information
     *
     * @param type $request
     */
    function get_permalink($request){
        $data = new Content;
        $res=$data->get($request['id']);


        if(empty($res))
        {
            $this->getResponse()
                ->addStatusCode(404)
                ->executeHeaders();

            $data= new Content;
            $this->assign("stream", $data->getNext(false, 1 , "cat", false, "img", "order by rand()"));
            $this->render("404.php");
            return true;
        }

        if(strpos($res->data, "<code")!==false)
        {
            $res->data="";
        }
        else
        {
            $res->data=  strip_tags($res->data);
        }

        $pattern="/(^|\s)#(\w*[a-zA-Z0-9öäü_-]+\w*)/";
        preg_match_all($pattern, $res->data, $hashtags);

        $user = new User;
        $user=$user->get($res->user_id);

        $this->assign("title", $user->name." - ".$res->data);
        $this->assign("keyword", implode(",", $hashtags[2]));
        $this->assign("description", $res->data);

        $media=json_decode($res->media);
        $this->addHeader('<link rel="canonical" href="'.Config::get("address").'permalink/'.$request['id'].'">');
        $this->addHeader('<meta property="og:url" content="'.Config::get("address").'permalink/'.$request['id'].'"/>');
        $this->addHeader('<meta property="og:title" content="'.$res->data.'"/>');
        $this->addHeader('<meta property="og:type" content="website" />');
        if(isset($media->img[0]))
            $this->addHeader('<meta property="og:image" content="'.Config::get("upload_address").$media->img[0].'"/>');
        if(isset($media->type) && $media->type=="img")
            $this->addHeader('<meta property="og:image" content="'.Config::get("upload_address").$media->url.'"/>');

        $this->assign("show_share", false);
        $this->assign("permalink", $request['id']);
        $this->render("stream.php");
    }

    /**
     * function handles new content
     *
     * @return boolean
     */
    function post_content(){

        if (isset($_POST) && !empty($_POST))
        {
            //spam bot prevention
            if(isset($_POST['mail']) && !empty($_POST['mail']))
                return false;

            if(
                isset($_POST['content']) && empty($_POST['content']) &&
                isset($_POST['metadata']) && count(json_decode($_POST['metadata']))==0 &&
                isset($_FILES) && empty($_FILES['img']['name'][0])
              )
            {
               $this->redirect("/public/stream/");
               return false;
            }

            $content = new Content();
            $content->data = $_POST['content'];
            $pattern="/(^|\s)#(\w*[a-zA-Z0-9öäü_-]+\w*)/";
            preg_match_all($pattern, $content->data, $hashtags);
            if(count($hashtags[0])>0)
            {
                $hashdb= new Hashtags;
                foreach($hashtags[0] as $hashtag)
                {
                    $res=$hashdb->find(array("hashtag"=> trim(str_replace("#", "", $hashtag))));

                    if(count($res)==0)
                    {
                        $hashdb->hashtag=trim(str_replace("#", "", $hashtag));
                        $hashdb->pop=1;
                        $hashdb->save();
                    }
                    else
                    {


                        $res[0]->pop++;
                        $res[0]->save();

                    }

                }
            }


            $metadata= NULL;

            if(isset($_POST['metadata']) && !empty($_POST['metadata']))
            {
                $metadata = json_decode($_POST['metadata']);
                if(is_array($metadata) && count($metadata)==0)
                    $metadata=NULL;

            }
            if (isset($metadata->type) && $metadata->type == "img") {
                $metadata->url = $this->download($metadata->url);
            }

            if(isset($metadata->type) && $metadata->type == "video")
            {
                if($metadata->dl==true)
                {
                    $filename = $this->download($metadata->url);
                    $metadata->url= Config::get("upload_address").$filename;
                    $metadata->html=$this->replaceVideo($metadata->url);

                }

            }

            if (isset($metadata->type) && $metadata->type == "www") {
               $metadata=(object)($this->og_parser($metadata->url));
            }

            if (isset($_FILES) && !empty($_FILES['img']['name'][0]) && is_array($_FILES)) {
                foreach ($_FILES['img']['tmp_name'] as $i => $file) {
                    $uniq = uniqid("", true) . "_" . $_FILES['img']['name'][$i];
                    $upload_path = Config::get("dir") . Config::get("upload_path");
                    move_uploaded_file($file, $upload_path . $uniq);

                    $metadata= (is_null($metadata) ? new \stdClass() : $metadata);

                    $metadata->type = "upload";

                    $mime = mime_content_type($upload_path . $uniq);
                    $metadata->files[$i]=new \stdClass();
                    $metadata->files[$i]->src = $uniq;
                    $metadata->files[$i]->name = $_FILES['img']['name'][$i];
                    $metadata->files[$i]->type = $mime;
                }
            }

            $content->media = json_encode($metadata);
            if(Helper::isUser()) {
                $content->user_id = Helper::getUserID();
            } else {
                $content->user_id = 1;
            }


            $content->date=date("U");

            $new_id=$content->save();

            //save notification for mentions @username
            $pattern="/(^|\s)@(\w*[a-zA-Z0-9öäü._-]+\w*)/";
            preg_match_all($pattern, $content->data, $users);
            if(count($users[0])>0)
            {
                $client = new Client(Config::get("notification_server"));
                $user = new User;
                $notification = new Notification;
                $notification->from_user_id=Helper::getUserID();
                $notification->date=date("U");
                $notification->message='mention you in a '
                        . '<a href="/permalink/'.$new_id.'">post</a>';

                foreach($users[0] as $username)
                {
                    $username = trim(str_replace(array("@", "."), " ", $username));
                    $res = $user->find(array("name" => $username));

                    $notification->to_user_id=$res[0]->id;

                    if($notification->to_user_id!=Helper::getUserID())
                        $notification->save();

                    $client->send(json_encode(array("action"=>"update", "uid" =>$notification->to_user_id)));
                }
            }

            if(isset($_REQUEST['api_key']) && !empty($_REQUEST['api_key']))
            {
                $this->asJson(
                    [
                        "status" => "done",
                        "id" => $new_id
                    ]
                );
            }
            else
            {
               $this->redirect("/public/stream/");
            }
        }
    }


    /**
     * @param $request
     */
    function delete($request)
    {

        $content = new Content();
        $res=$content->find(array("user_id" => Helper::getUserID(), "id" =>$request['id'] ));

        if(count($res)>0)
        {
            $content->delete($request['id']);
            $this->asJson(
                [
                    "status" => "deleted"
                ]
            );
        }else{
           if(!isset($_REQUEST['api_key']) || empty($_REQUEST['api_key']))
           {

               $this->getResponse()->addStatusCode(403)->executeHeaders();
               die();

           }


        }

    }

    function update($request)
    {

        parse_str(file_get_contents("php://input"),$put_vars);


        if(isset($put_vars['api_key'])) {
            $_REQUEST['api_key']=$put_vars['api_key'];
        }

        if(!Helper::isUser())
        {
            $this->getResponse()
                ->addHeader('HTTP/1.0 403 Forbidden')
                ->executeHeaders();

            echo "Please validate your API Key: ".$_REQUEST['api_key'];
            return;
        }

        $content = new Content();


        $res=$content->find(
            [
                "user_id" => Helper::getUserID(),
                "id" => $request['id']
            ]
        );

        if(count($res)>0)
        {

            $res[0]->data=$put_vars['content'];
            $res[0]->save();

            $pattern="/(^|\s)#(\w*[a-zA-Z0-9öäü_-]+\w*)/";
            preg_match_all($pattern, $put_vars['content'], $hashtags);
            if(count($hashtags[0])>0)
            {
                $hashdb= new Hashtags;
                foreach($hashtags[0] as $hashtag)
                {
                    $hashdb->hashtag=trim(str_replace("#", "", $hashtag));
                    $hashdb->save();
                }
            }

            $this->asJson(
                [
                    "status" => "done"
                ]
            );
        }

    }


    /**
     * @param array $request
     * @throws \phpmailerException
     */
    function report($request)
    {
        $mail = new PHPMailer;

            if(Config::get("smtp")=="true")
            {
                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->Username = Config::get("smtp_user");
                $mail->Password = Config::get("smtp_pass");
                $mail->Port = Config::get("smtp_port");
                $mail->Host = Config::get("smtp_host");

            }


            $mail->From =  Config::get("mail_from");
            $mail->FromName =  Config::get("mail_from_name");
            $mail->addAddress(Config::get("abuse_mail"), Config::get("abuse_mail"));

            $this->assign("url", Config::get("address")."permalink/".$request['id']);
            $mail->Subject = _("Verify Content")." - ".Config::get("address");
            $mail->Body    = $this->render("email/report_content.php", true);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            if (!$mail->send()) {
                die( "Mailer Error: " . $mail->ErrorInfo );
            }

            $this->asJson(
                [
                    "status" => "reported"
                ]
            );
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

        curl_setopt($ch, CURLOPT_USERAGENT, 'www.dasmerkendienie.com');

        $rawdata = curl_exec($ch);
        curl_close($ch);

        $path_parts = pathinfo($url);
        $filename = hash("sha512", $url);
        if(isset($path_parts['extension'])) {
            $filename.=".".$path_parts['extension'];
        }


        $fullpath = Config::get("dir") . Config::get("upload_path") . $filename;
        $fp = fopen($fullpath, 'x');
        fwrite($fp, $rawdata);
        return $filename;
    }


    /**
     * returns infomations about given url
     *
     * @todo splitt image, www, video handling into smaller functions
     * @todo better fallback for og tag parsing
     * @return void
     */
    function metadata() {
        $url = $_GET['url'];

        //check for image
        $path_parts = pathinfo($url);
        if (isset($path_parts['extension'])) {
            $extensions = array("svg", "png", "jpg", "gif", "jpeg");
            if (in_array(strtolower($path_parts['extension']), $extensions)) {
                $data = array("type" => "img", "url" => $url);
                $this->asJson($data);
            }
        }

        //check for video
        $extensions = array("webm", "mpeg", "mp4");
        if ((isset($path_parts['extension']) &&
                in_array(strtolower($path_parts['extension']), $extensions)) ||
                strpos($url, "youtube") ||
                strpos($url, "youtu.be") ||
                strpos($url, "vimeo") ||
                strpos($url, "redtube")
        ) {
            if (isset($path_parts['extension']) && in_array(strtolower($path_parts['extension']), $extensions))
                $downloadable = true;
            else
                $downloadable = false;

            $data = array(
                "type" => "video",
                "html" => DataController::replaceVideo($url),
                "dl" => $downloadable);
            $this->asJson($data);
            return;
        }

        $data=$this->og_parser($url);
        $this->asJson($data);
    }

    /**
     * og_parser downloads a website
     * and extract information like title, description and og tags
     *
     * @param type $url
     * @return array
     */
    function og_parser($url){

        //check for og tag
        $data = array("type" => "www", "url"=>$url);
        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);

        $dom = new \DOMDocument;
        @$dom->loadHTML($result);
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

    /**
     * function was needed to replace hashs and links
     * @todo can be removed
     * @param string $data
     * @return string
     */
    static function replaceData($data) {

        $data = DataController::replaceLinks($data);
        $data = DataController::replaceHash($data);
        return $data;
    }

    static function replaceLinks($txt)
    {
        return TransformerFactory::makeStatic('string\\html\\LinkTransformer')->transform($txt);
    }


    static function replaceHash($txt)
    {
        return TransformerFactory::makeStatic('string\\html\\HashTransformer')->transform($txt, ['url' => Config::get('address')]);
    }



    static function replaceVideo($input, $dimension = false)
    {

        if (is_array($dimension)) {
            $width = $dimension['width'];
            $height = $dimension['height'];
        } else {
            $width = 400;
            $height = 350;
        }


        // create a transformer storage and iterate over them
        $transformerFactory = new TransformerFactory();
        $transformerStack = $transformerFactory->makeFromArray([
            "string\\html\\YoutubeTransformer",
            "string\\html\\VimeoTransformer",
            "string\\html\\RedtubeTransformer",
            "string\\html\\WebVideoTransformer",
        ]);

        // reformat the string
        /**
         * @var IStringTransformer $transformer
         */
        foreach ($transformerStack as $transformer) {
            $input = $transformer->transform(
                $input,
                [
                    'width'     => $width,
                    'height'    => $height,
                    'url'       => Config::get('address')
                ]
            );
        }

        return $input;
    }

}
