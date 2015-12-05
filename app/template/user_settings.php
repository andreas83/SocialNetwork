<?php
include("header.php");
include("menu.php");
?>

<div class="col-md-10">
    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <label><?php echo _("Profile Picture"); ?></label>
                <a href="#" class="thumbnail">
                    <?php
                    if ($settings->profile_picture != "") {
                        $profile_url = Config::get("upload_path") . $settings->profile_picture;
                    } else {
                        $profile_url = "https://placeholdit.imgix.net/~text?txtsize=33&txt=180×180&w=180&h=180";
                    }
                    ?>
                    <img src="<?php echo $profile_url; ?>" alt="...">
                </a>
                <input name="picture" class="form-control" type="file">
            </div>

            <div class="col-xs-6 col-md-3">
                <label for="nick"><?php echo _("Nickname") ?></label>
                <input type="text" id="nick" name="nick" class="form-control" value="<?php echo $user->name; ?>">
                <?php
                if (isset($error['nick'])) {
                    echo "<b>" . $error['nick'] . "</b>";
                }
                ?>
            </div>

            <div class="col-xs-6 col-md-3">
                <label for="nsfw"><?php echo _('Show NSFW Content') ?></label>
                <select id="nsfw" name="nsfw" class="form-control">
                    <option <?php echo($settings->show_nsfw == "yes" ? "selected" : "") ?> value="yes">
                        <?php echo _("Yepp I'm in"); ?>
                    </option>
                    <option <?php echo($settings->show_nsfw == "no" ? "selected" : "") ?> value="no">
                        <?php echo _("Nope"); ?>
                    </option>
                </select>
            </div>

            
            
            <div class="col-xs-6 col-md-3">
                <label for="autoplay"><?php echo _("Autoplay Videos"); ?></label>
                <select id="autoplay" name="autoplay" class="form-control">
                    <option <?php echo($settings->autoplay == "yes" ? "selected" : "") ?> value="yes">
                        <?php echo _("Yep please"); ?>
                    </option>
                    <option <?php echo($settings->autoplay == "no" ? "selected" : "") ?> value="no">
                        <?php echo _("Nope"); ?>
                    </option>
                </select>
            </div>

            <div class="col-xs-6 col-md-3">
                <label for="mute-videos"><?php echo _("Mute Videos"); ?></label>
                <select id="mute-videos" name="mute_video" class="form-control">
                    <option <?php echo($settings->mute_video == "yes" ? "selected" : "") ?> value="yes">
                        <?php echo _("Yep please"); ?></option>
                    <option <?php echo($settings->mute_video == "no" ? "selected" : "") ?> value="no">
                        <?php echo _("Nope"); ?>
                    </option>
                </select>
            </div>
            
            <div class="col-xs-6 col-md-6">
                <label for="api-key"><?php echo _("API Key") ?></label>
                <input id="api-key" class="form-control" type="text" disabled="" value="<?php echo $user->api_key; ?>">
            </div>
        
            <div class="col-xs-8 col-md-8">
                <label for="custom-css"><?php echo _("CSS for Profile page"); ?></label>
                <textarea id="custom_css_input" name="custom_css" class="form-control" rows="10"><?php echo (isset($settings->custom_css) ? $settings->custom_css: ""); ?></textarea>
            </div>
        </div>
        
        <input type="submit" class="btn btn-lg btn-warning " value="Save" />
    </form>
</div>


<?php

include("footer.php");