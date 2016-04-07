<?php
namespace SocialNetwork\app\controller;

use SocialNetwork\app\lib\BaseController;
use SocialNetwork\app\lib\Config;
use SocialNetwork\app\lib\model\ModelFactory;
use SocialNetwork\app\model\User;

/**
 * BackendController
 * 
 * this class handles all requests from backend in a very generic 
 * aka unreadable way. 
 * maybe we will split these function into smaller method in the near future. 
 * right now this class handles the backend login and
 * generates generic CRUD method for configure Backend Models 
 * 
 * @todo validation of user input
 * 
 * @author andreas <andreas@moving-bytes.at>
 */
class BackendController extends BaseController
{
    const MODEL_NAMESPACE_PREFIX = 'SocialNetwork\\app\\model';

    public function __construct() {
        $this->assign("BackendModels", BackendController::getConfiguredBackendModels());
    }


    /**
     * @return bool
     */
    public function login()
    {
        $error=false;
        
        if ($_POST) {
            $user =  new User();
            $res = $user->find(array("mail" => $_POST['mail'], "password" => md5($_POST['pass'] . Config::get("salat")), "isAdmin" =>1));
            $error = [];

            if (count($res) == 0) {
                $error['login'] = _("Your login is incoreect");
            }
            else
            {
                $_SESSION['login'] = $res[0]->id;
                $_SESSION['isAdmin']=true;
                $_SESSION['user_settings']=$res[0]->settings;
                $res[0]->auth_cookie=md5($_POST['mail'] . $_POST['pass'] . Config::get("salat"));
                $res[0]->save();
                setcookie("auth", $res[0]->auth_cookie, strtotime( '+1 year' ), "/");
                $this->init();
                return true;
            }
        }
        $this->assign("error", $error);
        $this->render("backend/login.php");
    }
    
    /**
     * We just redirect to the first configured model after login
     */
    public function init(){
        $models= self::getConfiguredBackendModels();
        $this->redirect("/backend/". $models[0]."/list/");
        
    }

    /**
     * Get all configured backend models.
     *
     * @todo caching or find another way to get configured backend models
     * @return array
     */
    public static function getConfiguredBackendModels()
    {
        $configuredmodels = [];
        if ($handle = opendir('./app/model')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry !== "." && $entry !== "..") {
                    $model = str_replace(".php", "", $entry);
                    if(method_exists(self::MODEL_NAMESPACE_PREFIX . '\\' . $model, "getBackendConfiguration"))
                    {
                        $configuredmodels[]=$model;
                    }
                }
            }
            closedir($handle);
        }
        return $configuredmodels;
    }
    
    

    /**
     * handles create and update requests for given models
     * 
     * @todo validation
     * @param array $request
     * @return void
     */
    public function edit($request)
    {
        $model = ModelFactory::make((string) $request['model']);
        if($_POST){
            if(isset($_POST[$model->getPrimary()]) && is_numeric($_POST[$model->getPrimary()])) {
                $model = $model->get($_POST[$model->getPrimary()]);
            }
            
            foreach ($_POST as $key => $val) {
                $model->{$key}=$val;
            }
            
            $model->save();
            $this->table($request);
            return;
        }
        $this->assign("modelName", $request['model']);
        if(isset($request['id'])){
            $this->assign("model", $model->get($request['id']));
        } else {
            $this->assign("model", $model);
        }

        
        $this->assign("configuration", $model->getBackendConfiguration());
        $this->render("backend/generic_edit.php");
    }
    
    /*
     * Table is responsible also for pagination and 
     * search
     * 
     * 
     */
    public function table($request)
    {
        $model = ModelFactory::make((string) $request['model']);
              
        $page = (isset($request['page']) ? $request['page'] : false);
        $term = (isset($_REQUEST['term']) ? $_REQUEST['term'] : false);
        $res = $model->getPages($page, $term);

        if (isset($res[0])){
            $this->assign("pages", ceil($res[0]->cnt /  5));
        } else {
            $this->assign("pages", 0);
        }

        $this->assign("term", $term);
        $this->assign("modelName", $request['model']);
        $this->assign("configuration", $model->getBackendConfiguration());
        $this->assign("result", $res);
        $this->render("backend/generic_list.php");
    }
    
   
    
    /**
     * handles the delete requests
     * 
     * @todo (sanitize ??? check permissions ! :D pllllssss)
     * 
     * @param array $request
     * @return void
     */
    public function delete($request)
    {

        $model = ModelFactory::make((string) $request['model']);
        $model->delete($request['id']);
        $this->table($request);
    }
}
