<form id="loginform" class="<?php echo(isset($scope) && strpos($scope, "login") === false ? "hide" : ""); ?>"
      action="/user/login/" method="post">

    <div class="form-group">
        <label for="nickname">
            <?php
            echo(isset($error) ? $error['login'] : _("Your login please"));
            ?>
        </label>
        <input name="mail" type="mail" class="form-control" id="nickname"
               placeholder="<?php echo _("email is okay"); ?>" />
    </div>
    <div class="form-group">
        <label for="password"><?php echo _('your password please') ?></label>
        <input name="pass" type="text" class="form-control" id="password" placeholder="<?php echo _("1234"); ?>" />
    </div>

    <button class="btn-success btn btn-lg"><?php echo _("Come to the darkside"); ?></button>
    
</form>

<form id="registerform" class="<?php echo(isset($scope) && strpos($scope, "register") === false ? "hide" : ""); ?>"
      action="/user/register/" method="post">

    <div class="form-group">
        <label for="">
            <?php
                echo(isset($error['nick']) ? $error['nick'] : _("Your nickname please"));
            ?>
        </label>
        <input type="text" name="nick" class="form-control" id="" placeholder="<?php echo _("chuck noris"); ?>" />

    </div>
    <div class="form-group">
        <label for="">
            <?php
                echo(isset($error['mail']) ? $error['mail'] : _("Your mail please"));
            ?>
        </label>
        <input type="email" name="mail" class="form-control" id="" placeholder="<?php echo _("chuck@noris.com"); ?>" />

    </div>
    <div class="form-group">
        <label for=""><?php echo _('your password please') ?></label>
        <input type="password" name="pass" class="form-control" id="" placeholder="<?php echo _("1234"); ?>" />
    </div>
    <input type="submit" class="btn-warning  btn btn-lg" value="<?php echo _("Signup Now"); ?>" />
    <button class="btn-info btn btn-lg toggleform"><?php echo _("Already have an account?"); ?></button>
    
</form>