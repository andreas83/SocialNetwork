
<div class="col-md-2 col-sm-3 col-xs-12 animated bounceInLeft">
    
        <div class="list-group user-box ">

            <?php if (!Helper::isUser()): ?>
                <a class="btn btn-success col-xs-12 col-sm-12 col-md-12" href="/user/register/  "><?php echo _('Join us'); ?></a>
            <?php endif; ?>
            <?php if (Helper::isUser()): 
                if(isset($_SESSION['user_settings']))
                    $user_settings = json_decode($_SESSION['user_settings']);

                ?>
                <?php
                if(isset($user_settings->profile_picture)){
                ?>
                
                <a class="" href="/">
                    <img class="hidden-xs img-responsive" src="<?php echo  (isset($user_settings->profile_picture) ? Config::get('upload_address') .$user_settings->profile_picture : ""); ?>">
                </a>
                <?php } ?>
                <a class="btn btn-success hidden-xs col-sm-12 col-md-12" href="/my/settings/"><?php echo _('Settings') ?></a>
            <?php endif; ?>
        </div>
        <?php if (!Helper::isUser() && Config::get("facebook_auth")==true): ?>
        <div class="left-nav hidden-xs">
            
            <a href='<?php echo UserController::getFBLoginURL(); ?>' class="col-xs-12 col-sm-12 col-md-12 btn" id="fblogin">Facebook</a>
            
        </div>
        
        <?php endif; ?>
        <div class="left-nav hidden-xs">
            <button class="col-xs-12 col-sm-12 col-md-12 btn btn-info" id="next">(R)andom Post</button>
        </div>
        <div class="left-nav hidden-xs">
            <a href="/help/" class="col-xs-12 col-sm-12 col-md-12 btn btn-warning" >API Documentation</a>
        </div>
        <?php if (Helper::isUser() ): ?>
        <div class="left-nav hidden-xs">
            
            <a href='/user/logout/' class="col-xs-12 col-sm-12 col-md-12 btn btn-danger"><?php echo _("Logout"); ?></a>
            
        </div>
        <?php endif; ?>
        
</div>