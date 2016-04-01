<?php

/**
 * Class UserController
 */
class UserController extends BaseController
{

    /**
     *
     */
    function login()
    {
        $request = new Request();

        $error = false;
        if ($request->isPost()) {
            $user = new User();
            $res = $user->find(
                array(
                    "mail"      => $request->getPost('mail'),
                    "password"  => md5($request->getPost('pass') . Config::get("salat"))
                )
            );

            if (count($res) == 0) {
                $error['login'] = _("Your login is incoreect");

            } else {

                $_SESSION['login'] = $res[0]->id;
                $_SESSION['user_settings']=$res[0]->settings;
                $res[0]->auth_cookie=md5( $request->getPost('mail').  $request->getPost('pass') . Config::get("salat"));
                $res[0]->save();
                setcookie("auth", $res[0]->auth_cookie, strtotime( '+1 year' ), "/");
                $this->redirect("/public/stream/");
            }
        }

        
        $this->assign("scope", "login frontpage");
        $this->assign("title", "Login");
        $this->assign("error", $error);
        $this->render("main.php");
    }



    function register()
    {
        $request = new Request();
        $error = false;
        if ($request->isPost())
        {
            if (!filter_var($request->getPost('mail'), FILTER_VALIDATE_EMAIL)) {
                $error['mail'] = _("Please validate your email");
            }

            if (strlen($request->getPost('pass')) < 4) {
                $error['pass'] = "Your password is to short";
            }


            if (strlen($request->getPost('nick')) < 2) {
                $error['nick'] = _("Nickname is to short 2.");
            }

            if (!$request->getPost('nick')) {
                $error['nick'] = "Required field";
            }

            $user = new User();

            $res = $user->find(array("name" => $request->getPost('nick')));
            if (count($res) > 0) {
                $error['nick'] = _("A User with this nick already exist.");
            }

            if ($error === false) {

                $user->name =$request->getPost('nick');
                $user->mail = $request->getPost('mail');
                $user->password = md5($request->getPost('pass') . Config::get("salat"));
                              
                
                $user->settings = json_encode(UserController::defaultSettings());
                $user->api_key = md5($request->getPost('nick')+date("Y-m-d H:i:s"));
                $user->created = date("Y-m-d H:i:s");
                $user->id = $user->save();
                $_SESSION['login'] = $user->id;
                $_SESSION['user_settings'] = $user->settings;
                $this->redirect("/public/stream/");

            }

        }
        
        
        $this->assign("title", "Register an account");
        $this->assign("error", $error);
        $this->assign("scope", "frontpage register");
        $this->render("main.php");
    }

    
    function passwordResetConfirmed($request)
    {
        $user = new User();

        $res = $user->getUserbyAPIKey($request['hash']);
        if (count($res) == 0) {
            $error['pw_error'] = _("Account not found.");
        }else{
            $newpw=uniqid();
            $this->assign("newpw", $newpw);
            $res[0]->password = md5( $newpw. Config::get("salat"));
            $res[0]->save();
            
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
            $mail->addAddress($res[0]->mail, $res[0]->name);
            
            $this->assign("name", $res[0]->name);
            $mail->Subject = _("Password Reset")." - ".Config::get("address");
            $mail->Body    = $this->render("email/pw_forgot.php", true);
            $mail->AltBody    = $this->render("email/pw_forgot_txt.php", true);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            
            if (!$mail->send()) {
                die( "Mailer Error: " . $mail->ErrorInfo );
            } 
            

            $this->assign("scope", "frontpage ");
            $this->assign("status", "new_pw_send");
            $this->assign("scope", "frontpage ");
            $this->assign("title", _("Password reset"));
            $this->render("pw_forgot.php");

        }
   
    }
    
