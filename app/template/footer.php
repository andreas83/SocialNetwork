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

<link rel="stylesheet" href="/vendor/components/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="/public/css/scss.php/dmdn.scss" />

<script src="/vendor/components/highlightjs/highlight.pack.min.js"></script>
<script src="/vendor/components/jquery/jquery.min.js"></script>
<script src="https://fb.me/react-0.14.7.min.js"></script>
<script src="https://fb.me/react-dom-0.14.7.min.js"></script>
<script src="/public/js/main.js"></script>
<script src="/vendor/components/bootstrap/js/bootstrap.min.js"></script>
<script src="/public/js/react.js"></script>

<link rel="stylesheet" href="/vendor/components/highlightjs/styles/railscasts.css">

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