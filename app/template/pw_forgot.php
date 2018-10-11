<?php
include("header.php");
$mail=(isset($_POST['mail']) && filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) ? $_POST['mail'] : "");
                
?>
<div class="container">
    <div class="row">
        
        <div class="col-md-12">
            
            
            <form id="password_reset_form" action="/user/password/reset/" method="post">
                <?php
                if (isset($status)) {
                    if ($status == "new_pw_send") {
                        echo "<h2><label>" . _("New Password was send") . "</label></h2>";
                    }
                    if ($status == "confirm") {
                        echo "<h2><label>" . _("Pls check your mail") . "</label></h2>";
                    }
                } else {
                    ?>

              <div class="form-group">
                  <label for="">
                  <?php
                  echo(isset($error['pw_error']) ? $error['pw_error'] : _("mail")); ?>
                  </label>
                  <input type="email" value="<?php echo(isset($error['mail']) ? "" : $mail); ?>" name="mail" class="form-control" id="" placeholder="<?php echo _("chuck@norris.com"); ?>" />
                  <input type="submit" class="col-md-12  btn-warning  btn btn-lg" value="<?php echo _("Reset Password"); ?>" />
              </div>

              <?php
                } ?>
            </form>
            </div>
        </div>
</div>
<?php
include("footer.php");