    function passwordReset()
    {
        $error=false;
        if(isset($_POST) && !empty($_POST))
        {

            $user = new User();

            $res = $user->find(array("mail" => $_POST['mail']));
            if (count($res) == 0) {
                $error['pw_error'] = _("Account not found.");
            }else{
      
            
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
                $mail->addAddress($res[0]->mail, $res[0]->name);


                $mail->Subject = _("Confirm Password Reset")." - ".Config::get("address");
                $this->assign("name", $res[0]->name );
                $this->assign("confirm_url", Config::get("address")."user/password/reset/".$res[0]->api_key."/");
                $mail->AltBody= $this->render("email/pw_confirm_txt.php", true);
                $mail->Body    = $this->render("email/pw_confirm.php", true);

                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';

                if (!$mail->send()) {
                    die( "Mailer Error: " . $mail->ErrorInfo );
                } 
                $this->assign("status", "confirm");


            }
        
        }
        
            

        $this->assign("title", "Password Reset");
        $this->assign("error", $error);
        $this->assign("scope", "frontpage ");
        $this->assign("title", _("Password reset"));
        $this->render("pw_forgot.php");
    }
    
    static function defaultSettings(){
        $default=new stdClass;
        $default->show_nsfw = "true";
        $default->autoplay = "yes";
        $default->mute_video = "yes";
        return $default;
    }
    
    
    function get($request){
        $user= new User;
        
        $res=$user->getUserbyName($request['user']);

        $this->asJson($res);
    }
    
    function settings()
    {
        $user = new User();
        $user = $user->get($_SESSION['login']);
        $setting = json_decode($user->settings);
        $error = false;
        if ($_POST) {
            
            
            
            if (isset($_FILES['picture']['tmp_name']) && !empty($_FILES['picture']['tmp_name'])) {
                
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['picture']['tmp_name']);
                $allowed_mime=array("image/jpeg", "image/gif", "image/png", "image/bmp");
                
                if(in_array($mime, $allowed_mime))
                {
                    $uniq = uniqid("", true) . "_" . $_FILES['picture']['name'];
                    move_uploaded_file($_FILES['picture']['tmp_name'], Config::get("dir").Config::get("upload_path") . "$uniq");
                    $setting->profile_picture = $uniq;
                }else{
                    $error['image']=_("Image type is not allowed");
                }
                
            }
            
            $setting->show_nsfw = $_POST['nsfw'];
            $setting->autoplay = $_POST['autoplay'];
            $setting->mute_video = $_POST['mute_video'];
            
            $setting->custom_css=(isset($_POST['custom_css']) ? $_POST['custom_css']: $setting->customcss);
            $user->settings = json_encode($setting);
            $_SESSION['user_settings']=$user->settings;
            $res = $user->find(array("name" => $_POST['nick']));
            if (count($res) > 0 && $_POST['nick'] != $user->name) {
                $error['user'] = "A User with this Nickname already exist.";
            } else {
                $user->name = $_POST['nick'];
            }
            
            if($user->password==md5($_POST['pass1']. Config::get("salat")))
            {
                $user->password=md5($_POST['pass2']. Config::get("salat"));
            }


            $user->save();
        }
                
        $hashtags= new Hashtags;
      
