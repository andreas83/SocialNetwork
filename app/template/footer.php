<?php
use SocialNetwork\app\lib\Config;
use SocialNetwork\app\lib\Helper;

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
<script async src="/public/js/app.js"></script>


<?php

if (isset($user) && !empty($user)) {
    ?><style id="custom_css"></style><?php
}

foreach ($footer as $script) {
    echo $script;
}
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-73697944-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
