<?php
if (Helper::isUser()) {
    echo "<script>"
    . "var user_id=" . $_SESSION['login']  . ";"
    . "var user_settings=" . $_SESSION['user_settings']
    . "</script>";
    
    
} else {
    echo "<script>var user_id=0;</script>";
    echo "<script>var user_settings=false;</script>";
}
echo "<script>var upload_address=\"".Config::get("upload_address")."\";  </script>";
echo "<script>var notification_server=\"".Config::get("notification_server")."\";  </script>";
?>
<link rel="stylesheet" href="/public/css/style.min.css">
<script src="/public/js/app.js"></script>


<?php

if( isset($user) && !empty($user))
{
    ?><style id="custom_css"></style><?php
    
}

foreach ($footer as $script) {
    echo $script;
}
?>
</body>
</html>
