<?php
use SocialNetwork\app\controller\UserController;
use SocialNetwork\app\lib\Config;
use SocialNetwork\app\lib\Helper;

include("header.php");
?>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            
        </div>

        <div class="col-md-4">
            <form id="loginform" 
                  action="/user/login/" method="post">

                <div class="form-group">
                    <label for="nickname">
                        <?php
                        echo(isset($error['login']) ? $error['login'] : _("Your login please"));
                        ?>
                    </label>
                    <input tabindex="1" name="mail" type="mail" class="form-control" id="nickname"
                           placeholder="<?php echo _("email is okay"); ?>" />
                </div>
                <div class="form-group">
                    <label for="password"><?php echo _('your password please') ?></label>
                    <input tabindex="2" name="pass" type="password" class="form-control" id="password" placeholder="<?php echo _("1234"); ?>" />
                </div>
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <button tabindex="3" class="col-md-6 col-xs-5 btn-success btn btn-lg"><?php echo _("LOGIN"); ?></button>
                    </div>
                    <div class="col-md-2 col-xs-1">
                    <?php if (!Helper::isUser() && Config::get("facebook_auth") == true): ?>
                        <a href='<?php echo UserController::getFBLoginURL(); ?>' class="col-xs-12 col-sm-12 col-md-4 btn btn-lg btn-circle " id="fblogin">FB</a>
                    <?php endif; ?>
                    </div>
                    <div class="col-md-2 col-xs-1">
                    <?php if (!Helper::isUser() && Config::get("google_auth") == true): ?>
                        <a href='<?php echo UserController::getGLoginURL(); ?>' class="col-xs-12 col-sm-12 col-md-4 btn btn-lg btn-circle " id="glogin">G+</a>
                    <?php endif; ?>
                    </div>
                </div>
                <?php
                if (!isset($status)) {
                    ?>
                <a href="/user/password/reset/" id="passsword_reset"><?php echo _("Password Reset"); ?></a>
            <?php } ?>
               
            </form>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-4">
                
            <?php
            $nick=(isset($_POST['nick']) ? $_POST['nick'] : "" );
            $mail=(isset($_POST['mail']) && !isset($error['login']) ? $_POST['mail'] : "" );
            ?>
                
            <form id="registerform" 
                  action="/user/register/" method="post">

                <div class="form-group">
                    <label for="">
                        <?php
                        echo(isset($error['nick']) ? $error['nick'] : _("nickname"));
                        ?>
                    </label>
                    <input type="text" value="<?php
                    echo(isset($error) && isset($error['nick']) ? "" : $nick);
                    ?>" name="nick" class="form-control" id="" placeholder="<?php echo _("chuck norris"); ?>" />

                </div>
                <div class="form-group">
                    <label for="">
                        <?php
                        echo(isset($error['mail']) ? $error['mail'] : _("mail"));
                        ?>
                    </label>
                    <input type="email" value="<?php echo(isset($error['mail']) ? "" : $mail); ?>" name="mail" class="form-control" id="" placeholder="<?php echo _("chuck@norris.com"); ?>" />

                </div>
                <div class="form-group">
                    <label for=""><?php echo(isset($error) && isset($error['pass']) ? $error['pass'] : _("password")); ?></label>
                    <input type="password" name="pass" class="form-control" id="" placeholder="<?php echo _("1234"); ?>" />
                </div>
                <input type="submit" class="col-md-4  btn-warning  btn btn-lg" value="<?php echo _("Register"); ?>" />
            </form>
        </div>
    </div>
</div>
<?php
include("footer.php");
