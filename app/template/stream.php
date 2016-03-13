<?php
include("header.php");
include("menu.php");
?>    
<div class="col-md-7 col-sm-8 col-xs-12 ">
    
            
                <?php
                if(isset($show_share) && $show_share===true)
                { ?>
                        
                    <div id="ShareBox"></div>
                    
                <?php } ?>
        
        <div class="stream-row animated bounceInDown" 
             data-permalink="<?php echo (isset($permalink) ? $permalink : ""); ?>" 
             data-hash="<?php echo (isset($hash) && !empty($hash) ? $hash : ""); ?>"
             data-user="<?php echo (isset($user) && !empty($user) ? $user : ""); ?>"
             data-maxid="<?php echo (isset($maxid) && !empty($maxid) ? $maxid : ""); ?>"
             >
            <div class="stream col-md-11"></div>
        </div>
        <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
    </div>

<div class="col-md-3 hidden-sm hidden-xs">
    

    
    <ul class="list-group" id="notifications">
        
    </ul>
    
</div>
<?php

include("footer.php");
