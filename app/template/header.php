<html>
    <head>
        <title><?php echo $title; ?></title>


        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://fb.me/react-0.13.3.js"></script>
        <script src="https://fb.me/JSXTransformer-0.13.3.js"></script>
        <script src="/public/js/main.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />


        <link rel="stylesheet" href="/public/css/scss.php/dmdn.scss" />


        <?php
        if (Helper::isUser()) {
            echo "<script>"
            . "var user_id=" . $_SESSION['login']  . ";"
            . "var user_settings=" . $_SESSION['user_settings']
            . "</script>";
            $user_settings = json_decode($_SESSION['user_settings']);

            if (isset($user_settings->custom_css)) {
                echo "<style>" . $user_settings->custom_css . "</style>";
            }
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
    </head>
    <body class="<?php echo $scope; ?>">
    <top-menu><nav class="navbar navbar-default navbar-static-top">
            <div class="container">

                <a class="navbar-brand" href="/public/stream/">
                    Share some            <span class="glyphicon glyphicon-heart red"></span>
                </a>

                <form class="navbar-form navbar-left ng-pristine ng-valid" method="post" role="search">
                    <div class="form-group">
                        <input ng-model="searchTerm" type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="#hash">
                    </div>
                    <button type="submit" class="btn btn-default">Search</button>
                </form>

            </div>
        </nav>
    </top-menu>


