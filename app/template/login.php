<?php
use SocialNetwork\app\controller\UserController;
use SocialNetwork\app\lib\Config;
use SocialNetwork\app\lib\Helper;
?>


<form id="loginform" class="<?php
echo(isset($scope) && strpos($scope, "login") === false ? "hide" : ""); ?>"
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
    
    <button tabindex="3" class="col-md-6 btn-success btn btn-lg"><?php echo _("SignIn!"); ?></button>
    <button class="col-md-6  btn-info btn btn-lg toggleform"><?php echo _("Need an account?"); ?></button>
    <?php if (!Helper::isUser() && Config::get("facebook_auth")==true): ?>
    <a href='<?php echo UserController::getFBLoginURL(); ?>' class="col-xs-12 col-sm-12 col-md-12 btn btn-lg" id="fblogin">Facebook</a>
    <?php endif; ?>
</form>

<form id="registerform" class="<?php echo(isset($scope) && strpos($scope, "register") === false ? "hide" : ""); ?>"
      action="/user/register/" method="post">

    <div class="form-group">
        <label for="">
            <?php
                echo(isset($error['nick']) ? $error['nick'] : _("nickname"));
            ?>
        </label>
        <input type="text" value="<?php
                echo(isset($error['nick']) ? "": $_POST['nick']);
            ?>" name="nick" class="form-control" id="" placeholder="<?php echo _("chuck norris"); ?>" />

    </div>
    <div class="form-group">
        <label for="">
            <?php
                echo(isset($error['mail']) ? $error['mail'] : _("mail"));
            ?>
        </label>
        <input type="email" value="<?php echo(isset($error['mail']) ? "" : $_POST['mail']); ?>" name="mail" class="form-control" id="" placeholder="<?php echo _("chuck@norris.com"); ?>" />
        
    </div>
    <div class="form-group">
        <label for=""><?php  echo(isset($error['pass']) ? $error['pass'] : _("password"));   ?></label>
        <input type="password" name="pass" class="form-control" id="" placeholder="<?php echo _("1234"); ?>" />
    </div>
    <input type="submit" class="col-md-4  btn-warning  btn btn-lg" value="<?php echo _("SignUp !"); ?>" />
    <button class="btn-info btn btn-lg col-md-8 toggleform"><?php echo _("Already have an account?"); ?></button>
    <?php if (!Helper::isUser() && Config::get("facebook_auth")==true): ?>
    <a href='<?php echo UserController::getFBLoginURL(); ?>' class="col-xs-12 col-sm-12 col-md-12 btn btn-lg" id="fblogin">Facebook</a>
    <?php endif; ?>
    
    
    
</form>

<form id="password_reset_form" class="<?php echo(isset($scope) && strpos($scope, "password_reset_form") === false ? "hide" : ""); ?>"
      action="/user/password/reset/" method="post">
      <?php
      if(isset($status))
      {
          if($status=="new_pw_send")
            echo "<h2><label>"._("New Password was send")."</label></h2>"; 
          if($status=="confirm")
            echo "<h2><label>"._("Pls check your mail")."</label></h2>"; 
      }else{
      
      ?>
        
        <div class="form-group">
        <label for="">
            <?php
                echo(isset($error['pw_error']) ? $error['pw_error'] : _("mail"));
            ?>
        </label>
        <input type="email" value="<?php echo(isset($error['mail']) ? "" : $_POST['mail']); ?>" name="mail" class="form-control" id="" placeholder="<?php echo _("chuck@norris.com"); ?>" />
        
    </div>
    <input type="submit" class="btn-info btn btn-lg col-md-12 "value="<?php echo _("Reset Password"); ?>">
    <?php } ?>
</form>
<?php
if(!isset($status))
{?>
<a href="#" id="passsword_reset"><?php echo _("Password Reset"); ?></a>
<?php } ?>