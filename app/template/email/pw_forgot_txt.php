<?php
use SocialNetwork\app\lib\Config;
?>

Hello <?php echo $name; ?>,

Your new password is : <?php echo $newpw; ?>
You can change your password at user settings.

Login: <?php echo Config::get("address")."user/login/"; ?>


Happy Hacking

Bot
