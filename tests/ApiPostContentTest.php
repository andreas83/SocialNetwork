<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiPostContentTest extends TestCase
{
    
    function __construct() {
       
    }
            
    
    /**
     * Post Youtube video as authorized user
     */
    function testPostVideoContent()
    {
        
        $user = factory('App\User')->create();

        $this->actingAs($user)->post('/api/content', ["data" => $this->getVideo()])
             ->seeJson([
                 'created' => true,
             ]);
    }
    
    /**
     * Post something as unauthorized user
     */
    function testPostAsUnauthorizedUserContent(){
        $resp=$this->call('post', '/api/content', ["data" => $this->getVideo()]);
        $this->assertEquals(401, $resp->status());
    }
    
    /**
     * Post something without data parameter
     */
    function testPostWithoutDataParameter(){
        $user = factory('App\User')->create();

        $this->actingAs($user)->post('/api/content', [])
             ->seeJson([
                 'status' => "Parameter data is missing",
             ]);
        
    }
    
    
    function testPostWithInvalidDataParameter(){
        $user = factory('App\User')->create();

        $this->actingAs($user)->post('/api/content', ["data" => $this->getInvalid()])
             ->seeJson([
                 'error' => "Data is malformed",
             ]);
        
    }
    
    function getInvalid()
    {
        $class=new stdClass;
        $class->type="woho";
        $class->url="https://www.youtube.com/watch?v=cmUFJmFoiDUdas";
        $class->nope=true;
        
        return json_encode($class);
    }
    
    function getVideo()
    {
        $class=new stdClass;
        $class->type="video";
        $class->url="https://www.youtube.com/watch?v=cmUFJmFoiDUdas";
        
        return json_encode($class);
    }
    
    function getWww()
    {
        $class=new stdClass;
        $class->type="www";
        $class->url="https://de.wikipedia.org/wiki/Dalai_Lama";
        $class->text="#karma";
        
        return json_encode($class);
    }
    
    function getImg()
    {
        $class=new stdClass;
        $class->type="img";
        $class->url="https://upload.wikimedia.org/wikipedia/commons/7/74/Imprint_of_%27Sonam_Gyatso%27s%2C_3rd_Dalai_Lama%27s%2C_seal.jpg";
        $class->text="#karma";
        
        return json_encode($class);
    }
    
    function getArticle(){
        $class=new stdClass;
        $class->type="article";
        $class->title="Title";
        $class->description="Title";
        $class->text="#karma";
        
        return json_encode($class);
    }
    
    function getGallery()
    {
        $class=new stdClass;
        $class->type="gallery";
        $class->title="title";
        $class->text="#karma";
        $class->img[0]["url"]="https://upload.wikimedia.org/wikipedia/commons/7/74/Imprint_of_%27Sonam_Gyatso%27s%2C_3rd_Dalai_Lama%27s%2C_seal.jpg";
        $class->img[1]["url"]="https://upload.wikimedia.org/wikipedia/commons/7/74/Imprint_of_%27Sonam_Gyatso%27s%2C_3rd_Dalai_Lama%27s%2C_seal.jpg";
        
        return json_encode($class);
    }
    
}