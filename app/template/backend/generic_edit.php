<?php
include("header.php");
?>

    <form class="form-horizontal" enctype="multipart/form-data"  method="post">
        
        <?php
        
        foreach ($configuration->editable as $propertie){
            
            
        ?>
        <div class="form-group">
            <label for="<?php echo $propertie; ?>" class="col-sm-2 control-label"><?php echo $propertie; ?></label>
            <div class="col-sm-10">
                <input type="text" name="<?php echo $propertie; ?>" class="form-control"  placeholder="<?php echo $propertie; ?>" value="<?php echo (isset($model->$propertie) ? htmlentities($model->$propertie) : ""); ?>">
            </div>
        </div>
        <?php } ?>
        
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" name="<?php echo $model->getPrimary(); ?>" value="<?php echo (isset($model->{$model->getPrimary()}) ? $model->{$model->getPrimary()} : ""); ?>">
                <button type="submit" class="btn btn-success"><span class=" glyphicon glyphicon-ok-sign" aria-hidden="true"></span>  <?php echo _("Save"); ?></button>
                <a href="/backend/<?php echo $modelName; ?>/list/" class="btn btn-warning"><span class=" glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  <?php echo _("Abort"); ?></a>
            </div>
        </div>
    </form>
<?php
include("footer.php");
?>