<?php
use SocialNetwork\app\lib\Config;
?>
Hello <?php echo $name; ?>,

someone is trying to reset your Password at <?php echo Config::get("address"); ?>

Please follow this link if you really like to reset your Password:

<?php echo $confirm_url; ?>


Best Regards

