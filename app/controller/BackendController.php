<?php
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
class BackendController extends BaseController  {
    /**
     * load database trait
     */
    use DBTrait;


    
    function __construct() {
        $this->load_database_handler();
        $this->assign("BackendModels", $this->getConfiguredBackendModels());
    }
    
    /*
     * Login for the backend 
     *
     */
    function login(){
        $error=false;
        
        if($_POST){
            $user =  new User;
            $res = $user->find(array("mail" => $_POST['mail'], "password" => md5($_POST['pass'] . Config::get("salat")), "isAdmin" =>1));
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
    function init(){
        $models=$this->getConfiguredBackendModels();
        $this->redirect("/backend/". $models[0]."/list/");
        
    }
    /**
     * Get all configured backend models.
     * 
     * @todo caching or find another way to get configured backend models
     */
    
    function getConfiguredBackendModels(){
        if ($handle = opendir('./app/model')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $model=str_replace(".php", "", $entry);
                    
                    if(method_exists($model, "getBackendConfiguration"))
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
     * @param type $request
     * @return void
     */
    function edit($request){
        $model = new $request['model'];
        if($_POST){
            if(isset($_POST[$model->getPrimary()]) && is_numeric($_POST[$model->getPrimary()]))
                $model = $model->get($_POST[$model->getPrimary()]);
            
            foreach ($_POST as $key => $val)
                $model->$key=$val;
            
            $model->save();
            return $this->table($request);
            
        }
        $this->assign("modelName", $request['model']);
        if(isset($request['id']))
            $this->assign("model", $model->get($request['id']));
        else
            $this->assign("model", $model);
        
        $this->assign("configuration", $model->getBackendConfiguration());
        $this->render("backend/generic_edit.php");
    }
    
    /*
     * Table is responsible also for pagination and 
     * search
     * 
     * 
     */
    function table($request){
        $model = new $request['model'];
              
        $page = (isset($request['page']) ? $request['page'] : false);
        $term = (isset($_REQUEST['term']) ? $_REQUEST['term'] : false);
        $res = $model->getPages($page, $term);

        $this->assign("pages", ceil($res[0]->cnt /  5));
        $this->assign("term", $term);
        $this->assign("modelName", $request['model']);
        $this->assign("configuration", $model->getBackendConfiguration());
        $this->assign("result", $res);
        $this->render("backend/generic_list.php");
    }
    
    function dashboard(){
         $this->render("backend/dashboard.php");
    }
    
    /*
     * @todo refactoring
     */
    function dashboard_hashtags(){
        $sql = 'SELECT data FROM Content WHERE data like "%#%" ';
        //we create a array with unique hashes and a count/weight 
        foreach( $this->dbh->query( $sql ) as $row )
        {
                preg_match_all('/(?<!\w)#\w+/', $row['data'], $hashes); 
                foreach($hashes[0] as $key => $hash)
                {

                if(!isset($unique_hash[$hash]) && !$unique_hash[$hash] )
                {
                        $unique_hash[$hash]=array("id" => $id++, "count"=>1);
                }else{
                        $unique_hash[$hash]['count']++;
                }
                }

        }
        $group=1;
        foreach( $this->dbh->query( $sql ) as $row )
        {
                preg_match_all('/(?<!\w)#\w+/', $row['data'], $hashes);
                if(count($hashes[0]) > 1)
                {
        #       var_dump($hashes);
                        foreach($hashes[0] as $hash)
                        {
        #               var_dump($hash);
                                if(isset($unique_hash[$hashes[0][0]]['id']) && isset($unique_hash[$hash]['id']))
                                        $link[]=array("source"=> $unique_hash[$hashes[0][0]]['id'], 
                                                        "target" => $unique_hash[$hash]['id'], 
                                                        "weight" => $unique_hash[$hashes[0][0]]['count'],
                                                        "group" => $unique_hash[$hashes[0][0]]['id']);
                                $grouphash[$hash]=(isset($grouphash[$hash]) ? $grouphash[$hash] : $group);
                        }
                        $group++;
                }
        }
        
        $output= '{
          "nodes":[';

        foreach($unique_hash as $key => $value)
        {
                $grouphash[$key] = (isset($grouphash[$key]) ? $grouphash[$key] : 1);
                $data[] = '{"name" : "'.$key.'", "group" : '.$grouphash[$key].'}';
        }
        $output.= implode(",", $data);
        $output.= ' ],
          "links":[';
        unset($data);
        foreach($link as $data)
        {
                $sdata[]='{"source":'.$data['source'].',"target": '.$data['target'].',"value": 1}';

        }
        $output.= implode(",", $sdata);
        $output.= ' ]
        }
        ';

        
        echo $output;
    }
    
    function dashboard_content(){
        $content = new Content();
        $res=$content->getStats();
        echo json_encode($res);
    }
    
    /**
     * handles the delete requests
     * 
     * @param type $request
     * @return type
     */
    function delete($request){
        $model = new $request['model'];
        $model->delete($request['id']);
        return $this->table($request);
    }
    
    
    
}
