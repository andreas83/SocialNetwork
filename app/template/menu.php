<?php
use SocialNetwork\app\controller\UserController;
use SocialNetwork\app\lib\Config;
use SocialNetwork\app\lib\Helper;
?>


<div class="col-md-2 col-sm-3 col-xs-12 animated bounceInLeft hidden-xs menuLeft">
    
        <div class="user-box ">

            
            <?php
            if (SocialNetwork\app\lib\Helper::isUser()): 
                if(isset($_SESSION['user_settings']))
                    $user_settings = json_decode($_SESSION['user_settings']);

                ?>
                <?php
                if(isset($user_settings->profile_picture)){
                ?>
                
                <a class="" href="/">
                    <img class=" img-responsive" src="<?php echo  (isset($user_settings->profile_picture) ? Config::get('upload_address') .$user_settings->profile_picture : ""); ?>">
                </a>
                <?php } ?>
                <a class="btn btn-success col-xs-12 col-sm-12 col-md-12" href="/my/settings/"><?php echo _('Settings') ?></a>
            <?php endif; ?>
        </div>
        
        <?php if (!Helper::isUser()): ?>
        <div class="left-nav ">
                <a class="btn btn-success col-xs-12 col-sm-12 col-md-12" href="/user/register/  "><?php echo _('Join us'); ?></a>
        </div>
        <?php endif; ?>
        <?php if (!Helper::isUser() && Config::get("facebook_auth")): ?>
        
        <a href='<?php echo UserController::getFBLoginURL(); ?>' class="col-xs-12 visible-xs btn" id="fblogin"><i class="fa fa-facebook"></i> Facebook</a>
        
        <?php endif; ?>
    
        <?php if (!Helper::isUser() && Config::get("google_auth") ): ?>
            <a href='<?php echo UserController::getGLoginURL(); ?>' class="col-xs-12 visible-xs btn" id="glogin"><i class="fa fa-google-plus"></i> Google</a>
        <?php endif; ?>
        <div class="left-nav hidden-xs">
            <button class=" col-sm-12 col-md-12 btn btn-info" id="next">(R)andom Post</button>
        </div>
        <div class="left-nav ">
            <a href="/clusters/" class="col-xs-12 col-sm-12 col-md-12 btn " id="cluster" >Cluster</a>
        </div>
        <div class="left-nav ">
            <a href="/help/" class="col-xs-12 col-sm-12 col-md-12 btn btn-warning" >API Documentation </a>
        </div>
        <?php if (Helper::isUser() ): ?>
        <div class="left-nav">
            
            <a href='/user/logout/' class="col-xs-12 col-sm-12 col-md-12 btn btn-danger"><?php echo _("Logout"); ?></a>
            
        </div>
        <?php endif; ?>
        
        <div class="hashtag">
            <h3>Trending</h3>
            <ul>
                <?php
                foreach($popularhashtags as $hashtag)
                {
                    echo "<li><a href=\"/hash/".$hashtag->hashtag."\">#".$hashtag->hashtag."</a></li>";
                }
                ?>
            </ul>
        </div>
    
        <div class="hashtag">
            <h3>Random</h3>
            <ul>
                <?php
                foreach($randomhashtags as $hashtag)
                {
                    echo "<li><a href=\"/hash/".$hashtag->hashtag."\">#".$hashtag->hashtag."</a></li>";
                }
                ?>
            </ul>
        </div>
        
</div>