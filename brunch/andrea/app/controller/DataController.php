<?php

/**
 * Class DataController
 */
class DataController extends BaseController {

    function frontend() {
        
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

    function content() {
        $data = new Content;

        $id = (isset($_REQUEST['id']) && $_REQUEST['id'] != 0 ? (int) $_REQUEST['id'] : 1000000000);
        $show = (isset($_REQUEST['show']) && $_REQUEST['show'] < 100 ? $_REQUEST['show'] : 10);
        $hash= (isset($_REQUEST['hash']) && $_REQUEST['hash'] != "" ? $_REQUEST['hash'] : false);
        $user= (isset($_REQUEST['user']) && $_REQUEST['user'] != "" ? $_REQUEST['user'] : false);

        
        $data = $data->getNext($id, $show, $hash, $user);
        header('Content-Type: application/json');
        $i = 0;

        foreach ($data as $res) {
            $std[$i] = new stdClass();
            $std[$i]->stream = new stdClass();
            $std[$i]->stream->type = "generic";
            $std[$i]->stream = (isset($res->media) && $res->media != "null" ? json_decode($res->media) : $std[$i]->stream);

            $std[$i]->stream->date = $res->date;
            $std[$i]->stream->text = $res->data;
            $std[$i]->stream->id = $res->id;

            $std[$i]->author = json_decode($res->settings);
            $std[$i]->author->name = $res->name;
            $std[$i]->author->id = $res->user_id;

            $i++;
        }

        if (isset($std))
            echo json_encode($std);
        else {
            echo json_encode(array());
        }
    }

    function stream($request=false) {
        
        $data = new Content;

        if (isset($_POST) && !empty($_POST) && Helper::isUser() && !isset($_POST['wayback'])) {
            $content = new Content();
            $content->data = $_POST['content'];

            $metadata = json_decode($_POST['metadata']);
            if ($metadata->type == "img") {
                $metadata->url = $this->download($metadata->url);
            }

            if (isset($_FILES) && !empty($_FILES['img']['name'][0]) && is_array($_FILES)) {

                foreach ($_FILES['img']['tmp_name'] as $i => $file) {
                    $uniq = uniqid("", true) . "_" . $_FILES['img']['name'][$i];
                    $upload_path = Config::get("dir") . Config::get("upload_path");
                    move_uploaded_file($file, $upload_path . $uniq);
                    $metadata->img[] = $uniq;
                    $metadata->type = "upload";
                }
            }
            $content->media = json_encode($metadata);
            $content->user_id = $_SESSION['login'];
            $content->date=date("U");
            
            $content->save();
            $this->redirect("/public/stream/");
        }
        $this->assign("title", "Public Stream");
        if(isset($request['id']))
        {
            $res=$data->get($request['id']);
            $this->assign("title", "Public Stream  ".$res->data);
            $media=json_decode($res->media);
            $this->addHeader('<link rel="canonical" href="'.Config::get("address").'permalink/'.$request['id'].'">');
            $this->addHeader('<meta property="og:url" content="'.Config::get("address").'permalink/'.$request['id'].'"/>');
            $this->addHeader('<meta property="og:title" content="'.$res->data.'"/>');
            $this->addHeader('<meta property="og:type" content="website" />');
            if(isset($media->img[0]))
                $this->addHeader('<meta property="og:image" content="'.Config::get("address").'/public/upload/'.$media->img[0].'"/>');
            if(isset($media->type) && $media->type=="img")
                $this->addHeader('<meta property="og:image" content="'.Config::get("address").'/public/upload/'.$media->url.'"/>');
            
            
            $this->assign("permalink", $request['id']);
        }
        if(isset($request['hash']))
        {
            $this->assign("hash", $request['hash']);
            $this->assign("title", "Pictures of ".$request['hash'] );
            
        }
        if(isset($request['user']))
        {
            $this->assign("user", $request['user']);
            $this->assign("title", "Stream of ".str_replace(".", " ", $request['user'] ));
            
        }
        
        if(isset($_POST['wayback']) && is_numeric($_POST['wayback']))
        {
        
            $this->assign("wayback", $_POST['wayback']);
        }
        
        
        $this->assign("scope", "login");
        
       
        $this->render("stream.php");
    }

    function comment($request) {
        $comment = new Comment();
        if ($_POST && Helper::isUser()) {
            $comment->content_id = $request['id'];
            $comment->comment = $_POST['text'];
            $comment->user_id = $_SESSION['login'];
            $comment->save();
        } elseif ($_POST && !Helper::isUser()) {
            header('HTTP/1.0 403 Forbidden');
        }

        $data = $comment->getComment($request['id']);

        header('Content-Type: application/json');
        $i = 0;
        foreach ($data as $res) {
            $std[$i] = new stdClass();

            $std[$i]->text = $res->comment;
            $std[$i]->author = json_decode($res->settings);
            $std[$i]->author->name = $res->name;
            $i++;
        }
        if (isset($std))
            echo json_encode($std);
        else {
            echo json_encode(array());
        }
    }

    function score($request) {

        $score = new Score();
        if (isset($request['type']) && Helper::isUser()) {
            
            $find=$score->find(array(
                "content_id"=>$request['id'], 
                "user_id" => $_SESSION['login'],
                "type" => $request['type']));
            
            if(count($find)>0)
            {
                $find[0]->delete($find[0]->id);
                goto getScore;
                    
            }
            
            $score->user_id = $_SESSION['login'];
            $score->content_id = $request['id'];
            $score->type = $request['type'];
            $score->save();
        } elseif ($_POST && !Helper::isUser()) {
            header('HTTP/1.0 403 Forbidden');
        }
        getScore:
        $like=$score->find(array(
            "content_id"=>$request['id'], 
            "type" => "add"));
        
        $dislike=$score->find(array(
            "content_id"=>$request['id'], 
            "type" => "sub"));
        
        header('Content-Type: application/json');
   
        if (count($like)>0 || count($dislike)>0)
            echo json_encode(array("like"=>count($like), "dislike"=>count($dislike)));
        else {
            echo json_encode(array("like" =>0, "dislike" => 0));
        }
    }
    
    function delete($request){
        $content = new Content();
        $res=$content->find(array("user_id" => $_SESSION['login'], "id" =>$request['id'] ));
        header('Content-Type: application/json');
        if(count($res)>0)
        {
            $content->delete($request['id']);
            echo json_encode(array("status" => "deleted"));
        }
        
    }
    function update($request){
        $content = new Content();
        $res=$content->find(array("user_id" => $_SESSION['login'], "id" =>$request['id'] ));
        header('Content-Type: application/json');
        if(count($res)>0)
        {
            parse_str(file_get_contents("php://input"),$put_vars);
            $res[0]->data=$put_vars['content'];
            $res[0]->save();
            echo json_encode(array("status" => "done"));
        }
        
    }

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

        $filename = hash("sha512", $url);
        $fullpath = Config::get("dir") . Config::get("upload_path") . $filename;
        $fp = fopen($fullpath, 'x');
        fwrite($fp, $rawdata);
        return $filename;
    }

