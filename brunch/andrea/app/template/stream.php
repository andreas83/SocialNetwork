<?php
include("header.php");

include("menu.php");
?>


    
<div class="row mainrow">
    <div class="col-md-8 col-sm-8 col-xs-12" id="stream">
        <?php if (Helper::isUser()) { ?>
            <div class="row ">
                <div class="col-md-8 stream-input">
                    <form method="post" enctype="multipart/form-data">

                            <textarea id="share_area" name="content" class="form-control"></textarea>

                            <div class="row preview www">

                                <p class="text-right">
                                    <button class="btn btn-info close">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </button>
                                </p>
                                <div class="col-md-3">
                                    <img class="img-responsive" src="" id="og_img" />
                                </div>
                                <div class="col-md-9">
                                    <h3 id="og_title"></h3>

                                    <p id="og_desc"></p>

                                </div>
                                <div class="col-md-12">
                                    <a href="" id="www_link"></a>
                                </div>
                            </div>
                            <div class="row preview img">
                                <div class="col-md-12">
                                    <p class="text-right">
                                        <button class="btn btn-info close">
                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        </button>
                                    </p>
                                    <img class="img-responsive" src="" id="preview_img" />
                                </div>

                            </div>
                            <div class="row preview upload">
                                <div class="col-md-12">
                                    <p class="text-right">
                                        <button class="btn btn-info close">
                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        </button>
                                    </p>
                                    <div id="uploadPreview"></div>
                                </div>

                            </div>
                            <div class="row preview video">
                                <div class="col-md-12">
                                    <p class="text-right">
                                        <button class="btn btn-info close">
                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        </button>
                                    </p>
                                    <div id="video_target" class="embed-responsive embed-responsive-16by9"></div>
                                </div>

                            </div>
                            <input type="file" id="img" multiple name="img[]" class="form-control" />
                            <select class="form-control">
                                <option><?php echo _('Public'); ?></option>
                                <option><?php echo  _('Private'); ?></option>
                                <option><?php echo  _('Friends'); ?></option>
                            </select>
                            <input type="hidden" name="metadata" id="metadata" />
                            <button class="btn btn-lg btn-info"><?php echo _('Share now!'); ?></button>
                        </form>
                    </div>
                </div>
            <?php } ?>

        <div class="row stream-row" data-permalink="<?php echo $permalink; ?>" data-hash="<?php echo (isset($hash) && !empty($hash) ? $hash : ""); ?>">
            <div class="stream col-md-12"></div>
        </div>
     
        </div>
    </div>
<?php

include("footer.php");
