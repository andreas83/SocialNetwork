<form id="loginform" class="<?php echo(isset($scope) && strpos($scope, "login") === false ? "hide" : ""); ?>"
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
    
    <button tabindex="3" class="btn-success btn btn-lg"><?php echo _("SignIn!"); ?></button>
    <button class="btn-info btn btn-lg toggleform"><?php echo _("Need an account?"); ?></button>
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
    <input type="submit" class="btn-warning  btn btn-lg" value="<?php echo _("SignUp !"); ?>" />
    <button class="btn-info btn btn-lg toggleform"><?php echo _("Already have an account?"); ?></button>
    
</form>