
<div class="col-md-2 col-sm-2 col-xs-12">

    <div class="list-group">
        <?php if (!Helper::isUser()): ?>
            <a class="btn btn-success col-xs-12 col-sm-12 col-md-12" href="/"><?php echo _('Join us'); ?></a>
        <?php endif; ?>
        <?php if (Helper::isUser()): ?>
            <a class="" href="/"><?php if (isset($user_settings->profile_picture) && $user_settings->profile_picture) { ?>
                            <img class="hidden-xs img-responsive" src="<?php echo ($user_settings->profile_picture) ? Config::get("upload_path") . "$user_settings->profile_picture" : "no-profile.jpg"; ?>">
                        <?php } else { ?>
                            <div class="glyph-icon flaticon-user91"></div>
                        <?php } ?>
            </a>
            <a class="btn btn-success col-xs-12 col-sm-12 col-md-12" href="/my/settings/"><?php echo _('Settings') ?></a>
            
        <?php endif; ?>
    </div>


</div>