        $this->assign("popularhashtags",   $hashtags->getPopularHashtags());
        $this->assign("randomhashtags",   $hashtags->getRandomHashtags());
        $this->assign("title", "My Settings");
        $this->assign("error", $error);
        $this->assign("user", $user);
        $this->assign("settings", json_decode($user->settings));
        $this->render("user_settings.php");
    }

    

    static function getFBLoginURL(){
       
     

        $provider = new League\OAuth2\Client\Provider\Facebook([
            'clientId'          => Config::get("facebook_app_id"),
            'clientSecret'      => Config::get("facebook_app_secret"),
            'redirectUri'       => Config::get("address")."user/fblogin/",
            'graphApiVersion'   => 'v2.5',
        ]);
        
        $authUrl = $provider->getAuthorizationUrl(['scope' => ['email']]);
        $_SESSION['oauth2state'] = $provider->getState();
        
        
        
      
      
      return htmlspecialchars($authUrl);

    }
    
    
    function fbcallback(){
        $provider = new League\OAuth2\Client\Provider\Facebook([
            'clientId'          => Config::get("facebook_app_id"),
            'clientSecret'      => Config::get("facebook_app_secret"),
            'redirectUri'       => Config::get("address")."user/fblogin/",
            'graphApiVersion'   => 'v2.5',
        ]);
        
        // Try to get an access token (using the authorization code grant)
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);
        $_SESSION['oauth2state'] = $provider->getState();
        try {

            $ownerDetails = $provider->getResourceOwner($token);
            $this->oAuth( $ownerDetails->getFirstName(). " ". $ownerDetails->getLastName(), $ownerDetails->getEmail());

        } catch (Exception $e) {

            // Failed to get user details
            exit('Something went wrong: ' . $e->getMessage());

        }
        
    }
    
    
    static function getGLoginURL(){
        $provider = new League\OAuth2\Client\Provider\Google([
            'clientId'     => Config::get("google_app_id"),
            'clientSecret' => Config::get("google_app_secret"),
            'redirectUri'  => Config::get("address")."user/glogin/",
            'hostedDomain' => Config::get("address"),
        ]);

        $authUrl = $provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $provider->getState();
        
      return htmlspecialchars($authUrl);

    }
    
    function gcallback(){
        $provider = new League\OAuth2\Client\Provider\Google([
            'clientId'     => Config::get("google_app_id"),
            'clientSecret' => Config::get("google_app_secret"),
            'redirectUri'  => Config::get("address")."user/glogin/",
            'hostedDomain' => Config::get("address"),
        ]);
        // Try to get an access token (using the authorization code grant)
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);
        $_SESSION['oauth2state'] = $provider->getState();
        try {

            
            $ownerDetails = $provider->getResourceOwner($token);
       
            $this->oAuth( $ownerDetails->getFirstName(). " ". $ownerDetails->getLastName(), $ownerDetails->getEmail());
            
           

        } catch (Exception $e) {

            // Failed to get user details
            exit('Something went wrong: ' . $e->getMessage());

        }
    }
    
    
    function oAuth($username, $mail){
        
        $user= new User;
        $res = $user->find(array("name" => $username));
        if (count($res) > 0) {
            $error=true;
        }
        $res = $user->find(array("mail" => $mail));
        if (count($res) > 0) {
            $_SESSION['login'] = $res[0]->id;
            $_SESSION['user_settings']=$res[0]->settings;
            $res[0]->auth_cookie=md5($res[0]->name . $res[0]->mail. uniqid() . Config::get("salat"));
            $res[0]->save();
            setcookie("auth", $res[0]->auth_cookie, strtotime( '+1 year' ), "/");
            $this->redirect("/public/stream/");
            return true;
        }
        if(!$error)
        {
            $user->name = $username;
            $user->mail = $mail;
            $user->password = md5(uniqid(). Config::get("salat"));

            $user->settings = json_encode(UserController::defaultSettings());
            $user->api_key = md5(uniqid().date("Y-m-d H:i:s"));
            $user->created = date("Y-m-d H:i:s");
            $user->auth_cookie=md5($user->name . $user->mail. uniqid() . Config::get("salat"));
            $user->id = $user->save();
            $_SESSION['login'] = $user->id;
            $_SESSION['user_settings'] = $user->settings;
            setcookie("auth", $user->auth_cookie, strtotime( '+1 year' ), "/");
            $this->redirect("/public/stream/");
            return true;
        }
        

      $_SESSION['fb_access_token'] = (string) $accessToken;
      
      $this->assing("error", array("nick" =>_("A User with this nick already exist.") ));
      $this->register();
    }
    
    
    /**
     * 
     * 
     */
    function logout()
    {
        session_destroy();
        setcookie("auth", "", time()-3600, "/");
        $this->redirect("/");
    }

}
