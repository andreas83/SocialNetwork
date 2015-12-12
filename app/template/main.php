<?php
include("header.php");
?>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="jumbotron">
                    <h3><?php echo _("Newest stuff from da Internerds!"); ?></h3>
                    <?php
                    foreach ($stream as $data){
                        $img=json_decode($data->media);
                        $img->url=Config::get("upload_address").$img->url;
                        echo "<a href=\"/permalink/".$data->id."\"><img width=\"100\" height=\"100\" class=\"img-thumbnail\"  src=\"$img->url\"></a>";
                    }
                    ?> 
                    <p><?php echo _("From Hackers with "); ?> <span class="glyphicon glyphicon-heart red"></span></p>
                    
                    <p>
                        <a class="btn btn-primary btn-lg" href="/public/stream/" role="button">
                            <?php echo _("Show me more Cats "); ?>
                        </a>
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <?php include("login.php"); ?>
            </div>
        </div>
    </div>
<?php

include("footer.php");