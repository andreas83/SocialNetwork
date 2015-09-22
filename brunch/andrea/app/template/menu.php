<div class="col-md-2">

    <div class="list-group">
        <?php if (!Helper::isUser()): ?>
            <a class="list-group-item" href="/"><?php echo _('Join us'); ?></a>
        <?php endif; ?>
        <?php if (Helper::isUser()): ?>
            <a class="list-group-item" href="/"><?php if (isset($user_settings->profile_picture) && $user_settings->profile_picture) { ?>
                            <img class="img-responsive" src="<?php echo ($user_settings->profile_picture) ? Config::get("upload_path") . "$user_settings->profile_picture" : "no-profile.jpg"; ?>">
                        <?php } else { ?>
                            <div class="glyph-icon flaticon-user91"></div>
                        <?php } ?>
            </a>
            <a class="list-group-item" href="/my/settings/"><?php echo _('Settings') ?></a>
            <a class="list-group-item" href="/documentation/api/"><?php echo _('API') ?></a>
        <?php endif; ?>
    </div>


</div>