    function metadata() {
        $url = $_GET['url'];

        header('Content-Type: application/json; charset=utf-8');
        //check for image
        $path_parts = pathinfo($url);
        if (isset($path_parts['extension'])) {
            $extensions = array("svg", "png", "jpg", "gif", "jpeg");
            if (in_array(strtolower($path_parts['extension']), $extensions)) {
                $data = array("type" => "img");
                echo json_encode($data);
                return;
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
            echo json_encode($data);
            return;
        }

        //check for og tag
        $data = array("type" => "www");
        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);

        $dom = new DOMDocument;
        @$dom->loadHTML(mb_convert_encoding($result, 'HTML-ENTITIES', 'UTF-8'));
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
        }

        echo json_encode($data);
    }

    static function replaceData($data) {

        $data = DataController::replaceLinks($data);
        $data = DataController::replaceHash($data);
        return $data;
    }

    static function replaceLinks($txt) {
        return preg_replace('@((www|http://|https://)[^ ]+)@', '<a href="\1">\1</a>', $txt);
    }

    static function replaceHash($txt) {
        return preg_replace('/(^|\s)#(\w*[a-zA-Z0-9öäü_-]+\w*)/', '\1<a href="http://www.dasmerkendienie.com/hash/\2">#\2</a>', $txt);
    }

    static function replaceMedia($media, $size = false) {

        if (!$size) {
            $width = 630;
            $height = 630;
        } else {
            $width = $size['width'];
            $height = $size['height'];
        }
        $data = DataController::replaceVideo($media, true, $size);

        $path_parts = pathinfo($media);

        $extensions = array("svg", "png", "jpg", "gif", "jpeg");
        if (in_array(strtolower($path_parts['extension']), $extensions)) {
            $data = "<img src=\"/upload/" . $path_parts['basename'] . "\">";
        }

        return $data;
    }

    static function replaceVideo($input, $dimension = false) {

        if (is_array($dimension)) {
            $width = $dimension['width'];
            $height = $dimension['height'];
        } else {
            $width = 400;
            $height = 350;
        }

        $search = "/(.+(\?|&)v=([a-zA-Z0-9_-]+).*)|https\:\/\/youtu.be\/([a-zA-Z0-9_-]+).*/";

        $youtube = '<iframe id="ytplayer" type="text/html"  width="' . $width . '" height="' . $height . '" src="https://www.youtube.com/embed/$3$4" frameborder="0"> </iframe> ';
        $input = preg_replace($search, $youtube, $input);

        $search = '/((http|https)\:\/\/)?([w]{3}\.)?vimeo.com\/(\d+)+/i';

        $vimeo = '<iframe src="http://player.vimeo.com/video/$4?badge=0" width="' . $width . '" height="' . $height . '" frameborder="0" webkitAllowFullScreen="true" mozallowfullscreen="true" 
allowFullScreen="true"></iframe>';
        $input = preg_replace($search, $vimeo, $input);

        $search = '/((http|https)\:\/\/)?([w]{3}\.)?redtube.com\/(\d+)+/i';
        $redtube = '<iframe src="http://embed.redtube.com/?id=$4" width="' . $width . '" height="' . $height . '" frameborder="0" scrolling="no"></iframe>';
        $input = preg_replace($search, $redtube, $input);

        $path_parts = pathinfo($input);
        if (isset($path_parts['extension']) && ($path_parts['extension'] == "webm" || $path_parts['extension'] == "mpeg" || $path_parts['extension'] == "mp4")) {
            $input = '<video width="' . $width . '" height="' . $height . '" autoplay="autoplay" loop controls ><source src="' . $input . '" type="video/webm">Your browser does not support the video tag 
or webm</video>';
#       $input ='<video controls loop ><source src="'.$input.'" type="video/webm">Your browser does not support the video tag or webm</video>';
        }

        return $input;
    }

}
