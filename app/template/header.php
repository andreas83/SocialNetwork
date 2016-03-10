<html>
    <head>
        <title><?php echo $title; ?></title>


        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="<?php echo $description; ?>" />
        <meta name="keywords" content="<?php echo $keyword; ?>">
        <?php
        foreach ($header as $script) {
            echo $script;
        }
        ?>
    </head>
    <body class="<?php echo (isset($scope) ? $scope : ""); ?>">
    <top-menu>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <a class="navbar-brand " href="/public/stream/">
                            Share some <span class="glyphicon glyphicon-heart red"></span>
                        </a>
                    </div>
                    <div class="col-md-4 col-xs-6">
                        <div id="SearchBox"></div>
                    </div>
                    <div class="col-md-2 hidden-xs hidden-sm">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="https://github.com/andreas83/SocialNetwork" class="navbar-default"><span class="glyphicon glyphicon-grain "></span> Fork me on Github</a></li>
                        </ul>
                    </div>
                </div> 
            </div>
        </nav>
    </top-menu>
   