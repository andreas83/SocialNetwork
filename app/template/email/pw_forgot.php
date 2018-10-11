<?php
use SocialNetwork\app\lib\Config;

?>

<html>
    <body>
        
        <p>Hello <?php echo $name; ?>,</p>

        <p>Your new password is : <?php echo $newpw; ?></p>
        <p> You can change your password at user settings.</p>
        <p>Login: <a href="<?php echo Config::get("address")."user/login/"; ?>"><?php echo Config::get("address")."user/login/"; ?></a></p>
        <br/>
        <br/>
        Happy Hacking
        <br/>
        Bot

    </body>
</html>