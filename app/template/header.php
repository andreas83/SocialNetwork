<html>
    <head>
        <title><?php echo $title; ?></title>


        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="Images and Videos to kill some time - mostly funny cat pictures from the internet" />
        <meta name="keywords" content="gifs,webm,fun,lol,omg">

    
    </head>
    <body class="<?php echo $scope; ?>">
    <top-menu><nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">

                <a class="navbar-brand" href="/public/stream/">
                    Share some <span class="glyphicon glyphicon-heart red"></span>
                </a>
                

                <form id="search" class="navbar-form navbar-left" method="post" role="search">
                    <div class="form-group">
                        <input  type="text" class="form-control" placeholder="#hash">
                        <ul class="searchresult">
                        </ul>
                    </div>
                    <button type="submit" class="btn btn-default hidden-xs">Search</button>
                </form>
                

            </div>
        </nav>
    </top-menu>
    <div class="space visible-xs"></div>


