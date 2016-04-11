<?php
namespace SocialNetwork\app\controller;
use SocialNetwork\app\lib\BaseController;
use SocialNetwork\app\lib\Helper;   
use SocialNetwork\app\model\Groups;


/**
 * Description of GroupController
 *
 * @author andreas
 */
class GroupController extends BaseController {
    
    /**
     * Create Group
     */
    function postGroup(){
        
    }
    
    /**
     * Get Group
     */
    function getGroup(){
        $group = new Groups;
        
        $res=$group->getGroups(Helper::getUserID());
        
        
        
        $this->asJson($res);
        
    }   
    
    /**
     * Delete Group
     */
    function deleteGroup(){
        
    }
    
    /**
     * Update Group
     */
    function putGroup(){}
    
    /**
     * Add User to Group
     */
    function addToGroup($request){
        $user_id=(int)$request['user_id'];
        $group_id=(int)$request['group_id'];
        $group = new Groups;
        $group->addToGroup($group_id , $user_id);
        
        $this->getGroup();
    }
    
    /**
     * Remove From Group
     */
    function removeFromGroup($request){
         $group = new Groups;
    }
}
