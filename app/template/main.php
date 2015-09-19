<?php
include("header.php");
?>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="jumbotron">
                    <h1><?php echo _("Hottest stuff from da Internerds"); ?></h1>

                    <p><?php echo _("From Hackers with "); ?> <span class="glyphicon glyphicon-heart red"></span></p>

                    <p>
                        <a class="btn btn-primary btn-lg" href="/public/stream/" role="button">
                            <?php echo _("Show me the Cats "); ?>
                        </a>
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <?php include("login.php"); ?>
            </div>
        </div>
    </div>
<?php

include("footer.php");