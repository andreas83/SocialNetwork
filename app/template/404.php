<?php
use SocialNetwork\app\lib\Config;

include("header.php");
include("menu.php");
?>    
<div class="col-md-7 col-sm-8 col-xs-12 ">


    <div class="row">

        <div class="col-md-12">
            <h1>Ohoohoo - we are so sorry</h1>
            <p>The page you are looking for cannot be found</p>
            
            
            <?php
            foreach ($stream as $data){
                $img=json_decode($data->media);
                $img->url=Config::get("upload_address").$img->url;
                echo "<a href=\"/permalink/".$data->id."\"><img  class=\"img-thumbnail\"  src=\"$img->url\"></a>";
            }
            ?> 
            <br/>
            <b>here you have some random kitten instead</b>
        </div>
    </div>
</div>


<?php
include("footer.php");
?>