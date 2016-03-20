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

<div class="col-md-3 hidden-sm hidden-xs animated bounceInRight">
    <?php if (!Helper::isUser() && (Config::get("facebook_auth")  && Config::get("google_auth"))): ?>
    <div class="signinBox">
        
        <?php if (!Helper::isUser() && Config::get("facebook_auth")): ?>
        <a href='<?php echo UserController::getFBLoginURL(); ?>' class="col-xs-12 col-sm-12 col-md-12 btn" id="fblogin"><i class="fa fa-facebook"></i> Facebook</a>
        <?php endif; ?>
        
        <?php if (!Helper::isUser() && Config::get("google_auth") ): ?>
            <a href='<?php echo UserController::getGLoginURL(); ?>' class="col-xs-12 col-sm-12 col-md-12 btn" id="glogin"><i class="fa fa-google-plus"></i> Google</a>
        <?php endif; ?>
            
    </div>
    <?php endif; ?>
    
    <div id="NotificationBox"></div>
    
</div>
<?php

include("footer.php");
