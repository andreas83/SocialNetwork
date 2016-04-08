<?php
namespace SocialNetwork\app\controller;
use SocialNetwork\app\lib\BaseController;

use SocialNetwork\app\model\Content;
use SocialNetwork\app\model\User;


/**
 * DashboardController
 *
 * @author andreas <andreas@moving-bytes.at>
 */
class DashboardController extends BaseController
{
    
    public function __construct() {
        $this->assign("BackendModels", BackendController::getConfiguredBackendModels());
    }

    /**
     * @param array $request
     */
    public function dashboard($request)
    {
         $this->render("backend/dashboard_".$request['target'].".php");
    }

    public function dashboard_user()
    {
        $user = new User();
        
        $res=$user->getActiveUsers(date("n"), date("Y"));
        $this->assign("thismonth", $res);
        
        $month=date("n", strtotime("-1 month"));
        $year =date("Y", strtotime("-1 month"));
        
        $res=$user->getActiveUsers($month, $year);
        $this->assign("lastmonth", $res);
        $this->render("backend/dashboard_user.php");
    }
    
    
    /*
     * @todo refactoring
     */
    public function dashboard_json_hashtags(){
        $sql = 'SELECT data FROM Content WHERE data IKE "%#%" ';
        //we create a array with unique hashes and a count/weight
        $id = 0;
        foreach( $this->dbh->query( $sql ) as $row )
        {
            preg_match_all('/(?<!\w)#\w+/', $row['data'], $hashes);
            foreach($hashes[0] as $key => $hash)
            {
                if(!isset($unique_hash[$hash]) && !$unique_hash[$hash] ) {
                        $unique_hash[$hash]=array("id" => $id++, "count"=>1);
                } else {
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
                foreach($hashes[0] as $hash)
                {
                        if(isset($unique_hash[$hashes[0][0]]['id']) && isset($unique_hash[$hash]['id'])){
                            $link[]=array("source"=> $unique_hash[$hashes[0][0]]['id'],
                                "target" => $unique_hash[$hash]['id'],
                                "weight" => $unique_hash[$hashes[0][0]]['count'],
                                "group" => $unique_hash[$hashes[0][0]]['id']);
                        }

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

        
        $this->asJson($output);
    }


    public function dashboard_json_content(){
        $content = new Content();
        $res=$content->getStats();
        $this->asJson($res);
    }

    public function dashboard_json_user(){
        $user = new User();
        $res=$user->getStats();
        $this->asJson($res);
    }
}
