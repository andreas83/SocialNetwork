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
?>
<?php
foreach ($header as $script) {
    echo $script;
}
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
<link rel="stylesheet" href="/public/css/scss.php/dmdn.scss" />

<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.8.0/highlight.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://fb.me/react-0.13.3.js"></script>
<script src="/public/js/main.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="/public/js/react.js"></script>

<link rel="stylesheet" href="/public/css/code/railscasts.css">

<?php
if (isset($user_settings->custom_css)) {
        echo "<style>" . $user_settings->custom_css . "</style>";
}
?>
</body>
</html>