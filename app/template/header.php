<html>
    <head>
        <title><?php echo isset($title) ? $title : ''; ?></title>


        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="<?php echo isset($description) ? $description : ''; ?>" />
        <meta name="keywords" content="<?php echo isset($keyword) ? $keyword : ''; ?>">
        <?php
        if (!empty($header)) {
            foreach ($header as $script) {
                echo $script;
            }
        }
        ?>
    </head>
    <body class="<?php echo (isset($scope) ? $scope : ""); ?>">
     <top-menu>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <a class="navbar-brand visible-xs " id="MobileMenu" href="" ><span class="fa fa-bars"></span> </a>
                        
                        <a class="navbar-brand hidden-xs" href="/public/stream/" ><span class="fa fa-home"></span> Home</a>
                        
                        
                    </div>
                    <div class="col-md-4 col-xs-6">
                        <div id="SearchBox"></div>
                        
                        <ul class="nav navbar-nav hidden-xs navbar-right">
                            <li><a href="#" class="navbar-default showChat"><span class="glyphicon glyphicon-envelope "></span></a></li>
                        </ul>
                        
                        
                    </div>
                    <div class="col-md-2 hidden-xs hidden-sm">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="https://github.com/andreas83/SocialNetwork" class="navbar-default"><span class="fa fa-github "></span> Fork me on Github</a></li>
                        </ul>
                        
                    </div>
                </div>
                <div class="row">
                    
                    <div id="ChatBox" class="hide"></div>
                </div>
            </div>
        </nav>
    </top-menu>
