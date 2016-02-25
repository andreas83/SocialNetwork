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
        $error = false;
        if ($_POST) {
            $user = new User();
            $res = $user->find(array("mail" => $_POST['mail'], "password" => md5($_POST['pass'] . Config::get("salat"))));
            if (count($res) == 0) {
                $error['login'] = _("Your login is incoreect");

            } else {

                $_SESSION['login'] = $res[0]->id;
                $_SESSION['user_settings']=$res[0]->settings;
                $res[0]->auth_cookie=md5($_POST['mail'] . $_POST['pass'] . Config::get("salat"));
                $res[0]->save();
                setcookie("auth", $res[0]->auth_cookie, strtotime( '+1 year' ), "/");
                $this->redirect("/public/stream/");
            }
        }
        $data= new Content;
        $this->assign("stream", $data->getNext(false, 5 , "cat", false, "img", "order by rand()"));
        
        $this->assign("scope", "login frontpage");
        $this->assign("title", "Login");
        $this->assign("error", $error);
        $this->render("main.php");
    }



    function register()
    {

        $error = false;
        if ($_POST) {
            if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
                $error['mail'] = _("Please validate your email");
            }

            if (strlen($_POST['pass']) < 4) {
                $error['pass'] = "Your password is to short";
            }


            if (strlen($_POST['nick']) < 2) {
                $error['nick'] = _("Nickname is to short 2.");
            }

            if (!isset($_POST['nick']) || empty($_POST['nick'])) {
                $error['nick'] = "Required field";
            }

            $user = new User();

            $res = $user->find(array("name" => $_POST['nick']));
            if (count($res) > 0) {
                $error['nick'] = _("A User with this nick already exist.");
            }

            if ($error === false) {

                $user->name = $_POST['nick'];
                $user->mail = $_POST['mail'];
                $user->password = md5($_POST['pass'] . Config::get("salat"));
                              
                
                $user->settings = json_encode(UserController::defaultSettings());
                $user->api_key = md5($_POST['nick']+date("Y-m-d H:i:s"));
                $user->created = date("Y-m-d H:i:s");
                $user->id = $user->save();
                $_SESSION['login'] = $user->id;
                $_SESSION['user_settings'] = $user->settings;
                $this->redirect("/public/stream/");

            }

        }
        
        $data= new Content;
        $this->assign("stream", $data->getNext(false, 5 , "cat", false, "img", "order by rand()"));
        
        $this->assign("title", "Register an account");
        $this->assign("error", $error);
        $this->assign("scope", "frontpage register");
        $this->render("main.php");
    }

    
    function passwordResetConfirmed($request){
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
            
            $data= new Content;
            $this->assign("stream", $data->getNext(false, 5 , "cat", false, "img", "order by rand()"));
            $this->assign("scope", "frontpage password_reset_form");
            $this->assign("status", "new_pw_send");
            $this->render("main.php");

        }
        
            
   
    }
    
    function passwordReset(){
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
        
            
        $data= new Content;
        $this->assign("stream", $data->getNext(false, 5 , "cat", false, "img", "order by rand()"));
        $this->assign("title", "Password Reset");
        $this->assign("error", $error);
        $this->assign("scope", "frontpage password_reset_form");
        $this->assign("title", _("Password reset"));
        $this->render("main.php");
    }
    
    static function defaultSettings(){
        $default=new stdClass;
        $default->show_nsfw = "true";
        $default->autoplay = "yes";
        $default->mute_video = "yes";
        return $default;
    }
    
    function settings()
    {
        $user = new User();
        $user = $user->get($_SESSION['login']);
        $setting = json_decode($user->settings);
        $error = false;
        if ($_POST) {

            if (isset($_FILES['picture']['tmp_name']) && !empty($_FILES['picture']['tmp_name'])) {
                $uniq = uniqid("", true) . "_" . $_FILES['picture']['name'];
                move_uploaded_file($_FILES['picture']['tmp_name'], Config::get("dir").Config::get("upload_path") . "$uniq");
                $setting->profile_picture = $uniq;
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
        $this->assign("title", "My Settings");
        $this->assign("error", $error);
        $this->assign("user", $user);
        $this->assign("settings", json_decode($user->settings));
        $this->render("user_settings.php");
    }

    

    static function getFBLoginURL(){
       
        $fb = new Facebook\Facebook([
        'app_id' => Config::get("facebook_app_id"),
        'app_secret' => Config::get("facebook_app_secret"),
        'default_graph_version' => 'v2.2'
        ]);
        
      $helper = $fb->getRedirectLoginHelper();
      
      $permissions = ['email']; // Optional permissions
      $loginUrl = $helper->getLoginUrl(Config::get("address")."/user/fblogin/", $permissions);
      
      
      return htmlspecialchars($loginUrl);

    }
    
    
    function fbcallback(){
        
        $fb = new Facebook\Facebook([
        'app_id' => Config::get("facebook_app_id"), // Replace {app-id} with your app id
        'app_secret' => Config::get("facebook_app_secret"),
        'default_graph_version' => 'v2.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
          $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        if (! isset($accessToken)) {
          if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error: " . $helper->getError() . "\n";
            echo "Error Code: " . $helper->getErrorCode() . "\n";
            echo "Error Reason: " . $helper->getErrorReason() . "\n";
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
          } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
          }
          exit;
        }

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

      
      
        try {
          // Returns a `Facebook\FacebookResponse` object
          $response = $fb->get('/me?fields=id,name,email', $accessToken->getValue());
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        $fbuser = $response->getGraphUser();
        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        
        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId(Config::get("facebook_app_id")); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        $tokenMetadata->validateUserId($fbuser['id']);
        $tokenMetadata->validateExpiration();
        
        

        $user = new User();
        $error=false;
        $res = $user->find(array("name" => $fbuser['name']));
        if (count($res) > 0) {
            $error=true;
        }
        $res = $user->find(array("mail" => $fbuser['email']));
        if (count($res) > 0) {
            $_SESSION['login'] = $res[0]->id;
            $_SESSION['user_settings']=$res[0]->settings;
            $res[0]->auth_cookie=md5($fbuser['name'] . $fbuser['email']. uniqid() . Config::get("salat"));
            $res[0]->save();
            setcookie("auth", $res[0]->auth_cookie, strtotime( '+1 year' ), "/");
            $this->redirect("/public/stream/");
            return true;
        }
        if(!$error)
        {
            $user->name = $fbuser['name'];
            $user->mail = $fbuser['email'];
            $user->password = md5(uniqid(). Config::get("salat"));


            $user->settings = json_encode(UserController::defaultSettings());
            $user->api_key = md5(uniqid()+date("Y-m-d H:i:s"));
            $user->created = date("Y-m-d H:i:s");
            $user->id = $user->save();
            $_SESSION['login'] = $user->id;
            $_SESSION['user_settings'] = $user->settings;
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
