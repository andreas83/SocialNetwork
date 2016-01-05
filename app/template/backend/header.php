<!DOCTYPE html>
<html lang="de">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    

    <?php
    foreach($header as $script)
        echo $script;
    ?>
    
    <script>
    $(function () {
    $('.confirm').on('click', function(e){
        e.preventDefault();
        var src= $(this).attr("href");
        $('#confirm').modal({ backdrop: 'static', keyboard: false })
            .one('click', '#delete', function (e) {
                window.location.href=src;
            });
    });
    });
    </script>

</head>
<body >

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">
        
        
      </a>
        <ul class="nav navbar-nav">
            <?php
                foreach ($BackendModels as $BackendModel){
            ?>
            <li><a href="/backend/<?php echo $BackendModel; ?>/list/" class=""><?php echo $BackendModel; ?></a></li>
            <?php } ?>
        </ul>
    </div>
  </div>
</nav>
<div class="container-fluid">

    <div class="row">
        <div class="col-md-1">
        <?php 
        #include("menu.php"); 
        ?>

        </div>
        <div class="col-md-10">
