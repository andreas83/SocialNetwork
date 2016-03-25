<?php



/**
 * DashboardController
 *
 * @author andreas <andreas@moving-bytes.at>
 */
class DashboardController extends BaseController  {
    
    
    function __construct() {
        $this->load_database_handler();
        
        $this->assign("BackendModels", BackendController::getConfiguredBackendModels());
    }
    
    
    use DBTrait;
    function dashboard($request){
        
        
         $this->render("backend/dashboard_".$request['target'].".php");
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
    function dashboard_user(){
        $user = new User();
        $res=$user->getStats();
        echo json_encode($res);
    }
}
