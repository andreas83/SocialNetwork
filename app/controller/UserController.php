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

    function abmelden()
    {
        unset($_SESSION['login']);
        unset($_SESSION['isAdmin']);
        $this->redirect("/");
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
                $default=new stdClass;
                
                $default->show_nsfw = "true";
                $default->autoplay = "yes";
                $default->mute_video = "yes";
                $user->settings = json_encode($default);
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


            $user->save();
        }
        $this->assign("title", "My Settings");
        $this->assign("error", $error);
        $this->assign("user", $user);
        $this->assign("settings", json_decode($user->settings));
        $this->render("user_settings.php");
    }

    /**
     * @todo does not work right now.
     * 
     */
    function logout()
    {
        session_destroy();
        setcookie("auth", "", time()-3600);
        $this->redirect("/");
    }

}
