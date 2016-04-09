<?php

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
        if(Helper::isUser())
            $res=$group->getGroups(Helper::getUserID());
        
        
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
        $group = new Groups;
        $group->addToGroup($request['group_id'], $request['user_id']);
        
    }
    
    /**
     * Remove From Group
     */
    function removeFromGroup($request){
         $group = new Groups;
    }
}
