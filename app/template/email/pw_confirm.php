<?php
    use app\lib\Config;
?>
<html>
    <body>
        
        <p>Hello <?php echo $name; ?>,</p>

        <p>someone is trying to reset your Password at <?php echo Config::get("address"); ?></p>

        <p>Please follow this link if you really like to reset your Password:</p>

        <a href="<?php echo $confirm_url; ?>"><?php echo $confirm_url; ?></a>

        <br/><br/>
        Best Regards

        <br/>
    </body>
</html>