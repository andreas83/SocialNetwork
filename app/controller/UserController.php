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
                $this->redirect("/public/stream/");
            }
        }
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
                $error['mail'] = "Please validate you mail";
            }

            if ($_POST['pass'] != $_POST['pass2']) {
                $error['pass2'] = "Passwords do not match";
            }

            if (strlen($_POST['pass']) < 6) {
                $error['pass'] = "A bit stronger";
            }


            if (strlen($_POST['nick']) < 2) {
                $error['nick'] = "Nickname must be greater than 2.";
            }

            if (!isset($_POST['nick']) || empty($_POST['nick'])) {
                $error['nick'] = "Required Field";
            }

            $user = new User();

            $res = $user->find(array("name" => $_POST['nick']));
            if (count($res) > 0) {
                $error['mail'] = "A User with this Nickname already exist.";
            }

            if ($error === false) {

                $user->name = $_POST['nick'];
                $user->mail = $_POST['mail'];
                $user->password = md5($_POST['pass'] . Config::get("salat"));
                $default=new stdClass;
                $default->use_experimental = "nein";
                $default->show_nsfw = "nein";
                $default->autoplay = "ja";
                $default->mute_video = "ja";
                $user->settings = json_encode($default);
                $user->api_key = md5($_POST['nick']+date("Y-m-d H:i:s"));
                $user->created = date("Y-m-d H:i:s");
                $user->id = $user->save();
                $_SESSION['login'] = $user->id;
                $_SESSION['user_settings'] = $user->settings;
                $this->redirect("/public/stream/");

            }

        }
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
            $setting->use_experimental = $_POST['use_experimental'];
            $setting->show_nsfw = $_POST['nsfw'];
            $setting->autoplay = $_POST['autoplay'];
            $setting->mute_video = $_POST['mute_video'];
            $setting->custom_css=(isset($_POST['custom_css'])?$_POST['custom_css']: $setting->customcss);
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


    function logout()
    {
        session_destroy();
        $this->redirect("/");
    }

    /**
     * @param $request
     */
    function backend_list($request)
    {
        $user = new User();

        $page = (isset($request['page']) ? $request['page'] : false);
        $term = (isset($_REQUEST['term']) ? $_REQUEST['term'] : false);
        $user = $user->getUser($page, $term);

        $this->assign("pages", ceil($user[0]->cnt / Config::get("show_per_page")));
        $this->assign("term", $term);
        $this->assign("kunden", $user);
        $this->render("backend/kunden_liste.php");
    }

    /**
     * @param $request
     */
    function backend_edit($request)
    {

        $user = new User();

        //save data
        $user = (isset($request['id']) ? $user->get((int)$request['id']) : $user);

        if ($_POST) {
            foreach ($_POST as $key => $val) {
                if ($key == "pass") {
                    if (strlen($val) >= 6) {
                        $user->password = md5($val . Config::get("salat"));
                    }
                } else {
                    $user->$key = $val;
                }
            }
            $user->save();
        }

        $this->assign("kunde", $user);
        $this->render("backend/kunden_edit.php");
    }

    /**
     * @param $request
     */
    function backend_delete($request)
    {
        $user = new User();
        $user->delete($request['id']);
        $this->redirect("/backend/kunden/list");
    }

}
